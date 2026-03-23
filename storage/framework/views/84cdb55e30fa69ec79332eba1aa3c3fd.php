<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Lending Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📚 Book Lending Manager</h1>
                <p class="text-gray-600">Track your personal book lending with friends</p>
            </div>
            
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Get Started</h2>
                <p class="text-gray-600 mb-6">Sign in to manage your book collection and track lending.</p>
                
                <?php if(session('error')): ?>
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
                
                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                
                <div class="space-y-3">
                    <?php if(app()->environment('local')): ?>
                        <a href="/test-login" 
                           class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            🧪 Test Login (Development Only)
                        </a>
                        <div class="text-center text-sm text-gray-500 my-2">Or use social login:</div>
                    <?php endif; ?>
                    <a href="<?php echo e(route('auth.redirect', 'google')); ?>" 
                       class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Continue with Google
                    </a>
                    
                    <a href="<?php echo e(route('auth.redirect', 'facebook')); ?>" 
                       class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Continue with Facebook
                    </a>
                    
                    <a href="<?php echo e(route('auth.redirect', 'microsoft')); ?>" 
                       class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#F25022" d="M11.4 11.4H2.6V2.6h8.8v8.8z"/>
                            <path fill="#7FBA00" d="M21.4 11.4h-8.8V2.6h8.8v8.8z"/>
                            <path fill="#00A4EF" d="M11.4 21.4H2.6v-8.8h8.8v8.8z"/>
                            <path fill="#FFB900" d="M21.4 21.4h-8.8v-8.8h8.8v8.8z"/>
                        </svg>
                        Continue with Microsoft
                    </a>
                </div>
            </div>
            
            <div class="text-center text-sm text-gray-500">
                <p>Your personal book lending management system</p>
                <p class="mt-1">Track who has your books and manage your collection</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/resources/views/welcome.blade.php ENDPATH**/ ?>