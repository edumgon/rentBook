<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Book Lending Manager</title>
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
                    <span class="text-sm text-gray-700"><?php echo e($user->name); ?></span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 flex items-center justify-center">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to Your Dashboard!</h2>
                    <p class="text-gray-600 mb-8">This is where you'll manage your book collection and track lending.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="<?php echo e(route('books.index')); ?>" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">📖 My Books</h3>
                            <p class="text-gray-600">Manage your personal book collection</p>
                            <div class="mt-2 text-sm text-blue-600 hover:text-blue-800">View Books →</div>
                        </a>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">👥 Borrowers</h3>
                            <p class="text-gray-600">Keep track of who has your books</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">📋 Loans</h3>
                            <p class="text-gray-600">View lending history and current loans</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php /**PATH /var/www/resources/views/dashboard.blade.php ENDPATH**/ ?>