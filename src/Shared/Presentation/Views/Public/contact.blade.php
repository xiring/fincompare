<x-guest-layout>
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-700 via-indigo-600 to-indigo-500 text-white">
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-extrabold tracking-tight">Contact Us</h1>
            <p class="mt-2 text-white/90">We’d love to hear from you.</p>
        </div>
    </section>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white border rounded-2xl p-6">
            <h2 class="font-semibold mb-3">Send a message</h2>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea rows="4" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <button type="button" class="px-5 py-2 rounded-md bg-indigo-600 text-white">Submit</button>
            </form>
        </div>
        <div class="space-y-4">
            <div class="p-6 bg-white border rounded-2xl">
                <h3 class="font-semibold">Email</h3>
                <p class="text-sm text-gray-700 mt-1"><a href="mailto:hello@example.com" class="text-indigo-700 hover:underline">hello@example.com</a></p>
            </div>
            <div class="p-6 bg-white border rounded-2xl">
                <h3 class="font-semibold">Office</h3>
                <p class="text-sm text-gray-700 mt-1">123 Market Street, Suite 100, City</p>
            </div>
        </div>
    </div>
</x-guest-layout>


