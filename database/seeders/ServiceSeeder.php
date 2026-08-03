<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Pengembangan Aplikasi Web',
                'icon' => 'code',
                'short_description' => 'Membangun aplikasi web modern, responsif, dan skalabel sesuai kebutuhan bisnis Anda.',
                'body' => "Kami mengembangkan aplikasi web berkualitas tinggi menggunakan teknologi terkini seperti Laravel, React, dan Vue.js. Setiap solusi dirancang khusus untuk memenuhi kebutuhan unik bisnis Anda.\n\nTim kami berpengalaman dalam membangun berbagai jenis aplikasi — mulai dari sistem manajemen internal, platform e-commerce, hingga aplikasi SaaS yang kompleks. Kami menerapkan best practice dalam pengembangan perangkat lunak untuk memastikan kode yang bersih, teruji, dan mudah di-maintain.",
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Pengembangan Aplikasi Mobile',
                'icon' => 'cloud',
                'short_description' => 'Aplikasi mobile native dan cross-platform untuk iOS dan Android.',
                'body' => "Kami membangun aplikasi mobile yang intuitif dan performan untuk platform iOS dan Android. Dengan pendekatan cross-platform menggunakan Flutter dan React Native, kami memastikan konsistensi pengalaman pengguna di semua perangkat.\n\nDari konsep hingga deployment di App Store dan Google Play, tim kami menangani seluruh siklus pengembangan mobile — termasuk desain UI/UX, integrasi API, push notification, dan optimasi performa.",
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Konsultasi IT & Infrastruktur',
                'icon' => 'shield',
                'short_description' => 'Strategi teknologi dan infrastruktur yang tepat untuk mendukung pertumbuhan bisnis.',
                'body' => "Kami menyediakan layanan konsultasi IT komprehensif untuk membantu perusahaan Anda membuat keputusan teknologi yang tepat. Tim konsultan kami akan menganalisis kebutuhan bisnis, mengevaluasi infrastruktur yang ada, dan merancang roadmap teknologi yang selaras dengan tujuan perusahaan.\n\nLayanan kami meliputi audit IT, perencanaan arsitektur sistem, migrasi infrastruktur, dan implementasi solusi keamanan siber.",
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'UI/UX Design',
                'icon' => 'brain',
                'short_description' => 'Desain antarmuka yang indah dan pengalaman pengguna yang intuitif.',
                'body' => "Tim desainer kami menciptakan antarmuka yang tidak hanya menarik secara visual, tetapi juga memberikan pengalaman pengguna yang luar biasa. Kami menerapkan metodologi design thinking dan user-centered design dalam setiap proyek.\n\nProses kami meliputi riset pengguna, wireframing, prototyping interaktif, visual design, dan usability testing. Kami memastikan setiap desain tidak hanya cantik, tetapi juga fungsional dan accessible.",
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Cloud & DevOps',
                'icon' => 'cog',
                'short_description' => 'Infrastruktur cloud yang handal dan pipeline CI/CD yang efisien.',
                'body' => "Kami membantu perusahaan Anda bermigrasi ke cloud dan mengimplementasikan praktik DevOps modern. Dengan pengalaman di AWS, Google Cloud, dan Azure, kami merancang infrastruktur yang scalable, secure, dan cost-effective.\n\nLayanan kami mencakup setup server cloud, containerization dengan Docker dan Kubernetes, pipeline CI/CD, monitoring dan alerting, serta disaster recovery planning.",
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Maintenance & Support',
                'icon' => 'compass',
                'short_description' => 'Dukungan teknis berkelanjutan dan pemeliharaan sistem yang andal.',
                'body' => "Kami menyediakan layanan maintenance dan support untuk memastikan sistem Anda berjalan optimal 24/7. Tim support kami siap membantu menangani issue, melakukan update keamanan, dan memastikan performa sistem tetap prima.\n\nPaket maintenance kami meliputi monitoring proaktif, backup rutin, patch keamanan, optimasi performa, dan dukungan teknis dengan SLA yang jelas.",
                'is_featured' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
