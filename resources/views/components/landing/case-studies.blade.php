@props(['visionSection' => null])

@php
    $section = $visionSection;
    $extraData = $section?->extra_data ?? [];
    $vision = $extraData['vision'] ?? 'Menjadi mitra teknologi terdepan di Indonesia yang menghadirkan solusi digital inovatif dan berdampak positif bagi pertumbuhan bisnis klien.';
    $missions = $extraData['missions'] ?? [
        'Mengembangkan solusi teknologi yang inovatif, scalable, dan berkualitas tinggi.',
        'Membangun hubungan jangka panjang dengan klien melalui layanan yang profesional dan responsif.',
        'Terus berinovasi dan mengadopsi teknologi terkini untuk memberikan nilai tambah maksimal.',
        'Mengembangkan talenta digital lokal yang kompeten dan berdaya saing global.',
    ];
@endphp

<section id="solutions" class="scroll-mt-24 bg-brand-snow py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="landing-reveal max-w-2xl">
            <p class="font-mono text-sm uppercase tracking-widest text-brand-accent-dark">
                Visi & Misi
            </p>
            <h2 class="mt-4 font-display text-4xl font-bold tracking-tight text-brand-navy sm:text-5xl">
                {{ $section?->title ?? 'Visi & Misi Kami' }}
            </h2>
        </div>

        <div class="mt-16 grid gap-8 lg:grid-cols-2">
            {{-- Vision Card --}}
            <div class="landing-reveal landing-reveal-delay-1 rounded-3xl bg-brand-navy p-10 sm:p-12">
                <div class="flex items-center justify-between">
                    <span class="font-mono text-xs uppercase tracking-widest text-brand-accent">
                        Visi
                    </span>
                    <span class="font-mono text-xs text-brand-snow/60">
                        <x-landing.icon name="star" class="inline size-4" />
                    </span>
                </div>
                <h3 class="mt-6 font-display text-3xl font-bold text-brand-snow sm:text-4xl">
                    Visi Perusahaan
                </h3>
                <p class="mt-4 text-lg leading-relaxed text-brand-snow/80">
                    {{ $vision }}
                </p>

                <div class="mt-10 grid grid-cols-2 gap-6 border-t border-white/10 pt-8 sm:grid-cols-3">
                    <div>
                        <p class="font-display text-3xl font-bold text-brand-accent">
                            #1
                        </p>
                        <p class="mt-1 text-xs text-brand-snow/60">
                            Mitra Teknologi Pilihan
                        </p>
                    </div>
                    <div>
                        <p class="font-display text-3xl font-bold text-brand-accent">
                            ∞
                        </p>
                        <p class="mt-1 text-xs text-brand-snow/60">
                            Komitmen Inovasi
                        </p>
                    </div>
                </div>
            </div>

            {{-- Mission Card --}}
            <div class="landing-reveal landing-card-hover landing-reveal-delay-2 rounded-3xl border border-brand-navy/10 bg-brand-snow p-10 sm:p-12">
                <div class="flex items-center justify-between">
                    <span class="font-mono text-xs uppercase tracking-widest text-brand-accent-dark">
                        Misi
                    </span>
                    <span class="font-mono text-xs text-brand-navy/50">
                        <x-landing.icon name="compass" class="inline size-4" />
                    </span>
                </div>
                <h3 class="mt-6 font-display text-2xl font-bold text-brand-navy">
                    Misi Perusahaan
                </h3>

                <div class="mt-6 space-y-4">
                    @foreach ($missions as $index => $mission)
                        <div class="flex items-start gap-4">
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-brand-accent/10 font-mono text-xs font-bold text-brand-accent-dark">
                                {{ $index + 1 }}
                            </span>
                            <p class="text-sm leading-relaxed text-brand-navy/70">
                                {{ $mission }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
