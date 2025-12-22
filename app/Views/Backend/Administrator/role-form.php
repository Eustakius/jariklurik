<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([2, 3])]) ?>
    <form action="<?= site_url($form['route']) ?>" method="post" data-parsley-validate>
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="<?= $form['method'] ?>">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <div class="card border-0">
                    <div class="card-header">
                        <h6 class="card-title mb-0 text-lg">Form</h6>
                    </div>
                    <div class="card-body">
                        <?php if (session()->has('errors-backend')): ?>
                            <div class="alert alert-danger bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border-danger-100 px-6 py-[11px] mb-0 font-semibold text-lg rounded-lg" role="alert">
                                <div class="flex items-start justify-between text-lg">
                                    <div class="flex items-start gap-2">
                                        <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl mt-1.5 shrink-0"></iconify-icon>
                                        <div>
                                            <ul class="font-medium dark:text-danger-400 text-danger-600 text-sm mt-2">
                                                <?php foreach (session('errors-backend') as $field => $error): ?>
                                                    <li><?= esc($error) ?></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="remove-button text-danger-600 text-2xl line-height-1 cursor-pointer"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="md:col-span-6 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' =>  [
                                    'field' => 'name',
                                    'label' => 'Name',
                                    'required' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' =>  [
                                    'field' => 'description',
                                    'label' => 'Description',
                                ]]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <div class="card border-0">
                    <div class="card-header">
                        <h5 class="text-lg font-semibold mb-0">Permission</h5>
                    </div>
                    <div class="card-body">
                        <?= view('Backend/Partial/form/checkbox-list-group', ['attribute' =>  [
                                    'field' => 'permissions',
                                    'source' => $datarole,
                                    'label' => 'Nama',
                                    'required' => true,
                                ]]) ?>
                    </div>
                </div>
            </div>
            <?php if(strtolower($param['action']) != "detail"): ?>
            <div class="col-span-12">
                <div class="p-4 bg-warning-50 dark:bg-warning-600/20 rounded-lg border-l-[3px] border-[#ebc470]">
                    <div class="flex items-center justify-between">
                        <div class="col-auto">
                        </div>
                        <div class="col-auto">
                            <button class="btn bg-success-100 text-success-600 hover:bg-success-700 hover:text-white rounded-lg px-3.5 py-2 text-sm">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>