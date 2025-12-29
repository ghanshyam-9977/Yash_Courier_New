<?php

namespace App\Repositories\Hub;

use App\Models\Backend\Hub;
use App\Models\Backend\Parcel;
use App\Repositories\Hub\HubInterface;
use Carbon\Carbon;

class HubRepository implements HubInterface
{
    public function all()
    {
        return Hub::orderByDesc('id')->paginate(10);
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
        try {
            $hub           = new Hub();
            $hub->name     = $request->name;
            $hub->phone    = $request->phone;
            $hub->address  = $request->address;
            $hub->city     = $request->city;
            $hub->state     = $request->state;
            $hub->contact_person = $request->contact_person;
            $hub->pincode = $request->pincode;
            $hub->hub_lat  = $request->lat;
            $hub->hub_long = $request->long;
            $hub->status   = $request->status;
            if (isset($request->item_type)) {
                $hub->item_type = $request->item_type;
            }

            if (isset($request->transport_type)) {
                $hub->transport_type = $request->transport_type;
            }

            if (isset($request->unit)) {
                $hub->unit = $request->unit;
            }

            if (isset($request->description)) {
                $hub->description = $request->description;
            }

            if (isset($request->cgst)) {
                $hub->cgst = $request->cgst;
            }

            if (isset($request->sgst)) {
                $hub->sgst = $request->sgst;
            }
            if (isset($request->rate)) {
                $hub->rate = $request->rate;
            }
            if (isset($request->quantity)) {
                $hub->quantity = $request->quantity;
            }
            $hub->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function update($id, $request)
    {
        try {
            $hub           = Hub::find($id);
            $hub->name     = $request->name;
            $hub->phone    = $request->phone;
            $hub->address  = $request->address;
            $hub->hub_lat  = $request->lat;
            $hub->hub_long = $request->long;
            $hub->status   = $request->status;
            $hub->save();
            return true;
        } catch (\Exception $e) {
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
