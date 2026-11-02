<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Ganti password<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1>Ganti password</h1></div>
<form method="post" action="<?= site_url('password') ?>" class="card form">
    <?= csrf_field() ?>
    <label>Password saat ini <input type="password" name="current_password" autocomplete="current-password" required></label>
    <label>Password baru (minimal 8 karakter) <input type="password" name="new_password" autocomplete="new-password" minlength="8" required></label>
    <label>Ulangi password baru <input type="password" name="confirm_password" autocomplete="new-password" required></label>
    <div class="form-actions"><button class="btn primary" type="submit">Simpan password</button> <a href="<?= site_url('dashboard') ?>">Batal</a></div>
</form>
<?= $this->endSection() ?>
