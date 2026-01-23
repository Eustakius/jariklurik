<div class="card p-5 shadow-lg rounded-2xl bg-white dark:bg-neutral-800 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 relative overflow-hidden group">
    <div class="flex justify-between items-start z-10 relative">
        <div class="flex-1">
            <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-1"><?= $label ?></p>
            <h3 class="text-2xl font-bold text-neutral-800 dark:text-white mb-0"><?= $value ?></h3>
        </div>
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/20 bg-gradient-to-br <?= $colorClass ?>">
            <iconify-icon icon="<?= $icon ?>" class="text-2xl"></iconify-icon>
        </div>
    </div>
    
    <?php if(!empty($link)): ?>
    <div class="mt-4 flex items-center pt-3 border-t border-neutral-100 dark:border-neutral-700/50">
        <a href="<?= $link ?>" class="text-sm font-semibold flex items-center gap-1 transition-colors hover:gap-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 text-neutral-600 dark:text-neutral-400">
            View Details <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
        </a>
    </div>
    <?php endif; ?>

    <!-- Decorative Background Icon -->
    <iconify-icon icon="<?= $icon ?>" class="absolute -bottom-4 -right-4 text-9xl opacity-[0.03] dark:opacity-[0.05] grayscale group-hover:scale-110 transition-transform duration-500"></iconify-icon>
</div>
