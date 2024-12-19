<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>

            <!-- Statistik Hari Ini -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">Total Karyawan</h3>
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

            <!-- Daftar Karyawan -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Daftar Karyawan</h3>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="text-left">Nama</th>
                                <th class="text-left">Email</th>
                                <th class="text-left">Role</th>
                                <th class="text-left">Status Hari Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="py-2">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>
                                    @if($user->attendances()->whereDate('check_in', today())->exists())
                                        <span class="text-green-600">Hadir</span>
                                    @else
                                        <span class="text-red-600">Belum Hadir</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>