<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="/back-end" class="sidebar-logo">
            <img src="<?= base_url('/assets/images/logo.svg') ?>" alt="site logo" class="light-logo">
            <img src="<?= base_url('/assets/images/logo.svg') ?>" alt="site logo" class="dark-logo">
            <img src="<?= base_url('/assets/images/logo-icon.png') ?>" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <?php
            helper('menu');
            $menuTree = buildMenuTree(array_filter(config('Backend')->menus, function ($perm) {
                return $perm['type'] == 'sidebar';
            })); 
            ?>
            <?= view('Backend/Partial/layout-sidebar-item', ['menus' => $menuTree]) ?>
        </ul>
    </div>
</aside>