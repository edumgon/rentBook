<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    /**
     * Display a listing of the loans.
     */
    public function index(Request $request)
    {
        $loans = Loan::with(['book', 'borrower'])
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('book', function ($bookQuery) use ($search) {
                        $bookQuery->where('title', 'like', "%{$search}%")
                               ->orWhere('author', 'like', "%{$search}%");
                    })
                    ->orWhereHas('borrower', function ($borrowerQuery) use ($search) {
                        $borrowerQuery->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->status, function ($query, $status) {
                if ($status === 'active') {
                    $query->whereNull('return_date');
                } elseif ($status === 'returned') {
                    $query->whereNotNull('return_date');
                }
            })
            ->orderBy('loan_date', 'desc')
            ->paginate(15);

        return view('loans.index', [
            'loans' => $loans,
            'search' => $request->search,
            'status' => $request->status,
            'statuses' => ['active', 'returned']
        ]);
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $availableBooks = Book::where('status', 'available')->orderBy('title')->get();
        $borrowers = Borrower::orderBy('name')->get();

        return view('loans.create', [
            'availableBooks' => $availableBooks,
            'borrowers' => $borrowers
        ]);
    }

    /**
     * Store a newly created loan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_id' => 'required|exists:borrowers,id',
            'loan_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ], [
            'loan_date.before_or_equal' => 'The loan date cannot be in the future.',
        ]);

        // Check if book is available
        $book = Book::findOrFail($validated['book_id']);
        if ($book->status === 'lent') {
            return redirect()
                ->route('loans.create')
                ->with('error', 'This book is already lent out. Please choose another book.');
        }

        // Create the loan
        $loan = Loan::create($validated);

        // Update book status
        $book->status = 'lent';
        $book->save();

        return redirect()
            ->route('loans.index')
            ->with('success', 'Book loaned successfully!');
    }

    /**
     * Display the specified loan.
     */
    public function show(Loan $loan)
    {
        // Ensure tenant access
        $loan->load(['book', 'borrower']);

        return view('loans.show', [
            'loan' => $loan
        ]);
    }

    /**
     * Show the form for editing the specified loan.
     */
    public function edit(Loan $loan)
    {
        // Only allow editing notes for returned loans
        if ($loan->return_date === null) {
            return redirect()
                ->route('loans.show', $loan)
                ->with('error', 'Cannot edit active loans. Please return the book first.');
        }

        return view('loans.edit', [
            'loan' => $loan
        ]);
    }

    /**
     * Update the specified loan in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        // Only allow editing returned loans
        if ($loan->return_date === null) {
            return redirect()
                ->route('loans.show', $loan)
                ->with('error', 'Cannot edit active loans.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $loan->update($validated);

        return redirect()
            ->route('loans.index')
            ->with('success', 'Loan updated successfully!');
    }

    /**
     * Remove the specified loan from storage.
     */
    public function destroy(Loan $loan)
    {
        // Only allow deleting returned loans
        if ($loan->return_date === null) {
            return redirect()
                ->route('loans.index')
                ->with('error', 'Cannot delete active loans. Please return the book first.');
        }

        $loan->delete();

        return redirect()
            ->route('loans.index')
            ->with('success', 'Loan deleted successfully!');
    }

    /**
     * Return a book.
     */
    public function returnBook(Loan $loan)
    {
        if ($loan->return_date !== null) {
            return redirect()
                ->route('loans.index')
                ->with('error', 'This book has already been returned.');
        }

        // Mark loan as returned
        $loan->return_date = now();
        $loan->save();

        // Update book status
        $book = $loan->book;
        $book->status = 'available';
        $book->save();

        return redirect()
            ->route('loans.index')
            ->with('success', 'Book returned successfully!');
    }

    /**
     * Quick loan creation from book page.
     */
    public function quickCreate(Request $request)
    {
        $bookId = $request->get('book_id');
        $book = Book::findOrFail($bookId);

        if ($book->status === 'lent') {
            return response()->json([
                'success' => false,
                'message' => 'This book is already lent out.'
            ]);
        }

        $borrowers = Borrower::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'book' => $book,
            'borrowers' => $borrowers
        ]);
    }

    /**
     * Quick loan store.
     */
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_id' => 'required|exists:borrowers,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if book is available
        $book = Book::findOrFail($validated['book_id']);
        if ($book->status === 'lent') {
            return response()->json([
                'success' => false,
                'message' => 'This book is already lent out.'
            ]);
        }

        // Create the loan
        $loan = Loan::create([
            'book_id' => $validated['book_id'],
            'borrower_id' => $validated['borrower_id'],
            'loan_date' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update book status
        $book->status = 'lent';
        $book->save();

        return response()->json([
            'success' => true,
            'message' => 'Book loaned successfully!',
            'loan' => $loan->load(['book', 'borrower'])
        ]);
    }

    /**
     * Get loan statistics.
     */
    public function statistics()
    {
        $totalLoans = Loan::count();
        $activeLoans = Loan::whereNull('return_date')->count();
        $returnedLoans = Loan::whereNotNull('return_date')->count();
        $availableBooks = Book::where('status', 'available')->count();
        $lentBooks = Book::where('status', 'lent')->count();

        return response()->json([
            'total_loans' => $totalLoans,
            'active_loans' => $activeLoans,
            'returned_loans' => $returnedLoans,
            'available_books' => $availableBooks,
            'lent_books' => $lentBooks,
        ]);
    }
}
