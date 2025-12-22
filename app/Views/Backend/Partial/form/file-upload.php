<?php
$attribute['label']      = $attribute['label'] ?? '';
$v[$attribute['field']] = old($attribute['field']) ?? $v[$attribute['field']] ?? $data->{$attribute['field']};
$attribute['placeholder'] = $attribute['placeholder'] ?? $attribute['label'];
$attribute['required']    = isset($attribute['required']) && $attribute['required'] ? 'required' : '';
$attribute['readonly']    = (isset($attribute['readonly']) && $attribute['readonly']) || (strtolower($param['action']) == "detail") ? 'readonly' : '';
$attribute['disabled']    = isset($attribute['disabled']) && $attribute['disabled'] ? 'disabled' : '';
$attribute['accept']    = isset($attribute['accept']) && $attribute['accept'] ? 'data-parsley-fileextension="' . str_replace(".","",$attribute['accept']) . '" accept="' . $attribute['accept'] . '" data-parsley-fileextension-message="Invalid file type. Only (' . $attribute['accept'] . ')"' : '';

?>
<label class="form-label text-sm" for="<?= esc($attribute['field']) ?>">
    <?= esc($attribute['label']) ?> 
</label>
<?php if(isset($attribute['info']) && $attribute['info'] ): ?>
<p><i class="dark:text-danger-400 text-danger-600 text-xs"><?= esc($attribute['info']) ?></i></p>
<?php endif; ?>
<div class="upload-image-wrapper flex flex-col items-center gap-3">
    <label for="upload-file" class="w-full border border-primary-600 font-medium text-primary-600 px-4 py-2 rounded-xl inline-flex items-center gap-2 cursor-pointer hover:bg-primary-50">
        <iconify-icon icon="solar:upload-linear" class="text-xl"></iconify-icon>
        Click to upload
        <input 
        hidden 
        type="file" 
        id="upload-file"
        name="<?= esc($attribute['field']) ?>"
        <?= $attribute['required'] ?>
        <?= $attribute['readonly'] ?>
        <?= $attribute['disabled'] ?>
        <?= $attribute['accept'] ?>
        data-parsley-maxfilesize="1048576"
        data-parsley-maxfilesize-message="File size must not exceed 1 MB."
        data-parsley-errors-container="#upload-error"
        >
    </label>
    <div class="uploaded-img <?= isset($v[$attribute['field']]) ? '' : 'hidden' ?> relative w-full h-auto border input-form-light rounded-lg overflow-hidden border-dashed bg-neutral-50 dark:bg-neutral-600">
        <button type="button" class="uploaded-img__remove absolute top-0 end-0 z-1 text-2xxl line-height-1 me-2 mt-2 flex">
            <iconify-icon icon="radix-icons:cross-2" class="text-xl text-danger-600"></iconify-icon>
        </button>
        <img id="uploaded-img__preview" class="w-full h-full object-fit-cover" src="<?= esc($v[$attribute['field']]) ?>" alt="image">
    </div>
    <div id="upload-error"></div>
</div>

<script>
    $(document).ready(function() {
        const $fileInput = $("#upload-file");
        const $imagePreview = $("#uploaded-img__preview");
        const $uploadedImgContainer = $(".uploaded-img");
        const $removeButton = $(".uploaded-img__remove");

        // Saat file dipilih
        $fileInput.on("change", function() {
            const file = this.files[0];
            if (file) {
                const src = URL.createObjectURL(file);
                $imagePreview.attr("src", src);
                $uploadedImgContainer.removeClass("hidden");
                $imagePreview.show(); // pastikan tampil
            }
        });

        // Saat tombol remove diklik
        $removeButton.on("click", function() {
            $imagePreview.attr("src", "../assets/images/user.png"); // reset ke default
            $uploadedImgContainer.addClass("hidden");
            $fileInput.val("");
        });
    });
</script>