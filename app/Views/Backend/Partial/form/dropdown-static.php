<?php
$attribute['placeholder']  = $attribute['placeholder']  ?? $attribute['label'];
$attribute['value']  = old($attribute['field']) ?? $attribute['value']  ?? ((isset($data)) ? $data->{$attribute['field']} : "");
$attribute['required']     = isset($attribute['required']) && $attribute['required']  ? 'required' : '';
$attribute['disabled']  = (isset($attribute['disabled']) && $attribute['disabled']) ||  (isset($param)) ? (strtolower($param['action']) == "detail" ? 'readonly' : '') : '';
?>
<label class="form-label text-sm" for="<?= esc($attribute['field']) ?>">
    <?= esc($attribute['label']) ?>
</label>
<div class="text-sm [&_span]:text-sm">
    <select
        data-parsley-errors-container="#<?= esc($attribute['field']) ?>-error"
        id="<?= esc($attribute['field']) ?>"
        name="<?= esc($attribute['field']) ?>"
        <?= $attribute['required']  ?>
        <?= $attribute['disabled']  ?>
        class="text-sm form-select w-full">
        <option value=""></option>
        <?php foreach ($attribute['data'] as $item): ?>
            <option <?= $item['value'] == $attribute['value'] ? "selected" : "" ?> value="<?= $item['value'] ?>"><?= $item['label'] ?></option>
        <?php endforeach ?>
    </select>
    <div id="<?= esc($attribute['field']) ?>-error" class="text-red-500 text-base mt-1"></div>
</div>

<script>
    $(document).ready(function() {
        const $select = $('#<?= esc($attribute['field']) ?>');
        $select.select2({
            placeholder: 'Select a <?= esc($attribute['placeholder']) ?>',
            allowClear: true,
            width: '100%',
        });

        <?php if (isset($attribute['required'])): ?>
            $select.on('change.select2', function() {
                $(this).parsley().validate();
            });

            $select.parsley().on('field:success', function() {
                const $select2Container = $select.next('.select2-container').find('.select2-selection');
                $select2Container.removeClass('parsley-error');
            });

            $select.parsley().on('field:error', function() {
                const $select2Container = $select.next('.select2-container').find('.select2-selection');
                $select2Container.addClass('parsley-error');
            });
        <?php endif; ?>
    });
</script>