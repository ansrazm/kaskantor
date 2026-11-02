<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Jejak audit<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-head"><h1>Jejak audit</h1></div>
<section class="card">
    <?php if (! $rows): ?><p class="empty">Belum ada aktivitas tercatat.</p><?php else: ?>
    <div class="table-wrap"><table class="ledger">
        <thead><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Data</th><th>Detail</th><th>IP</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= esc($r['created_at']) ?></td>
                <td><?= esc($r['user_name'] ?? '-') ?></td>
                <td><?= esc($r['action']) ?></td>
                <td><?= esc($r['table_name']) ?><?= $r['record_id'] ? ' #' . (int) $r['record_id'] : '' ?></td>
                <td><?= esc($r['detail'] ?? '') ?></td>
                <td><?= esc($r['ip_address'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?= $pager->links() ?>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
