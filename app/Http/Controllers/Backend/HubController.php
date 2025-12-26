<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ParcelStatus;
use App\Exports\BranchPaymentExport;
use App\Exports\HubExport;
use App\Http\Controllers\Controller;
use App\Models\Backend\DeliveryMan;
use App\Models\Backend\Hub;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Hub\StoreHubRequest;
use App\Http\Requests\Hub\UpdateHubRequest;
use App\Models\Backend\Parcel;
use App\Repositories\Hub\HubInterface;
use App\Models\BranchPaymentGet;
use App\Repositories\HubManage\HubPayment\HubPaymentInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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


    public function store(StoreHubRequest $request)
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


    public function edit($id)
    {
        $hub = Hub::find($id);
        Log::info('branch_data', ['data' => $hub]);

        if (!$hub) {
            abort(404, 'Hub not found');
        }

        return view('backend.hub.branch-edit', compact('hub'));
    }

    public function update(UpdateHubRequest $request)
    {
        if ($this->repo->update($request->id, $request)) {
            Toastr::success(__('hub.update_msg'), __('message.success'));
            return redirect()->route('hubs.index');
        } else {
            Toastr::error(__('hub.error_msg'), __('message.error'));
            return redirect()->back();
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

        // OUT Records - where request_type = 'out'
        $outQuery = BranchPaymentGet::where('request_type', 'out');

        // IN Records - where request_type = 'in'
        $inQuery = BranchPaymentGet::where('request_type', 'in');

        // Apply filters to both queries
        if ($request->from_branch) {
            $outQuery->where('from_branch_id', $request->from_branch);
            $inQuery->where('from_branch_id', $request->from_branch);
        }
        if ($request->transport_type) {
            $outQuery->where('transport_type', $request->transport_type);
            $inQuery->where('transport_type', $request->transport_type);
        }

        $outRecords = $outQuery->get();
        $inRecords = $inQuery->get();

        // Log the filtered data
        Log::info('estimate-filtered-data', [
            'from_branch' => $request->from_branch,
            'transport_type' => $request->transport_type,
            'out_records_count' => $outRecords->count(),
            'in_records_count' => $inRecords->count()
        ]);

        return view('backend.hub.branch-estimate', compact('outRecords', 'inRecords', 'hubs'));
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
}
