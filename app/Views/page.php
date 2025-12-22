<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<?php
foreach ($sections as $section) {
    include('Sections/' . $section['name'] . '.php');
}
?>

<?= $this->endSection() ?>