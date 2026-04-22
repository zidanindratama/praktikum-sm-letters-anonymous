<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard Admin<?= $this->endSection() ?>

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

<section class="space-y-8">
    <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
        <div class="ui-card p-8 md:p-10">
            <p class="ui-section-label">Admin Dashboard</p>
            <h1 class="ui-title-page mt-5 font-extrabold text-stone-900">Kelola seluruh surat anonim dalam satu meja kerja.</h1>
            <p class="ui-copy mt-5 max-w-3xl">Dashboard ini sudah mendukung pencarian berdasarkan penerima, isi surat, atau UUID; pengurutan data; paginasi; serta edit dan delete langsung dari tabel.</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
            <div class="ui-card-dark p-7 text-white">
                <p class="text-[0.82rem] uppercase tracking-[0.42em] text-orange-300">Session</p>
                <p class="mt-6 text-3xl font-extrabold tracking-[-0.05em]"><?= esc($adminUsername) ?></p>
                <p class="mt-3 text-[0.98rem] leading-8 text-stone-300">Sedang login sebagai admin aktif.</p>
            </div>
            <div class="ui-card-soft p-7">
                <p class="text-[0.82rem] uppercase tracking-[0.42em] text-stone-500">Data</p>
                <p class="mt-6 text-3xl font-extrabold tracking-[-0.05em] text-stone-900"><?= esc((string) $pagination['total']) ?></p>
                <p class="mt-3 text-[0.98rem] leading-8 text-stone-600">Total surat yang berhasil ditemukan oleh filter saat ini.</p>
            </div>
        </div>
    </div>

    <div class="ui-card-soft p-6 md:p-8">
        <form action="<?= site_url('admin') ?>" method="get" class="grid gap-5 lg:grid-cols-[1.5fr_0.65fr_0.65fr_auto]">
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
                <a href="<?= site_url('admin') ?>" class="ui-pill">Reset</a>
            </div>
        </form>
    </div>

    <div class="ui-card overflow-hidden">
        <div class="flex items-center justify-between border-b border-stone-900/10 px-6 py-5">
            <div>
                <h2 class="text-[2rem] font-extrabold tracking-[-0.05em] text-stone-900">Tabel Surat</h2>
                <p class="mt-2 text-sm text-stone-600">Menampilkan <?= esc((string) $start) ?>-<?= esc((string) $end) ?> dari <?= esc((string) $pagination['total']) ?> data.</p>
            </div>
        </div>

        <?php if ($letters === []): ?>
            <div class="p-12 text-center">
                <p class="text-[1.8rem] font-extrabold tracking-[-0.05em] text-stone-900">Tidak ada data yang cocok.</p>
                <p class="mt-3 text-base text-stone-600">Coba ubah kata kunci pencarian atau aturan sort yang dipakai.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="ui-table min-w-full divide-y divide-stone-200">
                    <thead class="bg-stone-50">
                        <tr class="text-left">
                            <th class="px-6 py-4">Preview</th>
                            <th class="px-6 py-4">Recipient</th>
                            <th class="px-6 py-4">Message</th>
                            <th class="px-6 py-4">UUID</th>
                            <th class="px-6 py-4">Created</th>
                            <th class="px-6 py-4">Updated</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php foreach ($letters as $letter): ?>
                            <tr class="align-top hover:bg-white/35">
                                <td class="px-6 py-5">
                                    <img src="<?= base_url($letter['image_path']) ?>" alt="Preview surat" class="h-20 w-20 rounded-2xl object-cover shadow-sm">
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-stone-900"><?= esc($letter['recipient']) ?></p>
                                </td>
                                <td class="px-6 py-5 text-sm leading-7 text-stone-600"><?= esc(character_limiter($letter['message'], 100)) ?></td>
                                <td class="px-6 py-5 text-xs leading-6 text-stone-500"><?= esc($letter['id']) ?></td>
                                <td class="px-6 py-5 text-sm text-stone-600"><?= esc(date('d M Y H:i', strtotime($letter['created_at']))) ?></td>
                                <td class="px-6 py-5 text-sm text-stone-600"><?= esc(date('d M Y H:i', strtotime($letter['updated_at'] ?? $letter['created_at']))) ?></td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="<?= site_url('letters/' . $letter['id']) ?>" class="ui-pill min-h-0 px-4 py-2 text-xs">Detail</a>
                                        <a href="<?= site_url('admin/letters/' . $letter['id'] . '/edit') ?>" class="ui-pill-dark min-h-0 px-4 py-2 text-xs">Edit</a>
                                        <form action="<?= site_url('admin/letters/' . $letter['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus surat ini dari dashboard admin?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="ui-pill-danger min-h-0 px-4 py-2 text-xs">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($pagination['pageCount'] > 1): ?>
                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-stone-900/10 px-6 py-5">
                    <p class="text-sm text-stone-600">Halaman <?= esc((string) $pagination['currentPage']) ?> dari <?= esc((string) $pagination['pageCount']) ?></p>
                    <div class="flex flex-wrap gap-2">
                        <?php if ($pagination['currentPage'] > 1): ?>
                            <a href="<?= site_url('admin') . '?' . http_build_query(array_merge($queryBase, ['page' => $pagination['currentPage'] - 1])) ?>" class="ui-pill min-h-0 px-4 py-2 text-sm">Prev</a>
                        <?php endif; ?>

                        <?php for ($page = 1; $page <= $pagination['pageCount']; $page++): ?>
                            <a href="<?= site_url('admin') . '?' . http_build_query(array_merge($queryBase, ['page' => $page])) ?>" class="<?= $page === $pagination['currentPage'] ? 'ui-pill-dark' : 'ui-pill' ?> min-h-0 px-4 py-2 text-sm">
                                <?= esc((string) $page) ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($pagination['currentPage'] < $pagination['pageCount']): ?>
                            <a href="<?= site_url('admin') . '?' . http_build_query(array_merge($queryBase, ['page' => $pagination['currentPage'] + 1])) ?>" class="ui-pill min-h-0 px-4 py-2 text-sm">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
