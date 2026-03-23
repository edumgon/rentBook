<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\OpenLibraryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    private OpenLibraryService $openLibraryService;

    public function __construct(OpenLibraryService $openLibraryService)
    {
        $this->openLibraryService = $openLibraryService;
    }

    /**
     * Display a listing of the books.
     */
    public function index(Request $request)
    {
        $books = Book::when($request->search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        })
        ->when($request->status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->orderBy('title')
        ->paginate(12);

        return view('books.index', [
            'books' => $books,
            'search' => $request->search,
            'status' => $request->status,
            'statuses' => ['available', 'lent']
        ]);
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0|max:999999.99',
            'status' => ['required', Rule::in(['available', 'lent'])],
            'notes' => 'nullable|string|max:1000',
        ]);

        Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        // Ensure tenant access
        return view('books.show', [
            'book' => $book,
            'currentLoan' => $book->currentLoan
        ]);
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        return view('books.edit', [
            'book' => $book,
            'statuses' => ['available', 'lent']
        ]);
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => ['nullable', 'string', 'max:20', Rule::unique('books', 'isbn')->ignore($book->id)],
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0|max:999999.99',
            'status' => ['required', Rule::in(['available', 'lent'])],
            'notes' => 'nullable|string|max:1000',
        ]);

        $book->update($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        // Check if book has active loans
        if ($book->currentLoan) {
            return redirect()
                ->route('books.index')
                ->with('error', 'Cannot delete book with active loans.');
        }

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }

    /**
     * Search for books using Open Library API.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $limit = min($request->get('limit', 10), 20); // Max 20 results

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter at least 2 characters to search.'
            ]);
        }

        $results = $this->openLibraryService->searchBooks($query, $limit);

        return response()->json([
            'success' => true,
            'data' => $results,
            'count' => count($results)
        ]);
    }

    /**
     * Search for a book by ISBN.
     */
    public function searchByIsbn(Request $request)
    {
        $isbn = $request->get('isbn');

        if (empty($isbn)) {
            return response()->json([
                'success' => false,
                'message' => 'ISBN is required.'
            ]);
        }

        // Clean ISBN (remove hyphens, spaces)
        $isbn = preg_replace('/[\s-]/', '', $isbn);

        if (strlen($isbn) < 10 || strlen($isbn) > 13) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ISBN format.'
            ]);
        }

        $result = $this->openLibraryService->searchByIsbn($isbn);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'No book found with this ISBN.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Get book details from Open Library API.
     */
    public function getDetails(Request $request)
    {
        $key = $request->get('key');

        if (empty($key)) {
            return response()->json([
                'success' => false,
                'message' => 'Book key is required.'
            ]);
        }

        $details = $this->openLibraryService->getBookDetails($key);

        if (!$details) {
            return response()->json([
                'success' => false,
                'message' => 'Book details not found.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $details
        ]);
    }

    /**
     * Import book from Open Library API.
     */
    public function importFromApi(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ]);

        Book::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Book imported successfully!',
            'redirect' => route('books.index')
        ]);
    }
}
