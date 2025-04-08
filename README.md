# Laravel Sanctum Multi-Level Authentication API

API Laravel 12 dengan autentikasi multi-level menggunakan Sanctum, dilengkapi dengan kontrol akses berbasis peran untuk pengguna, developer, dan administrator.

## Fitur

1. Auth API multi level dan middleware (email dan password) -> role (user, dev, admin)
     - Login
     - Register
     - Reset Password
2. Dashboard Dev
     - Melihat semua data postingan dari pengguna dengan role 'user'
     - Menghapus postingan dari pengguna dengan role 'user'
3. Dashboard Admin
     - Melihat semua data postingan dari pengguna dengan role 'user'
     - Menghapus postingan dari pengguna dengan role 'user'
4. Dashboard User
     - Melihat, membuat, mengubah, dan menghapus postingan milik sendiri

## Instalasi

1. Clone repository
```bash
git clone https://github.com/wahyudedik/laravel-sanctum-multiple-auth.git
```

2. Masuk ke direktori proyek
```bash
cd laravel-sanctum-multiple-auth
```

3. Install dependensi
```bash
composer install
```

4. Salin file environment
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate --ansi
```

6. Konfigurasi database di file .env

7. Jalankan migrasi
```bash
php artisan migrate
```

8. Jalankan server
```bash
php artisan serve
```

## Dokumentasi API

### Endpoint Autentikasi

#### Registrasi Pengguna Baru

- **URL**: `/api/register`
- **Metode**: `POST`
- **Autentikasi Diperlukan**: Tidak
- **Body Request**:
    ```json
    {
      "name": "John Doe",
      "email": "john@example.com",
      "password": "password123",
      "password_confirmation": "password123",
      "role": "user" // Opsional, default "user"
    }
    ```
- **Response Sukses**: `201 Created`
    ```json
    {
      "message": "User registered successfully",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      },
      "token": "1|abcdefghijklmnopqrstuvwxyz"
    }
    ```

#### Login

- **URL**: `/api/login`
- **Metode**: `POST`
- **Autentikasi Diperlukan**: Tidak
- **Body Request**:
    ```json
    {
      "email": "john@example.com",
      "password": "password123"
    }
    ```
- **Response Sukses**: `200 OK`
    ```json
    {
      "message": "Login successful",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      },
      "token": "1|abcdefghijklmnopqrstuvwxyz"
    }
    ```

#### Logout

- **URL**: `/api/logout`
- **Metode**: `POST`
- **Autentikasi Diperlukan**: Ya (Bearer Token)
- **Response Sukses**: `200 OK`
    ```json
    {
      "message": "Logged out successfully"
    }
    ```

#### Lupa Password

- **URL**: `/api/forgot-password`
- **Metode**: `POST`
- **Autentikasi Diperlukan**: Tidak
- **Body Request**:
    ```json
    {
      "email": "john@example.com"
    }
    ```
- **Response Sukses**: `200 OK`
    ```json
    {
      "message": "We have emailed your password reset link!"
    }
    ```

#### Reset Password

- **URL**: `/api/reset-password`
- **Metode**: `POST`
- **Autentikasi Diperlukan**: Tidak
- **Body Request**:
    ```json
    {
      "token": "token-reset-dari-email",
      "email": "john@example.com",
      "password": "newpassword123",
      "password_confirmation": "newpassword123"
    }
    ```
- **Response Sukses**: `200 OK`
    ```json
    {
      "message": "Your password has been reset!"
    }
    ```

### Endpoint User

#### Mendapatkan Postingan User

- **URL**: `/api/user/posts`
- **Metode**: `GET`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role User)
- **Response Sukses**: `200 OK`
    ```json
    {
      "posts": {
        "current_page": 1,
        "data": [
          {
            "id": 1,
            "title": "Postingan Pertama Saya",
            "content": "Ini adalah konten dari postingan pertama saya",
            "user_id": 1,
            "created_at": "2023-01-01T00:00:00.000000Z",
            "updated_at": "2023-01-01T00:00:00.000000Z",
            "user": {
              "id": 1,
              "name": "John Doe",
              "email": "john@example.com",
              "role": "user"
            }
          }
        ],
        "first_page_url": "http://localhost:8000/api/user/posts?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost:8000/api/user/posts?page=1",
        "links": [
          {
            "url": null,
            "label": "« Previous",
            "active": false
          },
          {
            "url": "http://localhost:8000/api/user/posts?page=1",
            "label": "1",
            "active": true
          },
          {
            "url": null,
            "label": "Next »",
            "active": false
          }
        ],
        "next_page_url": null,
        "path": "http://localhost:8000/api/user/posts",
        "per_page": 10,
        "prev_page_url": null,
        "to": 1,
        "total": 1
      }
    }
    ```

#### Membuat Postingan

- **URL**: `/api/user/posts`
- **Metode**: `POST`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role User)
- **Body Request**:
    ```json
    {
      "title": "Postingan Baru Saya",
      "content": "Ini adalah konten dari postingan baru saya"
    }
    ```
- **Response Sukses**: `201 Created`
    ```json
    {
      "message": "Post created successfully",
      "post": {
        "title": "Postingan Baru Saya",
        "content": "Ini adalah konten dari postingan baru saya",
        "user_id": 1,
        "updated_at": "2023-01-01T00:00:00.000000Z",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "id": 2
      }
    }
    ```

#### Mendapatkan Detail Postingan

- **URL**: `/api/user/posts/{post_id}`
- **Metode**: `GET`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role User)
- **Response Sukses**: `200 OK`
    ```json
    {
      "post": {
        "id": 1,
        "title": "Postingan Pertama Saya",
        "content": "Ini adalah konten dari postingan pertama saya",
        "user_id": 1,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z",
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "role": "user"
        }
      }
    }
    ```

#### Mengupdate Postingan

- **URL**: `/api/user/posts/{post_id}`
- **Metode**: `PUT`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role User)
- **Body Request**:
    ```json
    {
      "title": "Judul Postingan Diperbarui",
      "content": "Ini adalah konten yang diperbarui dari postingan saya"
    }
    ```
- **Response Sukses**: `200 OK`
    ```json
    {
      "message": "Post updated successfully",
      "post": {
        "id": 1,
        "title": "Judul Postingan Diperbarui",
        "content": "Ini adalah konten yang diperbarui dari postingan saya",
        "user_id": 1,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    }
    ```

#### Menghapus Postingan

- **URL**: `/api/user/posts/{post_id}`
- **Metode**: `DELETE`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role User)
- **Response Sukses**: `200 OK`
    ```json
    {
      "message": "Post deleted successfully"
    }
    ```

### Endpoint Developer

#### Mendapatkan Semua Postingan User

- **URL**: `/api/dev/posts`
- **Metode**: `GET`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role Dev)
- **Response Sukses**: `200 OK`
    ```json
    {
      "posts": {
        "current_page": 1,
        "data": [
          {
            "id": 1,
            "title": "Postingan User",
            "content": "Ini adalah postingan dari seorang user",
            "user_id": 2,
            "created_at": "2023-01-01T00:00:00.000000Z",
            "updated_at": "2023-01-01T00:00:00.000000Z",
            "user": {
              "id": 2,
              "name": "Regular User",
              "email": "user@example.com",
              "role": "user"
            }
          }
        ],
        "first_page_url": "http://localhost:8000/api/dev/posts?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost:8000/api/dev/posts?page=1",
        "links": [
          {
            "url": null,
            "label": "« Previous",
            "active": false
          },
          {
            "url": "http://localhost:8000/api/dev/posts?page=1",
            "label": "1",
            "active": true
          },
          {
            "url": null,
            "label": "Next »",
            "active": false
          }
        ],
        "next_page_url": null,
        "path": "http://localhost:8000/api/dev/posts",
        "per_page": 10,
        "prev_page_url": null,
        "to": 1,
        "total": 1
      }
    }
    ```

#### Mendapatkan Detail Postingan User

- **URL**: `/api/dev/posts/{post_id}`
- **Metode**: `GET`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role Dev)
- **Response Sukses**: `200 OK`
    ```json
    {
      "post": {
        "id": 1,
        "title": "Postingan User",
        "content": "Ini adalah postingan dari seorang user",
        "user_id": 2,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z",
        "user": {
          "id": 2,
          "name": "Regular User",
          "email": "user@example.com",
          "role": "user"
        }
      }
    }
    ```

#### Menghapus Postingan User

- **URL**: `/api/dev/posts/{post_id}`
- **Metode**: `DELETE`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role Dev)
- **Response Sukses**: `200 OK`
    ```json
    {
      "message": "Post deleted successfully"
    }
    ```

### Endpoint Admin

#### Mendapatkan Semua Postingan User

- **URL**: `/api/admin/posts`
- **Metode**: `GET`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role Admin)
- **Response Sukses**: Format sama dengan endpoint Dev

#### Mendapatkan Detail Postingan User

- **URL**: `/api/admin/posts/{post_id}`
- **Metode**: `GET`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role Admin)
- **Response Sukses**: Format sama dengan endpoint Dev

#### Menghapus Postingan User

- **URL**: `/api/admin/posts/{post_id}`
- **Metode**: `DELETE`
- **Autentikasi Diperlukan**: Ya (Bearer Token + Role Admin)
- **Response Sukses**: Format sama dengan endpoint Dev

## Response Error

Semua endpoint dapat mengembalikan response error berikut:

- **401 Unauthorized**
    ```json
    {
      "message": "Unauthenticated."
    }
    ```

- **403 Forbidden**
    ```json
    {
      "message": "Unauthorized. You do not have the required role."
    }
    ```

- **404 Not Found**
    ```json
    {
      "message": "Resource not found."
    }
    ```

- **422 Unprocessable Entity**
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "field": [
          "Pesan error untuk field tersebut."
        ]
      }
    }
    ```

## Pengujian API

Anda dapat menguji API menggunakan tools seperti Postman atau curl. Berikut contoh cara menguji endpoint login menggunakan curl:

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'
```

Setelah login, Anda dapat menggunakan token yang diterima untuk request yang memerlukan autentikasi:

```bash
curl -X GET http://localhost:8000/api/user/posts \
  -H "Authorization: Bearer TOKEN_ANDA_DISINI" \
  -H "Content-Type: application/json"
```

## Struktur Database

### Tabel Users
- `id` - ID pengguna (primary key)
- `name` - Nama pengguna
- `email` - Email pengguna (unique)
- `email_verified_at` - Waktu verifikasi email
- `password` - Password terenkripsi
- `role` - Peran pengguna (user, dev, admin)
- `remember_token` - Token untuk fitur "remember me"
- `created_at` - Waktu pembuatan
- `updated_at` - Waktu pembaruan

### Tabel Posts
- `id` - ID postingan (primary key)
- `title` - Judul postingan
- `content` - Konten postingan
- `user_id` - ID pengguna yang membuat postingan (foreign key)
- `created_at` - Waktu pembuatan
- `updated_at` - Waktu pembaruan

### Tabel Password Reset Tokens
- `email` - Email pengguna (primary key)
- `token` - Token reset password
- `created_at` - Waktu pembuatan token

### Tabel Sessions
- `id` - ID sesi (primary key)
- `user_id` - ID pengguna (foreign key)
- `ip_address` - Alamat IP
- `user_agent` - User agent browser
- `payload` - Data sesi
- `last_activity` - Waktu aktivitas terakhir

## Keamanan

API ini menggunakan Laravel Sanctum untuk autentikasi token. Beberapa fitur keamanan yang diimplementasikan:

1. Token berbasis Sanctum untuk autentikasi API
2. Middleware role-based untuk kontrol akses
3. Validasi input untuk semua request
4. Enkripsi password menggunakan bcrypt
5. Proteksi terhadap CSRF untuk endpoint web

## Kontribusi

Kontribusi selalu diterima. Untuk berkontribusi:

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/amazing-feature`)
3. Commit perubahan Anda (`git commit -m 'Menambahkan fitur amazing'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buka Pull Request

## Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.

## Kontak

Wahyu Dedik - wdedyk@gmail.com
Link Proyek: [https://github.com/wahyudedik/laravel-sanctum-multiple-auth.git](https://github.com/wahyudedik/laravel-sanctum-multiple-auth.git)
