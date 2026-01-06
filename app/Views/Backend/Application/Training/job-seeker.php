<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([2, 3])]) ?>
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <div class="card border-0">
                <div class="card-header pb-0 px-6 py-4 bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-600 space-y-4">
                    <!-- Title & Header -->
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                             <!-- Optional Icon if desired, or keep simple -->
                            <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0">List</h6>
                        </div>
                    </div>

                    <!-- Universal Filter Banner (Full Width) -->
                    <div class="w-full">
                        <?= view('Backend/Partial/banner/filter-banner', [
                            'key_base' => 'training',
                            'api_url' => 'back-end/api/training-type',
                            'title_label' => 'Training Program',
                            'mappings' => [
                                'title' => 'name',
                                'tertiary' => 'quota_info'
                            ],
                            'token' => $token,
                            'filter_config' => $tabs[0]['datatable']['filters'] ?? [] 
                        ]) ?>
                    </div>

                    <!-- Tabs -->
                    <ul data-toggle="tab" class="flex flex-wrap p-1 bg-neutral-100 dark:bg-neutral-700/50 rounded-xl" id="card-title-tab" data-tabs-toggle="#card-title-tab-content" role="tablist">
                        <?php foreach ($tabs as $tab): ?>
                            <li role="presentation" class="me-1 last:me-0">
                                <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-300 ease-in-out hover:bg-white hover:shadow-sm dark:hover:bg-neutral-600 focus:outline-none aria-selected:bg-white aria-selected:text-primary-600 aria-selected:shadow-md dark:aria-selected:bg-neutral-600 dark:aria-selected:text-primary-400" id="<?= $tab['key'] ?>-tab" data-tabs-target="#<?= $tab['key'] ?>" type="button" role="tab" aria-controls="<?= $tab['key'] ?>" aria-selected="<?= session()->has('key') && session('key') == $tab['key'] ? 'true' : 'false'  ?>"><?= $tab['label'] ?></button>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
                <div class="card-body relative p-6">
                    <div id="card-title-tab-content">
                        <?php foreach ($tabs as $tab): ?>
                            <div class="<?= (session('key') == $tab['key'] || (!session('key') && $tab['key'] === 'new')) ? 'animate-fade-in-up' : 'hidden' ?>" id="<?= $tab['key'] ?>" role="tabpanel" aria-labelledby="<?= $tab['key'] ?>-tab">
                                <?= view('Backend/Partial/table/table', [
                                    'title' => getTitleFromUri([2, 3]), 
                                    'props' => $tab['datatable'],
                                    'mas_actions' => match ($tab['key']) {
                                        'new' => [
                                            'process' => [
                                                'label' => 'Mass Process',
                                                'type' => 'primary',
                                                'url' => '/back-end/training/job-seekers/mass-process' 
                                            ]
                                        ],
                                        'approved' => [
                                            'revert' => [
                                                'label' => 'Mass Revert',
                                                'type' => 'danger',
                                                'url' => '/back-end/training/job-seekers/mass-revert' 
                                            ]
                                        ],
                                        'rejected' => [
                                            'process' => [
                                                'label' => 'Mass Process',
                                                'type' => 'primary',
                                                'url' => '/back-end/training/job-seekers/mass-process' 
                                            ]
                                        ],
                                        default => []
                                    }
                                ]) ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>