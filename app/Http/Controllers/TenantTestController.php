<?php

namespace App\Http\Controllers;

use App\Services\TenantService;
use App\Models\Book;
use App\Models\Borrower;
use App\Models\Loan;
use Illuminate\Http\Request;

class TenantTestController extends Controller
{
    /**
     * Show tenant information and test data isolation.
     * This should only be available in local environment.
     */
    public function index(Request $request)
    {
        if (app()->environment() !== 'local') {
            abort(403, 'Tenant test only available in local environment');
        }

        $tenantId = TenantService::getCurrentTenantId();
        
        // Test tenant isolation by creating sample data
        $books = Book::count();
        $borrowers = Borrower::count();
        $loans = Loan::count();

        return view('tenant-test', [
            'tenantId' => $tenantId,
            'books' => $books,
            'borrowers' => $borrowers,
            'loans' => $loans,
            'userId' => auth()->id(),
        ]);
    }

    /**
     * Create sample data for testing tenant isolation.
     */
    public function createSampleData()
    {
        if (app()->environment() !== 'local') {
            abort(403, 'Tenant test only available in local environment');
        }

        $user = auth()->user();
        
        // Create sample book
        $book = Book::create([
            'title' => 'Sample Book',
            'author' => 'Test Author',
            'publisher' => 'Test Publisher',
            'isbn' => '1234567890',
            'purchase_date' => now(),
            'purchase_price' => 29.99,
            'status' => 'available',
            'notes' => 'Sample book for testing',
        ]);

        // Create sample borrower
        $borrower = Borrower::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-1234',
            'location' => 'Test Location',
            'notes' => 'Sample borrower for testing',
        ]);

        return redirect()->route('tenant.test')
            ->with('success', 'Sample data created successfully!');
    }
}
