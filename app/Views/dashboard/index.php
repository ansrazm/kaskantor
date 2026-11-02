<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Ringkasan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head">
    <h1>Ringkasan kas</h1>
    <?php if (can('admin', 'kasir')): ?><a class="btn primary" href="<?= site_url('transactions/create') ?>">Catat transaksi</a><?php endif; ?>
</div>

<section class="hero card">
    <div>
        <div class="label">Total saldo semua akun</div>
        <div class="big <?= $total < 0 ? 'out' : '' ?>"><?= rupiah($total) ?></div>
    </div>
    <dl class="mini">
        <div><dt>Masuk, <?= esc($monthLabel) ?></dt><dd class="in"><?= rupiah($month['masuk']) ?></dd></div>
        <div><dt>Keluar, <?= esc($monthLabel) ?></dt><dd class="out"><?= rupiah($month['keluar']) ?></dd></div>
        <div><dt>Selisih bulan ini</dt><dd><?= rupiah($month['masuk'] - $month['keluar']) ?></dd></div>
    </dl>
</section>

<div class="grid2">
    <section class="card">
        <h2>Saldo per akun</h2>
        <table class="ledger">
            <tbody>
            <?php foreach ($balances as $b): ?>
                <tr><td><?= esc($b['name']) ?> <span class="muted"><?= esc($b['code']) ?></span></td><td class="num"><?= rupiah($b['saldo']) ?></td></tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot><tr><td>Total</td><td class="num"><?= rupiah($total) ?></td></tr></tfoot>
        </table>
    </section>

    <section class="card">
        <h2>Enam bulan terakhir</h2>
        <?php $max = max(1, ...array_map(static fn ($t) => max($t['masuk'], $t['keluar']), $trend)); ?>
        <div class="bars" role="img" aria-label="Grafik kas masuk dan keluar enam bulan terakhir">
            <?php foreach ($trend as $t): ?>
                <div class="bar-col">
                    <div class="bar-pair">
                        <i class="b-in" style="height:<?= round($t['masuk'] / $max * 100) ?>%" title="Masuk <?= rupiah($t['masuk']) ?>"></i>
                        <i class="b-out" style="height:<?= round($t['keluar'] / $max * 100) ?>%" title="Keluar <?= rupiah($t['keluar']) ?>"></i>
                    </div>
                    <span><?= esc(substr($t['label'], 0, 3)) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="legend"><i class="b-in"></i> Masuk <i class="b-out"></i> Keluar</p>
    </section>
</div>

<section class="card">
    <div class="row-between"><h2>Transaksi terbaru</h2><a href="<?= site_url('transactions') ?>">Lihat semua</a></div>
    <?php if (! $recent): ?>
        <p class="empty">Belum ada transaksi. Catat kas masuk atau keluar pertama untuk mulai.</p>
    <?php else: ?>
    <div class="table-wrap"><table class="ledger">
        <thead><tr><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th class="num">Jumlah</th></tr></thead>
        <tbody>
        <?php foreach ($recent as $r): ?>
            <tr>
                <td><?= tgl($r['trx_date']) ?></td>
                <td><?= esc($r['description'] ?: '-') ?></td>
                <td><?= esc($r['category_name']) ?></td>
                <td class="num <?= $r['type'] === 'masuk' ? 'in' : 'out' ?>"><?= $r['type'] === 'masuk' ? '+' : '−' ?> <?= rupiah($r['amount']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
