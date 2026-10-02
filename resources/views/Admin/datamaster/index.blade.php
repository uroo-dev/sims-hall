@extends('Admin.layout.app')

@section('title', 'Dashboard Data Master')
@section('content')



    <!-- STATS OVERVIEW -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <!-- TOTAL GURU -->
        <a href="{{ route('datamaster.guru.index') }}" class="group bg-white rounded-2xl p-5 card-shadow border border-gray-100 hover:border-brand-300 hover:shadow-md transition duration-200 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Guru</span>
                <div class="text-2xl font-extrabold text-gray-800">{{ $totalGuru ?? 0 }}</div>
                <div class="text-[11px] font-semibold text-brand-600 group-hover:text-brand-700 flex items-center gap-1">
                    <span>Kelola Data Guru</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl border border-cyan-100 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
        </a>

        <!-- TOTAL SISWA -->
        <a href="{{ route('datamaster.siswa.index') }}" class="group bg-white rounded-2xl p-5 card-shadow border border-gray-100 hover:border-brand-300 hover:shadow-md transition duration-200 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Siswa</span>
                <div class="text-2xl font-extrabold text-gray-800">{{ $totalSiswa ?? 0 }}</div>
                <div class="text-[11px] font-semibold text-brand-600 group-hover:text-brand-700 flex items-center gap-1">
                    <span>Kelola Data Siswa</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl border border-emerald-100 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </a>

        <!-- TOTAL USERS -->
        <a href="{{ route('datamaster.users') }}" class="group bg-white rounded-2xl p-5 card-shadow border border-gray-100 hover:border-brand-300 hover:shadow-md transition duration-200 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Akun Pengguna</span>
                <div class="text-2xl font-extrabold text-gray-800">{{ $totalUsers ?? 0 }}</div>
                <div class="text-[11px] font-semibold text-brand-600 group-hover:text-brand-700 flex items-center gap-1">
                    <span>Kelola Akun User</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl border border-blue-100 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users-gear"></i>
            </div>
        </a>
    </div>

    <!-- BOTTOM SECTION -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                <h3 class="font-bold text-gray-800 text-xs md:text-sm">{{ $sekolah->nama_kepsek ?: 'Nama Kepala Sekolah Belum Diatur' }}</h3>
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
@endsection

@push('scripts')
    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('userChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($userChartLabels ?? ['Admin', 'Guru', 'Kepala Sekolah', 'Pelanggan']) !!},
                    datasets: [{
                        label: 'Jumlah Pengguna',
                        data: {!! json_encode($userChartData ?? [0, 0, 0, 0]) !!},
                        backgroundColor: '#82e0aa',
                        hoverBackgroundColor: '#58d68d',
                        borderRadius: 4,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => `Total: ${context.raw} Pengguna`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 9, weight: 'bold' },
                                color: '#333',
                                maxRotation: 45,
                                autoSkip: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: { size: 10 },
                                color: '#666'
                            },
                            grid: { color: '#f0f0f0' }
                        }
                    }
                }
            });
        });
    </script>
@endpush