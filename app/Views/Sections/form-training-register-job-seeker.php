<div class="bg-[#EBC470] px-7 md:px-[16.813rem] tab-content pt-12">
  <div class="flex flex-wrap items-center justify-between mb-6">
    <div class="flex flex-row gap-4 items-center">
      <img src="/image/jariklurik-icon-kepelatihan.png" class="h-[2.563rem] md:h-[3.563rem] w-[2.563rem] md:w-[3.563rem]" alt="Jariklurik info menakir luar negeri">
      <p class="font-bold text-xl md:text-[2rem] text-[#74430D]">Daftar Kepelatihan</p>
    </div>
  </div>

  <div class="shadow-lg bg-white flex flex-col rounded-[0.938rem] w-full pb-16">
    <div class="flex flex-wrap items-center gap-4 justify-between p-7 md:px-14 md:pt-14 md:pb-11">
      <div class="flex gap-5">
        <p class="text-lg md:text-2xl font-bold">Saya Adalah :</p>
      </div>
      <div class="flex gap-5 md:gap-16">
        <span>
          <input name="type" type="radio" id="option1" checked class="post-back with-gap" value="daftar-kepelatihan-pencari-kerja" />
          <label for="option1" class="!text-lg !md:text-[2rem] !font-bold !text-black">Pencari kerja</label>
        </span>
        <span>
          <input name="type" type="radio" id="option2" class="post-back with-gap" value="daftar-kepelatihan-purna-pmi" />
          <label for="option2" class="!text-lg !md:text-[2rem] !font-bold !text-black">Purna PMI</label>
        </span>
      </div>
    </div>

    <div class="border border-[#714D00]"></div>
    <?php if (empty($data_master['training_type'])): ?>
      <div class="p-5 md:p-10 flex flex-col gap-4 md:gap-7 justify-center items-center">
        <img src="/icon/jariklurik-hands.png" class="h-[5.75rem] md:h-[5.75rem] w-[5.75rem]" />
        <p class="text-lg md:text-2xl font-bold">Mohon maaf saat ini pelatihan Pencari Kerja belum tersedia.</p>
      </div>
    <?php else: ?>
      <form class="px-7 md:px-14 text-sm md:text-lg flex flex-col gap-8" action="/submit/job-seeker" method="post" enctype="multipart/form-data" data-parsley-validate>
        <input type="hidden" name="token" value="<?= $token ?>">
        <?= csrf_field() ?>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="mt-8 w-full">
            <div class="bg-red-100 text-red-700 p-3 rounded">
              <?= session()->getFlashdata('error') ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- ====== BAGIAN 1 ====== -->
        <div class="flex flex-wrap gap-x-[60px] gap-y-8 mt-16 w-full">
          <div class="w-full md:w-[calc(50%-30px)]">
            <div class="form-group form-float">
              <div class="form-line">
                <input type="text" name="name" class="form-control" required value="<?= old('name') ?>" data-parsley-maxlength="255" maxlength="255">
                <label class="form-label">Nama</label>
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
                <input type="date" name="birth_of_day" class="form-control" value="<?= old('birth_of_day') ?>" required>
                <label class="form-label">Tanggal Lahir</label>
              </div>
            </div>
          </div>
          <div class="w-full md:w-[calc(50%-30px)]">
            <div class="form-group form-float">
              <div class="form-line">
                <input type="text" name="address" class="form-control" value="<?= old('address') ?>" required>
                <label class="form-label">Alamat</label>
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
        </div>

        <!-- ====== BAGIAN 2 ====== -->
        <div class="flex flex-wrap gap-x-[60px] gap-y-8 mb-16 w-full">
          <div class="w-full md:w-[calc(50%-30px)]">
            <div class="form-group form-float">
              <div class="form-line">
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required data-parsley-maxlength="255" maxlength="255">
                <label class="form-label">Email</label>
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
                <select class="form-control" required name="training_type">
                  <option value=""></option>
                  <?php foreach ($data_master['training_type'] as $item): ?>
                    <option <?= old('training_type') == $item['value'] ? "selected" : "" ?> value="<?= $item['value'] ?>"><?= $item['label'] ?></option>
                  <?php endforeach ?>
                </select>
                <label class="form-label">Jenis Pelatihan</label>
              </div>
            </div>
          </div>

          <div class="w-full md:w-[calc(50%-30px)]">
            <div class="form-group form-float">
              <div class="form-line">
                <label for="upload-file" class="file-upload w-full justify-between flex form-control focused cursor-pointer items-center" id="trigger-upload">
                  <div id="file-info" class="mt-2 flex">
                    <span id="file-name"></span>
                    <span id="remove-file" class="ml-2 text-red-600 cursor-pointer hover:underline hidden">Remove</span>
                  </div>
                  <img src="/icon/jariklurik-upload.png" class="h-[1.563rem] md:h-[1.563rem] w-auto">
                </label>
                <input type="file" id="upload-file" name="file_statement" class="form-control hidden" required
                  data-parsley-maxfilesize="1048576"
                  data-parsley-maxfilesize-message="Maksimal 1 MB"
                  accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                  data-parsley-fileextension="pdf,doc,docx,jpg,jpeg,png"
                  data-parsley-fileextension-message="Tipe file hanya .pdf, .doc, .docx, .jpg, .jpeg, .png">
                <label for="upload-file" class="form-label">Upload KTP</label>
              </div>
              <i class="text-xs md:text-sm text-[#B94A48]">Maksimal 1 MB dan tipe file hanya .pdf, .doc, .docx, .jpg, .jpeg, .png</i>
            </div>
            <!-- <?php if (!empty($data_master['file_sample'])): ?>
              <?php if (!empty($data_master['file_sample']['values'])): ?>
                <a href="<?= $data_master['file_sample']['values'] ?>" download class="flex gap-4 items-center">
                  <img src="/icon/jariklurik-download.png" class="h-[1.813rem] w-auto" />
                  <span class="text-[#714D00] text-xs md:text-sm">Download Contoh<br />Surat Pernyataan</span>
                </a> -->
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
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

    <?php endif; ?>
  </div>
</div>