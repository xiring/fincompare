<?php

namespace Src\Shared\Presentation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Shared\Domain\Repositories\ActivityLogRepositoryInterface;

/**
 * ActivityLogController controller.
 */
class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ActivityLogRepositoryInterface $repository)
    {
        $items = $repository->paginate([
            'log_name' => $request->get('log_name'),
            'causer_id' => $request->get('causer_id'),
            'subject_type' => $request->get('subject_type'),
        ], (int) $request->get('per_page', 20));

        return response()->json($items);
    }
}
