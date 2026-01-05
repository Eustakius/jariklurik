<?php
$attribute['selected'] = $attribute['selected'] ?? [];
$attribute['disabled']    = (isset($attribute['disabled']) && $attribute['disabled']) || (strtolower($param['action']) == "detail") ? 'disabled' : '';
?>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach ($attribute['source'] as $attribute['dataField']): ?>
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
        <div class="card border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden h-full flex flex-col">
            <!-- HEADER: Permisson & Check All -->
            <div class="bg-neutral-100 dark:bg-neutral-700 px-4 py-3 border-b border-neutral-200 dark:border-neutral-700 flex items-center justify-between shrink-0">
                <span class="font-bold text-neutral-800 dark:text-white text-sm">
                    <?= htmlspecialchars($attribute['dataField']['name']) ?>
                </span>
                
                <label class="inline-flex items-center cursor-pointer gap-2" for="select_all_<?= $attribute['dataField']['id'] ?>">
                    <span class="text-xs font-bold text-neutral-600 dark:text-neutral-300">Check All</span>
                    <div class="relative">
                        <input type="checkbox"
                            class="dataField-select-all sr-only peer"
                            id="select_all_<?= $attribute['dataField']['id'] ?>"
                            data-field-id="<?= $attribute['dataField']['id'] ?>"
                            <?= $attribute['allChecked'] ? 'checked' : '' ?>
                            <?= $attribute['disabled'] ?>>
                        <span class="block w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-500 peer-checked:bg-primary-600"></span>
                    </div>
                </label>
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
                            <div class="relative">
                                <input type="checkbox"
                                    id="<?= $id ?>"
                                    name="<?= $attribute['field'] ?>[<?= $attribute['dataField']['id'] ?>][]"
                                    value="<?= $item['id'] ?>"
                                    data-field="<?= $attribute['dataField']['id'] ?>"
                                    <?= $checked ?>
                                    <?= $attribute['disabled'] ?>
                                    class="peer sr-only">
                                <label for="<?= $id ?>" 
                                       class="inline-flex px-3 py-1.5 rounded-md text-xs font-bold border border-neutral-200 dark:border-neutral-600 bg-white dark:bg-gray-700 text-neutral-600 dark:text-neutral-300 cursor-pointer transition-all duration-200 ease-out 
                                       hover:bg-neutral-100 dark:hover:bg-gray-600 hover:scale-105 active:scale-95
                                       peer-checked:bg-primary-500 peer-checked:text-white peer-checked:border-primary-500 
                                       dark:peer-checked:bg-primary-600 dark:peer-checked:text-white dark:peer-checked:border-primary-600 
                                       peer-checked:shadow-[0_0_20px] peer-checked:shadow-primary-500/50 dark:peer-checked:shadow-primary-500/70">
                                    <?= htmlspecialchars($item['label']) ?>
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

            $parent.prop('checked', total > 0 && checkedCount === total);
            $parent.prop('indeterminate', checkedCount > 0 && checkedCount < total);
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
    });
</script>