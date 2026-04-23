<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($this->renderSection('title') ?: 'Letters Anonymous') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        :root {
            --bg-soft: #f4ece4;
            --surface: rgba(255, 255, 255, 0.82);
            --surface-strong: rgba(255, 255, 255, 0.92);
            --surface-dark: #1f1916;
            --text-main: #211c18;
            --text-muted: #685d54;
            --line-soft: rgba(33, 28, 24, 0.1);
            --line-dashed: rgba(89, 76, 67, 0.22);
            --accent: #ff5a0a;
            --shadow-card: 0 30px 80px -42px rgba(46, 32, 18, 0.32);
            --shadow-soft: 0 18px 40px -28px rgba(46, 32, 18, 0.2);
            --radius-xl: 2rem;
            --radius-lg: 1.5rem;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at top left, rgba(255, 184, 118, 0.16), transparent 26%),
                radial-gradient(circle at 80% 22%, rgba(255, 215, 178, 0.28), transparent 22%),
                linear-gradient(180deg, #f8f1ea 0%, #f3ece4 34%, #efe7de 100%);
        }

        .page-shell {
            max-width: 1180px;
        }

        .brand-kicker {
            letter-spacing: 0.42em;
        }

        .ui-card {
            background: var(--surface);
            border: 1px solid var(--line-soft);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            backdrop-filter: blur(18px);
        }

        .ui-card-soft {
            background: var(--surface-strong);
            border: 1px solid var(--line-soft);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-soft);
        }

        .ui-card-dark {
            background: var(--surface-dark);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
        }

        .ui-section-label {
            color: var(--accent);
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.42em;
            text-transform: uppercase;
        }

        .ui-title-display {
            font-size: clamp(2.5rem, 4.5vw, 4.25rem);
            line-height: 0.98;
            letter-spacing: -0.06em;
        }

        .ui-title-page {
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1.04;
            letter-spacing: -0.05em;
        }

        .ui-copy {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.95;
        }

        .ui-pill,
        .ui-pill-active,
        .ui-pill-dark,
        .ui-pill-danger {
            align-items: center;
            border-radius: 999px;
            display: inline-flex;
            font-size: 0.95rem;
            font-weight: 700;
            gap: 0.5rem;
            justify-content: center;
            min-height: 52px;
            padding: 0.85rem 1.45rem;
            transition: 180ms ease;
        }

        .ui-pill {
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid var(--line-soft);
            color: var(--text-main);
        }

        .ui-pill:hover {
            border-color: rgba(33, 28, 24, 0.26);
            transform: translateY(-1px);
        }

        .ui-pill-active {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 90, 10, 0.3);
            color: var(--accent);
            box-shadow: 0 16px 30px -26px rgba(255, 90, 10, 0.45);
        }

        .ui-pill-active:hover {
            background: white;
            border-color: rgba(255, 90, 10, 0.48);
            transform: translateY(-1px);
        }

        .ui-pill-dark {
            background: var(--surface-dark);
            color: white;
        }

        .ui-pill-dark:hover {
            background: #302520;
            transform: translateY(-1px);
        }

        .ui-pill-danger {
            background: #b42318;
            color: white;
        }

        .ui-pill-danger:hover {
            background: #981b1b;
            transform: translateY(-1px);
        }

        .ui-input,
        .ui-select,
        .ui-textarea {
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(57, 47, 40, 0.16);
            border-radius: 1.2rem;
            color: var(--text-main);
            outline: none;
            transition: 160ms ease;
            width: 100%;
        }

        .ui-input,
        .ui-select {
            min-height: 56px;
            padding: 0 1.1rem;
        }

        .ui-textarea {
            min-height: 220px;
            padding: 1rem 1.1rem;
            resize: vertical;
        }

        .ui-input:focus,
        .ui-select:focus,
        .ui-textarea:focus {
            background: white;
            border-color: rgba(255, 90, 10, 0.42);
            box-shadow: 0 0 0 4px rgba(255, 90, 10, 0.08);
        }

        .ui-file {
            background: rgba(255, 255, 255, 0.84);
            border: 1px dashed rgba(57, 47, 40, 0.22);
            border-radius: 1.2rem;
            color: var(--text-muted);
            display: block;
            font-size: 0.95rem;
            padding: 1rem 1.1rem;
            width: 100%;
        }

        .ui-file::file-selector-button {
            background: var(--surface-dark);
            border: 0;
            border-radius: 999px;
            color: white;
            cursor: pointer;
            font-weight: 700;
            margin-right: 1rem;
            padding: 0.72rem 1rem;
        }

        .ui-table th {
            color: #7d746d;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.28em;
            text-transform: uppercase;
        }

        .ui-dashed {
            border: 1px dashed var(--line-dashed);
        }

        .confirm-modal[hidden] {
            display: none;
        }

        .confirm-modal {
            align-items: center;
            background: rgba(19, 16, 14, 0.48);
            backdrop-filter: blur(8px);
            display: flex;
            inset: 0;
            justify-content: center;
            padding: 1.5rem;
            position: fixed;
            z-index: 80;
        }

        .confirm-modal__panel {
            background: rgba(255, 252, 249, 0.98);
            border: 1px solid rgba(33, 28, 24, 0.08);
            border-radius: 1.75rem;
            box-shadow: 0 40px 80px -40px rgba(26, 18, 13, 0.48);
            max-width: 30rem;
            padding: 1.75rem;
            transform: translateY(0);
            width: 100%;
        }

        .confirm-modal__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .topbar-nav {
            display: grid;
            gap: 0.8rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            width: 100%;
        }

        .topbar-nav > * {
            width: 100%;
        }

        .topbar-nav a,
        .topbar-nav button {
            text-align: center;
        }

        .topbar-nav form {
            display: flex;
        }

        .topbar-nav form button {
            width: 100%;
        }

        @media (max-width: 768px) {
            .ui-title-display {
                font-size: 2.5rem;
            }

            .ui-title-page {
                font-size: 1.95rem;
            }

            .ui-copy {
                line-height: 1.8;
            }
        }

        @media (min-width: 768px) {
            .topbar-nav {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-end;
                width: auto;
            }

            .topbar-nav > *,
            .topbar-nav form,
            .topbar-nav form button {
                width: auto;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <?php
    $currentPath = trim(service('uri')->getPath(), '/');
    $normalizedPath = preg_replace('#^index\.php/?#', '', $currentPath) ?? $currentPath;
    $normalizedPath = trim($normalizedPath, '/');

    $isGroupActive = $normalizedPath === 'kelompok';
    $isCreateActive = $normalizedPath === 'letters/create';
    $isAdminActive = $normalizedPath === 'admin' || $normalizedPath === 'admin/login' || str_starts_with($normalizedPath, 'admin/');
    $isGalleryActive = ! $isGroupActive && ! $isCreateActive && ! $isAdminActive;

    $navClass = static fn (bool $active): string => $active ? 'ui-pill-active' : 'ui-pill';
    ?>
    <div class="min-h-screen">
        <header class="border-b border-stone-900/8 bg-white/68 backdrop-blur-xl">
            <div class="page-shell mx-auto flex flex-col gap-5 px-5 py-5 sm:px-6 lg:px-8 md:flex-row md:items-center md:justify-between">
                <a href="<?= site_url('/') ?>" class="flex min-w-0 items-center gap-4">
                    <span class="h-10 w-px bg-stone-900/12"></span>
                    <div class="min-w-0">
                        <p class="brand-kicker truncate text-[0.82rem] font-medium uppercase text-stone-500">Sistem Multimedia</p>
                        <p class="truncate text-[1.9rem] font-extrabold tracking-[-0.05em] text-stone-900">Letters Anonymous</p>
                    </div>
                </a>
                <nav class="topbar-nav items-center">
                    <a href="<?= site_url('/') ?>" class="<?= $navClass($isGalleryActive) ?>">Galeri</a>
                    <a href="<?= site_url('kelompok') ?>" class="<?= $navClass($isGroupActive) ?>">Kelompok</a>
                    <a href="<?= site_url('letters/create') ?>" class="<?= $isCreateActive ? 'ui-pill-active' : 'ui-pill-dark' ?>">Tulis Surat</a>
                    <?php if (session()->get('is_admin')): ?>
                        <a href="<?= site_url('admin') ?>" class="<?= $navClass($isAdminActive) ?>">Dashboard</a>
                        <form action="<?= site_url('admin/logout') ?>" method="post" class="inline-flex">
                            <?= csrf_field() ?>
                            <button type="submit" class="ui-pill">Logout</button>
                        </form>
                    <?php else: ?>
                        <a href="<?= site_url('admin/login') ?>" class="<?= $navClass($isAdminActive) ?>">Admin</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <main class="page-shell mx-auto px-5 py-8 sm:px-6 lg:px-8">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="ui-card-soft mb-6 px-5 py-4 text-sm font-medium text-emerald-900">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="ui-card-soft mb-6 border-rose-200/70 bg-rose-50/85 px-5 py-4 text-sm font-medium text-rose-900">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <div id="confirm-modal" class="confirm-modal" hidden aria-hidden="true">
        <div class="confirm-modal__panel">
            <p class="text-[0.82rem] font-semibold uppercase tracking-[0.34em] text-rose-500">Konfirmasi Aksi</p>
            <h2 id="confirm-modal-title" class="mt-3 text-[1.9rem] font-extrabold tracking-[-0.05em] text-stone-900">Hapus data ini?</h2>
            <p id="confirm-modal-message" class="mt-4 text-[0.98rem] leading-8 text-stone-600">Tindakan ini tidak bisa dibatalkan.</p>
            <div class="confirm-modal__actions">
                <button type="button" id="confirm-modal-cancel" class="ui-pill">Batal</button>
                <button type="button" id="confirm-modal-confirm" class="ui-pill-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('confirm-modal');
            const title = document.getElementById('confirm-modal-title');
            const message = document.getElementById('confirm-modal-message');
            const cancelButton = document.getElementById('confirm-modal-cancel');
            const confirmButton = document.getElementById('confirm-modal-confirm');

            if (!modal || !title || !message || !cancelButton || !confirmButton) {
                return;
            }

            let pendingForm = null;

            const closeModal = () => {
                modal.hidden = true;
                modal.setAttribute('aria-hidden', 'true');
                pendingForm = null;
            };

            document.addEventListener('submit', (event) => {
                const form = event.target;

                if (!(form instanceof HTMLFormElement) || !form.dataset.confirmMessage) {
                    return;
                }

                event.preventDefault();
                pendingForm = form;
                title.textContent = form.dataset.confirmTitle || 'Konfirmasi aksi';
                message.textContent = form.dataset.confirmMessage;
                confirmButton.textContent = form.dataset.confirmActionLabel || 'Ya, lanjutkan';
                modal.hidden = false;
                modal.setAttribute('aria-hidden', 'false');
            });

            confirmButton.addEventListener('click', () => {
                if (!pendingForm) {
                    closeModal();
                    return;
                }

                const form = pendingForm;
                closeModal();
                form.submit();
            });

            cancelButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.hidden) {
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>
