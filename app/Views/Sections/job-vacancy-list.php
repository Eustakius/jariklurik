<div class="bg-[#EBC470] px-7 md:px-[16.813rem] tab-content pt-12">
    <div class="flex flex-wrap items-center justify-between mb-6 gap-2">
        <div class="flex flex-row gap-4 items-center">
            <img src="/image/jariklurik-icon-lowongan.png" class="h-[2.563rem] md:h-[3.563rem] w-[2.563rem] md:w-[3.563rem]" alt="Jariklurik info menakir luar negeri">
            <h2 class="font-bold text-xl md:text-[2rem] text-[#74430D]">Daftar Lowongan Kerja Terbaru</h2>
        </div>
        <div class="flex flex-row items-center justify-center gap-2">
            <div class="relative z-20">
                <button id="country" data-dropdown-toggle="dropdownCountry" class="w-auto text-xs md:text-base text-[#714D00] font-bold rounded-[10px] border md:border-[3px] border-[#714D00] py-2 px-2 md:px-3 text-center flex flex-wrap items-center justify-between" type="button">Negara <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <div id="dropdownCountry"
                    class="z-50 hidden min-w-[300px] bg-white absolute top-full left-0 mt-2 divide-y divide-gray-100 rounded-lg shadow-2xl dark:bg-gray-700 dark:divide-gray-600 border border-gray-200">
                    <ul class="py-3 space-y-3 text-sm bg-white rounded-lg text-gray-700" aria-labelledby="dropdownRadioButton">
                        <li class="relative">
                            <div class="form-group form-float relative">
                                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <div class="form-line ps-10 mr-3">
                                    <input type="text" autocomplete="off" name="country" id="countryFilter"
                                        class="!text-lg form-control w-full border rounded-lg px-3 py-2" placeholder="Cari negara...">
                                </div>
                            </div>
                            <ul id="countryResult"
                                class="!text-lg z-50 bg-white border border-gray-200 w-full mt-1 hidden max-h-48 overflow-y-auto">
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="relative z-20">
                <button id="company" data-dropdown-toggle="dropdownCompany" class="w-auto text-xs md:text-base text-[#714D00] font-bold rounded-[10px] border md:border-[3px] border-[#714D00] py-2 px-2 md:px-3 text-center flex flex-wrap items-center justify-between" type="button">Perusahaan <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <div id="dropdownCompany" class="z-20 hidden w-[20.75rem] bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-3 space-y-3 text-sm bg-white rounded-lg text-gray-700" aria-labelledby="dropdownRadioButton">
                        <li class="relative">
                            <div class="form-group form-float relative">
                                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <div class="form-line ps-10 mr-3">
                                    <input type="text" autocomplete="off" name="company" id="companyFilter"
                                        class="!text-lg form-control w-full border rounded-lg px-3 py-2" placeholder="Cari perusahaan...">
                                </div>
                            </div>
                            <ul id="companyResult"
                                class="!text-lg z-50 bg-white border border-gray-200 w-full mt-1 hidden max-h-48 overflow-y-auto">
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="relative z-20">
                <button id="sorting" data-dropdown-toggle="dropdownSorting" class="w-auto text-xs md:text-base text-[#714D00] font-bold rounded-[10px] border md:border-[3px] border-[#714D00] py-2 px-2 md:px-3 text-center flex flex-wrap items-center justify-between" type="button">Urutkan <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <div id="dropdownSorting" class="z-20 hidden w-[20.75rem] md:!right-[-103px] bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="p-3 space-y-3 text-sm bg-white rounded-lg text-gray-700" aria-labelledby="dropdownSorting">
                        <li>
                            <div class="flex items-start">
                                <input checked id="default-radio-1" type="radio" value="desc" name="filter-radio" class="filter w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                <label for="default-radio-1" class="text-sm font-medium text-gray-900">Terbaru</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-start">
                                <input id="default-radio-2" type="radio" value="asc" name="filter-radio" class="filter w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                <label for="default-radio-2" class="text-sm font-medium text-gray-900">Terlama</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <p class="text-right w-full filter-result text-sm md:text-lg mb-6 md:mb-8 flex flex-row gap-2 justify-end items-end"></p>
    <div class="flex flex-col gap-4 md:gap-9 job-list mb-8 md:mb-14 relative" data-loading="<?= esc($loading) ?>"></div>
    <div class="flex flex-row gap-4 job-pagination items-center justify-center"></div>
</div>
<script>
    var $radio = 'desc';
    var $inputCompany = $('#companyFilter');
    var $inputCountry = $('#countryFilter');
    var $listCompany = $('#companyResult');
    var $listCountry = $('#countryResult');
    var $dropdownCompany = $('#dropdownCompany');
    var $dropdownCountry = $('#dropdownCountry');
    var $dropdownSorting = $('#dropdownSorting');
    var initData = function(page, order, company, country) {
        $('.job-list').html($('.job-list').data('loading'));
        $(document).ready(function() {
            fetch(`<?= base_url('api/job-vacancy?page=') ?>${page??1}&order=${order??$radio}&company=${company??$inputCompany.val()}&country=${country??$inputCountry.val()}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer <?= $token ?>`,
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(res => {
                    $('.job-list').html('');

                    let currentPage = res.pagination.page
                    let totalPage = res.pagination.totalpage;
                    let maxVisible = 5; 
                    let start = 1;
                    let end = totalPage;

                    if (totalPage == 0) {
                        let html = `<div class="shadow-lg bg-white flex flex-col rounded-[0.938rem] w-full pb-16"><div class="p-5 md:p-10 flex flex-col gap-4 md:gap-7 justify-center items-center">
                            <img src="/icon/jariklurik-hands.png" class="h-[5.75rem] md:h-[5.75rem] w-[5.75rem]" />
                            <p class="text-lg md:text-2xl font-bold">Mohon maaf saat ini Lowongan Kerja belum tersedia.</p>
                        </div></div>`
                        $('.job-list').append(html);
                    } else {
                        $.each(res.data, function(index, item) {
                            let html = `<div class="relative shadow-lg bg-white flex flex-col md:flex-row md:items-center justify-between p-5 rounded-[0.938rem] gap-4">
                                ${item.pin == "1" ? '<div class="hidden absolute right-[-12px] top-[-12px]"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 2048 2048"><path fill="currentColor" d="M1990 748q-33 33-64 60t-65 47t-73 29t-90 11q-34 0-65-6l-379 379q13 38 19 78t6 80q0 65-13 118t-37 100t-60 89t-79 87l-386-386l-568 569l-136 45l45-136l569-568l-386-386l45-45q70-70 160-107t190-37q82 0 157 25l379-379q-6-31-6-65q0-49 10-88t30-74t46-65t61-65zm-292 19q55 0 104-26l-495-495q-26 49-26 104q0 28 6 52t15 51L810 944q-25-10-47-19t-44-15t-45-9t-51-4q-57 0-110 16t-100 49l673 673q32-46 49-99t17-110q0-27-3-50t-10-46t-16-45t-19-47l491-492q26 8 50 14t53 7"/></svg></div>' : ''}
                                <div class="flex gap-3 md:gap-5">
                                    <img src="${item.company.logo}" class="h-[3.625rem] md:h-[5.625rem] w-[3.625rem] md:w-[5.625rem] object-cover border border-solid border-[#CCCCCC] rounded-[15px]"></img>
                                    <div class="flex flex-col justify-center">
                                        <h2 class="font-bold text-lg md:text-2xl">${item.position}</h2>
                                        <p class="text-sm md:text-base">${item.company.name}</p>
                                    </div>
                                    
                                </div>
                                <div class="flex justify-end"><a href="${item.slug}" class="text-xs md:text-base text-[#714D00] font-bold rounded-[10px] border md:border-[3px] border-[#714D00] py-2 px-2 md:px-5 shadow-[2px_2px_0_0_rgba(113,77,0,1)]">Selengkapnya</a></div>
                            </div>`;
                            $('.job-list').append(html);
                        });

                        if (totalPage > maxVisible) {
                            let half = Math.floor(maxVisible / 2);
                            start = currentPage - half;
                            end = currentPage + half;

                            if (start < 1) {
                                start = 1;
                                end = maxVisible;
                            }
                            if (end > totalPage) {
                                end = totalPage;
                                start = totalPage - maxVisible + 1;
                            }
                        }

                        if (res.pagination.hasprev) {
                            let html = `<div onclick="initData(${currentPage-1})" class="text-[#714D00] cursor-pointer flex items-center justify-center text-xs md:-base font-bold w-[2.188rem] h-[2.188rem] rounded-[5px] border-[3px] border-[#714D00]"><</div>`;
                            $('.job-pagination').append(html);
                        }
                        $('.job-pagination').html(''); // reset
                        for (let i = start; i <= end; i++) {
                            let html = `<div onclick="initData(${i})" class="${i === currentPage ?'bg-[#714D00] text-white':'text-[#714D00]'} cursor-pointer flex items-center justify-center text-xs md:text-base font-bold w-[2.188rem] h-[2.188rem] rounded-[5px] border-[3px] border-[#714D00]">${i}</div>`;
                            $('.job-pagination').append(html);
                        }

                        if (res.pagination.hasnext) {
                            let html = `<div onclick="initData(${currentPage+1})" class="text-[#714D00] cursor-pointer flex items-center justify-center text-xs md:text-base font-bold w-[2.188rem] h-[2.188rem] rounded-[5px] border-[3px] border-[#714D00]">></div>`;
                            $('.job-pagination').append(html);
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    $('.job-list').html('<div class="text-center text-red-500 py-10">Gagal memuat data. Silakan coba lagi.</div>');
                })
                // .finally(() => hideSpinner());
        });
    }
    initData();
    $('input[name="filter-radio"].filter').on('change', function() {
        $radio = $(this).val(); // Fix: Update value BEFORE calling initData
        initData(1, $radio); // Explicitly pass the new order
        const id = $(this).attr('id');
        const labelText = '<div class="flex items-center gap-2 bg-[#EBC470] bg-opacity-20 px-4 py-2 rounded-full border border-[#714D00] text-[#714D00] font-medium">' +
            '<span>Filter: ' + 
            ($inputCountry.val() ? 'Negara: <b>' + $inputCountry.val() + '</b>, ' : '') + 
            ($inputCompany.val() ? 'Perusahaan: <b>' + $inputCompany.val() + '</b>, ' : '') + 
            'Urutan: <b>' + $(`label[for="${id}"]`).text().trim() + '</b></span>' +
            '<button class="clear-filter hover:bg-red-100 p-1 rounded-full transition-colors duration-200" title="Hapus Filter">' +
            '<svg width="16" height="16" class="w-4 h-4 text-[#714D00]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><path fill="currentColor" d="M12.225 2.082a.5.5 0 0 1 .693.694l-.064.078L8.207 7.5l4.647 4.647l.064.078a.5.5 0 0 1-.693.693l-.078-.064L7.5 8.207l-4.646 4.647a.5.5 0 1 1-.707-.707L6.793 7.5L2.147 2.854l-.065-.078a.5.5 0 0 1 .694-.694l.078.065L7.5 6.793l4.647-4.646z"/></svg>' +
            '</button></div>';
        $('.filter-result').html(labelText);
        $dropdownSorting.addClass('hidden')
    });
    var page = 1;
    var loading = false;
    var hasMore = true;
    var query = '';
    var debounceTimer = null;

    function fetchData(reset, elInput, elList, url, elDropdown) {
        if (loading || !hasMore) return;
        loading = true;

        if (reset) {
            elList.empty();
        }

        elList.append('<li id="loadingItem" class="px-3 py-2 text-gray-400 text-sm">Memuat...</li>');

        $.ajax({
            url: '/api/'+ url +'/autocomplate',
            method: 'GET',
            headers: {
                'Authorization': `Bearer <?= $token ?>`,
                'Content-Type': 'application/json'
            },
            data: {
                q: query,
                page: page
            },
            success: function(res) {
                // Jika server hanya mengembalikan array biasa:
                var data = res.data || res;
                if (!Array.isArray(data)) {
                    console.warn('⚠️ Response bukan array:', res);
                    data = [];
                }

                $('#loadingItem').remove();

                if (!data.length) {
                    if (page === 1) {
                        elList.html('<li class="px-3 py-2 text-gray-500">Tidak ada hasil</li>');
                    }
                    hasMore = false;
                    return;
                }

                $.each(data, function(i, item) {
                    var $li = $('<li>')
                        .text(item)
                        .addClass('px-3 py-2 hover:bg-gray-100 cursor-pointer')
                        .on('click', function() {
                            elInput.val(item);
                            elList.addClass('hidden');
                            initData();
                            const id = $(`input[name="filter-radio"][value="${$radio}"]`).attr('id');
                            const labelText = '<div class="flex items-center gap-2 bg-[#EBC470] bg-opacity-20 px-4 py-2 rounded-full border border-[#714D00] text-[#714D00] font-medium">' +
                                '<span>Filter: ' + 
                                ($inputCountry.val() ? 'Negara: <b>' + $inputCountry.val() + '</b>, ' : '') + 
                                ($inputCompany.val() ? 'Perusahaan: <b>' + $inputCompany.val() + '</b>, ' : '') + 
                                'Urutan: <b>' + $(`label[for="${id}"]`).text().trim() + '</b></span>' +
                                '<button class="clear-filter hover:bg-red-100 p-1 rounded-full transition-colors duration-200" title="Hapus Filter">' +
                                '<svg width="16" height="16" class="w-4 h-4 text-[#714D00]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><path fill="currentColor" d="M12.225 2.082a.5.5 0 0 1 .693.694l-.064.078L8.207 7.5l4.647 4.647l.064.078a.5.5 0 0 1-.693.693l-.078-.064L7.5 8.207l-4.646 4.647a.5.5 0 1 1-.707-.707L6.793 7.5L2.147 2.854l-.065-.078a.5.5 0 0 1 .694-.694l.078.065L7.5 6.793l4.647-4.646z"/></svg>' +
                                '</button></div>';
                            $('.filter-result').html(labelText)
                            elDropdown.addClass('hidden')
                        });
                    elList.append($li);
                });

                hasMore = res.has_more !== false && data.length > 0;
                page++;
            },
            error: function() {
                console.error('❌ Gagal mengambil data perusahaan');
                $('#loadingItem').remove();
                hasMore = false;
            },
            complete: function() {
                loading = false;
            }
        });
    }

    $(document).on('click', '.clear-filter', function() {
        initData();
        $inputCompany.val('');
        $inputCountry.val('');
        $('.filter-result').html('')
    });

    $inputCompany.on('focus', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            page = 1;
            hasMore = true;
            $listCompany.removeClass('hidden').empty();
            fetchData(true, $inputCompany, $listCompany, 'company', $dropdownCompany);
        }, 300); // debounce 300ms
    });
    $inputCompany.on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            query = $inputCompany.val().trim();
            if (query.length < 2) {
                return;
            }
            page = 1;
            hasMore = true;
            $listCompany.removeClass('hidden').empty();
            fetchData(true, $inputCompany, $listCompany, 'company', $dropdownCompany);
        }, 300); // debounce 300ms
    });

    $listCompany.on('scroll', function() {
        var nearBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 10;
        
        if (nearBottom && !loading && hasMore) {
            fetchData(false, $inputCompany, $listCompany, 'company', $dropdownCompany);
        }
    });
    $inputCountry.on('focus', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            page = 1;
            hasMore = true;
            $listCountry.removeClass('hidden').empty();
            fetchData(true, $inputCountry, $listCountry, 'country', $dropdownCountry);
        }, 300); // debounce 300ms
    });
    $inputCountry.on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            query = $inputCountry.val().trim();
            if (query.length < 2) {
                return;
            }
            page = 1;
            hasMore = true;
            $listCountry.removeClass('hidden').empty();
            fetchData(true, $inputCountry, $listCountry, 'country', $dropdownCountry);
        }, 300); // debounce 300ms
    });

    $listCountry.on('scroll', function() {
        var nearBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 10;
        
        if (nearBottom && !loading && hasMore) {
            fetchData(false, $inputCountry, $listCountry, 'country', $dropdownCountry);
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#companyResult, #companyFilter').length) {
            // $listCompany.addClass('hidden');
        }
        else if (!$(e.target).closest('#countryResult, #countryFilter').length) {
            // $listCountry.addClass('hidden');
        }
    });
</script>