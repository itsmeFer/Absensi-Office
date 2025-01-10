<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-4">Riwayat Presensi - {{ $yesterday }}</h2>

            @if ($attendances->isEmpty())
                <p class="text-center">Tidak ada data presensi untuk {{ $yesterday }}.</p>
            @else
                <table class="min-w-full table-auto border-collapse border border-gray-200">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">No</th>
                            <th class="border border-gray-300 px-4 py-2">Nama Karyawan</th>
                            <th class="border border-gray-300 px-4 py-2">Waktu Check-In</th>
                            <th class="border border-gray-300 px-4 py-2">Waktu Check-Out</th>
                            <th class="border border-gray-300 px-4 py-2">Lokasi Check-In</th>
                            <th class="border border-gray-300 px-4 py-2">Lokasi Check-Out</th>
                            <th class="border border-gray-300 px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $index => $attendance)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->employee->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_in?->format('H:i:s') ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_out?->format('H:i:s') ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_in_location ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_out_location ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ ucfirst($attendance->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-4">Kembali ke Dashboard</a>
        </div>
    </div>
</x-app-layout>
