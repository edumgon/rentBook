<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loans - Book Lending Manager</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">📋 Loans</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('loans.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            ➕ New Loan
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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <div class="text-blue-600">📚</div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Loans</p>
                            <p class="text-lg font-semibold text-gray-900">{{ App\Models\Loan::count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <div class="text-yellow-600">⏳</div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Active</p>
                            <p class="text-lg font-semibold text-gray-900">{{ App\Models\Loan::whereNull('return_date')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <div class="text-green-600">✅</div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Returned</p>
                            <p class="text-lg font-semibold text-gray-900">{{ App\Models\Loan::whereNotNull('return_date')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <div class="text-purple-600">📖</div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Available Books</p>
                            <p class="text-lg font-semibold text-gray-900">{{ App\Models\Book::where('status', 'available')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('loans.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Loans</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search by book title, author, or borrower..." 
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
                            {{ $loans->total() }} loan{{ $loans->total() != 1 ? 's' : '' }} found
                        </p>
                        <a href="{{ route('loans.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Clear filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Loans List -->
            @if($loans->count() > 0)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($loans as $loan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $loan->book->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $loan->book->author }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $loan->borrower ? $loan->borrower->name : 'Deleted Borrower' }}
                                        </div>
                                        @if($loan->borrower && $loan->borrower->email)
                                            <div class="text-sm text-gray-500">{{ $loan->borrower->email }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($loan->return_date)
                                            {{ \Carbon\Carbon::parse($loan->return_date)->format('M j, Y') }}
                                        @else
                                            <span class="text-yellow-600">Not returned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($loan->return_date)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Returned
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('loans.show', $loan) }}" class="text-blue-600 hover:text-blue-900">
                                                View
                                            </a>
                                            @if($loan->return_date === null)
                                                <form method="POST" action="{{ route('loans.return', $loan) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Return this book?')">
                                                        Return
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('loans.edit', $loan) }}" class="text-gray-600 hover:text-gray-900">
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $loans->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-lg rounded-lg p-12 text-center">
                    <div class="text-6xl mb-4">📋</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No loans found</h3>
                    <p class="text-gray-600 mb-6">Start lending your books to track who has them.</p>
                    <a href="{{ route('loans.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Create Your First Loan
                    </a>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
