<x-layouts::main :title="$service->title" :seo-description="$service->short_description">
    <section class="landing-grid-bg relative overflow-hidden bg-brand-navy pt-32 pb-20 sm:pt-40 sm:pb-24">
        <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
            <div class="landing-reveal max-w-3xl">
                <div class="mb-6 flex size-16 items-center justify-center rounded-2xl bg-brand-accent/20">
                    <x-landing.icon :name="$service->icon ?? 'compass'" class="size-8 text-brand-accent" />
                </div>
                <p class="font-mono text-sm uppercase tracking-widest text-brand-accent">Layanan</p>
                <h1 class="mt-4 font-display text-4xl font-bold tracking-tight text-brand-snow sm:text-5xl">
                    {{ $service->title }}
                </h1>
                <p class="mt-6 text-xl leading-relaxed text-brand-silver">
                    {{ $service->short_description }}
                </p>
            </div>
        </div>
    </section>

    <section class="bg-brand-snow py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-16 lg:grid-cols-12">
                <div class="lg:col-span-8">
                    @if ($service->getFirstMediaUrl('service_image', 'card'))
                        <div class="mb-12 overflow-hidden rounded-3xl shadow-lg">
                            <img src="{{ $service->getFirstMediaUrl('service_image', 'card') }}" alt="{{ $service->title }}" class="w-full object-cover">
                        </div>
                    @endif

                    <div class="prose prose-lg prose-zinc max-w-none prose-headings:font-display prose-headings:font-bold prose-headings:text-brand-navy prose-a:text-brand-accent-dark hover:prose-a:text-brand-accent prose-img:rounded-2xl">
                        {!! str($service->body)->markdown() !!}
                    </div>

                    <div class="mt-16 rounded-3xl bg-brand-navy p-8 sm:p-12 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-8">
                        <div>
                            <h3 class="font-display text-2xl font-bold text-brand-snow">Tertarik dengan layanan ini?</h3>
                            <p class="mt-2 text-brand-silver">Diskusikan kebutuhan proyek Anda bersama tim ahli kami.</p>
                        </div>
                        <a href="{{ route('home') }}#contact" class="shrink-0 rounded-full bg-brand-accent px-8 py-4 text-sm font-bold text-brand-navy transition hover:bg-brand-accent-dark">
                            Mulai Konsultasi
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="sticky top-32 rounded-3xl border border-brand-navy/10 bg-white p-8 shadow-sm">
                        <h3 class="font-display text-lg font-bold text-brand-navy">Layanan Lainnya</h3>
                        <div class="mt-6 flex flex-col gap-4">
                            @foreach ($otherServices as $otherService)
                                <a href="{{ route('service.detail', $otherService->slug) }}" class="group flex items-start gap-4 rounded-xl p-3 transition hover:bg-brand-navy/5">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-navy/5 text-brand-navy transition group-hover:bg-brand-navy group-hover:text-brand-snow">
                                        <x-landing.icon :name="$otherService->icon ?? 'compass'" class="size-5" />
                                    </div>
                                    <div>
                                        <h4 class="font-display text-sm font-bold text-brand-navy transition group-hover:text-brand-accent-dark">
                                            {{ $otherService->title }}
                                        </h4>
                                        <p class="mt-1 text-xs text-brand-navy/60 line-clamp-2">
                                            {{ $otherService->short_description }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts::main>
