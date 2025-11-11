<?php
namespace Src\Content\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Presentation\Requests\BlogPostRequest;
use Src\Content\Application\Actions\ListBlogPostsAction;
use Src\Content\Application\Actions\CreateBlogPostAction;
use Src\Content\Application\Actions\ShowBlogPostAction;
use Src\Content\Application\Actions\UpdateBlogPostAction;
use Src\Content\Application\Actions\DeleteBlogPostAction;
use Src\Content\Application\DTOs\BlogPostDTO;

/**
 * BlogPostController controller.
 *
 * @package Src\Content\Presentation\Controllers\Admin
 */
class BlogPostController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(BlogPost::class, 'blog');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListBlogPostsAction $list)
    {
        $items = $list->execute([
            'q'=>$request->get('q'),
            'status'=>$request->get('status'),
            'sort'=>$request->get('sort'),
            'dir'=>$request->get('dir'),
        ], (int)$request->get('per_page', 20));
        if ($request->wantsJson()) return response()->json($items);
        return view('admin.blogs.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->wantsJson()) return response()->json(['message' => 'Provide blog post payload to store.']);
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BlogPostRequest $request, CreateBlogPostAction $create)
    {
        $post = $create->execute(BlogPostDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($post, 201);
        return redirect()->route('admin.blogs.index')->with('status', 'Blog post created');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, BlogPost $blog, ShowBlogPostAction $show)
    {
        $blog = $show->execute($blog);
        if ($request->wantsJson()) return response()->json($blog);
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, BlogPost $blog, ShowBlogPostAction $show)
    {
        $blog = $show->execute($blog);
        if ($request->wantsJson()) return response()->json($blog);
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BlogPostRequest $request, BlogPost $blog, UpdateBlogPostAction $update)
    {
        $post = $update->execute($blog, BlogPostDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($post);
        return redirect()->route('admin.blogs.index')->with('status', 'Blog post updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, BlogPost $blog, DeleteBlogPostAction $delete)
    {
        $delete->execute($blog);
        if ($request->wantsJson()) return response()->json(null, 204);
        return redirect()->route('admin.blogs.index')->with('status', 'Blog post deleted');
    }
}


