<?php foreach ($menus as $menu): ?>
    <li class="m-2 <?= $menu['is_group'] ?  esc('sidebar-menu-group-title') : ($menu['url'] ? '' :  esc('dropdown')) ?> ">
        <?php if ($menu['is_group']): ?>
             <span><?= esc($menu['name']) ?></span>
            <?php if (!empty($menu['children'])): ?>
                <?= view('Backend/Partial/layout-sidebar-item', ['menus' => $menu['children']]) ?>
            <?php endif; ?>
        <?php else: ?>
            <?php if ($menu['url']): ?>
                <a href="<?= base_url('back-end/' . $menu['url']) ?>">
                    <?php if ($menu['icon']): ?>
                        <iconify-icon icon="<?= esc($menu['icon']) ?>" class="menu-icon"></iconify-icon>
                    <?php endif; ?>
                    <span><?= esc($menu['name']) ?></span>
                </a>
            <?php else: ?>
                <a href="javascript:void(0)">
                    <?php if ($menu['icon']): ?>
                        <iconify-icon icon="<?= esc($menu['icon']) ?>" class="menu-icon"></iconify-icon>
                    <?php endif; ?>
                     <span><?= esc($menu['name']) ?></span>
                </a>

                <?php if (!empty($menu['children'])): ?>
                    <ul class="sidebar-submenu">
                        <?= view('Backend/Partial/layout-sidebar-item', ['menus' => $menu['children']]) ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

    </li>
<?php endforeach; ?>