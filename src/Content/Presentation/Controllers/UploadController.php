<?php
namespace Src\Content\Presentation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function storeWysiwygImage(Request $request)
    {
        $request->validate([
            'image' => ['required','image','mimes:jpg,jpeg,png,gif,webp','max:5120'], // 5MB
        ]);

        $file = $request->file('image');
        $path = $file->store('public/wysiwyg/'.now()->format('Y/m'));
        $url = Storage::url($path);

        return response()->json(['url' => $url]);
    }
}


