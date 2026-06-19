# Sekolah Hub V1 — Frontend Architecture & Documentation

Dokumen ini menjelaskan secara mendalam arsitektur, teknologi, konvensi, dan integrasi frontend yang diimplementasikan pada proyek **Sekolah Hub V1**. Platform website sekolah single-tenant berbasis Laravel ini mengutamakan kompatibilitas shared hosting (cPanel), performa optimal, dan antarmuka premium bagi pengguna non-teknis.

---

## 1. Tech Stack & Library Inti

Sistem frontend Sekolah Hub V1 dibangun menggunakan empat pilar utama:
1. **Laravel Blade:** Templating engine bawaan Laravel untuk penyusunan layout terstruktur.
2. **Tailwind CSS (v3/v4):** Framework utility-first untuk styling tata letak dan custom utility adjustment.
3. **DaisyUI (v5):** Library komponen Tailwind untuk element antarmuka (button, card, alert, modal, tabs, dll) guna meminimalkan custom CSS.
4. **Alpine.js (v3):** Library JavaScript minimalis untuk mengelola interaksi reaktif (state management) di sisi client.

---

## 2. Struktur View & Konvensi Blade

View dikelompokkan secara modular di dalam modul monolit dan direktori utama:

```text
resources/views/
├── auth/                       # Halaman login, register, reset password (Laravel Breeze)
├── components/                 # Komponen Blade global (input, button, modal)
│   ├── admin/                  # Komponen khusus panel admin
│   └── public/                 # Komponen khusus halaman publik
├── layouts/                    # Layout dasar sistem
│   ├── app.blade.php           # Layout utama panel admin
│   ├── navigation.blade.php    # Topbar/Sidebar navigasi admin dengan izin @can
│   ├── public.blade.php        # Layout dasar untuk halaman publik
│   └── guest.blade.php         # Layout login / autentikasi
└── profile/                    # Halaman profil pengguna admin
```

### Konvensi Pembuatan Komponen
- **Anonymous Components:** Digunakan untuk elemen UI murni tanpa logika PHP (contoh: `<x-text-input>`, `<x-primary-button>`). Disimpan di `resources/views/components/`.
- **Class-Based Components:** Digunakan hanya jika komponen membutuhkan query database langsung atau logika PHP yang kompleks (contoh: Media Picker yang perlu memanggil database berkas media).
- **Penamaan File:** Menggunakan `kebab-case.blade.php` (contoh: `form-input.blade.php`).

---

## 3. Sistem Styling & Integrasi CSS

### Tailwind CSS & DaisyUI
Konfigurasi file [tailwind.config.js](file:///c:/Users/kuromu/Desktop/sekolah-hub/tailwind.config.js) dirancang agar Vite memindai seluruh file HTML/Blade di proyek:

```javascript
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Modules/**/*.blade.php',
        './themes/**/*.blade.php', // PENTING: Memindai template tema eksternal
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms, daisyui],
};
```

### Aturan Styling UI
- **Tanpa Custom CSS Berlebih:** Developer dilarang menulis CSS manual (`style.css` kustom) apabila DaisyUI telah menyediakan token kelasnya (contoh: kelas `btn`, `alert`, `modal`, `card`, `table`).
- **Aset Terkompilasi:** Aset diproses melalui Vite. Pada development, Vite memantau perubahan secara langsung (`npm run dev`). Pada production, output kompilasi diletakkan di `public/build/` dan file manifest di-commit ke repositori agar siap digunakan di shared hosting tanpa perlu proses build ulang di server produksi.

---

## 4. Strategi JavaScript & Alpine.js

Interaktivitas antarmuka diatur secara eksklusif menggunakan Alpine.js dengan panduan sebagai berikut:

### A. Urutan Logika Pendek (Inline `x-data`)
Gunakan deklarasi inline di dalam tag HTML apabila logika interaksi kurang dari 5 baris dan bersifat terisolasi.
```html
<div x-data="{ open: false }">
    <button @click="open = !open" class="btn btn-sm">Toggle Panel</button>
    <div x-show="open" x-transition class="p-4 bg-gray-50">Konten</div>
</div>
```

### B. Urutan Logika Panjang (Ekstraksi ke File JS)
Apabila interaksi melibatkan request HTTP (axios/fetch), pemfilteran rumit, atau lebih dari 5 baris, logika wajib diekstrak ke dalam file JavaScript terpisah di `resources/js/` (misalnya di-bundle via `app.js` menggunakan `Alpine.data()`).

---

## 5. Filesystem-Based Theme Engine (Level 1–2)

Modul Tema menggunakan folder berstruktur tetap di dalam root direktori `themes/` untuk melokalisasi seluruh visual halaman depan (public frontend).

### Struktur Folder Tema
```text
themes/
└── school-classic/             # Direktori nama tema (kebab-case)
    ├── theme.json              # Metadata konfigurasi & daftar default section tema
    ├── screenshots/            # Screenshot visual tema untuk admin panel
    │   └── screenshot.png
    └── views/                  # Kumpulan template visual Blade tema
        ├── homepage.blade.php  # Template utama beranda
        └── sections/           # Modul-modul layout section dinamis
            ├── hero.blade.php
            ├── announcement.blade.php
            ├── news.blade.php
            ├── gallery.blade.php
            ├── ppdb.blade.php
            ├── contact.blade.php
            └── cta.blade.php
```

### Resolusi View Dinamis
Di runtime, `ThemeViewsMiddleware` mem-prepend lokasi direktori view milik tema aktif ke dalam Laravel View Finder secara aman:
```php
$themeViewsPath = base_path('themes/' . $activeTheme . '/views');
if (is_dir($themeViewsPath)) {
    app('view')->getFinder()->prependLocation($themeViewsPath);
}
```
Metode ini memastikan saat controller mereturn `view('homepage')`, Laravel secara otomatis memprioritaskan view di `themes/{theme-active}/views/homepage.blade.php` di atas view global.

### Dynamic Homepage Assembly
Susunan dan urutan section pada homepage ditentukan melalui pengaturan JSON array `theme.homepage_sections` di tabel `settings`. Halaman beranda memproses include section secara dinamis:
```html
@foreach($activeSections as $section)
    @include('sections.' . $section)
@endforeach
```

---

## 6. PPDB Hybrid Dynamic Form (EAV)

Modul PPDB menggunakan sistem formulir dinamis berbasis EAV (Entity-Attribute-Value) yang memetakan field dinamis database langsung ke komponen Blade input.

### Alur Form Dinamis
1. Kolom formulir didefinisikan oleh admin di database (`admission_form_fields`).
2. Kolom tersebut memuat konfigurasi tipe data, nama field, validasi, dan opsi pilihan.
3. Di frontend, sistem melakukan looping dan memetakan masing-masing field ke layout element yang cocok:
   - **`text` / `email` / `phone` / `number` / `date`:** Renders as `<input type="..." class="input">`
   - **`textarea`:** Renders as `<textarea class="textarea">`
   - **`select`:** Renders as `<select class="select">`
   - **`radio` / `checkbox`:** Renders as looping `<input type="radio|checkbox" class="radio|checkbox">`
   - **`file`:** Renders as `<input type="file" class="file-input">` (di-upload privat ke `storage/app/private/ppdb/`)

---

## 7. Integrasi WYSIWYG & Custom Media

### TinyMCE Self-Hosted
Untuk menghindari ketergantungan pada koneksi internet di shared hosting, TinyMCE di-host secara lokal:
- File library disimpan di: `public/vendor/tinymce/`
- Dipanggil di Blade via script tag lokal:
  ```html
  <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
  ```
- Inisialisasi TinyMCE dilakukan menggunakan script inline pada halaman admin editor berita/halaman yang membutuhkan teks kaya (WYSIWYG).

### Konfigurasi Kustom & Dropdown Media
Guna mempermudah admin non-teknis memperbarui aset visual tema (seperti Foto Kepala Sekolah atau Gambar Latar Hero) tanpa pustaka media picker JavaScript yang berat, frontend memuat dropdown selektor gambar. Dropdown ini diisi langsung dari database berkas media dengan kriteria MIME tipe `image/*`.

---

## 8. Penanganan Keamanan & Form Proteksi

### Cloudflare Turnstile
Untuk menghindari spam pada form kontak beranda dan formulir PPDB publik:
- **Widget Turnstile** ditambahkan ke form menggunakan Javascript resmi Cloudflare:
  ```html
  @if(!empty($turnstileSiteKey))
      <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
      <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"></div>
  @endif
  ```
- Backend (Form Request) memverifikasi input token `cf-turnstile-response`. Jika keys tidak di-set di panel pengaturan admin, validasi dilewati agar tidak menghambat siklus pengujian sistem lokal.

### CSRF & Validasi Session State
- Seluruh form wajib menyertakan direktif `@csrf`.
- Tanggapan error validasi ditangkap di frontend menggunakan DaisyUI Alert component:
  ```html
  @if($errors->any())
      <div class="alert alert-error bg-rose-50 text-rose-800 rounded-xl mb-6 text-sm">
          <ul class="list-disc list-inside">
              @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
  ```

---

## 9. Alur Kerja Build & Uji Coba Frontend

### Perintah Pembangunan Aset (Vite)
- **Mode Development (Hot Reloading):**
  ```bash
  npm run dev
  ```
- **Kompilasi Production (Wajib sebelum push ke remote):**
  ```bash
  npm run build
  ```
  *Output build di `public/build/` akan diperbarui secara otomatis dan harus ikut di-commit ke repositori Git.*
