<?php

namespace Src\Leads\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Src\Leads\Application\Actions\ExportLeadsCsvAction;
use Src\Leads\Application\Actions\ListLeadsAction;
use Src\Leads\Application\Actions\ShowLeadAction;
use Src\Leads\Application\Actions\UpdateLeadAction;
use Src\Leads\Application\DTOs\LeadDTO;
use Src\Leads\Domain\Entities\Lead;
use Src\Leads\Presentation\Requests\LeadUpdateRequest;

/**
 * LeadController controller.
 */
class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListLeadsAction $list)
    {
        $criteria = \Src\Shared\Application\Criteria\ListCriteria::fromArray([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
            'per_page' => $request->get('per_page', 20),
            'filters' => [
                'status' => $request->get('status'),
            ],
        ]);

        $items = $list->execute($criteria);

        return response()->json($items);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Lead $lead, ShowLeadAction $show)
    {
        $lead = $show->execute($lead);

        return response()->json($lead);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LeadUpdateRequest $request, Lead $lead, UpdateLeadAction $update)
    {
        $lead = $update->execute($lead, LeadDTO::fromArray($request->validated()));

        return response()->json($lead);
    }

    /**
     * Handle Export csv.
     *
     * @return mixed
     */
    public function exportCsv(Request $request, ExportLeadsCsvAction $export)
    {
        $filename = 'leads_export_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($request, $export) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Category', 'Product', 'Full Name', 'Email', 'Mobile', 'Status', 'Source', 'Created At']);

            $export->execute(['status' => $request->get('status')], 500, function ($lead) use ($handle) {
                fputcsv($handle, [
                    $lead->id,
                    optional($lead->productCategory)->name,
                    optional($lead->product)->name,
                    $lead->full_name,
                    $lead->email,
                    $lead->mobile_number,
                    $lead->status,
                    $lead->source,
                    $lead->created_at,
                ]);
            });
            fclose($handle);
        };

        return ResponseFacade::stream($callback, 200, $headers);
    }
}
