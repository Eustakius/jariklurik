<?php
$attribute['selected'] = $attribute['selected'] ?? [];
$attribute['disabled'] = (isset($attribute['disabled']) && $attribute['disabled']) || (strtolower($param['action']) == "detail") ? 'disabled' : '';
$attribute['fieldId']  = $attribute['field'] ?? uniqid('field_'); // unique id untuk parent-child grouping
?>

<?php
// Tentukan apakah semua child checked
$attribute['allChecked'] = true;
if (empty($attribute['dataField']['items'])) {
    $attribute['allChecked'] = false;
} else {
    foreach ($attribute['dataField']['items'] as $item) {
        if (empty($item['checked'])) {
            $attribute['allChecked'] = false;
            break;
        }
    }
}
?>
<?php 
    $display = $attribute['display'] ?? 'toggle'; // toggle, box, checkbox
?>
<!-- Force Checkbox Styles -->
<style>
    /* Direct sibling selector to guarantee generic CSS works if Tailwind peer fails */
    .checkbox-card-input:checked + .checkbox-card-box {
        border-color: #3b82f6 !important; /* blue-500 */
        background-color: rgba(59, 130, 246, 0.1) !important;
    }
    .checkbox-card-input:checked + .checkbox-card-box .checkbox-icon-wrapper {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
    }
    .checkbox-card-input:checked + .checkbox-card-box .checkbox-icon-wrapper iconify-icon {
        opacity: 1 !important;
    }
</style>

<div class="mb-8">
    <!-- Check All Control -->
    <div class="flex items-center space-x-2 font-semibold mb-4">
        <label class="inline-flex items-center cursor-pointer select-none" for="select_all_<?= $attribute['fieldId'] ?>">
            <input type="checkbox"
                class="dataField-select-all sr-only peer"
                id="select_all_<?= $attribute['fieldId'] ?>"
                data-field-id="<?= $attribute['fieldId'] ?>"
                <?= $attribute['allChecked'] ? 'checked' : '' ?>
                <?= $attribute['disabled'] ?>>
            
            <?php if($display === 'box'): ?>
                <div class="w-5 h-5 border-2 border-gray-400 rounded text-white flex items-center justify-center peer-checked:bg-primary-600 peer-checked:border-primary-600 transition-all bg-white dark:bg-neutral-700">
                    <iconify-icon icon="mingcute:check-line" class="text-xs opacity-0 peer-checked:opacity-100"></iconify-icon>
                </div>
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-300 group-hover:text-primary-600">Select All</span>
            <?php else: ?>
                <span class="relative w-11 h-6 bg-gray-400 peer-focus:outline-none rounded-full peer dark:bg-gray-500 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></span>
                <span class="line-height-1 font-medium ms-3 peer-checked:text-primary-600 text-sm text-gray-600 dark:text-gray-300">Check All</span>
            <?php endif; ?>
        </label>
    </div>

    <div class="<?= $display === 'box' ? 'grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4' : 'flex flex-wrap items-start justify-start gap-x-6' ?>">
        <?php foreach ($attribute['source'] as $i => $item): ?>
            <?php
            $id      = $attribute['fieldId'] . '_' . $i;
            $val     = $item['value'] ?? $i;
            $isSelected = in_array($val, $attribute['selected'] ?? []);
            $checked = (!empty($item['checked']) || $isSelected) ? 'checked' : '';
            ?>
            
            <?php if ($display === 'box'): ?>
                <!-- Card Style -->
                <label class="cursor-pointer relative group" for="<?= $id ?>">
                    <input type="checkbox"
                        class="checkbox-card-input sr-only peer"
                        id="<?= $id ?>"
                        name="<?= $attribute['field'] ?>[<?= $item['value'] ?? $i ?>]"
                        value="<?= $item['value'] ?? $i ?>"
                        data-field-id="<?= $attribute['fieldId'] ?>"
                        <?= $checked ?>
                        <?= $attribute['disabled'] ?>>
                    
                    <div class="checkbox-card-box h-full p-4 border border-gray-300 dark:border-gray-600 rounded-xl transition-all duration-200 
                                bg-white dark:bg-neutral-800 hover:border-primary-400 dark:hover:border-primary-500
                                flex items-center gap-3 shadow-sm">
                        
                        <!-- Visible Checkbox Icon -->
                        <div class="checkbox-icon-wrapper flex-shrink-0 w-5 h-5 rounded border-2 border-gray-300 dark:border-gray-500 flex items-center justify-center text-white transition-all bg-neutral-100 dark:bg-neutral-700">
                             <iconify-icon icon="mingcute:check-line" class="text-xs opacity-0 transition-opacity"></iconify-icon>
                        </div>
                        
                        <div class="flex flex-col select-none">
                            <span class="font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                <?= htmlspecialchars($item['label'] ?? $item['value'] ?? $i) ?>
                            </span>
                            <?php if(!empty($item['desc'])): ?>
                                <span class="text-xs text-gray-500"><?= $item['desc'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </label>
            
            <?php else: ?>
                <!-- Toggle Style (Legacy) -->
                <label class="inline-flex items-center cursor-pointer mt-2" for="<?= $id ?>">
                    <input type="checkbox"
                        class="sr-only peer"
                        id="<?= $id ?>"
                        name="<?= $attribute['field'] ?>[<?= $item['value'] ?? $i ?>]"
                        value="<?= $item['value'] ?? $i ?>"
                        data-field-id="<?= $attribute['fieldId'] ?>"
                        <?= $checked ?>
                        <?= $attribute['disabled'] ?>>
                    <span class="relative w-11 h-6 bg-gray-400 peer-focus:outline-none rounded-full peer dark:bg-gray-500 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></span>
                    <span class="line-height-1 font-medium ms-3 peer-checked:text-primary-600 text-sm text-gray-600 dark:text-gray-300">
                        <?= htmlspecialchars($item['label'] ?? $item['value'] ?? $i) ?>
                    </span>
                </label>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>
</div>

<script>
    $(function() {
        function updateParentState(fieldId) {
            const $children = $('input[data-field-id="' + fieldId + '"]:not(.dataField-select-all)');
            const $parent = $('.dataField-select-all[data-field-id="' + fieldId + '"]');
            if ($parent.length === 0) return;

            const total = $children.length;
            const checkedCount = $children.filter(':checked').length;

            $parent.prop('checked', total > 0 && checkedCount === total);
            $parent.prop('indeterminate', checkedCount > 0 && checkedCount < total);
        }

        // Parent "Check All"
        $('.dataField-select-all').on('change', function() {
            const fieldId = $(this).data('field-id');
            $('input[data-field-id="' + fieldId + '"]:not(.dataField-select-all)')
                .prop('checked', this.checked)
                .trigger('change');
        });

        // Children listener
        $('input[data-field-id]:not(.dataField-select-all)').on('change', function() {
            updateParentState($(this).data('field-id'));
        });

        // Init state on page load
        $('.dataField-select-all').each(function() {
            updateParentState($(this).data('field-id'));
        });
    });
</script>