<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body min-h-screen bg-neutral-50 dark:bg-neutral-900">
    <?= view('Backend/Partial/page-header', ['title' => getTitleFromUri([2, 3])]) ?>
    <div class="grid grid-cols-12 gap-6 mt-6">
        <!-- Applicant Lists -->
        <div class="col-span-12">
            <!-- Modern Card with Solid Background & Shadow -->
            <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
                
                <!-- Card Header -->
                <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex flex-col items-start gap-3 w-full md:w-auto">
                        <div class="flex items-center gap-3">
                             <div class="p-2 bg-primary-50 text-primary-600 rounded-lg dark:bg-primary-900/50 dark:text-primary-400">
                                <iconify-icon icon="mingcute:user-3-line" class="text-xl"></iconify-icon>
                             </div>
                             <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0">Applicant Lists</h6>
                        </div>
                        <!-- Dynamic Job Vacancy Info Badge - Modern Fluent Design -->
                        <div id="job-vacancy-info-badge" class="hidden w-full animate-fade-in-up">
                            <div class="relative overflow-hidden bg-white dark:bg-neutral-800 border border-primary-200 dark:border-primary-700/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                                <!-- Gradient Accent Bar -->
                                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-400 via-primary-500 to-primary-600"></div>
                                
                                <div class="flex items-start gap-4 p-4">
                                    <!-- Icon Container -->
                                    <div class="flex-shrink-0 p-3 bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/40 dark:to-primary-800/40 rounded-xl">
                                        <iconify-icon icon="mingcute:briefcase-fill" class="text-2xl text-primary-600 dark:text-primary-400"></iconify-icon>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-primary-600/80 dark:text-primary-400/80">Filtered View</span>
                                            <div class="h-1 w-1 rounded-full bg-primary-400"></div>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">Active Filter</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <!-- Position -->
                                            <div class="flex items-center gap-2">
                                                <iconify-icon icon="mingcute:user-4-line" class="text-sm text-primary-500 dark:text-primary-400 flex-shrink-0"></iconify-icon>
                                                <span id="job-position-text" class="text-base font-bold text-neutral-800 dark:text-neutral-100 truncate"></span>
                                            </div>
                                            
                                            <!-- Company & Country -->
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <div class="flex items-center gap-1.5">
                                                    <iconify-icon icon="mingcute:building-2-line" class="text-sm text-primary-500 dark:text-primary-400"></iconify-icon>
                                                    <span id="job-company-text" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"></span>
                                                </div>
                                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-primary-500 dark:bg-primary-600 rounded-lg shadow-sm">
                                                    <iconify-icon icon="mingcute:location-line" class="text-sm text-white"></iconify-icon>
                                                    <span id="job-country-text" class="text-xs font-bold text-white uppercase tracking-wide"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Close Button -->
                                    <button onclick="clearJobVacancyFilter()" class="flex-shrink-0 group p-2 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-xl transition-all duration-200" title="Clear filter">
                                        <iconify-icon icon="mingcute:close-line" class="text-xl text-neutral-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modern Pill Tabs -->
                    <ul data-toggle="tab" class="flex flex-wrap p-1 bg-neutral-100 dark:bg-neutral-700/50 rounded-xl" id="card-title-tab" data-tabs-toggle="#card-title-tab-content" role="tablist">
                        <?php foreach ($tabs as $tab): ?>
                            <li role="presentation" class="me-1 last:me-0">
                                <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-300 ease-in-out hover:bg-white hover:shadow-sm dark:hover:bg-neutral-600 focus:outline-none aria-selected:bg-white aria-selected:text-primary-600 aria-selected:shadow-md dark:aria-selected:bg-neutral-600 dark:aria-selected:text-primary-400" 
                                    id="<?= $tab['key'] ?>-tab" 
                                    data-tabs-target="#<?= $tab['key'] ?>" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="<?= $tab['key'] ?>" 
                                    aria-selected="<?= session()->has('key') && session('key') == $tab['key'] ? 'true' : 'false'  ?>">
                                    <?php if(isset($tab['icon'])): ?>
                                        <iconify-icon icon="<?= $tab['icon'] ?>" class="text-lg"></iconify-icon>
                                    <?php endif; ?>
                                    <?= $tab['label'] ?>
                                </button>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>

                <div class="card-body relative p-6">
                    <div id="card-title-tab-content">
                        <?php foreach ($tabs as $tab): ?>
                            <div class="<?= (session('key') == $tab['key'] || (!session('key') && $tab['key'] === 'new')) ? 'animate-fade-in-up' : 'hidden' ?>" id="<?= $tab['key'] ?>" role="tabpanel" aria-labelledby="<?= $tab['key'] ?>-tab">                                
                                <?= view('Backend/Partial/table/table', ['title' => getTitleFromUri([2, 3]), 'props' => $tab['datatable']]) ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to clear the job vacancy filter
    function clearJobVacancyFilter() {
        // Remove query parameter from URL
        const url = new URL(window.location);
        url.searchParams.delete('jobvacancynew');
        window.history.replaceState({}, '', url);
        
        // Hide the badge with animation
        $('#job-vacancy-info-badge').fadeOut(300, function() {
            $(this).addClass('hidden');
        });
        
        // Clear the Select2 filter
        $('#jobvacancynew').val(null).trigger('change');
        
        // Reset the filter
        $('#btnFilternew').click();
    }

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const jobVacancyId = urlParams.get('jobvacancynew');

        if (jobVacancyId) {
            const filterId = '#jobvacancynew'; 
            const filterContainer = '#filter-containernew'; 
            const btnFilter = '#btnFilternew';

            // Fungsi untuk apply filter setelah Select2 ready
            function applyJobVacancyFilter() {
                // 1. Buka container filter dengan animasi
                $(filterContainer).slideDown();

                // 2. Fetch Data Job Vacancy untuk mengisi Select2
                $.ajax({
                    url: '<?= base_url("back-end/api/job-vacancy") ?>/' + jobVacancyId,
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer <?= esc($token) ?>'
                    },
                    success: function(response) {
                        if(response) {
                            // Populate the info badge - API now returns formatted data
                            const position = response.position || 'Unknown Position';
                            const companyName = response.company_name || 'Unknown Company';
                            const countryName = response.country_name || 'Unknown Country';
                            
                            $('#job-position-text').text(position);
                            $('#job-company-text').text(companyName);
                            $('#job-country-text').text(countryName);
                            
                            // Show the badge with smooth fade-in animation
                            $('#job-vacancy-info-badge').removeClass('hidden').css('opacity', 0).animate({opacity: 1}, 600);
                            
                            // Gunakan position + company + country sebagai label yang lebih deskriptif
                            let text = position;
                            if (companyName && companyName !== 'Unknown Company') text += ' - ' + companyName;
                            if (countryName && countryName !== 'Unknown Country') text += ' - ' + countryName;
                            
                            // 3. Tunggu Select2 benar-benar ready
                            const checkSelect2Ready = setInterval(function() {
                                if ($(filterId).hasClass('select2-hidden-accessible')) {
                                    clearInterval(checkSelect2Ready);
                                    
                                    // 4. Tambahkan Option baru & Set Value
                                    const newOption = new Option(text, jobVacancyId, true, true);
                                    $(filterId).append(newOption).trigger('change');
                                    
                                    // 5. Tunggu sebentar lalu klik tombol filter
                                    setTimeout(function() {
                                        $(btnFilter).click();
                                        
                                        // 6. Scroll Smooth ke Tabel setelah filter applied
                                        setTimeout(function() {
                                            $('html, body').animate({
                                                scrollTop: $("#new").offset().top - 100
                                            }, 600);
                                        }, 300);
                                        
                                        // 7. Highlight Filter Box untuk Memberi Fokus Visual
                                        $(filterContainer).addClass('ring-2 ring-primary-500 transition-all duration-500');
                                        setTimeout(() => {
                                             $(filterContainer).removeClass('ring-2 ring-primary-500');
                                        }, 2500);
                                    }, 200);
                                }
                            }, 100);
                            
                            // Timeout safety - jika Select2 tidak ready dalam 5 detik
                            setTimeout(function() {
                                clearInterval(checkSelect2Ready);
                            }, 5000);
                        }
                    },
                    error: function(err) {
                        console.error("Failed to fetch job vacancy detail for filter", err);
                        // Fallback: tetap coba set value meskipun gagal fetch detail
                        if ($(filterId).hasClass('select2-hidden-accessible')) {
                            $(filterId).val(jobVacancyId).trigger('change');
                            setTimeout(function() {
                                $(btnFilter).click();
                            }, 200);
                        }
                    }
                });
            }

            // Jalankan setelah semua komponen loaded
            // Cek apakah Select2 sudah ada, jika belum tunggu event
            if ($(filterId).length > 0) {
                // Tunggu sebentar untuk memastikan Select2 sudah diinisialisasi
                setTimeout(applyJobVacancyFilter, 500);
            } else {
                // Jika element belum ada, tunggu DOM mutation
                const observer = new MutationObserver(function(mutations, obs) {
                    if ($(filterId).length > 0) {
                        obs.disconnect();
                        setTimeout(applyJobVacancyFilter, 500);
                    }
                });
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        }
    });
</script>
<?= $this->endSection() ?>
