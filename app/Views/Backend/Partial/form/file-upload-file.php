<?php
$attribute['label']      = $attribute['label'] ?? '';
$v[$attribute['field']]  = old($attribute['field']) ?? $v[$attribute['field']] ?? $data->{$attribute['field']};
$attribute['placeholder'] = $attribute['placeholder'] ?? $attribute['label'];
$attribute['required']    = isset($attribute['required']) && $attribute['required'] ? 'required' : '';
$attribute['readonly']    = (isset($attribute['readonly']) && $attribute['readonly']) || (strtolower($param['action']) == "detail") ? 'readonly' : '';
$attribute['disabled']    = isset($attribute['disabled']) && $attribute['disabled'] ? 'disabled' : '';
$attribute['accept']      = isset($attribute['accept']) && $attribute['accept'] ? 'data-parsley-fileextension="' . str_replace(".", "", $attribute['accept']) . '" accept="' . $attribute['accept'] . '" data-parsley-fileextension-message="Invalid file type. Only (' . $attribute['accept'] . ')"' : '';
?>

<label class="form-label text-sm" for="<?= esc($attribute['field']) ?>">
    <?= esc($attribute['label']) ?>
</label>

<?php if (isset($attribute['info']) && $attribute['info']): ?>
<p><i class="dark:text-danger-400 text-danger-600 text-xs"><?= esc($attribute['info']) ?></i></p>
<?php endif; ?>

<div class="upload-image-wrapper flex flex-col items-center gap-3">
    <label for="<?= esc($attribute['field']) ?>" class="w-full border border-primary-600 font-medium text-primary-600 px-4 py-2 rounded-xl inline-flex items-center gap-2 cursor-pointer hover:bg-primary-50">
        <iconify-icon icon="solar:upload-linear" class="text-xl"></iconify-icon>
        Click to upload
        <input 
            hidden 
            type="file" 
            id="<?= esc($attribute['field']) ?>"
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

    <!-- Preview file -->
    <div class="uploaded-img <?= isset($v[$attribute['field']]) && $v[$attribute['field']] ? '' : 'hidden' ?> relative w-full h-auto border input-form-light rounded-lg overflow-hidden border-dashed bg-neutral-50 dark:bg-neutral-600">
        <button type="button" class="uploaded-img__remove absolute top-0 end-0 z-1 text-2xxl line-height-1 me-2 mt-2 flex">
            <iconify-icon icon="radix-icons:cross-2" class="text-xl text-danger-600"></iconify-icon>
        </button>

        <a id="uploaded-img__preview" href="<?= esc($v[$attribute['field']] ?? '#') ?>" target="_blank" class="block w-full h-auto p-2">
            View
        </a>
    </div>

    <div id="upload-error"></div>
</div>

<script>
$(document).ready(function() {
    const $fileInput = $("#<?= esc($attribute['field']) ?>");
    const $imageLink = $("#uploaded-img__preview");
    const $uploadedImgContainer = $(".uploaded-img");
    const $removeButton = $(".uploaded-img__remove");

    const defaultImage = "../assets/images/user.png";
    const fileIcon = "../assets/images/file-icon.png"; // ikon default untuk file non-image

    function isImage(fileName) {
        return /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(fileName);
    }

    $fileInput.on("change", function() {
        const file = this.files[0];
        if (file) {
            const src = URL.createObjectURL(file);
            const fileName = file.name;

            $imageLink.attr("href", src);

            $uploadedImgContainer.removeClass("hidden");
        }
    });

    $removeButton.on("click", function() {
        $imageLink.attr("href", "#");
        $uploadedImgContainer.addClass("hidden");
        $fileInput.val("");
    });
});
</script>
