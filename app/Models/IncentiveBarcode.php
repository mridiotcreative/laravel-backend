<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;

class IncentiveBarcode extends Model
{
    protected $table = 'incentive_barcode';

    protected $fillable = [
        'product_id',
        'barcode_number',
        'is_used',
        'customer_id',
        'expired_date',
    ];

    public function getImage()
    {
        return AppHelper::getStorageUrl(config('path.barcode'), $this->photo);
    }
}
