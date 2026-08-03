<x-layouts::main :seo-description="$seoDescription" :analytics-id="$analyticsId">
    <x-landing.hero :hero-section="$heroSection" />
    <x-landing.trusted-by />
    <x-landing.services :services="$services" />
    <x-landing.infrastructure :about-section="$aboutSection" />
    <x-landing.stats :why-us-section="$whyUsSection" />
    <x-landing.case-studies :vision-section="$visionSection" />
    <x-landing.process :process-section="$processSection" />
    <x-landing.cta />
</x-layouts::main>
