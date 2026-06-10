<?php

session_start();

// Jika belum login (belum terdaftar/belum masuk), baru ditendang balik ke halaman utama
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: index.php");
    exit;
}
require 'config.php';

// Ambil ID turnamen dari URL, jika tidak ada set default ke turnamen pertama (1)
$id_tur = isset($_GET['id_tur']) ? (int)$_GET['id_tur'] : 1;

$list_tim = [];
$slot_penuh = false;
$max_slot = 8; 
$nama_turnamen_aktif = "Aetheris Tournament";

try {
    // 1. Ambil info detail turnamen berdasarkan ID
    $query_tur = "SELECT nama_turnamen, kuota_maksimal FROM daftar_turnamen WHERE id = $id_tur";
    $res_tur = $conn->query($query_tur);
    if ($res_tur && $res_tur->num_rows > 0) {
        $data_tur = $res_tur->fetch_assoc();
        $max_slot = !empty($data_tur['kuota_maksimal']) ? (int)$data_tur['kuota_maksimal'] : 8;
        $nama_turnamen_aktif = $data_tur['nama_turnamen'];
    }

    // 2. Ambil list tim yang mendaftar KHUSUS untuk ID turnamen ini
    $query_tim = "SELECT nama_tim FROM tim_turnamen WHERE id_turnamen = $id_tur ORDER BY id ASC LIMIT $max_slot";
    $result_tim = $conn->query($query_tim);

    if ($result_tim) {
        while ($row = $result_tim->fetch_assoc()) {
            $list_tim[] = $row['nama_tim'];
        }
    }

    // Cek kuota penuh
    if (count($list_tim) >= $max_slot) {
        $slot_penuh = true;
    }
} catch (Exception $e) {
    // Jalankan fallback jika kolom belum dibuat
    $query_fallback = "SELECT nama_tim FROM tim_turnamen ORDER BY id ASC LIMIT 8";
    $result_fallback = $conn->query($query_fallback);
    if ($result_fallback) {
        while ($row = $result_fallback->fetch_assoc()) {
            $list_tim[] = $row['nama_tim'];
        }
    }
}

// Data placeholder tim penampung bagan
$tim1 = $list_tim[0] ?? "Menunggu Tim 1...";
$tim2 = $list_tim[1] ?? "Menunggu Tim 2...";
$tim3 = $list_tim[2] ?? "Menunggu Tim 3...";
$tim4 = $list_tim[3] ?? "Menunggu Tim 4...";
$tim5 = $list_tim[4] ?? "Menunggu Tim 5...";
$tim6 = $list_tim[5] ?? "Menunggu Tim 6...";
$tim7 = $list_tim[6] ?? "Menunggu Tim 7...";
$tim8 = $list_tim[7] ?? "Menunggu Tim 8...";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bagan Pertandingan — AETHERIS</title>
    <style>
        body {
            background-color: #0c0f17;
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .btn-back {
            display: inline-flex;
            color: #a0aec0;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2rem;
            padding: 0.5rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.01);
        }
        .btn-back:hover { color: #FFD700; border-color: #FFD700; }
        .title-section { text-align: center; margin-bottom: 3rem; }
        .title-section h1 { font-size: 2.2rem; text-transform: uppercase; margin: 0; color: #fff; }
        .title-section h2 { font-size: 1.1rem; color: #FFD700; margin-top: 0.5rem; }
        .status-badge { display: inline-block; padding: 0.4rem 1.2rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700; margin-top: 1rem; }
        .status-lock { background: rgba(72, 187, 120, 0.1); border: 1px solid #48bb78; color: #48bb78; }
        .status-wait { background: rgba(237, 137, 54, 0.1); border: 1px solid #ed8936; color: #ed8936; }
        .bracket-wrapper { display: flex; justify-content: space-between; align-items: center; background: #131926; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 3rem 2rem; overflow-x: auto; gap: 2rem; }
        .round { display: flex; flex-direction: column; justify-content: space-around; height: 440px; width: 220px; }
        .matchup { display: flex; flex-direction: column; background: #0c0f17; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 6px; overflow: hidden; }
        .slot-team { padding: 0.75rem 1rem; font-size: 0.9rem; font-weight: 600; border-bottom: 1px solid rgba(255, 255, 255, 0.05); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .slot-team:last-child { border-bottom: none; }
        .team-ready { color: #ffffff; background: rgba(255, 215, 0, 0.02); }
        .team-empty { color: #4a5568; font-style: italic; }
        .round-title { text-align: center; color: #FFD700; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid rgba(72, 223, 196, 0.15); padding: 25px 20px; margin-bottom: 35px; font-family: sans-serif; box-sizing: border-box;">
    
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="index.php" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: #fff; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
            <img src="logo remove.png" alt="Aetheris Logo" style="width: 40px; height: 40px; object-fit: contain;">
            <div>
                <h1 style="font-size: 1.4rem; font-weight: 800; margin: 0; letter-spacing: 1.5px; line-height: 1.2;">
                    AETHERIS
                </h1>
                <p style="margin: 2px 0 0 0; font-size: 0.7rem; color: rgba(255, 255, 255, 0.4); letter-spacing: 0.5px; text-transform: uppercase; font-weight: bold;">
                    ← Kembali ke Beranda
                </p>
            </div>
        </a>

        <button onclick="openModal()" style="width: 26px; height: 26px; border-radius: 50%; background: rgba(72, 223, 196, 0.1); border: 1px solid rgba(72, 223, 196, 0.5); color: #48dfc4; font-weight: bold; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; justify-content: center; margin-left: 8px; transition: all 0.3s;" onmouseover="this.style.background='#48dfc4'; this.style.color='#0b071e';" title="Detail & Aturan Turnamen">
            ?
        </button>
    </div>

    <div style="text-align: right; background: rgba(72, 223, 196, 0.03); border: 1px solid rgba(72, 223, 196, 0.15); padding: 8px 16px; border-radius: 6px; font-family: sans-serif;">
        <span style="display: block; font-size: 0.7rem; color: #48dfc4; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Contact Person Admin</span>
        <a href="https://wa.me/6285136236681" target="_blank" style="color: #fff; text-decoration: none; font-size: 0.9rem; font-weight: bold; display: flex; align-items: center; gap: 6px; justify-content: flex-end;">
            💬 +62 851-3623-6681
        </a>
    </div>
</div>

<div id="tournamentModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(11, 7, 30, 0.85); backdrop-filter: blur(5px); font-family: sans-serif;">
    <div style="background: #16112d; margin: 10% auto; padding: 30px; border: 1px solid rgba(72, 223, 196, 0.3); width: 80%; max-width: 550px; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.7); position: relative; color: #fff;">
        
        <span onclick="closeModal()" style="position: absolute; right: 20px; top: 15px; color: rgba(255,255,255,0.5); font-size: 28px; font-weight: bold; cursor: pointer;" onmouseover="this.style.color='#ef4444'">&times;</span>
        
        <h3 style="color: #48dfc4; margin-top: 0; border-bottom: 1px solid rgba(72, 223, 196, 0.2); padding-bottom: 10px; font-size: 1.4rem; text-transform: uppercase; letter-spacing: 1px;">📢 Detail & Aturan Turnamen</h3>
        
        <div style="margin-top: 20px; line-height: 1.6; font-size: 0.95rem;">
            <p><strong>Format Pertandingan:</strong> Best of 3 (BO3) - Sistem Gugur.</p>
            
            <p style="margin-bottom: 5px; color: #fdbf11; font-weight: bold;">🎁 PRIZE POOL TOURNAMENT:</p>
            <ul style="margin-top: 0; padding-left: 20px; color: rgba(255,255,255,0.9);">
                <li><strong style="color: #fff;">Juara 1:</strong> 5 WDP (Weekly Diamond Pass) + E-Certificate</li>
                <li><strong style="color: #fff;">Juara 2:</strong> E-Certificate Resmi Aetheris</li>
                <li><strong style="color: #fff;">Juara 3:</strong> E-Certificate Resmi Aetheris</li>
            </ul>

            <p style="margin-bottom: 5px; color: #48dfc4; font-weight: bold;">⚖️ Aturan Umum Pertandingan:</p>
            <ol style="margin-top: 0; padding-left: 20px; color: rgba(255,255,255,0.8); font-size: 0.9rem;">
                <li>Toleransi keterlambatan tim maksimal 10 menit dari jadwal.</li>
                <li>Dilarang keras menggunakan cheat, bug, atau program ilegal ketiga.</li>
                <li>Segala bentuk tindakan provokasi/toxic berlebih akan dikenakan diskualifikasi.</li>
                <li>Keputusan panitia dan admin Aetheris bersifat mutlak.</li>
            </ol>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('tournamentModal').style.display = 'block';
}
function closeModal() {
    document.getElementById('tournamentModal').style.display = 'none';
}
// Menutup pop-up otomatis jika user mengklik area luar kotak hitam modal
window.onclick = function(event) {
    var modal = document.getElementById('tournamentModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

    <div class="container">

        <div class="title-section">
            <h1>Bracket Turnamen Resmi</h1>
            <h2><?= htmlspecialchars($nama_turnamen_aktif); ?></h2>
            
            <?php if ($slot_penuh): ?>
                <span class="status-badge status-lock">🔒 Slot Penuh — Bracket Terkunci</span>
            <?php else: ?>
                <span class="status-badge status-wait">⏳ Menunggu Slot Penuh (<?= count($list_tim); ?>/<?= $max_slot ?>)</span>
            <?php endif; ?>
        </div>

        <div class="bracket-wrapper">
            <div>
                <div class="round-title">Perempat Final</div>
                <div class="round">
                    <div class="matchup">
                        <div class="slot-team <?= isset($list_tim[0]) ? 'team-ready' : 'team-empty'; ?>"><?= htmlspecialchars($tim1); ?></div>
                        <div class="slot-team <?= isset($list_tim[1]) ? 'team-ready' : 'team-empty'; ?>"><?= htmlspecialchars($tim2); ?></div>
                    </div>
                    <div class="matchup">
                        <div class="slot-team <?= isset($list_tim[2]) ? 'team-ready' : 'team-empty'; ?>"><?= htmlspecialchars($tim3); ?></div>
                        <div class="slot-team <?= isset($list_tim[3]) ? 'team-ready' : 'team-empty'; ?>"><?= htmlspecialchars($tim4); ?></div>
                    </div>
                    <div class="matchup">
                        <div class="slot-team <?= isset($list_tim[4]) ? 'team-ready' : 'team-empty'; ?>"><?= htmlspecialchars($tim5); ?></div>
                        <div class="slot-team <?= isset($list_tim[5]) ? 'team-ready' : 'team-empty'; ?>"><?= htmlspecialchars($tim6); ?></div>
                    </div>
                    <div class="matchup">
                        <div class="slot-team <?= isset($list_tim[6]) ? 'team-ready' : 'team-empty'; ?>"><?= htmlspecialchars($tim7); ?></div>
                        <div class="slot-team <?= isset($list_tim[7]) ? 'team-ready' : 'team-empty'; ?>"><?= htmlspecialchars($tim8); ?></div>
                    </div>
                </div>
            </div>

            <div>
                <div class="round-title">Semi Final</div>
                <div class="round">
                    <div class="matchup" style="margin-bottom: 2rem;">
                        <div class="slot-team team-empty">Pemenang Match 1</div>
                        <div class="slot-team team-empty">Pemenang Match 2</div>
                    </div>
                    <div class="matchup">
                        <div class="slot-team team-empty">Pemenang Match 3</div>
                        <div class="slot-team team-empty">Pemenang Match 4</div>
                    </div>
                </div>
            </div>

            <div>
                <div class="round-title">Grand Final</div>
                <div class="round">
                    <div class="matchup">
                        <div class="slot-team team-empty" style="color: #FFD700;">Finalis 1</div>
                        <div class="slot-team team-empty" style="color: #FFD700;">Finalis 2</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>