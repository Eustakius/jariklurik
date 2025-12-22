<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([3])]) ?>
    <?php if (session()->has('forbiden')): ?>
        <div class="alert alert-danger bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border-danger-600 border-start-width-4-px border-l-[3px] dark:border-neutral-600 px-6 py-[13px] mb-0 text-sm rounded flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
                <?= esc(session('forbiden')) ?>
            </div>
            <button class="remove-button text-danger-600 text-2xl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
        </div>
    <?php endif; ?>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
        <div class="lg:col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card px-4 py-5 shadow-2 rounded-lg border-gray-200 dark:border-neutral-600 h-full bg-gradient-to-l from-info-600/10 to-bg-white">
                    <div class="card-body p-0">
                        <div class="flex flex-wrap items-center justify-between gap-1 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="mb-0 w-[44px] h-[44px] bg-info-600 shrink-0 text-white flex justify-center items-center rounded-full h6">
                                    <iconify-icon icon="ic:twotone-list" class="icon"></iconify-icon>
                                </span>
                                <div>
                                    <span class="mb-2 font-medium text-secondary-light text-sm">Job Vacancy</span>
                                    <h6 class="font-semibold"><?= $jobVacancyCount ?></h6>
                                </div>
                            </div>

                            <div id="new-user-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
                        </div>
                        <a href="/back-end/job-vacancy" class="dark:text-white btn text-neutral-600 rounded-full px-0 py-0 flex justify-end items-center">
                            <span class="flex items-center justify-center gap-2">Read More <iconify-icon icon="mage:arrow-right" class="text-lg"></iconify-icon> </span>
                        </a>
                    </div>
                </div>
                <div class="card px-4 py-5 shadow-2 rounded-lg border-gray-200 dark:border-neutral-600 h-full bg-gradient-to-l from-success-600/10 to-bg-white">
                    <div class="card-body p-0">
                        <div class="flex flex-wrap items-center justify-between gap-1 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="mb-0 w-[44px] h-[44px] bg-success-600 shrink-0 text-white flex justify-center items-center rounded-full h6">
                                    <iconify-icon icon="solar:list-check-bold" class="icon"></iconify-icon>
                                </span>
                                <div>
                                    <span class="mb-2 font-medium text-secondary-light text-sm">Job Vacancy Active</span>
                                    <h6 class="font-semibold"><?= $jobVacancyActiveCount ?></h6>
                                </div>
                            </div>

                            <div id="new-user-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
                        </div>
                        <a href="/back-end/job-vacancy" class="dark:text-white btn text-neutral-600 rounded-full px-0 py-0 flex justify-end items-center">
                            <span class="flex items-center justify-center gap-2">Read More <iconify-icon icon="mage:arrow-right" class="text-lg"></iconify-icon> </span>
                        </a>
                    </div>
                </div>

                <div class="card px-4 py-5 shadow-2 rounded-lg border-gray-200 dark:border-neutral-600 h-full bg-gradient-to-l from-danger-600/10 to-bg-white">
                    <div class="card-body p-0">
                        <div class="flex flex-wrap items-center justify-between gap-1 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="mb-0 w-[44px] h-[44px] bg-danger-600 shrink-0 text-white flex justify-center items-center rounded-full h6">
                                    <iconify-icon icon="solar:list-cross-bold" class="icon"></iconify-icon>
                                </span>
                                <div>
                                    <span class="mb-2 font-medium text-secondary-light text-sm">Job Vacancy Expired</span>
                                    <h6 class="font-semibold"><?= $jobVacancyExpiredCount ?></h6>
                                </div>
                            </div>

                            <div id="active-user-chart" class="remove-tooltip-title rounded-tooltip-value"></div>
                        </div>
                        <a href="/back-end/job-vacancy" class="dark:text-white btn text-neutral-600 rounded-full px-0 py-0 flex justify-end items-center">
                            <span class="flex items-center justify-center gap-2">Read More <iconify-icon icon="mage:arrow-right" class="text-lg"></iconify-icon> </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>