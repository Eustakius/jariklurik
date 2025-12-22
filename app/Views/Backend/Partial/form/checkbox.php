<?php
$attribute['type']         = $attribute['type']  ?? 'checkbox';
$attribute['label']        = $attribute['label']  ?? '';
$v[$attribute['field'] ]   = $value ?? ((isset($data)) ? $data->{$attribute['field']} : 0) ?? 0;

$attribute['required']     = isset($attribute['required'] ) && $attribute['required']  ? 'required' : '';
$attribute['readonly']    = (isset($attribute['readonly']) && $attribute['readonly']) || (isset($param)) ? (strtolower($param['action']) == "detail" ? 'readonly' : '') : '';
$attribute['disabled']     = isset($attribute['disabled'] ) && $attribute['disabled']  ? 'disabled' : '';

$attribute['isChecked']    = ($v[$attribute['field'] ] == "1" || $v[$attribute['field'] ] === 1 || $v[$attribute['field'] ] === true);
?>
<label class="inline-flex items-center cursor-pointer mt-2" for="<?= esc($attribute['field'] ) ?>">
    <!-- Hidden biar kalau tidak dicentang tetap kirim 0 -->
    <input type="hidden" name="<?= esc($attribute['field'] ) ?>" value="0">

    <input type="checkbox"
        class="sr-only peer"
        id="<?= esc($attribute['field'] ) ?>"
        name="<?= esc($attribute['field'] ) ?>"
        value="1"
        <?= $attribute['isChecked']  ? 'checked' : '' ?>
        <?= $attribute['disabled']  ?>
        <?= $attribute['required']  ?>>
    
    <span class="relative w-11 h-6 bg-gray-400 peer-focus:outline-none rounded-full peer 
        dark:bg-gray-500 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full 
        peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] 
        after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 
        after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></span>
    
    <span class="line-height-1 font-medium ms-3 peer-checked:text-primary-600 text-sm text-gray-600 dark:text-gray-300">
        <?= esc($attribute['label'] ) ?>
    </span>
</label>
