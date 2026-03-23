<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TenantService;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'title',
        'author',
        'publisher',
        'isbn',
        'purchase_date',
        'purchase_price',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'status' => 'string',
        ];
    }

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
        static::creating(function ($book) {
            if (!$book->tenant_id) {
                $book->tenant_id = TenantService::getCurrentTenantId();
            }
        });
    }

    /**
     * Get the user that owns the book.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the loans for the book.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get current loan if book is lent.
     */
    public function currentLoan()
    {
        return $this->hasOne(Loan::class)->whereNull('return_date');
    }
}
