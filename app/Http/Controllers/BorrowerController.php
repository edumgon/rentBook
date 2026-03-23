<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the borrowers.
     */
    public function index(Request $request)
    {
        $borrowers = Borrower::when($request->search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        })
        ->orderBy('name')
        ->paginate(12);

        // Get loan statistics for each borrower
        $borrowers->getCollection()->transform(function ($borrower) {
            $borrower->active_loans_count = $borrower->activeLoans()->count();
            $borrower->total_loans_count = $borrower->loans()->count();
            return $borrower;
        });

        return view('borrowers.index', [
            'borrowers' => $borrowers,
            'search' => $request->search,
        ]);
    }

    /**
     * Show the form for creating a new borrower.
     */
    public function create()
    {
        return view('borrowers.create');
    }

    /**
     * Store a newly created borrower in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:borrowers,email',
            'phone' => 'nullable|regex:/^\(\d{2}\)\d{5}-\d{4}$/',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ], [
            'phone.regex' => 'The phone number must be in Brazilian format: (DD)#####-####',
        ]);

        Borrower::create($validated);

        return redirect()
            ->route('borrowers.index')
            ->with('success', 'Borrower added successfully!');
    }

    /**
     * Display the specified borrower.
     */
    public function show(Borrower $borrower)
    {
        // Ensure tenant access
        $borrower->load(['loans' => function ($query) {
            $query->with('book')->orderBy('loan_date', 'desc');
        }]);

        return view('borrowers.show', [
            'borrower' => $borrower,
            'activeLoans' => $borrower->activeLoans()->with('book')->get(),
            'loanHistory' => $borrower->loans()->with('book')->orderBy('loan_date', 'desc')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified borrower.
     */
    public function edit(Borrower $borrower)
    {
        return view('borrowers.edit', [
            'borrower' => $borrower,
        ]);
    }

    /**
     * Update the specified borrower in storage.
     */
    public function update(Request $request, Borrower $borrower)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('borrowers', 'email')->ignore($borrower->id)],
            'phone' => 'nullable|regex:/^\(\d{2}\)\d{5}-\d{4}$/',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ], [
            'phone.regex' => 'The phone number must be in Brazilian format: (DD)#####-####',
        ]);

        $borrower->update($validated);

        return redirect()
            ->route('borrowers.index')
            ->with('success', 'Borrower updated successfully!');
    }

    /**
     * Remove the specified borrower from storage.
     */
    public function destroy(Borrower $borrower)
    {
        // Check if borrower has active loans
        if ($borrower->activeLoans()->count() > 0) {
            return redirect()
                ->route('borrowers.index')
                ->with('error', 'Cannot delete borrower with active loans. Please return all books first.');
        }

        // Get loan history for confirmation message
        $totalLoans = $borrower->loans()->count();
        $borrowerName = $borrower->name;

        // Delete the borrower (loan history will remain but borrower_id will be null)
        // We need to handle the foreign key constraint first
        \DB::transaction(function () use ($borrower) {
            // Set borrower_id to null in loans to preserve history
            $borrower->loans()->update(['borrower_id' => null]);
            
            // Now delete the borrower
            $borrower->delete();
        });

        $message = $totalLoans > 0 
            ? "Borrower '{$borrowerName}' deleted successfully. Loan history ({$totalLoans} loans) has been preserved."
            : "Borrower '{$borrowerName}' deleted successfully.";

        return redirect()
            ->route('borrowers.index')
            ->with('success', $message);
    }

    /**
     * Search borrowers for AJAX requests.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $borrowers = Borrower::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json($borrowers);
    }

    /**
     * Get borrower statistics.
     */
    public function statistics()
    {
        $totalBorrowers = Borrower::count();
        $activeBorrowers = Borrower::whereHas('activeLoans')->count();
        $totalLoans = \App\Models\Loan::count();
        $activeLoans = \App\Models\Loan::whereNull('return_date')->count();

        return response()->json([
            'total_borrowers' => $totalBorrowers,
            'active_borrowers' => $activeBorrowers,
            'total_loans' => $totalLoans,
            'active_loans' => $activeLoans,
        ]);
    }
}
