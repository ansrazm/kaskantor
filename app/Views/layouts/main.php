<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($this->renderSection('title')) ?> · KasKantor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body>
<?php $path = service('request')->getUri()->getPath(); $nav = static fn ($p) => str_starts_with($path, $p) ? ' class="active"' : ''; ?>
<div class="shell">
    <aside class="side" id="side">
        <div class="brand">KasKantor</div>
        <nav>
            <a href="<?= site_url('dashboard') ?>"<?= $nav('dashboard') ?>>Ringkasan</a>
            <a href="<?= site_url('transactions') ?>"<?= $nav('transactions') ?>>Transaksi</a>
            <a href="<?= site_url('reports') ?>"<?= $nav('reports') ?>>Laporan bulanan</a>
            <?php if (can('admin')): ?>
                <a href="<?= site_url('accounts') ?>"<?= $nav('accounts') ?>>Akun kas &amp; bank</a>
                <a href="<?= site_url('categories') ?>"<?= $nav('categories') ?>>Kategori</a>
                <a href="<?= site_url('audit') ?>"<?= $nav('audit') ?>>Jejak audit</a>
            <?php endif; ?>
        </nav>
        <div class="me">
            <strong><?= esc(session('name')) ?></strong>
            <span class="role role-<?= esc(session('role')) ?>"><?= esc(session('role')) ?></span>
            <a class="pw" href="<?= site_url('password') ?>">Ganti password</a>
            <form action="<?= site_url('logout') ?>" method="post">
                <?= csrf_field() ?>
                <button class="link" type="submit">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <button class="menu-btn" type="button" onclick="document.getElementById('side').classList.toggle('open')" aria-label="Buka menu">Menu</button>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash ok" role="status"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash err" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if ($errs = session()->getFlashdata('errors')): ?>
            <div class="flash err" role="alert">
                <?php foreach ((array) $errs as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>
</div>
</body>
</html>
