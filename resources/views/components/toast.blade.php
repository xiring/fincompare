@php
$success = session('status') ?? session('success');
$error = session('error');
$info = session('info');
$firstError = null;
if (session('errors') && session('errors')->any()) {
	$firstError = session('errors')->first();
}
$message = $success ?? $error ?? $info ?? $firstError;
$type = $success ? 'success' : ($error || $firstError ? 'error' : ($info ? 'info' : null));
@endphp

@if ($message && $type)
<div
	id="toast-root"
	class="fixed z-50 top-4 right-4"
>
	<div
		id="toast"
		class="max-w-sm w-full shadow-lg rounded-md border p-4 pr-10 relative transition transform duration-200 ease-out
			{{ $type==='success' ? 'bg-green-50 border-green-200 text-green-800' : '' }}
			{{ $type==='error' ? 'bg-red-50 border-red-200 text-red-800' : '' }}
			{{ $type==='info' ? 'bg-blue-50 border-blue-200 text-blue-800' : '' }}"
		style="opacity:0; transform: translateY(-8px);"
	>
		<strong class="block mb-1 text-sm">
			{{ $type==='success' ? 'Success' : ($type==='error' ? 'Error' : 'Info') }}
		</strong>
		<div class="text-sm">{{ $message }}</div>
		<button type="button" aria-label="Close"
			class="absolute top-2 right-2 text-current/60 hover:text-current"
			onclick="(function(){const t=document.getElementById('toast');if(!t)return;t.style.opacity='0';t.style.transform='translateY(-8px)';setTimeout(()=>{const r=document.getElementById('toast-root');if(r) r.remove();},200);})()">
			&times;
		</button>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	var el = document.getElementById('toast');
	if (!el) return;
	requestAnimationFrame(function(){
		el.style.opacity = '1';
		el.style.transform = 'translateY(0)';
	});
	setTimeout(function(){
		if (!el) return;
		el.style.opacity = '0';
		el.style.transform = 'translateY(-8px)';
		setTimeout(function(){ var root = document.getElementById('toast-root'); if (root) root.remove(); }, 250);
	}, 4000);
});
</script>
@endif


