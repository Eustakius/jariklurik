<?= $this->extend($config->viewLayout ?? 'Auth/layout') ?>

<?= $this->section('main') ?>
<div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Two-Factor Authentication</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Please enter the otp code from your authentication app.</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif ?>

        <form action="<?= base_url('back-end/2fa/verify') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-6">
                <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">OTP Code</label>
                <input type="text" id="code" name="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="000000" required autofocus autocomplete="off" pattern="[0-9]*" inputmode="numeric">
            </div>

            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Verify</button>
            
            <div class="mt-4 text-center">
                 <a href="<?= base_url('back-end/logout') ?>" class="text-sm text-blue-600 hover:underline dark:text-blue-500">Back to Login</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
