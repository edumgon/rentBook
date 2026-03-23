<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Details - Book Lending Manager</title>
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

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">📋 Loan Details</h1>
                    <div class="flex space-x-3">
                        @if($loan->return_date === null)
                            <form method="POST" action="{{ route('loans.return', $loan) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                                        onclick="return confirm('Return this book?')">
                                    ✅ Return Book
                                </button>
                            </form>
                        @else
                            <a href="{{ route('loans.edit', $loan) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                ✏️ Edit
                            </a>
                        @endif
                        <a href="{{ route('loans.index') }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Loans
                        </a>
                    </div>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Book Cover -->
                    <div class="md:w-1/3">
                        <div class="h-96 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <div class="text-white text-center p-8">
                                <div class="text-8xl mb-4">📚</div>
                                <div class="text-xl font-medium">{{ $loan->book->title }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loan Information -->
                    <div class="md:w-2/3 p-8">
                        <div class="space-y-4">
                            <!-- Status Badge -->
                            <div>
                                @if($loan->return_date === null)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        ⏳ Active Loan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        ✅ Returned
                                    </span>
                                @endif
                            </div>

                            <!-- Book Information -->
                            <div class="border-t pt-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">📖 Book Information</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium text-gray-700">Title:</span> {{ $loan->book->title }}</p>
                                    <p><span class="font-medium text-gray-700">Author:</span> {{ $loan->book->author }}</p>
                                    @if($loan->book->publisher)
                                        <p><span class="font-medium text-gray-700">Publisher:</span> {{ $loan->book->publisher }}</p>
                                    @endif
                                    @if($loan->book->isbn)
                                        <p><span class="font-medium text-gray-700">ISBN:</span> {{ $loan->book->isbn }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Borrower Information -->
                            <div class="border-t pt-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">👥 Borrower Information</h3>
                                @if($loan->borrower)
                                    <div class="space-y-2">
                                        <p><span class="font-medium text-gray-700">Name:</span> {{ $loan->borrower->name }}</p>
                                        @if($loan->borrower->email)
                                            <p><span class="font-medium text-gray-700">Email:</span> {{ $loan->borrower->email }}</p>
                                        @endif
                                        @if($loan->borrower->phone)
                                            <p><span class="font-medium text-gray-700">Phone:</span> {{ $loan->borrower->phone }}</p>
                                        @endif
                                        @if($loan->borrower->location)
                                            <p><span class="font-medium text-gray-700">Location:</span> {{ $loan->borrower->location }}</p>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-gray-500">Borrower has been deleted from the system</p>
                                @endif
                            </div>

                            <!-- Loan Dates -->
                            <div class="border-t pt-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">📅 Loan Dates</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Loan Date</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $loan->loan_date->format('M j, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Return Date</p>
                                        <p class="text-lg font-semibold {{ $loan->return_date ? 'text-green-600' : 'text-yellow-600' }}">
                                            @if($loan->return_date)
                                                {{ $loan->return_date->format('M j, Y') }}
                                            @else
                                                Not returned yet
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($loan->notes)
                                <div class="border-t pt-4">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">📝 Notes</h3>
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $loan->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔧 Actions</h3>
                <div class="flex space-x-4">
                    @if($loan->return_date === null)
                        <form method="POST" action="{{ route('loans.return', $loan) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                                    onclick="return confirm('Are you sure you want to return this book?')">
                                ✅ Mark as Returned
                            </button>
                        </form>
                    @else
                        <a href="{{ route('loans.edit', $loan) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            ✏️ Edit Notes
                        </a>
                    @endif
                    
                    @if($loan->return_date !== null)
                        <form method="POST" action="{{ route('loans.destroy', $loan) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this loan record? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                🗑️ Delete Loan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>
