{{-- Unified Alert Component (SIMS Sarpras SMK N 2 Kra) --}}
@php
    $hasAlert = session('success') || session('error') || session('warning') || session('info') || (isset($errors) && $errors->any());
@endphp

<div id="unified-alert-container" class="space-y-3 {{ $hasAlert ? 'mb-5' : 'hidden' }} transition-all duration-300">
    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div class="unified-alert flex items-start gap-3.5 p-4 rounded-2xl bg-emerald-50/95 border border-emerald-200/90 text-emerald-900 shadow-xl backdrop-blur-md transition-all duration-300 transform relative overflow-hidden" role="alert" data-auto-dismiss="true">
            <div class="w-9 h-9 rounded-xl bg-emerald-100/80 text-emerald-600 flex items-center justify-center shrink-0 text-base shadow-xs mt-0.5">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="font-bold text-sm tracking-tight text-emerald-950">Berhasil!</h4>
                <p class="text-xs md:text-sm text-emerald-800 leading-relaxed mt-0.5">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="closeUnifiedAlert(this.closest('.unified-alert'))" class="text-emerald-500 hover:text-emerald-800 p-1.5 rounded-lg hover:bg-emerald-100/50 transition cursor-pointer" aria-label="Tutup notifikasi">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
            <div class="alert-progress absolute bottom-0 left-0 h-1 bg-emerald-500/30 w-full transition-all duration-[5000ms] ease-linear"></div>
        </div>
    @endif

    {{-- ERROR ALERT --}}
    @if (session('error'))
        <div class="unified-alert flex items-start gap-3.5 p-4 rounded-2xl bg-rose-50/95 border border-rose-200/90 text-rose-900 shadow-xl backdrop-blur-md transition-all duration-300 transform relative overflow-hidden" role="alert">
            <div class="w-9 h-9 rounded-xl bg-rose-100/80 text-rose-600 flex items-center justify-center shrink-0 text-base shadow-xs mt-0.5">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="font-bold text-sm tracking-tight text-rose-950">Terjadi Kesalahan!</h4>
                <p class="text-xs md:text-sm text-rose-800 leading-relaxed mt-0.5">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="closeUnifiedAlert(this.closest('.unified-alert'))" class="text-rose-500 hover:text-rose-800 p-1.5 rounded-lg hover:bg-rose-100/50 transition cursor-pointer" aria-label="Tutup notifikasi">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    {{-- VALIDATION ERRORS ALERT --}}
    @if ($errors->any())
        <div class="unified-alert flex items-start gap-3.5 p-4 rounded-2xl bg-rose-50/95 border border-rose-200/90 text-rose-900 shadow-xl backdrop-blur-md transition-all duration-300 transform relative overflow-hidden" role="alert">
            <div class="w-9 h-9 rounded-xl bg-rose-100/80 text-rose-600 flex items-center justify-center shrink-0 text-base shadow-xs mt-0.5">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="font-bold text-sm tracking-tight text-rose-950">Periksa Kembali Input Form:</h4>
                <ul class="text-xs md:text-sm text-rose-800 list-disc list-inside space-y-1 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="closeUnifiedAlert(this.closest('.unified-alert'))" class="text-rose-500 hover:text-rose-800 p-1.5 rounded-lg hover:bg-rose-100/50 transition cursor-pointer" aria-label="Tutup notifikasi">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    {{-- WARNING ALERT --}}
    @if (session('warning'))
        <div class="unified-alert flex items-start gap-3.5 p-4 rounded-2xl bg-amber-50/95 border border-amber-200/90 text-amber-900 shadow-xl backdrop-blur-md transition-all duration-300 transform relative overflow-hidden" role="alert" data-auto-dismiss="true">
            <div class="w-9 h-9 rounded-xl bg-amber-100/80 text-amber-600 flex items-center justify-center shrink-0 text-base shadow-xs mt-0.5">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="font-bold text-sm tracking-tight text-amber-950">Peringatan</h4>
                <p class="text-xs md:text-sm text-amber-800 leading-relaxed mt-0.5">{{ session('warning') }}</p>
            </div>
            <button type="button" onclick="closeUnifiedAlert(this.closest('.unified-alert'))" class="text-amber-500 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-100/50 transition cursor-pointer" aria-label="Tutup notifikasi">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
            <div class="alert-progress absolute bottom-0 left-0 h-1 bg-amber-500/30 w-full transition-all duration-[5000ms] ease-linear"></div>
        </div>
    @endif

    {{-- INFO ALERT --}}
    @if (session('info'))
        <div class="unified-alert flex items-start gap-3.5 p-4 rounded-2xl bg-blue-50/95 border border-blue-200/90 text-blue-900 shadow-xl backdrop-blur-md transition-all duration-300 transform relative overflow-hidden" role="alert" data-auto-dismiss="true">
            <div class="w-9 h-9 rounded-xl bg-blue-100/80 text-[#0060ac] flex items-center justify-center shrink-0 text-base shadow-xs mt-0.5">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="font-bold text-sm tracking-tight text-blue-950">Informasi</h4>
                <p class="text-xs md:text-sm text-blue-800 leading-relaxed mt-0.5">{{ session('info') }}</p>
            </div>
            <button type="button" onclick="closeUnifiedAlert(this.closest('.unified-alert'))" class="text-blue-500 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-100/50 transition cursor-pointer" aria-label="Tutup notifikasi">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
            <div class="alert-progress absolute bottom-0 left-0 h-1 bg-blue-500/30 w-full transition-all duration-[5000ms] ease-linear"></div>
        </div>
    @endif
</div>

<script>
    function checkAlertContainer() {
        const container = document.getElementById('unified-alert-container');
        if (container && container.querySelectorAll('.unified-alert').length === 0) {
            container.classList.add('hidden');
        }
    }

    function closeUnifiedAlert(alertEl) {
        if (!alertEl) return;
        if (window.gsap) {
            gsap.to(alertEl, {
                opacity: 0,
                y: -15,
                scale: 0.95,
                duration: 0.25,
                ease: 'power2.in',
                onComplete: function() {
                    alertEl.remove();
                    checkAlertContainer();
                }
            });
        } else {
            alertEl.classList.add('opacity-0', '-translate-y-2', 'scale-98');
            setTimeout(() => {
                alertEl.remove();
                checkAlertContainer();
            }, 250);
        }
    }

    // Auto-dismiss alerts with data-auto-dismiss="true" after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const autoDismissAlerts = document.querySelectorAll('#unified-alert-container [data-auto-dismiss="true"]');
        autoDismissAlerts.forEach(el => {
            const bar = el.querySelector('.alert-progress');
            if (bar) {
                setTimeout(() => {
                    bar.style.width = '0%';
                }, 50);
            }
            setTimeout(() => {
                closeUnifiedAlert(el);
            }, 5000);
        });
    });

    // Global Client JS Alert Helper
    window.showAlert = function(type, message, title = '') {
        const container = document.getElementById('unified-alert-container');
        if (!container) return;
        container.classList.remove('hidden');

        const themes = {
            success: {
                bg: 'bg-emerald-50/95 border-emerald-200/90 text-emerald-900',
                iconBg: 'bg-emerald-100/80 text-emerald-600',
                icon: 'fa-circle-check',
                titleColor: 'text-emerald-950',
                textColor: 'text-emerald-800',
                btnColor: 'text-emerald-500 hover:text-emerald-800 hover:bg-emerald-100/50',
                barColor: 'bg-emerald-500/30',
                defaultTitle: 'Berhasil!'
            },
            error: {
                bg: 'bg-rose-50/95 border-rose-200/90 text-rose-900',
                iconBg: 'bg-rose-100/80 text-rose-600',
                icon: 'fa-circle-exclamation',
                titleColor: 'text-rose-950',
                textColor: 'text-rose-800',
                btnColor: 'text-rose-500 hover:text-rose-800 hover:bg-rose-100/50',
                barColor: 'bg-rose-500/30',
                defaultTitle: 'Terjadi Kesalahan!'
            },
            warning: {
                bg: 'bg-amber-50/95 border-amber-200/90 text-amber-900',
                iconBg: 'bg-amber-100/80 text-amber-600',
                icon: 'fa-triangle-exclamation',
                titleColor: 'text-amber-950',
                textColor: 'text-amber-800',
                btnColor: 'text-amber-500 hover:text-amber-800 hover:bg-amber-100/50',
                barColor: 'bg-amber-500/30',
                defaultTitle: 'Peringatan'
            },
            info: {
                bg: 'bg-blue-50/95 border-blue-200/90 text-blue-900',
                iconBg: 'bg-blue-100/80 text-[#0060ac]',
                icon: 'fa-circle-info',
                titleColor: 'text-blue-950',
                textColor: 'text-blue-800',
                btnColor: 'text-blue-500 hover:text-blue-800 hover:bg-blue-100/50',
                barColor: 'bg-blue-500/30',
                defaultTitle: 'Informasi'
            }
        };

        const theme = themes[type] || themes.info;
        const displayTitle = title || theme.defaultTitle;

        const alertDiv = document.createElement('div');
        alertDiv.className = `unified-alert flex items-start gap-3.5 p-4 rounded-2xl border shadow-xl backdrop-blur-md transition-all duration-300 transform relative overflow-hidden ${theme.bg}`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.setAttribute('data-auto-dismiss', 'true');
        alertDiv.innerHTML = `
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-base shadow-xs mt-0.5 ${theme.iconBg}">
                <i class="fa-solid ${theme.icon}"></i>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="font-bold text-sm tracking-tight ${theme.titleColor}">${displayTitle}</h4>
                <p class="text-xs md:text-sm leading-relaxed mt-0.5 ${theme.textColor}">${message}</p>
            </div>
            <button type="button" onclick="closeUnifiedAlert(this.closest('.unified-alert'))" class="p-1.5 rounded-lg transition cursor-pointer ${theme.btnColor}" aria-label="Tutup notifikasi">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
            <div class="alert-progress absolute bottom-0 left-0 h-1 ${theme.barColor} w-full transition-all duration-[5000ms] ease-linear"></div>
        `;

        container.prepend(alertDiv);

        if (window.gsap) {
            gsap.from(alertDiv, { y: -20, opacity: 0, duration: 0.35, ease: 'back.out(1.4)' });
        }

        setTimeout(() => {
            const bar = alertDiv.querySelector('.alert-progress');
            if (bar) bar.style.width = '0%';
        }, 50);

        setTimeout(() => {
            closeUnifiedAlert(alertDiv);
        }, 5000);
    };
</script>
