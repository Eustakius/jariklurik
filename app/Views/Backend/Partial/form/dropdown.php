<?php
$attribute['placeholder']  = $attribute['placeholder']  ?? $attribute['label'];
$attribute['value']  = old($attribute['field']) ?? $attribute['value']  ?? ((isset($data)) ? $data->{$attribute['field']} : null);
$attribute['required']     = isset($attribute['required']) && $attribute['required']  ? 'required' : '';
$attribute['disabled']  = (isset($attribute['disabled']) && $attribute['disabled']) ||  (isset($param)) ? (strtolower($param['action']) == "detail" ? 'disabled' : '') : '';
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
        class="text-sm form-select w-full"></select>
    <div id="<?= esc($attribute['field']) ?>-error" class="text-red-500 text-base mt-1"></div>
</div>

<script>
    $(document).ready(function() {
        const $select = $('#<?= esc($attribute['field']) ?>');
        
        // Function to format status badges with colors using inline styles
        function formatStatusBadge(item) {
            if (!item.id) {
                return item.text;
            }
            
            const text = item.text || '';
            
            // Check for Active status
            if (text.includes('[✓ Active]')) {
                const parts = text.split('[✓ Active]');
                const mainText = parts[0].trim();
                const $result = $('<span></span>');
                
                // Main text with white color for visibility
                $result.append($('<span></span>').text(mainText + ' ').css({
                    'color': '#ffffff'
                }));
                
                // Active badge with inline styles
                $result.append($('<span></span>').text('✓ Active').css({
                    'margin-left': '4px',
                    'padding': '3px 10px',
                    'background-color': '#10b981',
                    'color': '#ffffff',
                    'font-size': '11px',
                    'font-weight': '700',
                    'border-radius': '6px',
                    'display': 'inline-block',
                    'vertical-align': 'middle'
                }));
                
                return $result;
            }
            // Check for Inactive status
            else if (text.includes('[✕ Inactive]')) {
                const parts = text.split('[✕ Inactive]');
                const mainText = parts[0].trim();
                const $result = $('<span></span>');
                
                // Main text with lighter color for inactive items
                $result.append($('<span></span>').text(mainText + ' ').css({
                    'color': '#d1d5db'
                }));
                
                // Inactive badge with inline styles
                $result.append($('<span></span>').text('✕ Inactive').css({
                    'margin-left': '4px',
                    'padding': '3px 10px',
                    'background-color': '#ef4444',
                    'color': '#ffffff',
                    'font-size': '11px',
                    'font-weight': '700',
                    'border-radius': '6px',
                    'display': 'inline-block',
                    'vertical-align': 'middle'
                }));
                
                return $result;
            }
            
            // Default: return text with white color
            return $('<span></span>').text(text).css({
                'color': '#ffffff'
            });
        }
        
        $select.select2({
            placeholder: 'Select a <?= esc($attribute['placeholder']) ?>',
            allowClear: true,
            width: '100%',
            templateResult: formatStatusBadge,
            templateSelection: formatStatusBadge,
            ajax: {
                url: '<?= site_url($attribute['api']) ?>',
                headers: {
                    'Authorization': 'Bearer <?= esc($token) ?>'
                },
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term, // text search
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        });

        // Set default value jika ada
        <?php if ($attribute['value']): ?>
            $.ajax({
                url: '<?= site_url($attribute['api']) ?>',
                headers: {
                    'Authorization': 'Bearer <?= esc($token) ?>'
                },
                data: {
                    id: '<?= esc($attribute['value']) ?>'
                }, // pakai ID, bukan term
                dataType: 'json'
            }).done(function(data) {
                if (data && data.results && data.results.length > 0) {
                    // pastikan property text sesuai
                    const option = new Option(data.results[0].text, data.results[0].id, true, true);
                    $select.append(option).trigger('change'); // set selected
                }
            });
        <?php endif; ?>
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