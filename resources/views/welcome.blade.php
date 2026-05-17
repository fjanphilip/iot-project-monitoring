<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>IoT Sensor Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background: #0a0e27;
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.1) 0px, transparent 50%);
        }

        .fan-spinning {
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .lamp-on {
            animation: lampGlow 2s ease-in-out infinite alternate;
        }

        @keyframes lampGlow {
            from {
                filter: drop-shadow(0 0 8px #fbbf24) drop-shadow(0 0 12px #fbbf24);
            }

            to {
                filter: drop-shadow(0 0 16px #fbbf24) drop-shadow(0 0 24px #fbbf24);
            }
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.6;
                transform: scale(1.1);
            }
        }

        .pulse-dot {
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes valueChange {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.15);
                color: #60a5fa;
            }

            100% {
                transform: scale(1);
            }
        }

        .value-changed {
            animation: valueChange 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes cardFlash {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.3);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .card-flash {
            animation: cardFlash 0.6s ease-in-out;
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>

<body class="min-h-screen py-6 px-4">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-4xl font-bold gradient-text mb-2">IoT Sensor Dashboard</h1>
                <p class="text-gray-400 text-sm flex items-center gap-2">
                    <i class="fas fa-chart-line"></i>
                    Real-time monitoring and control system
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="flex items-center gap-2 bg-gradient-to-r from-green-500/20 to-emerald-500/20 px-4 py-2.5 rounded-xl border border-green-500/30">
                    <div class="w-2.5 h-2.5 bg-green-500 rounded-full pulse-dot"></div>
                    <span class="text-green-400 text-sm font-semibold">LIVE</span>
                </div>
                <a href="/sensor/export"
                    class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300">
                    <i class="fas fa-file-excel"></i>
                    <span class="text-sm font-semibold">Export</span>
                </a>
            </div>
        </div>

        <!-- Device Control -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-sliders text-blue-400"></i>
                Device Control
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Fan -->
                <div
                    class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/50 shadow-xl card-hover">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300"
                                id="fan-container" style="background: rgba(71,85,105,0.4);">
                                <i class="fas fa-fan text-2xl text-gray-500 transition-all duration-300"
                                    id="fan-icon"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Exhaust Fan</p>
                                <p class="text-xl font-bold mt-0.5 text-white" id="fan-status">0</p>
                                <span class="text-xs text-gray-400">PWM</span>
                                <div class="h-1 bg-slate-700/50 rounded-full overflow-hidden mt-1">
                                    <div id="fan-bar"
                                        class="h-full bg-gradient-to-r from-emerald-500 to-green-500 rounded-full transition-all duration-500"
                                        style="width:0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pump -->
                <div
                    class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/50 shadow-xl card-hover">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300"
                                id="pump-container" style="background: rgba(71,85,105,0.4);">
                                <i class="fas fa-water text-2xl text-gray-500 transition-all duration-300"
                                    id="pump-icon"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Water Pump</p>
                                <p class="text-xl font-bold mt-0.5 text-white" id="pump-status">0</p>
                                <span class="text-xs text-gray-400">PWM</span>
                                <div class="h-1 bg-slate-700/50 rounded-full overflow-hidden mt-1">
                                    <div id="pump-bar"
                                        class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full transition-all duration-500"
                                        style="width:0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UV Lamp -->
                <div
                    class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/50 shadow-xl card-hover">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300"
                                id="lamp-container" style="background: rgba(71,85,105,0.4);">
                                <i class="far fa-lightbulb text-2xl text-gray-500 transition-all duration-300"
                                    id="lamp-icon"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">UV Lamp</p>
                                <p class="text-xl font-bold mt-0.5 text-white" id="lamp-status">OFF</p>
                                <span class="text-xs text-gray-400">PWM</span>
                                <div class="h-1 bg-slate-700/50 rounded-full overflow-hidden mt-1">
                                    <div id="lamp-bar"
                                        class="h-full bg-gradient-to-r from-amber-500 to-yellow-500 rounded-full transition-all duration-500"
                                        style="width:0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sensor Readings -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-purple-400"></i>
                Sensor Readings
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Temperature -->
                <div
                    class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/50 shadow-xl card-hover">
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="w-14 h-14 rounded-xl flex items-center justify-center bg-gradient-to-br from-red-500/20 to-orange-500/20 border border-red-500/30">
                            <i class="fas fa-temperature-three-quarters text-2xl text-red-400"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider mb-1">Temperature</p>
                            <div class="flex items-baseline justify-end gap-1">
                                <p class="text-4xl font-bold text-white" id="temperature">-</p>
                                <span class="text-red-400 text-lg font-semibold">°C</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <i class="fas fa-circle text-[6px]"></i>
                        <span>SHT2s Reading</span>
                    </div>
                </div>

                <!-- Air Humidity -->
                <div
                    class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/50 shadow-xl card-hover">
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="w-14 h-14 rounded-xl flex items-center justify-center bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30">
                            <i class="fas fa-droplet text-2xl text-blue-400"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider mb-1">Air Humidity</p>
                            <div class="flex items-baseline justify-end gap-1">
                                <p class="text-4xl font-bold text-white" id="humidity">-</p>
                                <span class="text-blue-400 text-lg font-semibold">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <i class="fas fa-circle text-[6px]"></i>
                        <span>Air Humidity Level</span>
                    </div>
                </div>

                <!-- Soil Moisture -->
                <div
                    class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/50 shadow-xl card-hover">
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="w-14 h-14 rounded-xl flex items-center justify-center bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/30">
                            <img src="{{ asset('assets/soil_sensor.png') }}" alt="Soil Sensor"
                                class="w-8 h-8 filter invert sepia saturate-[500%] hue-rotate-[260deg]">
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider mb-1">Soil Moisture
                            </p>
                            <div class="flex items-baseline justify-end gap-1">
                                <p class="text-4xl font-bold text-white" id="soil_moisture">-</p>
                                <span class="text-purple-400 text-lg font-semibold">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <i class="fas fa-circle text-[6px]"></i>
                        <span>Soil Moisture Level</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div
            class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-xl rounded-2xl p-5 border border-slate-700/30 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 text-sm">
                <div class="flex items-center gap-3 text-gray-400">
                    <i class="far fa-clock text-lg"></i>
                    <span>Last synchronized: <span id="time" class="text-gray-300 font-medium">-</span></span>
                </div>
                <div class="flex items-center gap-4 text-gray-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-microchip"></i>
                        <span>IoT System</span>
                    </div>
                    <div class="h-4 w-px bg-gray-700"></div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-code-branch"></i>
                        <span>v1.0.0</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- JS -->
    <script>
        const API_URL = "/api/sensor/latest";

        let previousData = {
            temperature: null,
            air_humidity: null,
            soil_moisture: null,
            fan_pwm: null,
            pump_pwm: null,
            uv_lamp: null
        };

        function animateValueChange(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove("value-changed");
            void el.offsetWidth;
            el.classList.add("value-changed");
            setTimeout(() => el.classList.remove("value-changed"), 500);
        }

        function updateFan(fanPWM, changed) {
            const container = document.getElementById("fan-container");
            const icon = document.getElementById("fan-icon");
            const text = document.getElementById("fan-status");
            const bar = document.getElementById("fan-bar");

            text.textContent = fanPWM;
            bar.style.width = (fanPWM / 255 * 100) + "%";

            if (fanPWM > 0) {
                icon.className = "fas fa-fan text-2xl text-green-400 fan-spinning";
                container.style.background = "rgba(16,185,129,0.25)";
            } else {
                icon.className = "fas fa-fan text-2xl text-gray-500";
                container.style.background = "rgba(71,85,105,0.4)";
            }

            if (changed) animateValueChange("fan-status");
        }

        function updatePump(pumpPWM, changed) {
            const container = document.getElementById("pump-container");
            const icon = document.getElementById("pump-icon");
            const text = document.getElementById("pump-status");
            const bar = document.getElementById("pump-bar");

            text.textContent = pumpPWM;
            bar.style.width = (pumpPWM / 255 * 100) + "%";

            if (pumpPWM > 0) {
                icon.className = "fas fa-water text-2xl text-blue-400";
                container.style.background = "rgba(59,130,246,0.25)";
            } else {
                icon.className = "fas fa-water text-2xl text-gray-500";
                container.style.background = "rgba(71,85,105,0.4)";
            }

            if (changed) animateValueChange("pump-status");
        }

        function updateLamp(mode, changed) {
            const container = document.getElementById("lamp-container");
            const icon = document.getElementById("lamp-icon");
            const text = document.getElementById("lamp-status");
            const bar = document.getElementById("lamp-bar");

            text.textContent = mode.toUpperCase();
            bar.style.width = mode === "OFF" ? "0%" : "100%";

            if (mode.toUpperCase() === "ON" || mode.toUpperCase() === "TERANG") {
                icon.className = "far fa-lightbulb text-2xl text-amber-400 lamp-on";
                container.style.background = "rgba(251,191,24,0.25)";
            } else {
                icon.className = "far fa-lightbulb text-2xl text-gray-500";
                container.style.background = "rgba(71,85,105,0.4)";
            }

            if (changed) animateValueChange("lamp-status");
        }

        function updateSensors(temp, hum, soil) {
            if (previousData.temperature !== temp) {
                document.getElementById("temperature").textContent = temp;
                animateValueChange("temperature");
                previousData.temperature = temp;
            }
            if (previousData.air_humidity !== hum) {
                document.getElementById("humidity").textContent = hum;
                animateValueChange("humidity");
                previousData.air_humidity = hum;
            }
            if (previousData.soil_moisture !== soil) {
                document.getElementById("soil_moisture").textContent = soil;
                animateValueChange("soil_moisture");
                previousData.soil_moisture = soil;
            }
        }

        async function fetchData() {
            try {
                const res = await fetch(API_URL);
                const json = await res.json();
                const data = json.data || {};

                // Update actuator
                if (previousData.fan_pwm !== data.fan_pwm) {
                    updateFan(data.fan_pwm, true);
                    previousData.fan_pwm = data.fan_pwm;
                } else {
                    updateFan(data.fan_pwm, false);
                }

                if (previousData.pump_pwm !== data.pump_pwm) {
                    updatePump(data.pump_pwm, true);
                    previousData.pump_pwm = data.pump_pwm;
                } else {
                    updatePump(data.pump_pwm, false);
                }

                if (previousData.uv_lamp !== data.uv_lamp) {
                    updateLamp(data.uv_lamp || "OFF", true);
                    previousData.uv_lamp = data.uv_lamp || "OFF";
                } else {
                    updateLamp(data.uv_lamp || "OFF", false);
                }

                // Update sensors
                updateSensors(data.temperature || "-", data.air_humidity || "-", data.soil_moisture || "-");

                // --- PERBAIKAN LOGIKA WAKTU DATABASE ---
                if (data && data.created_at) {
                    const dbTime = new Date(data.created_at);

                    // Memastikan hasil konversi Date valid (bukan Invalid Date)
                    if (!isNaN(dbTime.getTime())) {
                        document.getElementById("time").textContent = dbTime.toLocaleString('id-ID', {
                            dateStyle: 'medium',
                            timeStyle: 'medium'
                        });
                    } else {
                        document.getElementById("time").textContent = "Format Waktu Salah";
                    }
                } else {
                    // Jika mikrokontroler mati dan API tidak mengirim properti created_at
                    document.getElementById("time").textContent = "Sensor Offline / No Data";
                }

            } catch (err) {
                console.error("Failed to fetch data:", err);
            }
        }

        setInterval(fetchData, 2000);
        fetchData();
    </script>
</body>

</html>
