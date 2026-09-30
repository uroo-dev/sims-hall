@extends('Admin.layout.app')

@section('title', 'Dashboard Data Master')
@section('content')



    <!-- BOTTOM SECTION -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- KEPALA SEKOLAH CARD -->
        <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-[#0073c6] text-xs md:text-sm tracking-wider uppercase">KEPALA SEKOLAH</h2>
                </div>
                <div class="rounded-xl overflow-hidden bg-emerald-100 border border-emerald-200 h-64 flex items-end justify-center relative mb-4">
                    @if(isset($sekolah->foto_kepsek) && $sekolah->foto_kepsek)
                        <img src="{{ asset('assets/' . $sekolah->foto_kepsek) }}" alt="Kepala Sekolah" class="h-full object-contain">
                    @else
                        <img src="{{ asset('assets/foto kepsek.png') }}" alt="Kepala Sekolah Default" class="h-full object-contain">
                    @endif
                </div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-center">
                <h3 class="font-bold text-gray-800 text-xs md:text-sm">{{ $sekolah->nama_kepsek ?? 'Bapak Sukidi S.Pd., M.Pd.' }}</h3>
                <p class="text-gray-500 text-[11px] mt-0.5">Kepala Sekolah SMKN 2 Karanganyar</p>
            </div>
        </div>

        <!-- JUMLAH USER CHART CARD -->
        <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-[#0073c6] text-xs md:text-sm tracking-wider uppercase">JUMLAH USER</h2>
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded">Total: {{ $totalUsers ?? 0 }}</span>
                </div>
                <div class="h-64 w-full pt-2">
                    <canvas id="userChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- SEJARAH CARD -->
    <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-[#0073c6] text-xs md:text-sm tracking-wider uppercase">SEJARAH SMKN 2 KARANGANYAR</h2>
        </div>
        <div class="space-y-3 text-xs text-gray-600 leading-relaxed font-normal">
            {!! nl2br(e($sekolah->sejarah ?? 'Data sejarah belum diisi.')) !!}
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('userChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Organisasi', 'Guru', 'Kepala Sekolah', 'Instansi Luar Terikat', 'Instansi Luar'],
                    datasets: [{
                        data: [6, 10, 1, 16, 18], // Anda bisa mengganti ini dengan data dinamis dari controller jika mau
                        backgroundColor: '#82e0aa',
                        borderRadius: 2,
                        barThickness: 28
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' }, color: '#333', maxRotation: 0, autoSkip: false } },
                        y: { min: 0, max: 18, ticks: { stepSize: 2, font: { size: 10 }, color: '#666' }, grid: { color: '#f0f0f0' } }
                    }
                }
            });
        });
    </script>
@endsection