<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([2,3])]) ?>
    <div class="grid grid-cols-12">
        <div class="col-span-12">
        <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
            
            <!-- Card Header -->
            <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 space-y-4">
                <!-- Title & Header -->
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                         <div class="p-2 bg-primary-50 text-primary-600 rounded-lg dark:bg-primary-900/50 dark:text-primary-400">
                            <iconify-icon icon="mingcute:briefcase-line" class="text-xl"></iconify-icon>
                         </div>
                         <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0">Job Vacancy Lists</h6>
                    </div>
                </div>
                
                <!-- Filter Banner (Full Width) -->
                <div class="w-full">
                    <?= view('Backend/Partial/banner/filter-banner', [
                        'key_base' => 'jobvacancy', 
                        'api_url' => 'back-end/api/job-vacancy',
                        'title_label' => 'Job Vacancy',
                        'mappings' => [
                            'title' => 'position',
                            'subtitle' => 'company_name', 
                            'tertiary' => 'country_name'
                        ],
                        'token' => $token ?? '', 
                        'filter_config' => $tabs[0]['datatable']['filters'] ?? [] 
                    ]) ?>
                </div>

                <!-- Modern Pill Tabs -->
                <ul data-toggle="tab" class="flex flex-wrap p-1 bg-neutral-100 dark:bg-neutral-700/50 rounded-xl" id="card-title-tab" data-tabs-toggle="#card-title-tab-content" role="tablist">
                    <?php if(isset($tabs)): foreach ($tabs as $tab): ?>
                        <li role="presentation" class="me-1 last:me-0">
                            <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-300 ease-in-out hover:bg-white hover:shadow-sm dark:hover:bg-neutral-600 focus:outline-none aria-selected:bg-white aria-selected:text-primary-600 aria-selected:shadow-md dark:aria-selected:bg-neutral-600 dark:aria-selected:text-primary-400" 
                                id="<?= $tab['key'] ?>-tab" 
                                data-tabs-target="#<?= $tab['key'] ?>" 
                                type="button" 
                                role="tab" 
                                aria-controls="<?= $tab['key'] ?>" 
                                aria-selected="<?= (session()->has('key') && session('key') == $tab['key']) || (!session()->has('key') && $tab['key'] === 'active') ? 'true' : 'false'  ?>">
                                <?php if(isset($tab['icon'])): ?>
                                    <iconify-icon icon="<?= $tab['icon'] ?>" class="text-lg"></iconify-icon>
                                <?php endif; ?>
                                <?= $tab['label'] ?>
                            </button>
                        </li>
                    <?php endforeach; endif; ?>
                </ul>
            </div>

            <div class="card-body relative p-6">
                <div id="card-title-tab-content">
                    <?php if(isset($tabs)): foreach ($tabs as $tab): ?>
                        <div class="<?= (session('key') == $tab['key'] || (!session('key') && $tab['key'] === 'active')) ? 'animate-fade-in-up' : 'hidden' ?>" id="<?= $tab['key'] ?>" role="tabpanel" aria-labelledby="<?= $tab['key'] ?>-tab">                                
                            <?= view('Backend/Partial/table/table', ['title' => getTitleFromUri([2, 3]), 'props' => $tab['datatable']]) ?>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>