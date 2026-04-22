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
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen">
        <header class="border-b border-stone-900/8 bg-white/68 backdrop-blur-xl">
            <div class="page-shell mx-auto flex flex-wrap items-center justify-between gap-5 px-5 py-5 sm:px-6 lg:px-8">
                <a href="<?= site_url('/') ?>" class="flex min-w-0 items-center gap-4">
                    <span class="h-10 w-px bg-stone-900/12"></span>
                    <div class="min-w-0">
                        <p class="brand-kicker truncate text-[0.82rem] font-medium uppercase text-stone-500">Sistem Multimedia</p>
                        <p class="truncate text-[1.9rem] font-extrabold tracking-[-0.05em] text-stone-900">Letters Anonymous</p>
                    </div>
                </a>
                <nav class="flex flex-wrap items-center gap-3">
                    <a href="<?= site_url('/') ?>" class="ui-pill">Galeri</a>
                    <a href="<?= site_url('letters/create') ?>" class="ui-pill-dark">Tulis Surat</a>
                    <?php if (session()->get('is_admin')): ?>
                        <a href="<?= site_url('admin') ?>" class="ui-pill">Dashboard</a>
                        <form action="<?= site_url('admin/logout') ?>" method="post" class="inline-flex">
                            <?= csrf_field() ?>
                            <button type="submit" class="ui-pill">Logout</button>
                        </form>
                    <?php else: ?>
                        <a href="<?= site_url('admin/login') ?>" class="ui-pill">Admin</a>
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
</body>
</html>
