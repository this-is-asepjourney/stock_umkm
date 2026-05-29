<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCompany
{
    /**
     * Boot the trait and apply the global scope.
     */
    protected static function bootBelongsToCompany(): void
    {
        // Only apply if user is authenticated and has a company
        // For CLI/seeders, this can be bypassed by checking app()->runningInConsole() if needed,
        // but typically we want it everywhere unless explicitly withoutGlobalScope().
        static::addGlobalScope('company', function (Builder $builder) {
            if (auth()->hasUser() && auth()->user()->company_id) {
                $builder->where($builder->getModel()->getTable() . '.company_id', auth()->user()->company_id);
            }
        });

        // Automatically set company_id when creating a new record
        static::creating(function ($model) {
            if (auth()->hasUser() && auth()->user()->company_id && !$model->company_id) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }

    /**
     * Relationship to the Company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
