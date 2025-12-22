<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<section class="bg-white dark:bg-dark-2 flex-wrap min-h-[100vh] items-center">
    <div class="py-8 px-6 flex flex-col justify-center min-h-[100vh]">
        <div class="lg:max-w-[464px] mx-auto w-full">
            <div class="mb-2">
                <a href="index.html" class="mb-2.5 max-w-[290px]">
                    <img src="<?= base_url('assets/images/logo.svg') ?>" alt="">
                </a>
            </div>
            <form action="<?= url_to('login') ?>" method="post" data-parsley-validate>
				<?= csrf_field() ?>
				
                    <?= view('App\Views\Auth\_message_block') ?>
                <div class="icon-field mb-4 relative">
                    <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                        <iconify-icon icon="mage:user"></iconify-icon>
                    </span>
                    <input name="login" 
						required 
						data-parsley-errors-container="#email-error" 
						data-parsley-trigger="change" 
						class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl" 
						placeholder="Username">
                </div>
                <div class="relative mb-5">
                    <div class="icon-field">
                        <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input name="password" required data-parsley-minlength="6"
                            data-parsley-required-message="Password wajib diisi"
                            data-parsley-minlength-message="Password minimal 6 karakter"
                            data-parsley-errors-container="#email-error" type="password" class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl" id="your-password" placeholder="Password">
                    </div>
                    <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#your-password"></span>
                </div>
                <div class="mt-7">
                    <div class="flex justify-between gap-2">
                        <div class="flex items-center">
                            <input class="form-check-input border border-neutral-300" type="checkbox" name="remember" id="remember">
                            <label class="ps-2" for="remember">Remember me </label>
                        </div>
                        <!-- <a href="javascript:void(0)" class="text-primary-600 font-medium hover:underline">Forgot Password?</a> -->
                    </div>
                </div>
                <div id="email-error"></div>

                <button type="submit" class="btn btn-primary justify-center text-sm btn-sm px-3 py-4 w-full rounded-xl mt-8"> Sign In</button>

                <!-- <div class="mt-8 center-border-horizontal text-center relative before:absolute before:w-full before:h-[1px] before:top-1/2 before:-translate-y-1/2 before:bg-neutral-300 before:start-0">
                    <span class="bg-white dark:bg-dark-2 z-[2] relative px-4">Or sign in</span>
                </div>
                <div class="mt-8 text-center text-sm">
                    <p class="mb-0">Don't have an account? <a href="<?= url_to('register') ?>" class="text-primary-600 font-semibold hover:underline">Sign Up</a></p>
                </div> -->

            </form>
        </div>
    </div>
</section>
<?= $this->endSection() ?>