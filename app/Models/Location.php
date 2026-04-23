<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Location extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'type',
        'description'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate code jika kosong
        static::creating(function ($location) {
            if (empty($location->code)) {
                $prefix = match ($location->type) {
                    'warehouse' => 'WH',
                    'rack' => 'RACK',
                    'bin' => 'BIN',
                    default => 'LOC'
                };
                $location->code = $prefix . '-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Get the parent location
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    /**
     * Get the children locations
     */
    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Get all stock movements for this location
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get all stock opnames for this location
     */
    public function stockOpnames(): HasMany
    {
        return $this->hasMany(StockOpname::class);
    }

    /**
     * Get full location path (Warehouse > Rack > Bin)
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }

    /**
     * Get location type label with icon
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'warehouse' => '🏭 Gudang',
            'rack' => '📦 Rak',
            'bin' => '🗃️ Bin',
            default => $this->type
        };
    }

    /**
     * Get location type color
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'warehouse' => '#6366F1', // Indigo
            'rack' => '#10B981',      // Emerald
            'bin' => '#F59E0B',       // Amber
            default => '#6B7280'      // Gray
        };
    }

    /**
     * Scope for warehouse only
     */
    public function scopeWarehouses(Builder $query): Builder
    {
        return $query->where('type', 'warehouse');
    }

    /**
     * Scope for racks only
     */
    public function scopeRacks(Builder $query): Builder
    {
        return $query->where('type', 'rack');
    }

    /**
     * Scope for bins only
     */
    public function scopeBins(Builder $query): Builder
    {
        return $query->where('type', 'bin');
    }

    /**
     * Scope for root locations (no parent)
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if location has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all descendants (recursive)
     */
    public function descendants()
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }

    /**
     * Get available types for select options
     */
    public static function getTypeOptions(): array
    {
        return [
            'warehouse' => '🏭 Gudang',
            'rack' => '📦 Rak',
            'bin' => '🗃️ Bin',
        ];
    }

    /**
     * Get parent options for select
     */
    public static function getParentOptions($excludeId = null)
    {
        $query = self::whereIn('type', ['warehouse', 'rack']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get()->mapWithKeys(function ($location) {
            return [$location->id => $location->full_path];
        });
    }
}