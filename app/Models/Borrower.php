<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TenantService;

class Borrower extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'location',
        'notes',
    ];

    /**
     * Boot the model and add tenant scope.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('tenant', function ($query) {
            $tenantId = TenantService::getCurrentTenantId();
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        });

        // Ensure tenant_id is set when creating
        static::creating(function ($borrower) {
            if (!$borrower->tenant_id) {
                $borrower->tenant_id = TenantService::getCurrentTenantId();
            }
        });
    }

    /**
     * Get the user that owns the borrower.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the loans for the borrower.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get active loans for the borrower.
     */
    public function activeLoans()
    {
        return $this->hasMany(Loan::class)->whereNull('return_date');
    }
}
