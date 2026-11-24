# SponsorHub — Pembaruan Landing Page & Registrasi Perusahaan

Pembaruan ini melanjutkan project Laravel yang sudah ada. Fitur UC-01 sampai UC-09 dipertahankan; perubahan utama adalah landing page publik, registrasi perusahaan, relasi akun perusahaan dengan data sponsor, dan pembatasan data UC-04/UC-05 berdasarkan akun perusahaan.

## Sebelum menjalankan

1. Buat salinan/backup folder project dan database yang sedang digunakan.
2. Jangan menghapus atau mengganti file `.env` milik project aktif. Arsip pembaruan sengaja tidak menyertakan `.env` karena file tersebut berisi konfigurasi rahasia.
3. Salin file pembaruan ke folder project lama dengan mempertahankan `.env` dan database yang sudah digunakan.

## Menjalankan pembaruan database

Buka terminal pada folder project, lalu jalankan:

```bash
php artisan optimize:clear
php artisan migrate --force
```

Migration baru hanya menambahkan kolom `user_id` pada tabel `sponsors` dan menghubungkan akun demo Perusahaan yang sudah ada. Migration tidak menghapus tabel atau data lama.

**Jangan jalankan `php artisan migrate:fresh`, `db:wipe`, atau menghapus database.** Perintah tersebut dapat menghilangkan data project.

## Menjalankan aplikasi

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Fitur yang ditambahkan

- `/` — landing page publik SponsorHub.
- `/register` — registrasi akun perusahaan.
- Akun hasil registrasi otomatis mendapat role `Perusahaan`; role internal tidak tersedia pada form publik.
- Profil sponsor dibuat otomatis dan ditautkan ke akun perusahaan.
- UC-04 hanya membaca dan menyimpan kustomisasi milik perusahaan yang sedang login.
- UC-05 hanya menampilkan dan memproses bukti benefit milik perusahaan yang sedang login.
- Dashboard perusahaan hanya menampilkan ringkasan data sponsornya sendiri.

## Akun demo

- `panitia@sponsorhub.test`
- `perusahaan@sponsorhub.test`
- `koordinator@sponsorhub.test`
- `bendahara@sponsorhub.test`

Password demo: `password`.

## Catatan

Akun demo Perusahaan lama dihubungkan dengan profil sponsor melalui migration. Registrasi perusahaan baru tidak mengubah akun internal yang sudah ada. Untuk deployment publik, ganti password demo, gunakan HTTPS, dan konfigurasi email serta kebijakan privasi sebelum menerima data perusahaan sungguhan.
