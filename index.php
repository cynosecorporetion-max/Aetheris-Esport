-- =====================================================
-- Database: shixuan_mlbb
-- Untuk website Joki MLBB & HOK (shixuan)
-- Jalankan di phpMyAdmin (XAMPP)
-- =====================================================

CREATE DATABASE IF NOT EXISTS `shixuan_mlbb`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `shixuan_mlbb`;

-- =====================================================
-- Tabel: paket (paket harga joki)
-- =====================================================
DROP TABLE IF EXISTS `paket`;
CREATE TABLE `paket` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_paket` VARCHAR(50) NOT NULL,
  `rank_badge` VARCHAR(50) NOT NULL,
  `rank_range` VARCHAR(100) NOT NULL,
  `harga` INT(11) NOT NULL,
  `satuan` VARCHAR(50) NOT NULL DEFAULT 'per tier',
  `fitur` TEXT NOT NULL,
  `featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `paket` (`nama_paket`, `rank_badge`, `rank_range`, `harga`, `satuan`, `fitur`, `featured`) VALUES
('Starter',    'Warrior-Elite', 'Naik dari Warrior -> Elite',   45000,  'per tier',     'Booster berpengalaman|Live update via chat|Garansi 70% win rate|Selesai 1-2 hari|Akun aman & rahasia', 0),
('Pro',        'Epic-Legend',   'Naik dari Epic -> Legend',     75000,  'per tier',     'Booster pro rank tinggi|Live streaming optional|Garansi 75% win rate|Selesai 1 hari|Akun aman & rahasia|Prioritas antrian', 1),
('Elite',      'Legend-Myth',   'Naik dari Legend -> Mythic',   120000, 'per tier',     'Top booster ML Indonesia|Hero preference tersedia|Garansi 80% win rate|Selesai < 24 jam|Akun aman & rahasia|Laporan lengkap per match', 0),
('Mythic Pro', 'Mythic+',       'Mythic -> Mythical Glory',     200000, 'per 100 star', 'Grand Master booster|Duo mode tersedia|Garansi 85% win rate|Support 24 jam|VIP customer service|Free coaching 1 sesi', 0);

-- =====================================================
-- Tabel: pesanan (data order dari form)
-- =====================================================
DROP TABLE IF EXISTS `pesanan`;
CREATE TABLE `pesanan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `wa` VARCHAR(20) NOT NULL,
  `ml_id` VARCHAR(50) NOT NULL,
  `rank_now` VARCHAR(50) NOT NULL,
  `rank_target` VARCHAR(50) NOT NULL,
  `paket` VARCHAR(100) NOT NULL,
  `catatan` TEXT DEFAULT NULL,
  `status` ENUM('pending','diproses','selesai','dibatalkan') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabel: testimoni
-- =====================================================
DROP TABLE IF EXISTS `testimoni`;
CREATE TABLE `testimoni` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `inisial` VARCHAR(5) NOT NULL,
  `rank_progress` VARCHAR(100) NOT NULL,
  `isi` TEXT NOT NULL,
  `rating` TINYINT(1) NOT NULL DEFAULT 5,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `testimoni` (`nama`, `inisial`, `rank_progress`, `isi`, `rating`) VALUES
('Rangga D.', 'RD', 'Legend -> Mythic',           'Serius terpercaya banget! Udah 3x pake MLBoost, dari Epic ke Mythic lancar. Boosternya fast response dan akun aman. Auto balik lagi!', 5),
('Sinta N.',  'SN', 'Epic -> Legend',             'Keren banget pelayanannya! Order jam 10 malam, besok paginya udah naik 2 tier. Win rate-nya bagus banget.', 5),
('Bagas W.',  'BW', 'Mythic -> Mythical Glory',   'Garansi beneran ada! Sempet khawatir akun kena ban, tapi aman-aman aja. Progress dilaporin tiap match. Worth it banget.', 5),
('Aldi P.',   'AP', 'Warrior -> Epic',            'Harga paling kompetitif dibanding tempat lain. Selesai tepat waktu, komunikasi boosternya juga aktif. Definitely recommended!', 4),
('Fauzi H.',  'FH', 'Legend -> Mythic',           'Udah cobain banyak joki, tapi MLBoost tetep yang terbaik. Hero preference diikutin, mainnya juga rapih banget. 10/10!', 5),
('Dewi L.',   'DL', 'Elite -> Legend',            'Customer servicenya ramah dan fast respon. Order malem hari juga langsung diproses. Akun aman, nggak ada yang aneh-aneh.', 5);

-- =====================================================
-- Tabel: faq
-- =====================================================
DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pertanyaan` VARCHAR(255) NOT NULL,
  `jawaban` TEXT NOT NULL,
  `urutan` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `faq` (`pertanyaan`, `jawaban`, `urutan`) VALUES
('Apakah akun saya aman selama proses joki?', 'Ya, 100% aman. Booster kami menggunakan VPN lokal Indonesia sehingga server tidak mendeteksi login dari lokasi asing. Kami juga tidak pernah mengubah data akun (email, password, PIN) tanpa izin kamu.', 1),
('Berapa lama proses joki biasanya?', 'Tergantung paket dan target rank. Rata-rata 1-2 hari untuk Warrior-Legend, dan < 24 jam untuk paket Elite & Mythic Pro.', 2),
('Bagaimana cara pembayarannya?', 'Kami menerima transfer BCA, DANA, OVO, GoPay, dan QRIS. Pembayaran dilakukan setelah konfirmasi order via WhatsApp.', 3),
('Apakah ada garansi?', 'Ada. Semua paket include garansi win rate sesuai paket. Jika target rank tidak tercapai, kami refund 100%.', 4),
('Bisa request hero tertentu?', 'Bisa, mulai dari paket Elite ke atas. Tulis preferensi hero di kolom catatan saat order.', 5);

-- =====================================================
-- Tabel: admin (login admin panel)
-- =====================================================
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default login: admin / admin123
INSERT INTO `admin` (`username`, `password`, `nama`) VALUES
('admin', '$2y$10$e0NRfQk9ZQ6xT8m3JxgIcuPpL3hGqJYwR8fK2bV7Q5sZ9YqLgKjGm', 'Administrator');
