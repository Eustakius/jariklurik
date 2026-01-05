<?php
$attribute['selected'] = $attribute['selected'] ?? [];
$attribute['disabled']    = (isset($attribute['disabled']) && $attribute['disabled']) || (strtolower($param['action']) == "detail") ? 'disabled' : '';

// Module icons mapping
$moduleIcons = [
    'Dashboard' => 'solar:home-2-bold-duotone',
    'Company' => 'solar:buildings-2-bold-duotone',
    'Job Vacancy' => 'solar:case-round-bold-duotone',
    'Training' => 'solar:diploma-bold-duotone',
    'Training Type' => 'solar:book-bold-duotone',
    'Job Seekers' => 'solar:users-group-rounded-bold-duotone',
    'Purna PMI' => 'solar:user-check-rounded-bold-duotone',
    'Applicant' => 'solar:user-id-bold-duotone',
    'Role' => 'solar:shield-user-bold-duotone',
    'Setting' => 'solar:settings-bold-duotone',
    'User' => 'solar:user-bold-duotone',
    'My Profile' => 'solar:user-circle-bold-duotone',
];
?>

<style>
    @keyframes pulse-slow {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.85;
        }
    }
    
    @keyframes shimmer {
        0% {
            transform: translateX(-200%);
        }
        100% {
            transform: translateX(200%);
        }
    }
    
    @keyframes bounce-in {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0);
            opacity: 0.6;
        }
        100% {
            transform: scale(2.5);
            opacity: 0;
        }
    }
    
    .animate-pulse-slow {
        animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .animate-shimmer {
        animation: shimmer 3s ease-in-out infinite;
    }
    
    .animate-bounce-in {
        animation: bounce-in 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .animate-ripple {
        animation: ripple 0.6s ease-out;
    }
    
    /* Checked state styles */
    .permission-badge.is-checked {
        background: linear-gradient(135deg, rgb(59 130 246) 0%, rgb(37 99 235) 100%) !important;
        color: white !important;
        border-color: rgb(59 130 246) !important;
        box-shadow: 0 2px 8px rgba(59,130,246,0.25), 0 1px 3px rgba(0,0,0,0.1) !important;
    }
    
    .permission-badge.is-checked .unchecked-state {
        opacity: 0;
        transform: scale(0);
    }
    
    .permission-badge.is-checked .checked-state {
        opacity: 1;
        transform: scale(1);
        animation: bounce-in 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .permission-badge.is-checked .success-badge {
        opacity: 1;
        transform: scale(1);
    }
    
    .permission-badge.is-checked .shimmer-effect {
        opacity: 0;
    }
    
    .permission-badge.is-checked .radial-glow {
        opacity: 0;
    }
    
    /* Dark mode checked state */
    @media (prefers-color-scheme: dark) {
        .permission-badge.is-checked {
            background: linear-gradient(135deg, rgb(37 99 235) 0%, rgb(29 78 216) 100%) !important;
            border-color: rgb(59 130 246) !important;
            box-shadow: 0 2px 10px rgba(96,165,250,0.3), 0 1px 3px rgba(0,0,0,0.2) !important;
        }
    }
</style>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach ($attribute['source'] as $attribute['dataField']): ?>
        <?php
        // Tentukan apakah semua child checked
        $attribute['allChecked'] = true;
        $totalItems = 0;
        $checkedItems = 0;
        
        if (empty($attribute['dataField']['items'])) {
            $attribute['allChecked'] = false;
        } else {
            $totalItems = count($attribute['dataField']['items']);
            foreach ($attribute['dataField']['items'] as $item) {
                if (!empty($item['checked'])) {
                    $checkedItems++;
                } else {
                    $attribute['allChecked'] = false;
                }
            }
        }
        
        // Get module icon
        $moduleName = $attribute['dataField']['name'];
        $moduleIcon = $moduleIcons[$moduleName] ?? 'solar:widget-bold-duotone';
        
        // Determine card border color based on selection
        $borderClass = '';
        if ($checkedItems === $totalItems && $totalItems > 0) {
            $borderClass = 'border-success-300 dark:border-success-700 bg-success-50/30 dark:bg-success-900/10';
        } elseif ($checkedItems > 0) {
            $borderClass = 'border-warning-300 dark:border-warning-700 bg-warning-50/30 dark:bg-warning-900/10';
        } else {
            $borderClass = 'border-neutral-200 dark:border-neutral-700';
        }
        ?>
        <div class="card border <?= $borderClass ?> bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-md" data-module="<?= esc($moduleName) ?>">
            <!-- HEADER: Permission & Check All -->
            <div class="bg-gradient-to-br from-neutral-50 via-neutral-100 to-neutral-50 dark:from-neutral-800 dark:via-neutral-700 dark:to-neutral-800 px-4 py-4 border-b-2 border-neutral-200 dark:border-neutral-700 shrink-0">
                <!-- Top Row: Icon, Title, Toggle -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-700 flex items-center justify-center shadow-lg shrink-0">
                            <iconify-icon icon="<?= $moduleIcon ?>" class="text-xl text-white"></iconify-icon>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h6 class="font-bold text-neutral-800 dark:text-white text-sm block truncate mb-0.5">
                                <?= htmlspecialchars($attribute['dataField']['name']) ?>
                            </h6>
                            <div class="flex items-center gap-2">
                                <span class="module-count text-xs text-neutral-600 dark:text-neutral-400 font-medium">
                                    <?= $checkedItems ?> / <?= $totalItems ?> selected
                                </span>
                                <span class="module-percentage text-xs font-bold <?= $checkedItems === $totalItems && $totalItems > 0 ? 'text-success-600 dark:text-success-400' : ($checkedItems > 0 ? 'text-warning-600 dark:text-warning-400' : 'text-neutral-500 dark:text-neutral-500') ?>">
                                    (<?= $totalItems > 0 ? round(($checkedItems / $totalItems) * 100) : 0 ?>%)
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Toggle Switch -->
                    <label class="inline-flex items-center cursor-pointer gap-2 shrink-0" for="select_all_<?= $attribute['dataField']['id'] ?>">
                        <span class="text-xs font-bold text-neutral-600 dark:text-neutral-300">All</span>
                        <div class="relative">
                            <input type="checkbox"
                                class="dataField-select-all sr-only peer"
                                id="select_all_<?= $attribute['dataField']['id'] ?>"
                                data-field-id="<?= $attribute['dataField']['id'] ?>"
                                <?= $attribute['allChecked'] ? 'checked' : '' ?>
                                <?= $attribute['disabled'] ?>>
                            <span class="block w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-gradient-to-r peer-checked:from-success-500 peer-checked:to-success-600 dark:peer-checked:from-success-600 dark:peer-checked:to-success-700 peer-checked:shadow-[0_0_15px_rgba(34,197,94,0.5)]"></span>
                        </div>
                    </label>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-neutral-200 dark:bg-neutral-600 rounded-full h-2 overflow-hidden">
                    <div class="module-progress h-full rounded-full transition-all duration-500 ease-out <?= $checkedItems === $totalItems && $totalItems > 0 ? 'bg-gradient-to-r from-success-500 to-success-600' : ($checkedItems > 0 ? 'bg-gradient-to-r from-warning-500 to-warning-600' : 'bg-neutral-400') ?>" 
                         style="width: <?= $totalItems > 0 ? round(($checkedItems / $totalItems) * 100) : 0 ?>%">
                    </div>
                </div>
            </div>

            <!-- BODY: Items -->
            <div class="p-5 grow">
                <?php if (!empty($attribute['dataField']['items'])): ?>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($attribute['dataField']['items'] as $item): ?>
                            <?php
                            $id = $attribute['dataField']['id'] . '_' . $item['name'];
                            $checked = !empty($item['checked']) ? 'checked' : '';
                            ?>
                            <div class="relative group">
                                <input type="checkbox"
                                    id="<?= $id ?>"
                                    name="<?= $attribute['field'] ?>[<?= $attribute['dataField']['id'] ?>][]"
                                    value="<?= $item['id'] ?>"
                                    data-field="<?= $attribute['dataField']['id'] ?>"
                                    <?= $checked ?>
                                    <?= $attribute['disabled'] ?>
                                    class="permission-checkbox peer sr-only">
                                <label for="<?= $id ?>" 
                                       class="permission-badge inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold border-2 
                                       bg-white dark:bg-gray-800 text-neutral-700 dark:text-neutral-300 
                                       border-neutral-300 dark:border-neutral-600
                                       cursor-pointer transition-all duration-300 ease-out 
                                       hover:bg-neutral-50 dark:hover:bg-gray-700 hover:scale-[1.02] hover:border-neutral-400 dark:hover:border-neutral-500 hover:shadow-md
                                       active:scale-95
                                       relative overflow-hidden <?= $checked ? 'is-checked' : '' ?>">
                                    <!-- Checkmark Icon with Animation -->
                                    <div class="checkmark-container relative w-5 h-5 shrink-0">
                                        <!-- Unchecked State: Circle Outline -->
                                        <div class="unchecked-state absolute inset-0 rounded-full border-2 border-neutral-400 dark:border-neutral-500 
                                                    transition-all duration-300 ease-out
                                                    group-hover:border-primary-500 group-hover:scale-110"></div>
                                        
                                        <!-- Checked State: Filled Circle with Checkmark -->
                                        <div class="checked-state absolute inset-0 rounded-full bg-white dark:bg-primary-100
                                                    opacity-0 scale-0
                                                    transition-all duration-300 ease-out
                                                    flex items-center justify-center">
                                            <iconify-icon icon="solar:check-circle-bold" 
                                                         class="text-base text-primary-600 dark:text-primary-700"></iconify-icon>
                                        </div>
                                        
                                        <!-- Ripple Effect on Click -->
                                        <div class="ripple-effect absolute inset-0 rounded-full bg-primary-400 
                                                    opacity-0 scale-0"></div>
                                    </div>
                                    
                                    <!-- Label Text -->
                                    <span class="badge-text relative z-10"><?= htmlspecialchars($item['label']) ?></span>
                                    
                                    <!-- Success Indicator Badge (appears on checked) -->
                                    <div class="success-badge absolute -top-1 -right-1 w-5 h-5 rounded-full bg-success-500 dark:bg-success-600 
                                                flex items-center justify-center shadow-lg
                                                opacity-0 scale-0
                                                transition-all duration-300 delay-100 ease-out">
                                        <iconify-icon icon="solar:check-circle-bold" class="text-xs text-white"></iconify-icon>
                                    </div>
                                    
                                    <!-- Shimmer Effect -->
                                    <div class="shimmer-effect absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent
                                                translate-x-[-200%] opacity-0"></div>
                                    
                                    <!-- Radial Glow -->
                                    <div class="radial-glow absolute inset-0 rounded-xl opacity-0
                                                bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.2),transparent_70%)]
                                                transition-opacity duration-500"></div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-xs text-neutral-400 italic">No permission items available.</div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    $(function() {
        function getChildren(fieldId) {
            return $('input[data-field="' + fieldId + '"]');
        }

        function updateParentState(fieldId) {
            const $children = getChildren(fieldId);
            const $parent = $('.dataField-select-all[data-field-id="' + fieldId + '"]');
            if ($parent.length === 0) return;

            const total = $children.length;
            const checkedCount = $children.filter(':checked').length;
            const percentage = total > 0 ? Math.round((checkedCount / total) * 100) : 0;

            $parent.prop('checked', total > 0 && checkedCount === total);
            $parent.prop('indeterminate', checkedCount > 0 && checkedCount < total);
            
            // Update selection count display
            const $card = $parent.closest('.card');
            const $countSpan = $card.find('.module-count');
            $countSpan.text(checkedCount + ' / ' + total + ' selected');
            
            // Update percentage display
            const $percentageSpan = $card.find('.module-percentage');
            $percentageSpan.text('(' + percentage + '%)');
            
            // Update percentage color
            $percentageSpan.removeClass('text-success-600 dark:text-success-400 text-warning-600 dark:text-warning-400 text-neutral-500 dark:text-neutral-500');
            if (checkedCount === total && total > 0) {
                $percentageSpan.addClass('text-success-600 dark:text-success-400');
            } else if (checkedCount > 0) {
                $percentageSpan.addClass('text-warning-600 dark:text-warning-400');
            } else {
                $percentageSpan.addClass('text-neutral-500 dark:text-neutral-500');
            }
            
            // Update progress bar
            const $progressBar = $card.find('.module-progress');
            $progressBar.css('width', percentage + '%');
            
            // Update progress bar color
            $progressBar.removeClass('bg-gradient-to-r from-success-500 to-success-600 from-warning-500 to-warning-600 bg-neutral-400');
            if (checkedCount === total && total > 0) {
                $progressBar.addClass('bg-gradient-to-r from-success-500 to-success-600');
            } else if (checkedCount > 0) {
                $progressBar.addClass('bg-gradient-to-r from-warning-500 to-warning-600');
            } else {
                $progressBar.addClass('bg-neutral-400');
            }
            
            // Update toggle switch color
            const $toggleSpan = $parent.next('span');
            $toggleSpan.removeClass('peer-checked:bg-gradient-to-r peer-checked:from-success-500 peer-checked:to-success-600 dark:peer-checked:from-success-600 dark:peer-checked:to-success-700');
            if ($parent.prop('checked')) {
                $toggleSpan.addClass('peer-checked:bg-gradient-to-r peer-checked:from-success-500 peer-checked:to-success-600 dark:peer-checked:from-success-600 dark:peer-checked:to-success-700');
            }
            
            // Update card border color
            $card.removeClass('border-success-300 dark:border-success-700 bg-success-50/30 dark:bg-success-900/10 border-warning-300 dark:border-warning-700 bg-warning-50/30 dark:bg-warning-900/10 border-neutral-200 dark:border-neutral-700');
            
            if (checkedCount === total && total > 0) {
                $card.addClass('border-success-300 dark:border-success-700 bg-success-50/30 dark:bg-success-900/10');
            } else if (checkedCount > 0) {
                $card.addClass('border-warning-300 dark:border-warning-700 bg-warning-50/30 dark:bg-warning-900/10');
            } else {
                $card.addClass('border-neutral-200 dark:border-neutral-700');
            }
        }

        // Parent "Check All"
        $('.dataField-select-all').on('change', function() {
            const fieldId = $(this).data('field-id');
            const $children = getChildren(fieldId);

            $children.prop('checked', this.checked).trigger('change');
        });

        // Children listener
        $('input[data-field]').on('change', function() {
            updateParentState($(this).data('field'));
        });

        // Init state on page load
        $('.dataField-select-all').each(function() {
            updateParentState($(this).data('field-id'));
        });
        
        // Handle permission badge visual state
        $('.permission-checkbox').on('change', function() {
            const $badge = $(this).next('.permission-badge');
            const $ripple = $badge.find('.ripple-effect');
            
            if (this.checked) {
                $badge.addClass('is-checked');
                // Trigger ripple effect
                $ripple.addClass('animate-ripple');
                setTimeout(() => {
                    $ripple.removeClass('animate-ripple');
                }, 600);
            } else {
                $badge.removeClass('is-checked');
            }
        });
        
        // Initialize checked state on page load
        $('.permission-checkbox:checked').each(function() {
            $(this).next('.permission-badge').addClass('is-checked');
        });
    });
</script>
