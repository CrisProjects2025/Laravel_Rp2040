<x-app-layout>
    <div x-data="{ shadeStatus: 'unknown' }" class="p-6">
        <h2 class="text-xl font-bold mb-4">🧢 Sunblock Net</h2>

        <div class="mb-4">
            <button @click="fetch('/api/sunblock-data')
                .then(res => res.json())
                .then(data => shadeStatus = data.shade)"
                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                Refresh Status
            </button>
        </div>

        <p class="text-lg">Shade Status: <span class="font-semibold" x-text="shadeStatus"></span></p>
    </div>
</x-app-layout>
