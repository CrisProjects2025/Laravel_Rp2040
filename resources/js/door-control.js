// resources/js/door-control.js
console.log("Door Control JS loaded");




function updateData() {
    fetch('/storage/rp2040_env_data.json')
        .then(response => response.json())
        .then(data => {
            document.getElementById('light').innerText = data.Light + " %";
            document.getElementById('status').innerText = data["Open or Close"];
            document.getElementById('mode').innerText = data.Mode;
            document.getElementById('temp').innerText = data.Temperature + " °C";
            document.getElementById('hum').innerText = data.Humidity + " %";
            document.getElementById('time').innerText = data.timestamp;

            const isManual = data.Mode.toLowerCase().includes("manual");
            document.getElementById('activateBtn').disabled = !isManual;
            document.getElementById('deactivateBtn').disabled = !isManual;
        });
}

function sendCommand(command) {
    fetch('/api/send-command', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ command })
    })
    .then(response => response.json())
    .then(data => alert("Command sent: " + data.command))
    .catch(() => alert("Error sending command"));
}


setInterval(updateData, 5000);
window.onload = updateData;
