<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Borrower - Book Lending Manager</title>
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

    <main class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">➕ Add New Borrower</h1>
                    <a href="{{ route('borrowers.index') }}" class="text-blue-600 hover:text-blue-800">
                        ← Back to Borrowers
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

            <!-- Add Borrower Form -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form method="POST" action="{{ route('borrowers.store') }}">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                            <input type="text" id="name" name="name" required
                                   value="{{ old('name') }}"
                                   placeholder="Enter borrower's full name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Contact Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📞 Contact Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" id="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="borrower@example.com"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="tel" id="phone" name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="(11)98765-4321"
                                           oninput="formatPhone(this)"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">Format: (DD)#####-####</p>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">📍 Location</label>
                            <input type="text" id="location" name="location"
                                   value="{{ old('location') }}"
                                   placeholder="City, State or Address"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">📝 Notes</label>
                            <textarea id="notes" name="notes" rows="4"
                                      placeholder="Additional notes about this borrower..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Optional: Add any notes about this borrower</p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('borrowers.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Add Borrower
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
                <h3 class="text-lg font-medium text-blue-900 mb-2">💡 Tips for Adding Borrowers</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Include at least a name - this is required</li>
                    <li>• Email must be a valid format (e.g., user@domain.com)</li>
                    <li>• Phone must be in Brazilian format: (DD)#####-####</li>
                    <li>• Location helps you remember where the borrower lives</li>
                    <li>• Notes can include relationship details or preferences</li>
                    <li>• You can always edit borrower details later</li>
                </ul>
            </div>
        </div>
    </main>

    <script>
        function formatPhone(input) {
            // Remove all non-digit characters
            let value = input.value.replace(/\D/g, '');
            
            // Apply Brazilian phone format (DD)#####-####
            if (value.length <= 2) {
                if (value.length > 0) {
                    input.value = '(' + value;
                } else {
                    input.value = value;
                }
            } else if (value.length <= 7) {
                input.value = '(' + value.slice(0, 2) + ')' + value.slice(2);
            } else {
                input.value = '(' + value.slice(0, 2) + ')' + value.slice(2, 7) + '-' + value.slice(7, 11);
            }
        }
    </script>
</body>
</html>
