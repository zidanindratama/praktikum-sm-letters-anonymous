# Deploy ke Hostinger VPS

Panduan ini menjelaskan cara deploy project `Letters Anonymous` ke `Hostinger VPS` dengan arsitektur:

- `Hostinger VPS` sebagai server utama
- `Nginx` sebagai reverse proxy
- `Docker + Docker Compose` untuk menjalankan aplikasi
- `CodeIgniter 4` sebagai framework backend
- `MySQL Hostinger` sebagai database
- `GitHub Actions` untuk CI/CD deploy otomatis

Panduan ini ditulis berdasarkan setup yang **sudah berhasil dijalankan** untuk domain:

- `https://letters.taxcenterug.com`

## Arsitektur Deploy

Struktur production yang dipakai:

- source code ada di VPS pada `/opt/praktikum-sm-letters-anonymous`
- aplikasi dijalankan lewat container Docker
- container app expose ke host pada `127.0.0.1:3003`
- `nginx` menerima request publik pada `https://letters.taxcenterug.com`
- `nginx` me-forward request ke `127.0.0.1:3003`
- database ada di Hostinger dan diakses secara remote

## Prasyarat

Sebelum mulai, pastikan hal berikut sudah tersedia:

- domain aktif di Hostinger
- VPS aktif dan bisa diakses via SSH
- repository GitHub project sudah tersedia
- Docker dan Docker Compose sudah terpasang di VPS
- MySQL database dan user database sudah dibuat di Hostinger
- akses `sudo` di VPS tersedia

## Informasi yang Dipakai

Contoh data dari setup ini:

- VPS host: `148.230.102.236`
- SSH port: `3190`
- SSH user: `zidan`
- domain project: `letters.taxcenterug.com`
- folder app di VPS: `/opt/praktikum-sm-letters-anonymous`
- port app internal host: `3003`
- hostname MySQL Hostinger: `srv1151.hstgr.io`
- nama database: `u583247040_praktikum_sm`
- username database: `u583247040_zidanindratama`

## Tahap 1: Siapkan SSH ke VPS

Masuk ke VPS:

```bash
ssh -p 3190 zidan@148.230.102.236
```

Kalau ingin membuat SSH key khusus untuk GitHub Actions:

```bash
ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519_github_actions -C "github-actions-letters-anonymous"
```

Lihat public key:

```bash
cat ~/.ssh/id_ed25519_github_actions.pub
```

Tambahkan public key itu ke server jika dibutuhkan, atau simpan private key-nya nanti untuk GitHub Actions secret.

## Tahap 2: Tentukan Folder Deploy

Di VPS, cek port yang masih kosong:

```bash
sudo ss -tulpn | grep 3003
```

Kalau tidak ada output, berarti port `3003` aman dipakai.

Buat folder app:

```bash
sudo mkdir -p /opt/praktikum-sm-letters-anonymous
sudo chown -R zidan:zidan /opt/praktikum-sm-letters-anonymous
```

## Tahap 3: Tambahkan DNS Record di Hostinger

Masuk ke:

- `Hostinger hPanel -> DNS / Nameserver`

Tambahkan record:

- Type: `A`
- Name: `letters`
- Points to: `148.230.102.236`
- TTL: `300`

Setelah itu cek dari VPS:

```bash
nslookup letters.taxcenterug.com
```

Hasil yang diharapkan:

```text
Name:   letters.taxcenterug.com
Address: 148.230.102.236
```

## Tahap 4: Siapkan Nginx di VPS

Buat file konfigurasi nginx:

```bash
sudo nano /etc/nginx/sites-available/letters.taxcenterug.com
```

Isi file:

```nginx
server {
    server_name letters.taxcenterug.com;

    client_max_body_size 10M;

    location / {
        proxy_pass http://127.0.0.1:3003;
        proxy_http_version 1.1;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    listen 80;
}
```

Aktifkan site:

```bash
sudo ln -s /etc/nginx/sites-available/letters.taxcenterug.com /etc/nginx/sites-enabled/letters.taxcenterug.com
```

Test konfigurasi:

```bash
sudo nginx -t
```

Reload nginx:

```bash
sudo systemctl reload nginx
```

## Tahap 5: Pasang SSL dengan Certbot

Jalankan:

```bash
sudo certbot --nginx -d letters.taxcenterug.com
```

Kalau certbot bertanya apakah HTTP ingin di-redirect ke HTTPS, pilih opsi redirect.

Kalau sukses, domain akan aktif di:

- `https://letters.taxcenterug.com`

## Tahap 6: Siapkan Database Hostinger

Di Hostinger, buat database dan user database:

- database: `u583247040_praktikum_sm`
- username: `u583247040_zidanindratama`
- password: buat password yang aman

Jangan gunakan password yang pernah terekspos di screenshot atau chat.

## Tahap 7: Aktifkan Remote MySQL

Karena aplikasi berjalan di VPS dan database berada di Hostinger, akses database harus diizinkan secara remote.

Masuk ke:

- `Hostinger hPanel -> Databases -> Remote MySQL`

Tambahkan:

- IP: `148.230.102.236`
- Database: `u583247040_praktikum_sm`

Hostname MySQL Hostinger yang dipakai:

```text
srv1151.hstgr.io
```

## Tahap 8: Clone Repository ke VPS

Masuk ke VPS:

```bash
cd /opt
sudo rm -rf /opt/praktikum-sm-letters-anonymous
sudo git clone https://github.com/zidanindratama/praktikum-sm-letters-anonymous.git /opt/praktikum-sm-letters-anonymous
sudo chown -R zidan:zidan /opt/praktikum-sm-letters-anonymous
```

Verifikasi:

```bash
ls -la /opt/praktikum-sm-letters-anonymous
```

## Tahap 9: Siapkan File Environment Production

Masuk ke folder project:

```bash
cd /opt/praktikum-sm-letters-anonymous
cp env .env.production
nano .env.production
```

Isi konfigurasi penting:

```ini
CI_ENVIRONMENT = production

app.baseURL = 'https://letters.taxcenterug.com/'

database.default.hostname = srv1151.hstgr.io
database.default.database = u583247040_praktikum_sm
database.default.username = u583247040_zidanindratama
database.default.password = PASSWORD_DB_BARU
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306

admin.username = admin
admin.password_hash = 'HASH_PASSWORD_ADMIN'
```

Untuk membuat hash password admin:

```bash
php -r "echo password_hash('PASSWORD_ADMIN_BARU', PASSWORD_DEFAULT), PHP_EOL;"
```

Simpan hasil hash ke:

```ini
admin.password_hash = '...'
```

Verifikasi konfigurasi non-sensitif:

```bash
grep -E "CI_ENVIRONMENT|app.baseURL|database.default.hostname|database.default.database|database.default.username|database.default.DBDriver|database.default.port|admin.username" .env.production
```

## Tahap 10: Build dan Jalankan Docker

Project ini menggunakan file:

- [Dockerfile](Dockerfile)
- [docker-compose.prod.yml](docker-compose.prod.yml)
- [.dockerignore](.dockerignore)

Jalankan build dan up:

```bash
cd /opt/praktikum-sm-letters-anonymous
docker compose --env-file .env.production -f docker-compose.prod.yml up -d --build
```

Cek container:

```bash
docker ps
```

Kalau container `letters-anonymous-web` muncul dan status-nya `Up`, berarti container hidup.

## Tahap 11: Jalankan Migration dari Dalam Container

Supaya tabel database terbentuk:

```bash
docker exec -it letters-anonymous-web php spark migrate
```

Kalau sukses, output akan menampilkan:

```text
Running all new migrations...
Migrations complete.
```

## Tahap 12: Perbaiki Permission Writable

Karena project memakai bind mount:

- `./writable`
- `./public/uploads`

permission host perlu disesuaikan:

```bash
cd /opt/praktikum-sm-letters-anonymous
sudo chown -R www-data:www-data writable public/uploads
sudo find writable public/uploads -type d -exec chmod 775 {} \;
sudo find writable public/uploads -type f -exec chmod 664 {} \;
```

Lalu restart app:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml up -d
```

## Tahap 13: Verifikasi Aplikasi

Cek HTTP lokal dari VPS:

```bash
curl -i http://127.0.0.1:3003/
```

Cek website publik:

- `https://letters.taxcenterug.com`

Kalau halaman utama muncul, deploy manual sudah berhasil.

## Tahap 14: Workflow CI/CD GitHub Actions

Workflow final yang dipakai:

- [.github/workflows/deploy-vps.yml](.github/workflows/deploy-vps.yml)

Workflow ini melakukan:

1. checkout code
2. setup SSH
3. SSH ke VPS
4. `git pull origin main`
5. `docker compose --env-file .env.production -f docker-compose.prod.yml up -d --build`
6. membersihkan image lama dengan `docker image prune -f`

## Tahap 15: GitHub Secrets yang Harus Dibuat

Masuk ke:

- `GitHub Repository -> Settings -> Secrets and variables -> Actions`

Buat secrets berikut:

### `VPS_HOST`

```text
148.230.102.236
```

### `VPS_PORT`

```text
3190
```

### `VPS_USER`

```text
zidan
```

### `VPS_APP_PATH`

```text
/opt/praktikum-sm-letters-anonymous
```

### `VPS_SSH_KEY`

Isi private key dari:

```bash
cat ~/.ssh/id_ed25519_github_actions
```

Copy seluruh isi private key, termasuk:

```text
-----BEGIN OPENSSH PRIVATE KEY-----
...
-----END OPENSSH PRIVATE KEY-----
```

### `VPS_KNOWN_HOSTS`

Generate dari lokal:

```bash
ssh-keyscan -p 3190 -t rsa,ecdsa,ed25519 148.230.102.236 2>/dev/null
```

Simpan seluruh output command itu sebagai isi secret `VPS_KNOWN_HOSTS`.

## Tahap 16: Menjalankan CI/CD

Setelah semua secrets dibuat, workflow akan jalan otomatis saat ada push ke `main`.

Kalau ingin menjalankan manual:

1. buka tab `Actions`
2. pilih workflow `Deploy to VPS`
3. klik `Run workflow`

Atau cukup push perubahan:

```bash
git add .
git commit -m "Update production"
git push origin main
```

## Command Ringkas yang Sering Dipakai

### Build ulang app

```bash
cd /opt/praktikum-sm-letters-anonymous
docker compose --env-file .env.production -f docker-compose.prod.yml up -d --build
```

### Cek container

```bash
docker ps
```

### Lihat log app

```bash
docker logs letters-anonymous-web --tail 200
```

### Jalankan migration

```bash
docker exec -it letters-anonymous-web php spark migrate
```

### Pull source terbaru dari GitHub

```bash
cd /opt/praktikum-sm-letters-anonymous
git pull origin main
```

### Reload nginx

```bash
sudo systemctl reload nginx
```

### Test konfigurasi nginx

```bash
sudo nginx -t
```

## Troubleshooting

### 1. `500 Internal Server Error`

Kemungkinan penyebab:

- `.env.production` belum dibaca dengan benar
- koneksi database gagal
- migration belum dijalankan
- permission `writable` / `public/uploads` salah

Langkah cek:

```bash
docker logs letters-anonymous-web --tail 200
docker exec -it letters-anonymous-web php spark migrate
```

### 2. Domain tidak bisa dibuka

Cek:

```bash
nslookup letters.taxcenterug.com
sudo nginx -t
sudo systemctl status nginx
```

### 3. CI/CD gagal SSH

Pastikan:

- `VPS_SSH_KEY` benar
- `VPS_KNOWN_HOSTS` benar
- port SSH benar
- user SSH benar

### 4. Database tidak terhubung

Pastikan:

- Remote MySQL sudah menambahkan IP VPS `148.230.102.236`
- hostname MySQL adalah `srv1151.hstgr.io`
- username dan password database benar

## Catatan Keamanan

- Jangan commit `.env.production` ke GitHub.
- Jangan kirim password database atau private key ke chat publik.
- Kalau password atau token pernah terekspos, segera rotate atau ganti.
- Simpan credential di password manager atau secret manager.

## File yang Terlibat

- [Dockerfile](Dockerfile)
- [docker-compose.prod.yml](docker-compose.prod.yml)
- [.dockerignore](.dockerignore)
- [.github/workflows/deploy-vps.yml](.github/workflows/deploy-vps.yml)
- [app/Config/Routes.php](app/Config/Routes.php)
- [app/Controllers/Letters.php](app/Controllers/Letters.php)
- [app/Controllers/AdminLetters.php](app/Controllers/AdminLetters.php)

## Hasil Akhir

Jika semua langkah selesai, hasil yang diharapkan:

- aplikasi aktif di `https://letters.taxcenterug.com`
- database remote tersambung
- nginx reverse proxy aktif
- Docker container berjalan di `127.0.0.1:3003`
- deploy otomatis berjalan setiap push ke branch `main`
