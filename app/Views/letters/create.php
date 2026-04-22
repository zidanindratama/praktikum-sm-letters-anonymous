<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Tulis Surat<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $errors = session('errors') ?? []; ?>

<section class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
    <div class="ui-card-dark p-8 md:p-10 text-white">
        <p class="text-[0.82rem] font-medium uppercase tracking-[0.42em] text-orange-300">Create Letter</p>
        <h1 class="ui-title-page mt-5 font-extrabold text-white">Tulis pesan anonim dengan visual yang kuat.</h1>
        <p class="mt-5 max-w-lg text-[1rem] leading-9 text-stone-300">Form ini akan menyimpan penerima, isi surat, gambar, dan membuat passcode rahasia otomatis untuk edit atau delete.</p>
        <ul class="mt-8 space-y-4 text-[0.98rem] leading-8 text-stone-300">
            <li>Gambar wajib format JPG atau PNG.</li>
            <li>Ukuran maksimal file 2MB.</li>
            <li>Passcode cuma muncul sekali setelah surat berhasil dibuat.</li>
        </ul>
    </div>

    <div class="ui-card p-8 md:p-10">
        <h2 class="ui-title-page text-stone-900 font-extrabold">Form Surat</h2>
        <form action="<?= site_url('letters') ?>" method="post" enctype="multipart/form-data" class="mt-8 space-y-6">
            <?= csrf_field() ?>

            <div>
                <label for="recipient" class="mb-3 block text-base font-bold text-stone-800">Untuk siapa surat ini?</label>
                <input id="recipient" name="recipient" type="text" value="<?= esc(old('recipient')) ?>" class="ui-input" placeholder="Contoh: Sahabat Rahasia">
                <?php if (isset($errors['recipient'])): ?>
                    <p class="mt-2 text-sm text-rose-600"><?= esc($errors['recipient']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="message" class="mb-3 block text-base font-bold text-stone-800">Isi surat</label>
                <textarea id="message" name="message" rows="8" class="ui-textarea" placeholder="Tulis pesanmu di sini..."><?= esc(old('message')) ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <p class="mt-2 text-sm text-rose-600"><?= esc($errors['message']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="image" class="mb-3 block text-base font-bold text-stone-800">Upload gambar</label>
                <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="ui-file">
                <?php if (isset($errors['image'])): ?>
                    <p class="mt-2 text-sm text-rose-600"><?= esc($errors['image']) ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="ui-pill-dark bg-orange-600 hover:!bg-stone-900">Kirim Surat</button>
        </form>
    </div>
</section>
<?= $this->endSection() ?>
