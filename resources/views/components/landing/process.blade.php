@props(['processSection' => null])

@php
    $section = $processSection;
    $extraData = $section?->extra_data ?? [];
    $stats = [
        ['value' => '4', 'label' => 'Tahapan'],
        ['value' => '1', 'label' => 'Tim Dedicated'],
    ];
    $steps = $extraData['steps'] ?? [
        ['number' => '01', 'title' => 'Konsultasi & Analisis', 'description' => 'Memahami kebutuhan bisnis Anda secara mendalam melalui diskusi dan analisis komprehensif.', 'duration' => '1-2 Minggu'],
        ['number' => '02', 'title' => 'Perencanaan & Desain', 'description' => 'Merancang solusi teknis dan desain UI/UX yang sesuai dengan kebutuhan dan tujuan bisnis.', 'duration' => '2-3 Minggu'],
        ['number' => '03', 'title' => 'Pengembangan & Testing', 'description' => 'Mengembangkan solusi dengan metodologi agile dan pengujian menyeluruh di setiap iterasi.', 'duration' => 'Bervariasi'],
        ['number' => '04', 'title' => 'Deployment & Support', 'description' => 'Meluncurkan solusi dan memberikan dukungan teknis berkelanjutan pasca-implementasi.', 'duration' => 'Ongoing'],
    ];
@endphp

<section id="about" class="scroll-mt-24 bg-brand-snow py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-8">
            <div class="landing-reveal lg:col-span-5">
                <span class="font-mono text-sm uppercase tracking-widest text-brand-accent">
                    Alur Kerja
                </span>
                <h2 class="mt-4 font-display text-3xl font-bold tracking-tight text-brand-navy sm:text-4xl">
                    {{ $section?->title ?? 'Alur Kerja Kami' }}
                </h2>
                <p class="mt-4 text-base text-brand-navy/70">
                    {{ $section?->subtitle ?? 'Proses yang terstruktur untuk hasil yang optimal.' }}
                </p>

                <div class="mt-10 flex flex-wrap gap-8">
                    @foreach ($stats as $stat)
                        <div class="{{ $loop->first ? '' : 'border-l border-brand-navy/10 pl-8' }}">
                            <div class="font-display text-2xl font-bold text-brand-navy">
                                {{ $stat['value'] }}
                            </div>
                            <div class="mt-1 text-sm text-brand-navy/60">
                                {{ $stat['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="landing-reveal landing-reveal-delay-2 lg:col-span-7">
                <div class="divide-y divide-brand-navy/10 border-t border-brand-navy/10">
                    @foreach ($steps as $step)
                        <div class="grid grid-cols-12 items-start gap-4 rounded-lg px-4 py-6 -mx-4 transition hover:bg-brand-navy/[0.03]">
                            <div class="col-span-2 font-mono text-sm text-brand-accent sm:col-span-1">
                                {{ $step['number'] }}
                            </div>
                            <div class="col-span-10 sm:col-span-8">
                                <h3 class="font-display text-lg font-bold text-brand-navy">
                                    {{ $step['title'] }}
                                </h3>
                                <p class="mt-1 text-sm text-brand-navy/60">
                                    {{ $step['description'] }}
                                </p>
                            </div>
                            <div class="col-span-12 text-right font-mono text-xs text-brand-navy/40 sm:col-span-3">
                                {{ $step['duration'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
