<?php $socialLinks = collect([
    'linkedin' => \App\Models\Setting::get('social_linkedin'),
    'twitter' => \App\Models\Setting::get('social_twitter'),
    'github' => \App\Models\Setting::get('social_github'),
    'instagram' => \App\Models\Setting::get('social_instagram'),
])->filter(); ?>
<footer class="landing-grid-bg-dark relative overflow-hidden bg-brand-navy text-brand-snow">
    <div class="relative mx-auto max-w-7xl px-6 py-20">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
            <div class="landing-reveal lg:col-span-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Reka Mitra Teknologi" class="size-9 rounded-lg border border-brand-navy bg-brand-snow p-1.5">
                    <span class="font-display text-lg font-semibold text-brand-snow">Reka Mitra Teknologi</span>
                </a>
                <p class="mt-5 max-w-sm text-sm text-brand-silver/80">
                    PT. Reka Mitra Teknologi — solusi teknologi terpercaya untuk transformasi digital bisnis Anda. Kami membantu perusahaan dari berbagai sektor dalam mewujudkan potensi digital mereka.
                </p>
                @if ($socialLinks->isNotEmpty())
                    <div class="mt-6 flex items-center gap-3">
                        @foreach ($socialLinks as $platform => $url)
                            <a
                                href="{{ $url }}"
                                target="_blank"
                                rel="noopener"
                                class="flex size-9 items-center justify-center rounded-lg border border-brand-snow/15 text-brand-silver transition hover:border-brand-accent/40 hover:text-brand-accent"
                            >
                                <x-landing.icon :name="$platform" class="size-4" />
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @foreach ([
                [
                    'heading' => 'Layanan',
                    'links' => [
                        ['label' => 'Layanan Kami', 'url' => route('home').'#services'],
                        ['label' => 'Tentang Kami', 'url' => route('home').'#infrastructure'],
                        ['label' => 'Visi & Misi', 'url' => route('home').'#solutions'],
                        ['label' => 'Blog', 'url' => route('blogs')],
                    ],
                ],
                [
                    'heading' => 'Perusahaan',
                    'links' => [
                        ['label' => 'Alur Kerja', 'url' => route('home').'#about'],
                        ['label' => 'Portfolio', 'url' => route('gallery')],
                        ['label' => 'Kontak', 'url' => route('home').'#contact'],
                    ],
                ],
                [
                    'heading' => 'Akun',
                    'links' => [
                        ['label' => 'Masuk', 'url' => route('login')],
                        ['label' => 'Daftar', 'url' => route('register')],
                    ],
                ],
            ] as $column)
                <div class="landing-reveal landing-reveal-delay-{{ min($loop->iteration, 4) }} lg:col-span-2">
                    <p class="font-mono text-xs font-medium uppercase tracking-widest text-brand-silver/60">
                        {{ $column['heading'] }}
                    </p>
                    <ul class="mt-5 space-y-3">
                        @foreach ($column['links'] as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="text-sm text-brand-silver/80 transition hover:text-brand-snow">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="mt-16 flex flex-col gap-6 border-t border-brand-snow/10 pt-8 lg:flex-row lg:items-center lg:justify-between">
            <p class="text-sm text-brand-silver/60">
                &copy; {{ date('Y') }} PT. Reka Mitra Teknologi. All rights reserved.
            </p>
            <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                <a href="#" class="text-sm text-brand-silver/60 transition hover:text-brand-snow">Kebijakan Privasi</a>
                <a href="#" class="text-sm text-brand-silver/60 transition hover:text-brand-snow">Syarat & Ketentuan</a>
                <span class="flex items-center gap-2 text-sm text-brand-silver/60">
                    <span class="landing-animate-pulse-soft size-2 rounded-full bg-emerald-400"></span>
                    Sistem Operasional
                </span>
            </div>
        </div>
    </div>
</footer>
