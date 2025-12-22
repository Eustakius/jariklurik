<?php
$attribute['selected'] = $attribute['selected'] ?? [];
$attribute['disabled']    = (isset($attribute['disabled']) && $attribute['disabled']) || (strtolower($param['action']) == "detail") ? 'disabled' : '';
?>

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
    <div class="mb-8">
        <!-- PARENT (CHECK ALL) -->
        <div class="flex items-center space-x-2 font-semibold">
            <label class="inline-flex items-center cursor-pointer" for="select_all_<?= $attribute['dataField']['id'] ?>">
                <input type="checkbox"
                    class="dataField-select-all sr-only peer"
                    id="select_all_<?= $attribute['dataField']['id'] ?>"
                    data-field-id="<?= $attribute['dataField']['id'] ?>"
                    <?= $attribute['allChecked'] ? 'checked' : '' ?>
                    <?= $attribute['disabled'] ?>>
                <span class="relative w-11 h-6 bg-gray-400 peer-focus:outline-none rounded-full peer dark:bg-gray-500 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></span>
                <span class="line-height-1 font-medium ms-3 peer-checked:text-primary-600 text-sm text-gray-600 dark:text-gray-300">
                    <b><?= htmlspecialchars($attribute['dataField']['name']) ?></b> Check All
                </span>
            </label>
        </div>

        <!-- CHILDREN -->
        <?php if (!empty($attribute['dataField']['items'])): ?>
            <div class="ml-2 flex flex-wrap items-start justify-start gap-x-6">
                <?php foreach ($attribute['dataField']['items'] as $item): ?>
                    <?php
                    $id = $attribute['dataField']['id'] . '_' . $item['name'];
                    $checked = !empty($item['checked']) ? 'checked' : '';
                    ?>
                    <label class="inline-flex items-center cursor-pointer mt-2" for="<?= $id ?>">
                        <input type="checkbox"
                            class="sr-only peer"
                            id="<?= $id ?>"
                            name="<?= $attribute['field'] ?>[<?= $attribute['dataField']['id'] ?>][]"
                            value="<?= $item['id'] ?>"
                            data-field="<?= $attribute['dataField']['id'] ?>"
                            <?= $checked ?>
                            <?= $attribute['disabled'] ?>>
                        <span class="relative w-11 h-6 bg-gray-400 peer-focus:outline-none rounded-full peer dark:bg-gray-500 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></span>
                        <span class="line-height-1 font-medium ms-3 peer-checked:text-primary-600 text-sm text-gray-600 dark:text-gray-300">
                            <?= htmlspecialchars($item['label']) ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

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