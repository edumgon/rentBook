<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book - Book Lending Manager</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">➕ Add New Book</h1>
                    <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-800">
                        ← Back to Books
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

            <!-- Open Library Search Section -->
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">🔍 Search Open Library</h2>
                <p class="text-sm text-gray-600 mb-4">Find your book automatically by searching the Open Library database.</p>
                
                <div class="space-y-4">
                    <!-- Title/Author Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search by Title or Author</label>
                        <div class="flex space-x-2">
                            <input type="text" id="search-query" placeholder="Enter book title or author..." 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" onclick="searchBooks()" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Search
                            </button>
                        </div>
                    </div>

                    <!-- ISBN Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Or search by ISBN</label>
                        <div class="flex space-x-2">
                            <input type="text" id="isbn-input" placeholder="Enter ISBN (10 or 13 digits)..." 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" onclick="searchByIsbn()" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                Find by ISBN
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search Results -->
                <div id="search-results" class="mt-4 hidden">
                    <h3 class="text-md font-semibold text-gray-900 mb-2">Search Results</h3>
                    <div id="results-container" class="space-y-2 max-h-64 overflow-y-auto"></div>
                </div>
            </div>

            <!-- Manual Entry Form -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">✍️ Manual Entry</h2>
                <p class="text-sm text-gray-600 mb-4">Enter book details manually or use the search results above.</p>
                
                <form method="POST" action="{{ route('books.store') }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Author -->
                        <div class="md:col-span-2">
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Author *</label>
                            <input type="text" id="author" name="author" required
                                   value="{{ old('author') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Publisher -->
                        <div>
                            <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">Publisher</label>
                            <input type="text" id="publisher" name="publisher"
                                   value="{{ old('publisher') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                            <input type="text" id="isbn" name="isbn"
                                   value="{{ old('isbn') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Purchase Date -->
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">Purchase Date</label>
                            <input type="date" id="purchase_date" name="purchase_date"
                                   value="{{ old('purchase_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Purchase Price -->
                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">Purchase Price</label>
                            <input type="number" id="purchase_price" name="purchase_price" step="0.01" min="0"
                                   value="{{ old('purchase_price') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="available" selected>Available</option>
                                <option value="lent">Lent</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('books.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Add Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        let searchTimeout;

        function searchBooks() {
            const query = document.getElementById('search-query').value.trim();
            
            if (query.length < 2) {
                showResults([], 'Please enter at least 2 characters to search.');
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetch(`/books/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showResults(data.data, `Found ${data.count} book${data.count !== 1 ? 's' : ''}`);
                        } else {
                            showResults([], data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        showResults([], 'Search failed. Please try again.');
                    });
            }, 500);
        }

        function searchByIsbn() {
            const isbn = document.getElementById('isbn-input').value.trim();
            
            if (!isbn) {
                showResults([], 'Please enter an ISBN.');
                return;
            }

            fetch(`/books/search-by-isbn?isbn=${encodeURIComponent(isbn)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showResults([data.data], 'Found 1 book');
                        // Auto-fill the form with the found book
                        fillFormWithBook(data.data);
                    } else {
                        showResults([], data.message);
                    }
                })
                .catch(error => {
                    console.error('ISBN search error:', error);
                    showResults([], 'Search failed. Please try again.');
                });
        }

        function showResults(books, message) {
            const resultsDiv = document.getElementById('search-results');
            const container = document.getElementById('results-container');
            
            resultsDiv.classList.remove('hidden');
            
            if (books.length === 0) {
                container.innerHTML = `<p class="text-sm text-gray-600">${message}</p>`;
                return;
            }

            container.innerHTML = books.map(book => `
                <div class="border rounded-lg p-3 hover:bg-gray-50 cursor-pointer" onclick="fillFormWithBook(${JSON.stringify(book).replace(/"/g, '&quot;')})">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${book.title}</h4>
                            <p class="text-sm text-gray-600">${book.authors.join(', ')}</p>
                            ${book.isbn ? `<p class="text-xs text-gray-500">ISBN: ${book.isbn}</p>` : ''}
                            ${book.publisher ? `<p class="text-xs text-gray-500">Publisher: ${book.publisher.join(', ')}</p>` : ''}
                        </div>
                        ${book.cover_url ? `<img src="${book.cover_url}" alt="${book.title}" class="w-12 h-16 object-cover rounded">` : ''}
                    </div>
                </div>
            `).join('');
        }

        function fillFormWithBook(book) {
            document.getElementById('title').value = book.title || '';
            document.getElementById('author').value = book.authors ? book.authors.join(', ') : '';
            document.getElementById('publisher').value = book.publisher ? book.publisher.join(', ') : '';
            document.getElementById('isbn').value = book.isbn || '';
            
            // Scroll to form
            document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
        }

        // Search on Enter key
        document.getElementById('search-query').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBooks();
            }
        });

        document.getElementById('isbn-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchByIsbn();
            }
        });
    </script>
</body>
</html>
