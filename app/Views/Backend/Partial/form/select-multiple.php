<?php
$attribute['selected'] = $attribute['selected'] ?? [];
$attribute['disabled'] = (isset($attribute['disabled']) && $attribute['disabled']) || (strtolower($param['action']) == "detail") ? 'disabled' : '';
$attribute['id'] = $attribute['id'] ?? uniqid('select_');
$placeholder = $attribute['placeholder'] ?? 'Select Options';
?>
<div class="mb-4">
    <label for="<?= $attribute['id'] ?>" class="form-label fw-semibold text-primary-light text-sm mb-2">
        <?= $attribute['label'] ?> 
        <?php if(isset($attribute['required']) && $attribute['required']): ?>
            <span class="text-danger-600">*</span>
        <?php endif ?>
    </label>
    <select class="form-control radius-8 form-select select2" 
            id="<?= $attribute['id'] ?>" 
            name="<?= $attribute['field'] ?>[]" 
            multiple="multiple"
            data-placeholder="<?= $placeholder ?>"
            <?= $attribute['disabled'] ?>>
        <?php foreach ($attribute['source'] as $item): ?>
            <?php 
            $value = $item['value'] ?? $item['id'];
            $label = $item['label'] ?? $item['name'];
            // Check if selected (handle both array of IDs or array of objects/arrays)
            $isSelected = false;
            if (!empty($attribute['selected'])) {
                foreach ($attribute['selected'] as $selectedItem) {
                    $selectedVal = is_array($selectedItem) ? ($selectedItem['id'] ?? $selectedItem) : $selectedItem;
                    if ($selectedVal == $value) {
                        $isSelected = true;
                        break;
                    }
                }
            }
            // Fallback: check 'checked' property in source items if 'selected' array not passed
            if (!$isSelected && !empty($item['checked'])) {
                $isSelected = true;
            }
            ?>
            <option value="<?= $value ?>" <?= $isSelected ? 'selected' : '' ?>>
                <?= htmlspecialchars($label) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<script>
    $(document).ready(function() {
        $('#<?= $attribute['id'] ?>').select2({
            placeholder: "<?= $placeholder ?>",
            allowClear: true,
            width: '100%' // Ensure it fits container
        });
    });
</script>
