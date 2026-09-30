<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;

class InsertHistoryArticle extends Seeder
{
    public function run(): void
    {
        $content = '
<h2>Era Awal: Relay, Vacuum Tube, dan Mesin Hitung (1940-an)</h2>
<p>Sejarah komputasi dimulai jauh sebelum <strong>silicon chip</strong>. Cikal bakal ada pada <strong>tabung vakum (vacuum tube)</strong> dan <em>relay elektromekanik</em>. Komputer pertama, <strong>ENIAC</strong> (1946), memakai 17.468 tabung vakum dan memakan daya 150 kW — nyaris sebesar pabrik kecil. Ia berat sekitar 27 ton dan hanya bisa menampung 20 angka pada satu waktu.</p>
<img src="/storage/blog/eniac.jpg" alt="ENIAC computer tahun 1940-an" class="w-full h-auto my-4">
<p>Pada masa itu pemrograman dilakukan secara fisik — menyusun ulang kabel dan saklar. Belum ada konsep <em>software</em> seperti sekarang.</p>

<h2>Revolusi Transistor (1947)</h2>
<p>Tanggal <strong>23 Desember 1947</strong> adalah momen besar: para peneliti Bell Labs — John Bardeen, Walter Brattain, dan William Shockley — menciptakan <strong>transistor</strong> pertama. Ukurannya jauh lebih kecil, lebih hemat daya, dan lebih tahan lama dibanding tabung vakum.</p>
<img src="/storage/blog/transistor.jpg" alt="Para penemu transistor: Bardeen, Shockley, dan Brattain" class="w-full h-auto my-4">
<p>Transistor mengubah segalanya. Komputer yang tadinya sebesar ruangan bisa menyusut drastis, membuka jalan menuju komputer pribadi.</p>

<h2>Kelahiran Microprocessor (1971)</h2>
<p>Dari transistor muncullah <strong>integrated circuit (IC)</strong> — jutaan komponen dalam satu chip kecil. Puncaknya adalah <strong>Intel 4004</strong>, mikroprosesor komersial pertama di dunia pada tahun 1971.</p>
<img src="/storage/blog/intel4004.jpg" alt="Intel 4004 mikroprosesor pertama" class="w-full h-auto my-4">
<p>Dengan hanya 2.300 transistor, Intel 4004 menandai era komputer personal bisa beredar luas.</p>

<h2>Komputer Pribadi dan Personal Computing (1970-an — 1980-an)</h2>
<p>Dekade 1980-an adalah era ledakan komputer pribadi. Apple II, IBM PC, dan Commodore membuat komputer bisa dimiliki keluarga biasa. Sistem operasi berbasis teks berkembang menjadi GUI (Graphical User Interface).</p>
<p>Munculnya <strong>internet</strong> dan <strong>World Wide Web</strong> (1990) oleh Tim Berners-Lee menjadikan komputasi terhubung secara global.</p>
<img src="/storage/blog/webserver.jpg" alt="Server web pertama di dunia" class="w-full h-auto my-4">

<h2>Era Internet, Mobile, dan Cloud (2000-an — 2010-an)</h2>
<p>Internet membawa <strong>e-commerce</strong>, <strong>media sosial</strong>, dan <strong>cloud computing</strong>. Server tidak lagi harus dibeli — bisa dipinjam secara on-demand. Ponsel pintar (<em>smartphone</em>) membuat komputasi berada di saku kita.</p>
<img src="/storage/blog/datacenter.jpg" alt="Pusat data (data center) besar" class="w-full h-auto my-4">
<p>Pada era ini pula <strong>big data</strong> dan <strong>machine learning</strong> mulai meledak, disokong prosesor grafis (GPU) yang mampu menghitung paralel dalam skala raksasa.</p>

<h2>Revolusi Kecerdasan Buatan (2010-an — kini)</h2>
<p>Kombinasi <strong>GPU</strong>, <strong>big data</strong>, dan <strong>algoritma deep learning</strong> melahirkan kecerdasan buatan modern. Dari pengenalan gambar, suara, hingga model bahasa besar (LLM) yang mampu menulis, berkode, dan bernalar seperti manusia.</p>
<img src="/storage/blog/ai.jpg" alt="Ilustrasi AI dan jaringan syaraf" class="w-full h-auto my-4">
<p>Hari ini AI bukan lagi riset — ia sudah menjadi bagian dari aplikasi sehari-hari: asisten virtual, mobil otonom, hingga agen AI yang bekerja mandiri.</p>

<h2>Era Quantum (2025 ke depan)</h2>
<p>Langkah berikutnya adalah <strong>komputasi kuantum</strong> (quantum computing) dan <strong>komputasi neuromorfik</strong> yang meniru cara kerja otak manusia. Dua bidang ini menjanjikan kecepatan jauh melampaui komputer klasik untuk masalah tertentu.</p>
<img src="/storage/blog/hero.jpg" alt="Komputer kuantum dan masa depan" class="w-full h-auto my-4">

<h2>Kesimpulan: Dari Tabung ke Kecerdasan</h2>
<p>Perjalanan komputasi adalah kisah penyusutan: dari ruangan penuh tabung, menjadi chip sekecil kuku, lalu menjadi kecerdasan yang bisa berpikir. Setiap generasi membuka kemungkinan baru yang sebelumnya dianggap mustahil — dan masa depan kuantum serta AI hanyalah awal dari bab berikutnya.</p>
';

        BlogPost::updateOrCreate(
            ['slug' => 'sejarah-komputasi-vacuum-tube-ke-quantum'],
            [
                'title'          => 'Sejarah Teknologi Komputasi: Dari Vacuum Tube ke Quantum',
                'excerpt'        => 'Perjalanan lengkap sejarah komputasi — dari tabung vakum ENIAC, transistor, mikroprosesor, internet, cloud, AI, hingga komputasi kuantum di masa depan.',
                'content'        => $content,
                'featured_image' => 'blog/eniac.jpg',
                'category'       => 'Teknologi',
                'is_published'   => true,
                'published_at'   => now(),
            ]
        );

        $p = BlogPost::where('slug', 'sejarah-komputasi-vacuum-tube-ke-quantum')->first();
        echo "Artikel dibuat/update id={$p->id} | {$p->title}\n";
        echo "URL: /blog/{$p->slug}\n";
        echo "Featured: /storage/{$p->featured_image}\n";
    }
}
