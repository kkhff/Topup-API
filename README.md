# TopUp API - Payment Gateway Integration

Sebuah RESTful API untuk sistem top-up saldo pengguna yang terintegrasi langsung dengan payment gateway Midtrans.

**Catatan:** Repository ini murni berisi **Backend (REST API)**. Didesain secara *headless* agar siap dikonsumsi oleh berbagai platform Frontend (React, Vue, Mobile App, dll).

## Fitur Utama
* **RESTful Endpoint:** Struktur URL yang rapi untuk manajemen Top-up.
* **Midtrans Snap Integration:** Pembuatan transaksi (Order ID) dan pengembalian Snap Token secara otomatis.
* **Secure Webhook Listener:** Menangkap notifikasi pembayaran dari server Midtrans (dilengkapi validasi Signature Key / SHA512).
* **Auto-Update Balance:** Penambahan saldo user secara *real-time* saat pembayaran sukses (Settlement).
* **Transaction History:** API untuk melihat riwayat transaksi pengguna beserta statusnya.

## Teknologi yang Digunakan
* **Framework:** Laravel (PHP 8+)
* **Database:** MySQL
* **Payment Gateway:** Midtrans (Sandbox)
* **Testing & Tunneling:** Postman & Ngrok

**[Klik di sini untuk melihat Dokumentasi Postman LaporHub API]**  
https://crimson-satellite-1456435.postman.co/workspace/kkh's-Workspace~513eca4e-75f6-45a2-8afd-b1b7c048edb9/collection/51063118-116fbec1-6770-42af-8fdb-91627c84cff6?action=share&source=copy-link&creator=51063118

## Cara Menjalankan Project (Lokal)

**1. Clone Repository:**
```bash
git clone https://github.com/kkhff/LaporHub-API.git
cd LaporHub-API
```

**2. Setup Environment**
```bash
cp .env.example .env
```

**3. Install Dependencies:** Jika kamu memiliki PHP dan Composer lokal:
```bash
composer install
```
Jika kamu **hanya ingin menggunakan** Docker (Tanpa install PHP di lokal):
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

**4. Jalankan Docker Sail**
```bash
./vendor/bin/sail up -d
```

**5. Generate Key & Migrate**
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan storage:link
```
