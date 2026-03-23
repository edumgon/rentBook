<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TenantService;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'book_id',
        'borrower_id',
        'loan_date',
        'return_date',
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
            'loan_date' => 'date',
            'return_date' => 'date',
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
        static::creating(function ($loan) {
            if (!$loan->tenant_id) {
                $loan->tenant_id = TenantService::getCurrentTenantId();
            }
        });
    }

    /**
     * Get the user that owns the loan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book for the loan.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the borrower for the loan.
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    /**
     * Get the borrower name (handles deleted borrowers).
     */
    public function getBorrowerNameAttribute()
    {
        return $this->borrower ? $this->borrower->name : 'Deleted Borrower';
    }

    /**
     * Check if the loan is currently active.
     */
    public function isActive(): bool
    {
        return is_null($this->return_date);
    }

    /**
     * Mark the loan as returned.
     */
    public function markAsReturned(): void
    {
        $this->return_date = now();
        $this->save();
        
        // Update book status
        if ($this->book) {
            $this->book->status = 'available';
            $this->book->save();
        }
    }
}
