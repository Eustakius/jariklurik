<?php
/**
 * Universal Filter Banner (Stable High Contrast Version)
 * 
 * @param string $key_base      The primary filter key.
 * @param string $api_url       The API endpoint for primary details.
 * @param string $title_label   Label for the primary title.
 * @param array  $mappings      Map API fields to display areas.
 * @param string $token         Auth token.
 * @param array  $filter_config (Optional) Configuration of all filters to watch.
 */

$key_base = $key_base ?? '';
$api_url = $api_url ?? '';
$title_label = $title_label ?? 'Filtered View';
$token = $token ?? '';
$filter_config = $filter_config ?? [];

// Default mappings
$mappings = $mappings ?? [
    'title'    => 'name',
    'subtitle' => 'company_name', 
    'tertiary' => 'country_name'
];

$uniqId = 'banner-' . md5($key_base . uniqid());
?>

<div id="<?= $uniqId ?>" 
     class="hidden w-full space-y-4 animate-fade-in-up font-sans z-10 relative"
     data-behavior="universal-filter-banner"
     data-key-base="<?= esc($key_base) ?>"
     data-api-url="<?= esc(base_url($api_url)) ?>"
     data-mappings='<?= json_encode($mappings) ?>'
     data-filter-config='<?= json_encode($filter_config) ?>'
     data-title-label="<?= esc($title_label) ?>"
     data-token="<?= esc($token) ?>">
     

    <!-- Container for Stacked Filter Cards -->
    <div id="<?= $uniqId ?>-container" class="space-y-3">
        <!-- Cards will be injected here via JS -->
    </div>

    <!-- Global Reset All (Visible if any filter active) -->
    <div id="<?= $uniqId ?>-reset" class="hidden flex justify-end pt-2">
         <button type="button" data-action="clear-all" class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400 hover:text-primary-700 hover:underline transition-all">
            <iconify-icon icon="mingcute:refresh-2-line"></iconify-icon>
            Reset All Filters
         </button>
    </div>
</div>

<!-- Template for Filter Card (Hidden) -->
<!-- 
    STYLE: Same Fluent Card Design
    - Usage: Cloned for each active filter.
    - Data Attributes: used to fill content.
-->
<template id="<?= $uniqId ?>-template">
    <div class="relative overflow-hidden rounded-lg border transition-all duration-300
        bg-gray-50/50 border-gray-200
        dark:bg-gray-800/50 dark:border-gray-700 group hover:shadow-sm">
        
        <!-- Accent Line -->
        <div class="absolute top-0 left-0 w-[3px] h-full bg-primary-500"></div>

        <div class="relative px-5 py-4 flex items-center gap-4">
             <!-- Icon Area -->
            <div class="hidden sm:flex flex-shrink-0">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg 
                    bg-white border border-gray-200 shadow-sm
                    dark:bg-gray-700 dark:border-gray-600">
                    <iconify-icon data-bind="icon-visual" icon="mingcute:filter-3-fill" class="text-lg text-primary-600 dark:text-primary-400"></iconify-icon>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span data-bind="label-badge" class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border
                        bg-white text-gray-600 border-gray-200
                        dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                        <!-- Filled by JS -->
                    </span>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Active</span>
                </div>

                <div class="flex flex-col justify-center">
                    <div class="flex flex-wrap items-baseline gap-x-2">
                        <!-- Title -->
                        <h3 data-bind="title" class="text-base md:text-lg font-bold truncate leading-tight text-gray-900 dark:text-white">
                            <!-- Filled by JS -->
                        </h3>
                        
                        <span data-bind="divider" class="hidden text-gray-300 dark:text-gray-600 text-sm">/</span>
                        
                        <!-- Subtitle -->
                        <span data-bind="subtitle" class="text-sm font-medium truncate text-gray-500 dark:text-gray-400">
                             <!-- Filled by JS -->
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tertiary Info & Actions -->
            <div class="flex items-center gap-3">
                 <div data-bind="tertiary-container" class="hidden md:flex items-center gap-2 text-xs font-medium text-gray-500 dark:text-gray-400">
                    <span data-bind="tertiary"></span>
                </div>

                <div class="h-8 w-[1px] bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

                <button type="button" data-bind="clear-btn" class="group relative p-1.5 rounded-md transition-colors
                    text-gray-400 hover:bg-gray-100 hover:text-red-600
                    dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-red-400" 
                    title="Clear Filter">
                    <iconify-icon icon="mingcute:close-line" class="text-lg"></iconify-icon>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
(function() {
    function resolvePath(obj, path) {
        return path?.split('.').reduce((prev, curr) => prev ? prev[curr] : null, obj);
    }
    
    function initUniversalBanner() {
        const $root = $('#<?= $uniqId ?>');
        const $container = $('#<?= $uniqId ?>-container');
        const $resetBtn = $('#<?= $uniqId ?>-reset');
        const templateHtml = $('#<?= $uniqId ?>-template').html();

        if (!$root.length) return;

        // Config
        const keyBase = $root.data('key-base');
        const apiUrl = $root.data('api-url');
        const mappings = $root.data('mappings');
        const token = $root.data('token');
        const filterConfig = $root.data('filter-config') || []; 
        const titleLabel = $root.data('title-label');
        
        const suffixes = ['new', 'process', 'approved', 'rejected'];
        
        // Use Global Lock to prevent cross-tab recursion
        window.universalFilterSyncLock = window.universalFilterSyncLock || false;

        function renderAllCards() {
            $container.empty();
            let hasActive = false;

            // 1. Identify Primary Filter
            const suffix = getActiveSuffix();
            let primaryVal = null;
            let activePrimaryId = null;

            if (suffix) {
                 const id = keyBase + suffix;
                 const val = $('#' + id).val() || new URLSearchParams(window.location.search).get(id);
                 if (val) {
                     primaryVal = val;
                     activePrimaryId = id;
                 }
            }
            if (!primaryVal) { // Fallback scan
                for (const s of suffixes) {
                     const id = keyBase + s;
                     const v = new URLSearchParams(window.location.search).get(id);
                     if (v) { primaryVal = v; activePrimaryId = id; break; }
                }
            }
            if (!primaryVal && $('#'+keyBase).length) {
                primaryVal = $('#'+keyBase).val();
                if(primaryVal) activePrimaryId = keyBase;
            }

            // 2. Render Primary
            if (primaryVal) {
                hasActive = true;
                const $card = $(templateHtml);
                
                // Static setups
                $card.find('[data-bind="label-badge"]').text(titleLabel);
                $card.find('[data-bind="icon-visual"]').attr('icon', 'mingcute:briefcase-fill'); // Default icon
                
                // Bind Clear Action
                $card.find('[data-bind="clear-btn"]').on('click', function() {
                    syncPrimary(null, getActiveSuffix());
                    updateAll();
                    triggerFilterClick();
                });

                $container.append($card);
                
                // Fetch Details via API (Async)
                $.ajax({
                    url: apiUrl + '/' + primaryVal,
                    method: 'GET',
                    headers: token ? { 'Authorization': 'Bearer ' + token } : {}, 
                    success: function(response) {
                        if (response) {
                            $card.find('[data-bind="title"]').text(resolvePath(response, mappings.title) || '-');
                            
                            const sub = resolvePath(response, mappings.subtitle);
                            if (sub) {
                                $card.find('[data-bind="divider"]').removeClass('hidden').addClass('sm:inline-block');
                                $card.find('[data-bind="subtitle"]').text(sub);
                            }

                            let tertiaryText = resolvePath(response, mappings.tertiary);
                            if (mappings.tertiary === 'quota_info') { 
                                tertiaryText = (response.quota_used || 0) + ' / ' + (response.quota || 0);
                            }
                            if (tertiaryText) {
                                $card.find('[data-bind="tertiary-container"]').removeClass('hidden');
                                $card.find('[data-bind="tertiary"]').text(tertiaryText);
                            }
                        }
                    }
                });
            }

            // 3. Render Secondary (Standard) Filters
            const urlParams = new URLSearchParams(window.location.search);
            filterConfig.forEach(conf => {
                const filterKey = conf.id;
                if (filterKey === keyBase || filterKey === activePrimaryId) return; // Skip primary

                const $input = $('#' + filterKey);
                let val = null;
                if ($input.length) val = $input.val();
                else val = urlParams.get(filterKey);

                if (val && val !== '') {
                    hasActive = true;
                    let label = val;
                    if ($input.hasClass('select2-hidden-accessible')) {
                        const data = $input.select2('data');
                        if (data && data.length > 0) label = data[0].text;
                    } else if ($input.is('select')) {
                        const selectedText = $input.find('option:selected').text();
                        if(selectedText) label = selectedText;
                    }

                    const $card = $(templateHtml);
                    
                    // Setup Standard Card
                    $card.find('[data-bind="label-badge"]').text(conf.label);
                    $card.find('[data-bind="icon-visual"]').attr('icon', 'mingcute:filter-line'); // Generic icon
                    $card.find('[data-bind="title"]').text(label);
                    // Standard filters usually don't have subtitles/tertiary, keep them clean
                    
                    // Bind Clear Action
                    $card.find('[data-bind="clear-btn"]').on('click', function() {
                         $('#' + filterKey).val(null).trigger('change');
                         triggerFilterClick();
                    });

                    $container.append($card);
                }
            });

            // 4. Visibility Control
            if (hasActive) {
                $root.removeClass('hidden');
                $resetBtn.removeClass('hidden');
                $container.stop(true, true).hide().slideDown(300);
            } else {
                 $container.slideUp(200, function() {
                     $root.addClass('hidden');
                     $resetBtn.addClass('hidden');
                 });
            }
        }
        
        // Alias for event handlers thinking they call updateAll
        function updateAll() {
            renderAllCards();
        }

        function getActiveSuffix() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('key') || 'new'; 
        }

        // --- Event Binding ---
        suffixes.forEach(suffix => {
            $(document).on('change', '#' + keyBase + suffix, function(e, isInit) {
                if(window.universalFilterSyncLock) return;
                if(isInit === true) return; // Silent Init: Ignore setup changes
                
                const val = $(this).val();
                syncPrimary(val, suffix);
                renderAllCards();
                triggerFilterClick(); // Auto-refresh for primary
            });
        });

        // Use debounce for secondary filters to prevent flickering & fetch spam
        let debounceTimer;
        filterConfig.forEach(conf => {
            $(document).on('change', '#' + conf.id, function(e, isInit) {
                 if(isInit === true) return;
                 
                 clearTimeout(debounceTimer);
                 // Debounce both UI update and Table refresh
                 debounceTimer = setTimeout(function() {
                     renderAllCards();
                     triggerFilterClick();
                 }, 300); 
            });
        });
        
        $(document).on('click', '[data-action="clear-all"]', function() {
             syncPrimary(null, getActiveSuffix());
             filterConfig.forEach(conf => {
                 if (conf.id !== keyBase) {
                     $('#' + conf.id).val(null).trigger('change');
                 }
             });
             triggerFilterClick();
        });

        // Helper: Sync Primary across tabs
        function syncPrimary(val, refSuffix) {
            window.universalFilterSyncLock = true;
            try {
                // Also sync basic ID if exists
                if ($('#'+keyBase).length) $('#'+keyBase).val(val).trigger('change');
                
                const url = new URL(window.location);
                suffixes.forEach(s => {
                    const id = '#' + keyBase + s;
                    const param = keyBase + s;
                    if ($(id).length && $(id).val() != val) {
                        $(id).val(val).trigger('change');
                    }
                    if (val) url.searchParams.set(param, val);
                    else url.searchParams.delete(param);
                });
                window.history.replaceState({}, '', url);
            } finally {
                window.universalFilterSyncLock = false;
            }
        }

        // Helper: Click "Filter" button to refresh table
        function triggerFilterClick() {
             // Strategy: Find the filter button inside the CURRENTLY ACTIVE tab panel.
             // This avoids ID guessing (e.g. btnFilterjobvacancynew vs btnFilternew).
             
             // 1. Find active tab panel (standard selectors used in this app)
             // Usually it's a div under #card-title-tab-content that is NOT hidden.
             const $activePanel = $('#card-title-tab-content > div[role="tabpanel"]').not('.hidden');
             
             if ($activePanel.length) {
                 // 2. Find the primary Filter button inside this panel
                 const $btn = $activePanel.find('[id^="btnFilter"]');
                 if ($btn.length) {
                     // console.log("Triggering auto-refresh on:", $btn.attr('id'));
                     $btn.click();
                     return;
                 }
             }

             // Fallback: If no tabs or structure differs, try the simple ID guess
             const suffix = getActiveSuffix();
             let btn = $('#btnFilter' + suffix);
             if (btn.length) { btn.click(); return; }
             
             // Super Fallback: Any visible filter button?
             // (Note: Filter buttons might be hidden inside collapsed menus, so 'visible' check is tricky. 
             // But usually if no tabs, there's only one table/filter set).
             btn = $('[id^="btnFilter"]').first();
             if (btn.length) btn.click();
        }

        renderAllCards();
    }

    $(document).ready(initUniversalBanner);
})();
</script>
