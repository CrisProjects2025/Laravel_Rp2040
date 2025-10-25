class Motor_Controller:
    def __init__(self, Motor, button, sensor):
        # receive Pin objects  from Solar_Control.py
        self.Motor = Motor
        self.button = button
        self.sensor = sensor

    def update(self):
        """Check button/sensor and update Motor state"""
        if self.button.value() == 0:     # Button pressed
            self.Motor.value(1)            # Turn Motor ON
        if self.sensor.value() == 0:     # Sensor active (LOW)
            self.Motor.value(0)            # Turn Motor OFF

    def is_on(self):
        return self.Motor.value() == 1
        
    

    def off(self):
        self.Motor.value(0)

    def on(self):
        self.Motor.value(1)