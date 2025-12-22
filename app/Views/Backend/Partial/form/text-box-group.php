<?php

$attribute['type']        = $attribute['type']  ?? 'text';
$attribute['label']       = $attribute['label']  ?? '';
$attribute['placeholder']  = $attribute['placeholder']  ?? $attribute['label'];
$attribute['value']  = $attribute['value']  ?? ((isset($data)) ? $data->{$attribute['field']} : null);
$attribute['groupValue']  = $attribute['groupValue']  ?? ((isset($data)) ? $data->{$attribute['group']['field']} : null);

$attribute['required']     = isset($attribute['required']) && $attribute['required']  ? 'required' : '';
$attribute['readonly']    = (isset($attribute['readonly']) && $attribute['readonly']) || (isset($param)) ? (strtolower($param['action']) == "detail" ? 'readonly' : '') : '';
$attribute['disabled']     = (isset($attribute['disabled']) && $attribute['disabled']) || (isset($param)) ? (strtolower($param['action']) == "detail" ? 'disabled' : '') : '';
?>

<label class="form-label text-sm" for="<?= esc($attribute['field']) ?>">
    <?= esc($attribute['label']) ?>
</label>
<?php if ($attribute['type']  === "password"): ?>
    <div class="relative">
        <input
            type="password"
            name="<?= esc($attribute['field']) ?>"
            value="<?= $attribute['value']  ?>"
            data-parsley-minlength="8"
            class="text-sm form-control"
            placeholder="<?= esc($attribute['placeholder']) ?>"
            <?= $attribute['required']  ?>
            <?= $attribute['readonly']  ?>
            <?= $attribute['disabled']  ?>
            id="your-password" />
        <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#your-password"></span>
    </div>

<?php elseif ($attribute['type']  === "email"): ?>
    <input
        name="<?= esc($attribute['field']) ?>"
        type="email"
        value="<?= $attribute['value']  ?>"
        data-parsley-trigger="change"
        class="text-sm form-control"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        <?= $attribute['required']  ?>
        <?= $attribute['readonly']  ?>
        <?= $attribute['disabled']  ?> />
<?php elseif ($attribute['type']  === "textarea"): ?>
    <textarea
        rows="<?= $rows ?? 4 ?>"
        id="<?= esc($attribute['field']) ?>"
        name="<?= esc($attribute['field']) ?>"
        class="text-sm form-control"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        <?= $attribute['required']  ?>
        <?= $attribute['readonly']  ?>
        <?= $attribute['disabled']  ?>><?= $attribute['value']  ?></textarea>
<?php elseif ($attribute['type']  === "number"): ?>
    <input
        name="<?= esc($attribute['field']) ?>"
        type="number"
        value="<?= $attribute['value']  ?>"
        class="text-sm form-control"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        <?= $attribute['required']  ?>
        <?= $attribute['readonly']  ?>
        <?= $attribute['disabled']  ?> />
<?php elseif ($attribute['type']  === "text-select"): ?>
    <div class="flex">
        <input
            name="<?= esc($attribute['field']) ?>"
            type="text"
            value="<?= $attribute['value']  ?>"
            class="text-sm form-control rounded-se-none rounded-ee-none"
            data-parsley-errors-container="#text-select-error"
            placeholder="<?= esc($attribute['placeholder']) ?>"
            <?= $attribute['required']  ?>
            <?= $attribute['readonly']  ?>
            <?= $attribute['disabled']  ?> />
        <select
            <?= $attribute['required']  ?>
            <?= $attribute['disabled']  ?>
            name="<?= esc($attribute['group']['field']) ?>"
            id="<?= esc($attribute['group']['field']) ?>"
            data-parsley-errors-container="#text-select-error"
            class="form-select select2-no-border flex-grow-0 !rounded-ss-none !rounded-es-none border-s-0 w-auto">
            <?php foreach ($attribute['group']['data'] as $item): ?>
                <option <?= $attribute['groupValue']  === $item ? "selected" : "" ?>><?= $item ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div id="text-select-error"></div>
    <script>
        $(document).ready(function() {
            const $select = $('#<?= esc($attribute['group']['field']) ?>');
            $select.select2({
                placeholder: 'Select a <?= esc($attribute['placeholder']) ?>',
                width: '100%',
                <?php if((isset($attribute['group']['clear']))): ?> allowClear: true
                    <?php endif; ?>
            })
        });
    </script>
<?php endif; ?>