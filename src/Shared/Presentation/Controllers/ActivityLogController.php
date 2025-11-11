<?php

namespace Src\Shared\Presentation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Activitylog\Models\Activity;

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
    public function index(Request $request)
    {
        $query = Activity::query()
            ->when($request->get('log_name'), fn ($q, $n) => $q->where('log_name', $n))
            ->when($request->get('causer_id'), fn ($q, $id) => $q->where('causer_id', $id))
            ->when($request->get('subject_type'), fn ($q, $t) => $q->where('subject_type', $t))
            ->orderByDesc('id');
        $items = $query->paginate((int) $request->get('per_page', 20));
        if ($request->wantsJson()) {
            return response()->json($items);
        }

        return view('admin.activity.index', compact('items'));
    }
}
