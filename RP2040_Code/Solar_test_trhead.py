import network
from wifi_con import connect
import urequests
from machine import Pin, ADC
import time
import _thread
from Motor_Solar_Control import Motor_Controller
import DHT22_modV2
import ujson

# Wi-Fi credentials

SSID = "Infinix HOT 30i"
PASS = "iemibsa2023"
URL = 'https://tan-worm-536465.hostingersite.com/SolarControl.php'

#URL = "http://192.168.1.142:8000/door-control"
# Command endpoint
COMMAND_URL = "https://tan-worm-536465.hostingersite.com/rp2040_command.json"# Connect to Wi-Fi
wlan = network.WLAN(network.STA_IF)
wlan.active(True)
wlan.connect(SSID, PASS)
while not wlan.isconnected():
    time.sleep(1)
print("Connected:", wlan.ifconfig())

# Pin setup
adc = ADC(26)
dht_sensor = DHT22_modV2.DHT22_Sensor(0)

Open_sensor = Pin(16, Pin.IN, Pin.PULL_UP)
Close_sensor = Pin(17, Pin.IN, Pin.PULL_UP)
Manual = Pin(18, Pin.IN, Pin.PULL_UP)
Automatic = Pin(19, Pin.IN, Pin.PULL_UP)
Open_Button = Pin(12, Pin.IN, Pin.PULL_UP)
Close_Button = Pin(13, Pin.IN, Pin.PULL_UP)
M_open = Pin(14, Pin.OUT)
M_close = Pin(15, Pin.OUT)

M_1 = Motor_Controller(M_open, Open_Button, Open_sensor)
M_2 = Motor_Controller(M_close, Close_Button, Close_sensor)

# Shared data
shared_data = {
    "lux": 0,
    "status": "",
    "mode": "",
    "temp": None,
    "hum": None
}
lock = _thread.allocate_lock()

# Helper functions
def read_light():
    raw = adc.read_u16()
    return int(raw * 100 / 65535)

def sensor_status():
    if M_open.value() == 1:
        return "System is opening"
    elif M_close.value() == 1:
        return "System is closing"
    elif Open_sensor.value() == 0 and Close_sensor.value() == 1:
        return "The system is Open"
    elif Close_sensor.value() == 0 and Open_sensor.value() == 1:
        return "The system is Close"
    elif Open_sensor.value() == 0 and Close_sensor.value() == 0:
        return "The System is stuck"
    else:
        return "System is idle or transitioning"

def operation_mode():
    if Manual.value() == 0 and Automatic.value() == 1:
        return "System is in manual mode"
    elif Manual.value() == 1 and Automatic.value() == 0:
        return "System is in Automatic Mode"
    elif Manual.value() == 1 and Automatic.value() == 1:
        return "System is OFF"
    else:
        return "Check connection of selector"

def send_data():
    with lock:
        data = {
            "Light": shared_data["lux"],
            "Open or Close": shared_data["status"],
            "Mode": shared_data["mode"],
            "Temperature": shared_data["temp"],
            "Humidity": shared_data["hum"]
        }
    try:
        response = urequests.post(URL, json=data)
        print("Server response:", response.text)
        response.close()
    except Exception as e:
        print("Error sending data:", e)

# Core 0: Sensor reading and motor control
def core0_main():
    while True:
        lux = read_light()
        temp, hum = dht_sensor.read()
        mode = operation_mode()

        with lock:
            shared_data["lux"] = lux
            shared_data["temp"] = temp
            shared_data["hum"] = hum
            shared_data["mode"] = mode
            shared_data["status"] = sensor_status()

        if mode == "System is in manual mode":
            M_1.update()
            M_2.update()

        elif mode == "System is in Automatic Mode" and hum is not None:
            if hum > 90 and lux < 80:
                if Close_sensor.value():
                    M_1.on()
                else:
                    M_1.off()
                M_2.off()
            elif hum < 60 and lux > 95:
                if Open_sensor.value():
                    M_2.on()
                else:
                    M_2.off()
                M_1.off()
            else:
                M_1.off()
                M_2.off()
        else:
            M_1.off()
            M_2.off()

        time.sleep(0.5)

# Core 1 comb: Send telemetry
def core1_combined():
    send_timer = 0
    command_timer = 0

    while True:
        current_time = time.ticks_ms()

        # Send telemetry every 2 seconds
        if time.ticks_diff(current_time, send_timer) > 2000:
            send_data()
            send_timer = current_time

        # Poll command every 2 seconds if in manual mode
        with lock:
            mode = shared_data["mode"]

        if mode == "System is in manual mode" and time.ticks_diff(current_time, command_timer) > 2000:
            try:
                response = urequests.get(COMMAND_URL)
                raw = response.content
                decoded = raw.decode('utf-8')

                # Defensive check
                if decoded:
                    command_data = ujson.loads(decoded)
                    command = command_data.get("command", "").lower()
                    print("Received command:", command)

                    if command == "activate":
                        M_1.on()
                        M_2.off()
                    elif command == "deactivate":
                        M_2.on()
                        M_1.off()
                    else:
                        M_1.off()
                        M_2.off()
                else:
                    print("Empty response from server")

                response.close()
            except Exception as e:
                print("Error fetching command:", e)


            command_timer = current_time

        time.sleep(0.1)


# Start threads
_thread.start_new_thread(core1_combined, ())
core0_main()

