CREATE TABLE IF NOT EXISTS `tim_turnamen` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_tim` VARCHAR(255) NOT NULL,
  `nama_ketua` VARCHAR(255) NOT NULL,
  `wa_ketua` VARCHAR(50) NOT NULL,
  `anggota_list` TEXT NOT NULL, -- Untuk menyimpan nama-nama anggota tim
  `status_bayar` ENUM('pending', 'lunas') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;