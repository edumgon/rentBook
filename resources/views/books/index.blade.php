<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books - Book Lending Manager</title>
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

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">📖 My Books</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('books.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            ➕ Add Book
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search and Filters -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('books.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Books</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search by title, author, or ISBN..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ $status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                🔍 Search
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600">
                            {{ $books->total() }} book{{ $books->total() != 1 ? 's' : '' }} found
                        </p>
                        <a href="{{ route('books.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Clear filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Books Grid -->
            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($books as $book)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <!-- Book Cover Placeholder -->
                            <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <div class="text-white text-center p-4">
                                    <div class="text-4xl mb-2">📚</div>
                                    <div class="text-sm font-medium">{{ \Illuminate\Support\Str::limit($book->title, 30) }}</div>
                                </div>
                            </div>
                            
                            <!-- Book Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $book->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">by {{ $book->author }}</p>
                                
                                @if($book->publisher)
                                    <p class="text-xs text-gray-500 mb-2">{{ $book->publisher }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $book->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($book->status) }}
                                    </span>
                                    
                                    @if($book->purchase_price)
                                        <span class="text-sm text-gray-600">${{ number_format($book->purchase_price, 2) }}</span>
                                    @endif
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('books.show', $book) }}" 
                                       class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        View
                                    </a>
                                    <a href="{{ route('books.edit', $book) }}" 
                                       class="flex-1 text-center bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $books->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-lg rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No books found</h3>
                    <p class="text-gray-600 mb-6">Get started by adding your first book to your collection.</p>
                    <a href="{{ route('books.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Add Your First Book
                    </a>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
