<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'section_key' => 'hero',
                'title' => 'Mitra Teknologi Terpercaya untuk Solusi Digital Anda',
                'subtitle' => 'PT. Reka Mitra Teknologi menghadirkan solusi teknologi inovatif yang mendorong transformasi digital bisnis Anda — dari konsep hingga implementasi.',
                'body' => null,
                'extra_data' => [
                    'badge_text' => 'Solusi Digital Terpercaya',
                    'badge_highlight' => 'Sejak 2018',
                    'cta_primary' => 'Konsultasi Gratis',
                    'cta_secondary' => 'Lihat Layanan',
                    'stats' => [
                        ['value' => '150', 'suffix' => '+', 'label' => 'Proyek Selesai'],
                        ['value' => '50', 'suffix' => '+', 'label' => 'Klien Puas'],
                        ['value' => '8', 'suffix' => '+', 'label' => 'Tahun Pengalaman'],
                    ],
                ],
                'sort_order' => 1,
            ],
            [
                'section_key' => 'about',
                'title' => 'Tentang Kami',
                'subtitle' => 'Membangun masa depan digital bersama',
                'body' => "PT. Reka Mitra Teknologi adalah perusahaan teknologi informasi yang didirikan pada tahun 2018 di Jakarta. Kami berfokus pada pengembangan solusi digital yang inovatif dan berkualitas tinggi untuk membantu perusahaan dari berbagai sektor dalam transformasi digital mereka.\n\nDengan tim yang terdiri dari para profesional berpengalaman di bidang teknologi, kami berkomitmen untuk memberikan solusi terbaik yang tidak hanya memenuhi kebutuhan saat ini, tetapi juga siap menghadapi tantangan di masa depan.\n\nKami percaya bahwa teknologi yang tepat dapat menjadi pendorong utama pertumbuhan bisnis. Itulah mengapa setiap solusi yang kami kembangkan dirancang dengan mempertimbangkan skalabilitas, keamanan, dan kemudahan penggunaan.",
                'extra_data' => [
                    'regions' => [
                        ['name' => 'Backend', 'detail' => 'Laravel · Node.js · Python · Go'],
                        ['name' => 'Frontend', 'detail' => 'React · Vue.js · Flutter · Livewire'],
                        ['name' => 'Infrastructure', 'detail' => 'AWS · GCP · Docker · Kubernetes'],
                    ],
                ],
                'sort_order' => 2,
            ],
            [
                'section_key' => 'vision',
                'title' => 'Visi & Misi',
                'subtitle' => null,
                'body' => null,
                'extra_data' => [
                    'vision' => 'Menjadi mitra teknologi terdepan di Indonesia yang menghadirkan solusi digital inovatif dan berdampak positif bagi pertumbuhan bisnis klien.',
                    'missions' => [
                        'Mengembangkan solusi teknologi yang inovatif, scalable, dan berkualitas tinggi.',
                        'Membangun hubungan jangka panjang dengan klien melalui layanan yang profesional dan responsif.',
                        'Terus berinovasi dan mengadopsi teknologi terkini untuk memberikan nilai tambah maksimal.',
                        'Mengembangkan talenta digital lokal yang kompeten dan berdaya saing global.',
                    ],
                ],
                'sort_order' => 3,
            ],
            [
                'section_key' => 'why_us',
                'title' => 'Mengapa Memilih Kami',
                'subtitle' => 'Komitmen kami terhadap kualitas dan kepuasan klien.',
                'body' => null,
                'extra_data' => [
                    'stats' => [
                        ['value' => '150', 'suffix' => '+', 'label' => 'Proyek Selesai', 'sublabel' => 'di berbagai sektor industri'],
                        ['value' => '50', 'suffix' => '+', 'label' => 'Klien Puas', 'sublabel' => 'dari startup hingga enterprise'],
                        ['value' => '8', 'suffix' => '+', 'label' => 'Tahun Pengalaman', 'sublabel' => 'solusi digital terpercaya'],
                        ['value' => '30', 'suffix' => '+', 'label' => 'Tim Profesional', 'sublabel' => 'engineer & designer'],
                    ],
                ],
                'sort_order' => 4,
            ],
            [
                'section_key' => 'process',
                'title' => 'Alur Kerja Kami',
                'subtitle' => 'Proses yang terstruktur untuk hasil yang optimal',
                'body' => null,
                'extra_data' => [
                    'steps' => [
                        ['number' => '01', 'title' => 'Konsultasi & Analisis', 'description' => 'Memahami kebutuhan bisnis Anda secara mendalam melalui diskusi dan analisis komprehensif.', 'duration' => '1-2 Minggu'],
                        ['number' => '02', 'title' => 'Perencanaan & Desain', 'description' => 'Merancang solusi teknis dan desain UI/UX yang sesuai dengan kebutuhan dan tujuan bisnis.', 'duration' => '2-3 Minggu'],
                        ['number' => '03', 'title' => 'Pengembangan & Testing', 'description' => 'Mengembangkan solusi dengan metodologi agile dan pengujian menyeluruh di setiap iterasi.', 'duration' => 'Bervariasi'],
                        ['number' => '04', 'title' => 'Deployment & Support', 'description' => 'Meluncurkan solusi dan memberikan dukungan teknis berkelanjutan pasca-implementasi.', 'duration' => 'Ongoing'],
                    ],
                ],
                'sort_order' => 5,
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
