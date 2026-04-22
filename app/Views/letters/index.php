<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Galeri Surat<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$queryBase = [
    'q'         => $filters['q'],
    'sort'      => $filters['sort'],
    'direction' => $filters['direction'],
];
$start = $pagination['total'] > 0 ? (($pagination['currentPage'] - 1) * $pagination['perPage']) + 1 : 0;
$end = min($pagination['currentPage'] * $pagination['perPage'], $pagination['total']);
?>

<section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
    <div class="ui-card p-8 md:p-11">
        <p class="ui-section-label">Praktikum Sistem Multimedia</p>
        <h1 class="ui-title-display mt-5 max-w-3xl font-extrabold text-stone-900">Kirim surat anonim dengan gambar yang terasa personal.</h1>
        <p class="ui-copy mt-6 max-w-2xl">Project ini menyimpan pesan, gambar, dan passcode rahasia untuk mengedit atau menghapus surat tanpa perlu akun.</p>
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="<?= site_url('letters/create') ?>" class="ui-pill-dark bg-orange-600 hover:!bg-stone-900">Buat Surat Baru</a>
            <a href="#gallery" class="ui-pill">Lihat Galeri</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
        <div class="ui-card-dark p-8 text-white">
            <p class="text-[0.82rem] font-medium uppercase tracking-[0.42em] text-orange-300">Fitur</p>
            <p class="mt-7 text-4xl font-extrabold tracking-[-0.06em]"><?= esc((string) $pagination['total']) ?></p>
            <p class="mt-4 max-w-md text-[1rem] leading-9 text-stone-200">Surat tampil sebagai gallery visual, lengkap dengan upload gambar, UUID, passcode hash, search, sort, dan pagination.</p>
        </div>
        <div class="ui-card-soft p-8">
            <p class="text-[0.82rem] font-medium uppercase tracking-[0.42em] text-stone-500">Alur</p>
            <p class="mt-6 text-[1.95rem] font-extrabold tracking-[-0.05em] text-stone-900">Create, Read, Update, Delete</p>
            <p class="ui-copy mt-4">Edit butuh verifikasi passcode, delete akan ikut menghapus file gambar, dan galeri sekarang bisa difilter dari halaman public.</p>
        </div>
    </div>
</section>

<section id="gallery" class="mt-14">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-[0.82rem] font-medium uppercase tracking-[0.42em] text-stone-500">Galeri</p>
            <h2 class="ui-title-page mt-4 font-extrabold text-stone-900">Surat terbaru</h2>
            <p class="mt-3 text-base text-stone-600">Menampilkan <?= esc((string) $start) ?>-<?= esc((string) $end) ?> dari <?= esc((string) $pagination['total']) ?> surat.</p>
        </div>
    </div>

    <div class="ui-card-soft mt-8 p-6 md:p-8">
        <form action="<?= site_url('/') ?>" method="get" class="grid gap-5 lg:grid-cols-[1.5fr_0.72fr_0.72fr_auto]">
            <div>
                <label for="q" class="mb-3 block text-base font-bold text-stone-800">Search</label>
                <input id="q" name="q" type="text" value="<?= esc($filters['q']) ?>" class="ui-input" placeholder="Cari recipient, message, atau UUID">
            </div>
            <div>
                <label for="sort" class="mb-3 block text-base font-bold text-stone-800">Sort By</label>
                <select id="sort" name="sort" class="ui-select">
                    <option value="created_at" <?= $filters['sort'] === 'created_at' ? 'selected' : '' ?>>Created At</option>
                    <option value="updated_at" <?= $filters['sort'] === 'updated_at' ? 'selected' : '' ?>>Updated At</option>
                    <option value="recipient" <?= $filters['sort'] === 'recipient' ? 'selected' : '' ?>>Recipient</option>
                </select>
            </div>
            <div>
                <label for="direction" class="mb-3 block text-base font-bold text-stone-800">Direction</label>
                <select id="direction" name="direction" class="ui-select">
                    <option value="DESC" <?= $filters['direction'] === 'DESC' ? 'selected' : '' ?>>Descending</option>
                    <option value="ASC" <?= $filters['direction'] === 'ASC' ? 'selected' : '' ?>>Ascending</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="ui-pill-dark">Terapkan</button>
                <a href="<?= site_url('/') ?>" class="ui-pill">Reset</a>
            </div>
        </form>
    </div>

    <?php if ($letters === []): ?>
        <div class="ui-card-soft ui-dashed mt-8 p-12 text-center md:p-16">
            <p class="text-[2rem] font-extrabold tracking-[-0.05em] text-stone-900">Tidak ada surat yang cocok.</p>
            <p class="mx-auto mt-3 max-w-xl text-[1.02rem] leading-8 text-stone-600">Coba ubah keyword pencarian atau aturan urutannya.</p>
            <a href="<?= site_url('/') ?>" class="ui-pill-dark mt-8">Reset Filter</a>
        </div>
    <?php else: ?>
        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($letters as $letter): ?>
                <article class="ui-card-soft group overflow-hidden transition duration-200 hover:-translate-y-1 hover:shadow-[0_34px_82px_-46px_rgba(45,33,22,0.45)]">
                    <a href="<?= site_url('letters/' . $letter['id']) ?>" class="block">
                        <div class="aspect-[4/3] overflow-hidden bg-stone-200">
                            <img src="<?= base_url($letter['image_path']) ?>" alt="Gambar untuk <?= esc($letter['recipient']) ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        </div>
                        <div class="p-6">
                            <p class="text-[0.75rem] font-semibold uppercase tracking-[0.28em] text-orange-600">Untuk <?= esc($letter['recipient']) ?></p>
                            <p class="mt-3 text-base font-bold tracking-[-0.02em] text-stone-900"><?= esc(character_limiter($letter['message'], 56)) ?></p>
                            <p class="mt-3 text-sm leading-7 text-stone-600"><?= esc(character_limiter($letter['message'], 120)) ?></p>
                            <div class="mt-6 flex items-center justify-between gap-3 text-[0.72rem] text-stone-500">
                                <span class="truncate"><?= esc($letter['id']) ?></span>
                                <span class="shrink-0"><?= esc(date('d M Y H:i', strtotime($letter['created_at']))) ?></span>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($pagination['pageCount'] > 1): ?>
            <div class="ui-card-soft mt-8 flex flex-wrap items-center justify-between gap-4 px-6 py-5">
                <p class="text-sm text-stone-600">Halaman <?= esc((string) $pagination['currentPage']) ?> dari <?= esc((string) $pagination['pageCount']) ?></p>
                <div class="flex flex-wrap gap-2">
                    <?php if ($pagination['currentPage'] > 1): ?>
                        <a href="<?= site_url('/') . '?' . http_build_query(array_merge($queryBase, ['page' => $pagination['currentPage'] - 1])) ?>" class="ui-pill min-h-0 px-4 py-2 text-sm">Prev</a>
                    <?php endif; ?>

                    <?php for ($page = 1; $page <= $pagination['pageCount']; $page++): ?>
                        <a href="<?= site_url('/') . '?' . http_build_query(array_merge($queryBase, ['page' => $page])) ?>" class="<?= $page === $pagination['currentPage'] ? 'ui-pill-dark' : 'ui-pill' ?> min-h-0 px-4 py-2 text-sm">
                            <?= esc((string) $page) ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($pagination['currentPage'] < $pagination['pageCount']): ?>
                        <a href="<?= site_url('/') . '?' . http_build_query(array_merge($queryBase, ['page' => $pagination['currentPage'] + 1])) ?>" class="ui-pill min-h-0 px-4 py-2 text-sm">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
