<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ParcelPaymentMethod;
use App\Enums\ParcelStatus;
use App\Enums\Status;
use App\Enums\UserType;
use App\Exports\ParcelSampleExport;
use App\Http\Controllers\Controller;
use App\Imports\ParcelImport;
use App\Models\Backend\DeliveryCharge;
use App\Models\Backend\Hub;
use App\Models\BranchPaymentRequest;
use App\Models\BranchPaymentGet;


use App\Models\Backend\Merchant;
use App\Models\Backend\MerchantDeliveryCharge;
use App\Models\MerchantShops;

use App\Repositories\Merchant\MerchantInterface;
use App\Repositories\MerchantPanel\Shops\ShopsInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Parcel\StoreRequest;
use App\Http\Requests\Parcel\UpdateRequest;
use App\Models\Backend\DeliveryMan;
use App\Models\Backend\Parcel;


use App\Models\Backend\ParcelEvent;
use App\Models\Backend\ParcelItem;
use App\Models\User;
use App\Repositories\DeliveryMan\DeliveryManInterface;
use App\Repositories\Hub\HubInterface;
use App\Repositories\Parcel\ParcelInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;

class ParcelController extends Controller
{
    protected $merchant;
    protected $repo;
    protected $shop;
    protected $deliveryman;
    protected $hub;

    public function __construct(
        ParcelInterface $repo,
        MerchantInterface $merchant,
        ShopsInterface $shop,
        DeliveryManInterface $deliveryman,
        HubInterface $hub
    ) {
        $this->merchant = $merchant;
        $this->repo = $repo;
        $this->shop = $shop;
        $this->deliveryman = $deliveryman;
        $this->hub = $hub;
    }

    public function index(Request $request)
    {
        $parcels = $this->repo->all();
        $deliverymans = $this->deliveryman->all();
        $hubs = $this->hub->all();
        return view('backend.parcel.index', compact('parcels', 'deliverymans', 'hubs', 'request'));
    }
    public function createMultiple()
    {
        return view('backend.parcel.multiple');
    }

    public function create()
    {
        $merchants = $this->merchant->all();
        $merchantShops = MerchantShops::all();
        $deliveryCategories = $this->repo->deliveryCategories();
        $deliveryCharges = $this->repo->deliveryCharges();
        $packagings = $this->repo->packaging();
        $deliveryTypes = $this->repo->deliveryTypes();
        $hubs = $this->hub->all();
        $payment = BranchPaymentRequest::all();
        $branches = BranchPaymentGet::all();
        return view('backend.parcel.create', compact('merchants', 'merchantShops', 'payment', 'hubs', 'deliveryCategories', 'deliveryCharges', 'deliveryTypes', 'packagings', 'branches'));
    }








    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $merchant = Merchant::findOrFail($request->merchant_id);
            $merchantShop = MerchantShops::find($request->merchant_shop_id);
            $hub = Hub::findOrFail($request->hub_id);



            $trackingId = $request->tracking_id ?? 'WC' . date('Ymd') . strtoupper(uniqid());

            $totalItemPrice = collect($request->parcels)->sum('price');

            $chargeDetails = json_decode($request->chargeDetails, true);
            if ($request->parcel_payment_method == ParcelPaymentMethod::PREPAID) {
                if ($chargeDetails['totalDeliveryChargeAmount'] > $merchant->wallet_balance) {
                    Toastr::error(__('parcel.low_balance'), __('message.error'));
                    return redirect()->back()->withInput($request->all());
                }
            }

            $totalDeliveryAmount = $chargeDetails['totalDeliveryChargeAmount'] ?? 0;
            $cashCollection = $request->cash_collection ?? $totalItemPrice;
            $currentPayable = $cashCollection - $totalDeliveryAmount;

            // Get first barcode from parcels array for the main parcel record
            $firstBarcode = !empty($request->parcels) && isset($request->parcels[0]['barcode'])
                ? $request->parcels[0]['barcode']
                : null;

            $parcel = Parcel::create([
                'merchant_id' => $merchant->id,
                'merchant_shop_id' => $merchantShop ? $merchantShop->id : null,
                'hub_id' => $hub->id,
                'pickup_address' => $request->pickup_address,
                'pickup_phone' => $request->pickup_phone,
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address ?? null,
                'invoice_no' => $request->invoice_no,
                'delivery_type_id' => $request->delivery_type_id,
                'pickup_date' => $request->pickup_date,
                'delivery_date' => $request->delivery_date,
                'packaging_id' => $request->packaging_id,
                'cash_collection' => $cashCollection,
                'first_hub_id' => $request->first_hub_id,
                'selling_price' => $request->selling_price,
                'liquid_fragile_amount' => $chargeDetails['liquidFragileAmount'] ?? 0,
                'packaging_amount' => $chargeDetails['packagingAmount'] ?? 0,
                'delivery_charge' => $chargeDetails['deliveryChargeAmount'] ?? 0,
                'cod_charge' => $chargeDetails['codChargeAmount'] ?? 0,
                'cod_amount' => $request->cod_amount,
                'vat' => $chargeDetails['vatTex'] ?? 0,
                'vat_amount' => $chargeDetails['VatAmount'] ?? 0,
                'total_delivery_amount' => $totalDeliveryAmount,
                'current_payable' => $currentPayable,
                'tracking_id' => $trackingId,
                'status' => ParcelStatus::PENDING,
                'pickup_lat' => $request->pickup_lat,
                'pickup_long' => $request->pickup_long,
                'number_of_parcels' => $request->number_of_parcels,
                'barcode' => $firstBarcode,
            ]);

            $hubs = Hub::whereIn('id', collect($request->parcels)->pluck('hub_id'))->get()->keyBy('id');

            $items = collect($request->parcels)->map(function ($item, $index) use ($parcel, $hubs) {
                $requiredKeys = ['barcode', 'customer_name', 'unit', 'phone', 'address', 'hub_id', 'amount', 'quantity', 'to_branch_id', 'transport_type'];
                foreach ($requiredKeys as $key) {
                    if (!isset($item[$key])) {
                        Log::error('Missing required key in parcel item:', [
                            'index' => $index,
                            'key' => $key,
                            'item' => $item,
                        ]);
                        throw new \Exception("Missing required key '$key' for parcel item at index $index");
                    }
                }

                $hub = $hubs->get($item['hub_id']);
                if (!$hub) {
                    Log::error('Hub not found for parcel item:', [
                        'index' => $index,
                        'barcode' => $item['barcode'],
                        'hub_id' => $item['hub_id'],
                    ]);
                    throw new \Exception('Hub not found for parcel item: ' . $item['barcode']);
                }

                // Set to_branch_id to hub_id
                $toBranchId = $item['to_branch_id'];

                // Check for BranchPaymentRequest (optional)
                $branchPayment = BranchPaymentRequest::where('to_branch_id', $toBranchId)->first();
                if (!$branchPayment) {
                    Log::warning('No BranchPaymentRequest found for to_branch_id:', [
                        'to_branch_id' => $toBranchId,

                        'to_branch_id' => $item['to_branch_id'],
                        'index' => $index,
                    ]);
                }
                Log::info('BranchPayment data', ['BranchPayment' => $branchPayment ? $branchPayment->toArray() : null]);

                Log::info('Saving parcel item:', [
                    'index' => $index,
                    'barcode' => $item['barcode'],
                    'hub_id' => $item['hub_id'],
                    'hub_name' => $hub->name,
                    // 'weight' => $item['weight'],
                    'quantity' => $item['quantity'],



                    'unit' => $item['unit'],
                    'transport_type' => $item['transport_type'],
                    'to_branch_id' => $toBranchId,

                ]);

                return [
                    'parcel_id' => $parcel->id,
                    'customer_name' => $item["customer_name"],
                    'barcode' => $item['barcode'],
                    'phone' => $item['phone'],
                    'address' => $item['address'],
                    'amount' => $item['amount'],
                    'quantity' => $item['quantity'],
                    'unit' => strtolower($item['unit']),
                    'transport_type' => strtolower($item['transport_type']),
                    'hub_id' => $item['hub_id'],
                    'hub_name' => $hub->name,
                    'to_branch_id' => $toBranchId,

                    'created_at' => now(),
                    'updated_at' => now(),

                ];
            })->toArray();

            ParcelItem::insert($items);

            DB::commit();
            Toastr::success(__('parcel.added_msg'), __('message.success'));
            return redirect()->route('parcel.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Parcel creation failed:', [
                'message' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            Toastr::error(__('parcel.error_msg'), __('message.error'));
            return redirect()->back()->withInput();
        }
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'invoice_no' => 'required|string|max:255',
            'parcels' => 'required|array|min:1',
            'parcels.*.transport_type' => 'required|in:by_road,by_air',
            'parcels.*.to_branch_id' => 'required|exists:hubs,id',
            'parcels.*.barcode' => 'required|string|max:255',
            'parcels.*.customer_name' => 'required|string|max:255',
            'parcels.*.amount' => 'required|numeric|min:0',
            'parcels.*.phone' => 'required|string|max:20',
            'parcels.*.address' => 'required|string|max:500',
            'parcels.*.quantity' => 'required|integer|min:1',
            'parcels.*.unit' => 'required|in:kg,liter,pcs',
        ]);

        foreach ($request->parcels as $parcelData) {
            Parcel::create([
                'merchant_id' => $request->merchant_id,
                'merchant_shop_id' => null,
                'invoice_no' => $request->invoice_no,
                'hub_id' => $parcelData['to_branch_id'],
                'barcode' => $parcelData['barcode'],
                'customer_name' => $parcelData['customer_name'],
                'customer_phone' => $parcelData['phone'],
                'customer_address' => $parcelData['address'],
                'weight' => $parcelData['quantity'], // mapping quantity to weight
                'note' => $parcelData['unit'] . ' unit(s)',
                'transport_type' => $parcelData['transport_type'],

                // Optional default/fallback values
                'status' => \App\Enums\ParcelStatus::PENDING,
                'parcel_payment_method' => \App\Enums\ParcelPaymentMethod::COD,
                'number_of_parcels' => 1,
                'tracking_id' => uniqid('TRK'),
                'cash_collection' => $parcelData['amount'],
                'price' => $parcelData['amount'],
            ]);
        }

        return redirect()->route('parcel.index')->with('success', 'Parcels created successfully.');
    }
    public function getTransportPrice(Request $request)
    {
        $request->validate([
            'from_branch_id' => 'required|exists:branch_payment_requests,from_branch_id',
            'to_branch_id' => 'required|exists:branch_payment_requests,to_branch_id',
            'transport_type' => 'required|in:by_road,by_air',
        ]);

        $entry = DB::table('branch_payment_requests')
            ->where('from_branch_id', $request->from_branch_id)
            ->where('to_branch_id', $request->to_branch_id)
            ->where('transport_type', $request->transport_type)
            ->first();

        if (!$entry) {
            return response()->json([
                'status' => 'error',
                'message' => 'No price found for this route/transport type.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'amount' => $entry->amount,
        ]);
    }

    public function Multiple(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'invoice_no' => 'required|string|unique:parcels,invoice_no',
            'parcels' => 'required|array|min:1',
            'parcels.*.barcode' => 'required|string|distinct',
            'parcels.*.customer_name' => 'required|string|max:255',
            'parcels.*.amount' => 'required|numeric|min:0',
            'parcels.*.phone' => 'required|string|max:20',
            'parcels.*.address' => 'required|string|max:255',
            'parcels.*.quantity' => 'required|integer|min:1',
            'parcels.*.unit' => 'required|string',
            'parcels.*.to_branch_id' => 'required|exists:hubs,id',
        ]);

        DB::beginTransaction();
        try {
            Log::debug('Parcel request validated.', $request->all());

            foreach ($request->parcels as $key => $parcelData) {
                Log::debug("Storing parcel #$key", $parcelData);

                $parcel = new Parcel();
                $parcel->merchant_id = $request->merchant_id;
                $parcel->invoice_no = $request->invoice_no;
                $parcel->barcode = $parcelData['barcode'];
                $parcel->customer_name = $parcelData['customer_name'];
                $parcel->price = $parcelData['amount'];
                $parcel->customer_phone = $parcelData['phone'];
                $parcel->customer_address = $parcelData['address'];
                $parcel->quantity = $parcelData['quantity'];
                $parcel->unit = $parcelData['unit'];
                $parcel->to_branch_id = $parcelData['to_branch_id'];
                $parcel->save();

                Log::debug("Parcel #$key saved", ['id' => $parcel->id]);
            }

            DB::commit();
            Log::info('All parcels stored successfully.');
            return redirect()->route('parcel.index')->with('success', 'Parcels created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store parcels', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to store parcels: ' . $e->getMessage()]);
        }
    }

    public function duplicate($id)
    {
        $parcel = $this->repo->get($id);
        if (!$parcel) {
            Toastr::error('Parcel not found.', 'Error');
            return redirect()->back();
        }
        $merchant = $this->merchant->get($parcel->merchant_id);
        $shops = $this->shop->all($parcel->merchant_id);
        $deliveryCharges = DeliveryCharge::where('category_id', $parcel->category_id)->get();
        $deliveryCategories = $this->repo->deliveryCategories();
        $deliveryCategoryCharges = $this->repo->deliveryCharges();
        $packagings = $this->repo->packaging();
        $deliveryTypes = $this->repo->deliveryTypes();

        $hubs = $this->hub->all();
        return view('backend.parcel.duplicate', compact('parcel', 'merchant', 'hubs', 'shops', 'deliveryCategories', 'deliveryTypes', 'deliveryCategoryCharges', 'deliveryCharges', 'packagings'));
    }
    // Rest of the methods remain unchanged for brevity
    // Include other methods as provided in the original code
    public function logs($id)
    {
        $parcel = $this->repo->get($id);
        $parcelevents = $this->repo->parcelEvents($id);
        return view('backend.parcel.logs', compact('parcel', 'parcelevents'));
    }

    public function details($id)
    {
        $parcel = $this->repo->details($id);
        $parcelevents = ParcelEvent::where('parcel_id', $id)->orderBy('created_at', 'desc')->get();
        return view('backend.parcel.details', compact('parcel', 'parcelevents'));
    }

    public function edit($id)
    {
        $parcel = $this->repo->get($id);

        $hubs = $this->hub->all();
        $merchant = $this->merchant->get($parcel->merchant_id);
        // $shops = $this->shop->all($parcel->merchant_id);
        $deliveryCharges = DeliveryCharge::where('category_id', $parcel->category_id)->get();
        $deliveryCategories = $this->repo->deliveryCategories();
        $deliveryCategoryCharges = $this->repo->deliveryCharges();
        $packagings = $this->repo->packaging();
        $deliveryTypes = $this->repo->deliveryTypes();
        return view('backend.parcel.edit', compact('parcel', 'merchant', 'shops', 'hubs', 'deliveryCategories', 'deliveryTypes', 'deliveryCategoryCharges', 'deliveryCharges', 'packagings'));
    }

    public function statusUpdate($id, $status_id)
    {
        $this->repo->statusUpdate($id, $status_id);
        Toastr::success(__('parcel.update_msg'), __('message.success'));
        return redirect()->route('parcel.index');
    }

    public function update(StoreRequest $request, $id)
    {
        if ($this->repo->update($id, $request)) {
            Toastr::success(__('parcel.update_msg'), __('message.success'));
            return redirect()->route('parcel.index');
        } else {
            Toastr::error(__('parcel.error_msg'), __('message.error'));
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $this->repo->delete($id);
        Toastr::success(__('parcel.delete_msg'), __('message.success'));
        return back();
    }

    public function parcelImportExport()
    {
        $deliveryCategories = $this->repo->deliveryCategories();
        return view('backend.parcel.import', compact('deliveryCategories'));
    }

    public function getBranches(Request $request)
    {
        try {
            $branches = $this->repo->deliveryCategories();
            $formattedBranches = $branches->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                ];
            });
            return response()->json($formattedBranches, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch branches.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function parcelImport(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);
        try {
            $import = new ParcelImport();
            $import->import($request->file('file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $importErrors = [];
            foreach ($failures as $failure) {
                $importErrors[$failure->row()][] = $failure->errors()[0];
            }
            return back()->with('importErrors', $importErrors);
        }
        Toastr::success(__('parcel.added_msg'), __('message.success'));
        return redirect()->route('parcel.index');
    }

    public function getImportMerchant(Request $request)
    {
        $search = $request->search;
        $response = [];
        if ($request->searchQuery == 'true') {
            $merchants = Merchant::where('status', Status::ACTIVE)
                ->orderBy('business_name', 'asc')
                ->select('id', 'business_name', 'vat')
                ->where('business_name', 'like', '%' . $search . '%')
                ->limit(10)
                ->get();
            foreach ($merchants as $merchant) {
                $response[] = [
                    'id' => $merchant->id,
                    'text' => $merchant->id . ' = ' . $merchant->business_name,
                ];
            }
            return response()->json($response);
        }
    }

    public function getMerchant(Request $request)
    {
        $search = $request->search;
        $response = [];
        if ($request->searchQuery == 'true') {
            $merchants = Merchant::where('status', Status::ACTIVE)
                ->orderBy('business_name', 'asc')
                ->select('id', 'business_name', 'vat')
                ->where('business_name', 'like', '%' . $search . '%')
                ->limit(10)
                ->get();
            foreach ($merchants as $merchant) {
                $response[] = [
                    'id' => $merchant->id,
                    'text' => $merchant->business_name,
                ];
            }
            return response()->json($response);
        } else {
            $merchant = Merchant::find($search);
            $response[] = [
                'vat' => $merchant->vat ?? 0,
                'cod_charges' => $merchant->cod_charges,
            ];
            return response()->json($response);
        }
    }

    public function getHub(Request $request)
    {
        $search = $request->search;
        $response = [];
        if ($request->searchQuery == 'true') {
            $hubs = Hub::where('status', Status::ACTIVE)
                ->orderBy('name', 'asc')
                ->select('id', 'name')
                ->where('name', 'like', '%' . $search . '%')
                ->limit(10)
                ->get();
            foreach ($hubs as $hub) {
                $response[] = [
                    'id' => $hub->id,
                    'text' => $hub->name,
                ];
            }
            return response()->json($response);
        }
    }

    public function getMerchantCod(Request $request)
    {
        if (request()->ajax()) {
            $merchant = Merchant::find($request->merchant_id);
            return response()->json([
                'inside_city' => $merchant->cod_charges['inside_city'],
                'sub_city' => $merchant->cod_charges['sub_city'],
                'outside_city' => $merchant->cod_charges['outside_city'],
            ]);
        }
        return '';
    }

    public function merchantShops(Request $request)
    {
        if (request()->ajax()) {
            if ($request->id && $request->shop == 'true') {
                $merchantShops = [];
                $merchantShop = MerchantShops::where(['merchant_id' => $request->id, 'default_shop' => Status::ACTIVE])->first();
                $merchantShops[] = $merchantShop;
                $merchantShopArray = MerchantShops::where(['merchant_id' => $request->id, 'default_shop' => Status::INACTIVE])->get();
                if (!blank($merchantShopArray)) {
                    foreach ($merchantShopArray as $shop) {
                        $merchantShops[] = $shop;
                    }
                }
                if (!blank($merchantShops)) {
                    return view('backend.parcel.shops', compact('merchantShops'));
                }
                return '';
            } else {
                $merchantShop = MerchantShops::find($request->id);
                if (!blank($merchantShop)) {
                    return $merchantShop;
                }
                return '';
            }
        }
        return '';
    }

    public function deliveryCharge(Request $request)
    {
        if (request()->ajax()) {
            if ($request->merchant_id && $request->category_id && $request->weight != '0' && $request->delivery_type_id) {
                $charges = MerchantDeliveryCharge::where([
                    'merchant_id' => $request->merchant_id,
                    'category_id' => $request->category_id,
                    'weight' => $request->weight,
                ])->first();
                if (blank($charges)) {
                    $charges = DeliveryCharge::where(['category_id' => $request->category_id])->first();
                }
            } else {
                $charges = MerchantDeliveryCharge::where([
                    'merchant_id' => $request->merchant_id,
                    'category_id' => $request->category_id,
                    'weight' => $request->weight,
                ])->first();
                if (blank($charges)) {
                    $charges = DeliveryCharge::where(['category_id' => $request->category_id])->first();
                }
            }
            if (!blank($charges)) {
                $chargeAmount = match ($request->delivery_type_id) {
                    '1' => $charges->same_day,
                    '2' => $charges->next_day,
                    '3' => $charges->sub_city,
                    '4' => $charges->outside_city,
                    '5' => $charges->by_road,
                    '6' => $charges->by_air,
                    default => 0,
                };
                return $chargeAmount;
            }
            return 0;
        }
        return 0;
    }

    public function deliveryWeight(Request $request)
    {
        if (request()->ajax()) {
            if ($request->category_id) {
                $deliveryCharges = DeliveryCharge::where('category_id', $request->category_id)->get();
                if (!blank($deliveryCharges)) {
                    return view('backend.parcel.deliveryWeight', compact('deliveryCharges'));
                }
                return '';
            }
        }
        return '';
    }

    public function transferHub(Request $request)
    {
        $parcelEvent = ParcelEvent::where(['parcel_id' => $request->parcel_id, 'parcel_status' => ParcelStatus::RECEIVED_WAREHOUSE])->first();
        $hubs = Hub::orderByDesc('id')->whereNotIn('id', [$parcelEvent->hub_id])->get();
        $response = '';
        foreach ($hubs as $hub) {
            $response .= '<option value="' . $hub->id . '">' . $hub->name . '</option>';
        }
        return $response;
    }

    public function deliverymanSearch(Request $request)
    {
        $search = $request->search;
        if ($request->single) {
            $deliveryMan = ParcelEvent::where([
                'parcel_id' => $request->parcel_id,
                'parcel_status' => $request->status,
            ])->first();
            if (isset($deliveryMan->deliveryMan) && !blank($deliveryMan->deliveryMan)) {
                $response = '<option value="' . $deliveryMan->delivery_man_id . '" selected>' . $deliveryMan->deliveryMan->user->name . '</option>';
            } else {
                $response = '<option value="' . $deliveryMan->pickup_man_id . '" selected>' . $deliveryMan->pickupman->user->name . '</option>';
            }
            return $response;
        } else {
            $deliverymans = User::where('status', Status::ACTIVE)
                ->orderBy('name', 'asc')
                ->select('id', 'name')
                ->where('name', 'like', '%' . $search . '%')
                ->where('user_type', UserType::DELIVERYMAN)
                ->limit(10)
                ->get();
            $response = [];
            foreach ($deliverymans as $deliveryman) {
                $response[] = [
                    'id' => $deliveryman->deliveryman->id,
                    'text' => $deliveryman->name,
                ];
            }
            return response()->json($response);
        }
    }

    public function parcelRecivedByHubSearch(Request $request)
    {
        if ($request->ajax()) {
            $hub = $request->hub_id;
            $track_id = $request->track_id;
            if ($track_id && $hub) {
                $parcel = Parcel::with(['merchant', 'merchant.user'])->where([
                    'tracking_id' => $request->track_id,
                    'transfer_hub_id' => $hub,
                    'status' => ParcelStatus::TRANSFER_TO_HUB,
                ])->first();
                if ($parcel) {
                    return response()->json($parcel);
                }
                return 0;
            }
        }
    }

    public function transfertohubSelectedHub(Request $request)
    {
        $parcel = Parcel::find($request->parcel_id);
        if ($parcel && $parcel->hub_id) {
            return '<option selected disabled>' . $parcel->hub->name . '</option>';
        }
        return '<option selected disabled>Hub not found</option>';
    }

    public function PickupManAssigned(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_man_id' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->pickupdatemanAssigned($request->parcel_id, $request)) {
            Toastr::success(__('parcel.pickup_man_assigned'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function PickupManAssignedCancel(Request $request)
    {
        if ($this->repo->pickupdatemanAssignedCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.pickup_man_assigned'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function PickupReSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_man_id' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->PickupReSchedule($request->parcel_id, $request)) {
            Toastr::success(__('parcel.pickup_scheduled'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function PickupReScheduleCancel(Request $request)
    {
        if ($this->repo->PickupReScheduleCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.pickup_reschedule_canceled'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function receivedBypickupman(Request $request)
    {
        if ($this->repo->receivedBypickupman($request->parcel_id, $request)) {
            Toastr::success(__('parcel.received_by_pickup_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function receivedByHub(Request $request)
    {
        if ($this->repo->receivedByHub($request->parcel_id, $request)) {
            Toastr::success(__('parcel.received_by_hub'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function receivedByHubCancel(Request $request)
    {
        if ($this->repo->receivedByHubCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.received_by_hub_cancel'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function receivedBypickupmanCancel(Request $request)
    {
        if ($this->repo->receivedBypickupmanCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.received_by_pickup_cancel_success'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function search(Request $data)
    {
        return $this->repo->search($data);
    }

    public function searchDeliveryManAssingMultipleParcel(Request $data)
    {
        return $this->repo->searchDeliveryManAssingMultipleParcel($data);
    }

    public function searchExpense(Request $data)
    {
        return $this->repo->searchExpense($data);
    }

    public function searchIncome(Request $data)
    {
        return $this->repo->searchIncome($data);
    }

    public function transferToHubMultipleParcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hub_id' => 'required',
            'parcel_ids' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return redirect(paginate_redirect($request));
        }
        if ($this->repo->transferToHubMultipleParcel($request)) {
            Toastr::success(__('parcel.transfer_to_hub_success'), __('message.success'));
            $deliveryman = $this->deliveryman->get($request->delivery_man_id);
            $parcels = $this->repo->bulkParcels($request->parcel_ids);
            $bulk_type = ParcelStatus::TRANSFER_TO_HUB;
            $transfered_hub = Hub::find($request->hub_id);
            return view('backend.parcel.bulk_print', compact('parcels', 'deliveryman', 'bulk_type', 'transfered_hub'));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect(paginate_redirect($request));
    }

    public function deliveryManAssignMultipleParcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_man_id' => 'required',
            'parcel_ids_' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return redirect(paginate_redirect($request));
        }
        if ($this->repo->deliveryManAssignMultipleParcel($request)) {
            Toastr::success(__('parcel.delivery_man_assign_success'), __('message.success'));
            $deliveryman = $this->deliveryman->get($request->delivery_man_id);
            $parcels = $this->repo->bulkParcels($request->parcel_ids_);
            $bulk_type = ParcelStatus::DELIVERY_MAN_ASSIGN;
            return view('backend.parcel.bulk_print', compact('parcels', 'deliveryman', 'bulk_type'));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect(paginate_redirect($request));
    }

    public function ParcelBulkAssignPrint(Request $request)
    {
        try {
            $deliveryman = $this->deliveryman->get($request->delivery_man_id);
            $parcels = $this->repo->bulkParcels($request->parcels);
            $bulk_type = ParcelStatus::DELIVERY_MAN_ASSIGN;
            $reprint = true;
            return view('backend.parcel.bulk_print', compact('parcels', 'deliveryman', 'bulk_type', 'reprint'));
        } catch (\Throwable $th) {
            Toastr::error(__('parcel.error_msg'), __('message.error'));
            return redirect()->back();
        }
    }

    public function transfertohub(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hub_id' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.error_msg'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->transfertohub($request->parcel_id, $request)) {
            Toastr::success(__('parcel.transfer_to_hub_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function transfertoHubCancel(Request $request)
    {
        if ($this->repo->transfertoHubCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.transfer_to_hub_canceled'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function deliverymanAssign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_man_id' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->deliverymanAssign($request->parcel_id, $request)) {
            Toastr::success(__('parcel.delivery_man_assign_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function deliverymanAssignCancel(Request $request)
    {
        if ($this->repo->deliverymanAssignCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.deliveryman_assign_cancel'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function deliveryReschedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_man_id' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->deliveryReschedule($request->parcel_id, $request)) {
            Toastr::success(__('parcel.delivery_reschedule_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function deliveryReScheduleCancel(Request $request)
    {
        if ($this->repo->deliveryReScheduleCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.delivery_re_schedule_cancel'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function receivedWarehouse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hub_id' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->receivedWarehouse($request->parcel_id, $request)) {
            Toastr::success(__('parcel.received_warehouse_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function receivedWarehouseCancel(Request $request)
    {
        if ($this->repo->receivedWarehouseCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.received_warehouse_cancel'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function returntoQourier(Request $request)
    {
        if ($this->repo->returntoQourier($request->parcel_id, $request)) {
            Toastr::success(__('parcel.return_to_qourier_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function returntoQourierCancel(Request $request)
    {
        if ($this->repo->returntoQourierCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.received_warehouse_cancel'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function returnAssignToMerchant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_man_id' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->returnAssignToMerchant($request->parcel_id, $request)) {
            Toastr::success(__('parcel.return_assign_to_merchant_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect(paginate_redirect($request));
    }

    public function returnAssignToMerchantCancel(Request $request)
    {
        if ($this->repo->returnAssignToMerchantCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.return_assign_to_merchant_cancel_success'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function returnAssignToMerchantReschedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_man_id' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->returnAssignToMerchantReschedule($request->parcel_id, $request)) {
            Toastr::success(__('parcel.return_assign_to_merchant_reschedule_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function returnAssignToMerchantRescheduleCancel(Request $request)
    {
        if ($this->repo->returnAssignToMerchantRescheduleCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.return_assign_to_merchant_reschedule_cancel_success'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function returnReceivedByMerchant(Request $request)
    {
        if ($this->repo->returnReceivedByMerchant($request->parcel_id, $request)) {
            Toastr::success(__('parcel.return_received_by_merchant'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function returnReceivedByMerchantCancel(Request $request)
    {
        if ($this->repo->returnReceivedByMerchantCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.return_received_by_merchant_cancel_success'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function parcelDelivered(Request $request)
    {
        if ($this->repo->parcelDelivered($request->parcel_id, $request)) {
            Toastr::success(__('parcel.delivered_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function parcelDeliveredCancel(Request $request)
    {
        if ($this->repo->parcelDeliveredCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.delivered_cancel'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function parcelPartialDelivered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cash_collection' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.required'), __('message.error'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        if ($this->repo->parcelPartialDelivered($request->parcel_id, $request)) {
            Toastr::success(__('parcel.partial_delivered_success'), __('message.success'));
            return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return $request->filter == 'on' ? redirect()->back() : redirect(paginate_redirect($request));
    }

    public function parcelPartialDeliveredCancel(Request $request)
    {
        if ($this->repo->parcelPartialDeliveredCancel($request->parcel_id, $request)) {
            Toastr::success(__('parcel.partial_delivered_cancel'), __('message.success'));
            return redirect()->back();
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect()->back();
    }

    public function parcelPrint($id)
    {
        // $parcel = $this->repo->get($id);

        $parcel = $this->repo->get($id)->load('items');
        $merchant = $this->merchant->get($parcel->merchant_id);
        $shops = $this->shop->all($parcel->merchant_id);
        return view('backend.parcel.print', compact('parcel', 'merchant', 'shops'));
    }

    public function parcelPrintLabel($id)
    {
        $parcel = $this->repo->get($id);
        $merchant = $this->merchant->get($parcel->merchant_id);
        $shops = $this->shop->all($parcel->merchant_id);
        return view('backend.parcel.print-label', compact('parcel', 'merchant', 'shops'));
    }

    public function parcelMultiplePrintLabel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parcels' => 'required',
        ]);

        if ($validator->fails()) {
            Toastr::error('Must be select parcel.', __('message.error'));
            return redirect()->back();
        }

        $parcels = $this->repo->parcelMultiplePrintLabel($request);

        // ✅ Deliveryman name nikalne ka logic yahan likh
        foreach ($parcels as $parcel) {
            // Step 1: Parcel se deliveryman record nikal
            $deliveryman = DeliveryMan::find($parcel->delivery_type_id);

            // Step 2: Agar deliveryman mila to uska user name nikal
            if ($deliveryman && $deliveryman->user_id) {
                $user = User::find($deliveryman->user_id);
                $parcel->deliveryman_name = $user->name ?? 'N/A';
            } else {
                $parcel->deliveryman_name = 'N/A';
            }
        }

        return view('backend.parcel.multiple-print-label', compact('parcels'));
    }



    public function parcelMultiplePrintLabelEstimate(Request $request)
    {
        $parcelIds = $request->input('parcels', []); // selected parcel IDs

        // Sirf wahi parcels fetch karo jo selected hain
        $parcels = Parcel::whereIn('id', $parcelIds)->get();

        // ✅ Deliveryman name nikalne ka logic yahan likh
        foreach ($parcels as $parcel) {
            // Step 1: Parcel se deliveryman record nikal
            $deliveryman = DeliveryMan::find($parcel->delivery_type_id);

            // Step 2: Agar deliveryman mila to uska user name nikal
            if ($deliveryman && $deliveryman->user_id) {
                $user = User::find($deliveryman->user_id);
                $parcel->deliveryman_name = $user->name ?? 'N/A';
            } else {
                $parcel->deliveryman_name = 'N/A';
            }
        }

        // Agar delivery boy name ya DR no bhejna ho
        $deliveryBoyName = $parcels->first()->deliveryman_name ?? 'N/A';
        $dr_no = 'DR-' . now()->format('Ymd-His');

        return view('backend.parcel.multiple-print-label-estimate', compact('parcels', 'deliveryBoyName', 'dr_no'));
    }


    public function printLabelInfo(Request $request)
    {
        $parcelIds = $request->get('parcels', []);

        if (empty($parcelIds)) {
            Toastr::warning('Please select at least one parcel before printing info.');
            return redirect()->back();
        }

        $parcels = Parcel::whereIn('id', $parcelIds)->get();

        return view('backend.parcel.multiple-print-label-info', compact('parcels'));
    }

    public function printLabelorderinfo(Request $request)
    {
        $parcelIds = $request->get('parcels', []);

        if (empty($parcelIds)) {
            Toastr::warning('Please select at least one parcel before printing info.');
            return redirect()->back();
        }

        $parcels = Parcel::with('hub')->whereIn('id', $parcelIds)->get();

        // Optional: Debugging
        foreach ($parcels as $parcel) {
            Log::info('Parcel ID: ' . $parcel->id, [
                $parcel
            ]);
        }


        // $parcels = Parcel::whereIn('id', $parcelIds)->get();
        // foreach ($parcels as $parcel) {
        //     $parcel->hub = Hub::find($parcel->hub_id);
        // }

        // Debugging
        // Log::info('testing_data', ['data' => $parcels->pluck('hub_id')]);


        return view('backend.parcel.multiple-print-label-order', compact('parcels'));
    }
    public function printLabelOrder(Request $request)
    {
        try {
            $today = Carbon::today();

            $parcels = Parcel::with(['merchant'])
                ->whereDate('created_at', $today)
                ->get();

            if ($parcels->isEmpty()) {
                Toastr::warning('No parcel record was found today.', 'Warning');
                return redirect()->back();
            }

            // Deliveryman name attach karna
            foreach ($parcels as $parcel) {
                $deliveryman = DeliveryMan::find($parcel->delivery_type_id);
                $parcel->deliveryman_name = $deliveryman && $deliveryman->user_id
                    ? ($deliveryman->user?->name ?? 'N/A')
                    : 'N/A';

                // Merchant shop ya pickup address
                $parcel->pickup_address_full = $parcel->merchant_shop_id
                    ? ($parcel->merchantShop?->shop_name ?? 'N/A')
                    : ($parcel->pickup_address ?? 'N/A');

                $parcel->pickup_phone_full = $parcel->pickup_phone ?? $parcel->merchant?->phone ?? 'N/A';
            }

            return view('backend.parcel.multiple-print-label-order', compact('parcels'));
        } catch (\Exception $e) {
            Log::error('Print Label Error: ' . $e->getMessage());
            Toastr::error('Label print karte waqt error aaya.');
            return redirect()->back();
        }
    }




    public function parcelPerDay(Request $request)
    {
        try {
            $today = Carbon::today();

            Log::info('Parcel Per Day Request Initiated', [
                'date' => $today->toDateString(),
                'user_id' => auth()->id(),
            ]);

            $parcels = Parcel::whereDate('created_at', $today)->get();

            if ($parcels->isEmpty()) {
                Toastr::warning('No parcel record was found today', 'Warning');
                return redirect()->back();
            }

            foreach ($parcels as $parcel) {
                $deliveryman = DeliveryMan::find($parcel->delivery_type_id);
                if ($deliveryman && $deliveryman->user_id) {
                    $user = User::find($deliveryman->user_id);
                    $parcel->deliveryman_name = $user->name ?? 'N/A';
                } else {
                    $parcel->deliveryman_name = 'N/A';
                }
            }

            Log::info('Parcel Per Day Print View Rendered', [
                'total_parcels' => $parcels->count(),
            ]);

            return view('backend.parcel.multiple-print-label-perday', compact('parcels'));
        } catch (\Exception $e) {
            Log::error('Parcel Per Day Error: ' . $e->getMessage());
            Toastr::error('Something went wrong while fetching today\'s parcels.');
            return redirect()->back();
        }
    }








    public function parcelReceivedByMultipleHub(Request $request)
    {
        if ($this->repo->parcelReceivedByMultipleHub($request->parcel_id, $request)) {
            Toastr::success(__('parcel.received_by_multiple_hub'), __('message.success'));
            return redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect(paginate_redirect($request));
    }

    public function AssignPickupParcelSearch(Request $request)
    {
        if ($request->ajax()) {
            $merchant_id = $request->merchant_id;
            $tracking_id = $request->tracking_id;
            if ($merchant_id !== null && $tracking_id !== null) {
                $parcel = Parcel::with(['merchant', 'merchant.user'])->where([
                    'merchant_id' => $merchant_id,
                    'tracking_id' => $tracking_id,
                    'status' => ParcelStatus::PENDING,
                ])->first();
                if ($parcel) {
                    return response()->json($parcel);
                }
                return 0;
            }
            return 0;
        }
        return 0;
    }

    public function AssignPickupBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required',
            'delivery_man_id' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.feild_required'), __('message.error'));
            return redirect(paginate_redirect($request));
        }
        if ($this->repo->pickupdatemanAssignedBulk($request)) {
            Toastr::success(__('parcel.pickup_man_assigned'), __('message.success'));
            return redirect(paginate_redirect($request));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect(paginate_redirect($request));
    }

    public function AssignReturnToMerchantParcelSearch(Request $request)
    {
        if ($request->ajax()) {
            $merchant_id = $request->merchant_id;
            $tracking_id = $request->tracking_id;
            if ($merchant_id !== null && $tracking_id !== null) {
                $parcel = Parcel::with(['merchant', 'merchant.user'])->where([
                    'merchant_id' => $merchant_id,
                    'tracking_id' => $tracking_id,
                    'status' => ParcelStatus::RETURN_TO_COURIER,
                ])->first();
                if ($parcel) {
                    return response()->json($parcel);
                }
                return 0;
            }
            return 0;
        }
        return 0;
    }

    public function AssignReturnToMerchantBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required',
            'delivery_man_id' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            Toastr::error(__('parcel.feild_required'), __('message.error'));
            return redirect(paginate_redirect($request));
        }
        if ($this->repo->AssignReturnToMerchantBulk($request)) {
            Toastr::success(__('parcel.return_assign_to_merchant_success'), __('message.success'));
            $deliveryman = $this->deliveryman->get($request->delivery_man_id);
            $parcels = $this->repo->bulkParcels($request->parcel_ids);
            $bulk_type = ParcelStatus::RETURN_ASSIGN_TO_MERCHANT;
            return view('backend.parcel.bulk_print', compact('parcels', 'deliveryman', 'bulk_type'));
        }
        Toastr::error(__('parcel.error_msg'), __('message.error'));
        return redirect(paginate_redirect($request));
    }

    public function warehouseHubSelected(Request $request)
    {
        $hubs_list = "<option>" . __("menus.select") . " " . __("hub.title") . "</option>";
        $hubs = Hub::all();
        foreach ($hubs as $hub) {
            $selected = $hub->id == $request->hub_id ? 'selected' : '';
            $hubs_list .= "<option $selected value='" . $hub->id . "'>" . $hub->name . "</option>";
        }
        return $hubs_list;
    }

    public function ParcelSearchs(Request $request)
    {
        if ($this->repo->parcelSearchs($request)) {
            $parcels = $this->repo->parcelSearchs($request);
            $deliverymans = $this->deliveryman->all();
            $hubs = $this->hub->all();
            return view('backend.parcel.index', compact('parcels', 'request', 'deliverymans', 'hubs'));
        }
        return redirect()->back();
    }

    public function parcelSampleExport()
    {
        return Excel::download(new ParcelSampleExport, 'invoice.xlsx');
    }

    public function priorityUpdate(Request $request)
    {
        $parcel = Parcel::where(['id' => $request->id])->first();
        $parcel->priority_type_id = (int) $request->priority == 1 ? 2 : 1;
        $parcel->save();
        return $parcel;
    }

    public function parcelDeliveryMan()
    {
        $parcelEvents = ParcelEvent::with('parcel')->whereNotNull('delivery_man_id')->where('parcel_status', ParcelStatus::DELIVERY_MAN_ASSIGN)->get();
        $mapParcels = [];
        if (!blank($parcelEvents)) {
            foreach ($parcelEvents as $key => $parcelEvent) {
                $mapParcels[$key]['deliveryMan'] = optional($parcelEvent->deliveryMan->user)->name;
                $mapParcels[$key]['deliveryPhone'] = optional($parcelEvent->deliveryMan->user)->mobile;
                $mapParcels[$key]['deliveryImage'] = optional($parcelEvent->deliveryMan->user)->image;
                $mapParcels[$key]['lat'] = $parcelEvent->delivery_lat;
                $mapParcels[$key]['long'] = $parcelEvent->delivery_long;
                $mapParcels[$key]['customer_name'] = $parcelEvent->parcel->customer_name;

                $mapParcels[$key]['customer_phone'] = $parcelEvent->parcel->customer_phone;
                $mapParcels[$key]['merchant_business_name'] = $parcelEvent->parcel->merchant->business_name;
                $mapParcels[$key]['merchant_phone'] = $parcelEvent->parcel->merchant->user->mobile;
                $mapParcels[$key]['merchant_address'] = $parcelEvent->parcel->merchant->address;
                $mapParcels[$key]['current_payable'] = $parcelEvent->parcel->current_payable;
                $mapParcels[$key]['tracking_id'] = $parcelEvent->parcel->tracking_id;
                $mapParcels[$key]['url'] = route('parcel.logs', $parcelEvent->parcel->id);
            }
        }
        return view('backend.parcel.parcel-map-logs', compact('mapParcels'));
    }

    public function deliveredInfo($id)
    {
        $parcel = $this->repo->get($id);
        $parcelevents = $this->repo->parcelEvents($id);
        return view('backend.parcel.parcel-delivered-info', compact('parcel', 'parcelevents'));
    }

    public function getBranchRate(Request $request)
    {
        $from_branch_id = $request->query('from_branch_id');
        $branch_id = $request->query('branch_id');
        $transport_type = $request->query('transport_type');
        $unit = $request->query('unit');

        if (!$from_branch_id || !$branch_id || !$transport_type || !$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Missing parameters',
            ]);
        }

        // Example logic (you can replace with real DB logic)
        $rate = \DB::table('branch_payment_requests')
            ->where('from_branch_id', $from_branch_id)
            ->where('id', $branch_id)
            ->where('transport_type', $transport_type)
            ->value('amount');

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Rate not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'amount' => $rate, // sirf rate bhej rahe hain
        ]);
    }


    public function getBranchRatemultiple(Request $request)
    {
        $from_branch_id = $request->query('from_branch_id');
        $branch_id = $request->query('to_branch_id');
        $transport_type = $request->query('transport_type');
        $unit = $request->query('unit');

        if (!$from_branch_id || !$branch_id || !$transport_type || !$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Missing parameters',
            ]);
        }

        // Dummy logic (replace with your actual table/logic)
        $rate = \DB::table('branch_payment_requests')
            ->where('from_branch_id', $from_branch_id)
            ->where('id', $branch_id)
            ->where('transport_type', $transport_type)
            ->where('unit', $unit)
            ->value('amount');

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Rate not found',
            ]);
        }

        // Example: If you want to multiply by unit, adjust logic
        $finalAmount = $rate; // ya $rate * quantity (agar quantity chahiye ho)

        return response()->json([
            'success' => true,
            'amount' => $finalAmount,
        ]);
    }
}
