@props([
    'name' => 'content',
    'id' => 'wysiwyg_'.Str::random(6),
    'value' => '',
    'height' => '300px',
])

<div class="space-y-2">
    <div id="{{ $id }}_editor" class="border rounded-md bg-white" style="min-height: {{ $height }};"></div>
    <textarea id="{{ $id }}" name="{{ $name }}" class="hidden">{!! old($name, $value) !!}</textarea>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>

@pushOnce('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet" />
    <style>
        .ql-editor { min-height: {{ $height }}; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@11.9.0/styles/default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
@endPushOnce

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-uploader@1.3.0/dist/quill.imageUploader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/highlight.js@11.9.0/lib/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
@endPushOnce

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    var hidden = document.getElementById(@json($id));
    var mount = document.getElementById(@json($id.'_editor'));
    if (!hidden || !mount) return;

    function initQuill(){
        const uploadUrl = @json(route('admin.uploads.wysiwyg'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (window.Quill) {
            const ImageResizeMod = (window.ImageResize && (window.ImageResize.default || window.ImageResize)) || null;
            if (ImageResizeMod) {
                window.Quill.register('modules/imageResize', ImageResizeMod);
            }
            const ImageUploaderMod = (window.ImageUploader && (window.ImageUploader.default || window.ImageUploader)) || window.QuillImageUploader || null;
            if (ImageUploaderMod) {
                window.Quill.register('modules/imageUploader', ImageUploaderMod);
            }
        }
        var q = new Quill(mount, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    [{ font: [] }],
                    [{ size: ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike', 'blockquote', 'code-block'],
                    [{ script: 'sub' }, { script: 'super' }],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ direction: 'rtl' }],
                    [{ color: [] }, { background: [] }],
                    [{ align: [] }],
                    ['link', 'image', 'video', 'formula'],
                    ['clean']
                ],
                imageResize: { modules: [ 'Resize', 'DisplaySize' ] },
                imageUploader: {
                    upload: file => new Promise((resolve, reject) => {
                        const form = new FormData();
                        form.append('image', file);
                        fetch(uploadUrl, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken },
                            body: form,
                            credentials: 'same-origin'
                        })
                        .then(r => r.ok ? r.json() : Promise.reject(new Error('Upload failed')))
                        .then(json => json && json.url ? resolve(json.url) : reject(new Error('Invalid response')))
                        .catch(err => reject(err));
                    })
                },
                history: { delay: 1000, maxStack: 500, userOnly: true },
                clipboard: { matchVisual: false },
                syntax: window.hljs ? true : false
            }
        });
        // Set initial HTML
        if (hidden.value) {
            q.clipboard.dangerouslyPasteHTML(hidden.value);
        }
        // Sync changes back to textarea
        q.on('text-change', function(){
            hidden.value = mount.querySelector('.ql-editor').innerHTML;
        });
    }

    if (window.Quill) { initQuill(); }
    else {
        var check = setInterval(function(){
            if (window.Quill) { clearInterval(check); initQuill(); }
        }, 50);
    }
});
</script>
@endpush


