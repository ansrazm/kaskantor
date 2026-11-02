<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Laporan bulanan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head">
    <h1>Laporan <?= nama_bulan($month) ?> <?= $year ?></h1>
    <div class="head-actions no-print">
        <a class="btn" href="<?= site_url('reports/csv') . '?month=' . $month . '&year=' . $year ?>">Unduh CSV</a>
        <button class="btn" type="button" onclick="window.print()">Cetak</button>
    </div>
</div>

<form method="get" class="card filters no-print">
    <label>Bulan
        <select name="month">
            <?php for ($i = 1; $i <= 12; $i++): ?><option value="<?= $i ?>" <?= $i === $month ? 'selected' : '' ?>><?= nama_bulan($i) ?></option><?php endfor; ?>
        </select>
    </label>
    <label>Tahun <input type="number" name="year" value="<?= $year ?>" min="2000" max="2100"></label>
    <div class="filter-actions"><button class="btn" type="submit">Tampilkan</button></div>
</form>

<section class="card">
    <table class="ledger summary">
        <tbody>
            <tr><td>Saldo awal</td><td class="num"><?= rupiah($opening) ?></td></tr>
            <tr><td>Kas masuk</td><td class="num in">+ <?= rupiah($period['masuk']) ?></td></tr>
            <tr><td>Kas keluar</td><td class="num out">− <?= rupiah($period['keluar']) ?></td></tr>
        </tbody>
        <tfoot><tr><td>Saldo akhir</td><td class="num"><?= rupiah($closing) ?></td></tr></tfoot>
    </table>
</section>

<div class="grid2">
    <section class="card">
        <h2>Per kategori</h2>
        <?php if (! $cats): ?><p class="empty">Belum ada transaksi di bulan ini.</p><?php else: ?>
        <table class="ledger"><tbody>
        <?php foreach ($cats as $c): ?>
            <tr><td><?= esc($c['name']) ?></td><td class="num <?= $c['type'] === 'masuk' ? 'in' : 'out' ?>"><?= rupiah($c['total']) ?></td></tr>
        <?php endforeach; ?>
        </tbody></table>
        <?php endif; ?>
    </section>
    <section class="card">
        <h2>Daftar transaksi</h2>
        <?php if (! $rows): ?><p class="empty">Belum ada transaksi di bulan ini.</p><?php else: ?>
        <div class="table-wrap"><table class="ledger"><tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= tgl($r['trx_date']) ?></td>
                <td><?= esc($r['description'] ?: $r['category_name']) ?></td>
                <td class="num <?= $r['type'] === 'masuk' ? 'in' : 'out' ?>"><?= $r['type'] === 'masuk' ? '+' : '−' ?> <?= rupiah($r['amount']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody></table></div>
        <?php endif; ?>
    </section>
</div>
<?= $this->endSection() ?>
