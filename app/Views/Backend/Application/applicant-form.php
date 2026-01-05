<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([2])]) ?>
    <form action="<?= site_url($form['route']) ?>" method="post" data-parsley-validate enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="<?= $form['method'] ?>">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <div class="card border-0">
                    <div class="card-header">
                        <h6 class="card-title mb-0 text-lg">Form</h6>
                    </div>
                    <div class="card-body">
                        <?php if (session()->has('errors-backend')): ?>
                            <div class="alert alert-danger bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border-danger-100 px-6 py-[11px] mb-0 font-semibold text-lg rounded-lg" role="alert">
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
                            <div class="md:col-span-4 col-span-12">
                                <div id="document-uploads" class="flex flex-col gap-4">
                                    <!-- Dynamic Uploads will appear here -->
                                    <div class="text-gray-500 text-sm italic">Select a Job Vacancy to see required documents.</div>
                                </div>
                                <script>
                                    $(function() {
                                        const documents = <?= !empty($data->documents) ? json_encode($data->documents) : '{}' ?>; // PHP array to JSON string
                                        const docLabels = {
                                            'cv': 'CV / Resume',
                                            'language_cert': 'Language Certificate',
                                            'skill_cert': 'Skill Certificate',
                                            'other': 'Other Support Documents'
                                        };

                                        function renderUpload(key, label, required, existing) {
                                            const reqAttr = required ? 'required' : '';
                                            const previewHidden = existing ? '' : 'hidden';
                                            const previewSrc = existing ? '<?= base_url() ?>/' + existing : '';
                                            
                                            // Simple structure mimicking file-upload.php
                                            return `
                                                <div class="upload-group" data-key="${key}">
                                                    <label class="form-label text-sm">${label} ${required ? '<span class="text-danger-600">*</span>' : ''}</label>
                                                    <div class="upload-image-wrapper flex flex-col items-center gap-3">
                                                        <label class="w-full border border-primary-600 font-medium text-primary-600 px-4 py-2 rounded-xl inline-flex items-center gap-2 cursor-pointer hover:bg-primary-50">
                                                            <iconify-icon icon="solar:upload-linear" class="text-xl"></iconify-icon>
                                                            <span>Click to upload ${label}</span>
                                                            <input type="file" hidden name="${key}" id="file_${key}" ${ (!existing && required) ? 'required' : '' } accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this)">
                                                        </label>
                                                        <div class="uploaded-img ${previewHidden} relative w-full h-auto border input-form-light rounded-lg overflow-hidden border-dashed bg-neutral-50 dark:bg-neutral-600">
                                                            ${existing ? `<a href="${previewSrc}" target="_blank" class="block text-xs text-center py-2 text-primary-600 underline">View Existing File</a>` : ''}
                                                            <div id="preview_${key}" class="text-xs text-center py-1 text-gray-500"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                        }

                                        window.previewFile = function(input) {
                                            const file = input.files[0];
                                            if (file) {
                                                const container = $(input).closest('.upload-image-wrapper').find('.uploaded-img');
                                                container.removeClass('hidden');
                                                container.find('div[id^="preview_"]').text('Selected: ' + file.name);
                                            }
                                        }

                                        function loadRequirements(id) {
                                            if(!id) return;
                                            $.get('<?= base_url('back-end/api/job-vacancy') ?>/' + id, function(res) {
                                                const container = $('#document-uploads');
                                                container.empty();
                                                
                                                let reqs = [];
                                                let raw = res.required_documents;
                                                if (Array.isArray(raw)) {
                                                    reqs = raw;
                                                } else if (typeof raw === 'string') {
                                                    try {
                                                        reqs = JSON.parse(raw || '[]');
                                                    } catch(e) {}
                                                }

                                                if(reqs.length === 0) {
                                                    container.html('<div class="text-gray-500 text-sm">No specific documents required.</div>');
                                                    return;
                                                }

                                                reqs.forEach(key => {
                                                    const label = docLabels[key] || key;
                                                    const existing = documents[key] || null;
                                                    container.append(renderUpload(key, label, true, existing));
                                                });
                                            });
                                        }

                                        $('[name="job_vacancy_id"]').on('change', function() {
                                            loadRequirements($(this).val());
                                        });
                                        
                                        // Init
                                        const initialId = $('[name="job_vacancy_id"]').val();
                                        if(initialId) loadRequirements(initialId);
                                    });
                                </script>
                            </div>
                            <div class="md:col-span-8 col-span-12">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="md:col-span-12 col-span-12">
                                        <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                            'type' => 'text',
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'required' => true,
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/dropdown-static', ['attribute' => [
                                            'field' => 'gender',
                                            'label' => 'Gender',
                                            'required' => true,
                                            'data' => [
                                                ["value" => 'M', "label" => "Male"],
                                                ["value" => 'F', "label" => "Female"]
                                            ]
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                            'type' => 'date',
                                            'field' => 'bod',
                                            'label' => 'Date of Birth',
                                            'required' => true,
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/dropdown-static', ['attribute' => [
                                            'field' => 'educationlevel',
                                            'label' => 'Education Level',
                                            'required' => true,
                                            'data' => $config->educationLevel
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/dropdown', ['attribute' => [
                                            'field' => 'job_vacancy_id',
                                            'label' => 'Job Vacancy',
                                            'api' => 'back-end/api/job-vacancy/select',
                                            'required' => true,
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/dropdown', ['attribute' => [
                                            'field' => 'country_id',
                                            'label' => 'Country',
                                            'api' => 'back-end/api/country/select',
                                            'required' => true,
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/dropdown', ['attribute' => [
                                            'field' => 'company_id',
                                            'label' => 'Company',
                                            'api' => 'back-end/api/company/select',
                                            'required' => true,
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                            'type' => 'email',
                                            'field' => 'email',
                                            'label' => 'Email',
                                        ]]) ?>
                                    </div>
                                    <div class="md:col-span-6 col-span-12">
                                        <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                            'type' => 'phone',
                                            'field' => 'phone',
                                            'label' => 'Phone',
                                        ]]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (strtolower($param['action']) != "detail"): ?>
                <div class="col-span-12">
                    <div class="p-4 bg-warning-50 dark:bg-warning-600/20 rounded-lg border-l-[3px] border-[#ebc470]">
                        <div class="flex flex flex-col md:flex-row gap-2 md:gap-16 md:items-center md:justify-between">
                            <div class="col-auto">
                                <button class="btn bg-success-100 text-success-600 hover:bg-success-700 hover:text-white rounded-lg px-3.5 py-2 text-sm">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
