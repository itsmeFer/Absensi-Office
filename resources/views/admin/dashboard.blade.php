<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-4">Halaman Admin </h2>
            
            <!-- Statistik -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">Total keseluruhan Karyawan</h3>
                    <p class="text-3xl">{{ $users->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">Hadir Hari Ini</h3>
                    <p class="text-3xl">{{ $todayAttendance->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">Belum Hadir</h3>
                    <p class="text-3xl">{{ $users->count() - $todayAttendance->count() }}</p>
                </div>
            </div>

            <!-- Tabel Absensi & Riwayat seperti yang sudah dibuat sebelumnya -->
            
        </div>
    </div>
</x-app-layout>