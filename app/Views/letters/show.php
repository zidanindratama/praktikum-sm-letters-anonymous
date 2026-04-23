<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Detail Surat<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
    <div class="ui-card overflow-hidden">
        <div class="aspect-[4/3] bg-stone-200">
            <img src="<?= base_url($letter['image_path']) ?>" alt="Gambar surat untuk <?= esc($letter['recipient']) ?>" class="h-full w-full object-cover">
        </div>
        <div class="p-8 md:p-10">
            <p class="text-[0.82rem] font-semibold uppercase tracking-[0.38em] text-orange-600">Untuk <?= esc($letter['recipient']) ?></p>
            <h1 class="ui-title-page mt-4 font-extrabold text-stone-900">Surat Anonim</h1>
            <p class="mt-6 whitespace-pre-line text-[1.02rem] leading-9 text-stone-700"><?= esc($letter['message']) ?></p>
            <div class="mt-8 flex flex-wrap gap-3 text-xs text-stone-500">
                <span class="rounded-full bg-stone-100 px-4 py-2">UUID: <?= esc($letter['id']) ?></span>
                <span class="rounded-full bg-stone-100 px-4 py-2">Dibuat: <?= esc(date('d M Y H:i', strtotime($letter['created_at']))) ?></span>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <?php if (session()->getFlashdata('generatedPasscode')): ?>
            <div class="ui-card-soft border-amber-300/60 bg-amber-50/90 p-6">
                <p class="text-[0.82rem] font-semibold uppercase tracking-[0.34em] text-amber-700">Simpan Passcode Ini</p>
                <p class="mt-4 text-[2.1rem] font-extrabold tracking-[0.24em] text-amber-950"><?= esc(session()->getFlashdata('generatedPasscode')) ?></p>
                <p class="mt-3 text-[0.95rem] leading-8 text-amber-900">Passcode hanya ditampilkan sekali. Simpan baik-baik karena dipakai untuk edit atau delete.</p>
            </div>
        <?php endif; ?>

        <div class="ui-card-soft p-6 md:p-7">
            <p class="text-[0.82rem] font-semibold uppercase tracking-[0.34em] text-stone-500">Update</p>
            <h2 class="mt-3 text-[1.85rem] font-extrabold tracking-[-0.05em] text-stone-900">Buka akses edit</h2>
            <p class="mt-3 text-[0.98rem] leading-8 text-stone-600">Masukkan passcode untuk membuka halaman edit dalam sesi browser ini.</p>

            <?php if ($canEdit): ?>
                <a href="<?= site_url('letters/' . $letter['id'] . '/edit') ?>" class="ui-pill-dark mt-6">Lanjut ke Halaman Edit</a>
            <?php else: ?>
                <form action="<?= site_url('letters/' . $letter['id'] . '/edit-access') ?>" method="post" class="mt-5 space-y-4">
                    <?= csrf_field() ?>
                    <input name="passcode" type="text" class="ui-input" placeholder="Masukkan passcode">
                    <button type="submit" class="ui-pill-dark">Verifikasi Passcode</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="ui-card-soft border-rose-200/70 p-6 md:p-7">
            <p class="text-[0.82rem] font-semibold uppercase tracking-[0.34em] text-rose-500">Delete</p>
            <h2 class="mt-3 text-[1.85rem] font-extrabold tracking-[-0.05em] text-stone-900">Hapus surat</h2>
            <p class="mt-3 text-[0.98rem] leading-8 text-stone-600">Saat surat dihapus, data database dan file gambar di folder `public/uploads` akan ikut dihapus.</p>
            <form
                action="<?= site_url('letters/' . $letter['id'] . '/delete') ?>"
                method="post"
                class="mt-5 space-y-4"
                data-confirm-message="Yakin ingin menghapus surat ini secara permanen? File gambar juga akan ikut dihapus."
                data-confirm-title="Hapus surat ini?"
                data-confirm-action-label="Ya, hapus surat"
            >
                <?= csrf_field() ?>
                <input name="passcode" type="text" class="ui-input border-rose-200 bg-rose-50/85 focus:border-rose-400 focus:shadow-[0_0_0_4px_rgba(244,63,94,0.08)]" placeholder="Masukkan passcode untuk delete">
                <button type="submit" class="ui-pill-danger">Hapus Permanen</button>
            </form>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
