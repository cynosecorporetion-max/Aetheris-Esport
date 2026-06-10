<?php
require 'config.php';
$res = $conn->query("SELECT * FROM pesanan ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin — Daftar Pesanan</title>
<style>
  body { font-family: Arial, sans-serif; background:#0E1020; color:#E8EAFF; padding:2rem; }
  h1 { color:#FFD700; }
  table { width:100%; border-collapse: collapse; background:#1A1F35; margin-top:1rem; }
  th, td { padding:.7rem; border-bottom:1px solid #2a3050; text-align:left; font-size:.9rem; }
  th { background:#212744; color:#FFD700; text-transform:uppercase; font-size:.75rem; letter-spacing:1px; }
  tr:hover { background:#212744; }
  .status { padding:.2rem .6rem; border-radius:3px; font-size:.75rem; font-weight:bold; }
  .pending { background:#FFA500; color:#000; }
  .diproses { background:#00D4FF; color:#000; }
  .selesai { background:#2ECC71; color:#000; }
  .dibatalkan { background:#FF3B3B; color:#fff; }
</style>
</head>
<body>
  <h1>📋 Daftar Pesanan Joki</h1>
  <p>Total: <?= $res->num_rows ?> pesanan</p>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Tanggal</th><th>Nama</th><th>WA</th><th>ML ID</th>
        <th>Rank Now</th><th>Target</th><th>Paket</th><th>Catatan</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php while($r = $res->fetch_assoc()): ?>
      <tr>
        <td>#<?= $r['id'] ?></td>
        <td><?= $r['created_at'] ?></td>
        <td><?= htmlspecialchars($r['nama']) ?></td>
        <td><?= htmlspecialchars($r['wa']) ?></td>
        <td><?= htmlspecialchars($r['ml_id']) ?></td>
        <td><?= htmlspecialchars($r['rank_now']) ?></td>
        <td><?= htmlspecialchars($r['rank_target']) ?></td>
        <td><?= htmlspecialchars($r['paket']) ?></td>
        <td><?= htmlspecialchars($r['catatan']) ?></td>
        <td><span class="status <?= $r['status'] ?>"><?= $r['status'] ?></span></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
