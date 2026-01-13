<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toast Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Toast Notification Test</h1>
        
        <div class="space-y-4">
            <button onclick="ToastManager.success('Success! Operation completed.')" 
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Test Success Toast
            </button>
            
            <button onclick="ToastManager.error('Error! Something went wrong.')" 
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Test Error Toast
            </button>
            
            <button onclick="ToastManager.warning('Warning! Please check this.')" 
                    class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                Test Warning Toast
            </button>
            
            <button onclick="ToastManager.info('Info: Here is some information.')" 
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Test Info Toast
            </button>
        </div>
    </div>

    <?php
    // Simulate session for testing
    function session() {
        return new class {
            public function has($key) {
                return false;
            }
        };
    }
    ?>
    
    <?= file_get_contents(__DIR__ . '/app/Views/Backend/Partial/toast-notification.php') ?>
</body>
</html>
