<?php

namespace Src\Content\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Content\Application\Actions\CreateBlogPostAction;
use Src\Content\Application\Actions\DeleteBlogPostAction;
use Src\Content\Application\Actions\ListBlogPostsAction;
use Src\Content\Application\Actions\ShowBlogPostAction;
use Src\Content\Application\Actions\UpdateBlogPostAction;
use Src\Content\Application\DTOs\BlogPostDTO;
use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Presentation\Requests\BlogPostRequest;

/**
 * BlogPostController controller.
 */
class BlogPostController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(BlogPost::class, 'blog');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListBlogPostsAction $list)
    {
        $items = $list->execute([
            'q' => $request->get('q'),
            'status' => $request->get('status'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ], (int) $request->get('per_page', 20));
        
        return response()->json($items);

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        
        return response()->json(['message' => 'Provide blog post payload to store.']);

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BlogPostRequest $request, CreateBlogPostAction $create)
    {
        $post = $create->execute(BlogPostDTO::fromArray($request->validated()));
        
        return response()->json($post, 201);

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, BlogPost $blog, ShowBlogPostAction $show)
    {
        $blog = $show->execute($blog);
        
        return response()->json($blog);

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, BlogPost $blog, ShowBlogPostAction $show)
    {
        $blog = $show->execute($blog);
        
        return response()->json($blog);

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BlogPostRequest $request, BlogPost $blog, UpdateBlogPostAction $update)
    {
        $post = $update->execute($blog, BlogPostDTO::fromArray($request->validated()));
        
        return response()->json($post);

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, BlogPost $blog, DeleteBlogPostAction $delete)
    {
        $delete->execute($blog);
        
        return response()->json(null, 204);

}
