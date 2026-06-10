<?php
require 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Method tidak diizinkan']);
    exit;
}

$nama        = trim($_POST['nama'] ?? '');
$wa          = trim($_POST['wa'] ?? '');
$ml_id       = trim($_POST['ml_id'] ?? '');
$rank_now    = trim($_POST['rank_now'] ?? '');
$rank_target = trim($_POST['rank_target'] ?? '');
$paket       = trim($_POST['paket'] ?? '');
$catatan     = trim($_POST['catatan'] ?? '');

if (!$nama || !$wa || !$ml_id || !$rank_now || !$rank_target || !$paket) {
    echo json_encode(['ok' => false, 'msg' => 'Lengkapi semua field wajib']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO pesanan (nama, wa, ml_id, rank_now, rank_target, paket, catatan)
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('sssssss', $nama, $wa, $ml_id, $rank_now, $rank_target, $paket, $catatan);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'id' => $stmt->insert_id]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Gagal menyimpan: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
