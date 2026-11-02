<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · KasKantor</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body class="login-page">
<div class="login">
    <div class="login-ledger" aria-hidden="true">
        <div class="ln"><span>Kas masuk</span><b class="in">+ 12.500.000</b></div>
        <div class="ln"><span>Kas keluar</span><b class="out">− 6.000.000</b></div>
        <div class="ln total"><span>Saldo</span><b>6.500.000</b></div>
    </div>
    <form method="post" action="<?= site_url('login') ?>" class="card login-card">
        <h1>KasKantor</h1>
        <p class="muted">Catat kas masuk dan keluar, lihat saldo, cetak laporan bulanan.</p>

        <?php if (session()->getFlashdata('error')): ?><div class="flash err" role="alert"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?><div class="flash ok" role="status"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
        <?php if ($errs = session()->getFlashdata('errors')): ?><div class="flash err"><?php foreach ((array) $errs as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?></div><?php endif; ?>

        <?= csrf_field() ?>
        <label>Email
            <input type="email" name="email" value="<?= esc(old('email')) ?>" autocomplete="username" required autofocus>
        </label>
        <label>Password
            <input type="password" name="password" autocomplete="current-password" required>
        </label>
        <button class="btn primary block" type="submit">Masuk</button>
    </form>
</div>
</body>
</html>
