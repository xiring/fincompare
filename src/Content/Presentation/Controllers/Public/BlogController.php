<?php
namespace Src\Content\Presentation\Controllers\Public;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Src\Content\Domain\Entities\BlogPost;

/**
 * BlogController controller.
 *
 * @package Src\Content\Presentation\Controllers\Public
 */
class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $q = (string)$request->get('q');
        $category = (string)$request->get('category');
        $tag = (string)$request->get('tag');
        $sort = strtolower((string)$request->get('sort', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = BlogPost::query()->where('status', 'published')
            ->when($q, function ($qbuilder) use ($q) {
                $qbuilder->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%$q%")
                       ->orWhere('content', 'like', "%$q%");
                });
            })
            ->when($category, fn($qb) => $qb->where('category', $category))
            ->when($tag, fn($qb) => $qb->whereJsonContains('tags', $tag))
            ->orderBy('created_at', $sort);

        $posts = $query->paginate(9)->withQueryString();

        // For filters UI
        $categories = BlogPost::query()->where('status','published')->whereNotNull('category')
            ->distinct()->orderBy('category')->pluck('category');
        $tags = BlogPost::query()->where('status','published')->pluck('tags')
            ->filter()->flatMap(function ($item) { return is_array($item) ? $item : []; })
            ->unique()->sort()->values();

        if ($request->wantsJson()) {
            $html = view()->file(base_path('src/Shared/Presentation/Views/Public/_blog_cards_chunk.blade.php'), [
                'posts' => $posts,
            ])->render();
            return response()->json([
                'html' => $html,
                'next' => $posts->nextPageUrl(),
            ]);
        }

        return view()->file(base_path('src/Shared/Presentation/Views/Public/blog_index.blade.php'), compact('posts','categories','tags','category','tag','sort','q'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function show(string $slug)
    {
        $post = BlogPost::query()->where('slug', $slug)->where('status','published')->firstOrFail();

        return view()->file(base_path('src/Shared/Presentation/Views/Public/blog_show.blade.php'), compact('post'));
    }
}


