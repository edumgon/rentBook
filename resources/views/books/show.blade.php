<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - Book Lending Manager</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">📖 {{ $book->title }}</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('books.edit', $book) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            ✏️ Edit
                        </a>
                        <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Books
                        </a>
                    </div>
                </div>
            </div>

            <!-- Book Details -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Book Cover -->
                    <div class="md:w-1/3">
                        <div class="h-96 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <div class="text-white text-center p-8">
                                <div class="text-6xl mb-4">📚</div>
                                <div class="text-xl font-medium">{{ $book->title }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book Information -->
                    <div class="md:w-2/3 p-8">
                        <div class="space-y-4">
                            <!-- Title and Author -->
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $book->title }}</h2>
                                <p class="text-lg text-gray-600">by {{ $book->author }}</p>
                            </div>

                            <!-- Status Badge -->
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $book->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($book->status) }}
                                </span>
                            </div>

                            <!-- Publisher -->
                            @if($book->publisher)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700">Publisher</h3>
                                    <p class="text-gray-900">{{ $book->publisher }}</p>
                                </div>
                            @endif

                            <!-- ISBN -->
                            @if($book->isbn)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700">ISBN</h3>
                                    <p class="text-gray-900">{{ $book->isbn }}</p>
                                </div>
                            @endif

                            <!-- Purchase Information -->
                            <div class="border-t pt-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Purchase Information</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    @if($book->purchase_date)
                                        <div>
                                            <p class="text-sm text-gray-600">Purchase Date</p>
                                            <p class="text-gray-900">{{ $book->purchase_date->format('M j, Y') }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($book->purchase_price)
                                        <div>
                                            <p class="text-sm text-gray-600">Purchase Price</p>
                                            <p class="text-gray-900">${{ number_format($book->purchase_price, 2) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($book->notes)
                                <div class="border-t pt-4">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">Notes</h3>
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $book->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Loan Information -->
            @if($currentLoan)
                <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Current Loan</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    Lent to: <span class="text-yellow-800">{{ $currentLoan->borrower->name }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Loan Date: {{ $currentLoan->loan_date->format('M j, Y') }}
                                </p>
                                @if($currentLoan->notes)
                                    <p class="text-sm text-gray-600 mt-1">Notes: {{ $currentLoan->notes }}</p>
                                @endif
                            </div>
                            <div>
                                <form method="POST" action="{{ route('loans.return', $currentLoan) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        ✅ Mark as Returned
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Loan History -->
            <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📚 Loan History</h3>
                
                @if($book->loans->count() > 0)
                    <div class="space-y-3">
                        @foreach($book->loans->orderBy('loan_date', 'desc')->get() as $loan)
                            <div class="border-l-4 {{ $loan->return_date ? 'border-gray-300' : 'border-yellow-400' }} pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $loan->borrower->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $loan->loan_date->format('M j, Y') }} 
                                            @if($loan->return_date)
                                                → {{ $loan->return_date->format('M j, Y') }}
                                            @else
                                                (Currently lent)
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
                        <p class="text-gray-600">This book hasn't been lent yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
