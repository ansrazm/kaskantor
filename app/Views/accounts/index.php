<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Akun kas &amp; bank<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1>Akun kas &amp; bank</h1><a class="btn primary" href="<?= site_url('accounts/create') ?>">Tambah akun</a></div>
<section class="card">
    <div class="table-wrap"><table class="ledger">
        <thead><tr><th>Kode</th><th>Nama</th><th>Jenis</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= esc($r['code']) ?></td><td><?= esc($r['name']) ?></td><td><?= esc($r['type']) ?></td>
                <td><?= $r['is_active'] ? 'Aktif' : '<span class="muted">Nonaktif</span>' ?></td>
                <td class="actions">
                    <a href="<?= site_url("accounts/{$r['id']}/edit") ?>">Ubah</a>
                    <form method="post" action="<?= site_url("accounts/{$r['id']}/delete") ?>" onsubmit="return confirm('Hapus akun ini?')">
                        <?= csrf_field() ?><button class="link danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</section>
<?= $this->endSection() ?>
