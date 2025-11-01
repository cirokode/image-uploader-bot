# Image Uploader (Telegram Bot + Web Upload + Gallery)

**Fitur**
- Upload gambar lewat web (`index.html` -> `upload.php`)
- Upload gambar lewat Bot Telegram (webhook `bot.php`)
- Gallery / daftar gambar (`gallery.php`) dengan tombol copy link
- Proteksi `uploads/` untuk mencegah eksekusi file
- `.gitignore` yang mengecualikan `uploads/` dan `config.php`

**Catatan penting**
- `config.php` berisi `BOT_TOKEN` (sudah diisi sesuai permintaan) dan `BASE_URL`. 
  **Ganti `BASE_URL` dengan domain HTTPS tempat kamu deploy** (contoh: `https://example.com`).
- Jangan commit `config.php` ke GitHub. File ini sudah dicantumkan di `.gitignore`.

**Langkah deploy cepat**
1. Upload seluruh isi folder ke hosting PHP (public_html).
2. Edit `config.php` dan ganti `BASE_URL` dengan domain-mu (wajib `https://`).
3. Set webhook Telegram:
   ```
   bash set_webhook.sh
   ```
   atau pakai:
   ```
   curl -X GET "https://api.telegram.org/bot<YOUR_TOKEN>/setWebhook?url=https://yourdomain.com/bot.php"
   ```
4. Buka `index.html` untuk mencoba upload via web, atau kirim foto ke bot Telegram.

**Tips keamanan**
- Jangan commit token ke repo publik.
- Untuk produksi, pertimbangkan memakai storage object (S3) atau menambahkan autentikasi/gallery per-user.
