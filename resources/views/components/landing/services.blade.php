@props(['services' => collect()])

@php
    $items = $services;
    $last = $items->count() - 1;
@endphp

<section id="services" class="scroll-mt-24 bg-brand-snow">
    <div class="mx-auto max-w-7xl px-6 py-24">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <div class="landing-reveal lg:w-3/5">
                <div class="flex items-center gap-3">
                    <span class="h-px w-8 bg-brand-accent"></span>
                    <span class="font-mono text-xs uppercase tracking-widest text-brand-accent">
                        01 &mdash; Layanan
                    </span>
                </div>
                <h2 class="mt-4 font-display text-4xl font-semibold tracking-tight text-brand-navy sm:text-5xl">
                    Solusi digital untuk setiap kebutuhan bisnis
                </h2>
            </div>
            <div class="landing-reveal landing-reveal-delay-1 lg:w-2/5">
                <p class="text-base text-brand-navy/70">
                    Kami menyediakan layanan teknologi komprehensif — dari pengembangan aplikasi hingga infrastruktur cloud — untuk mendukung transformasi digital perusahaan Anda.
                </p>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $index => $item)
                @php
                    $delay = ($loop->index % 4) + 1;
                    $isLast = $loop->index === $last;
                    $number = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                @endphp

                @if ($isLast)
                    <a
                        href="#contact"
                        class="landing-card-hover landing-reveal landing-reveal-delay-{{ $delay }} group flex flex-col justify-between rounded-2xl bg-brand-navy p-8"
                    >
                        <div>
                            <div class="flex size-11 items-center justify-center rounded-xl bg-brand-snow/10">
                                <x-landing.icon :name="$item->icon ?? 'compass'" class="size-5 text-brand-accent" />
                            </div>
                            <p class="mt-6 font-mono text-xs uppercase tracking-widest text-brand-silver">
                                {{ $number }} &mdash; Layanan
                            </p>
                            <h3 class="mt-2 font-display text-xl font-semibold text-brand-snow">
                                {{ $item->title }}
                            </h3>
                            <p class="mt-3 text-sm text-brand-silver">
                                {{ $item->short_description }}
                            </p>
                        </div>
                        <span class="mt-8 inline-flex items-center gap-2 text-sm font-medium text-brand-accent">
                            Hubungi Kami
                            <x-landing.icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" />
                        </span>
                    </a>
                @else
                    <div
                        class="landing-card-hover landing-reveal landing-reveal-delay-{{ $delay }} flex flex-col rounded-2xl border border-brand-navy/10 bg-white p-8"
                    >
                        <div class="flex size-11 items-center justify-center rounded-xl bg-brand-navy">
                            <x-landing.icon :name="$item->icon ?? 'code'" class="size-5 text-brand-accent" />
                        </div>
                        <p class="mt-6 font-mono text-xs uppercase tracking-widest text-brand-navy/50">
                            {{ $number }} &mdash; Layanan
                        </p>
                        <h3 class="mt-2 font-display text-xl font-semibold text-brand-navy">
                            {{ $item->title }}
                        </h3>
                        <p class="mt-3 text-sm text-brand-navy/70">
                            {{ $item->short_description }}
                        </p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
