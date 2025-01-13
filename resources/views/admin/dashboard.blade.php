<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-4">Halaman Admin</h2>
            
            <!-- Statistik -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <!-- Total Keseluruhan Karyawan -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">Total Keseluruhan Karyawan</h3>
                    <p class="text-3xl">{{ $users->count() }}</p>

                    <!-- Tombol untuk Rincian Karyawan -->
                    <div x-data="{ open: false }" class="mt-4">
                        <button @click="open = ! open" class="text-blue-500 hover:text-blue-700 flex items-center">
                            <span class="mr-2">Lihat Daftar Karyawan</span>
                            <svg :class="open ? 'transform rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="mt-2">
                            <ul class="list-disc pl-5">
                                @foreach ($users as $user)
                                    <li>{{ $user->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Hadir Hari Ini -->


                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">Hadir Hari Ini</h3>
                    <p class="text-3xl">{{ $todayAttendance->count() }}</p>
                    
                    <a href="{{ route('admin.attendance.previous') }}" class="btn btn-primary mt-4">Lihat Presensi Sebelumnya </a>

                </div>

                <!-- Belum Hadir -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">Belum Hadir</h3>
                    <p class="text-3xl">
                        @php
                            $belumHadir = $users->count() - $todayAttendance->count();
                        @endphp
                        {{ $belumHadir }}
                    </p>

                    <!-- Tombol untuk Rincian Belum Hadir -->
                    <div x-data="{ open: false }" class="mt-4">
                        <button @click="open = ! open" class="text-blue-500 hover:text-blue-700 flex items-center">
                            <span class="mr-2">Lihat Karyawan yang Belum Hadir</span>
                            <svg :class="open ? 'transform rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="mt-2">
                            <ul class="list-disc pl-5">
                                @foreach ($users as $user)
                                    @if (!$todayAttendance->pluck('employee_id')->contains($user->id))
                                        <li>{{ $user->name }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- Daftar Karyawan yang Hadir Hari Ini -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-2">Karyawan yang Hadir Hari Ini</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Waktu Hadir</th>
                            <th class="px-4 py-2 text-left">Waktu Keluar</th>
                            <th class="px-4 py-2 text-left">Lokasi Hadir</th>
                            <th class="px-4 py-2 text-left">Lokasi Keluar</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Foto Check In</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                            

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($todayAttendance as $attendance)
                            <tr>
                                <td class="px-4 py-2">{{ $attendance->employee->name }}</td>
                                <td class="px-4 py-2">{{ $attendance->check_in->format('H:i:s') }}</td>
                                <td class="px-4 py-2">
                                    @if ($attendance->check_out)
                                        {{ $attendance->check_out->format('H:i:s') }}
                                    @else
                                        Belum Check-Out
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $attendance->check_in_location ?? 'Lokasi tidak diketahui' }}</td>
                                <td class="px-4 py-2">{{ $attendance->check_out_location ?? 'Lokasi tidak diketahui' }}</td>
                                <td class="px-4 py-2">{{ $attendance->status }}</td>
                                <td class="px-4 py-2">
                                    @if ($attendance->check_in_photo)
                                        <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" alt="Foto Check In" class="w-16 h-16 object-cover rounded-full">
                                    @else
                                        Tidak Ada Foto
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-blue-600 hover:text-blue-800">Ubah Status</button>
                                    </form>
                                    <form action="{{ route('admin.attendance.delete', $attendance->id) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </td>
                                

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            
        </div>
    </div>
</x-app-layout>