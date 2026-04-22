<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="mx-auto grid max-w-5xl gap-8 lg:grid-cols-[0.9fr_1.1fr]">
    <div class="ui-card-dark p-8 md:p-10 text-white">
        <p class="text-[0.82rem] font-medium uppercase tracking-[0.42em] text-orange-300">Admin Access</p>
        <h1 class="ui-title-page mt-5 font-extrabold text-white">Masuk ke dashboard moderasi.</h1>
        <p class="mt-5 text-[1rem] leading-9 text-stone-300">Admin dashboard dipakai untuk mencari, mengurutkan, memeriksa, mengedit, dan menghapus surat tanpa perlu passcode user.</p>
        <div class="mt-8 rounded-[1.6rem] border border-white/10 bg-white/5 p-5 text-sm leading-8 text-stone-300">
            <p class="font-bold text-white">Kredensial default development</p>
            <p class="mt-2">Username: <span class="font-mono">admin</span></p>
            <p>Password: <span class="font-mono">admin123</span></p>
            <p class="mt-3 text-xs text-stone-400">Nilai ini diambil dari `.env` dan sebaiknya diganti sebelum project dipresentasikan.</p>
        </div>
    </div>

    <div class="ui-card p-8 md:p-10">
        <h2 class="ui-title-page font-extrabold text-stone-900">Login Admin</h2>
        <form action="<?= site_url('admin/login') ?>" method="post" class="mt-8 space-y-6">
            <?= csrf_field() ?>

            <div>
                <label for="username" class="mb-3 block text-base font-bold text-stone-800">Username</label>
                <input id="username" name="username" type="text" value="<?= esc(old('username')) ?>" class="ui-input" placeholder="Masukkan username admin">
            </div>

            <div>
                <label for="password" class="mb-3 block text-base font-bold text-stone-800">Password</label>
                <input id="password" name="password" type="password" class="ui-input" placeholder="Masukkan password admin">
            </div>

            <button type="submit" class="ui-pill-dark">Masuk Dashboard</button>
        </form>
    </div>
</section>
<?= $this->endSection() ?>
