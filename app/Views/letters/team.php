<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Anggota Kelompok<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
    <div class="ui-card p-8 md:p-10">
        <p class="ui-section-label">Anggota Kelompok</p>
        <h1 class="ui-title-page mt-5 font-extrabold text-stone-900">Tim pengembang project Letters Anonymous.</h1>
        <p class="ui-copy mt-5 max-w-2xl">Halaman ini menampilkan anggota kelompok praktikum Sistem Multimedia yang terlibat dalam perancangan, implementasi, dan deployment aplikasi.</p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="<?= site_url('/') ?>" class="ui-pill">Kembali ke Galeri</a>
            <a href="<?= site_url('letters/create') ?>" class="ui-pill-dark">Tulis Surat</a>
        </div>
    </div>

    <div class="ui-card-dark p-8 md:p-10 text-white">
        <p class="text-[0.82rem] font-medium uppercase tracking-[0.42em] text-orange-300">Praktikum Sistem Multimedia</p>
        <p class="mt-6 text-3xl font-extrabold tracking-[-0.05em]">4 Anggota</p>
        <p class="mt-4 text-[1rem] leading-9 text-stone-300">Setiap anggota berkontribusi dalam ide, pembangunan fitur, antarmuka, pengujian, sampai kesiapan deployment di VPS production.</p>
    </div>
</section>

<section class="mt-12">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-[0.82rem] font-medium uppercase tracking-[0.42em] text-stone-500">Daftar Tim</p>
            <h2 class="ui-title-page mt-4 font-extrabold text-stone-900">Identitas Anggota Kelompok</h2>
        </div>
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-2">
        <?php foreach ($members as $index => $member): ?>
            <article class="ui-card-soft p-7 transition duration-200 hover:-translate-y-1 hover:shadow-[0_34px_82px_-46px_rgba(45,33,22,0.35)]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[0.78rem] font-semibold uppercase tracking-[0.34em] text-orange-600">Anggota <?= esc((string) ($index + 1)) ?></p>
                        <h3 class="mt-4 text-[1.55rem] font-extrabold tracking-[-0.04em] text-stone-900"><?= esc($member['name']) ?></h3>
                    </div>
                    <span class="rounded-full bg-stone-900 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-white">Tim</span>
                </div>
                <div class="mt-6 rounded-[1.35rem] border border-stone-900/8 bg-white/80 px-5 py-4">
                    <p class="text-[0.78rem] font-semibold uppercase tracking-[0.32em] text-stone-500">NPM</p>
                    <p class="mt-2 text-lg font-bold tracking-[0.04em] text-stone-900"><?= esc($member['npm']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?= $this->endSection() ?>
