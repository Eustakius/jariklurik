<?php if (!empty($title)): ?>
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <div class="mb-0 dark:text-white">
            <?php if (isset($param['action'])): ?>
                <a href="<?= '/' . esc(implode('/', array_slice(explode('/', $path), 0, (strtolower($param['action']) == "edit") ? -2 : -1))) ?>" class="btn text-neutral-600 rounded-full px-0 py-0 flex justify-center items-center">
                    <span class="flex items-center justify-center gap-2"><iconify-icon icon="mage:arrow-left" class="text-lg"></iconify-icon> Back</span>
            </a>
            <?php endif; ?>
        </div>
        <ul class="flex items-center gap-[6px]">
            <li class="font-medium">
                <a href="/back-end" class="flex items-center gap-2 text-neutral-600 hover:text-primary-600 dark:text-white">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li class="text-neutral-600 dark:text-white">-</li>

            <?php
            if (!empty($path)) {  ?>
                <li class="font-medium">
                    <a href="<?= '/' . esc(implode('/', array_slice(explode('/', $path), 0, (strtolower($param['action']) == "edit") ? -2 : -1))) ?>" class="flex items-center gap-2 text-neutral-600 hover:text-primary-600 dark:text-white"><?= esc($title) ?></a>
                </li>
                <?php
                $pages = is_array($param['action']) ? $param['action'] : [$param['action']]; // pastikan array
                foreach ($pages as $item): ?>
                    <li class="text-neutral-600 dark:text-white">-</li>
                    <li class="font-medium text-neutral-600 dark:text-white"><?= esc(ucfirst($item)) ?></li>
                <?php endforeach;
            } else { ?>

                <li class="font-medium text-neutral-600 dark:text-white"><?= esc($title) ?></li>
            <?php }
            ?>
        </ul>
    </div>
<?php endif; ?>