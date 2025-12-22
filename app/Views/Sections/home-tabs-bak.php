<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div id="fadeDiv" class="mt-16 mb-[5.563rem] w-auto mx-auto w-fit">
    <img src="<?= base_url('image/jariklurik-hero.png') ?>" class="h-[17.625rem] w-auto" alt="Jariklurik info menakir luar negeri">
</div>

<?php
$tabs = [
    ['id' => 'tab1', 'title' => 'Lowongan Kerja', 'content' => 'Konten dari Tab 1'],
    ['id' => 'tab2', 'title' => 'Daftar Kepelatihan', 'content' => 'Konten dari Tab 2'],
];
?>

<div class="w-full">
    <!-- Tabs Header -->
    <div class="flex justify-center items-center border-b border-gray-200 text-2xl gap-7" id="tabs-nav">
        <?php foreach ($tabs as $index => $tab): ?>
            <a href="<?= $index === 0 ? '/lowongan-kerja':'/daftar-kepelatihan' ?>"
                data-tab="<?= $tab['id'] ?>"
                class="tab-btn px-4 py-2 -mb-px w-[29.563rem] font-bold text-center
                       <?= $index === 0 ? 'active bg-[#EBC470] text-[#4D2800] rounded-t-[10px]' : '' ?>">
                <h1><?= htmlspecialchars($tab['title']) ?></h1>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Tabs Content -->
    <div id="tabs-content">
        <?php foreach ($tabs as $index => $tab): ?>
            <div id="1" class="bg-[#EBC470] px-[16.813rem] tab-content pt-12 <?= $index !== 0 ? 'hidden' : '' ?>">
                <div class="flex flex-wrap items-center justify-between">
                    <div class="flex flex-row gap-4">
                        <img src="<?= base_url('image/jariklurik-icon-lowongan.png') ?>" class="h-[3.563rem] w-[3.563rem]" alt="Jariklurik info menakir luar negeri">
                        <p class="font-bold text-[2rem] text-[#74430D]">Daftar Lowongan Kerja Terbaru</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.tab-btn').on('click', function() {
            var tabId = $(this).data('tab');
            let classes = $('.tab-btn.active').attr('class');
            $('.tab-btn').addClass('tab-btn px-4 py-2 -mb-px w-[29.563rem] font-bold');
            $(this).addClass(classes);
            $('.tab-content').addClass('hidden');
            $('#' + tabId).removeClass('hidden').hide().fadeIn(200);
        });
    });
</script>


<script>
    const fadeDiv = document.getElementById('fadeDiv');
    window.addEventListener('scroll', () => {
        const maxScroll = 200; // scroll sejauh ini akan 100% hilang
        const opacity = Math.max(0, 1 - window.scrollY / maxScroll);
        fadeDiv.style.opacity = opacity;
    });
</script>

<?= $this->endSection() ?>