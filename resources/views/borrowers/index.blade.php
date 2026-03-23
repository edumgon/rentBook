<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowers - Book Lending Manager</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">👥 Borrowers</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('borrowers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            ➕ Add Borrower
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

            <!-- Search -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('borrowers.index') }}">
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search borrowers by name, email, or phone..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            🔍 Search
                        </button>
                        <a href="{{ route('borrowers.index') }}" class="text-blue-600 hover:text-blue-800">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Borrowers Grid -->
            @if($borrowers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($borrowers as $borrower)
                        <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition-shadow duration-300">
                            <!-- Borrower Avatar -->
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($borrower->name, 0, 1) }}
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $borrower->name }}</h3>
                                    @if($borrower->active_loans_count > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $borrower->active_loans_count }} active loan{{ $borrower->active_loans_count != 1 ? 's' : '' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Available
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="space-y-2 mb-4">
                                @if($borrower->email)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="mr-2">📧</span>
                                        <span class="truncate">{{ $borrower->email }}</span>
                                    </div>
                                @endif

                                @if($borrower->phone)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="mr-2">📱</span>
                                        <span>{{ $borrower->phone }}</span>
                                    </div>
                                @endif

                                @if($borrower->location)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="mr-2">📍</span>
                                        <span>{{ $borrower->location }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Statistics -->
                            <div class="border-t pt-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Loans:</span>
                                    <span class="font-medium text-gray-900">{{ $borrower->total_loans_count }}</span>
                                </div>
                                @if($borrower->total_loans_count > 0)
                                    <div class="flex justify-between text-sm mt-1">
                                        <span class="text-gray-600">Active:</span>
                                        <span class="font-medium text-yellow-600">{{ $borrower->active_loans_count }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('borrowers.show', $borrower) }}" 
                                   class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    View
                                </a>
                                <a href="{{ route('borrowers.edit', $borrower) }}" 
                                   class="flex-1 text-center bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $borrowers->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-lg rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">👥</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No borrowers found</h3>
                    <p class="text-gray-600 mb-6">Start adding borrowers to track who you're lending your books to.</p>
                    <a href="{{ route('borrowers.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Add Your First Borrower
                    </a>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
