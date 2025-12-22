<div class="h-[3.5rem] md:h-[4.5rem] navbar-header border-b border-neutral-200 dark:border-neutral-600">
    <div class="flex items-center justify-between">
        <div class="col-auto">
            <div class="flex flex-wrap items-center gap-[16px]">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle d-flex !leading-[0]">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-sm md:text-[30px]"></iconify-icon>
                </button>
                <h6 class="font-semibold mb-0 text-black dark:text-white text-sm md:text-2xl"><?= esc((!empty($title) ? $title : "Dashboard").(!empty($param['action']) ? ' '.ucfirst($param['action']) : "")) ?></h6>
    
            </div>
        </div>
        <div class="col-auto">
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="theme-toggle" class="w-5 h-5 md:w-10 md:h-10 bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-white rounded-full flex justify-center items-center">
                    <span id="theme-toggle-dark-icon" class="hidden">
                        <i class="ri-sun-line"></i>
                    </span>
                    <span id="theme-toggle-light-icon" class="hidden">
                        <i class="ri-moon-line"></i>
                    </span>
                </button>
                <button data-dropdown-toggle="dropdownProfile" class="flex justify-center items-center rounded-full" type="button">
                    <img src="<?= base_url('/assets/images/user-grid/user-grid-img13.png') ?>" alt="image" class="w-5 h-5 md:w-10 md:h-10 object-fit-cover rounded-full">
                </button>
                <div id="dropdownProfile" class="z-10 hidden bg-white dark:bg-neutral-700 rounded-lg shadow-lg dropdown-menu-sm p-3" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(1190.05px, 66.0697px, 0px);" data-popper-placement="bottom">
                    <div class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 mb-4 flex items-center justify-between gap-2">
                        <div>
                            <h6 class="text-lg text-neutral-900 font-semibold mb-0"><?= esc($auth->user()->name == "" ? ucfirst($auth->user()->username) : $auth->user()->name) ?></h6>
                            <span class="text-neutral-500"><?= esc(ucfirst($auth->user()->user_type)) ?></span>
                        </div>
                        <button type="button" class="hover:text-danger-600">
                            <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                        </button>
                    </div>

                    <div class="max-h-[400px] overflow-y-auto scroll-sm pe-2">
                        <ul class="flex flex-col">
                            <li>
                                <a class="dark:text-white text-black px-0 py-2 hover:text-primary-600 flex items-center gap-4" href="/back-end/my-profile">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dark:text-white text-black px-0 py-2 hover:text-danger-600 flex items-center gap-4" href="<?= route_to('logout'); ?>">
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>