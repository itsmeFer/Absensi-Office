<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-4">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-800" id="clock"></h2>
                    <p class="text-gray-600" id="date"></p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Absensi Hari Ini</h2>

                @php
                    $todayAttendance = Auth::user()->attendances()
                        ->whereDate('created_at', today())
                        ->first();
                @endphp

                <div class="grid grid-cols-2 gap-4">
                    {{-- Check In --}}
                    @if(!$todayAttendance)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <h3 class="text-lg font-medium text-blue-800 mb-3">Check In</h3>
                            <form method="POST" action="{{ route('check-in') }}" id="checkInForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="check_in_location" id="check_in_location">

                                <div class="mb-4">
                                    <label for="check_in_photo" class="block text-gray-700">Foto Check In</label>
                                    <input 
                                        type="file" 
                                        name="check_in_photo" 
                                        id="check_in_photo" 
                                        class="w-full p-2 border border-gray-300 rounded-md mt-2" 
                                        required
                                    >
                                </div>

                                <button 
                                    type="button" 
                                    id="checkInBtn"
                                    onclick="confirmCheckIn()"
                                    class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded transition-all duration-300 ease-in-out transform hover:scale-105 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
                                >
                                    Absensi Masuk
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Check Out --}}
                    @if($todayAttendance && !$todayAttendance->check_out)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <h3 class="text-lg font-medium text-red-800 mb-3">Check Out</h3>
                            <form method="POST" action="{{ route('check-out') }}" id="checkOutForm">
                                @csrf
                                <input type="hidden" name="check_out_location" id="check_out_location">
                                <button 
                                    type="button" 
                                    id="checkOutBtn"
                                    onclick="confirmCheckOut()"
                                    class="w-full bg-red-500 text-white font-bold py-2 px-4 rounded transition-all duration-300 ease-in-out transform hover:scale-105 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                                >
                                    Absen Pulang
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Informasi Terakhir Absen --}}
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Status Absensi Hari Ini</h3>
                    @if($todayAttendance)
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <strong>Check In:</strong> 
                                {{ $todayAttendance->check_in ? $todayAttendance->check_in->translatedFormat('l, d F Y - H:i:s') : 'Belum Check In' }}
                                <br>
                                <strong>Lokasi Check In:</strong> 
                                {{ $todayAttendance->check_in_location ?? 'Tidak Tercatat' }}
                            </div>
                            <div>
                                <strong>Check Out:</strong> 
                                {{ $todayAttendance->check_out ? $todayAttendance->check_out->translatedFormat('l, d F Y - H:i:s') : 'Belum Check Out' }}
                                <br>
                                <strong>Lokasi Check Out:</strong> 
                                {{ $todayAttendance->check_out_location ?? 'Tidak Tercatat' }}
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600">Anda Belum melakukan absensi hari ini!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan SweetAlert2 CSS dan JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmCheckIn() {
            // Cek apakah foto sudah diunggah
            const fileInput = document.getElementById('check_in_photo');
            if (!fileInput.files.length) {
                Swal.fire({
                    title: 'Error',
                    text: 'Foto Check In wajib diunggah!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            navigator.geolocation.getCurrentPosition((position) => {
                document.getElementById('check_in_location').value = 
                    `${position.coords.latitude}, ${position.coords.longitude}`;
                
                Swal.fire({
                    title: 'Konfirmasi Check In',
                    text: 'Apakah Anda yakin ingin melakukan check in sekarang?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Check In!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('checkInForm').submit();
                    }
                });
            });
        }

        function confirmCheckOut() {
            navigator.geolocation.getCurrentPosition((position) => {
                document.getElementById('check_out_location').value = 
                    `${position.coords.latitude}, ${position.coords.longitude}`;
                
                Swal.fire({
                    title: 'Konfirmasi Check Out',
                    text: 'Apakah Anda yakin ingin melakukan check out sekarang?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Check Out!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('checkOutForm').submit();
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            setInterval(updateClock, 1000);
            updateClock();
        });

        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString();
            document.getElementById('date').textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long', 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric'
            });
        }
    </script>
</x-app-layout>
