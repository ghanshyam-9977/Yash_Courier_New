<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ParcelStatus;
use App\Exports\BranchPaymentExport;
use App\Exports\HubExport;
use App\Http\Controllers\Controller;
use App\Models\Backend\DeliveryMan;
use App\Models\Backend\Hub;
use App\Models\ConsignmentStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\StoreHubRequest;
use App\Http\Requests\Hub\UpdateHubRequest;
use App\Models\Backend\DrsEntry;
use App\Models\Backend\DrsShipment;
use App\Models\Backend\FastBooking;
use App\Models\Backend\FastBookingItem;
use App\Models\Backend\Parcel;
use App\Repositories\Hub\HubInterface;
use App\Models\BranchPaymentGet;
use App\Models\BranchPaymentRequest;
use App\Repositories\HubManage\HubPayment\HubPaymentInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Config\Exception\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ValidatorsValidationException;
use Picqer\Barcode\BarcodeGeneratorPNG;

class HubController extends Controller
{
    protected $repo;
    protected $hubPayments;

    public function __construct(HubInterface $repo, HubPaymentInterface $hubPayments)
    {
        $this->repo = $repo;
        $this->hubPayments = $hubPayments;
    }

    public function index(Request $request)
    {
        $hubs = $this->repo->all();
        Log::info('Hub index loaded', [
            'data'  => $hubs
        ]);
        return view('backend.hub.index', compact('hubs', 'request'));
    }



    public function branch_index(Request $request)
    {
        $hubs = Hub::all()->keyBy('id');
        Log::info('hubs-data', [
            'request_data' => $hubs
        ]);
        // $hubs = $this->repo->all();
        $payments = BranchPaymentGet::all();
        Log::info('payment-data', [
            'request_data' => $payments
        ]);
        $allBranches = Hub::all();
        Log::info('allbranches-data', [
            'allbraches_data' => $allBranches
        ]);
        // Branches for dropdown
        // $data['payments']  = $this->hubPayments->getSingleHubPayments(auth()->user()->hub_id);

        return view('backend.hub.branch', compact('payments', 'hubs', 'allBranches', 'request'));
    }


    public function drx_index(Request $request)
    {
        $drsEntries = DrsEntry::with([
            'shipments',
            'deliveryMan.user' // user relation bhi load hogi
        ])
            ->orderBy('id', 'desc')
            ->paginate(10);

        // logger('data', [
        //     'drsEntries' => $drsEntries
        // ]);

        return view('backend.drs.drs', compact('drsEntries', 'request'));
    }

    public function fastbooking_index(Request $request)
    {
        $query = FastBooking::with([
            'items',
            'sourceHub',
            'destinationHub'
        ]);

        // 🔍 Search
        if ($search = $request->input('search')) {
            $query->where('booking_no', 'like', "%{$search}%")
                ->orWhereHas('sourceHub', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('destinationHub', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        // 📅 Date filter
        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate = $request->input('to_date')) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $fastBookings = $query
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('backend.fastbooking.index', compact('fastBookings', 'request'));
    }



    public function fastbooking_create(Request $request)
    {
        $branches = Hub::all();
        $networks = [
            'DTDC',
            'Blue Dart',
            'Delhivery',
            'XpressBees',
            'Ecom Express',
            'Amazon Transport',
            'Shadowfax',
            'Ekart',
        ];
        return view('backend.fastbooking.create', compact('branches', 'networks'));
    }


    public function fastbooking_store(Request $request)
    {
        $request->validate([
            'booking_no'                 => 'nullable|string|max:50',
            'from_branch_id'             => 'nullable|exists:hubs,id',
            'to_branch_id'               => 'nullable|exists:hubs,id',
            'network'                    => 'nullable|string|max:50',
            'payment_type'               => 'nullable|string|max:50',
            'forwarding_no'  => 'nullable|string|max:50',
            'eway_bill_no'   => 'nullable|string|max:20',

            'items.tracking_no.*'        => '|distinct|unique:fast_booking_items,tracking_no',
            'items.receiver_name.*'      => '',
            'items.address.*'            => '',
            'items.pcs.*'                => '|integer|min:1',
            'items.weight.*'             => '|numeric|min:0.01',
            'items.amount.*'             => '|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            /* ---------- CALCULATE TOTALS ---------- */
            $totalPcs = array_sum($request->items['pcs']);
            $totalWeight = array_sum($request->items['weight']);
            $totalAmount = array_sum($request->items['amount']);

            /* ---------- FAST BOOKING (MASTER) ---------- */
            $booking = FastBooking::create([
                'booking_no'     => $request->booking_no,
                'from_branch_id' => $request->from_branch_id,
                'to_branch_id'   => $request->to_branch_id,
                'network'        => $request->network,
                'payment_type'   => $request->payment_type,
                'forwarding_no'  => $request->forwarding_no,
                'eway_bill_no'   => $request->eway_bill_no,
                'slip_no'        => $request->slip_no,
                'total_pcs'      => $totalPcs,
                'total_weight'   => $totalWeight,
                'total_amount'   => $totalAmount,
                'remark'         => $request->remark,
            ]);

            /* ---------- FAST BOOKING ITEMS ---------- */
            foreach ($request->items['tracking_no'] as $index => $trackingNo) {
                FastBookingItem::create([
                    'fast_booking_id' => $booking->id,
                    'tracking_no'     => $trackingNo,
                    'receiver_name'   => $request->items['receiver_name'][$index],
                    'address'         => $request->items['address'][$index],
                    'pcs'             => $request->items['pcs'][$index],
                    'weight'          => $request->items['weight'][$index],
                    'amount'          => $request->items['amount'][$index],
                ]);
            }

            DB::commit();

            foreach ($request->items['tracking_no'] as $trackingNo) {
                ConsignmentStatusHistory::create([
                    'tracking_number' => $trackingNo,
                    'status' => 'fast_booked',  // ya 'fast_booked', jo bhi tum use karte ho
                    'remarks' => 'Fast booking created',
                    'branch_id' => $request->from_branch_id ?? null,
                    'created_at' => now(),
                ]);
            }

            return response()->json([
                'success'  => true,
                'message'  => 'Fast Booking created successfully',
                'redirect' => route('fast_bookings.index'),
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function fastbooking_update(Request $request, $id)
    {
        $request->validate([
            'booking_no'                 => 'nullable|string|max:50',
            'from_branch_id'             => 'nullable|exists:hubs,id',
            'to_branch_id'               => 'nullable|exists:hubs,id',
            'network'                    => 'nullable|string|max:50',
            'payment_type'               => 'nullable|string|max:50',
            'forwarding_no'              => 'nullable|string|max:50',
            'eway_bill_no'               => 'nullable|string|max:20',

            'items.tracking_no.*'        => 'distinct',
            'items.receiver_name.*'      => '',
            'items.address.*'            => '',
            'items.pcs.*'                => 'integer|min:1',
            'items.weight.*'             => 'numeric|min:0.01',
            'items.amount.*'             => 'numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $booking = FastBooking::findOrFail($id);

            /* ---------- CALCULATE TOTALS ---------- */
            $totalPcs = array_sum($request->items['pcs']);
            $totalWeight = array_sum($request->items['weight']);
            $totalAmount = array_sum($request->items['amount']);

            /* ---------- UPDATE FAST BOOKING (MASTER) ---------- */
            $booking->update([
                'booking_no'     => $request->booking_no,
                'from_branch_id' => $request->from_branch_id,
                'to_branch_id'   => $request->to_branch_id,
                'network'        => $request->network,
                'payment_type'   => $request->payment_type,
                'forwarding_no'  => $request->forwarding_no,
                'eway_bill_no'   => $request->eway_bill_no,
                'slip_no'        => $request->slip_no ?? $booking->slip_no, // agar slip_no bhi update karna ho
                'total_pcs'      => $totalPcs,
                'total_weight'   => $totalWeight,
                'total_amount'   => $totalAmount,
                'remark'         => $request->remark,
            ]);

            /* ---------- UPDATE ITEMS ---------- */
            // Delete old items first
            $booking->items()->delete();

            // Create new items from request
            foreach ($request->items['tracking_no'] as $index => $trackingNo) {
                FastBookingItem::create([
                    'fast_booking_id' => $booking->id,
                    'tracking_no'     => $trackingNo,
                    'receiver_name'   => $request->items['receiver_name'][$index],
                    'address'         => $request->items['address'][$index],
                    'pcs'             => $request->items['pcs'][$index],
                    'weight'          => $request->items['weight'][$index],
                    'amount'          => $request->items['amount'][$index],
                ]);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Fast Booking updated successfully',
                'redirect' => route('fast_bookings.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }




    public function fastbooking_edit($id)
    {
        $booking = FastBooking::with('items')->findOrFail($id);
        $branches = Hub::all();
        $networks = [
            'DTDC',
            'Blue Dart',
            'Delhivery',
            'XpressBees',
            'Ecom Express',
            'Amazon Transport',
            'Shadowfax',
            'Ekart',
        ];

        return view('backend.fastbooking.create', compact('booking', 'branches', 'networks'));
    }





    public function fastbooking_view(Request $request) {}

    public function fastbooking_delete(Request $request) {}

    public function drs_create()
    {
        $deliveryBoys = DeliveryMan::with('user')->where('status', 1)->get();
        $deliveryBoysData = $deliveryBoys->map(function ($boy) {
            return [
                'id' => $boy->id,
                'user_id' => $boy->user_id,
                'name' => $boy->user->name ?? 'N/A',
                'status' => $boy->status,
            ];
        });
        Log::info('delivery_boys with names', ['data' => $deliveryBoysData]);

        $areas = Hub::where('status', 1)->pluck('name')->toArray();

        return view('backend.drs.create', compact('deliveryBoys', 'areas'));
    }

    public function printShipper()
    {
        $bookingsData = FastBooking::with(['items'])->get();
        logger('latest', ['data' => $bookingsData]);
        return view('backend.fastbooking.print_shipper', compact('bookingsData'));
    }



    public function printSticker()
    {
        // ✅ fast booking ke saath parent data load
        $fastBookings = FastBookingItem::with('fastBooking')->get();

        $generator = new BarcodeGeneratorPNG();

        foreach ($fastBookings as $booking) {

            $trackingNo = (string) $booking->tracking_no;

            $barcodeData = $generator->getBarcode(
                $trackingNo,
                $generator::TYPE_CODE_128
            );

            $fileName = 'barcodes/' . $trackingNo . '.png';

            Storage::disk('public')->put($fileName, $barcodeData);

            $booking->barcode_image = $fileName;
            // $booking->save(); // agar DB me save karna ho
        }

        return view('backend.fastbooking.print_sticker', compact('fastBookings'));
    }


    public function drs_store(Request $request)
    {
        $request->validate([
            'drs_no'          => 'required|unique:drs_entries,drs_no',
            'date'            => 'required|date',
            'time'            => 'required',
            'shipments'       => 'required|array|min:1',
            'shipments.*.tracking_no' => 'required|string',
            'shipments.*.pincode' => 'required|string',
            'shipments.*.area' => 'required|string',
            'shipments.*.receiver_name' => 'required|string',
            'shipments.*.delivery_boy_id' => 'required|exists:delivery_man,id',
            'shipments.*.weight' => 'nullable|numeric|min:0',
            'shipments.*.pieces' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // ✅ 1. Insert DRS Entry
            $drs = DrsEntry::create([
                'drs_no'           => $request->drs_no,
                'area_name'        => $request->shipments[0]['area'], // Use first shipment's area
                'drs_date'         => $request->date,
                'drs_time'         => $request->time,
                'delivery_boy_id'  => $request->shipments[0]['delivery_boy_id'], // Use first shipment's delivery boy
                'pincode'          => $request->shipments[0]['pincode'], // Use first shipment's pincode
                'total_shipments'  => count($request->shipments),
            ]);

            // ✅ 2. Insert Shipments
            foreach ($request->shipments as $shipment) {
                DrsShipment::create([
                    'drs_entry_id'   => $drs->id,
                    'tracking_no'    => $shipment['tracking_no'],
                    'booking_station' => $shipment['area'], // Assuming area is booking station
                    'weight'         => $shipment['weight'] ?? 0,
                    'pcs'            => $shipment['pieces'],
                    'receiver_name'  => $shipment['receiver_name'],
                    'address'        => $shipment['area'], // Assuming area as address for now
                ]);
                ConsignmentStatusHistory::create([
                    'tracking_number' => $shipment['tracking_no'],
                    'status' => 'out_for_delivery',
                    'remarks' => 'Shipment out for delivery',
                    'drs_id' => $drs->id,
                    'branch_id' => $shipment['area'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'DRS entry with ' . count($request->shipments) . ' shipments added successfully!',
                'redirect' => route('drs.index')
            ]);


            // return response()->json([
            //     'success' => true,
            //     'message' => 'DRS entry with ' . count($request->shipments) . ' shipments added successfully!',
            // ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getEntriesData()
    {
        $entries = DrsEntry::with('deliveryBoy')->latest()->get();

        // Area wise shipments count
        $areaStats = $entries->groupBy('area')
            ->map(fn($group) => $group->sum('total_shipments'))
            ->toArray();

        // Delivery boy wise shipments
        $boyStats = $entries->groupBy('delivery_boy_id')
            ->map(fn($group) => [
                'boy_name' => $group->first()->deliveryBoy->name,
                'count' => $group->count(),
                'shipments' => $group->sum('total_shipments')
            ])
            ->toArray();

        return response()->json([
            'area_stats' => $areaStats,
            'boy_stats' => $boyStats,
            'total_entries' => $entries->count(),
            'total_shipments' => $entries->sum('total_shipments'),
            'entries' => $entries
        ]);
    }

    public function drs_view()
    {
        // return view('backend.drs.create');
    }
    public function drs_edit($id)
    {
        $drs = DrsEntry::with('shipments')->findOrFail($id);
        logger()->info('DRS Edit', [
            'DATA' => $drs
        ]);
        return view('backend.drs.update', compact('drs'));
    }
    public function drs_update(Request $request, $id)
    {
        $request->validate([
            'drs_status'        => 'required|in:out_for_delivery,delivered,undelivered',
            'delivery_date'     => 'nullable|date',
            'remarks'           => 'nullable|string|max:255',
            'scan_tracking_no'  => 'nullable|string',  // naya input: scanned tracking number
        ]);

        DB::transaction(function () use ($request, $id) {

            $drs = DrsEntry::findOrFail($id);

            $drs->drs_status    = $request->drs_status;
            $drs->delivery_date = $request->delivery_date ?? now()->toDateString();
            $drs->remarks       = $request->remarks;
            $drs->updated_by    = auth()->id();
            $drs->is_closed     = ($request->drs_status === 'delivered') ? 1 : 0;
            $drs->save();

            // Agar scan_tracking_no diya gaya hai, to sirf wahi shipment update karo
            if ($request->filled('scan_tracking_no')) {
                $shipment = DrsShipment::where('drs_entry_id', $drs->id)
                    ->where('tracking_no', $request->scan_tracking_no)
                    ->first();

                if ($shipment) {
                    // Status ko mapping karo, jese delivered => delivered, undelivered => delivery_failed etc.
                    $status = match ($request->drs_status) {
                        'delivered' => 'delivered',
                        'undelivered' => 'delivery_failed',
                        default => 'out_for_delivery',
                    };

                    ConsignmentStatusHistory::create([
                        'tracking_number' => $shipment->tracking_no,
                        'status'          => $status,
                        'remarks'         => $request->remarks ?? ucfirst(str_replace('_', ' ', $status)),
                        'drs_id'          => $drs->id,
                        'branch_id'       => auth()->user()->branch_id ?? null,
                        'created_at'      => now(),
                    ]);
                } else {
                    // Agar tracking number galat hai to error throw kar sakte ho ya ignore kar sakte ho
                    throw ValidatorsValidationException::withMessages([
                        'scan_tracking_no' => 'Invalid tracking number for this DRS.',
                    ]);
                }
            } else {
                // Agar scan_tracking_no nahi diya, to saare shipments ke liye update karo (old behavior)
                $shipments = DrsShipment::where('drs_entry_id', $drs->id)->get();

                foreach ($shipments as $shipment) {
                    $status = match ($request->drs_status) {
                        'delivered' => 'delivered',
                        'undelivered' => 'delivery_failed',
                        default => 'out_for_delivery',
                    };

                    ConsignmentStatusHistory::create([
                        'tracking_number' => $shipment->tracking_no,
                        'status'          => $status,
                        'remarks'         => $request->remarks ?? ucfirst(str_replace('_', ' ', $status)),
                        'branch_id'          => $drs->id,
                        'created_at'      => now(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('drs.index')
            ->with('success', 'DRS updated and tracking history added successfully');
    }

    public function drs_remove()
    {
        // return view('backend.drs.create');
    }



    public function branchfilter(Request $request)
    {
        // Start query for payments
        $query = BranchPaymentGet::query();

        // Branch filter - filter by from_branch
        if ($request->from_branch) {
            $query->where('from_branch_id', $request->from_branch);
        }

        // Transport Type filter
        if ($request->transport_type) {
            $query->where('transport_type', $request->transport_type);
        }

        $payments = $query->get();
        $hubs = Hub::all()->keyBy('id'); // Sabhi hubs list ke liye
        $allBranches = Hub::all(); // Branches for dropdown

        return view('backend.hub.branch', compact('payments', 'hubs', 'allBranches', 'request'));
    }

    public function exportBranchPayments(Request $request)
    {
        try {
            // Start query for payments
            $query = BranchPaymentGet::query();

            // Branch filter - filter by from_branch
            if ($request->from_branch) {
                $query->where('from_branch_id', $request->from_branch);
            }

            // Transport Type filter
            if ($request->transport_type) {
                $query->where('transport_type', $request->transport_type);
            }

            $payments = $query->get();
            $hubs = Hub::all()->keyBy('id');

            $fileName = 'branch-payments-' . \Carbon\Carbon::now()->format('d-m-Y-His') . '.xlsx';

            return Excel::download(new BranchPaymentExport($payments, $hubs), $fileName);
        } catch (\Throwable $th) {
            Toastr::error(__('parcel.error_msg'), __('message.error'));
            return redirect()->back();
        }
    }



    public function getHubs(Request $request)
    {
        $city = $request->query('city');
        $hubs = Hub::when($city, function ($query, $city) {
            return $query->where('city', $city);
        })->select('id', 'name')->get();

        return response()->json(['hubs' => $hubs]);
    }

    public function exportHubs(Request $request)
    {
        try {
            // Get filtered hubs using the same filter logic as index
            $query = Hub::query();

            if ($request->name) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->phone) {
                $query->where('phone', 'like', '%' . $request->phone . '%');
            }

            // Get all matching hubs without pagination
            $hubs = $query->orderByDesc('id')->get();

            $fileName = 'hubs-' . \Carbon\Carbon::now()->format('d-m-Y-His') . '.xlsx';

            return Excel::download(new HubExport($hubs), $fileName);
        } catch (\Throwable $th) {
            Toastr::error(__('parcel.error_msg'), __('message.error'));
            return redirect()->back();
        }
    }

    public function filter(Request $request)
    {
        $hubs = $this->repo->filter($request);
        return view('backend.hub.index', compact('hubs', 'request'));
    }



    public function create()
    {
        return view('backend.hub.create');
    }


    public function store(Request $request)
    {

        // dd($request->all());
        if ($this->repo->store($request)) {
            Toastr::success(__('hub.added_msg'), __('message.success'));
            return redirect()->route('hubs.index');
        } else {
            Toastr::error(__('hub.error_msg'), __('message.error'));
            return redirect()->back();
        }
    }


    // public function edit($id)
    // {
    //     $hub = Hub::find($id);
    //     Log::info('branch_data', ['data' => $hub]);

    //     if (!$hub) {
    //         abort(404, 'Hub not found');
    //     }

    //     return view('backend.hub.branch-edit', compact('hub'));
    // }

    public function edit($id)
    {
        $hub = Hub::with(['serviceAreas', 'rateSlabs'])->find($id);

        if (!$hub) {
            abort(404, 'Hub not found');
        }

        Log::info('branch_data', ['data' => $hub]);

        return view('backend.hub.branch-edit', compact('hub'));
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());

        if ($this->repo->update($request, $id)) {
            Toastr::success(__('hub.updated_msg'), __('message.success'));
            return redirect()->route('hubs.index');
        } else {
            Toastr::error(__('hub.error_msg'), __('message.error'));
            return redirect()->back()->withInput();
        }
    }


    public function destroy($id)
    {
        $this->repo->delete($id);
        Toastr::success(__('hub.delete_msg'), __('message.success'));
        return back();
    }


    public function view(Request $request, $id)
    {

        $data['parcels'] = $this->repo->parcelFilter($request, $id)->paginate(15);
        $data['t_parcels'] = $this->repo->parcelFilter($request, $id)->get();
        $data['total_parcels'] = $data['t_parcels']->count();
        $data['total_cash_collection'] = $data['t_parcels']->sum('cash_collection');
        $data['total_delivered_cash_collection'] = $this->repo->parcelFilter($request, $id)->where('status', ParcelStatus::DELIVERED)->get()->sum('cash_collection');
        $data['total_partials_delivered_cash_collection'] = $this->repo->parcelFilter($request, $id)->where('status', ParcelStatus::PARTIAL_DELIVERED)->get()->sum('cash_collection');
        $data['total_in_transit_cash_collection'] = $this->repo->parcelFilter($request, $id)->whereNotIn('status', [ParcelStatus::DELIVERED, ParcelStatus::PARTIAL_DELIVERED])->get()->sum('cash_collection');
        $data['total_delivery_charges'] = $data['t_parcels']->sum('delivery_charge');
        $data['total_vat_amount'] = $data['t_parcels']->sum('vat_amount');
        $data['parcelsGrouped'] = $data['t_parcels']->groupBy('status');
        return view('backend.hub.view', compact('data', 'request', 'id'));
    }

    public function branchInvoice($paymentId)
    {
        $payment = BranchPaymentGet::findOrFail($paymentId);

        // Ek simple invoice object structure create kar rahe hain
        $invoice = (object) [
            'from_branch' => $payment->from_branch_id,
            'to_branch' => $payment->to_branch_id,
            'transport_type' => $payment->transport_type,
            'description' => $payment->description,
            'quantity' => $payment->quantity,
            'amount' => $payment->amount,
            'date' => $payment->created_at,
        ];

        return view('backend.hub.branch_invoice', compact('invoice'));
    }

    // public function estimate(Request $request)
    // {
    //     $hubs = Hub::all()->keyBy('id');
    //     $query = BranchPaymentGet::query();

    //     if ($request->from_branch) {
    //         $query->where('from_branch_id', $request->from_branch);
    //     }
    //     if ($request->transport_type) {
    //         $query->where('transport_type', $request->transport_type);
    //     }

    //     $records = $query->get();


    //     return view('backend.hub.branch-estimate', compact('records', 'hubs'));
    // }


    public function estimate(Request $request)
    {
        $hubs = Hub::all()->keyBy('id');

        $outRecords = collect();
        $inRecords = collect();

        $fromBranch = null;
        $toBranch = null;
        $inFromBranch = null;
        $receiveBranch = null;
        $manifestNo = null;

        if ($request->type === 'out') {
            // Show filtered OUT manifest after user selects branches
            $outQuery = BranchPaymentGet::where('request_type', 'out');

            if ($request->from_branch) {
                $outQuery->where('from_branch_id', $request->from_branch);
                $fromBranch = $hubs->has($request->from_branch) ? $hubs->get($request->from_branch)->name : null;
            }
            if ($request->to_branch) {
                $outQuery->where('to_branch_id', $request->to_branch);
                $toBranch = $hubs->has($request->to_branch) ? $hubs->get($request->to_branch)->name : null;
            }

            $outRecords = $outQuery->get();
            $manifestNo = $outRecords->first()?->manifest_no;
        } elseif ($request->type === 'in') {
            $inQuery = BranchPaymentGet::where('request_type', 'in');

            if ($request->from_branch) {
                $inQuery->where('from_branch_id', $request->from_branch);
                $fromBranch = $hubs->has($request->from_branch) ? $hubs->get($request->from_branch)->name : null;
            }
            if ($request->to_branch) {
                $inQuery->where('to_branch_id', $request->to_branch);
                $toBranch = $hubs->has($request->to_branch) ? $hubs->get($request->to_branch)->name : null;
            }
            $inRecords = $inQuery->get();
            $manifestNo = $inRecords->first()?->manifest_no;
        }

        return view('backend.hub.branch-estimate', compact(
            'outRecords',
            'inRecords',
            'hubs',
            'fromBranch',
            'toBranch',
            'manifestNo'
        ));
    }


    public function estimatePerDay(Request $request)
    {
        $hubs = Hub::all()->keyBy('id');
        $query = BranchPaymentGet::query();

        // Filter by branch
        if ($request->from_branch) {
            $query->where('from_branch_id', $request->from_branch);
        }

        // Filter by transport type
        if ($request->transport_type) {
            $query->where('transport_type', $request->transport_type);
        }

        // Filter only today's records
        $query->whereDate('created_at', Carbon::today());

        $records = $query->get();

        return view('backend.hub.branch-estimate', compact('records', 'hubs'));
    }


    public function parcelMultiplePrintLabelEstimateTransaction(Request $request)
    {
        // 🔹 Step 1: Get selected parcel IDs
        $parcelIds = $request->input('parcels', []); // Example: [1,2,3]

        if (empty($parcelIds)) {
            return back()->with('error', 'No parcels selected!');
        }

        // 🔹 Step 2: Fetch selected parcels
        $parcels = Parcel::whereIn('hub_id', $parcelIds)->get();

        if ($parcels->isEmpty()) {
            return back()->with('error', 'No valid parcels found!');
        }

        // 🔹 Step 3: Hub-wise count
        $hubCounts = Parcel::whereIn('hub_id', $parcelIds)
            ->select('hub_id', \DB::raw('COUNT(*) as total'))
            ->groupBy('hub_id')
            ->get();

        // 🔹 Step 4: Delivery Boy Name (assuming delivery_type_id → users table)
        $firstParcel = $parcels->first();
        $deliveryBoyName = 'N/A';

        if ($firstParcel && $firstParcel->delivery_type_id) {
            $deliveryMan = DeliveryMan::find($firstParcel->delivery_type_id);
            if ($deliveryMan && $deliveryMan->user_id) {
                $user = User::find($deliveryMan->user_id);
                $deliveryBoyName = $user->name ?? 'N/A';
            }
        }

        // 🔹 Step 5: Add price from parcel_items if missing
        foreach ($parcels as $parcel) {
            // Agar price field khali hai to barcode se search karo
            if (empty($parcel->price) && !empty($parcel->barcode)) {
                $item = \DB::table('parcel_items')
                    ->where('barcode', $parcel->barcode)
                    ->select('amount')
                    ->first();

                if ($item && isset($item->amount)) {
                    // Dynamic property add kar do
                    $parcel->price = $item->amount;
                } else {
                    $parcel->price = 0;
                }
            } else {
                // Agar selling_price available hai
                $parcel->price = $parcel->price ?? 0;
            }
        }

        // 🔹 Step 6: Generate DR number
        $dr_no = 'DR-' . now()->format('Ymd-His');

        // 🔹 Step 7: Send data to view
        return view('backend.hub.transaction', compact('parcels', 'hubCounts', 'deliveryBoyName', 'dr_no'));
    }
    public function parcelMultiplePrintLabelEstimateTransactionPerday(Request $request)
    {

        // 🔹 Step 1: Get today's date
        $today = \Carbon\Carbon::today();

        // 🔹 Step 2: Get selected parcel IDs (optional — agar use karna ho)
        $parcelIds = $request->input('parcels', []);

        // 🔹 Step 3: Fetch only today's parcels
        $parcelsQuery = Parcel::whereDate('created_at', $today);

        // Agar specific parcel IDs diye gaye ho, toh unhe bhi filter karo
        if (!empty($parcelIds)) {
            $parcelsQuery->whereIn('hub_id', $parcelIds);
        }

        $parcels = $parcelsQuery->get();

        if ($parcels->isEmpty()) {
            return back()->with('error', 'Aaj koi parcel record nahi mila!');
        }

        // 🔹 Step 4: Hub-wise count (sirf aaj ke parcels ka)
        $hubCounts = Parcel::whereDate('created_at', $today)
            ->when(!empty($parcelIds), function ($query) use ($parcelIds) {
                $query->whereIn('hub_id', $parcelIds);
            })
            ->select('hub_id', \DB::raw('COUNT(*) as total'))
            ->groupBy('hub_id')
            ->get();

        // 🔹 Step 5: Delivery Boy Name (first parcel se)
        $firstParcel = $parcels->first();
        $deliveryBoyName = 'N/A';

        if ($firstParcel && $firstParcel->delivery_type_id) {
            $deliveryMan = DeliveryMan::find($firstParcel->delivery_type_id);
            if ($deliveryMan && $deliveryMan->user_id) {
                $user = User::find($deliveryMan->user_id);
                $deliveryBoyName = $user->name ?? 'N/A';
            }
        }

        // 🔹 Step 6: Price assign karo agar missing ho
        foreach ($parcels as $parcel) {
            if (empty($parcel->price) && !empty($parcel->barcode)) {
                $item = \DB::table('parcel_items')
                    ->where('barcode', $parcel->barcode)
                    ->select('amount')
                    ->first();

                $parcel->price = $item->amount ?? 0;
            } else {
                $parcel->price = $parcel->price ?? 0;
            }
        }

        // 🔹 Step 7: Generate DR number
        $dr_no = 'DR-' . now()->format('Ymd-His');

        // 🔹 Step 8: Return view
        return view('backend.hub.transaction-perday', compact('parcels', 'hubCounts', 'deliveryBoyName', 'dr_no'));
    }

    public function printLabelEstimateBranchPerDay(Request $request)
    {
        Log::info('Branch Per Day Label Print Started');

        $today = Carbon::today();

        $hubIds = $request->input('hubs', []);

        $parcels = Hub::whereDate('created_at', $today)
            ->when(!empty($hubIds), function ($query) use ($hubIds) {
                $query->whereIn('id', $hubIds);
            })
            ->get();

        Log::info('Total Parcels Found', ['count' => $parcels->count()]);

        return view('backend.hub.branch-estimate-perday', compact('parcels'));
    }
    public function printLabelEstimateBranch(Request $request)
    {

        $hubs = Hub::all()->keyBy('id');


        Log::info('Total hubs Found', ['data' => $hubs]);

        return view('backend.hub.branch-estimate-all', compact('hubs'));
    }

    public function drs_estimate(Request $request)
    {
        $drsEntries = DrsEntry::with(['shipments', 'deliveryMan.user'])
            ->orderBy('drs_date', 'desc')
            ->get();



        return view('backend.drs.drs_estimate', compact('drsEntries'));
    }


    public function estimate_data(Request $request)
    {
        logger()->info('Estimate data call api');

        $drsNo = $request->input('drs_no');

        $drsEntry = DrsEntry::with(['shipments', 'deliveryMan.user'])
            ->where('drs_no', $drsNo)
            ->first();

        if (!$drsEntry) {
            return response()->json(['error' => 'DRS नंबर नहीं मिला'], 404);
        }

        $data = [
            'drs_no' => $drsEntry->drs_no,
            'drs_date' => $drsEntry->drs_date->format('d/m/Y'),
            'drs_time' => $drsEntry->created_at->format('h:i A'),
            'area_name' => $drsEntry->area_name,
            'branch' => optional($drsEntry->branch)->name,
            'delivery_boy_name' => optional($drsEntry->deliveryMan->user)->name,
            'contact_person' => optional($drsEntry->branch)->contact_person,
            'shipments' => $drsEntry->shipments->map(function ($shipment, $index) {
                return [
                    'sno' => $index + 1,
                    'tracking' => $shipment->tracking_no,
                    'weight' => $shipment->weight,
                    'pcs' => $shipment->pcs,
                    // 'cod' => $shipment->cod_amount,
                    'stamp' => $shipment->stamp_name
                ];
            })->toArray()
        ];

        logger()->info('DRS Entry Data:', ['data' => $data]);

        return response()->json($data);
    }

    public function tracking_index(Request $request)
    {
        return view('backend.hub.tracking.index');
    }

    public function tracking_search(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $trackingNumber = trim($request->tracking_number);

        // 1️⃣ Main consignment data
        $consignment = BranchPaymentRequest::where('tracking_number', $trackingNumber)
            ->with(['fromBranch', 'toBranch'])
            ->first();

        if (!$consignment) {
            return back()->with('error', 'Tracking number not found. Please check and try again.');
        }

        // 2️⃣ Tracking history (REAL source)
        $trackingHistory = ConsignmentStatusHistory::where('tracking_number', $trackingNumber)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($trackingHistory->isEmpty()) {
            return back()->with('error', 'No tracking updates found for this consignment.');
        }

        // 3️⃣ Current status = last record
        $currentStatus = $trackingHistory->last();

        return view(
            'backend.hub.tracking.index',
            compact('consignment', 'trackingHistory', 'currentStatus')
        );
    }
}
