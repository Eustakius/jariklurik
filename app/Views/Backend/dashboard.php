<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body">
    <?= view('Backend/Partial/page-header', ['title' => 'Dashboard']) ?>
    <?php if (session()->has('forbiden')): ?>
        <div class="alert alert-danger bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border-danger-600 border-start-width-4-px border-l-[3px] dark:border-neutral-600 px-6 py-[13px] mb-0 text-sm rounded flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
                <?= esc(session('forbiden')) ?>
            </div>
            <button class="remove-button text-danger-600 text-2xl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
        </div>
    <?php endif; ?>
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <?= view('Backend/Partial/stat-card', [
            'label' => 'Total Vacancies',
            'value' => $jobVacancyCount,
            'icon' => 'solar:case-round-bold-duotone',
            'colorClass' => 'from-primary-500 to-primary-600',
            'link' => '/back-end/job-vacancy'
        ]) ?>

        <?= view('Backend/Partial/stat-card', [
            'label' => 'Active Vacancies',
            'value' => $jobVacancyActiveCount,
            'icon' => 'solar:play-circle-bold-duotone',
            'colorClass' => 'from-success-500 to-success-600',
            'link' => '/back-end/job-vacancy?status=active'
        ]) ?>

        <?= view('Backend/Partial/stat-card', [
            'label' => 'Expired Vacancies',
            'value' => $jobVacancyExpiredCount,
            'icon' => 'solar:close-circle-bold-duotone',
            'colorClass' => 'from-danger-500 to-danger-600',
            'link' => '/back-end/job-vacancy?status=expired'
        ]) ?>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h5 class="text-lg font-bold text-neutral-800 dark:text-white mb-4 flex items-center gap-2">
            <iconify-icon icon="solar:thunderstorm-bold-duotone" class="text-warning-500"></iconify-icon>
            Quick Actions
        </h5>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="/back-end/job-vacancy/new" class="card p-4 rounded-2xl shadow-lg bg-white dark:bg-neutral-800 hover:shadow-xl transition-all group flex flex-col items-center justify-center gap-3 text-center h-[120px]">
                <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <iconify-icon icon="solar:add-circle-bold-duotone" class="text-2xl"></iconify-icon>
                </div>
                <span class="font-semibold text-neutral-700 dark:text-neutral-300 text-sm">Post New Job</span>
            </a>

            <a href="/back-end/applicant" class="card p-4 rounded-2xl shadow-lg bg-white dark:bg-neutral-800 hover:shadow-xl transition-all group flex flex-col items-center justify-center gap-3 text-center h-[120px]">
                <div class="w-10 h-10 rounded-full bg-success-50 dark:bg-success-900/30 text-success-600 dark:text-success-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-2xl"></iconify-icon>
                </div>
                <span class="font-semibold text-neutral-700 dark:text-neutral-300 text-sm">Review Applicants</span>
            </a>

            <a href="/back-end/company" class="card p-4 rounded-2xl shadow-lg bg-white dark:bg-neutral-800 hover:shadow-xl transition-all group flex flex-col items-center justify-center gap-3 text-center h-[120px]">
                <div class="w-10 h-10 rounded-full bg-warning-50 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <iconify-icon icon="solar:buildings-2-bold-duotone" class="text-2xl"></iconify-icon>
                </div>
                <span class="font-semibold text-neutral-700 dark:text-neutral-300 text-sm">Company Profile</span>
            </a>

            <a href="/back-end/administrator/setting" class="card p-4 rounded-2xl shadow-lg bg-white dark:bg-neutral-800 hover:shadow-xl transition-all group flex flex-col items-center justify-center gap-3 text-center h-[120px]">
                <div class="w-10 h-10 rounded-full bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <iconify-icon icon="solar:settings-bold-duotone" class="text-2xl"></iconify-icon>
                </div>
                <span class="font-semibold text-neutral-700 dark:text-neutral-300 text-sm">Settings</span>
            </a>
        </div>
    </div>
    <!-- Analytics Section: Tiled Layout (2:1 Split -> NOW 1:1 Split for Alignment) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Application Trends (1/2 Width) -->
        <div class="col-span-1 card p-6 rounded-3xl shadow-xl bg-white dark:bg-neutral-800 border border-neutral-100 dark:border-neutral-700/50 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                    <h4 class="text-xl font-bold text-neutral-800 dark:text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/20 text-primary-500 flex items-center justify-center">
                            <iconify-icon icon="solar:graph-new-bold-duotone" class="text-2xl"></iconify-icon>
                        </div>
                        Application Trends
                    </h4>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1 ml-13">Overview from Jan 2024</p>
                </div>
                <div>
                    <span class="badge bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 px-3 py-1 rounded-lg text-xs font-semibold border border-primary-200 dark:border-primary-800">
                        Daily View
                    </span>
                </div>
            </div>
            <!-- Chart Container -->
            <div id="applicationTrendChart" class="w-full flex-1 min-h-[350px]"></div>
        </div>

        <!-- Vacancy Status (1/2 Width) -->
        <div class="col-span-1 card p-6 rounded-3xl shadow-xl bg-white dark:bg-neutral-800 border border-neutral-100 dark:border-neutral-700/50 flex flex-col">
            <h5 class="text-lg font-bold text-neutral-800 dark:text-white mb-2 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-success-50 dark:bg-success-900/20 text-success-500 flex items-center justify-center">
                    <iconify-icon icon="solar:pie-chart-2-bold-duotone" class="text-lg"></iconify-icon>
                </div>
                Vacancy Status
            </h5>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-6 pl-11">Distribution of job postings</p>
            
            <div id="vacancyStatusChart" class="w-full flex-1 flex items-center justify-center min-h-[320px]"></div>
        </div>
    </div>

    <!-- Visitor Analytics Section (New - 1:1 Split) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Visitor Growth (Line Chart) -->
        <div class="col-span-1 card p-6 rounded-3xl shadow-xl bg-white dark:bg-neutral-800 border border-neutral-100 dark:border-neutral-700/50 flex flex-col justify-between">
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <h4 class="text-xl font-bold text-neutral-800 dark:text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/20 text-primary-500 flex items-center justify-center">
                            <iconify-icon icon="solar:chart-square-bold-duotone" class="text-2xl"></iconify-icon>
                        </div>
                        Visitor Growth
                    </h4>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1 ml-13">Monthly website traffic (Real-time)</p>
                </div>
            </div>
            <div id="visitorGrowthChart" class="w-full flex-1 min-h-[380px]"></div>
        </div>

        <!-- Stats & Traffic Sources -->
        <div class="col-span-1 flex flex-col gap-6">
            
            <!-- Total Job Views Card -->
            <div class="card p-6 rounded-3xl shadow-xl bg-white dark:bg-neutral-800 border border-neutral-100 dark:border-neutral-700/50">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-1">Total Site Visits</p>
                        <h3 class="text-3xl font-bold text-neutral-800 dark:text-white"><?= number_format($totalVisitors ?? 0) ?></h3>
                        <div class="flex items-center gap-1 mt-2 text-sm text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-500/10 px-2 py-1 rounded w-fit">
                            <iconify-icon icon="solar:arrow-right-up-bold-duotone"></iconify-icon>
                            <span>+12.5% vs last month</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 flex items-center justify-center">
                        <iconify-icon icon="solar:eye-bold-duotone" class="text-2xl"></iconify-icon>
                    </div>
                </div>
            </div>

            <!-- Traffic Sources Chart -->
            <div class="card p-6 rounded-3xl shadow-xl bg-white dark:bg-neutral-800 border border-neutral-100 dark:border-neutral-700/50 flex-1 flex flex-col">
                <h5 class="text-lg font-bold text-neutral-800 dark:text-white mb-2 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-warning-50 dark:bg-warning-900/20 text-warning-500 flex items-center justify-center">
                        <iconify-icon icon="solar:pie-chart-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    Device Types
                </h5>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-6 pl-11">Visitor devices breakdown</p>
                <div id="trafficSourceChart" class="w-full flex-1 flex items-center justify-center min-h-[220px]"></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colors = {
            primary: '#EBC470',
            success: '#10B981',
            danger: '#EF4444',
            warning: '#F59E0B',
            grid: '#e5e7eb',
            gridDark: '#374151',
            textLight: '#6b7280',
            textDark: '#d1d5db'
        };
        const isDark = document.documentElement.classList.contains('dark');
        
        // --- 1. Application Trend Chart (ApexCharts - Zoomable/Daily) ---
        const rawData = <?= json_encode($echartsData ?? []) ?>;
        const seriesApproved = rawData.map(item => [item.date, item.approved]);
        const seriesPending = rawData.map(item => [item.date, item.pending]);
        const seriesRejected = rawData.map(item => [item.date, item.rejected]);

        var appTrendOptions = {
            series: [
                { name: 'Approved', data: seriesApproved },
                { name: 'In Review', data: seriesPending },
                { name: 'Rejected', data: seriesRejected }
            ],
            chart: {
                type: 'area', 
                height: 350, // Reduced to 350
                stacked: false,
                fontFamily: 'inherit',
                background: 'transparent', // Fix container color bleed
                toolbar: {
                    show: true,
                    tools: {
                        download: false,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    },
                    autoSelected: 'zoom',
                    offsetY: -5 
                },
                animations: { enabled: true, speed: 800 },
                zoom: { enabled: true, type: 'x', autoScaleYaxis: true }
            },
            colors: [colors.success, colors.warning, colors.danger],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                type: 'datetime',
                tooltip: { enabled: true, theme: isDark ? 'dark' : 'light' },
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: isDark ? colors.textDark : colors.textLight, fontSize: '12px', fontWeight: 500 } }
            },
            yaxis: {
                labels: { style: { colors: isDark ? colors.textDark : colors.textLight, fontSize: '11px' } }
            },
            grid: {
                borderColor: isDark ? colors.gridDark : colors.grid,
                strokeDashArray: 4,
                padding: { top: 0, right: 10, bottom: 0, left: 10 } 
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center', 
                markers: { radius: 12 },
                itemMargin: { horizontal: 10, vertical: 0 },
                labels: { colors: isDark ? colors.textDark : '#374151' },
                onItemClick: { toggleDataSeries: true },
                onItemHover: { highlightDataSeries: true }
            },
            tooltip: { 
                theme: isDark ? 'dark' : 'light',
                style: { fontSize: '12px' },
                x: { format: 'dd MMM yyyy' }
            }
        };

        var trendChart = new ApexCharts(document.querySelector("#applicationTrendChart"), appTrendOptions);
        trendChart.render();
        
        // --- 2. Vacancy Status Chart (Donut) ---
        var vacancyData = [
            <?= $jobVacancyActiveCount ?? 0 ?>, 
            <?= $jobVacancyExpiredCount ?? 0 ?>, 
            <?= $jobVacancyNotActiveCount ?? 0 ?>
        ];
        
        var vacancyOptions = {
            series: vacancyData,
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: 'inherit',
                background: 'transparent',
                animations: { enabled: true }
            },
            labels: ['Active', 'Expired', 'Inactive'],
            colors: [colors.success, colors.danger, '#9ca3af'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { show: true, color: isDark ? colors.textDark : '#374151', offsetY: -10 },
                            value: { show: true, fontSize: '24px', fontWeight: 700, color: isDark ? '#fff' : '#111827', offsetY: 16 },
                            total: { show: true, showAlways: true, label: 'Total', color: isDark ? '#9ca3af' : '#6b7280', formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0) }
                        }
                    }
                }
            },
            stroke: { show: false },
            legend: { position: 'bottom', labels: { colors: isDark ? colors.textDark : '#374151' } },
            tooltip: { theme: isDark ? 'dark' : 'light' }
        };

        var vacancyChart = new ApexCharts(document.querySelector("#vacancyStatusChart"), vacancyOptions);
        vacancyChart.render();

        // --- 3. Visitor Growth Chart (Line - Mock Data) ---
        const visitorGrowthData = <?= json_encode($visitorGrowth) ?>;
        var visitorOptions = {
            series: [{ name: 'Visitors', data: visitorGrowthData.data }],
            chart: {
                type: 'line',
                height: 380, // Increased to 380 to match stack
                fontFamily: 'inherit',
                background: 'transparent',
                toolbar: { show: false },
                animations: { enabled: true }
            },
            colors: [colors.primary],
            stroke: { curve: 'smooth', width: 3 },
            dataLabels: { enabled: false },
            xaxis: {
                categories: visitorGrowthData.categories,
                labels: { style: { colors: isDark ? colors.textDark : colors.textLight } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { style: { colors: isDark ? colors.textDark : colors.textLight } }
            },
            grid: {
                borderColor: isDark ? colors.gridDark : colors.grid,
                strokeDashArray: 4,
            },
            tooltip: { theme: isDark ? 'dark' : 'light' }
        };
        new ApexCharts(document.querySelector("#visitorGrowthChart"), visitorOptions).render();

        // --- 4. Traffic Sources Chart (Donut - Mock Data) ---
        const trafficData = <?= json_encode($trafficSources) ?>;
        var trafficOptions = {
            series: trafficData.series,
            labels: trafficData.labels,
            chart: {
                type: 'donut',
                height: 220, // Reduced to 220
                fontFamily: 'inherit',
                background: 'transparent'
            },
            colors: [colors.primary, colors.success, colors.warning, colors.textLight],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: false // Keep it clean for smaller chart
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            stroke: { show: false },
            legend: { 
                position: 'bottom', 
                labels: { colors: isDark ? colors.textDark : '#374151' },
                itemMargin: { horizontal: 5, vertical: 0 }
            },
            tooltip: { theme: isDark ? 'dark' : 'light' }
        };
        new ApexCharts(document.querySelector("#trafficSourceChart"), trafficOptions).render();
        
        // --- Auto-Refresh: Poll API every 30 seconds for new visitor data ---
        let visitorChart = new ApexCharts(document.querySelector("#visitorGrowthChart"), visitorOptions);
        let trafficChart = new ApexCharts(document.querySelector("#trafficSourceChart"), trafficOptions);
        
        setInterval(async () => {
            try {
                const response = await fetch('/api/dashboard/visitor-stats');
                if (!response.ok) return;
                
                const result = await response.json();
                if (!result.success) return;
                
                const data = result.data;
                
                // Update Total Visitors count
                const totalVisitorsEl = document.querySelector('.text-3xl.font-bold');
                if (totalVisitorsEl && data.totalVisitors) {
                    totalVisitorsEl.textContent = data.totalVisitors.toLocaleString();
                }
                
                // Update Visitor Growth chart
                if (data.visitorGrowth && visitorChart) {
                    visitorChart.updateOptions({
                        xaxis: { categories: data.visitorGrowth.categories }
                    });
                    visitorChart.updateSeries([{
                        name: 'Visitors',
                        data: data.visitorGrowth.data
                    }]);
                }
                
                // Update Traffic Sources chart
                if (data.trafficSources && trafficChart) {
                    trafficChart.updateOptions({
                        labels: data.trafficSources.labels
                    });
                    trafficChart.updateSeries(data.trafficSources.series);
                }
                
            } catch (error) {
                console.error('Auto-refresh error:', error);
            }
        }, 30000); // Poll every 30 seconds
    });
</script>
<?= $this->endSection() ?>