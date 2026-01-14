<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([2])]) ?>
    <form action="<?= site_url($form['route']) ?>" method="post" data-parsley-validate>
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="<?= $form['method'] ?>">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <div class="card border-0">
                    <div class="card-header">
                        <h6 class="card-title mb-0 text-lg">Form</h6>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="md:col-span-6 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'field' => 'position',
                                    'label' => 'Position',
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
                            <div class="md:col-span-3 col-span-12">
                                <?= view('Backend/Partial/form/text-box-group', ['attribute' => [
                                    'type' => 'text-select',
                                    'field' => 'duration',
                                    'label' => 'Duration',
                                    'group' => [
                                        'field' => 'duration_type',
                                        'data' => [
                                            "Bulan",
                                            "Tahun"
                                        ]
                                    ],
                                    'required' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-3 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' =>  [
                                    'type' => 'date',
                                    'field' => 'selection_date',
                                    'label' => 'Selection Date',
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
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'male_quota',
                                    'label' => 'Male Quota',
                                    'required' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'female_quota',
                                    'label' => 'Female Quota',
                                    'required' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'unisex_quota',
                                    'label' => 'Unisex Quota',
                                    'required' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'male_quota_used',
                                    'label' => 'Male Quota Used',
                                    'readonly' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'female_quota_used',
                                    'label' => 'Female Quota Used',
                                    'readonly' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'unisex_quota_used',
                                    'label' => 'Unisex Quota Used',
                                    'readonly' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'visitor',
                                    'label' => 'Visitor',
                                    'readonly' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'applicant',
                                    'label' => 'Applicant',
                                    'readonly' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-4 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'number',
                                    'field' => 'applicant_process',
                                    'label' => 'Applicant Process',
                                    'readonly' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-12 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'editor',
                                    'field' => 'description',
                                    'label' => 'Description',
                                    'required' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-12 col-span-12">
                                <?= view('Backend/Partial/form/text-box', ['attribute' => [
                                    'type' => 'editor',
                                    'field' => 'requirement',
                                    'label' => 'Requirement',
                                    'required' => true,
                                ]]) ?>
                            </div>
                            <div class="md:col-span-12 col-span-12">
                                <?= view('Backend/Partial/form/checkbox-list', ['attribute' => [
                                    'field' => 'required_documents', // checkbox-list.php will append []
                                    'label' => 'Required Documents (Max 2, CV Mandatory)',
                                    'display' => 'box', 
                                    'required' => true,
                                    'source' => [
                                        ['value' => 'cv', 'label' => 'CV / Resume'],
                                        ['value' => 'language_cert', 'label' => 'Language Certificate'],
                                        ['value' => 'skill_cert', 'label' => 'Skill Certificate'],
                                        ['value' => 'other', 'label' => 'Other Support Documents'],
                                    ],
                                    // Handle value decoding:
                                    'selected' => !empty($data->required_documents) ? $data->required_documents : [],
                                ]]) ?>
                                <script>
                                    // Wait for document to be ready
                                    $(document).ready(function() {
                                        // ROBUST SELECTOR: Match input name starting with "required_documents"
                                        // This handles both "required_documents[]" and "required_documents[][cv]"
                                        const cbs = document.querySelectorAll('input[name^="required_documents"]');
                                        
                                        // Find CV checkbox by value 'cv'
                                        const cvCheckbox = Array.from(cbs).find(cb => cb.value === 'cv');
                                        const max = 2;



                                        // Ensure CV is always checked on page load
                                        if (cvCheckbox) {
                                            cvCheckbox.checked = true;
                                        }
                                        
                                        const handleCheck = () => {
                                            const checked = Array.from(cbs).filter(cb => cb.checked);
                                            
                                            // Prevent unchecking CV
                                            if (cvCheckbox && !cvCheckbox.checked) {
                                                cvCheckbox.checked = true;
                                                // Show visual feedback
                                                const cvCard = cvCheckbox.closest('.form-check')?.querySelector('.checkbox-card-box');
                                                if (cvCard) {
                                                    cvCard.classList.add('ring-2', 'ring-red-500');
                                                    setTimeout(() => {
                                                        cvCard.classList.remove('ring-2', 'ring-red-500');
                                                    }, 500);
                                                }
                                            }

                                            if (checked.length >= max) {
                                                cbs.forEach(cb => {
                                                    if (!cb.checked) {
                                                        cb.disabled = true;
                                                        // Visual feedback for disabled check-box-card if needed
                                                        const card = cb.parentElement.querySelector('.checkbox-card-box');
                                                        if(card) card.classList.add('opacity-50', 'cursor-not-allowed');
                                                    }
                                                });
                                            } else {
                                                cbs.forEach(cb => {
                                                    cb.disabled = false;
                                                    const card = cb.parentElement.querySelector('.checkbox-card-box');
                                                    if(card) card.classList.remove('opacity-50', 'cursor-not-allowed');
                                                });
                                            }
                                        };

                                        cbs.forEach(cb => {
                                            cb.addEventListener('change', handleCheck);
                                            // Prevent CV from being unchecked - check by value
                                            if (cb.value === 'cv') {
                                                cb.addEventListener('click', function(e) {
                                                    if (!this.checked) {
                                                        e.preventDefault();
                                                        this.checked = true;
                                                        return false;
                                                    }
                                                });
                                            }
                                        });
                                        
                                        // Run on init in case of edit mode
                                        handleCheck();
                                        
                                        // CRITICAL: Enable all checkboxes before form submit
                                        $('form[data-parsley-validate]').on('submit', function(e) {

                                            
                                            // Re-enable all checkboxes temporarily for submission
                                            cbs.forEach(cb => {
                                                cb.disabled = false;
                                            });
                                            

                                        });
                                    });
                                </script>
                            </div>
                            <div class="md:col-span-6 col-span-6">
                                <?= view('Backend/Partial/form/dropdown', ['attribute' => [
                                    'field' => 'company_id',
                                    'label' => 'by Company',
                                    'api' => 'back-end/api/company/select',
                                    'required' => true,
                                ]]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (strtolower($param['action']) != "detail"): ?>
                <div class="col-span-12">
                    <div class="p-4 bg-warning-50 dark:bg-warning-600/20 rounded-lg border-l-[3px] border-[#ebc470]">
                        <div class="flex items-center justify-between">
                            <div class="col-auto text-xs text-xs flex flex-col md:flex-row gap-2 md:gap-16">
                                <?php if (strtolower($param['action']) != "create"): ?>
                                    <div class="flex flex-col gap-2">
                                        <div class="flex flex-row gap-2">
                                            <label>Created by</label>
                                            <label>: <?= $data->getCreator()?->username ?></label>
                                        </div>
                                        <div class="flex flex-row gap-2">
                                            <label>Created by</label>
                                            <label>: <?= $data->created_at ?></label>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <div class="flex flex-row gap-2">
                                            <label>Updated by</label>
                                            <label>: <?= $data->getUpdater()?->username ?></label>
                                        </div>
                                        <div class="flex flex-row gap-2">
                                            <label>Updated by</label>
                                            <label>: <?= $data->updated_at ?></label>
                                        </div>
                                    </div>
                                    <?php if($data->status != 9 && $auth->user()->user_type === 'admin'): ?>
                                    <div class="">
                                        <?= view('Backend/Partial/form/checkbox', ['attribute' => [
                                            'value' => $data->status,
                                            'type' => 'status',
                                            'field' => 'status',
                                            'label' => 'Active',
                                        ]]) ?>
                                    </div>
                                <?php endif ?>
                                <?php endif ?>
                            </div>
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