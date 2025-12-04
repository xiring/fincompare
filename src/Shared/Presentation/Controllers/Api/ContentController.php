<?php

namespace Src\Shared\Presentation\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Content\Application\DTOs\BlogListFiltersDTO;
use Src\Content\Application\Services\PublicBlogService;
use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Domain\Repositories\FaqRepositoryInterface;
use Src\Partners\Domain\Entities\Partner;
use Src\Shared\Presentation\Resources\CategoryResource;
use Src\Shared\Presentation\Resources\PartnerResource;
use Src\Shared\Presentation\Resources\ProductResource;

/**
 * ContentController - Handles public content API endpoints.
 */
class ContentController extends Controller
{
    public function __construct(
        private readonly FaqRepositoryInterface $faqsRepo,
        private readonly PublicBlogService $blogService
    ) {}

    public function home(): JsonResponse
    {
        $featuredProducts = Product::with(['partner', 'productCategory'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $categories = ProductCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        $partners = Partner::where('status', 'active')
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json([
            'featured_products' => ProductResource::collection($featuredProducts),
            'categories' => CategoryResource::collection($categories),
            'partners' => PartnerResource::collection($partners),
            'hero_slides' => [
                [
                    'title' => 'Find the Best Financial Products',
                    'subtitle' => 'Compare loans, credit cards, and insurance plans to save money.',
                    'image' => '/images/hero-1.jpg',
                    'cta_text' => 'Start Comparing',
                    'cta_link' => '/products'
                ]
            ]
        ]);
    }

    public function faqs(): JsonResponse
    {
        $faqs = $this->faqsRepo->list(['sort' => 'created_at', 'dir' => 'asc']);
        return response()->json($faqs);
    }

    public function blogIndex(Request $request): JsonResponse
    {
        $filters = BlogListFiltersDTO::fromRequest($request->all());

        $posts = $this->blogService->getPaginatedPosts($filters);
        $categories = $this->blogService->getAvailableCategories();
        $tags = $this->blogService->getAvailableTags();

        return response()->json([
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    public function blogShow(string $slug): JsonResponse
    {
        $post = BlogPost::query()->where('slug', $slug)->where('status', 'published')->firstOrFail();
        return response()->json($post);
    }
}
