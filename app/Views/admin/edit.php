<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Surat Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $errors = session('errors') ?? []; ?>

<section class="grid gap-8 lg:grid-cols-[0.75fr_1.25fr]">
    <div class="ui-card overflow-hidden">
        <div class="aspect-[4/3] bg-stone-200">
            <img src="<?= base_url($letter['image_path']) ?>" alt="Preview gambar surat" class="h-full w-full object-cover">
        </div>
        <div class="p-6">
            <p class="text-[0.82rem] font-semibold uppercase tracking-[0.34em] text-stone-500">Admin Edit Mode</p>
            <p class="mt-3 text-[0.98rem] leading-8 text-stone-600">Admin bisa mengubah isi surat dan mengganti gambar tanpa passcode. Jika gambar diganti, file lama ikut dihapus.</p>
        </div>
    </div>

    <div class="ui-card p-8 md:p-10">
        <p class="ui-section-label">Dashboard Edit</p>
        <h1 class="ui-title-page mt-4 font-extrabold text-stone-900">Moderasi surat</h1>

        <form action="<?= site_url('admin/letters/' . $letter['id'] . '/update') ?>" method="post" enctype="multipart/form-data" class="mt-8 space-y-6">
            <?= csrf_field() ?>

            <div>
                <label for="recipient" class="mb-3 block text-base font-bold text-stone-800">Penerima</label>
                <input id="recipient" name="recipient" type="text" value="<?= esc(old('recipient', $letter['recipient'])) ?>" class="ui-input">
                <?php if (isset($errors['recipient'])): ?>
                    <p class="mt-2 text-sm text-rose-600"><?= esc($errors['recipient']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="message" class="mb-3 block text-base font-bold text-stone-800">Isi surat</label>
                <textarea id="message" name="message" rows="8" class="ui-textarea"><?= esc(old('message', $letter['message'])) ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <p class="mt-2 text-sm text-rose-600"><?= esc($errors['message']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="image" class="mb-3 block text-base font-bold text-stone-800">Ganti gambar</label>
                <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="ui-file">
                <?php if (isset($errors['image'])): ?>
                    <p class="mt-2 text-sm text-rose-600"><?= esc($errors['image']) ?></p>
                <?php endif; ?>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="ui-pill-dark bg-orange-600 hover:!bg-stone-900">Simpan Perubahan</button>
                <a href="<?= site_url('admin') ?>" class="ui-pill">Kembali ke Dashboard</a>
            </div>
        </form>
    </div>
</section>
<?= $this->endSection() ?>
