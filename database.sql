-- KasKantor: skema + data contoh (MySQL / MariaDB)
-- Import lewat phpMyAdmin jika hosting tidak punya akses terminal.
-- Jangan dipakai bersamaan dengan 'php spark migrate' pada database yang sama.
-- Password akun contoh: password123 (WAJIB diganti setelah login pertama).

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS audit_logs, transactions, categories, accounts, users;

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'viewer',
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE accounts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(20) NOT NULL,
  name VARCHAR(100) NOT NULL,
  type VARCHAR(20) NOT NULL DEFAULT 'kas',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  type VARCHAR(10) NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE transactions (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  trx_date DATE NOT NULL,
  type VARCHAR(10) NOT NULL,
  account_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  amount DECIMAL(15,2) NOT NULL,
  description VARCHAR(255) NULL,
  created_by INT UNSIGNED NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id),
  KEY trx_date (trx_date),
  CONSTRAINT fk_trx_account FOREIGN KEY (account_id) REFERENCES accounts (id),
  CONSTRAINT fk_trx_category FOREIGN KEY (category_id) REFERENCES categories (id),
  CONSTRAINT fk_trx_user FOREIGN KEY (created_by) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE audit_logs (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NULL,
  action VARCHAR(30) NOT NULL,
  table_name VARCHAR(50) NOT NULL,
  record_id INT UNSIGNED NULL,
  detail VARCHAR(255) NULL,
  ip_address VARCHAR(45) NULL,
  created_at DATETIME NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (id,name,email,password_hash,role,created_at,updated_at) VALUES ('1','Administrator','admin@kaskantor.test','$2y$10$KW1c5mDBSFpa6WXKhPx5Du4P0K190LoVbR0CHog7Xpb4oHJJE6xRi','admin','2026-11-02 14:52:19','2026-11-02 14:52:19');
INSERT INTO users (id,name,email,password_hash,role,created_at,updated_at) VALUES ('2','Kasir Kantor','kasir@kaskantor.test','$2y$10$llPJfjUTOpbltj7C.L/1UOzhfxHa8GIOW/95deapZpAW6d49zmo16','kasir','2026-11-02 14:52:19','2026-11-02 14:52:19');
INSERT INTO users (id,name,email,password_hash,role,created_at,updated_at) VALUES ('3','Pimpinan','viewer@kaskantor.test','$2y$10$w0HpeqCWfOLrPrqhQhHDF.4nSuMQm2O6M8dfMBjvpKq7g4HC0D1Kq','viewer','2026-11-02 14:52:19','2026-11-02 14:52:19');

INSERT INTO accounts (id,code,name,type,is_active,created_at,updated_at) VALUES ('1','1-100','Kas Kecil','kas','1','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO accounts (id,code,name,type,is_active,created_at,updated_at) VALUES ('2','1-110','Kas Besar','kas','1','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO accounts (id,code,name,type,is_active,created_at,updated_at) VALUES ('3','1-200','Bank BRI Operasional','bank','1','2026-11-02 14:52:20','2026-11-02 14:52:20');

INSERT INTO categories (id,name,type,created_at,updated_at) VALUES ('1','Penjualan','masuk','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO categories (id,name,type,created_at,updated_at) VALUES ('2','Jasa & Servis','masuk','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO categories (id,name,type,created_at,updated_at) VALUES ('3','Pendapatan Lain','masuk','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO categories (id,name,type,created_at,updated_at) VALUES ('4','Gaji & Honor','keluar','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO categories (id,name,type,created_at,updated_at) VALUES ('5','Listrik, Air & Internet','keluar','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO categories (id,name,type,created_at,updated_at) VALUES ('6','ATK & Perlengkapan','keluar','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO categories (id,name,type,created_at,updated_at) VALUES ('7','Transportasi','keluar','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO categories (id,name,type,created_at,updated_at) VALUES ('8','Konsumsi','keluar','2026-11-02 14:52:20','2026-11-02 14:52:20');

INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('1','2026-08-19','masuk','2','1','12500000','Penjualan paket layanan bulan lalu','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('2','2026-08-24','keluar','2','4','6000000','Honor staf','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('3','2026-08-28','keluar','1','6','350000','Beli kertas dan tinta printer','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('4','2026-09-03','masuk','3','2','3200000','Servis jaringan klien','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('5','2026-09-08','keluar','3','5','1450000','Tagihan listrik dan internet','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('6','2026-09-15','masuk','2','1','9800000','Penjualan perangkat','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('7','2026-09-19','keluar','1','8','275000','Konsumsi rapat','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('8','2026-09-23','keluar','2','4','6000000','Honor staf','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('9','2026-09-30','masuk','3','3','500000','Bunga bank','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('10','2026-10-05','keluar','1','7','180000','Bensin kunjungan klien','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('11','2026-10-12','masuk','2','2','4100000','Jasa instalasi','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('12','2026-10-15','keluar','3','5','1520000','Tagihan listrik dan internet','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('13','2026-10-21','keluar','1','6','420000','Perlengkapan kantor','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('14','2026-10-24','masuk','2','1','11300000','Penjualan bulan berjalan','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('15','2026-10-27','keluar','2','4','6000000','Honor staf','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('16','2026-10-30','keluar','1','8','190000','Konsumsi tamu','2','2026-11-02 14:52:20','2026-11-02 14:52:20');
INSERT INTO transactions (id,trx_date,type,account_id,category_id,amount,description,created_by,created_at,updated_at) VALUES ('17','2026-11-01','masuk','3','2','2750000','Jasa maintenance','2','2026-11-02 14:52:20','2026-11-02 14:52:20');

SET FOREIGN_KEY_CHECKS = 1;
