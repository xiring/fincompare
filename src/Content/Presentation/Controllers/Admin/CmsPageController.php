<?php
namespace Src\Content\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Presentation\Requests\CmsPageRequest;
use Src\Content\Application\Actions\ListCmsPagesAction;
use Src\Content\Application\Actions\CreateCmsPageAction;
use Src\Content\Application\Actions\ShowCmsPageAction;
use Src\Content\Application\Actions\UpdateCmsPageAction;
use Src\Content\Application\Actions\DeleteCmsPageAction;
use Src\Content\Application\DTOs\CmsPageDTO;

class CmsPageController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(CmsPage::class, 'cms_page');
    }

    public function index(Request $request, ListCmsPagesAction $list)
    {
        $items = $list->execute([
            'q'=>$request->get('q'),
            'status'=>$request->get('status'),
            'sort'=>$request->get('sort'),
            'dir'=>$request->get('dir'),
        ], (int)$request->get('per_page', 20));
        if ($request->wantsJson()) return response()->json($items);
        return view('admin.cms_pages.index', compact('items'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) return response()->json(['message' => 'Provide page payload to store.']);
        return view('admin.cms_pages.create');
    }

    public function store(CmsPageRequest $request, CreateCmsPageAction $create)
    {
        $page = $create->execute(CmsPageDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($page, 201);
        return redirect()->route('admin.cms-pages.index')->with('status', 'Page created');
    }

    public function show(Request $request, CmsPage $cms_page, ShowCmsPageAction $show)
    {
        $cms_page = $show->execute($cms_page);
        if ($request->wantsJson()) return response()->json($cms_page);
        return view('admin.cms_pages.edit', compact('cms_page'));
    }

    public function edit(Request $request, CmsPage $cms_page, ShowCmsPageAction $show)
    {
        $cms_page = $show->execute($cms_page);
        if ($request->wantsJson()) return response()->json($cms_page);
        return view('admin.cms_pages.edit', compact('cms_page'));
    }

    public function update(CmsPageRequest $request, CmsPage $cms_page, UpdateCmsPageAction $update)
    {
        $item = $update->execute($cms_page, CmsPageDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($item);
        return redirect()->route('admin.cms-pages.index')->with('status', 'Page updated');
    }

    public function destroy(Request $request, CmsPage $cms_page, DeleteCmsPageAction $delete)
    {
        $delete->execute($cms_page);
        if ($request->wantsJson()) return response()->json(null, 204);
        return redirect()->route('admin.cms-pages.index')->with('status', 'Page deleted');
    }
}


