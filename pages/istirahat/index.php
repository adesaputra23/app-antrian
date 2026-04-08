<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSUD Sumbawa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            zoom: 90%;
            overflow: hidden;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .main-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content-wrapper {
            width: 95vw;
            height: 95vh;
            max-height: 95vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <!-- Main Container -->
    <div class="main-container p-4">
        <div class="content-wrapper glass-effect rounded-3xl border border-gray-100 shadow-2xl p-6">

            <!-- Top Section with Hospital Name -->
            <div class="text-center mb-6">
                <div class="text-8xl mb-4 animate-pulse-slow">🏥</div>
                <h1 class="text-6xl font-bold text-gray-800 tracking-tight">
                    RSUD Sumbawa
                </h1>
            </div>

            <!-- Notice Box -->
            <div class="bg-yellow-50 rounded-2xl p-8 border-2 border-yellow-200 shadow-lg mb-6 flex-grow">
                <div class="grid grid-cols-2 gap-8 h-full">
                    <!-- Left Side - Status -->
                    <div class="text-center border-r-2 border-yellow-200 pr-8 flex flex-col justify-center">
                        <div class="text-7xl mb-6 animate-bounce">⏰</div>
                        <h2 class="text-6xl font-bold text-red-600 mb-6">
                            SEDANG ISTIRAHAT
                        </h2>
                        <p class="text-3xl text-yellow-800">
                            Mohon maaf, <br />saat ini kami sedang istirahat
                        </p>
                    </div>

                    <!-- Right Side - Time -->
                    <div class="flex flex-col justify-center items-center">
                        <p class="text-3xl text-yellow-800 mb-6">Kembali Beroperasi:</p>
                        <div class="text-9xl font-bold text-yellow-900 animate-pulse-slow">
                            14:00
                        </div>
                        <p class="text-3xl text-yellow-800 mt-4">WITA</p>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="grid grid-cols-2 gap-6 mb-4">
                <!-- IGD Information -->
                <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-green-500">
                    <div class="text-4xl mb-2">🏥</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">IGD 24 Jam:</h3>
                    <p class="text-2xl font-bold text-green-600">Tetap Buka</p>
                </div>

                <!-- Pharmacy Information -->
                <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-blue-500">
                    <div class="text-4xl mb-2">💊</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Apotek:</h3>
                    <p class="text-xl font-medium text-blue-600">Buka Kembali Sesuai Jadwal</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-500 text-xl">
                © 2024 Rumah Sakit Umum Daerah Sumbawa
            </div>
        </div>
    </div>

    <!-- Audio Element -->
    <audio id="notificationAudio" src="../../assets/audio/istirahat.mp3" preload="auto" muted></audio>

    <script>
        // Memutar audio saat halaman dimuat
        window.onload = function() {
            const audio = document.getElementById('notificationAudio');
            audio.muted = false; // Menghapus mute sebelum play

            // Menambahkan event listener untuk memutar audio
            document.body.addEventListener('click', function() {
                audio.play().catch(function(error) {
                    console.log("Audio tidak bisa diputar:", error);
                });
            });
        };
    </script>
</body>

</html>