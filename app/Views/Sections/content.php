<?php
$current_url = current_url();
$title = urlencode("Lihat halaman menarik ini!");
$path = parse_url($current_url, PHP_URL_PATH);
$parts = explode('-', $path);
$lastPart = end($parts);
?>
<div class="bg-[#EBC470] px-7 md:px-[9.063rem] tab-content py-7 md:py-12">
    <div class="relative shadow-lg bg-white rounded-[15px] p-5 md:p-10 flex flex-col gap-4 md:gap-7">
        <a href="/" class="text-sm md:text-base text-[#714D00] flex flex-row items-center gap-3 w-1/2 md:w-1/4">
            <img src="/icon/jariklurik-arrow-left.png" class="h-[0.638rem] md:h-[0.938rem] w-auto" />
            <span>Kembali Ke Beranda</span>
        </a>
        <div data-dial-init class="absolute top-5 end-5 md:top-6 md:end-10 group">
            <button type="button" data-dial-toggle="speed-dial-menu-top-right" aria-controls="speed-dial-menu-top-right" aria-expanded="false" class="flex items-center justify-center text-white !bg-[#714D00] rounded-full w-8 h-8 md:w-14 md:h-14 hover:bg-[#EBC470] dark:bg-blue-600 dark:hover:bg-[#EBC470] focus:ring-4 focus:ring-[#EBC470] focus:outline-none dark:focus:ring-[#EBC470]">
                <svg class="w-3 h-3 md:w-5 md:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M14.419 10.581a3.564 3.564 0 0 0-2.574 1.1l-4.756-2.49a3.54 3.54 0 0 0 .072-.71 3.55 3.55 0 0 0-.043-.428L11.67 6.1a3.56 3.56 0 1 0-.831-2.265c.006.143.02.286.043.428L6.33 6.218a3.573 3.573 0 1 0-.175 4.743l4.756 2.491a3.58 3.58 0 1 0 3.508-2.871Z" />
                </svg>
                <span class="sr-only">Open actions menu</span>
            </button>
            <div id="speed-dial-menu-top-right" class="flex flex-col items-center hidden mt-4 space-y-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($current_url) ?>"
                    target="_blank" type="button" data-tooltip-target="tooltip-facebook" data-tooltip-placement="left" class="flex justify-center items-center w-8 h-8 md:w-14 md:h-14 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-xs dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-8 md:h-8" viewBox="0 0 256 256">
                        <path fill="#1877f2" d="M256 128C256 57.308 198.692 0 128 0S0 57.308 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.348-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.959 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445" />
                        <path fill="#fff" d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A129 129 0 0 0 128 256a129 129 0 0 0 20-1.555V165z" />
                    </svg>
                </a>
                <div id="tooltip-facebook" role="tooltip" class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                    Facebook
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode($current_url) ?>&text=<?= $title ?>"
                    target="_blank" type="button" data-tooltip-target="tooltip-twitter" data-tooltip-placement="left" class="flex justify-center items-center w-8 h-8 md:w-14 md:h-14 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-xs dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-8 md:h-8" viewBox="0 0 14 14">
                        <g fill="none">
                            <g clip-path="url(#SVGG1Ot4cAD)">
                                <path fill="currentColor" d="M11.025.656h2.147L8.482 6.03L14 13.344H9.68L6.294 8.909l-3.87 4.435H.275l5.016-5.75L0 .657h4.43L7.486 4.71zm-.755 11.4h1.19L3.78 1.877H2.504z" />
                            </g>
                            <defs>
                                <clipPath id="SVGG1Ot4cAD">
                                    <path fill="#fff" d="M0 0h14v14H0z" />
                                </clipPath>
                            </defs>
                        </g>
                    </svg>
                </a>
                <div id="tooltip-twitter" role="tooltip" class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                    Twitter
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <a href="https://api.whatsapp.com/send?text=<?= $title ?>%20<?= urlencode($current_url) ?>"
                    target="_blank" type="button" data-tooltip-target="tooltip-whatsapp" data-tooltip-placement="left" class="flex justify-center items-center w-8 h-8 md:w-14 md:h-14 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-xs dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-8 md:h-8" viewBox="0 0 256 258">
                        <defs>
                            <linearGradient id="SVGBRLHCcSy" x1="50%" x2="50%" y1="100%" y2="0%">
                                <stop offset="0%" stop-color="#1faf38" />
                                <stop offset="100%" stop-color="#60d669" />
                            </linearGradient>
                            <linearGradient id="SVGHW6lecxh" x1="50%" x2="50%" y1="100%" y2="0%">
                                <stop offset="0%" stop-color="#f9f9f9" />
                                <stop offset="100%" stop-color="#fff" />
                            </linearGradient>
                        </defs>
                        <path fill="url(#SVGBRLHCcSy)" d="M5.463 127.456c-.006 21.677 5.658 42.843 16.428 61.499L4.433 252.697l65.232-17.104a123 123 0 0 0 58.8 14.97h.054c67.815 0 123.018-55.183 123.047-123.01c.013-32.867-12.775-63.773-36.009-87.025c-23.23-23.25-54.125-36.061-87.043-36.076c-67.823 0-123.022 55.18-123.05 123.004" />
                        <path fill="url(#SVGHW6lecxh)" d="M1.07 127.416c-.007 22.457 5.86 44.38 17.014 63.704L0 257.147l67.571-17.717c18.618 10.151 39.58 15.503 60.91 15.511h.055c70.248 0 127.434-57.168 127.464-127.423c.012-34.048-13.236-66.065-37.3-90.15C194.633 13.286 162.633.014 128.536 0C58.276 0 1.099 57.16 1.071 127.416m40.24 60.376l-2.523-4.005c-10.606-16.864-16.204-36.352-16.196-56.363C22.614 69.029 70.138 21.52 128.576 21.52c28.3.012 54.896 11.044 74.9 31.06c20.003 20.018 31.01 46.628 31.003 74.93c-.026 58.395-47.551 105.91-105.943 105.91h-.042c-19.013-.01-37.66-5.116-53.922-14.765l-3.87-2.295l-40.098 10.513z" />
                        <path fill="#fff" d="M96.678 74.148c-2.386-5.303-4.897-5.41-7.166-5.503c-1.858-.08-3.982-.074-6.104-.074c-2.124 0-5.575.799-8.492 3.984c-2.92 3.188-11.148 10.892-11.148 26.561s11.413 30.813 13.004 32.94c1.593 2.123 22.033 35.307 54.405 48.073c26.904 10.609 32.379 8.499 38.218 7.967c5.84-.53 18.844-7.702 21.497-15.139c2.655-7.436 2.655-13.81 1.859-15.142c-.796-1.327-2.92-2.124-6.105-3.716s-18.844-9.298-21.763-10.361c-2.92-1.062-5.043-1.592-7.167 1.597c-2.124 3.184-8.223 10.356-10.082 12.48c-1.857 2.129-3.716 2.394-6.9.801c-3.187-1.598-13.444-4.957-25.613-15.806c-9.468-8.442-15.86-18.867-17.718-22.056c-1.858-3.184-.199-4.91 1.398-6.497c1.431-1.427 3.186-3.719 4.78-5.578c1.588-1.86 2.118-3.187 3.18-5.311c1.063-2.126.531-3.986-.264-5.579c-.798-1.593-6.987-17.343-9.819-23.64" />
                    </svg>
                </a>
                <div id="tooltip-whatsapp" role="tooltip" class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                    WhatsApp
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <a href="https://t.me/share/url?url=<?= urlencode($current_url) ?>&text=<?= $title ?>"
                    target="_blank" type="button" data-tooltip-target="tooltip-telegram" data-tooltip-placement="left" class="flex justify-center items-center w-8 h-8 md:w-14 md:h-14 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 dark:hover:text-white shadow-xs dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-8 md:h-8" viewBox="0 0 256 256">
                        <defs>
                            <linearGradient id="SVGuySfwdaH" x1="50%" x2="50%" y1="0%" y2="100%">
                                <stop offset="0%" stop-color="#2aabee" />
                                <stop offset="100%" stop-color="#229ed9" />
                            </linearGradient>
                        </defs>
                        <path fill="url(#SVGuySfwdaH)" d="M128 0C94.06 0 61.48 13.494 37.5 37.49A128.04 128.04 0 0 0 0 128c0 33.934 13.5 66.514 37.5 90.51C61.48 242.506 94.06 256 128 256s66.52-13.494 90.5-37.49c24-23.996 37.5-56.576 37.5-90.51s-13.5-66.514-37.5-90.51C194.52 13.494 161.94 0 128 0" />
                        <path fill="#fff" d="M57.94 126.648q55.98-24.384 74.64-32.152c35.56-14.786 42.94-17.354 47.76-17.441c1.06-.017 3.42.245 4.96 1.49c1.28 1.05 1.64 2.47 1.82 3.467c.16.996.38 3.266.2 5.038c-1.92 20.24-10.26 69.356-14.5 92.026c-1.78 9.592-5.32 12.808-8.74 13.122c-7.44.684-13.08-4.912-20.28-9.63c-11.26-7.386-17.62-11.982-28.56-19.188c-12.64-8.328-4.44-12.906 2.76-20.386c1.88-1.958 34.64-31.748 35.26-34.45c.08-.338.16-1.598-.6-2.262c-.74-.666-1.84-.438-2.64-.258c-1.14.256-19.12 12.152-54 35.686c-5.1 3.508-9.72 5.218-13.88 5.128c-4.56-.098-13.36-2.584-19.9-4.708c-8-2.606-14.38-3.984-13.82-8.41c.28-2.304 3.46-4.662 9.52-7.072" />
                    </svg>
                </a>
                <div id="tooltip-telegram" role="tooltip" class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                    Telegram
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($current_url) ?>"
                    target="_blank" type="button" data-tooltip-target="tooltip-linkedin" data-tooltip-placement="left" class="flex justify-center items-center w-8 h-8 md:w-14 md:h-14 text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 dark:hover:text-white shadow-xs dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-8 md:h-8" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M10 .4C4.698.4.4 4.698.4 10s4.298 9.6 9.6 9.6s9.6-4.298 9.6-9.6S15.302.4 10 .4M7.65 13.979H5.706V7.723H7.65zm-.984-7.024c-.614 0-1.011-.435-1.011-.973c0-.549.409-.971 1.036-.971s1.011.422 1.023.971c0 .538-.396.973-1.048.973m8.084 7.024h-1.944v-3.467c0-.807-.282-1.355-.985-1.355c-.537 0-.856.371-.997.728c-.052.127-.065.307-.065.486v3.607H8.814v-4.26c0-.781-.025-1.434-.051-1.996h1.689l.089.869h.039c.256-.408.883-1.01 1.932-1.01c1.279 0 2.238.857 2.238 2.699z" />
                    </svg>
                </a>
                <div id="tooltip-linkedin" role="tooltip" class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                    LinkedIn
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
        </div>
        <div class="border border-[#714D00] "></div>
        <div class="flex flex-col md:flex-row gap-5">
            <div class="w-full md:w-[51.063rem] flex flex-col gap-4 md:gap-7">
                <div class="flex flex-wrap items-center justify-between">
                    <div class="flex gap-5">
                        <img src="<?= $data->company->logo ?>" class="h-[3.625rem] md:h-[5.625rem] w-[3.625rem] md:w-[5.625rem] object-cover border border-solid border-[#CCCCCC] rounded-[15px]"></img>
                        <div class="flex flex-col justify-center">
                            <h1 class="font-bold text-lg md:text-2xl"><?= $data->position ?></h1>
                            <p class="text-sm md:text-lg"><?= $data->company->name ?></p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4 md:gap-14">
                    <div class="flex-1 flex flex-col gap-1 md:gap-4">
                        <h3 class="font-bold text-sm md:text-lg">Durasi Kontrak</h3>
                        <p class="text-sm md:text-lg"><?= $data->duration ?></p>
                    </div>
                    <div class="flex flex flex-col gap-1 md:gap-4">
                        <h3 class="font-bold text-sm md:text-lg">Kuota</h3>
                        <div class="flex flex-row gap-4 md:gap-7">
                            <div class="flex-1 flex flex-row gap-2">
                                <img src="/icon/jariklurik-male-gender.png" class="h-[1rem] md:h-[1.563rem] w-auto" />
                                <p class="text-sm md:text-lg"><?= $data->malequota ?></p>
                            </div>
                            <div class="flex-1 flex flex-row gap-2">
                                <img src="/icon/jariklurik-female.png" class="h-[1rem] md:h-[1.563rem] w-auto" />
                                <p class="text-sm md:text-lg"><?= $data->femalequota ?></p>
                            </div>
                            <div class="flex flex-row gap-2">
                                <img src="/icon/jariklurik-male-and-female-signs.png" class="h-[1rem] md:h-[1.563rem] w-full" />
                                <p class="text-sm md:text-lg"><?= $data->unisexquota ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col gap-1 md:gap-4">
                        <h3 class="font-bold text-sm md:text-lg">Tanggal Seleksi</h3>
                        <p class="text-sm md:text-lg"><?= $data->selection ?></p>
                    </div>
                    <div class="flex-1 flex flex-col gap-1 md:gap-4">
                        <h3 class="font-bold text-sm md:text-lg">Lokasi </h3>
                        <p class="text-sm md:text-lg"><?= $data->country ?></p>
                    </div>
                </div>
                <div class="border border-[#714D00] "></div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mt-8 w-full">
                        <div class="bg-red-100 text-red-700 p-3 rounded">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div id="fadeJob" class="<?= session()->getFlashdata('error') ? 'hidden opacity-0' : 'opacity-1' ?> transition-opacity duration-500 ease-in-out">
                    <div class="flex flex-col justify-center gap-1 md:gap-2">
                        <h2 class="text-lg md:text-2xl font-bold">Deskripsi Pekerjaan</h2>
                        <div class="editor !font-inter text-sm md:text-lg [&_ul]:leading-normal "><?= $data->description ?></div>
                    </div>
                    <div class="flex flex-col justify-center gap-1 md:gap-2">
                        <h2 class="text-lg md:text-2xl font-bold">Persyaratan</h2>
                        <div class="editor !font-inter text-sm md:text-lg [&_ul]:leading-normal"><?= $data->requirement ?></div>
                    </div>
                </div>
                <?php if ($data->malequota > 0 || $data->femalequota > 0 || $data->unisexquota > 0): ?>
                    <div id="fadeJobApply" class="<?= session()->getFlashdata('error') ? 'opacity-1' : 'hidden opacity-0' ?> transition-opacity duration-500 ease-in-out">
                        <form id="application-form" class="pr-0 md:pr-14 text-sm md:text-lg flex flex-col gap-8" action="<?= base_url('api/submit/applicant') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                            <input type="hidden" name="token" value="<?= $token ?>">
                            <input type="hidden" name="slug" value="<?= $data->slug ?>">
                            <?= csrf_field() ?>

                            <h2 class="text-lg md:text-2xl font-bold mt-8 md:mt-0">Informasi Pribadi</h2>
                            <div class="flex flex-wrap gap-x-[60px] gap-y-8 w-full">
                                <div class="w-full md:w-[calc(50%-30px)]">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="first_name" class="form-control" required value="<?= old('first_name') ?>" data-parsley-maxlength="255" maxlength="255">
                                            <label class="form-label">Nama Depan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-[calc(50%-30px)]">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="last_name" class="form-control" value="<?= old('last_name') ?>" data-parsley-maxlength="255" maxlength="255">
                                            <label class="form-label">Nama Belakang</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-[calc(50%-30px)]">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="date" name="birth_of_day" class="form-control" value="<?= old('birth_of_day') ?>" required>
                                            <label class="form-label">Tanggal Lahir</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full md:w-[calc(50%-30px)]">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control" required name="education_level">
                                                <option value=""></option>
                                                <option <?= old('education_level') == "SD" ? "selected" : "" ?> value="SD">SD</option>
                                                <option <?= old('education_level') == "SMP" ? "selected" : "" ?> value="SMP">SMP</option>
                                                <option <?= old('education_level') == "SMA" ? "selected" : "" ?> value="SMA">SMA</option>
                                                <option <?= old('education_level') == "SMK" ? "selected" : "" ?> value="SMK">SMK</option>
                                                <option <?= old('education_level') == "D1" ? "selected" : "" ?> value="D1">Diploma 1</option>
                                                <option <?= old('education_level') == "D2" ? "selected" : "" ?> value="D2">Diploma 2</option>
                                                <option <?= old('education_level') == "D3" ? "selected" : "" ?> value="D3">Diploma 3</option>
                                                <option <?= old('education_level') == "D4" ? "selected" : "" ?> value="D4">Diploma 4</option>
                                                <option <?= old('education_level') == "S1" ? "selected" : "" ?> value="S1">Sarjana (S1)</option>
                                                <option <?= old('education_level') == "S2" ? "selected" : "" ?> value="S2">Magister (S2)</option>
                                                <option <?= old('education_level') == "S3" ? "selected" : "" ?> value="S3">Doktor (S3)</option>
                                            </select>
                                            <label class="form-label">Pendidikan Terakhir</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-[calc(50%-30px)]">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>"
                                                data-parsley-minlength="5"
                                                data-parsley-maxlength="20"
                                                maxlength="20"
                                                required data-parsley-phoneid data-parsley-trigger="change">
                                            <label class="form-label">No Handphone</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-[calc(50%-30px)]">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control" id="gender" required name="gender">
                                                <option value=""></option>
                                                <option <?= old('gender') == "M" ? "selected" : "" ?> value="M">Pria</option>
                                                <option <?= old('gender') == "F" ? "selected" : "" ?> value="F">Wanita</option>
                                            </select>
                                            <label class="form-label">Jenis Kelamin</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-[calc(50%-30px)]">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required data-parsley-maxlength="255" maxlength="255">
                                            <label class="form-label">Email</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h2 class="text-lg md:text-2xl font-bold mt-16">Upload Dokumen</h2>
                            <div class="flex flex-col gap-6 mb-16 w-full">
                                <?php 
                                    $reqDocs = !empty($data->required_documents) ? $data->required_documents : ['cv'];
                                    // Handle case if empty
                                    if(empty($reqDocs)) $reqDocs = ['cv'];
                                    
                                    $docLabels = [
                                        'cv' => 'CV / Resume',
                                        'language_cert' => 'Sertifikat Bahasa',
                                        'skill_cert' => 'Sertifikat Keahlian',
                                        'other' => 'Dokumen Pendukung Lainnya'
                                    ];
                                ?>
                                <?php foreach($reqDocs as $key): ?>
                                    <?php $label = $docLabels[$key] ?? $key; ?>
                                    <div class="w-full">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <label for="file_<?= $key ?>" class="file-upload w-full justify-between flex form-control focused cursor-pointer items-center" id="trigger-<?= $key ?>">
                                                    <div id="file-info-<?= $key ?>" class="mt-2 flex">
                                                        <span id="file-name-<?= $key ?>"></span>
                                                        <span id="remove-file-<?= $key ?>" class="ml-2 text-red-600 cursor-pointer hover:underline hidden" onclick="removeFile('<?= $key ?>'); return false;">Remove</span>
                                                    </div>
                                                    <img src="/icon/jariklurik-upload.png" class="h-[1.563rem] md:h-[1.563rem] w-auto">
                                                </label>
                                                <input type="file" id="file_<?= $key ?>" name="<?= $key ?>" class="form-control hidden doc-input" required
                                                    data-parsley-maxfilesize="2097152"
                                                    data-parsley-maxfilesize-message="Maksimal 2 MB"
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                    data-parsley-fileextension="pdf,doc,docx,jpg,jpeg,png"
                                                    data-parsley-fileextension-message="Tipe file hanya .pdf, .doc, .docx, .jpg, .jpeg, .png"
                                                    onchange="handleFileSelect(this, '<?= $key ?>')">
                                                <label for="file_<?= $key ?>" class="form-label">Upload <?= $label ?></label>
                                            </div>
                                                <i class="text-xs md:text-sm text-[#B94A48]">Maksimal 2 MB dan tipe file hanya .pdf, .doc, .docx, .jpg, .jpeg, .png</i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <script>
                                function handleFileSelect(input, key) {
                                    const file = input.files[0];
                                    if (file) {
                                        document.getElementById('file-name-' + key).textContent = file.name;
                                        document.getElementById('remove-file-' + key).classList.remove('hidden');
                                    }
                                }
                                function removeFile(key) {
                                    const input = document.getElementById('file_' + key);
                                    input.value = '';
                                    document.getElementById('file-name-' + key).textContent = '';
                                    document.getElementById('remove-file-' + key).classList.add('hidden');
                                }
                            </script>
                            <div class="flex flex-col">
                                <label>Captcha</label>
                                <div class="flex flex-row gap-4">
                                    <div class="flex-1">
                                        <img src="/captcha" id="captcha-img" class="w-full">
                                        <a class="text-[#714D00]" href="#" onclick="document.getElementById('captcha-img').src='/captcha?'+Date.now(); return false;">Refresh captcha</a>
                                    </div>
                                    <div class="flex-1">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="captcha" class="form-control" required>
                                                <label class="form-label">Masukkan captcha</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ====== BUTTON ====== -->
                            <button class="w-full text-base text-[#714D00] font-bold rounded-[10px] border-[3px] border-[#714D00] py-2 px-5 shadow-[4px_4px_0_0_rgba(113,77,0,1)]">
                                Submit
                            </button>
                        </form>

                    </div>
                <?php endif; ?>
            </div>
            <div class="flex-1">
                <div class="flex flex-col justify-center gap-4 md:gap-8 bg-[#EDEDED] rounded-[5px] p-6 md:px-6 md:py-10">
                    <div class="flex flex-col justify-center gap-1 md:gap-2">
                        <h3 class="text-sm md:text-base font-bold">Tentang Perusahaan</h3>
                        <p class="text-base"><?= $data->company->about ?></p>
                    </div>
                    <div class="flex flex-col justify-center gap-1 md:gap-2">
                        <h3 class="text-sm md:text-base font-bold">Alamat Kantor</h3>
                        <p class="text-base"><?= $data->company->address ?></p>
                    </div>
                    <div class="flex flex-col justify-center gap-1 md:gap-2">
                        <h3 class="text-sm md:text-base font-bold">Industri</h3>
                        <p class="text-base"><?= $data->company->sector ?></p>
                    </div>
                    <div class="flex flex-col justify-center gap-1 md:gap-2">
                        <h3 class="text-sm md:text-base font-bold">Kontak Perusahaan</h3>
                        <div class="flex flex-col justify-center gap-1">
                            <p class="text-base">Email</p>
                            <p class="text-base"><?= empty($data->email) ? "-" : $data->email ?></p>
                        </div>
                        <div class="flex flex-col justify-center gap-1">
                            <p class="text-base">Telephone</p>
                            <a href="https://wa.me/<?= $data->company->phone ?>" target="_blank" class="w-full flex justify-start items-center gap-2">
                                <span class="logos--whatsapp-icon"></span>
                                <p class="text-base"><?= $data->company->phone ?></p>
                            </a>
                        </div>
                    </div>
                    <?php if ($data->malequota > 0 || $data->femalequota > 0 || $data->unisexquota > 0): ?>
                        <button id="toggleBtnApply" class="w-full text-base text-[#714D00] font-bold rounded-[10px] border-[3px] border-[#714D00] py-2 px-5 shadow-[4px_4px_0_0_rgba(113,77,0,1)]"><?= session()->getFlashdata('error') ? 'Persyaratan & Deskripsi' : 'Apply Loker' ?></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        console.log("Page Loaded. Form script ready.");
        
        const $fadeJob = $('#fadeJob');
        const $fadeJobApply = $('#fadeJobApply');
        const $btn = $('#toggleBtnApply');

        // AJAX Form Submission
        $('#application-form').on('submit', function(e) {
            console.log("Form submission triggered");
            e.preventDefault(); // Stop default submission
            
            var $form = $(this);
            var formData = new FormData(this);
            var $submitBtn = $form.find('button[type="submit"]'); // Assuming the button inside form is submit

            // Basic Parsley validation check if parsely is used
            if ($form.parsley && !$form.parsley().isValid()) {
                return;
            }

            $submitBtn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    $submitBtn.prop('disabled', false).text('Submit');
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonColor: '#714D00'
                        }).then((result) => {
                            // Redirect to thank you page or reload
                            window.location.href = '/thank-you-registered'; 
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message,
                            confirmButtonColor: '#d33'
                        });
                        // Refresh captcha if needed
                        document.getElementById('captcha-img').src='/captcha?'+Date.now();
                    }
                },
                error: function(xhr, status, error) {
                    $submitBtn.prop('disabled', false).text('Submit');
                    console.error("AJAX Error:", status, error);
                    console.error("Response:", xhr.responseText);
                    
                    let errorMessage = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                        // Handle specific CSRF error if needed (CI4 usually returns 403)
                         if (xhr.responseJSON.error) {
                            errorMessage += ' (' + xhr.responseJSON.error + ')';
                        }
                    } else if (xhr.status === 403) {
                         errorMessage = 'Sesi Anda telah berakhir atau token tidak valid. Silakan refresh halaman.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage,
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });

        // Toggle Button Logic
        $btn.off('click').on('click', function (e) {
            e.preventDefault();
            console.log("Toggle button clicked");
            
            if ($fadeJob.hasClass('opacity-0') || $fadeJob.is(':hidden')) {
                // Show Description, Hide Form
                $(this).text('Apply Loker');
                
                $fadeJobApply.removeClass('opacity-100').addClass('opacity-0');
                
                setTimeout(() => {
                    $fadeJobApply.addClass('hidden');
                    $fadeJob.removeClass('hidden');
                    // Small delay to allow display:block to apply before opacity transition
                    setTimeout(() => {
                        $fadeJob.removeClass('opacity-0').addClass('opacity-100');
                    }, 50);
                }, 500);

            } else {
                // Show Form, Hide Description
                $(this).text('Persyaratan & Deskripsi');
                
                $fadeJob.removeClass('opacity-100').addClass('opacity-0');
                
                setTimeout(() => {
                    $fadeJob.addClass('hidden');
                    $fadeJobApply.removeClass('hidden');
                    setTimeout(() => {
                        $fadeJobApply.removeClass('opacity-0').addClass('opacity-100');
                    }, 50);
                }, 500);
            }
        });
    });
</script>