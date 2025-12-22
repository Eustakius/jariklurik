<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<!-- <script>
    var table = {};
</script> -->
<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([2,3])]) ?>
    <div class="grid grid-cols-12">
        <div class="col-span-12">
        <div class="card border-0">
            <div class="card-header">
            <h6 class="card-title mb-0 text-lg">List</h6>
            </div>
            <div class="card-body relative ">
                <?= view('Backend/Partial/table/table', ['title' => getTitleFromUri([2,3]), 'props' => $datatable]) ?>
            </div>
        </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>