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
<div class="mb-8">
    <div class="flex items-center space-x-2 font-semibold">
        <label class="inline-flex items-center cursor-pointer" for="select_all_<?= $attribute['fieldId'] ?>">
            <input type="checkbox"
                class="dataField-select-all sr-only peer"
                id="select_all_<?= $attribute['fieldId'] ?>"
                data-field-id="<?= $attribute['fieldId'] ?>"
                <?= $attribute['allChecked'] ? 'checked' : '' ?>
                <?= $attribute['disabled'] ?>>
            <span class="relative w-11 h-6 bg-gray-400 peer-focus:outline-none rounded-full peer dark:bg-gray-500 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></span>
            <span class="line-height-1 font-medium ms-3 peer-checked:text-primary-600 text-sm text-gray-600 dark:text-gray-300">
                Check All
            </span>
        </label>
    </div>

    <div class="ml-2 flex flex-wrap items-start justify-start gap-x-6">
        <?php foreach ($attribute['source'] as $i => $item): ?>
            <?php
            $id      = $attribute['fieldId'] . '_' . $i;
            $checked = !empty($item['checked']) ? 'checked' : '';
            ?>
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