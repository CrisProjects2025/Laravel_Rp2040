import machine
import dht
import time

class DHT22_Sensor:
    def __init__(self, pin_number):
        #Initialize DHT22 on pin 0
        self.pin = machine.Pin(pin_number)
        self.sensor = dht.DHT22(self.pin)

    def read(self):
        #Read temperature and humidity. Returns (temperature, humidity) tuple
        try:
            self.sensor.measure()
            temp = self.sensor.temperature()  # °C
            hum = self.sensor.humidity()      # %
            return temp, hum
        except Exception as e:
            print("DHT22 read error:", e)
            return None, None

    def read_and_print(self):
        #Read and print values
        temp, hum = self.read()
        if temp is not None and hum is not None:
            print("Temperature: {:.1f} °C | Humidity: {:.1f} %".format(temp, hum))
        else:
            print("Failed to read DHT22 sensor")
