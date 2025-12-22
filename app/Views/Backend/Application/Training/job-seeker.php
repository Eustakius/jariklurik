<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([2, 3])]) ?>
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <div class="card border-0">
                <div class="card-header pb-0 px-6 bg-white dark:bg-neutral-700 border-b border-neutral-200 dark:border-neutral-600 flex items-center flex-wrap justify-between">
                    <h6 class="text-lg mb-0">List</h6>
                    <ul data-toggle="tab" class="flex flex-wrap -mb-px text-sm font-medium text-center" id="card-title-tab" data-tabs-toggle="#card-title-tab-content" role="tablist">
                        <?php foreach ($tabs as $tab): ?>
                            <li role="presentation">
                                <button class="inline-block px-4 py-2.5 pb-4 font-semibold border-b-2 rounded-t-lg" id="<?= $tab['key'] ?>-tab" data-tabs-target="#<?= $tab['key'] ?>" type="button" role="tab" aria-controls="<?= $tab['key'] ?>" aria-selected="<?= session()->has('key') && session('key') == $tab['key'] ? 'true' : 'false'  ?>"><?= $tab['label'] ?></button>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
                <div class="card-body relative ">
                    <div id="card-title-tab-content" class="hidden">
                        <?php foreach ($tabs as $tab): ?>
                            <div id="<?= $tab['key'] ?>" role="tabpanel" aria-labelledby="<?= $tab['key'] ?>-tab">                                
                                <?= view('Backend/Partial/table/table', ['title' => getTitleFromUri([2, 3]), 'props' => $tab['datatable']]) ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>