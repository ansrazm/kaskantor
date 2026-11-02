<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1><?= esc($title) ?></h1></div>
<?php $v = static fn ($k, $d = '') => old($k, $row[$k] ?? $d); ?>
<form method="post" action="<?= $action ?>" class="card form">
    <?= csrf_field() ?>
    <label>Kode <input type="text" name="code" maxlength="20" value="<?= esc($v('code')) ?>" required></label>
    <label>Nama <input type="text" name="name" maxlength="100" value="<?= esc($v('name')) ?>" required></label>
    <label>Jenis
        <select name="type">
            <option value="kas" <?= $v('type') === 'kas' ? 'selected' : '' ?>>Kas</option>
            <option value="bank" <?= $v('type') === 'bank' ? 'selected' : '' ?>>Bank</option>
        </select>
    </label>
    <label class="check"><input type="checkbox" name="is_active" value="1" <?= (int) $v('is_active', 1) ? 'checked' : '' ?>> Akun aktif</label>
    <div class="form-actions"><button class="btn primary" type="submit">Simpan</button> <a href="<?= site_url('accounts') ?>">Batal</a></div>
</form>
<?= $this->endSection() ?>
