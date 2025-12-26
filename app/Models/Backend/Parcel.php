<?php

namespace App\Models\Backend;

use App\Models\MerchantShops;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\User;
use App\Models\Backend\Deliverycategory;
use App\Models\Backend\Packaging;
use App\Enums\ParcelStatus;
use App\Enums\DeliveryType;
use App\Models\Backend\Merchantpanel\Invoice;
use DNS1D;
use DNS2D;
use Illuminate\Support\Facades\Auth;
use App\Models\Backend\ParcelItem;


class Parcel extends Model
{
    use HasFactory, LogsActivity;

    /********************************************************
     * 1️⃣  Mass‑assignment: don’t list auto timestamps
     ********************************************************/
    protected $fillable = [
        'merchant_id',
        'merchant_shop_id',
        'percel_input',
        'pickup_address',
        'number_of_parcels',
        'pickup_phone',
        'customer_name',
        'customer_phone',
        'invoice_no',

        'delivery_type_id',
        'pickup_date',
        'delivery_date',
        'packaging_id',
        'cash_collection',
        'first_hub_id',
        'hub_id',
        'selling_price',
        'liquid_fragile_amount',
        'packaging_amount',
        'delivery_charge',
        'vat',
        'vat_amount',
        'total_delivery_amount',
        'current_payable',
        'note',
        'tracking_id',
        'status',
        'pickup_lat',
        'pickup_long',
        'parcel_input',
        'barcode',
        'price',

    ];

    protected $table = 'parcels';


    public function scopeOrderByDesc($query, $column)
    {
        return $query->orderBy($column, 'desc');
    }


    public static function generateTrackingId()
    {
        return 'WC' . date('Ymd') . strtoupper(uniqid());
    }
    public function items()   // or parcelItems()
    {
        // parcel_items.parcel_id   → parcels.id
        return $this->hasMany(ParcelItem::class, 'parcel_id', 'id');
    }

    /**
     * Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->useLogName('parcel')
            ->logOnly(['merchant.business_name', 'pickup_address', 'pickup_phone', 'customer_name', 'customer_phone', 'invoice_no', 'cash_collection', 'selling_price', 'delivery_charge', 'total_delivery_amount', 'current_payable'])
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}");
    }

    // Merchant details
    public function merchant()
    {
        return $this->belongsTo(Merchant::class)->with('user');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    // Merchant shop details
    public function merchantShop()
    {
        return $this->belongsTo(MerchantShops::class, 'merchant_shop_id', 'id');
    }

    // Delivery Category details
    public function deliveryCategory()
    {
        return $this->belongsTo(Deliverycategory::class, 'category_id', 'id');
    }

    // Delivery Category details
    public function packaging()
    {
        return $this->belongsTo(Packaging::class);
    }

    public function shop()
    {
        return $this->belongsTo(MerchantShops::class, 'merchant_shop_id', 'id');
    }

    public function parcelEvent()
    {
        return $this->hasMany(ParcelEvent::class, 'parcel_id', 'id');
    }

    public function deliverymanStatement()
    {
        return $this->hasMany(DeliverymanStatement::class, 'parcel_id', 'id');
    }

    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_type_id', 'id');
    }


    public function getMyItemTypeAttribute()
    {
        $itemType = '';
        foreach (trans("parcelType") as $key => $value) {
            if ($this->item_type == $key) {
                $itemType = $value;
            }
        }
        return $itemType;
    }

    public function getMyDeliveryTypeAttribute()
    {
        $deliveryType = '';
        foreach (trans("DeliveryType") as $key => $value) {
            if ($this->delivery_type == $key) {
                $deliveryType = $value;
            }
        }
        return $deliveryType;
    }

    public function getParcelStatusAttribute()
    {
        if ($this->status == ParcelStatus::PENDING) {
            $status = '<span class="badge badge-pill badge-danger">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::PICKUP_ASSIGN) {
            $status = '<span class="badge badge-pill badge-primary">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::RECEIVED_WAREHOUSE) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::DELIVERY_MAN_ASSIGN) {
            $status = '<span class="badge badge-pill badge-warning">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::DELIVERY_RE_SCHEDULE) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::RETURN_TO_COURIER) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::RETURN_ASSIGN_TO_MERCHANT) {
            $status = '<span class="badge badge-pill badge-dark">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::RETURN_MERCHANT_RE_SCHEDULE) {
            $status = '<span class="badge badge-pill badge-dark">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::RETURN_RECEIVED_BY_MERCHANT) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::DELIVER) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::DELIVERED) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::PARTIAL_DELIVERED) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::RETURN_WAREHOUSE) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::ASSIGN_MERCHANT) {
            $status = '<span class="badge badge-pill badge-secondary">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::RETURNED_MERCHANT) {
            $status = '<span class="badge badge-pill badge-dark">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::PICKUP_RE_SCHEDULE) {
            $status = '<span class="badge badge-pill badge-dark">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::RECEIVED_BY_PICKUP_MAN) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $this->status) . '</span>';
        } elseif ($this->status == ParcelStatus::TRANSFER_TO_HUB) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $this->status) . '</span>' . '<br><span class="badge badge-pill badge-danger mt-1">' . $this->hub->name . ' To ' . $this->transferhub->name . '</span>';
        } elseif ($this->status == ParcelStatus::RECEIVED_BY_HUB) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $this->status) . '</span>';
        }
        return $status;
    }

    public function getStatusParcelAttribute($status_id)
    {
        if ($status_id == ParcelStatus::PENDING) {
            $status = '<span class="badge badge-pill badge-danger">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::PICKUP_ASSIGN) {
            $status = '<span class="badge badge-pill badge-primary">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RECEIVED_WAREHOUSE) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::DELIVERY_MAN_ASSIGN) {
            $status = '<span class="badge badge-pill badge-warning">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::DELIVERY_RE_SCHEDULE) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RETURN_TO_COURIER) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RETURN_ASSIGN_TO_MERCHANT) {
            $status = '<span class="badge badge-pill badge-dark">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RETURN_MERCHANT_RE_SCHEDULE) {
            $status = '<span class="badge badge-pill badge-dark">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RETURN_RECEIVED_BY_MERCHANT) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::DELIVER) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::DELIVERED) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::PARTIAL_DELIVERED) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RETURN_WAREHOUSE) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::ASSIGN_MERCHANT) {
            $status = '<span class="badge badge-pill badge-secondary">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RETURNED_MERCHANT) {
            $status = '<span class="badge badge-pill badge-dark">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::PICKUP_RE_SCHEDULE) {
            $status = '<span class="badge badge-pill badge-dark">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RECEIVED_BY_PICKUP_MAN) {
            $status = '<span class="badge badge-pill badge-success">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::TRANSFER_TO_HUB) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $status_id) . '</span>';
        } elseif ($status_id == ParcelStatus::RECEIVED_BY_HUB) {
            $status = '<span class="badge badge-pill badge-info">' . trans("parcelStatus." . $status_id) . '</span>';
        }
        return $status;
    }

    public function getDeliveryTypeNameAttribute()
    {
        if ($this->delivery_type_id == DeliveryType::SAMEDAY) {
            $delivery_type_id = trans("deliveryType." . $this->delivery_type_id);
        } elseif ($this->delivery_type_id == DeliveryType::NEXTDAY) {
            $delivery_type_id = trans("deliveryType." . $this->delivery_type_id);
        } elseif ($this->delivery_type_id == DeliveryType::SUBCITY) {
            $delivery_type_id = trans("deliveryType." . $this->delivery_type_id);
        } elseif ($this->delivery_type_id == DeliveryType::OUTSIDECITY) {
            $delivery_type_id = trans("deliveryType." . $this->delivery_type_id);
        } elseif ($this->delivery_type_id == DeliveryType::BYROAD) {
            $delivery_type_id = trans("deliveryType." . $this->delivery_type_id);
        } elseif ($this->delivery_type_id == DeliveryType::BYAIR) {
            $delivery_type_id = trans("deliveryType." . $this->delivery_type_id);
        }
        return $delivery_type_id;
    }

    public function hub()
    {
        return $this->belongsTo(Hub::class, 'hub_id', 'id');
    }

    public function transferhub()
    {
        return $this->belongsTo(Hub::class, 'transfer_hub_id', 'id');
    }

    public function getBarcodePrintAttribute()
    {
        // Use barcode field if available, otherwise fallback to tracking_id
        $barcodeValue = !empty($this->barcode) ? $this->barcode : $this->tracking_id;
        return DNS1D::getBarcodeHTML($barcodeValue, 'C128', 2, 25);
    }

    public function getQrcodePrintAttribute()
    {
        // Create tracking URL with barcode or tracking_id
        $barcodeValue = !empty($this->tracking_id) ? $this->tracking_id : $this->tracking_id;
        $trackingUrl = url('/tracking?tracking_id=' . urlencode($barcodeValue));

        return 'data:image/png;base64,' . DNS2D::getBarcodePNG($trackingUrl, 'QRCODE', 10, 10, array(1, 1, 1), false);
    }

    // Separate method for QR code from barcode
    public function getBarcodeQrcodeAttribute()
    {
        $barcodeValue = !empty($this->barcode) ? $this->barcode : $this->tracking_id;
        $trackingUrl = url('/tracking?tracking_id=' . urlencode($barcodeValue));

        return 'data:image/png;base64,' . DNS2D::getBarcodePNG($trackingUrl, 'QRCODE', 8, 8, array(1, 1, 1), false);
    }

    public function getStatusNameAttribute()
    {
        return __('parcelStatus.' . $this->status);
    }

    public function getParcelInvoiceAttribute()
    {
        $invoice   = Invoice::where('merchant_id', Auth::user()->merchant->id)->get();
        $inv  = null;
        foreach ($invoice as $in) {
            if (in_array($this->id, $in->parcels_id) == true):
                $inv  = $in;
            endif;
        }
        return $inv;
    }

    public function getAdminParcelInvoiceAttribute()
    {
        $invoice   = Invoice::where('merchant_id', $this->merchant_id)->get();
        $inv  = null;
        foreach ($invoice as $in) {
            if (in_array($this->id, $in->parcels_id) == true):
                $inv  = $in;
            endif;
        }
        return $inv;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
