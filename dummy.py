import requests
import random
import time

# URL API Laravel
API_URL = "https://app.iot-project-1.my.id/api/sensor"
# API_URL = "http://103.247.11.50//api/sensor"
IOT_TOKEN = "EgazblFsSTOTlezzLP4kQGaoEl6km2BFfVg4Kvf7Uk"

HEADERS = {
    "X-IOT-TOKEN": IOT_TOKEN,
    "Content-Type": "application/json",
    "Accept": "application/json"
}

def generate_sensor_data():
    """
    Generate random sensor and actuator data:
    - temperature: 25-40 °C
    - air_humidity: 50-90 %
    - soil_moisture: 20-80 %
    - fan_pwm: 0-255
    - pump_pwm: 0-255
    - uv_lamp: ON / OFF
    """
    return {
        "temperature": round(random.uniform(25.0, 40.0), 2),
        "air_humidity": round(random.uniform(50.0, 90.0), 2),
        "soil_moisture": round(random.uniform(20.0, 80.0), 2),
        "fan_pwm": random.randint(0, 255),
        "pump_pwm": random.randint(0, 255),
        "uv_lamp": random.choice(["Redup", "Terang"])
    }

def send_data(payload):
    """Send data to Laravel API"""
    try:
        response = requests.post(API_URL, json=payload, headers=HEADERS, timeout=5)
        if response.status_code in (200, 201):
            print("✅ Sent:", payload)
        else:
            print("❌ Error:", response.status_code, response.text)
    except requests.exceptions.RequestException as e:
        print("⚠️ Connection error:", e)

if __name__ == "__main__":
    while True:
        payload = generate_sensor_data()
        send_data(payload)
        time.sleep(2)  # kirim tiap 2 detik
