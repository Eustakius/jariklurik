<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <form action="<?= site_url($form['route']) ?>" method="post" data-parsley-validate enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="<?= $form['method'] ?>">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <div class="card border-0">
                    <div class="card-header">
                        <h6 class="card-title mb-0 text-lg">My Profile</h6>
                    </div>
                    <div class="card-body">
                        <?php if (session()->has('errors-backend')): ?>
                            <div class="alert alert-danger bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border-danger-100 px-6 py-[11px] mb-0 font-semibold text-lg rounded-lg" role="alert">
                                <div class="flex items-start justify-between text-lg">
                                    <div class="flex items-start gap-2">
                                        <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl mt-1.5 shrink-0"></iconify-icon>
                                        <div>
                                            <ul class="font-medium text-danger-600 text-sm mt-2">
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
                        <?php if (session()->has('message-backend')): ?>
                            <div class="mb-4 alert alert-success bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 border-success-600 border-start-width-4-px border-l-[3px] dark:border-neutral-600 px-6 py-[13px] mb-0 text-sm rounded flex items-center justify-between" role="alert">
                                <div class="flex items-center gap-2">
                                    <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
                                    <?= esc(session('message-backend')) ?>
                                </div>
                                <button class="remove-button text-success-600 text-2xl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
                            </div>
                        <?php endif; ?>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/file-upload', ['attribute' => [
                                    'field' => 'photo',
                                    'label' => 'Photo',
                                    'info' => 'Max 1 MB and type file only .jpg,.jpeg,.png,.bitmap',
                                    'accept' => ".jpg,.jpeg,.png,.bitmap",
                                    'required' => (strtolower($param['action']) == "create" ? true : false),
                                ]]) ?>
                            </div>
                            <div class="md:col-span-8 col-span-12">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/text-box',['attribute' =>  [
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'required' => true,
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/text-box',['attribute' =>  [
                                            'field' => 'username',
                                            'label' => 'User Name',
                                            'required' => true,
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/text-box',['attribute' =>  [
                                            'type' => 'email',
                                            'field' => 'email',
                                            'label' => 'Email',
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/text-box',['attribute' => [
                                            'type' => 'password',
                                            'field' => 'password_hash',
                                            'label' => 'Password',
                                        ]]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (strtolower($param['action']) != "detail"): ?>
                <div class="col-span-12">
                    <div class="p-4 bg-warning-50 dark:bg-warning-600/20 rounded-lg border-l-[3px] border-[#ebc470]">
                        <div class="flex items-center justify-between">
                            <div class="col-auto text-xs text-xs flex flex-col md:flex-row gap-2 md:gap-16">
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