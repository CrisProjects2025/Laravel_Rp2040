<x-app-layout>
    <div class="container mx-auto p-6 bg-white/10 rounded shadow text-center">
        <img src="{{ asset('images/Bara_logo.svg') }}"
     alt="Logo BARATRONICS"
     class="mx-auto w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/6 mb-4">

        <h1 class="text-2xl font-bold text-blue-600 mb-4">Baratronics Solar Control</h1>

        <div class="space-y-2 text-white">
            <div><strong>Light:</strong> <span id="light">--</span></div>
            <div><strong>Status:</strong> <span id="status">--</span></div>
            <div><strong>Mode:</strong> <span id="mode">--</span></div>
            <div><strong>Temperature:</strong> <span id="temp">--</span></div>
            <div><strong>Humidity:</strong> <span id="hum">--</span></div>
            <div><strong>Last Update:</strong> <span id="time">--</span></div>
        </div>

        <hr class="my-4">

        <div class="flex justify-center gap-4">
            <button id="activateBtn" class="activate px-4 py-2 bg-green-600 text-white rounded" onclick="sendCommand('Activate')" disabled>⚡ Activate</button>
            <button id="deactivateBtn" class="deactivate px-4 py-2 bg-red-600 text-white rounded" onclick="sendCommand('Deactivate')" disabled>🛑 Deactivate</button>
        </div>
    </div>

    @vite(['resources/js/door-control.js'])
</x-app-layout>
