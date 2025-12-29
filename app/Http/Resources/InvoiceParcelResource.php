<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceParcelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       
                $status ='';

                if( $this->parcel_status == \App\Enums\ParcelStatus::RETURN_TO_COURIER ):
                    $status .= trans("parcelStatus.24").', ';
                endif;
        
                if($this->parcel->partial_delivered == \App\Enums\BooleanStatus::YES): 
                    $status .= trans("parcelStatus.".\App\Enums\ParcelStatus::PARTIAL_DELIVERED ); 
                else:
                    if( $this->parcel->status != \App\Enums\ParcelStatus::RETURN_TO_COURIER ):
                        $status .= __('parcelStatus.'.$this->parcel->status);
                    endif;
                endif;
          
                return [
                    'updated_at'        =>  Carbon::parse($this->parcel->update_at)->format('d-m-Y'),
                    'customer_name'     => $this->parcel->customer_name,
                    'customer_phone'    => $this->parcel->customer_phone,
                    'cutomer_address'   => $this->parcel->customer_address,
                    'invoice_id'        => $this->parcel->invoice_no,
                    'tracking_id'       => $this->parcel->tracking_id,
                    'status'            => $status,
                    'invoice_status'    => __('invoice.'.$this->invoice->status),
                    'cash_collection'   => $this->collected_amount,
                    'delivery_charge'   => $this->total_delivery_amount,
                    'return_charge'     => $this->return_charge,
                    'vat'               => $this->vat_amount,
                    'cod_charge'        => $this->cod_amount,
                    'total_charge'      => $this->total_charge_amount,
                    'current_payable'   => $this->current_payable
                ];

    }
}
