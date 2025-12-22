<?php
$tabs = [
    ['id' => 'tab1', 'title' => 'Lowongan Kerja', 'url' => 'lowongan-kerja'],
    ['id' => 'tab2', 'title' => 'Daftar Kepelatihan', 'url' => 'daftar-kepelatihan'],
];
?>

<div class="w-full">
    <!-- Tabs Header -->
    <div class="flex justify-center items-center border-b border-gray-200 text-lg md:text-2xl gap-4 md:gap-7" id="tabs-nav">
        <?php foreach ($tabs as $index => $tab): ?>
            <a href="/<?= $tab['url'] ?>"
                class="tab-btn -mb-px w-[12.143rem] md:w-[29.563rem] font-bold text-center
                       <?= strpos($page, $tab['url']) !== false ? 'active bg-[#EBC470] text-[#4D2800] rounded-t-[10px]' : '' ?>">
                <?php if (strpos($page, $tab['url']) !== false): ?>
                    <h1 class="px-4 pt-5 pb-4 "><?= htmlspecialchars($tab['title']) ?></h1>
                <?php else: ?>
                    <h2 class="px-4 pt-5 pb-4 "><?= htmlspecialchars($tab['title']) ?></h2>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>