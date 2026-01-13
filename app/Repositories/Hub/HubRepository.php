<?php

namespace App\Repositories\Hub;

use App\Models\Backend\Hub;
use App\Models\Backend\HubRateSlab;
use App\Models\Backend\HubServiceArea;
use App\Models\Backend\Parcel;
use App\Repositories\Hub\HubInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HubRepository implements HubInterface
{
    // public function all()
    // {
    //     return Hub::orderByDesc('id')->paginate(10);
    // }

    public function all()
    {
        return Hub::with(['serviceAreas', 'rateSlabs'])
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function filter($request)
    {
        return Hub::where(function ($query) use ($request) {
            if ($request->name) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->phone):
                $query->where('phone', 'like', '%' . $request->phone . '%');
            endif;
        })->orderByDesc('id')->paginate(10);
    }
    public function hubs()
    {
        return Hub::all();
    }
    public function get($id)
    {
        return Hub::find($id);
    }


    public function store($request)
    {
        DB::beginTransaction();

        try {

            /* =========================
         * 1️⃣ HUB MASTER SAVE
         * ========================= */
            $hub = Hub::create([
                'name'            => $request->name,
                'phone'           => $request->phone,
                'state'           => $request->state,
                'city'            => $request->city,
                'address'         => $request->address,
                'contact_person'  => $request->contact_person,
                'pincode'         => $request->pincode,
                'hub_lat'         => $request->hub_lat,
                'hub_long'        => $request->hub_long,

                'item_type'       => $request->item_type,
                'transport_type'  => $request->transport_type,
                'weight_unit'     => $request->weight_unit,
                'rate_type'       => $request->rate_type,

                'gst_withdrawn'   => $request->has('gst_withdrawn') ? 1 : 0,
                'cgst'            => $request->cgst,
                'sgst'            => $request->sgst,
                'igst'            => $request->igst,

                'status'          => $request->status,
                'opening_balance' => 0,
                'current_balance' => 0,
            ]);

            /* =========================
         * 2️⃣ SERVICE AREAS SAVE
         * ========================= */
            if ($request->has('service_cities')) {
                // service_cities is an array of arrays indexed by the service area index.
                // We also accept service_states[...] inputs (added in the form) so each
                // service area can have its own state. Fall back to hub state if missing.
                foreach ($request->service_cities as $index => $cities) {
                    $serviceState = $request->input("service_states.$index", $request->state);
                    foreach ($cities as $city) {
                        HubServiceArea::create([
                            'hub_id' => $hub->id,
                            'state'  => $serviceState,
                            'city'   => $city,
                        ]);
                    }
                }
            }

            /* =========================
         * 3️⃣ RATE SLABS SAVE
         * ========================= */
            if ($request->has('slabs')) {
                foreach ($request->slabs as $slab) {
                    HubRateSlab::create([
                        'hub_id'     => $hub->id,
                        'min_weight' => $slab['min'],
                        'max_weight' => $slab['max'],
                        'rate'       => $slab['rate'],
                        'unit'       => $request->weight_unit,
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hub Store Error: ' . $e->getMessage());
            return false;
        }
    }



    public function update($request, $id)
    {
        DB::beginTransaction();

        try {

            /* =========================
         * 1️⃣ HUB MASTER UPDATE
         * ========================= */
            $hub = Hub::findOrFail($id);

            $hub->update([
                'name'            => $request->name,
                'phone'           => $request->phone,
                'state'           => $request->state,
                'city'            => $request->city,
                'address'         => $request->address,
                'contact_person'  => $request->contact_person,
                'pincode'         => $request->pincode,
                'hub_lat'         => $request->hub_lat,
                'hub_long'        => $request->hub_long,

                'item_type'       => $request->item_type,
                'transport_type'  => $request->transport_type,
                'weight_unit'     => $request->weight_unit,
                'rate_type'       => $request->rate_type,

                'gst_withdrawn'   => $request->has('gst_withdrawn') ? 1 : 0,
                'cgst'            => $request->has('gst_withdrawn') ? $request->cgst : null,
                'sgst'            => $request->has('gst_withdrawn') ? $request->sgst : null,
                'igst'            => $request->has('gst_withdrawn') ? $request->igst : null,

                'status'          => $request->status,
            ]);

            /* =========================
         * 2️⃣ SERVICE AREAS UPDATE
         * (DELETE + INSERT)
         * ========================= */
            HubServiceArea::where('hub_id', $hub->id)->delete();

            if ($request->has('service_cities')) {
                foreach ($request->service_cities as $index => $cities) {
                    $serviceState = $request->input("service_states.$index", $request->state);
                    foreach ($cities as $city) {
                        HubServiceArea::create([
                            'hub_id' => $hub->id,
                            'state'  => $serviceState,
                            'city'   => $city,
                        ]);
                    }
                }
            }

            /* =========================
         * 3️⃣ RATE SLABS UPDATE
         * (DELETE + INSERT)
         * ========================= */
            HubRateSlab::where('hub_id', $hub->id)->delete();

            if ($request->has('slabs')) {
                foreach ($request->slabs as $slab) {
                    HubRateSlab::create([
                        'hub_id'     => $hub->id,
                        'min_weight' => $slab['min'],
                        'max_weight' => $slab['max'],
                        'rate'       => $slab['rate'],
                        'unit'       => $request->weight_unit,
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hub Update Error: ' . $e->getMessage());
            return false;
        }
    }


    public function delete($id)
    {
        return Hub::destroy($id);
    }


    public function parcelFilter($request, $id)
    {
        $hub_id = $id;
        return  Parcel::where(['hub_id' => $hub_id])->orderByDesc('id')->where(function ($query) use ($request) {
            if ($request->parcel_date) {
                $date = explode('To', $request->parcel_date);
                if (is_array($date)) {
                    $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
                    $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
                    $query->whereBetween('updated_at', [$from, $to]);
                }
            }
        });
    }
}
