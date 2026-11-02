<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Transaksi<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head">
    <h1>Transaksi</h1>
    <?php if (can('admin', 'kasir')): ?><a class="btn primary" href="<?= site_url('transactions/create') ?>">Catat transaksi</a><?php endif; ?>
</div>

<form method="get" class="card filters">
    <label>Dari <input type="date" name="from" value="<?= esc($filters['from']) ?>"></label>
    <label>Sampai <input type="date" name="to" value="<?= esc($filters['to']) ?>"></label>
    <label>Jenis
        <select name="type">
            <option value="">Semua</option>
            <option value="masuk" <?= $filters['type'] === 'masuk' ? 'selected' : '' ?>>Masuk</option>
            <option value="keluar" <?= $filters['type'] === 'keluar' ? 'selected' : '' ?>>Keluar</option>
        </select>
    </label>
    <label>Kategori
        <select name="category_id">
            <option value="">Semua</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= (string) $filters['category_id'] === (string) $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?> (<?= $c['type'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Akun
        <select name="account_id">
            <option value="">Semua</option>
            <?php foreach ($accounts as $a): ?>
                <option value="<?= $a['id'] ?>" <?= (string) $filters['account_id'] === (string) $a['id'] ? 'selected' : '' ?>><?= esc($a['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <div class="filter-actions"><button class="btn" type="submit">Terapkan</button> <a href="<?= site_url('transactions') ?>">Reset</a></div>
</form>

<section class="card">
<?php if (! $rows): ?>
    <p class="empty">Tidak ada transaksi yang cocok dengan filter ini.</p>
<?php else: ?>
    <div class="table-wrap"><table class="ledger">
        <thead><tr><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th>Akun</th><th class="num">Jumlah</th><?php if (can('admin', 'kasir')): ?><th></th><?php endif; ?></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= tgl($r['trx_date']) ?></td>
                <td><?= esc($r['description'] ?: '-') ?><div class="muted small">oleh <?= esc($r['user_name']) ?></div></td>
                <td><?= esc($r['category_name']) ?></td>
                <td><?= esc($r['account_name']) ?></td>
                <td class="num <?= $r['type'] === 'masuk' ? 'in' : 'out' ?>"><?= $r['type'] === 'masuk' ? '+' : '−' ?> <?= rupiah($r['amount']) ?></td>
                <?php if (can('admin', 'kasir')): ?>
                <td class="actions">
                    <a href="<?= site_url("transactions/{$r['id']}/edit") ?>">Ubah</a>
                    <form method="post" action="<?= site_url("transactions/{$r['id']}/delete") ?>" onsubmit="return confirm('Hapus transaksi ini?')">
                        <?= csrf_field() ?><button class="link danger" type="submit">Hapus</button>
                    </form>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?= $pager->links() ?>
<?php endif; ?>
</section>
<?= $this->endSection() ?>
