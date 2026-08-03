<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    public function index(): View
    {
        return view('pages.main.index', [
            'seoDescription' => Setting::get('seo_description'),
            'analyticsId' => Setting::get('analytics_id'),
            'heroSection' => Page::section('hero'),
            'aboutSection' => Page::section('about'),
            'visionSection' => Page::section('vision'),
            'whyUsSection' => Page::section('why_us'),
            'processSection' => Page::section('process'),
            'services' => Service::query()->published()->featured()->orderBy('sort_order')->get(),
        ]);
    }

    public function blogs(): View
    {
        return view('pages.main.blogs', [
            'posts' => Post::query()->published()->with('author')->latest()->paginate(9),
        ]);
    }

    public function blogDetail(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->published()->with('author')->firstOrFail();

        return view('pages.main.blog-detail', [
            'post' => $post,
        ]);
    }

    public function gallery(): View
    {
        return view('pages.main.gallery', [
            'items' => GalleryItem::query()->published()->orderBy('sort_order')->paginate(12),
            'categories' => GalleryItem::query()->published()->whereNotNull('category')->distinct()->pluck('category')->sort()->values(),
        ]);
    }

    public function serviceDetail(string $slug): View
    {
        $service = Service::query()->where('slug', $slug)->published()->firstOrFail();

        return view('pages.main.service-detail', [
            'service' => $service,
            'otherServices' => Service::query()->published()->where('id', '!=', $service->id)->orderBy('sort_order')->get(),
        ]);
    }

    public function sitemap(): Response
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home')))
            ->add(Url::create(route('blogs')))
            ->add(Url::create(route('gallery')));

        Post::query()->published()->get()->each(
            fn (Post $post) => $sitemap->add(
                Url::create(route('blog.detail', $post->slug))->setLastModificationDate($post->updated_at)
            )
        );

        Service::query()->published()->get()->each(
            fn (Service $service) => $sitemap->add(
                Url::create(route('service.detail', $service->slug))->setLastModificationDate($service->updated_at)
            )
        );

        return $sitemap->toResponse(request());
    }
}
