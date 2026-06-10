<?php
require 'config.php';

$notif_sukses = "";
$notif_gagal = "";

// Proses ketika tombol "Kirim Pendaftaran" diklik
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_daftar'])) {
    // Ambil data dari form dan bersihkan dari karakter berbahaya
    $nama_tim    = mysqli_real_escape_string($conn, $_POST['nama_tim']);
    $nama_kapten = mysqli_real_escape_string($conn, $_POST['nama_kapten']);
    $whatsapp    = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $anggota_1   = mysqli_real_escape_string($conn, $_POST['anggota_1']);
    $anggota_2   = mysqli_real_escape_string($conn, $_POST['anggota_2']);
    $anggota_3   = mysqli_real_escape_string($conn, $_POST['anggota_3']);
    $anggota_4   = mysqli_real_escape_string($conn, $_POST['anggota_4']);
    $cadangan    = mysqli_real_escape_string($conn, $_POST['cadangan']);

    // Validasi input tidak boleh kosong (kecuali cadangan)
    if (empty($nama_tim) || empty($nama_kapten) || empty($whatsapp) || empty($anggota_1) || empty($anggota_2) || empty($anggota_3) || empty($anggota_4)) {
        $notif_gagal = "Semua kolom data wajib diisi (kecuali pemain cadangan)!";
    } else {
        // Query insert menggunakan MySQLi murni yang aman
        $query_insert = "INSERT INTO tim_turnamen (nama_tim, nama_kapten, whatsapp, anggota_1, anggota_2, anggota_3, anggota_4, cadangan) 
                         VALUES ('$nama_tim', '$nama_kapten', '$whatsapp', '$anggota_1', '$anggota_2', '$anggota_3', '$anggota_4', '$cadangan')";
        
        if ($conn->query($query_insert)) {
            $notif_sukses = "Pendaftaran Tim <strong>" . htmlspecialchars($nama_tim) . "</strong> Berhasil! Silakan hubungi admin untuk konfirmasi pembayaran.";
        } else {
            $notif_gagal = "Gagal mendaftar: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Turnamen — AETHERIS</title>
    <style>
        /* --- STYLING FORM PENDAFTARAN ESPORT (TEMA GELAP & EMAS) --- */
        body {
            background-color: #0c0f17;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem 1.5rem 5rem 1.5rem;
        }

        /* Tombol Kembali */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #a0aec0;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
            margin-bottom: 2rem;
            padding: 0.5rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.01);
        }

        .btn-back:hover {
            color: #FFD700;
            border-color: #FFD700;
            background: rgba(255, 215, 0, 0.02);
        }

        /* Header Form */
        .title-section {
            text-align: center;
            margin-bottom: 3rem;
        }

        .title-section h2 {
            font-size: 0.85rem;
            color: #FFD700;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .title-section h1 {
            font-size: 2.2rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
            background: linear-gradient(135deg, #ffffff 0%, #a0aec0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Box Notifikasi */
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 2rem;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .alert-sukses {
            background-color: rgba(72, 187, 120, 0.1);
            border: 1px solid #48bb78;
            color: #48bb78;
        }
        .alert-gagal {
            background-color: rgba(245, 101, 101, 0.1);
            border: 1px solid #f56565;
            color: #f56565;
        }

        /* Form Card */
        .form-wrapper {
            background: #131926;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .form-section-title {
            font-size: 1.1rem;
            color: #FFD700;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            color: #cbd5e0;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            box-sizing: border-box;
            background-color: #0c0f17;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            padding: 0.75rem 1rem;
            color: #ffffff;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #FFD700;
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        /* Tombol Kirim */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #FFD700 0%, #FF8C00 100%);
            color: #0c0f17;
            border: none;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            opacity: 0.9;
        }

        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .form-wrapper {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid rgba(72, 223, 196, 0.15); padding: 25px 20px 20px 20px; font-family: sans-serif; box-sizing: border-box;">
    
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
    </div>

    <div style="text-align: right; background: rgba(72, 223, 196, 0.03); border: 1px solid rgba(72, 223, 196, 0.15); padding: 8px 16px; border-radius: 6px; font-family: sans-serif;">
        <span style="display: block; font-size: 0.7rem; color: #48dfc4; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Contact Person Admin</span>
        <a href="https://wa.me/6285136236681" target="_blank" style="color: #fff; text-decoration: none; font-size: 0.9rem; font-weight: bold; display: flex; align-items: center; gap: 6px; justify-content: flex-end;">
            💬 +62 851-3623-6681
        </a>
    </div>
</div>

    <div class="container">
        

        <div class="title-section">
            <h2>Aetheris Grand Opening</h2>
            <h1>Form Pendaftaran Tim</h1>
        </div>

        <?php if (!empty($notif_sukses)): ?>
            <div class="alert alert-sukses">
                <?php echo $notif_sukses; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($notif_gagal)): ?>
            <div class="alert alert-gagal">
                <?php echo $notif_gagal; ?>
            </div>
        <?php endif; ?>

        <div class="form-wrapper">
            <form action="" method="POST">
                
                <div class="form-section-title">Informasi Tim & Kontak</div>
                
                <div class="form-group">
                    <label for="nama_tim">Nama Tim / Squad *</label>
                    <input type="text" id="nama_tim" name="nama_tim" placeholder="Contoh: Aetheris Esports" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_kapten">Nama / Nickname Kapten *</label>
                        <input type="text" id="nama_kapten" name="nama_kapten" placeholder="Contoh: Shixuan" required>
                    </div>
                    <div class="form-group">
                        <label for="whatsapp">No. WhatsApp Aktif *</label>
                        <input type="tel" id="whatsapp" name="whatsapp" placeholder="Contoh: 082132517461" required>
                    </div>
                </div>

                <div class="form-section-title" style="margin-top: 2.5rem;">Susunan Pemain (Nick / ID)</div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="anggota_1">Pemain 2 *</label>
                        <input type="text" id="anggota_1" name="anggota_1" placeholder="Nickname + ID" required>
                    </div>
                    <div class="form-group">
                        <label for="anggota_2">Pemain 3 *</label>
                        <input type="text" id="anggota_2" name="anggota_2" placeholder="Nickname + ID" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="anggota_3">Pemain 4 *</label>
                        <input type="text" id="anggota_3" name="anggota_3" placeholder="Nickname + ID" required>
                    </div>
                    <div class="form-group">
                        <label for="anggota_4">Pemain 5 *</label>
                        <input type="text" id="anggota_4" name="anggota_4" placeholder="Nickname + ID" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cadangan">Pemain Cadangan (Opsional)</label>
                    <input type="text" id="cadangan" name="cadangan" placeholder="Nickname + ID (Jika Ada)">
                </div>

                <button type="submit" name="submit_daftar" class="btn-submit">⚡ Kirim Pendaftaran Tim</button>

            </form>
        </div>

    </div>

</body>
</html>