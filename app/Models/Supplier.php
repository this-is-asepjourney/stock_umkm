<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'code',
        'phone',
        'email',
        'address',
        'contact_person',
        'payment_term',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    // Generate kode supplier otomatis
    public static function generateCode(): string
    {
        $lastSupplier = self::latest('id')->first();
        $lastId = $lastSupplier ? $lastSupplier->id + 1 : 1;
        return 'SUP-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
    }
}