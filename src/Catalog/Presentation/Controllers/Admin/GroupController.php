<?php

namespace Src\Catalog\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Application\Actions\CreateGroupAction;
use Src\Catalog\Application\Actions\DeleteGroupAction;
use Src\Catalog\Application\Actions\ListGroupsAction;
use Src\Catalog\Application\Actions\ShowGroupAction;
use Src\Catalog\Application\Actions\UpdateGroupAction;
use Src\Catalog\Application\DTOs\GroupDTO;
use Src\Catalog\Domain\Entities\Group;
use Src\Catalog\Presentation\Requests\GroupRequest;

/**
 * GroupController controller.
 */
class GroupController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Group::class, 'group');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ListGroupsAction $list)
    {
        $items = $list->execute([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ], (int) $request->get('per_page', 20));

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Provide group payload to store.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request, CreateGroupAction $create)
    {
        $group = $create->execute(GroupDTO::fromArray($request->validated()));

        return response()->json($group, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, ShowGroupAction $show)
    {
        $group = $show->execute($group);

        return response()->json($group);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group, ShowGroupAction $show)
    {
        $group = $show->execute($group);

        return response()->json($group);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupRequest $request, Group $group, UpdateGroupAction $update)
    {
        $group = $update->execute($group, GroupDTO::fromArray($request->validated()));

        return response()->json($group);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, DeleteGroupAction $delete)
    {
        $delete->execute($group);

        return response()->json(null, 204);
    }
}


