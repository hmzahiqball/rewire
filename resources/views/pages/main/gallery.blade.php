<x-layouts::main title="Portfolio & Gallery">
    <section class="landing-grid-bg relative overflow-hidden bg-brand-navy pt-32 pb-20 sm:pt-40 sm:pb-24">
        <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
            <div class="landing-reveal max-w-2xl">
                <p class="font-mono text-sm uppercase tracking-widest text-brand-accent">Portfolio</p>
                <h1 class="mt-4 font-display text-4xl font-bold tracking-tight text-brand-snow sm:text-5xl">
                    Karya &amp; Pencapaian Kami
                </h1>
                <p class="mt-4 text-lg leading-relaxed text-brand-silver/80">
                    Jelajahi berbagai solusi digital inovatif yang telah kami kembangkan untuk klien-klien kami.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-brand-snow py-24 sm:py-32" x-data="{ activeCategory: 'all' }">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            @if ($items->isEmpty())
                <p class="text-brand-navy/60">Belum ada portfolio yang dipublikasikan.</p>
            @else
                @if (count($categories) > 0)
                    <div class="mb-12 flex flex-wrap items-center gap-3">
                        <button
                            type="button"
                            x-on:click="activeCategory = 'all'"
                            :class="activeCategory === 'all' ? 'bg-brand-navy text-brand-snow' : 'bg-white text-brand-navy hover:bg-brand-navy/5'"
                            class="rounded-full border border-brand-navy/10 px-5 py-2 text-sm font-medium transition"
                        >
                            Semua
                        </button>
                        @foreach ($categories as $category)
                            <button
                                type="button"
                                x-on:click="activeCategory = '{{ $category }}'"
                                :class="activeCategory === '{{ $category }}' ? 'bg-brand-navy text-brand-snow' : 'bg-white text-brand-navy hover:bg-brand-navy/5'"
                                class="rounded-full border border-brand-navy/10 px-5 py-2 text-sm font-medium transition"
                            >
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($items as $item)
                        <div
                            x-show="activeCategory === 'all' || activeCategory === '{{ $item->category }}'"
                            class="landing-reveal landing-card-hover group flex flex-col overflow-hidden rounded-3xl border border-brand-navy/10 bg-white"
                        >
                            @if ($item->getFirstMediaUrl('gallery_image', 'card'))
                                <div class="overflow-hidden h-56 w-full">
                                    <img
                                        src="{{ $item->getFirstMediaUrl('gallery_image', 'card') }}"
                                        alt="{{ $item->title }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    >
                                </div>
                            @else
                                <div class="flex h-56 w-full items-center justify-center bg-brand-navy/5">
                                    <x-landing.icon name="photo" class="size-12 text-brand-navy/20" />
                                </div>
                            @endif

                            <div class="flex flex-1 flex-col p-6">
                                @if ($item->category)
                                    <span class="mb-2 font-mono text-xs font-semibold uppercase tracking-widest text-brand-accent-dark">
                                        {{ $item->category }}
                                    </span>
                                @endif
                                <h2 class="font-display text-xl font-bold text-brand-navy">{{ $item->title }}</h2>
                                @if ($item->description)
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-brand-navy/70">{{ Str::limit($item->description, 120) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts::main>
