<?php

namespace App\Http\Controllers\Backend\HubPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\HubPaymentRequest\StoreRequest;
use App\Models\Backend\Hub;
use App\Models\Backend\HubPayment;
use App\Repositories\HubPaymentRequest\HubPaymentRequestInterface;
use App\Repositories\HubManage\HubPayment\HubPaymentInterface;
use App\Enums\ApprovalStatus;
use App\Models\BranchPaymentGet;
use Illuminate\Http\Request;
use App\Models\BranchPaymentRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HubPaymentRequestController extends Controller
{
    protected $repo;
    protected $hubPayments;

    public function __construct(HubPaymentInterface $hubPayments, HubPaymentRequestInterface $repo)
    {
        $this->hubPayments = $hubPayments;
        $this->repo = $repo;
    }

    public function index()
    {
        $payments = $this->hubPayments->getSingleHubPayments(auth()->user()->hub_id);
        return view('backend.hub_panel.hub_payment_request.index', compact('payments'));
    }


    public function index_payment()
    {
        $payments = $this->hubPayments->getSingleHubPayments(auth()->user()->hub_id);

        // Check if the payments are correctly retrieved
        if ($payments->isEmpty()) {
            return redirect()->route('hub-panel.payment-request.index')->with('error', 'No payments found.');
        }

        return view('backend.hub_panel.hub_payment_request.index', compact('payments'));
    }




    public function update_branchss(Request $request, $id)
    {
        Log::info('Attempting to update branch payment', ['id' => $id]);
        Log::debug('Form data submitted', $request->all());

        $payment = BranchPaymentRequest::find($id);

        if (!$payment) {
            Log::warning('Branch payment not found for update', ['id' => $id]);
            Toastr::error(__('hub_payment_request.not_found'), __('message.error'));
            return redirect()->back();
        }

        $originalData = $payment->getOriginal();

        $result = $payment->update([
            'from_branch_id' => $request['from_branch_id'],
            'to_branch_id' => $request['to_branch_id'],
            'transport_type' => $request['transport_type'],
            'amount' => $request['amount'],
            'description' => $request['description'],
            'quantity' => $request['quantity'],
            'unit' => $request['unit'],
        ]);

        if ($result) {
            $payment->refresh(); // Reload the model to get updated data
            $updatedData = $payment->toArray();

            // Log the before and after data for debugging
            Log::info('Data before update', $originalData);
            Log::info('Data after update', $updatedData);

            // Example: Check if a specific field was updated
            if ($originalData['amount'] !== $updatedData['amount']) {
                Log::info('Amount field was updated', [
                    'old' => $originalData['amount'],
                    'new' => $updatedData['amount'],
                ]);
            }

            Toastr::success(__('hub_payment_request.updated'), __('message.success'));
            // return redirect()->back();
            return redirect()->route('hubs.branch.index');
        } else {
            Log::warning('Branch payment update failed', ['id' => $id]);
            Toastr::error(__('hub_payment_request.update_failed'), __('message.error'));
            return redirect()->back();
        }
    }
    public function update_branch_request(Request $request, $id)
    {


        $payment = Hub::find($id);

        if (!$payment) {
            Log::warning('Branch request not found for update', ['id' => $id]);
            Toastr::error(__('hub_payment_request.not_found'), __('message.error'));
            return redirect()->back();
        }

        $originalData = $payment->getOriginal();

        $result = $payment->update([
            // Basic Info
            'name'            => $request->name,
            'phone'           => $request->phone,
            'state'           => $request->state,
            'city'            => $request->city,
            'contact_person'  => $request->contact_person,
            'pincode'         => $request->pincode,
            'address'         => $request->address,

            // Item & Transport
            'item_type'       => $request->item_type,
            'transport_type'  => $request->transport_type,

            // Weight & Rate
            'unit'            => $request->unit,
            'quantity'        => $request->quantity,
            'rate'            => $request->rate,

            // GST
            'include_gst'     => $request->has('include_gst') ? 1 : 0,
            'cgst'            => $request->include_gst ? $request->cgst : 0,
            'sgst'            => $request->include_gst ? $request->sgst : 0,
            'igst'            => $request->include_gst ? $request->igst : 0,

            // Extra
            'description'     => $request->description,
            'status'          => $request->status,
        ]);


        if ($result) {
            $payment->refresh(); // Reload the model to get updated data
            $updatedData = $payment->toArray();

            // Log the before and after data for debugging
            Log::info('Data before update', $originalData);
            Log::info('Data after update', $updatedData);



            Toastr::success(__('hub_payment_request.updated'), __('message.success'));
            // return redirect()->back();
            return redirect()->route('hubs.index');
        } else {
            Log::warning('Branch request update failed', ['id' => $id]);
            Toastr::error(__('branch_update_request.update_failed'), __('message.error'));
            return redirect()->back();
        }
    }

    public function create()
    {
        return view('backend.hub_panel.hub_payment_request.create');
    }




    public function branch_create()
    {
        $branches = Hub::all();
        return view('backend.hub_panel.hub_payment_request.branch_create', compact('branches'));
    }
    // public function ledger_index()
    // {
    //     // Payments
    //     $payments = BranchPaymentGet::all();

    //     // Saare hubs/branches ka id => name map
    //     $hubs = Hub::pluck('name', 'id');

    //     return view(
    //         'backend.hub_panel.ledger.index',
    //         compact('payments', 'hubs')
    //     );
    // }

    public function ledger_index()
    {
        // Unique branch routes (from + to combination)
        $payments = BranchPaymentGet::select('from_branch_id', 'to_branch_id')
            ->groupBy('from_branch_id', 'to_branch_id')
            ->get();

        // Branch id => name
        $hubs = Hub::pluck('name', 'id');

        return view(
            'backend.hub_panel.ledger.index',
            compact('payments', 'hubs')
        );
    }


    public function getByBranch(Request $request)
    {
        $fromBranchId = $request->query('from_branch_id');
        $toBranchId = $request->query('to_branch_id');

        if (!$fromBranchId || !$toBranchId) {
            return response()->json(['error' => 'Invalid branch IDs'], 400);
        }

        $ledger = DB::table('branch_payment_requests')
            ->selectRaw("
        created_at AS entry_date,

        CASE
            WHEN request_type = 'in' AND to_branch_id = ?
                THEN CONCAT(
                    'REC-',
                    id,
                    ' MF.NO.',
                    manifest_no
                )

            WHEN request_type = 'out' AND from_branch_id = ?
                THEN CONCAT(
                    'PAY-',
                    id,
                    ' MF.NO.',
                    manifest_no
                )

            ELSE ''
        END AS particulars,

        CASE
            WHEN request_type = 'in' AND to_branch_id = ?
                THEN IFNULL(total_with_gst, amount)
            ELSE 0
        END AS debit,

        CASE
            WHEN request_type = 'out' AND from_branch_id = ?
                THEN IFNULL(total_with_gst, amount)
            ELSE 0
        END AS credit
    ", [
                $toBranchId,
                $fromBranchId,
                $toBranchId,
                $fromBranchId
            ])
            ->where(function ($query) use ($fromBranchId, $toBranchId) {
                $query->where('from_branch_id', $fromBranchId)
                    ->orWhere('to_branch_id', $toBranchId);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json(['ledger' => $ledger]);
    }





    public function edit($id)
    {
        $payment = BranchPaymentRequest::findOrFail($id);
        return view('hub-panel.payment-request.edit', compact('payment'));
    }



    public function branchedit($id)
    {

        $numericId = (int) $id;
        Log::info('Fetching branch payment for editing', ['id' => $numericId]);

        // $payment = BranchPaymentRequest::find($numericId);
        $payment = BranchPaymentRequest::with(['fromBranch', 'toBranch'])->findOrFail($id);

        if (!$payment) {
            Log::warning('Branch payment not found', ['id' => $numericId]);
            Toastr::error(__('hub_payment_request.not_found'), __('message.error'));
            return redirect()->route('hub-panel.payment-request.index');
        }

        return view('backend.hub.branchedit', compact('payment'));
    }


    /**
     * Generate unique manifest number based on request type
     */
    // private function generateManifestNo(string $requestType): string
    // {
    //     $prefix = strtoupper($requestType) === 'OUT' ? 'OUT-' : 'IN-';

    //     do {
    //         // Create manifest number like OUT-000123 or IN-000456
    //         $number = $prefix . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

    //         // Check if manifest_no already exists
    //         $exists = BranchPaymentRequest::where('manifest_no', $number)->exists();
    //     } while ($exists);

    //     return $number;
    // }


    public function store_branch(Request $request)
    {
        try {
            $isCod = $request->has('is_cod');

            $validated = $request->validate([
                'request_type' => '|in:in,out',
                'item_type' => '|string',
                'tracking_number' => '|string',
                'manifest_no' => '|string',
                'from_branch_id' => '',
                'to_branch_id' => '',
                'transport_type' => '|in:by_road,by_air',
                'unit' => '|string',
                'quantity' => '|numeric|min:0',
                'amount' => '|numeric|min:0',

                'include_gst' => 'nullable',
                'cgst' => 'nullable|numeric|min:0|max:100',
                'sgst' => 'nullable|numeric|min:0|max:100',
                'igst' => 'nullable|numeric|min:0|max:100',

                'description' => '|string',
                'vehicle_no' => '|string|max:50',

                'is_cod' => 'nullable',
                'is_return' => 'nullable',

                'cod_amount' => $isCod ? '|numeric|min:0.01' : 'nullable',
                'cod_payment_mode' => 'nullable|string|max:50',
                'cod_remarks' => 'nullable|string',

                'city'  => '|string|max:100',
                'state' => '|string|max:100',
            ]);

            $validated['is_cod'] = $isCod ? 1 : 0;
            $validated['include_gst'] = $request->has('include_gst') ? 1 : 0;

            if (!$validated['is_cod']) {
                $validated['cod_amount'] = null;
                $validated['cod_payment_mode'] = null;
                $validated['cod_remarks'] = null;
            }

            if (!$validated['is_return']) {
                $validated['return_reason'] = null;
                $validated['return_remarks'] = null;
            }


            if ($validated['is_return']) {
                $validated['amount'] = 0;
                $validated['is_cod'] = 0;
                $validated['cod_amount'] = null;
                $validated['include_gst'] = 0;
            }

            if ($validated['include_gst']) {
                $amount = (float) $request->amount;
                $cgst = (float) ($request->cgst ?? 0);
                $sgst = (float) ($request->sgst ?? 0);
                $igst = (float) ($request->igst ?? 0);

                $validated['total_with_gst'] =
                    $amount +
                    ($amount * $cgst / 100) +
                    ($amount * $sgst / 100) +
                    ($amount * $igst / 100);
            } else {
                $validated['cgst'] = null;
                $validated['sgst'] = null;
                $validated['igst'] = null;
                $validated['total_with_gst'] = null;
            }

            // GENERATE UNIQUE MANIFEST NO
            // $validated['manifest_no'] = $this->generateManifestNo($validated['request_type']);

            BranchPaymentRequest::create($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('branch_payment_request.added_msg')
                ]);
            }

            Toastr::success(__('branch_payment_request.added_msg'), __('message.success'));
            return redirect()->route('hubs.branch.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('branch Request Error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', $e->getMessage())->withInput();
        }
    }





    public function getRate(Request $request)
    {
        $rate = BranchPaymentRequest::where('from_branch_id', $request->from_branch_id)
            ->where('to_branch_id', $request->to_branch_id)
            ->where('transport_type', $request->transport_type)
            ->first();

        if ($rate) {
            return response()->json([
                'success' => true,
                'base_rate' => $rate->base_rate,
                'rate_per_kg' => $rate->rate_per_kg
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Rate not found']);
        }
    }




    public function get($id)
    {
        return BranchPaymentRequest::where('id', $id)
            ->whereHas('fromBranch', function ($query) {
                $query->where('hub_id', auth()->user()->hub_id);
            })
            ->first();
    }

    public function destroy($id)
    {
        try {
            // Find the BranchPaymentRequest by ID
            $payment = BranchPaymentRequest::find($id);

            // Check if the record exists
            if (!$payment) {
                return response()->json(['message' => 'Payment request not found'], 404);
            }

            // Delete the record
            $payment->delete();

            // Return success response
            return redirect()->back()->with('success', 'Payment request delete successfully.');
        } catch (\Exception $e) {
            // Handle any errors during deletion
            return redirect()->back()->with('success', 'Payment request delete successfully.');
        }
    }



    public function getStates()
    {
        $path = storage_path('app/data/states+cities.json');
        $json = json_decode(file_get_contents($path), true);

        // $states = collect($json)->pluck('name');
        $states = array_keys($json);


        Log::info('✅ getStates called', [
            'total_states' => count($states),
            // 'sample_states' => $states->take(5)
            'sample_states' => $states



        ]);

        return response()->json($states);
    }

    public function getCities(Request $request)
    {
        $stateName = $request->input('state');
        $path = storage_path('app/data/states+cities.json');
        $json = json_decode(file_get_contents($path), true);

        $stateData = collect($json)->firstWhere('name', $stateName);
        $cities = $stateData['cities'] ?? [];

        Log::info('✅ getCities called', [
            'requested_state' => $stateName,
            'total_cities' => count($cities),
            'sample_cities' => collect($cities)->pluck('name')->take(5)
        ]);

        return response()->json($cities);
    }



    public function getBranchesByCity($city)
    {
        $branches = Hub::where('city', $city)->get(['id', 'name']);
        Log::info('✅ branch api called', [
            'branch_data' => $branches
        ]);

        return response()->json([
            'count' => $branches->count(),
            'branches' => $branches
        ]);
    }

    // public function getBranchRates(Request $request, $branchId)
    // {
    //     $rate = BranchPaymentRequest::where('to_branch_id', $branchId)->first();
    //     // echo $rate;
    //     if ($rate) {
    //         return response()->json([
    //             'success' => true,
    //             'data' => $rate
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => false
    //     ]);
    // }

    public function getBranchRates(Request $request, $branchId)
    {
        try {
            $itemType = $request->input('item_type');         // 'document' or 'parcel'
            $transportType = $request->input('transport_type'); // Ye ab  hai

            if (!$itemType || !$transportType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item type and transport type are required'
                ], 400);
            }

            // Teeno parameters ke base pe rate fetch karo
            $rate = DB::table('hubs') // Ya jo bhi aapka actual rates table hai (hubs ya rates?)
                ->where('id', $branchId)
                ->where('item_type', $itemType)
                ->where('transport_type', $transportType)
                ->first();

            Log::info('getBranchRates called', [
                'branchId' => $branchId,
                'item_type' => $itemType,
                'transport_type' => $transportType,
                'rate_found' => $rate ? true : false
            ]);

            if ($rate) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'rate' => $rate->rate, // Ya jo bhi fields aapko chahiye
                        // Agar aur fields bhi return karne hai to yaha add kar do
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No rates found for this combination'
            ]);
        } catch (\Exception $e) {
            Log::error('getBranchRates error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    public function getRequestByTracking($tracking)
    {
        try {
            // Find the OUT request by tracking number
            $request = BranchPaymentRequest::where('tracking_number', $tracking)
                ->where('request_type', 'out')
                ->first();


            Log::info('Active data', [
                'data' => $request
            ]);

            if (!$request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tracking number not found or not an OUT request'
                ], 404);
            }

            // Get branch details for city and state
            $fromBranch = hub::find($request->from_branch_id);
            $toBranch = BranchPaymentRequest::find($request->to_branch_id);

            return response()->json([
                'success' => true,
                'data' => [
                    'tracking_number' => $request->tracking_number,
                    'from_branch_id' => $request->from_branch_id,
                    'from_branch_name' => $fromBranch ? $fromBranch->name : null,
                    'to_branch_id' => $request->to_branch_id,
                    'to_branch_name' => $toBranch ? $toBranch->name : null,
                    // 'city' => $request->city,
                    'city' => $fromBranch->city,
                    'state' => $fromBranch->state,
                    'item_type' => $request->item_type,
                    'transport_type' => $request->transport_type,
                    'unit' => $request->unit,
                    'quantity' => $request->quantity,
                    'amount' => $request->amount,
                    'cgst' => $request->cgst,
                    'vehicle_no' => $request->vehicle_no,
                    'sgst' => $request->sgst,
                    'description' => $request->description,
                    'include_gst' => !empty($request->cgst) || !empty($request->sgst),
                    'is_cod' => $request->is_cod,
                    'cod_amount' => $request->cod_amount,
                    'cod_payment_mode' => $request->cod_payment_mode,
                    'cod_remarks' => $request->cod_remarks,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching tracking details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAllBranches()
    {
        $branches = Hub::where('status', 'active')->get(['id', 'name', 'city', 'state']);
        Log::info('Active branches fetched', [
            'count' => $branches->count(),
            'branches' => $branches
        ]);
        return response()->json(['branches' => $branches]);
    }


    public function getStateByCity(Request $request)
    {
        $cityInput = trim($request->query('city'));

        if (strlen($cityInput) < 3) {
            return response()->json([
                'success' => false,
                'state' => null
            ]);
        }

        if (!Storage::exists('data/states+cities.json')) {
            Log::error('JSON file not found');
            return response()->json([
                'success' => false,
                'state' => null
            ]);
        }

        $json = Storage::get('data/states+cities.json');
        $stateCities = json_decode($json, true);

        if (!is_array($stateCities)) {
            Log::error('Invalid JSON format');
            return response()->json([
                'success' => false,
                'state' => null
            ]);
        }

        $normalize = fn($v) => mb_strtolower(trim($v));
        $foundState = null;

        foreach ($stateCities as $state => $cities) {
            foreach ($cities as $city) {
                if ($normalize($city) === $normalize($cityInput)) {
                    $foundState = $state;
                    break 2;
                }
            }
        }

        return response()->json([
            'success' => $foundState !== null,
            'state'   => $foundState,
            'city'    => ucwords($cityInput)
        ]);
    }

    public function getCitiesSuggestions(Request $request)
    {
        $query = trim($request->query('q', '')); // 'q' parameter से search

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        if (!Storage::exists('data/states+cities.json')) {
            return response()->json(['results' => []]);
        }

        $json = Storage::get('data/states+cities.json');
        $stateCities = json_decode($json, true);

        if (!is_array($stateCities)) {
            return response()->json(['results' => []]);
        }

        $normalize = fn($str) => mb_strtolower(preg_replace('/\s+/', ' ', trim($str)));
        $normalizedQuery = $normalize($query);

        $results = [];

        foreach ($stateCities as $state => $cities) {
            foreach ($cities as $city) {
                if (str_contains($normalize($city), $normalizedQuery)) {
                    $results[] = [
                        'city'  => $city,
                        'state' => $state,
                        'label' => $city . ', ' . $state  // suggestion में दिखाने के लिए
                    ];

                    // Max 10-15 results काफी हैं
                    if (count($results) >= 15) break 2;
                }
            }
        }

        // Alphabetical sort by city name
        usort($results, fn($a, $b) => strcmp($a['city'], $b['city']));

        return response()->json(['results' => $results]);
    }
}
