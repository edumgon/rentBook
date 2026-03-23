<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Loan - Book Lending Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">📚 Book Lending Manager</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                    <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800">Dashboard</a>
                    <a href="{{ route('books.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Books</a>
                    <a href="{{ route('borrowers.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Borrowers</a>
                    <a href="{{ route('loans.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Loans</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">➕ New Loan</h1>
                    <a href="{{ route('loans.index') }}" class="text-blue-600 hover:text-blue-800">
                        ← Back to Loans
                    </a>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Create Loan Form -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form method="POST" action="{{ route('loans.store') }}">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Book Selection -->
                        <div>
                            <label for="book_id" class="block text-sm font-medium text-gray-700 mb-2">Select Book *</label>
                            <select id="book_id" name="book_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Choose a book...</option>
                                @foreach($availableBooks as $book)
                                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }} - {{ $book->author }}
                                    </option>
                                @endforeach
                            </select>
                            @if($availableBooks->count() === 0)
                                <p class="text-sm text-red-600 mt-1">No available books. All books are currently lent out.</p>
                            @endif
                        </div>

                        <!-- Borrower Selection -->
                        <div>
                            <label for="borrower_id" class="block text-sm font-medium text-gray-700 mb-2">Select Borrower *</label>
                            <select id="borrower_id" name="borrower_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Choose a borrower...</option>
                                @foreach($borrowers as $borrower)
                                    <option value="{{ $borrower->id }}" {{ old('borrower_id') == $borrower->id ? 'selected' : '' }}>
                                        {{ $borrower->name }}
                                        @if($borrower->email)
                                            ({{ $borrower->email }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @if($borrowers->count() === 0)
                                <p class="text-sm text-red-600 mt-1">No borrowers available. <a href="{{ route('borrowers.create') }}" class="text-blue-600 hover:text-blue-800">Add a borrower first.</a></p>
                            @endif
                        </div>

                        <!-- Loan Date -->
                        <div>
                            <label for="loan_date" class="block text-sm font-medium text-gray-700 mb-2">Loan Date *</label>
                            <input type="date" id="loan_date" name="loan_date" required
                                   value="{{ old('loan_date', now()->format('Y-m-d')) }}"
                                   max="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Cannot be in the future</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">📝 Notes</label>
                            <textarea id="notes" name="notes" rows="4"
                                      placeholder="Add any notes about this loan..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Optional: Add any notes about this loan</p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('loans.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                @if($availableBooks->count() === 0 || $borrowers->count() === 0) disabled>
                            Create Loan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
                <h3 class="text-lg font-medium text-blue-900 mb-2">💡 Tips for Creating Loans</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Only available books can be lent out</li>
                    <li>• The loan date cannot be in the future</li>
                    <li>• Book status will automatically change to "lent"</li>
                    <li>• You can return books from the loans list</li>
                    <li>• Notes help you remember loan details</li>
                    <li>• All loan history is preserved for tracking</li>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>
