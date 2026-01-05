<style>
    /* Force checkmark visibility when input is checked */
    input.peer:checked + div svg {
        opacity: 1 !important;
        transform: scale(1) !important;
    }
</style>
<?php if (session()->has('key') && session('key') == $props['key'] || !session()->has('key')): ?>
    <?php if (session()->has('message-backend')): ?>
        <div class="mb-4 alert alert-success bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-white border-success-600 border-start-width-4-px border-l-[3px] dark:border-neutral-600 px-6 py-[13px] mb-0 text-sm rounded flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2 text-success-600 dark:text-white">
                <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
                <?= esc(session('message-backend')) ?>
            </div>
            <button class="remove-button text-success-600 text-2xl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
        </div>
    <?php endif; ?>
    <?php if (session()->has('error-backend')): ?>
        <div class="mb-4 alert alert-danger bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border-danger-600 border-start-width-4-px border-l-[3px] dark:border-neutral-600 px-6 py-[13px] mb-0 text-sm rounded flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
                <?= esc(session('error-backend')) ?>
            </div>
            <button class="remove-button text-danger-600 text-2xl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php
// Inject Checkbox Column if Selectable
if (isset($props['selectable']) && $props['selectable']) {
    // Check if we haven't already added it (in case of re-renders or shared props weirdness)
    // Actually props are local to this view render.
    array_unshift($props['columns'], [
        'title' => '<label class="inline-flex items-center cursor-pointer relative">
                        <input type="checkbox" id="selectAll' . $props['key'] . '" class="peer sr-only">
                        <div class="w-5 h-5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-500 rounded transition-all duration-200 peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:[&>svg]:opacity-100 peer-checked:[&>svg]:scale-100 flex items-center justify-center shadow-sm hover:shadow-md">
                            <svg class="w-3.5 h-3.5 text-white opacity-0 scale-50 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </label>',
        'data' => null,
        'defaultContent' => '',
        'orderable' => false,
        'searchable' => false,
        'width' => '50px'
    ]);
}
?>
<div class="notification fixed bottom-4 left-1/2 transform -translate-x-1/2 w-full max-w-md z-50"></div>
<div id="confirm-modal<?= $props['key'] ?>" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <button type="button" class="close-modal absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="confirm-modal<?= $props['key'] ?>">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400 warning-text<?= $props['key'] ?>"></h3>
                <div class="flex flex-row justify-center items-center">
                    <form id="formConfirm<?= $props['key'] ?>" action="" method="post">
                        <?= csrf_field() ?>
                        <input id="method<?= $props['key'] ?>" type="hidden" name="_method" value="DELETE">
                        <input id="key<?= $props['key'] ?>" type="hidden" name="key" value="">
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, I'm sure
                        </button>
                    </form>
                    <button data-modal-hide="confirm-modal<?= $props['key'] ?>" type="button" class="close-modal py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
// ... [Using a large match block because multiple occurrences need replacement or context matching is safer for 'body' replacement too]
// Actually, I should split this. The prompt allows multiple edits? No. "Use this tool ONLY when you are making... MULTIPLE NON_CONTIGUOUS...".
// Wait, replace_file_content is for SINGLE CONTIGUOUS.
// multi_replace_file_content is for multiple.
// I will use `multi_replace_file_content` to update both Header and Body in one go.


$buttons = [];
// ... (rest of file content logic is managed by line replacement, I don't need to repeat it all here if I target correctly)
// Wait, replace_file_content replaces a block. I need to be careful not to overwrite huge chunks unnecessarily.
// Let's split this into two calls or be precise.
// Call 1: Update the width in PHP block.
// Call 2: Update the columnDefs in JS block.
// Actually, I can do it in one replace if the distance is small, but lines 26 to 500+ is too big.
// I will use multi_replace.


$buttons = [];
$buttons[] = [
    'text' => '<div class="px-3.5 py-2 text-success-600 hover:text-white flex items-center justify-center gap-2">
                    <iconify-icon icon="mdi:arrow-expand-all" class="text-sm"></iconify-icon> Expand All
               </div>',
    'className' => 'btn-collapse' . $props['key'] . ' hidden btn bg-success-100 text-success-600 hover:bg-success-700 hover:text-white rounded-lg text-sm p-0',
    'action' => 'function(e, dt, node, config) {
                var $icon = $(node).find("iconify-icon");
                var $text = $(node).find("div");
                if($icon.attr("icon") === "mdi:arrow-expand-all") {
                    dt.rows().every(function() {
                        var tr = $(this.node());
                        var control = tr.find("td.dtr-control");
                        if (!tr.hasClass("dtr-expanded")) {
                            control.trigger("click");
                        }                     
                    });
                    $icon.attr("icon", "mdi:arrow-collapse-all");
                    $text.html(`<iconify-icon icon="mdi:arrow-collapse-all" class="text-sm"></iconify-icon> Collapse All`);                         
                }
                else{
                    dt.rows().every(function() {
                        this.child.hide();
                        $(this.node()).removeClass("dt-hasChild dtr-expanded");                            
                    });
                    $icon.attr("icon", "mdi:arrow-expand-all");
                    $text.html(`<iconify-icon icon="mdi:arrow-expand-all" class="text-sm"></iconify-icon> Expand All`);  
                }
            }'
];
$buttons[] = [
    'text' => '<div class="px-3.5 py-2 text-success-600 hover:text-white flex items-center justify-center gap-2"><iconify-icon icon="teenyicons:refresh-solid" class="text-sm"></iconify-icon> Refresh</div>',
    'className' => 'btn bg-success-100 text-success-600 hover:bg-success-700 hover:text-white rounded-lg text-sm p-0',
    'action' => 'function(e, dt, node, config) { 
                $("#tbl' . $props['key'] . ' tbody").empty();
                dt.ajax.reload(null, true);
            }'
];

// DATA FILTER BUTTON
if (isset($props['filters']) && !empty($props['filters'])) {
    $buttons[] = [
        'text' => '<div class="px-3.5 py-2 text-primary-600 hover:text-white flex items-center justify-center gap-2"><iconify-icon icon="mi:filter" class="text-sm"></iconify-icon> Filter</div>',
        'className' => 'btn bg-primary-100 text-primary-600 hover:bg-primary-700 hover:text-white rounded-lg text-sm p-0',
        'action' => 'function(e, dt, node, config) { 
                    $("#filter-container' . $props['key'] . '").slideToggle();
                }'
    ];
}
$buttons[] = [
    'extend' => 'collection',
    'text' => '<div class="px-3.5 py-2 text-info-600 flex items-center justify-center gap-2"><iconify-icon icon="material-symbols-light:export-notes-outline-rounded" class="text-lg"></iconify-icon> Export</div>',
    'className' => 'inline-flex items-center btn bg-info-100 text-info-600 hover:!bg-info-700 hover:!text-white rounded-lg text-sm p-0',
    'buttons' => [
        [
            'extend' => 'copy',
            'title'  => $title,     // judul di header export
            'filename' => 'laporan_data'    // nama file download
        ],
        [
            'extend' => 'excel',
            'title'  => $title,
            'filename' => $title
        ],
        [
            'extend' => 'csv',
            'title'  => $title,
            'filename' => 'laporan_data'
        ],
        [
            'extend' => 'pdf',
            'title'  => $title,
            'filename' => 'laporan_data'
        ],
        [
            'extend' => 'print',
            'title'  => $title
        ],
    ]
];


// Mass Action Buttons (Manual Configuration)
if (isset($mas_actions) && !empty($mas_actions)) {
    foreach ($mas_actions as $key => $action) {
         $buttons[] = [
            'text' => '<div class="px-3.5 py-2 flex items-center justify-center gap-2 text-white"><iconify-icon icon="' . ($action['icon'] ?? 'mingcute:check-line') . '" class="text-sm"></iconify-icon> ' . ($action['label'] ?? 'Action') . '</div>',
            'className' => 'btn-mass-action' . $props['key'] . ' btn bg-' . ($action['type'] ?? 'success') . '-100 [&_div]:text-' . ($action['type'] ?? 'success') . '-600 hover:bg-' . ($action['type'] ?? 'success') . '-700 hover:[&_div]:text-white rounded-lg text-sm p-0',
            'attr' =>  [
                'data-url' => $action['url'],
                'data-action-name' => $key
            ]
        ];
    }
} else if (isset($props['selectable']) && $props['selectable']) {
    // Mass Action Buttons (Auto-Generated based on Permissions)
    $approvePerm = array_filter($props['permission'], fn($p) => str_ends_with($p['permission'], '.approve'));
    if (!empty($approvePerm)) {
         $approveRouteDef = reset($approvePerm)['route']; 
         $massApproveRoute = preg_replace('/\(:(segment|num|any)\)\/approve/', 'mass-approve', $approveRouteDef);
         if ($massApproveRoute === $approveRouteDef) {
             $massApproveRoute = rtrim($approveRouteDef, '/') . '/mass-approve';
         }
         $massApproveUrl = base_url($massApproveRoute);
         
         $buttons[] = [
            'text' => '<div class="px-3.5 py-2 text-success-600 hover:text-white flex items-center justify-center gap-2"><iconify-icon icon="mingcute:check-line" class="text-sm"></iconify-icon> Mass Approve</div>',
            'className' => 'btn-mass-action' . $props['key'] . ' btn bg-success-100 [&_span]:text-success-600 hover:bg-success-700 hover:[&_span]:text-white rounded-lg text-sm p-0',
            'attr' =>  [
                'data-url' => $massApproveUrl,
                'data-action-name' => 'approve'
            ]
        ];
    }

    // Mass Action Buttons (Process)
    $processPerm = array_filter($props['permission'], fn($p) => str_ends_with($p['permission'], '.process'));
    if (!empty($processPerm)) {
         $processRouteDef = reset($processPerm)['route']; 
         $massProcessRoute = preg_replace('/\(:(segment|num|any)\)\/process/', 'mass-process', $processRouteDef);
         if ($massProcessRoute === $processRouteDef) {
             $massProcessRoute = rtrim($processRouteDef, '/') . '/mass-process';
         }
         $massProcessUrl = base_url($massProcessRoute);
         
         $buttons[] = [
            'text' => '<div class="px-3.5 py-2 text-success-600 hover:text-white flex items-center justify-center gap-2"><iconify-icon icon="mingcute:check-line" class="text-sm"></iconify-icon> Mass Process</div>',
            'className' => 'btn-mass-action' . $props['key'] . ' btn bg-success-100 [&_span]:text-success-600 hover:bg-success-700 hover:[&_span]:text-white rounded-lg text-sm p-0',
            'attr' =>  [
                'data-url' => $massProcessUrl,
                'data-action-name' => 'process'
            ]
        ];
    }

    // Mass Action Buttons (Revert)
    $revertPerm = array_filter($props['permission'], fn($p) => str_ends_with($p['permission'], '.revert'));
    if (!empty($revertPerm)) {
         $revertRouteDef = reset($revertPerm)['route']; 
         // Helper to derive mass route or fallback
         $massRevertRoute = preg_replace('/\(:(segment|num|any)\)\/revert/', 'mass-revert', $revertRouteDef);
         if ($massRevertRoute === $revertRouteDef) {
             $massRevertRoute = 'back-end/applicant/mass-revert';
         }
         $massRevertUrl = base_url($massRevertRoute);
         
         $buttons[] = [
            'text' => '<div class="px-3.5 py-2 text-warning-600 hover:text-white flex items-center justify-center gap-2"><iconify-icon icon="mingcute:back-line" class="text-sm"></iconify-icon> Mass Revert</div>',
            'className' => 'btn-mass-action' . $props['key'] . ' btn bg-warning-100 [&_span]:text-warning-600 hover:bg-warning-700 hover:[&_span]:text-white rounded-lg text-sm p-0',
            'attr' =>  [
                'data-url' => $massRevertUrl,
                'data-action-name' => 'revert'
            ]
        ];
    }

    // Mass Action Buttons (Delete)
    $deletePerm = array_filter($props['permission'], fn($p) => str_ends_with($p['permission'], '.delete'));
    if (!empty($deletePerm)) {
         $deleteRouteDef = reset($deletePerm)['route']; 
         // Helper to derive mass route or fallback
         // Usually route is /delete or /(:num) for delete. 
         // Let's assume standard resource route: /training-type/(:num) -> DELETE
         // We want /training-type/mass-delete
         
         // If route is like 'back-end/training/training-type/(:num)'
         $massDeleteRoute = preg_replace('/\(:(segment|num|any)\)/', 'mass-delete', $deleteRouteDef);
         
         // If replace didn't happen (no param in definition? unlikely for delete), append
         if ($massDeleteRoute === $deleteRouteDef) {
             $massDeleteRoute = rtrim($deleteRouteDef, '/') . '/mass-delete';
         }
         
         $massDeleteUrl = base_url($massDeleteRoute);
         
         $buttons[] = [
            'text' => '<div class="px-3.5 py-2 text-danger-600 hover:text-white flex items-center justify-center gap-2"><iconify-icon icon="mingcute:delete-2-line" class="text-sm"></iconify-icon> Mass Delete</div>',
            'className' => 'btn-mass-action' . $props['key'] . ' btn bg-danger-100 [&_span]:text-danger-600 hover:bg-danger-700 hover:[&_span]:text-white rounded-lg text-sm p-0',
            'attr' =>  [
                'data-url' => $massDeleteUrl,
                'data-action-name' => 'delete'
            ]
        ];
    }
}

$createPerm = array_filter($props['permission'], fn($p) => str_ends_with($p['permission'], '.create'));
if (!empty($createPerm)) {
    $route = base_url(reset($createPerm)['route'] . '/new');
    $buttons[] = [
        'text' => '<div class="px-3.5 py-2 text-warning-600 hover:text-white flex items-center justify-center gap-2"><iconify-icon icon="mage:plus" class="text-sm"></iconify-icon> Add</div>',
        'className' => 'btn bg-warning-100 [&_span]:text-warning-600 hover:bg-warning-700 hover:[&_span]:text-white rounded-lg text-sm p-0',
        'action' => 'function(e, dt, node, config) {
                    window.location.href = "' . esc($route) . '";
                }'
    ];
}


?>
<?php
$importPerm = array_filter($props['permission'], fn($p) => str_ends_with($p['permission'], '.import'));
if (!empty($importPerm)): ?>
    <div class="flex items-center justify-between pb-4">
        <div class="col-auto">
            <div class="flex flex-col md:flex-row gap-4">
                <form id="formUpload" action="<?= base_url(reset($importPerm)['route'] . '/import') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="flex flex-row gap-4">
                        <input type="file" name="excel_file" accept=".xls,.xlsx" required class="block w-full text-sm  text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <button type="submit" class="btn bg-neutral-100 text-neutral-600 hover:bg-neutral-700 hover:text-white rounded-lg px-3.5 py-2 text-sm">
                            <div class="flex items-center justify-center gap-2"><iconify-icon icon="oui:import" class="text-sm"></iconify-icon> Import</div>
                        </button>
                    </div>
                </form>
                <a href="<?= base_url(reset($importPerm)['route'] . '/template-import') ?>" class="btn bg-info-100 text-info-600 hover:bg-info-700 hover:text-white rounded-lg px-3.5 py-2 text-sm">
                    <div class="flex items-center justify-center gap-2"><iconify-icon icon="flowbite:file-import-outline" class="text-sm"></iconify-icon> Download Template Import</div>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>
<div id="datatable-process<?= $props['key'] ?>"
    class="hidden absolute inset-0 z-[9999] bg-white/70 flex flex-col items-center justify-center text-gray-700 font-semibold text-base backdrop-blur-sm">
    <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
        <span>Rendering data...</span>
    </div>
</div>
<div id="filter-container<?= $props['key'] ?>" style="display: none;" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-white p-4 rounded-lg shadow-sm border border-neutral-200 dark:bg-gray-800 dark:border-neutral-700">
    <?php foreach ($props['filters'] as $filter): ?>
        <div class="w-full">
            <?php if ($filter['input'] == "text"): ?>
                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                    'type' => 'text',
                    'field' => $filter['id'],
                    'label' => $filter['label'],
                ]]) ?>
            <?php elseif ($filter['input'] == "date"): ?>
                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                    'type' => 'date',
                    'field' => $filter['id'],
                    'label' => $filter['label'],
                ]]) ?>
            <?php elseif ($filter['input'] == "textgroup"): ?>
                <?= view('Backend/Partial/form/text-box-group', ['attribute' => [
                    'type' => 'text-select',
                    'field' => $filter['id'],
                    'label' => $filter['label'],
                    'group' => [
                        'field' => $filter['group']['id'],
                        'data' => $filter['group']['data'],
                        'clear' => true
                    ],
                    'required' => true,
                ]]) ?>
            <?php elseif ($filter['input'] == "select"): ?>
                <?php if (isset($filter['api'])): ?>
                    <?= view('Backend/Partial/form/dropdown', ['attribute' => [
                        'field' => $filter['id'],
                        'label' => $filter['label'],
                        'api' => $filter['api'],
                    ]]) ?>
                <?php else: ?>
                    <?= view('Backend/Partial/form/dropdown-static', ['attribute' => [
                        'field' => $filter['id'],
                        'label' => $filter['label'],
                        'data' => $filter['data'],
                    ]]) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <div class="flex flex-row gap-2 h-[42px]">
        <button id="btnFilter<?= $props['key'] ?>"
            class="dark:text-white bg-success-100 text-success-600 hover:bg-success-700 hover:text-white w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 h-full flex items-center justify-center">
            Filter
        </button>
        <button id="btnReset<?= $props['key'] ?>"
            class="text-center dark:text-white bg-warning-100 text-warning-600 hover:bg-warning-700 hover:text-white w-full focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 h-full flex items-center justify-center">
            Reset
        </button>
    </div>
</div>
<div class="customize-vue3-easy-data-table min-h-full max-w-full">
    <table class="datatable border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate" id="tbl<?= $props['key'] ?>">
    </table>
</div>

<!-- Mass Decision Modal -->
<div id="decision-modal<?= $props['key'] ?>" tabindex="-1" class="hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-md max-h-full top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
             <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Select Action
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="$('#decision-modal<?= $props['key'] ?>').addClass('hidden')">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 text-center">
                 <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">What do you want to do with <span id="decision-count<?= $props['key'] ?>"></span> selected items?</h3>
                <div class="flex justify-center gap-4">
                    <button id="btn-decision-approve<?= $props['key'] ?>" type="button" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        <iconify-icon icon="mingcute:check-line" class="mr-2"></iconify-icon> Approve
                    </button>
                    <button id="btn-decision-reject<?= $props['key'] ?>" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        <iconify-icon icon="mingcute:close-line" class="mr-2"></iconify-icon> Reject
                    </button> 
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    (function() {
        let isMobile = window.matchMedia("(max-width: 768px)").matches;
        if (isMobile) {
            $.fn.DataTable.ext.pager.numbers_length = 4;
        }
        var table = {};
        // Persistent Selection Set
        var selectedIds<?= $props['key'] ?> = new Set();

        let buttons = <?= json_encode($buttons, JSON_UNESCAPED_SLASHES) ?>;
        buttons.forEach(b => {
            b.action = eval("(" + b.action + ")");
        });

        function showAlert(message, type = 'success') {
            const colors = {
                success: 'bg-green-100 text-green-700 border-green-500',
                danger: 'bg-red-100 text-red-700 border-red-500',
            };

            const icon = {
                success: 'mdi:check-circle-outline',
                danger: 'mdi:alert-circle-outline'
            };
            var html = '<div class="mb-4 alert alert-danger bg-danger-100 dark:bg-danger-400 text-danger-600 dark:text-danger-400 border-danger-600 border-start-width-4-px border-l-[3px] dark:border-neutral-600 px-6 py-[13px] mb-0 text-sm rounded flex items-center justify-between" role="alert"><div class="flex items-center gap-2"><iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>' + message + '</div><button class="remove-button text-danger-600 text-2xl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button></div>'


            const container = $('.notification');
            const alert = $(html).hide().appendTo(container).fadeIn(200);

            // ✅ tombol close manual
            alert.find('.remove-button').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // biar klik nggak nembus ke iconify
                alert.fadeOut(200, function() {
                    $(this).remove();
                });
            });

            // ✅ auto close 5 detik
            setTimeout(() => {
                alert.fadeOut(200, function() {
                    $(this).remove();
                });
            }, 5000);
        }

        $(document).on('change', '.triger-update', function() {
            let checkbox = $(this);
            let id = checkbox.data('id');
            let isChecked = checkbox.is(':checked');
            let url = checkbox.data('url');

            $.ajax({
                url: url,
                method: 'PUT',
                data: {
                    id: id,
                    pinned: isChecked ? 1 : 0
                },
                headers: {
                    'Authorization': 'Bearer <?= esc($props['token']) ?>'
                },
                success: function(response) {
                    if (response.status != 'Success') {
                        checkbox.prop('checked', !isChecked);
                        showAlert(response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Gagal update:', error);
                    checkbox.prop('checked', !isChecked);
                    showAlert('Terjadi kesalahan pada server', 'danger');
                }
            });
        });
        $(document).ready(function() {

            $.extend(true, $.fn.dataTable.Buttons.defaults, {
                dom: {
                    button: {
                        className: '' // hapus class bawaan .dt-button
                    }
                }
            });

            let dataTableAjax = '<?= esc($props['api']) ?>';
            table.<?= $props['key'] ?> = $('#tbl<?= $props['key'] ?>').DataTable({
                deferRender: true,
                buttons: buttons,
                processing: true,
                serverSide: true,
                ajax: {
                    url: dataTableAjax,
                    type: 'GET',
                    headers: {
                        'Authorization': 'Bearer <?= esc($props['token']) ?>'
                    },
                    beforeSend: function() {
                    },
                    data: function(d) {
                         $('#filter-container<?= $props['key'] ?> :input').each(function() {
                            let key = $(this).attr('name') || $(this).attr('id');
                            if (key) {
                                d[key] = $(this).val();
                            }
                        });
                    },
                    dataSrc: function(json) {
                        return json.data; // penting! DataTables butuh ini
                    },
                    error: function(xhr, error, code) {
                        if (xhr.status === 401) {
                            window.location.href = '<?= site_url('back-end/logout') ?>';
                        }
                    }
                },
                scrollX: true,
                autoWidth: false,
                columns: <?= json_encode($props['columns']) ?>,
                lengthMenu: [
                    [10, 25, 50, 100, 200, 500, 1000],
                    [10, 25, 50, 100, 200, 500, 1000]
                ],
                columnDefs: [
                    {
                        className: "dt-nowrap",
                        targets: "_all"
                    }
                    <?php if (isset($props['selectable']) && $props['selectable']): ?>
                    ,{
                        className: 'text-center',
                        targets: 0, 
                        width: '50px',
                        visible: true,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            var isChecked = selectedIds<?= $props['key'] ?>.has(row.id.toString()) ? 'checked' : '';
                            return '<label class="inline-flex items-center cursor-pointer relative">' +
                                        '<input type="checkbox" class="row-checkbox peer sr-only" value="' + row.id + '" ' + isChecked + '>' +
                                        '<div class="w-5 h-5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-500 rounded transition-all duration-200 peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:[&>svg]:opacity-100 peer-checked:[&>svg]:scale-100 flex items-center justify-center shadow-sm hover:shadow-md">' +
                                            '<svg class="w-3.5 h-3.5 text-white opacity-0 scale-50 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">' +
                                                '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />' +
                                            '</svg>' +
                                        '</div>' +
                                   '</label>';
                        }
                    }
                    ,{
                        targets: 1,
                        orderable: false,
                        searchable: false,
                        searchBuilder: false,
                        visible: true,
                        width: "50px",
                        render: function(data, type, row, meta) {
                            return '<span class="ml-2">' + (meta.row + meta.settings._iDisplayStart + 1) + '</span>';
                        }
                    }
                    <?php else: ?>
                    ,{
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        searchBuilder: false,
                        visible: true,
                        width: "50px",
                        render: function(data, type, row, meta) {
                            return '<span class="ml-2">' + (meta.row + meta.settings._iDisplayStart + 1) + '</span>';
                        }
                    }
                    <?php endif; ?>
                    ,{
                        targets: -1,
                        orderable: false,
                        searchable: false,
                        searchBuilder: false,
                        searchDropdown: false,
                        render: function(data, type, row, meta) {
                            return `
                                <?php
                                $filtereds = array_filter(
                                    $props['permission'],
                                    fn($p) => !str_ends_with($p['permission'], '.create')
                                        && !str_ends_with($p['permission'], '.view')
                                );
                                foreach ($filtereds as $permission):
                                    $routeKey = $permission['route'];
                                    if (!str_starts_with($routeKey, 'back-end')) {
                                        $routeKey = 'back-end/' . ltrim($routeKey, '/');
                                    }
                                    $route = rtrim(base_url($routeKey), '/'); ?>
                                    <?php if (str_ends_with($permission['permission'], '.detail')): ?>
                                        <a href="<?= esc($route) ?>/${data.id}" 
                                        class="w-9 h-9 bg-cyan-50 hover:bg-cyan-100 text-cyan-600 dark:bg-cyan-900/20 dark:hover:bg-cyan-900/30 dark:text-cyan-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                                            <iconify-icon icon="solar:eye-broken" class="text-lg"></iconify-icon>
                                        </a>
                                    <?php elseif (str_ends_with($permission['permission'], '.update')): ?>
                                        <a href="<?= esc($route) ?>/${data.id}/edit" 
                                        class="w-9 h-9 bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:hover:bg-amber-900/30 dark:text-amber-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                                            <iconify-icon icon="solar:pen-new-square-broken" class="text-lg"></iconify-icon>
                                        </a>
                                    <?php elseif (str_ends_with($permission['permission'], '.delete')): ?>
                                        <button data-method="DELETE" data-key="<?= $props['key'] ?>" data-action="Are you sure you want to delete ?" data-url="<?= esc($route) ?>/${data.id}" 
                                        class="btn-open-modal<?= $props['key'] ?> w-9 h-9 bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 dark:text-rose-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                                            <iconify-icon icon="solar:trash-bin-trash-broken" class="text-lg"></iconify-icon>
                                        </button>
                                    <?php elseif (str_ends_with($permission['permission'], '.process')): ?>
                                        <button data-method="PUT" data-key="<?= $props['key'] ?>" data-action="Are you sure you want to process ?" data-url="<?= esc($route) ?>/${data.id}/process" 
                                        class="btn-open-modal<?= $props['key'] ?> w-9 h-9 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 dark:text-emerald-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                                            <iconify-icon icon="solar:check-circle-broken" class="text-lg"></iconify-icon>
                                        </button>
                                    <?php elseif (str_ends_with($permission['permission'], '.approve')): ?>
                                        <button data-method="PUT" data-key="<?= $props['key'] ?>" data-action="Are you sure you want to approve ?" data-url="<?= esc($route) ?>/${data.id}/approve" 
                                        class="btn-open-modal<?= $props['key'] ?> w-9 h-9 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 dark:text-emerald-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                                            <iconify-icon icon="solar:check-circle-broken" class="text-lg"></iconify-icon>
                                        </button>
                                    <?php elseif (str_ends_with($permission['permission'], '.reject')): ?>
                                        <button data-method="PUT" data-key="<?= $props['key'] ?>" data-action="Are you sure you want to reject ?" data-url="<?= esc($route) ?>/${data.id}/reject" 
                                        class="btn-open-modal<?= $props['key'] ?> w-9 h-9 bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 dark:text-rose-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                                            <iconify-icon icon="solar:close-circle-broken" class="text-lg"></iconify-icon>
                                        </button>
                                    <?php elseif (str_ends_with($permission['permission'], '.revert')): ?>
                                        <button data-method="PUT" data-key="<?= $props['key'] ?>" data-action="Are you sure you want to revert ?" data-url="<?= esc($route) ?>/${data.id}/revert" 
                                        class="btn-open-modal<?= $props['key'] ?> w-9 h-9 bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:hover:bg-amber-900/30 dark:text-amber-400 rounded-lg inline-flex items-center justify-center transition-all duration-200">
                                            <iconify-icon icon="solar:restart-broken" class="text-lg"></iconify-icon>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            `;
                        }
                    }
                ],
                layout: {
                    topStart: 'buttons',
                    topEnd: {
                        search: true,
                        buttons: []
                    },
                    bottomEnd: ['pageLength', 'paging'],
                    bottomStart: 'info',
                },
            });

            // Logic Checkbox Select All (Event Handlers Only)
            <?php if (isset($props['selectable']) && $props['selectable']): ?>
                // Note: Header checkbox is injected via props['columns'] in PHP
                
                // Select All Click Event
                $(document).on('click', '#selectAll<?= $props['key'] ?>', function() {
                    var rows = table.<?= $props['key'] ?>.rows({ 'search': 'applied' }).nodes();
                    $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
                    
                    // Update Set
                    $('input[type="checkbox"].row-checkbox', rows).each(function() {
                        var id = $(this).val().toString();
                        if(this.checked) selectedIds<?= $props['key'] ?>.add(id);
                        else selectedIds<?= $props['key'] ?>.delete(id);
                    });
                });


                // Individu Checkbox Click Event (untuk update status Select All & Persistent Set)
                $('#tbl<?= $props['key'] ?> tbody').on('change', 'input[type="checkbox"].row-checkbox', function(){
                    var id = $(this).val().toString();
                    if(this.checked) {
                        selectedIds<?= $props['key'] ?>.add(id);
                        console.log('Added ID:', id, 'Set:', Array.from(selectedIds<?= $props['key'] ?>));
                    } else {
                        selectedIds<?= $props['key'] ?>.delete(id);
                        console.log('Removed ID:', id, 'Set:', Array.from(selectedIds<?= $props['key'] ?>));
                    }

                    if(!this.checked){
                       var el = $('#selectAll<?= $props['key'] ?>').get(0);
                       if(el && el.checked && ('indeterminate' in el)){
                          el.indeterminate = true;
                       }
                    }
                });

                // Prevent Row Expansion when clicking checkbox
                $('#tbl<?= $props['key'] ?> tbody').on('click', 'input[type="checkbox"].row-checkbox', function(e){
                    e.stopPropagation();
                });
            <?php endif; ?>


            table.<?= $props['key'] ?>.on('responsive-resize', function(e, datatable, columns) {
                let anyHidden = columns.some(col => col === false);

                if (!anyHidden) {
                    $('.btn-collapse<?= $props['key'] ?>').hide();
                    $('#tbl<?= $props['key'] ?> td.dtr-control, #tbl<?= $props['key'] ?> th.dtr-control').removeClass('dtr-control');
                } else {
                    $('.btn-collapse<?= $props['key'] ?>').show();
                    <?php if (isset($props['selectable']) && $props['selectable']): ?>
                        $('#tbl<?= $props['key'] ?> tr:not(.child) td:nth-child(2), #tbl<?= $props['key'] ?> tr:not(.child) th:nth-child(2)').addClass('dtr-control');
                    <?php else: ?>
                        $('#tbl<?= $props['key'] ?> tr:not(.child) td:first-child, #tbl<?= $props['key'] ?> tr:not(.child) th:first-child').addClass('dtr-control');
                    <?php endif; ?>
                }
            });

            $('#tbl<?= $props['key'] ?>').on('xhr.dt', function(e, settings, json, xhr) {
                table.<?= $props['key'] ?>.columns().visible(true);
            });
            $(document).on('click', '#btnFilter<?= $props['key'] ?>', function(e) {
                e.preventDefault();
                $("#tbl<?= $props['key'] ?> tbody").empty();
                table.<?= $props['key'] ?>.ajax.reload();
                $("#filter-container<?= $props['key'] ?>").slideUp();
            });
            // Connect Enter key to filter button
            $(document).on('keypress', '#filter-container<?= $props['key'] ?> input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btnFilter<?= $props['key'] ?>').click();
                }
            });

            $(document).on('click', '#btnReset<?= $props['key'] ?>', function(e) {
                e.preventDefault();
                let filters = <?= json_encode($props['filters'], JSON_UNESCAPED_SLASHES) ?>;
                filters.forEach(function(filter) {
                    var selector = filter.selector || ('[name="' + filter.id + '"]');
                    var $el = $(selector);
                    $el.val('');
                    if ($el.hasClass('select2-hidden-accessible')) {
                        $el.val(null).trigger('change');
                    }
                });
                table.<?= $props['key'] ?>.ajax.reload();
                $("#filter-container<?= $props['key'] ?>").slideUp();
            });
            $('#tbl<?= $props['key'] ?>').on('length.dt', function(e, settings, len) {
                $("#tbl<?= $props['key'] ?> tbody").empty();
            });
            $('#tbl<?= $props['key'] ?>').on('page.dt', function(e, settings, len) {
                $("#tbl<?= $props['key'] ?> tbody").empty();
            });
            $('#tbl<?= $props['key'] ?>').on('preDraw.dt', function() {
                // Paksa spinner bawaan tampil
                showProcess(true);
            });
            $('#tbl<?= $props['key'] ?>').on('draw.dt', function() {
                ensureFullRender(() => {
                    showProcess(false);
                });
                $('#tbl<?= $props['key'] ?> td.dtr-hidden input, #tbljobvacancy td.dtr-hidden [id]').remove();
                
                // Reinitialize Flowbite components after table redraw
                if (typeof Flowbite !== 'undefined' && Flowbite.initModals) {
                    Flowbite.initModals();
                }
            });
            // MODAL INITIALIZATION & MASS ACTIONS
            const $modalElement = document.getElementById('confirm-modal<?= $props['key'] ?>');
            let modal = null;
            
            function getModal() {
                 if (modal) return modal;
                 if ($modalElement) {
                     try {
                          modal = new Modal($modalElement);
                     } catch(e) { }
                 }
                 return modal;
            }

            $(document).on('click', '.btn-open-modal<?= $props['key'] ?>', function() {
                modal = getModal();
                if (!modal) return;
                this.blur();
                const action = $(this).data('action');
                const url = $(this).data('url');
                const method = $(this).data('method');
                const key = $(this).data('key');
                $('.warning-text<?= $props['key'] ?>').text(action);
                $('#method<?= $props['key'] ?>').val(method);
                $('#key<?= $props['key'] ?>').val(key);
                $('#formConfirm<?= $props['key'] ?>').attr('action', url);
                
                $('#formConfirm<?= $props['key'] ?>').off('submit').on('submit', function(e) {
                     e.preventDefault();
                     const methodField = $('#method<?= $props['key'] ?>').val();
                     $.ajax({
                        url: url,
                        method: methodField || 'POST',
                        data: $(this).serialize(),
                        headers: {
                            'Authorization': 'Bearer <?= esc($props['token']) ?>',
                             'X-Requested-With': 'XMLHttpRequest'
                        },
                         success: function(response) {
                            if(modal) modal.hide();
                            if (response.status == 'Success' || response.status == 200) {
                                showAlert(response.message || 'Action successful', 'success');
                                table.<?= $props['key'] ?>.ajax.reload(null, false);
                            } else {
                                showAlert(response.message || 'Error occurred', 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            if(modal) modal.hide();
                             showAlert('Server error occurred', 'danger');
                        }
                     });
                });
                modal.show();
            });

             $(document).on('click', '.btn-mass-action<?= $props['key'] ?>', function() {
                var actionUrl = $(this).data('url');
                var actionName = $(this).data('action-name');
                
                var idsList = Array.from(selectedIds<?= $props['key'] ?>);
                if (idsList.length === 0) {
                    showAlert('Please select at least one item.', 'danger');
                    return;
                }

                if (actionName === 'process') {
                    let btnApproveLabel = 'Process';
                    let btnApproveAction = 'mass-process';
                    let key = '<?= $props['key'] ?>';
                    if (key === 'process') {
                        btnApproveLabel = 'Approve';
                        btnApproveAction = 'mass-approve';
                    }

                    $('#btn-decision-approve<?= $props['key'] ?>').html('<iconify-icon icon="mingcute:check-line" class="mr-2"></iconify-icon> ' + btnApproveLabel);
                     $('#decision-count<?= $props['key'] ?>').text(idsList.length);
                     const $decisionModalEl = document.getElementById('decision-modal<?= $props['key'] ?>');
                     if ($decisionModalEl) {
                         const decisionModal = new Modal($decisionModalEl);
                         decisionModal.show();
                         
                         $('#btn-decision-approve<?= $props['key'] ?>').off('click').on('click', function() {
                             decisionModal.hide();
                             let url = actionUrl;
                             if (actionUrl.includes('mass-process')) {
                                 url = actionUrl.replace('mass-process', btnApproveAction);
                             } else {
                                 url = '<?= base_url("back-end/applicant") ?>/' + btnApproveAction;
                             }
                             performMassAction(idsList, url, btnApproveLabel); 
                         });
                         
                         $('#btn-decision-reject<?= $props['key'] ?>').off('click').on('click', function() {
                             decisionModal.hide();
                             let url = actionUrl.replace('mass-process', 'mass-reject');
                             if(actionUrl === url) { 
                                 url = '<?= base_url("back-end/applicant/mass-reject") ?>';
                             }
                             performMassAction(idsList, url, 'Reject'); 
                         });
                     } else {
                        console.error('Decision modal element not found for key: <?= $props['key'] ?>');
                     }
                } else {
                    performMassAction(idsList, actionUrl, actionName);
                }
            });

            function performMassAction(ids, url, action) {
                modal = getModal(); 
                if(!modal) {
                     console.error("Modal initialization failed in performMassAction");
                     return;
                }
                
                $('.warning-text<?= $props['key'] ?>').text("Are you sure you want to " + action + " " + ids.length + " items?");
                
                $('#formConfirm<?= $props['key'] ?>').off('submit').on('submit', function(e) {
                     e.preventDefault();
                     $.ajax({
                        url: url,
                        method: 'PUT',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            ids: ids,
                            key: '<?= $props['key'] ?>'
                        }),
                        headers: {
                            'Authorization': 'Bearer <?= esc($props['token']) ?>',
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                        },
                         success: function(response) {
                             console.log('=== MASS ACTION RESPONSE ===');
                             console.log('URL:', url);
                             console.log('Action:', action);
                             console.log('Response:', response);
                             console.log('Response Type:', typeof response);
                             console.log('Response Status:', response.status);
                             console.log('Response Message:', response.message);
                             console.log('============================');
                             
                             if(modal) modal.hide();
                            if (response.status == 'Success' || response.status == 'success' || response.status == 200) {
                                showAlert(response.message || 'Mass action successful', 'success');
                                table.<?= $props['key'] ?>.ajax.reload();
                                selectedIds<?= $props['key'] ?>.clear(); 
                                $('#selectAll<?= $props['key'] ?>').prop('checked', false);
                            } else {
                                // Enhanced error message with full details
                                let errorMsg = response.message || 'Error occurred';
                                if (!response.message) {
                                    errorMsg += ' [No message from server] ';
                                    errorMsg += 'Status: ' + (response.status || 'undefined') + ' ';
                                    errorMsg += 'Full Response: ' + JSON.stringify(response);
                                }
                                console.error('Mass action failed with response:', response);
                                showAlert(errorMsg, 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                             console.error('=== MASS ACTION ERROR ===');
                             console.error('URL:', url);
                             console.error('Status Code:', xhr.status);
                             console.error('Status Text:', xhr.statusText);
                             console.error('Response Text:', xhr.responseText);
                             console.error('Error:', error);
                             console.error('XHR Object:', xhr);
                             console.error('========================');
                             
                             if(modal) modal.hide();
                             
                             let errorMsg = 'Server error occurred';
                             
                             // Try to parse JSON error response
                             try {
                                 let jsonResponse = JSON.parse(xhr.responseText);
                                 console.log('Parsed JSON Error:', jsonResponse);
                                 errorMsg = jsonResponse.message || jsonResponse.error || errorMsg;
                             } catch(e) {
                                 // Not JSON, show raw response
                                 if (xhr.responseText && xhr.responseText.length < 500) {
                                     errorMsg += ': ' + xhr.responseText;
                                 } else {
                                     errorMsg += ' (HTTP ' + xhr.status + ' - ' + xhr.statusText + ')';
                                 }
                             }
                             
                             showAlert(errorMsg, 'danger');
                         }
                     });
                });
                
                modal.show();
            }

            $('.close-modal').on('click', function() {
                if(modal) modal.hide();
            });
             $(document).on('click', '.btn-open-modal<?= $props['key'] ?>', function() {
                $('#formConfirm<?= $props['key'] ?>').off('submit'); 
             });
        });

        function showProcess(show) {
            $('#datatable-process<?= $props['key'] ?>').css('display', show ? 'flex' : 'none');
        }

        function ensureFullRender(callback) {
            requestAnimationFrame(() => {
                setTimeout(() => {
                    callback();
                }, 10);
            });
        }

    })();
</script>