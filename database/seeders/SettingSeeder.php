<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::put('contact_address', 'Jl. Sudirman No. 123, Jakarta Selatan 12190');
        Setting::put('contact_email', 'info@rekamitra.co.id');
        Setting::put('contact_phone', '+62 21 5555 0123');
        Setting::put('seo_description', 'PT. Reka Mitra Teknologi — Mitra teknologi terpercaya untuk solusi digital bisnis Anda. Pengembangan aplikasi web & mobile, konsultasi IT, cloud, dan DevOps.');
        Setting::put('social_linkedin', 'https://linkedin.com/company/rekamitra');
        Setting::put('social_instagram', 'https://instagram.com/rekamitra');
    }
}
