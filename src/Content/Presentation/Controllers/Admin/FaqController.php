<?php

namespace Src\Content\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Content\Application\Actions\CreateFaqAction;
use Src\Content\Application\Actions\DeleteFaqAction;
use Src\Content\Application\Actions\ListFaqsAction;
use Src\Content\Application\Actions\ShowFaqAction;
use Src\Content\Application\Actions\UpdateFaqAction;
use Src\Content\Application\DTOs\FaqDTO;
use Src\Content\Domain\Entities\Faq;
use Src\Content\Presentation\Requests\FaqRequest;

/**
 * FaqController controller.
 */
class FaqController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Faq::class, 'faq');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListFaqsAction $list)
    {
        $items = $list->execute([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ], (int) $request->get('per_page', 20));
        if ($request->wantsJson()) {
            return response()->json($items);
        }

        return view('admin.faqs.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Provide FAQ payload to store.']);
        }

        return view('admin.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FaqRequest $request, CreateFaqAction $create)
    {
        $faq = $create->execute(FaqDTO::fromArray($request->validated()));
        if ($request->wantsJson()) {
            return response()->json($faq, 201);
        }

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Faq $faq, ShowFaqAction $show)
    {
        $faq = $show->execute($faq);
        if ($request->wantsJson()) {
            return response()->json($faq);
        }

        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FaqRequest $request, Faq $faq, UpdateFaqAction $update)
    {
        $item = $update->execute($faq, FaqDTO::fromArray($request->validated()));
        if ($request->wantsJson()) {
            return response()->json($item);
        }

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Faq $faq, DeleteFaqAction $delete)
    {
        $delete->execute($faq);
        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ deleted');
    }
}
