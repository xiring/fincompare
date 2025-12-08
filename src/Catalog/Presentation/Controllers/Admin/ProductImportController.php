<?php

namespace Src\Catalog\Presentation\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Src\Catalog\Application\Jobs\ImportProductsJob;
use Src\Catalog\Presentation\Requests\ProductImportRequest;

/**
 * ProductImportController controller.
 */
class ProductImportController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json(['message' => 'Use POST to upload CSV file for import.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductImportRequest $request)
    {
        $file = $request->file('file');
        $path = $file->storeAs('imports/products', now()->format('Ymd_His').'_'.$file->getClientOriginalName(), 'local');

        $delimiter = (string) $request->input('delimiter', ',');
        if ($delimiter === '\\t' || $delimiter === '\t') {
            $delimiter = "\t"; // normalize to actual tab character
        }

        ImportProductsJob::dispatch(
            storage_path('app/'.$path),
            $delimiter,
            (bool) $request->boolean('has_header', true)
        )->onQueue('imports');

        return response()->json([
            'message' => 'Import started successfully. Products will be imported in the background.',
            'file' => $file->getClientOriginalName()
        ], 202);
    }
}
