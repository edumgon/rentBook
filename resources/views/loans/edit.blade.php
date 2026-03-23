<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Loan - Book Lending Manager</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">✏️ Edit Loan</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('loans.show', $loan) }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Loan
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
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-900 mb-2">ℹ️ Information</h3>
                    <p class="text-sm text-blue-800">
                        This loan has been returned. You can only edit the notes for returned loans.
                    </p>
                </div>

                <form method="POST" action="{{ route('loans.update', $loan) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Read-only Loan Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Loan Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Book</label>
                                    <div class="p-3 bg-gray-50 rounded-md">
                                        <p class="font-medium text-gray-900">{{ $loan->book->title }}</p>
                                        <p class="text-sm text-gray-600">{{ $loan->book->author }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Borrower</label>
                                    <div class="p-3 bg-gray-50 rounded-md">
                                        <p class="font-medium text-gray-900">
                                            {{ $loan->borrower ? $loan->borrower->name : 'Deleted Borrower' }}
                                        </p>
                                        @if($loan->borrower && $loan->borrower->email)
                                            <p class="text-sm text-gray-600">{{ $loan->borrower->email }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Date</label>
                                    <div class="p-3 bg-gray-50 rounded-md">
                                        <p class="font-medium text-gray-900">{{ $loan->loan_date->format('M j, Y') }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Return Date</label>
                                    <div class="p-3 bg-gray-50 rounded-md">
                                        <p class="font-medium text-green-600">{{ $loan->return_date->format('M j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editable Notes -->
                        <div class="border-t pt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">📝 Notes</label>
                            <textarea id="notes" name="notes" rows="4"
                                      placeholder="Add or edit notes about this loan..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $loan->notes) }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Add any additional information about this loan</p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('loans.show', $loan) }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Update Loan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Section -->
            <div class="bg-white shadow-lg rounded-lg p-6 mt-6 border-2 border-red-200">
                <h3 class="text-lg font-semibold text-red-900 mb-2">⚠️ Danger Zone</h3>
                <p class="text-sm text-red-600 mb-4">
                    Once you delete a loan record, there is no going back. Please be certain.
                </p>
                
                <form method="POST" action="{{ route('loans.destroy', $loan) }}" onsubmit="return confirm('Are you sure you want to delete this loan record? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        🗑️ Delete Loan Record
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
