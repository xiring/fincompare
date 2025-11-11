<?php
namespace Src\Content\Presentation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * UploadController controller.
 *
 * @package Src\Content\Presentation\Controllers
 */
class UploadController extends Controller
{
    /**
     * Handle Store wysiwyg image.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeWysiwygImage(\Src\Content\Presentation\Requests\WysiwygImageUploadRequest $request)
    {
        $request->validate([
            'image' => ['required','image','mimes:jpg,jpeg,png,gif,webp','max:5120'], // 5MB
        ]);

        $file = $request->file('image');
        $path = $file->store('wysiwyg/'.now()->format('Y/m'), 'public');
        $url = asset('storage/'.ltrim($path, '/'));

        return response()->json(['url' => $url]);
    }
}


