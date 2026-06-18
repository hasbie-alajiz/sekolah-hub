<?php

declare(strict_types=1);

namespace App\Modules\CMS\database\seeders;

use App\Models\User;
use App\Modules\CMS\Models\Category;
use App\Modules\CMS\Models\Post;
use App\Modules\CMS\Models\Page;
use App\Modules\CMS\Models\Menu;
use App\Modules\CMS\Models\MenuItem;
use Illuminate\Database\Seeder;

class CMSSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::first() ?? User::create([
            'name' => 'Super Admin',
            'email' => 'hasbialaziz67@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // 1. Seed Categories
        $catPengumuman = Category::firstOrCreate([
            'slug' => 'pengumuman'
        ], [
            'name' => 'Pengumuman',
            'description' => 'Kategori untuk pengumuman penting sekolah.'
        ]);

        $catKegiatan = Category::firstOrCreate([
            'slug' => 'kegiatan'
        ], [
            'name' => 'Kegiatan',
            'description' => 'Kegiatan kesiswaan, ekstrakurikuler, dan akademik.'
        ]);

        $catPrestasi = Category::firstOrCreate([
            'slug' => 'prestasi'
        ], [
            'name' => 'Prestasi',
            'description' => 'Prestasi yang diraih oleh siswa dan guru.'
        ]);

        // 2. Seed Posts
        $post1 = Post::firstOrCreate([
            'slug' => 'penerimaan-siswa-baru-tahun-ajaran-2026-2027'
        ], [
            'title' => 'Penerimaan Siswa Baru Tahun Ajaran 2026/2027 Telah Dibuka',
            'excerpt' => 'Pendaftaran Peserta Didik Baru (PPDB) Sekolah Hub tahun ajaran 2026/2027 resmi dibuka mulai hari ini.',
            'content' => '<p>Sekolah Hub resmi membuka pendaftaran peserta didik baru (PPDB) untuk Tahun Ajaran 2026/2027. Tersedia berbagai jalur pendaftaran seperti Zonasi, Prestasi, dan Afirmasi.</p><p>Untuk melakukan pendaftaran silakan mengunjungi menu PPDB pada halaman utama website sekolah dan mengisi formulir pendaftaran secara online. Pastikan dokumen persyaratan sudah lengkap sebelum diunggah.</p>',
            'status' => 'published',
            'published_at' => now(),
            'author_id' => $author->id,
            'seo_title' => 'PPDB Sekolah Hub TA 2026/2027 Dibuka',
            'seo_description' => 'Informasi lengkap pendaftaran siswa baru Sekolah Hub tahun ajaran 2026/2027 secara online.'
        ]);
        $post1->categories()->sync([$catPengumuman->id]);

        $post2 = Post::firstOrCreate([
            'slug' => 'tim-basket-sekolah-juara-1-tingkat-provinsi'
        ], [
            'title' => 'Tim Basket Sekolah Hub Raih Juara 1 Turnamen Provinsi',
            'excerpt' => 'Tim basket putra Sekolah Hub berhasil membawa pulang piala juara pertama setelah mengalahkan SMA Harapan di babak final.',
            'content' => '<p>Prestasi gemilang kembali diraih oleh siswa-siswi Sekolah Hub. Kali ini tim basket putra berhasil memenangkan turnamen bola basket tingkat provinsi yang diselenggarakan minggu lalu.</p><p>Dengan perjuangan keras, tim berhasil mengalahkan lawan-lawannya hingga babak final. Kepala Sekolah menyampaikan apresiasi sebesar-besarnya kepada tim dan pelatih atas pencapaian luar biasa ini.</p>',
            'status' => 'published',
            'published_at' => now(),
            'author_id' => $author->id,
            'seo_title' => 'Juara 1 Basket Provinsi - Sekolah Hub',
            'seo_description' => 'Tim basket putra Sekolah Hub meraih juara pertama turnamen provinsi 2026.'
        ]);
        $post2->categories()->sync([$catKegiatan->id, $catPrestasi->id]);

        // 3. Seed Pages
        $pageProfil = Page::firstOrCreate([
            'slug' => 'profil-sekolah'
        ], [
            'parent_id' => null,
            'title' => 'Profil Sekolah',
            'content' => '<h2>Tentang Sekolah Hub</h2><p>Sekolah Hub adalah salah satu sekolah unggulan yang berkomitmen mencetak generasi unggul yang berkarakter, inovatif, dan berakhlak mulia. Didirikan dengan visi menjadi pusat pendidikan berkualitas tinggi yang ramah anak.</p>',
            'status' => 'published',
            'seo_title' => 'Profil Lengkap Sekolah Hub',
            'seo_description' => 'Visi, misi, sejarah, dan profil umum Sekolah Hub.'
        ]);

        $pageSejarah = Page::firstOrCreate([
            'slug' => 'sejarah-sekolah'
        ], [
            'parent_id' => $pageProfil->id,
            'title' => 'Sejarah Sekolah',
            'content' => '<h2>Sejarah Berdirinya Sekolah</h2><p>Sekolah Hub didirikan pada tahun 2015 dengan tujuan untuk memberikan pendidikan berkualitas global yang dapat diakses oleh seluruh lapisan masyarakat. Dimulai dengan fasilitas yang sederhana, kini sekolah berkembang pesat memiliki laboratorium lengkap, perpustakaan digital, dan ruang kelas interaktif.</p>',
            'status' => 'published'
        ]);

        $pageVisiMisi = Page::firstOrCreate([
            'slug' => 'visi-dan-misi'
        ], [
            'parent_id' => $pageProfil->id,
            'title' => 'Visi dan Misi',
            'content' => '<h2>Visi</h2><p>Mewujudkan generasi cerdas, berintegritas, dan peduli lingkungan yang siap bersaing global.</p><h2>Misi</h2><ol><li>Menyelenggarakan pembelajaran berbasis teknologi inovatif.</li><li>Menanamkan nilai-nilai karakter budi pekerti luhur.</li><li>Mengembangkan bakat siswa melalui ekstrakurikuler yang beragam.</li></ol>',
            'status' => 'published'
        ]);

        $pageKontak = Page::firstOrCreate([
            'slug' => 'hubungi-kami'
        ], [
            'parent_id' => null,
            'title' => 'Hubungi Kami',
            'content' => '<h2>Alamat Sekolah</h2><p>Jl. Pendidikan No. 45, Jakarta Selatan</p><p>Telepon: (021) 555-1234<br>Email: info@sekolah.sch.id</p>',
            'status' => 'published',
            'seo_title' => 'Hubungi Kami - Sekolah Hub',
            'seo_description' => 'Hubungi administrasi sekolah atau kirimkan kritik & saran melalui form kontak.'
        ]);

        // 4. Seed Menus
        $menuHeader = Menu::firstOrCreate([
            'slug' => 'header-menu'
        ], [
            'name' => 'Header Menu',
            'location' => 'header-menu'
        ]);

        $menuFooter = Menu::firstOrCreate([
            'slug' => 'footer-menu'
        ], [
            'name' => 'Footer Menu',
            'location' => 'footer-menu'
        ]);

        // Clear existing items to prevent duplicates when running seeder multiple times
        MenuItem::whereIn('menu_id', [$menuHeader->id, $menuFooter->id])->delete();

        // 5. Seed Header Menu Items
        $mBeranda = MenuItem::create([
            'menu_id' => $menuHeader->id,
            'parent_id' => null,
            'title' => 'Beranda',
            'type' => 'custom',
            'url' => '/',
            'sort_order' => 1
        ]);

        $mProfil = MenuItem::create([
            'menu_id' => $menuHeader->id,
            'parent_id' => null,
            'title' => 'Profil',
            'type' => 'page',
            'reference_type' => Page::class,
            'reference_id' => $pageProfil->id,
            'sort_order' => 2
        ]);

        MenuItem::create([
            'menu_id' => $menuHeader->id,
            'parent_id' => $mProfil->id,
            'title' => 'Sejarah',
            'type' => 'page',
            'reference_type' => Page::class,
            'reference_id' => $pageSejarah->id,
            'sort_order' => 1
        ]);

        MenuItem::create([
            'menu_id' => $menuHeader->id,
            'parent_id' => $mProfil->id,
            'title' => 'Visi & Misi',
            'type' => 'page',
            'reference_type' => Page::class,
            'reference_id' => $pageVisiMisi->id,
            'sort_order' => 2
        ]);

        $mBerita = MenuItem::create([
            'menu_id' => $menuHeader->id,
            'parent_id' => null,
            'title' => 'Informasi',
            'type' => 'custom',
            'url' => '#',
            'sort_order' => 3
        ]);

        MenuItem::create([
            'menu_id' => $menuHeader->id,
            'parent_id' => $mBerita->id,
            'title' => 'Pengumuman',
            'type' => 'category',
            'reference_type' => Category::class,
            'reference_id' => $catPengumuman->id,
            'sort_order' => 1
        ]);

        MenuItem::create([
            'menu_id' => $menuHeader->id,
            'parent_id' => $mBerita->id,
            'title' => 'Kegiatan',
            'type' => 'category',
            'reference_type' => Category::class,
            'reference_id' => $catKegiatan->id,
            'sort_order' => 2
        ]);

        MenuItem::create([
            'menu_id' => $menuHeader->id,
            'parent_id' => null,
            'title' => 'Hubungi Kami',
            'type' => 'page',
            'reference_type' => Page::class,
            'reference_id' => $pageKontak->id,
            'sort_order' => 4
        ]);

        // 6. Seed Footer Menu Items
        MenuItem::create([
            'menu_id' => $menuFooter->id,
            'parent_id' => null,
            'title' => 'Profil Sekolah',
            'type' => 'page',
            'reference_type' => Page::class,
            'reference_id' => $pageProfil->id,
            'sort_order' => 1
        ]);

        MenuItem::create([
            'menu_id' => $menuFooter->id,
            'parent_id' => null,
            'title' => 'Hubungi Kami',
            'type' => 'page',
            'reference_type' => Page::class,
            'reference_id' => $pageKontak->id,
            'sort_order' => 2
        ]);

        MenuItem::create([
            'menu_id' => $menuFooter->id,
            'parent_id' => null,
            'title' => 'Peta Situs',
            'type' => 'custom',
            'url' => '#',
            'sort_order' => 3
        ]);
    }
}
