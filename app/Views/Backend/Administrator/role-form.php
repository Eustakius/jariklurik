<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <!-- Breadcrumb Navigation -->
    <div class="mb-4">
        <nav class="flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-400">
            <a href="<?= site_url('back-end/dashboard') ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                <iconify-icon icon="solar:home-2-bold-duotone" class="text-lg"></iconify-icon>
            </a>
            <iconify-icon icon="solar:alt-arrow-right-linear" class="text-xs"></iconify-icon>
            <a href="<?= site_url('back-end/administrator') ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Administrator</a>
            <iconify-icon icon="solar:alt-arrow-right-linear" class="text-xs"></iconify-icon>
            <a href="<?= site_url('back-end/administrator/role') ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Role</a>
            <iconify-icon icon="solar:alt-arrow-right-linear" class="text-xs"></iconify-icon>
            <span class="text-neutral-800 dark:text-neutral-200 font-semibold"><?= ucfirst($param['action']) ?></span>
        </nav>
    </div>

    <!-- Page Header with Actions -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h4 class="text-2xl font-bold text-neutral-800 dark:text-white mb-1">
                <?= getTitleFromUri([2, 3]) ?>
            </h4>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                Manage role permissions and access control
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= site_url('back-end/administrator/role') ?>" 
               class="btn bg-neutral-100 dark:bg-neutral-600 text-neutral-700 dark:text-white hover:bg-neutral-200 dark:hover:bg-neutral-500 rounded-lg px-4 py-2 text-sm flex items-center gap-2">
                <iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
                Back to List
            </a>
        </div>
    </div>

    <form action="<?= site_url($form['route']) ?>" method="post" data-parsley-validate id="roleForm">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="<?= $form['method'] ?>">
        
        <div class="grid grid-cols-12 gap-5">
            <!-- Role Information Card -->
            <div class="col-span-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 border-b border-primary-200 dark:border-primary-700">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="solar:document-text-bold-duotone" class="text-2xl text-primary-600 dark:text-primary-400"></iconify-icon>
                            <h6 class="card-title mb-0 text-lg font-bold text-primary-800 dark:text-primary-300">Role Information</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (session()->has('errors-backend')): ?>
                            <div class="alert alert-danger bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border-danger-100 px-6 py-[11px] mb-4 font-semibold text-lg rounded-lg" role="alert">
                                <div class="flex items-start justify-between text-lg">
                                    <div class="flex items-start gap-2">
                                        <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl mt-1.5 shrink-0"></iconify-icon>
                                        <div>
                                            <ul class="font-medium dark:text-danger-400 text-danger-600 text-sm mt-2">
                                                <?php foreach (session('errors-backend') as $field => $error): ?>
                                                    <li><?= esc($error) ?></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="remove-button text-danger-600 text-2xl line-height-1 cursor-pointer"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="grid grid-cols-12 gap-4">
                            <div class="md:col-span-6 col-span-12">
                                <label class="form-label text-sm font-semibold text-neutral-800 dark:text-neutral-200" for="name">
                                    Name <span class="text-danger-600">*</span>
                                </label>
                                <?= view('Backend/Partial/form/text-box', ['attribute' =>  [
                                    'field' => 'name',
                                    'label' => '',
                                    'placeholder' => 'Name',
                                    'required' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <label class="form-label text-sm font-semibold text-neutral-800 dark:text-neutral-200" for="description">
                                    Description
                                </label>
                                <?= view('Backend/Partial/form/text-box', ['attribute' =>  [
                                    'field' => 'description',
                                    'label' => '',
                                    'placeholder' => 'Description',
                                ]]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Card -->
            <div class="col-span-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 border-b border-primary-200 dark:border-primary-700">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:shield-user-bold-duotone" class="text-2xl text-primary-600 dark:text-primary-400"></iconify-icon>
                                <h5 class="text-lg font-bold text-primary-800 dark:text-primary-300 mb-0">
                                    Permissions 
                                    <span id="permissionCount" class="ml-2 px-3 py-1 bg-primary-500 dark:bg-primary-600 text-white text-xs font-bold rounded-full">
                                        0 / 0 selected
                                    </span>
                                </h5>
                            </div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <!-- Search Box -->
                                <div class="relative">
                                    <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-lg"></iconify-icon>
                                    <input type="text" 
                                           id="permissionSearch" 
                                           placeholder="Search permissions..." 
                                           class="pl-10 pr-4 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-white w-64">
                                </div>
                                <!-- Filter Dropdown -->
                                <select id="permissionFilter" class="px-4 py-2 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    <option value="all">All Permissions</option>
                                    <option value="selected">Selected Only</option>
                                    <option value="unselected">Unselected Only</option>
                                </select>
                                <!-- Bulk Actions -->
                                <button type="button" id="selectAllModules" class="btn bg-success-100 text-success-800 dark:bg-success-900/50 dark:text-success-300 hover:bg-success-200 dark:hover:bg-success-900/70 rounded-lg px-3 py-2 text-sm flex items-center gap-2 transition-colors">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                                    Select All
                                </button>
                                <button type="button" id="deselectAllModules" class="btn bg-danger-100 text-danger-800 dark:bg-danger-900/50 dark:text-danger-300 hover:bg-danger-200 dark:hover:bg-danger-900/70 rounded-lg px-3 py-2 text-sm flex items-center gap-2 transition-colors">
                                    <iconify-icon icon="solar:close-circle-bold" class="text-lg"></iconify-icon>
                                    Deselect All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="permissionsContainer">
                        <?= view('Backend/Partial/form/checkbox-list-group', ['attribute' =>  [
                                    'field' => 'permissions',
                                    'source' => $datarole,
                                    'label' => 'Nama',
                                    'required' => true,
                                ]]) ?>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Sticky Save Footer -->
    <?php if(strtolower($param['action']) != "detail"): ?>
    <div id="stickyFooter" class="fixed bottom-0 left-0 right-0 bg-white dark:bg-neutral-800 border-t border-neutral-200 dark:border-neutral-700 shadow-lg z-50 transition-transform duration-300">
        <div class="dashboard-main-body">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center gap-3">
                    <iconify-icon icon="solar:info-circle-bold-duotone" class="text-2xl text-warning-500"></iconify-icon>
                    <div>
                        <p class="text-sm font-semibold text-neutral-800 dark:text-white">Changes will affect all users assigned to this role</p>
                        <p class="text-xs text-neutral-600 dark:text-neutral-400">Make sure you review all permissions before saving</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="<?= site_url('back-end/administrator/role') ?>" 
                       class="btn bg-neutral-100 dark:bg-neutral-600 text-neutral-700 dark:text-white hover:bg-neutral-200 dark:hover:bg-neutral-500 rounded-lg px-6 py-2.5 text-sm flex items-center gap-2">
                        <iconify-icon icon="solar:close-circle-linear" class="text-lg"></iconify-icon>
                        Cancel
                    </a>
                    <button type="button" id="saveButton" class="btn bg-success-700 text-white hover:bg-success-800 rounded-lg px-6 py-2.5 text-sm flex items-center gap-2 shadow-lg hover:shadow-xl transition-all">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="text-lg"></iconify-icon>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>

<script>
$(document).ready(function() {
    // Update permission count
    function updatePermissionCount() {
        const total = $('input[name^="permissions"]').length;
        const selected = $('input[name^="permissions"]:checked').length;
        $('#permissionCount').text(selected + ' / ' + total + ' selected');
        
        // Update color based on selection
        if (selected === 0) {
            $('#permissionCount').removeClass('bg-success-500 bg-warning-500').addClass('bg-neutral-500');
        } else if (selected === total) {
            $('#permissionCount').removeClass('bg-neutral-500 bg-warning-500').addClass('bg-success-500');
        } else {
            $('#permissionCount').removeClass('bg-neutral-500 bg-success-500').addClass('bg-warning-500');
        }
    }

    // Search functionality
    $('#permissionSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        $('.card[data-module]').each(function() {
            const $card = $(this);
            const moduleName = $card.find('.font-bold').first().text().toLowerCase();
            const permissions = $card.find('label').map(function() {
                return $(this).text().toLowerCase();
            }).get().join(' ');
            
            const matches = moduleName.includes(searchTerm) || permissions.includes(searchTerm);
            $card.toggle(matches);
        });
    });

    // Filter functionality
    $('#permissionFilter').on('change', function() {
        const filter = $(this).val();
        
        $('.card[data-module]').each(function() {
            const $card = $(this);
            const checkboxes = $card.find('input[name^="permissions"]');
            const hasSelected = checkboxes.filter(':checked').length > 0;
            const hasUnselected = checkboxes.filter(':not(:checked)').length > 0;
            
            let show = true;
            if (filter === 'selected') {
                show = hasSelected;
            } else if (filter === 'unselected') {
                show = hasUnselected;
            }
            
            $card.toggle(show);
        });
    });

    // Bulk actions
    $('#selectAllModules').on('click', function() {
        $('.dataField-select-all').prop('checked', true).trigger('change');
    });

    $('#deselectAllModules').on('click', function() {
        if (confirm('Are you sure you want to deselect all permissions?')) {
            $('.dataField-select-all').prop('checked', false).trigger('change');
        }
    });

    // Save button with confirmation
    $('#saveButton').on('click', function() {
        const selectedCount = $('input[name^="permissions"]:checked').length;
        
        if (selectedCount === 0) {
            alert('Please select at least one permission before saving.');
            return;
        }
        
        if (confirm('Are you sure you want to save these changes? This will affect all users assigned to this role.')) {
            // Show loading state
            $(this).prop('disabled', true).html('<iconify-icon icon="svg-spinners:ring-resize" class="text-lg"></iconify-icon> Saving...');
            
            // Submit form
            $('#roleForm').submit();
        }
    });

    // Listen to checkbox changes
    $(document).on('change', 'input[name^="permissions"]', updatePermissionCount);
    
    // Initial count
    updatePermissionCount();
    
    // Add data-module attribute to cards for filtering
    $('.card').each(function(index) {
        $(this).attr('data-module', 'module-' + index);
    });
});
</script>

<?= $this->endSection() ?>
