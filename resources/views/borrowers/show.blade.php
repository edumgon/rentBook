<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $borrower->name }} - Book Lending Manager</title>
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

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">👥 {{ $borrower->name }}</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('borrowers.edit', $borrower) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            ✏️ Edit
                        </a>
                        <a href="{{ route('borrowers.index') }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Borrowers
                        </a>
                    </div>
                </div>
            </div>

            <!-- Borrower Details -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Borrower Avatar -->
                    <div class="md:w-1/3">
                        <div class="h-96 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                            <div class="text-white text-center p-8">
                                <div class="text-8xl mb-4">{{ substr($borrower->name, 0, 1) }}</div>
                                <div class="text-xl font-medium">{{ $borrower->name }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Borrower Information -->
                    <div class="md:w-2/3 p-8">
                        <div class="space-y-4">
                            <!-- Status Badge -->
                            <div>
                                @if($borrower->activeLoans()->count() > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        📚 {{ $borrower->activeLoans()->count() }} Active Loan{{ $borrower->activeLoans()->count() != 1 ? 's' : '' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        ✅ Available
                                    </span>
                                @endif
                            </div>

                            <!-- Contact Information -->
                            <div class="border-t pt-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">📞 Contact Information</h3>
                                
                                @if($borrower->email)
                                    <div class="mb-2">
                                        <p class="text-sm font-medium text-gray-700">Email</p>
                                        <p class="text-gray-900">{{ $borrower->email }}</p>
                                    </div>
                                @endif

                                @if($borrower->phone)
                                    <div class="mb-2">
                                        <p class="text-sm font-medium text-gray-700">Phone</p>
                                        <p class="text-gray-900">{{ $borrower->phone }}</p>
                                    </div>
                                @endif

                                @if($borrower->location)
                                    <div class="mb-2">
                                        <p class="text-sm font-medium text-gray-700">Location</p>
                                        <p class="text-gray-900">{{ $borrower->location }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Notes -->
                            @if($borrower->notes)
                                <div class="border-t pt-4">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">Notes</h3>
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $borrower->notes }}</p>
                                </div>
                            @endif

                            <!-- Statistics -->
                            <div class="border-t pt-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">📊 Statistics</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Total Loans</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $borrower->loans()->count() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Active Loans</p>
                                        <p class="text-lg font-semibold text-yellow-600">{{ $borrower->activeLoans()->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Loans -->
            @if($activeLoans->count() > 0)
                <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📚 Currently Borrowed Books</h3>
                    
                    <div class="space-y-3">
                        @foreach($activeLoans as $loan)
                            <div class="border-l-4 border-yellow-400 pl-4 py-3 bg-yellow-50 rounded-r-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $loan->book->title }}</p>
                                        <p class="text-sm text-gray-600">by {{ $loan->book->author }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Borrowed on: {{ $loan->loan_date->format('M j, Y') }}
                                        </p>
                                        @if($loan->notes)
                                            <p class="text-sm text-gray-500 mt-1">Notes: {{ $loan->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <form method="POST" action="{{ route('loans.return', $loan) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                                ✅ Return
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Loan History -->
            <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Complete Loan History</h3>
                
                @if($loanHistory->count() > 0)
                    <div class="space-y-3">
                        @foreach($loanHistory as $loan)
                            <div class="border-l-4 {{ $loan->return_date ? 'border-gray-300' : 'border-yellow-400' }} pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">
                                            {{ $loan->book->title }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $loan->loan_date->format('M j, Y') }} 
                                            @if($loan->return_date)
                                                → {{ $loan->return_date->format('M j, Y') }}
                                            @else
                                                (Currently borrowed)
                                            @endif
                                        </p>
                                        @if($loan->notes)
                                            <p class="text-sm text-gray-500 mt-1">Notes: {{ $loan->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @if($loan->return_date)
                                            <span class="text-green-600">✓ Returned</span>
                                        @else
                                            <span class="text-yellow-600">⏳ Active</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-4xl mb-2">📚</div>
                        <p class="text-gray-600">This borrower hasn't borrowed any books yet.</p>
                        <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            Browse Books →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
