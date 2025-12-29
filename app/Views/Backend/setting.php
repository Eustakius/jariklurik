<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="dashboard-main-body min-h-screen bg-neutral-50 dark:bg-neutral-900">
    <?= view('Backend/Partial/page-header', ['title' => 'System Settings']) ?>
    
    <form action="<?= site_url($form['route']) ?>" method="post" data-parsley-validate enctype="multipart/form-data" class="mt-6">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="<?= $form['method'] ?>">
        
        <!-- Alerts -->
        <?php if (session()->has('errors-backend')): ?>
            <div class="col-span-12 mb-6 alert alert-danger bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border-danger-100 px-6 py-[11px] font-semibold rounded-lg" role="alert">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl mt-1.5 shrink-0"></iconify-icon>
                        <div>
                            <ul class="font-medium text-danger-600 text-sm mt-2">
                                <?php foreach (session('errors-backend') as $field => $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (session()->has('message-backend')): ?>
            <div class="col-span-12 mb-6 alert alert-success bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 border-success-600 border-start-width-4-px border-l-[3px] px-6 py-[13px] rounded-lg flex items-center justify-between" role="alert">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
                    <?= esc(session('message-backend')) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-12 gap-6">

            <!-- Global Site Configurations -->
            <div class="col-span-12">
                <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
                    <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600">
                        <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:building-outline" class="text-xl"></iconify-icon>
                            Global Site Configurations
                        </h6>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid grid-cols-12 gap-4">
                            <!-- Site Name -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="site_name">Site Name</label>
                                <input type="text" id="site_name" name="site_name" class="form-control" value="<?= esc($data->site_name ?? '') ?>" required>
                            </div>

                            <!-- Company Logo -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="company_logo">Company Logo</label>
                                <input type="file" id="company_logo" name="company_logo" class="form-control" accept=".jpg,.jpeg,.png,.gif,.svg" data-parsley-maxfilesize="2097152" data-parsley-maxfilesize-message="File size must not exceed 2 MB.">
                                <?php if(!empty($data->company_logo ?? '')): ?>
                                    <small class="text-neutral-600 dark:text-neutral-400">Current: <?= esc($data->company_logo) ?></small>
                                <?php endif; ?>
                            </div>

                            <!-- Company Email -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="company_email">Company Email</label>
                                <input type="email" id="company_email" name="company_email" class="form-control" value="<?= esc($data->company_email ?? '') ?>" required>
                            </div>

                            <!-- Company Phone -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="company_phone">Company Phone</label>
                                <input type="tel" id="company_phone" name="company_phone" class="form-control" value="<?= esc($data->company_phone ?? '') ?>">
                            </div>

                            <!-- Company Address -->
                            <div class="col-span-12">
                                <label class="form-label text-sm" for="company_address">Company Address</label>
                                <textarea id="company_address" name="company_address" class="form-control" rows="3"><?= esc($data->company_address ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO & Metadata -->
            <div class="col-span-12">
                <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
                    <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600">
                        <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:magnifer-outline" class="text-xl"></iconify-icon>
                            SEO & Metadata
                        </h6>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid grid-cols-12 gap-4">
                            <!-- Meta Title -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="meta_title">Meta Title</label>
                                <input type="text" id="meta_title" name="meta_title" class="form-control" value="<?= esc($data->meta_title ?? '') ?>" maxlength="60">
                                <small class="text-neutral-600 dark:text-neutral-400">Recommended: 50-60 characters</small>
                            </div>

                            <!-- Meta Keywords -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="meta_keywords">Meta Keywords</label>
                                <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" value="<?= esc($data->meta_keywords ?? '') ?>">
                                <small class="text-neutral-600 dark:text-neutral-400">Separate keywords with commas</small>
                            </div>

                            <!-- Meta Description -->
                            <div class="col-span-12">
                                <label class="form-label text-sm" for="meta_description">Meta Description</label>
                                <textarea id="meta_description" name="meta_description" class="form-control" rows="2" maxlength="160"><?= esc($data->meta_description ?? '') ?></textarea>
                                <small class="text-neutral-600 dark:text-neutral-400">Recommended: 150-160 characters</small>
                            </div>

                            <!-- OG Title -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="og_title">Open Graph Title</label>
                                <input type="text" id="og_title" name="og_title" class="form-control" value="<?= esc($data->og_title ?? '') ?>">
                            </div>

                            <!-- OG Type -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="og_type">Open Graph Type</label>
                                <select id="og_type" name="og_type" class="form-control">
                                    <option value="">-- Select Type --</option>
                                    <option value="website" <?= ($data->og_type ?? '') === 'website' ? 'selected' : '' ?>>Website</option>
                                    <option value="article" <?= ($data->og_type ?? '') === 'article' ? 'selected' : '' ?>>Article</option>
                                    <option value="business" <?= ($data->og_type ?? '') === 'business' ? 'selected' : '' ?>>Business</option>
                                </select>
                            </div>

                            <!-- OG Description -->
                            <div class="col-span-12">
                                <label class="form-label text-sm" for="og_description">Open Graph Description</label>
                                <textarea id="og_description" name="og_description" class="form-control" rows="2"><?= esc($data->og_description ?? '') ?></textarea>
                            </div>

                            <!-- OG Image -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="og_image_url">Open Graph Image</label>
                                <input type="file" id="og_image_url" name="og_image_url" class="form-control" accept=".jpg,.jpeg,.png" data-parsley-maxfilesize="2097152">
                                <?php if(!empty($data->og_image_url ?? '')): ?>
                                    <small class="text-neutral-600 dark:text-neutral-400">Current: <?= esc($data->og_image_url) ?></small>
                                <?php endif; ?>
                            </div>

                            <!-- Canonical URL -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="canonical_url">Canonical URL</label>
                                <input type="url" id="canonical_url" name="canonical_url" class="form-control" value="<?= esc($data->canonical_url ?? '') ?>">
                            </div>

                            <!-- Google Analytics -->
                            <div class="col-span-12">
                                <label class="form-label text-sm" for="google_analytics_code">Google Analytics Code</label>
                                <textarea id="google_analytics_code" name="google_analytics_code" class="form-control" rows="3" placeholder="Paste your GA4 tracking code here"><?= esc($data->google_analytics_code ?? '') ?></textarea>
                            </div>

                            <!-- Google Site Verification -->
                            <div class="col-span-12">
                                <label class="form-label text-sm" for="google_site_verification">Google Site Verification</label>
                                <input type="text" id="google_site_verification" name="google_site_verification" class="form-control" value="<?= esc($data->google_site_verification ?? '') ?>" placeholder="e.g., google-site-verification=xxxx">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Localization -->
            <div class="col-span-12">
                <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
                    <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600">
                        <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:global-outline" class="text-xl"></iconify-icon>
                            Localization
                        </h6>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid grid-cols-12 gap-4">
                            <!-- Default Language -->
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label text-sm" for="default_language">Default Language</label>
                                <select id="default_language" name="default_language" class="form-control">
                                    <option value="">-- Select Language --</option>
                                    <option value="en" <?= ($data->default_language ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                                    <option value="id" <?= ($data->default_language ?? '') === 'id' ? 'selected' : '' ?>>Indonesian (Bahasa Indonesia)</option>
                                    <option value="ms" <?= ($data->default_language ?? '') === 'ms' ? 'selected' : '' ?>>Malay (Bahasa Melayu)</option>
                                </select>
                            </div>

                            <!-- Default Currency -->
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label text-sm" for="default_currency">Default Currency</label>
                                <input type="text" id="default_currency" name="default_currency" class="form-control" value="<?= esc($data->default_currency ?? 'USD') ?>" placeholder="USD, IDR, MYR">
                            </div>

                            <!-- Default Timezone -->
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label text-sm" for="default_timezone">Default Timezone</label>
                                <select id="default_timezone" name="default_timezone" class="form-control">
                                    <option value="">-- Select Timezone --</option>
                                    <option value="UTC" <?= ($data->default_timezone ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                    <option value="Asia/Jakarta" <?= ($data->default_timezone ?? '') === 'Asia/Jakarta' ? 'selected' : '' ?>>Jakarta (UTC+7)</option>
                                    <option value="Asia/Kuala_Lumpur" <?= ($data->default_timezone ?? '') === 'Asia/Kuala_Lumpur' ? 'selected' : '' ?>>Kuala Lumpur (UTC+8)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System & Maintenance -->
            <div class="col-span-12">
                <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
                    <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600">
                        <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:settings-minimalistic-outline" class="text-xl"></iconify-icon>
                            System & Maintenance
                        </h6>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid grid-cols-12 gap-4">
                            <!-- Maintenance Mode -->
                            <div class="col-span-12 md:col-span-6">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" class="form-checkbox" value="1" <?= (!empty($data->maintenance_mode ?? null)) ? 'checked' : '' ?>>
                                    <label for="maintenance_mode" class="form-label text-sm mb-0">Enable Maintenance Mode</label>
                                </div>
                                <small class="text-danger-600 dark:text-danger-400">Warning: This will make the site inaccessible to visitors</small>
                            </div>

                            <!-- Auto Backup -->
                            <div class="col-span-12 md:col-span-6">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="auto_backup_enabled" name="auto_backup_enabled" class="form-checkbox" value="1" <?= (!empty($data->auto_backup_enabled ?? null)) ? 'checked' : '' ?>>
                                    <label for="auto_backup_enabled" class="form-label text-sm mb-0">Enable Automatic Backups</label>
                                </div>
                            </div>

                            <!-- Maintenance Message -->
                            <div class="col-span-12">
                                <label class="form-label text-sm" for="maintenance_message">Maintenance Message</label>
                                <textarea id="maintenance_message" name="maintenance_message" class="form-control" rows="3" placeholder="Message to display to visitors during maintenance"><?= esc($data->maintenance_message ?? '') ?></textarea>
                            </div>

                            <!-- Backup Frequency -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="backup_frequency">Backup Frequency</label>
                                <select id="backup_frequency" name="backup_frequency" class="form-control">
                                    <option value="">-- Select Frequency --</option>
                                    <option value="daily" <?= ($data->backup_frequency ?? '') === 'daily' ? 'selected' : '' ?>>Daily</option>
                                    <option value="weekly" <?= ($data->backup_frequency ?? '') === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                                    <option value="monthly" <?= ($data->backup_frequency ?? '') === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Server (SMTP) -->
            <div class="col-span-12">
                <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
                    <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600">
                        <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:letter-outline" class="text-xl"></iconify-icon>
                            Email Server Configuration
                        </h6>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid grid-cols-12 gap-4">
                            <!-- SMTP Host -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="smtp_host">SMTP Host</label>
                                <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="<?= esc($data->smtp_host ?? '') ?>" placeholder="e.g., smtp.gmail.com">
                            </div>

                            <!-- SMTP Port -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="smtp_port">SMTP Port</label>
                                <input type="number" id="smtp_port" name="smtp_port" class="form-control" value="<?= esc($data->smtp_port ?? '587') ?>" placeholder="587 or 465">
                            </div>

                            <!-- SMTP Username -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="smtp_username">SMTP Username</label>
                                <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="<?= esc($data->smtp_username ?? '') ?>">
                            </div>

                            <!-- SMTP Password -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="smtp_password">SMTP Password</label>
                                <input type="password" id="smtp_password" name="smtp_password" class="form-control" placeholder="Leave blank to keep current password">
                            </div>

                            <!-- SMTP Encryption -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="smtp_encryption">SMTP Encryption</label>
                                <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                                    <option value="">-- Select Encryption --</option>
                                    <option value="tls" <?= ($data->smtp_encryption ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                    <option value="ssl" <?= ($data->smtp_encryption ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                </select>
                            </div>

                            <!-- From Email -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="from_email">From Email Address</label>
                                <input type="email" id="from_email" name="from_email" class="form-control" value="<?= esc($data->from_email ?? '') ?>">
                            </div>

                            <!-- From Name -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="from_name">From Name</label>
                                <input type="text" id="from_name" name="from_name" class="form-control" value="<?= esc($data->from_name ?? '') ?>" placeholder="e.g., Jariklurik Admin">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security & Authentication -->
            <div class="col-span-12">
                <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
                    <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600">
                        <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:lock-outline" class="text-xl"></iconify-icon>
                            Security & Authentication
                        </h6>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid grid-cols-12 gap-4">
                            <!-- Require Strong Passwords -->
                            <div class="col-span-12 md:col-span-6">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="require_password_strength" name="require_password_strength" class="form-checkbox" value="1" <?= (!empty($data->require_password_strength ?? null)) ? 'checked' : '' ?>>
                                    <label for="require_password_strength" class="form-label text-sm mb-0">Require Strong Passwords</label>
                                </div>
                                <small class="text-neutral-600 dark:text-neutral-400">Enforce uppercase, lowercase, numbers, and symbols</small>
                            </div>

                            <!-- Enable MFA -->
                            <div class="col-span-12 md:col-span-6">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="enable_mfa" name="enable_mfa" class="form-checkbox" value="1" <?= (!empty($data->enable_mfa ?? null)) ? 'checked' : '' ?>>
                                    <label for="enable_mfa" class="form-label text-sm mb-0">Enable Multi-Factor Authentication</label>
                                </div>
                            </div>

                            <!-- Minimum Password Length -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="password_min_length">Minimum Password Length</label>
                                <input type="number" id="password_min_length" name="password_min_length" class="form-control" value="<?= esc($data->password_min_length ?? '8') ?>" min="6" max="32">
                            </div>

                            <!-- Session Timeout -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-sm" for="session_timeout">Session Timeout (minutes)</label>
                                <input type="number" id="session_timeout" name="session_timeout" class="form-control" value="<?= esc($data->session_timeout ?? '30') ?>" min="5" max="1440">
                                <small class="text-neutral-600 dark:text-neutral-400">Auto logout after inactivity</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Statement Letter (Legacy) -->
            <div class="col-span-12">
                <div class="card border border-neutral-200 dark:border-neutral-600 shadow-md rounded-2xl bg-white dark:bg-neutral-800">
                    <div class="card-header pb-0 px-6 py-4 border-b border-neutral-200 dark:border-neutral-600">
                        <h6 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 mb-0 flex items-center gap-2">
                            <iconify-icon icon="solar:file-text-outline" class="text-xl"></iconify-icon>
                            Applicant Settings
                        </h6>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12">
                                <label class="form-label text-sm" for="file_statement_letter">Statement Letter Template</label>
                                <input type="file" id="file_statement_letter" name="file_statement_letter" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" data-parsley-maxfilesize="1048576" data-parsley-maxfilesize-message="File size must not exceed 1 MB.">
                                <small class="text-neutral-600 dark:text-neutral-400">Max 1 MB | .pdf, .doc, .docx, .jpg, .jpeg, .png</small>
                                <?php if(!empty($data->file_statement_letter ?? '')): ?>
                                    <div class="mt-2 text-neutral-600 dark:text-neutral-400">Current: <?= esc($data->file_statement_letter) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <?php if (strtolower($param['action']) != "detail"): ?>
                <div class="col-span-12">
                    <div class="flex items-center justify-end gap-3">
                        <a href="<?= site_url('/back-end/dashboard') ?>" class="btn bg-neutral-200 text-neutral-800 hover:bg-neutral-300 dark:bg-neutral-700 dark:text-neutral-100 rounded-lg px-6 py-2">
                            Cancel
                        </a>
                        <button type="submit" class="btn bg-primary-600 text-white hover:bg-primary-700 rounded-lg px-6 py-2 flex items-center gap-2">
                            <iconify-icon icon="solar:save-outline" class="text-lg"></iconify-icon>
                            Save Settings
                        </button>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-save draft functionality (optional)
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('change', function() {
                // You can implement auto-save here
                console.log('Form changed - consider auto-saving');
            });
        }
    });
</script>

<?= $this->endSection() ?>