<?php
namespace Src\Leads\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Src\Leads\Presentation\Requests\LeadUpdateRequest;
use Src\Leads\Application\Actions\ListLeadsAction;
use Src\Leads\Application\Actions\ShowLeadAction;
use Src\Leads\Application\Actions\UpdateLeadAction;
use Src\Leads\Application\Actions\ExportLeadsCsvAction;
use Src\Leads\Application\DTOs\LeadDTO;
use Src\Leads\Domain\Entities\Lead;

class LeadController extends Controller
{
    public function index(Request $request, ListLeadsAction $list)
    {
        $items = $list->execute([
            'q'=>$request->get('q'),
            'status'=>$request->get('status'),
            'sort'=>$request->get('sort'),
            'dir'=>$request->get('dir')
        ], (int)$request->get('per_page', 20));
        if ($request->wantsJson()) return response()->json($items);
        return view('admin.leads.index', compact('items'));
    }

    public function show(Request $request, Lead $lead, ShowLeadAction $show)
    {
        $lead = $show->execute($lead);
        if ($request->wantsJson()) return response()->json($lead);
        return view('admin.leads.show', compact('lead'));
    }

    public function update(LeadUpdateRequest $request, Lead $lead, UpdateLeadAction $update)
    {
        $lead = $update->execute($lead, LeadDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($lead);
        return redirect()->route('admin.leads.show', $lead)->with('status', 'Lead updated');
    }

    public function exportCsv(Request $request, ExportLeadsCsvAction $export)
    {
        $filename = 'leads_export_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($request, $export) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID','Category','Product','Full Name','Email','Mobile','Status','Source','Created At']);

            $export->execute(['status'=>$request->get('status')], 500, function ($lead) use ($handle) {
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


