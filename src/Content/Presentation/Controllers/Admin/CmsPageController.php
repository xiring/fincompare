<?php

namespace Src\Content\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Content\Application\Actions\CreateCmsPageAction;
use Src\Content\Application\Actions\DeleteCmsPageAction;
use Src\Content\Application\Actions\ListCmsPagesAction;
use Src\Content\Application\Actions\ShowCmsPageAction;
use Src\Content\Application\Actions\UpdateCmsPageAction;
use Src\Content\Application\DTOs\CmsPageDTO;
use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Presentation\Requests\CmsPageRequest;

/**
 * CmsPageController controller.
 */
class CmsPageController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(CmsPage::class, 'cms_page');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListCmsPagesAction $list)
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
        
        return response()->json(['message' => 'Provide page payload to store.']);

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CmsPageRequest $request, CreateCmsPageAction $create)
    {
        $page = $create->execute(CmsPageDTO::fromArray($request->validated()));
        
        return response()->json($page, 201);

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, CmsPage $cms_page, ShowCmsPageAction $show)
    {
        $cms_page = $show->execute($cms_page);
        
        return response()->json($cms_page);

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, CmsPage $cms_page, ShowCmsPageAction $show)
    {
        $cms_page = $show->execute($cms_page);
        
        return response()->json($cms_page);

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CmsPageRequest $request, CmsPage $cms_page, UpdateCmsPageAction $update)
    {
        $item = $update->execute($cms_page, CmsPageDTO::fromArray($request->validated()));
        
        return response()->json($item);

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, CmsPage $cms_page, DeleteCmsPageAction $delete)
    {
        $delete->execute($cms_page);
        
        return response()->json(null, 204);

}
