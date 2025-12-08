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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductImportRequest $request)
    {
        $file = $request->file('file');
        $path = $file->storeAs('imports/products', now()->format('Ymd_His').'_'.$file->getClientOriginalName(), 'local');

        $delimiter = (string) $request->input('delimiter', ',');
        if ($delimiter === '\\t' || $delimiter === '\t') {
            $delimiter = "\t"; // normalize to actual tab character

        ImportProductsJob::dispatch(
            storage_path('app/'.$path),
            $delimiter,
            (bool) $request->boolean('has_header', true)
        )->onQueue('imports');

}
