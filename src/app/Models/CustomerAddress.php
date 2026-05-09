<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    protected $table = 'customer_addresses';

    use HasFactory, SoftDeletes;


    protected $fillable = [
        'customer_id',
        'is_default',
        'address_name',
        'name',          // e.g. "Home", "Office"
        'phone',
        'address',
        'city',
        'state',
        'postcode',
        'country',

    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
