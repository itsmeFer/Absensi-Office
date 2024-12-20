<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('warning') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Kartu Informasi Absensi --}}
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
                            <form method="POST" action="{{ route('check-in') }}" id="checkInForm">
                                @csrf
                                <button 
                                    type="submit" 
                                    id="checkInBtn"
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105"
                                >
                                    Absen Masuk
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
                                <button 
                                    type="submit" 
                                    id="checkOutBtn"
                                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105"
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
                {{ $todayAttendance->check_in ? 
                    $todayAttendance->check_in->translatedFormat('l, d F Y - H:i:s') : 
                    'Belum Check In' 
                }}
            </div>
            <div>
                <strong>Check Out:</strong> 
                {{ $todayAttendance->check_out ? 
                    $todayAttendance->check_out->translatedFormat('l, d F Y - H:i:s') : 
                    'Belum Check Out' 
                }}
            </div>
        </div>
    @else
        <p class="text-gray-600">Belum melakukan absensi hari ini</p>
    @endif
</div>
            </div>
        </div>
    </div>

    {{-- Script Pencegah Double Submit --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInBtn = document.getElementById('checkInBtn');
        const checkOutBtn = document.getElementById('checkOutBtn');
        const checkInForm = document.getElementById('checkInForm');
        const checkOutForm = document.getElementById('checkOutForm');

        function preventDoubleSubmit(form, button) {
            if (form && button) {
                form.addEventListener('submit', function() {
                    button.disabled = true;
                    button.innerHTML = 'Sedang diproses...';
                });
            }
        }

        preventDoubleSubmit(checkInForm, checkInBtn);
        preventDoubleSubmit(checkOutForm, checkOutBtn);
    });
    </script>
</x-app-layout>