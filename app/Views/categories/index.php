<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Kategori<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1>Kategori</h1></div>
<form method="post" action="<?= site_url('categories') ?>" class="card filters">
    <?= csrf_field() ?>
    <label>Nama kategori <input type="text" name="name" maxlength="100" required></label>
    <label>Jenis
        <select name="type"><option value="masuk">Masuk</option><option value="keluar">Keluar</option></select>
    </label>
    <div class="filter-actions"><button class="btn primary" type="submit">Tambah kategori</button></div>
</form>
<section class="card">
    <table class="ledger">
        <thead><tr><th>Nama</th><th>Jenis</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= esc($r['name']) ?></td>
                <td class="<?= $r['type'] === 'masuk' ? 'in' : 'out' ?>"><?= esc($r['type']) ?></td>
                <td class="actions">
                    <form method="post" action="<?= site_url("categories/{$r['id']}/delete") ?>" onsubmit="return confirm('Hapus kategori ini?')">
                        <?= csrf_field() ?><button class="link danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?= $this->endSection() ?>
