@if (!request()->cookies->has('cookie_consent'))
<div
	x-data="{ open: true }"
	x-show="open"
	x-cloak
	class="fixed inset-x-0 bottom-0 z-50"
	style="pointer-events: none;"
>
	<div class="mx-auto max-w-7xl px-4 pb-4 sm:px-6 lg:px-8" style="pointer-events: auto;">
		<div class="rounded-lg bg-white shadow-lg ring-1 ring-black/5 p-4 sm:p-5 md:p-6 animate-fade-in-up">
			<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
				<p class="text-sm text-gray-700">
					We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic.
					See our
					<a href="{{ route('privacy') }}" class="font-medium text-[color:var(--brand-primary)] hover:text-[color:var(--brand-primary-2)] underline">
						Privacy Policy
					</a>
					for more details.
				</p>
				<div class="flex items-center gap-3 sm:justify-end">
					<button
						type="button"
						class="inline-flex items-center justify-center rounded-md bg-[color:var(--brand-primary)] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[color:var(--brand-primary-2)] focus:outline-none focus:ring-2 focus:ring-[color:var(--brand-primary)] focus:ring-offset-2"
						@click="
							document.cookie = 'cookie_consent=1; path=/; max-age=' + (60*60*24*365) + '; SameSite=Lax';
							open = false
						"
					>
						Accept
					</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		// Hide if cookie already set (client-side navigation edge cases)
		(function () {
			if (document.cookie.split('; ').some((c) => c.startsWith('cookie_consent='))) {
				const el = document.currentScript && document.currentScript.previousElementSibling
					? document.currentScript.previousElementSibling.parentElement
					: null;
				if (el) el.style.display = 'none';
			}
		})();
	</script>
</div>
@endif


