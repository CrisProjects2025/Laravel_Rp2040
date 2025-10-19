<x-app-layout>
    <div class="container mx-auto p-6 bg-white/10 rounded shadow text-center">
        <img src="{{ asset('images/Bara_logo.svg') }}"
     alt="Logo BARATRONICS"
     class="mx-auto w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/6 mb-4">

        <h1 class="text-2xl font-bold text-blue-600 mb-4">Baratronics Solar Control</h1>
        {{ dump($doorControls) }}

        <div class="space-y-2 text-white">
            <div>Last Update: <span>{{ $doorControls}}</span></div>
            <div>Light: <span id="light">5</span></div>
            <div>Status: <span id="status">—</span></div>
            <div>Mode: <span id="mode">—</span></div>
            <div>Temperature: <span id="temperature">444</span></div>
            <div>Humidity: <span id="humidity">—</span></div>
            <div>Last Update: <span id="last-update">—</span></div>
    


        </div>

        <hr class="my-4">

        <div class="flex justify-center gap-4">
            <button id="activateBtn" class="activate px-4 py-2 bg-green-600 text-white rounded" onclick="sendCommand('Activate')" disabled>⚡ Activate</button>
            <button id="deactivateBtn" class="deactivate px-4 py-2 bg-red-600 text-white rounded" onclick="sendCommand('Deactivate')" disabled>🛑 Deactivate</button>
        </div>
    </div>

    @vite(['resources/js/door-control.js'])
</x-app-layout>



<script>
function fetchTelemetry() {
    fetch('/door-control-data')
        .then(response => response.json())
        .then(data => {
            document.getElementById('light').textContent = data.Light;
            document.getElementById('mode').textContent = data.Mode;
            document.getElementById('status').textContent = data.Status;
            document.getElementById('temperature').textContent = data.Temperature;
            document.getElementById('humidity').textContent = data.Humidity;
            document.getElementById('last-update').textContent = data.LastUpdate;
        })
        .catch(error => console.error('Error fetching telemetry:', error));
}

// Refresh every 5 seconds
setInterval(fetchTelemetry, 5000);
fetchTelemetry(); // Initial load
</script>


