<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Book Lending Manager</title>
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
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">✏️ Edit Book</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Book
                        </a>
                    </div>
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

            <!-- Edit Form -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form method="POST" action="{{ route('books.update', $book) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title', $book->title) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Author -->
                        <div class="md:col-span-2">
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Author *</label>
                            <input type="text" id="author" name="author" required
                                   value="{{ old('author', $book->author) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Publisher -->
                        <div>
                            <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">Publisher</label>
                            <input type="text" id="publisher" name="publisher"
                                   value="{{ old('publisher', $book->publisher) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                            <input type="text" id="isbn" name="isbn"
                                   value="{{ old('isbn', $book->isbn) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Purchase Date -->
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">Purchase Date</label>
                            <input type="date" id="purchase_date" name="purchase_date"
                                   value="{{ old('purchase_date', $book->purchase_date?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Purchase Price -->
                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">Purchase Price</label>
                            <input type="number" id="purchase_price" name="purchase_price" step="0.01" min="0"
                                   value="{{ old('purchase_price', $book->purchase_price) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="available" {{ $book->status == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="lent" {{ $book->status == 'lent' ? 'selected' : '' }}>Lent</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $book->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Warning for status change -->
                    @if($book->status == 'lent' && old('status', $book->status) == 'available')
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-sm text-yellow-800">
                                ⚠️ You're changing the status from "Lent" to "Available". Make sure the book has actually been returned.
                            </p>
                        </div>
                    @endif

                    <!-- Submit Buttons -->
                    <div class="mt-6 flex justify-between">
                        <div>
                            @if($book->currentLoan)
                                <form method="POST" action="{{ route('loans.return', $book->currentLoan) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        ✅ Mark as Returned First
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('books.show', $book) }}" 
                               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Update Book
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Delete Section -->
            <div class="bg-white shadow-lg rounded-lg p-6 mt-6 border-2 border-red-200">
                <h3 class="text-lg font-semibold text-red-900 mb-2">⚠️ Danger Zone</h3>
                <p class="text-sm text-red-600 mb-4">
                    Once you delete a book, there is no going back. Please be certain.
                </p>
                
                @if($book->currentLoan)
                    <div class="bg-red-50 p-3 rounded-md mb-4">
                        <p class="text-sm text-red-800">
                            This book cannot be deleted because it is currently lent out.
                        </p>
                    </div>
                @else
                    <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Are you sure you want to delete this book? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            🗑️ Delete Book
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
