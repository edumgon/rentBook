<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Test - Book Lending Manager</title>
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
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">🧪 Tenant Isolation Test</h1>
                
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-blue-900 mb-2">Tenant Information</h2>
                        <div class="space-y-2 text-sm">
                            <p><strong>Tenant ID:</strong> <code class="bg-blue-100 px-2 py-1 rounded">{{ $tenantId }}</code></p>
                            <p><strong>User ID:</strong> {{ $userId }}</p>
                            <p><strong>User Email:</strong> {{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-green-900 mb-2">Data Count</h2>
                        <div class="space-y-2 text-sm">
                            <p><strong>Books:</strong> {{ $books }}</p>
                            <p><strong>Borrowers:</strong> {{ $borrowers }}</p>
                            <p><strong>Loans:</strong> {{ $loans }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Test Actions</h2>
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('tenant.test.create') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Create Sample Data
                            </button>
                        </form>
                        
                        <div class="text-sm text-gray-600">
                            <p>This will create sample data that is automatically scoped to your tenant.</p>
                            <p>Other users will not be able to see this data, proving tenant isolation works.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t mt-6 pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">How Tenant Isolation Works</h2>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p>✅ Each user gets a unique <code>tenant_id</code></p>
                        <p>✅ All data queries are automatically filtered by tenant_id</p>
                        <p>✅ Users can only see their own data</p>
                        <p>✅ Cross-tenant data access is prevented</p>
                        <p>✅ Database indexes ensure performance and security</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
