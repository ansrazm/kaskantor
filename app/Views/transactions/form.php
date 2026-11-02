<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1><?= esc($title) ?></h1></div>
<?php $v = static fn ($k, $d = '') => old($k, $row[$k] ?? $d); ?>
<form method="post" action="<?= $action ?>" class="card form">
    <?= csrf_field() ?>
    <fieldset class="seg">
        <legend>Jenis</legend>
        <label><input type="radio" name="type" value="masuk" <?= $v('type') === 'masuk' ? 'checked' : '' ?>> Kas masuk</label>
        <label><input type="radio" name="type" value="keluar" <?= $v('type') === 'keluar' ? 'checked' : '' ?>> Kas keluar</label>
    </fieldset>
    <label>Tanggal <input type="date" name="trx_date" value="<?= esc($v('trx_date')) ?>" required></label>
    <label>Akun
        <select name="account_id" required>
            <option value="">Pilih akun</option>
            <?php foreach ($accounts as $a): ?>
                <option value="<?= $a['id'] ?>" <?= (string) $v('account_id') === (string) $a['id'] ? 'selected' : '' ?>><?= esc($a['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Kategori
        <select name="category_id" id="category" required>
            <option value="">Pilih kategori</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" data-type="<?= $c['type'] ?>" <?= (string) $v('category_id') === (string) $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Jumlah (Rp) <input type="number" name="amount" min="1" step="1" inputmode="numeric" value="<?= esc($v('amount') !== '' ? (string) (int) $v('amount') : '') ?>" required></label>
    <label>Keterangan <input type="text" name="description" maxlength="255" value="<?= esc($v('description')) ?>"></label>
    <div class="form-actions">
        <button class="btn primary" type="submit">Simpan</button>
        <a href="<?= site_url('transactions') ?>">Batal</a>
    </div>
</form>
<script>
// Tampilkan hanya kategori yang sesuai jenis transaksi.
(function () {
    var radios = document.querySelectorAll('input[name="type"]');
    var sel = document.getElementById('category');
    function sync() {
        var t = document.querySelector('input[name="type"]:checked');
        t = t ? t.value : 'masuk';
        Array.prototype.forEach.call(sel.options, function (o) {
            if (!o.value) return;
            var ok = o.dataset.type === t;
            o.hidden = !ok; o.disabled = !ok;
            if (!ok && o.selected) sel.value = '';
        });
    }
    radios.forEach(function (r) { r.addEventListener('change', sync); });
    sync();
})();
</script>
<?= $this->endSection() ?>
