<?php

$attribute['type']       = $attribute['type'] ?? 'text';
$attribute['label']      = $attribute['label'] ?? '';
$attribute['placeholder'] = $attribute['placeholder'] ?? $attribute['label'];
$v[$attribute['field']] = old($attribute['field']) ?? $attribute['value'] ?? ((isset($data)) ? $data->{$attribute['field']} : null);
$attribute['autocomplete']  = isset($attribute['autocomplete']) && $attribute['autocomplete'] ? 'autocomplete="off"' : '';
$attribute['required']    = isset($attribute['required']) && $attribute['required'] ? 'required' : '';
$attribute['readonly']    = isset($param) ? (strtolower($param['action']) == "detail" ? 'readonly' : ((isset($attribute['readonly'])) ? 'readonly' : '')) : '';
$attribute['disabled']    = (isset($attribute['disabled']) && $attribute['disabled']) ||  (isset($param)) ? (strtolower($param['action']) == "detail" ? 'disabled' : '') : '';
$attribute['maxlength']    = isset($attribute['maxlength']) && $attribute['maxlength'] ? 'data-parsley-maxlength="' . $attribute['maxlength'] . '" maxlength="' . $attribute['maxlength'] . '"' : '';
$attribute['minlength']    = isset($attribute['minlength']) && $attribute['minlength'] ? 'data-parsley-minlength="' . $attribute['minlength'] . '" minlength="' . $attribute['minlength'] . '"' : '';

?>

<?php if ($attribute['type'] != "hidden"): ?>
    <label class="form-label text-sm" for="<?= esc($attribute['field']) ?>">
        <?= esc($attribute['label']) ?>
    </label>
<?php endif; ?>

<?php if ($attribute['type'] === "password"): ?>
    <div class="relative">
        <input
            type="password"
            name="<?= esc($attribute['field']) ?>"
            value="<?= $v[$attribute['field']] ?>"
            data-parsley-minlength="8"
            class="text-sm form-control"
            placeholder="<?= esc($attribute['placeholder']) ?>"
            <?= $attribute['autocomplete'] ?>
            <?= $attribute['required'] ?>
            <?= $attribute['readonly'] ?>
            <?= $attribute['disabled'] ?>
            <?= $attribute['maxlength'] ?>
            <?= $attribute['minlength'] ?>
            id="your-password" />
        <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#your-password"></span>
    </div>

<?php elseif ($attribute['type'] === "email"): ?>
    <input
        name="<?= esc($attribute['field']) ?>"
        type="email"
        value="<?= $v[$attribute['field']] ?>"
        data-parsley-trigger="change"
        class="text-sm form-control"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        <?= $attribute['autocomplete'] ?>
        <?= $attribute['required'] ?>
        <?= $attribute['readonly'] ?>
        <?= $attribute['disabled'] ?>
        <?= $attribute['maxlength'] ?>
        <?= $attribute['minlength'] ?> />
<?php elseif ($attribute['type'] === "textarea"): ?>
    <textarea
        rows="<?= $attribute['rows'] ?? 4 ?>"
        id="<?= esc($attribute['field']) ?>"
        name="<?= esc($attribute['field']) ?>"
        class="text-sm form-control"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        <?= $attribute['autocomplete'] ?>
        <?= $attribute['required'] ?>
        <?= $attribute['readonly'] ?>
        <?= $attribute['maxlength'] ?>
        <?= $attribute['minlength'] ?>
        <?= $attribute['disabled'] ?>><?= $v[$attribute['field']] ?></textarea>
<?php elseif ($attribute['type'] === "editor"): ?>
    <textarea
        rows="<?= $attribute['rows'] ?? 4 ?>"
        id="<?= esc($attribute['field']) ?>"
        name="<?= esc($attribute['field']) ?>"
        class="text-sm form-control"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        <?= $attribute['autocomplete'] ?>
        <?= $attribute['required'] ?>
        <?= $attribute['readonly'] ?>
        <?= $attribute['maxlength'] ?>
        <?= $attribute['minlength'] ?>
        <?= $attribute['disabled'] ?>><?= $v[$attribute['field']] ?></textarea>
    <script>
        $(document).ready(function() {

            const isReadonly = <?= $attribute['readonly'] === "readonly" ? 'true' : 'false' ?>;

            tinymce.init({
                selector: 'textarea#<?= esc($attribute['field']) ?>',
                plugins: 'lists image link code',
                toolbar: isReadonly ? false : 'undo redo | link image | bold italic underline | bullist numlist | alignleft aligncenter alignright',
                menubar: !isReadonly,
                license_key: 'gpl',
                convert_urls: false,
                valid_styles: {
                    '*': 'text-align,color,font-weight,font-style,text-decoration'
                },
                image_title: true,
                automatic_uploads: true,
                file_picker_types: 'image',

                // Jika readonly → matikan file picker
                file_picker_callback: isReadonly ? null : (cb, value, meta) => {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        const reader = new FileReader();
                        reader.addEventListener('load', () => {
                            const id = 'blobid' + (new Date()).getTime();
                            const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            const base64 = reader.result.split(',')[1];
                            const blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        });
                        reader.readAsDataURL(file);
                    });

                    input.click();
                },

                paste_preprocess: function(plugin, args) {
                    args.content = args.content.replace(/font-family\s*:\s*[^;"]+;?/gi, '');
                },

                setup: function(editor) {
                    editor.on('init', function() {
                        if (isReadonly) {
                            editor.getBody().setAttribute('contenteditable', false);
                            editor.getBody().style.pointerEvents = 'none';
                        }
                    });
                }
            });
        });
    </script>

<?php elseif ($attribute['type'] === "number"): ?>
    <input
        name="<?= esc($attribute['field']) ?>"
        type="number"
        value="<?= $v[$attribute['field']] ?? 0 ?>"
        class="text-sm form-control"
        min="0"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        <?= $attribute['autocomplete'] ?>
        <?= $attribute['required'] ?>
        <?= $attribute['readonly'] ?>
        <?= $attribute['disabled'] ?>
        <?= $attribute['maxlength'] ?>
        <?= $attribute['minlength'] ?> />
<?php elseif ($attribute['type'] === "date"): ?>
    <div class="icon-field-r mb-4">

        <input
            name="<?= esc($attribute['field']) ?>"
            type="date"
            value="<?= $v[$attribute['field']] ?>"
            class="text-sm form-control"
            placeholder="<?= esc($attribute['placeholder']) ?>"
            <?= $attribute['autocomplete'] ?>
            <?= $attribute['required'] ?>
            <?= $attribute['readonly'] ?>
            <?= $attribute['disabled'] ?>
            <?= $attribute['maxlength'] ?>
            <?= $attribute['minlength'] ?> />
        <span class="calendarIcon absolute right-[35px] top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
            <iconify-icon icon="mage:calendar-2"></iconify-icon>
        </span>
        <span class="clearIcon absolute right-[10px] top-1/2 -translate-y-1/2 cursor-pointer flex text-xl">
            <iconify-icon icon="mage:multiply"></iconify-icon>
        </span>
    </div>
<?php elseif ($attribute['type'] === "hidden"): ?>
    <input
        name="<?= esc($attribute['field']) ?>"
        type="hidden"
        value="<?= $v[$attribute['field']] ?>" />

<?php elseif ($attribute['type'] === "phone"): ?>
    <input
        name="<?= esc($attribute['field']) ?>"
        type="text"
        value="<?= $v[$attribute['field']] ?>"
        class="text-sm form-control"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        data-parsley-phoneid
        data-parsley-trigger="change"
        <?= $attribute['autocomplete'] ?>
        <?= $attribute['required'] ?>
        <?= $attribute['readonly'] ?>
        <?= $attribute['disabled'] ?>
        <?= $attribute['maxlength'] ?>
        <?= $attribute['minlength'] ?> />
<?php else: ?>
    <input
        name="<?= esc($attribute['field']) ?>"
        type="text"
        value="<?= $v[$attribute['field']] ?>"
        class="text-sm form-control"
        placeholder="<?= esc($attribute['placeholder']) ?>"
        <?= $attribute['autocomplete'] ?>
        <?= $attribute['required'] ?>
        <?= $attribute['readonly'] ?>
        <?= $attribute['disabled'] ?>
        <?= $attribute['maxlength'] ?>
        <?= $attribute['minlength'] ?> />
<?php endif; ?>