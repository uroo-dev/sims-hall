{{-- Unified Global Confirmation Modal & Script Helper --}}
<div id="unifiedGlobalConfirmModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity" role="dialog" aria-modal="true">
    <div id="unifiedGlobalConfirmBox" class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200">
        <div class="p-6 text-center space-y-4">
            <div id="unifiedConfirmIconWrap" class="w-14 h-14 mx-auto rounded-2xl bg-red-50 text-red-600 border border-red-100/80 flex items-center justify-center text-2xl shadow-xs">
                <i id="unifiedConfirmIcon" class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 id="unifiedConfirmTitle" class="font-extrabold text-slate-900 text-lg tracking-tight">Konfirmasi Tindakan</h3>
                <p id="unifiedConfirmMessage" class="text-xs md:text-sm text-slate-500 mt-1 leading-relaxed">
                    Apakah Anda yakin ingin melanjutkan tindakan ini?
                </p>
            </div>

            <div class="pt-2 flex items-center justify-center gap-3">
                <button type="button" id="unifiedConfirmCancelBtn" onclick="closeUnifiedConfirmModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="button" id="unifiedConfirmSubmitBtn"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-md shadow-red-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                    <span>Lanjutkan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Standard Global Modal Management
    window.openModal = function(modalId) {
        // If passed title/body as legacy public modal: openModal(title, body)
        if (arguments.length >= 2 && typeof modalId === 'string' && !document.getElementById(modalId)) {
            const title = arguments[0];
            const body = arguments[1];
            const publicModal = document.getElementById('global-modal');
            if (publicModal) {
                const titleEl = document.getElementById('modal-title');
                const bodyEl = document.getElementById('modal-body');
                if (titleEl) titleEl.innerText = title;
                if (bodyEl) bodyEl.innerText = body;
                openModalElement(publicModal);
                return;
            }
        }

        const modal = typeof modalId === 'string' ? document.getElementById(modalId) : modalId;
        if (!modal) return;
        openModalElement(modal);
    };

    window.closeModal = function(modalId) {
        if (!modalId) {
            // Close any currently active modal
            const activeModals = document.querySelectorAll('.fixed.z-\\[100\\]:not(.hidden), #global-modal:not(.hidden)');
            activeModals.forEach(m => closeModalElement(m));
            return;
        }
        const modal = typeof modalId === 'string' ? document.getElementById(modalId) : modalId;
        if (!modal) return;
        closeModalElement(modal);
    };

    // Global function aliases for inline onclick callers
    window.openModalElement = openModalElement;
    window.closeModalElement = closeModalElement;

    function openModalElement(modal) {
        if (!modal) return;
        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        if (!modal.classList.contains('flex')) {
            modal.classList.add('flex');
        }

        const box = modal.querySelector('[id$="Box"], [id$="-content"], [id$="ModalBox"], .bg-white.rounded-3xl, .bg-white.rounded-2xl') || modal.firstElementChild;
        if (box) {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }

        if (window.gsap) {
            try {
                gsap.killTweensOf([modal, box]);
                gsap.fromTo(modal, { opacity: 0 }, { opacity: 1, duration: 0.2, ease: 'power2.out' });
                if (box) {
                    gsap.fromTo(box, 
                        { opacity: 0, scale: 0.92, y: 15 }, 
                        { opacity: 1, scale: 1, y: 0, duration: 0.35, ease: 'back.out(1.4)' }
                    );
                }
            } catch (err) {
                console.warn('GSAP error during openModalElement:', err);
            }
        }
    }

    function closeModalElement(modal) {
        if (!modal) return;
        const box = modal.querySelector('[id$="Box"], [id$="-content"], [id$="ModalBox"], .bg-white.rounded-3xl, .bg-white.rounded-2xl') || modal.firstElementChild;

        const finalize = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Check if any other modal is still open
            const anyOpen = document.querySelectorAll('.fixed.z-\\[100\\]:not(.hidden), #global-modal:not(.hidden)');
            if (anyOpen.length === 0) {
                document.body.classList.remove('overflow-hidden');
            }
        };

        if (window.gsap) {
            gsap.killTweensOf([modal, box]);
            if (box) {
                gsap.to(box, { opacity: 0, scale: 0.94, y: 10, duration: 0.2, ease: 'power2.in' });
            }
            gsap.to(modal, { opacity: 0, duration: 0.2, ease: 'power2.in', onComplete: finalize });
        } else {
            if (box) {
                box.classList.remove('scale-100', 'opacity-100');
                box.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(finalize, 150);
        }
    }

    // Unified Confirm Dialog Helper
    let currentConfirmCallback = null;

    window.showConfirmDialog = function(options = {}) {
        const {
            title = 'Konfirmasi Tindakan',
            message = 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            type = 'danger', // danger | success | warning | info
            confirmText = 'Lanjutkan',
            cancelText = 'Batal',
            onConfirm = null
        } = options;

        const modal = document.getElementById('unifiedGlobalConfirmModal');
        const box = document.getElementById('unifiedGlobalConfirmBox');
        const iconWrap = document.getElementById('unifiedConfirmIconWrap');
        const icon = document.getElementById('unifiedConfirmIcon');
        const titleEl = document.getElementById('unifiedConfirmTitle');
        const messageEl = document.getElementById('unifiedConfirmMessage');
        const submitBtn = document.getElementById('unifiedConfirmSubmitBtn');
        const cancelBtn = document.getElementById('unifiedConfirmCancelBtn');

        if (!modal) return;

        titleEl.textContent = title;
        messageEl.innerHTML = message;
        submitBtn.querySelector('span').textContent = confirmText;
        cancelBtn.textContent = cancelText;

        // Apply theme styling
        iconWrap.className = 'w-14 h-14 mx-auto rounded-2xl flex items-center justify-center text-2xl shadow-xs border ';
        submitBtn.className = 'flex-1 px-4 py-2.5 rounded-xl text-white text-xs md:text-sm font-bold shadow-md active:scale-95 transition flex items-center justify-center gap-2 cursor-pointer ';

        if (type === 'danger') {
            iconWrap.className += 'bg-red-50 text-red-600 border-red-100/80';
            icon.className = 'fa-solid fa-triangle-exclamation';
            submitBtn.className += 'bg-red-600 hover:bg-red-700 shadow-red-500/20';
        } else if (type === 'success') {
            iconWrap.className += 'bg-emerald-50 text-emerald-600 border-emerald-100/80';
            icon.className = 'fa-solid fa-circle-check';
            submitBtn.className += 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20';
        } else if (type === 'warning') {
            iconWrap.className += 'bg-amber-50 text-amber-600 border-amber-100/80';
            icon.className = 'fa-solid fa-triangle-exclamation';
            submitBtn.className += 'bg-amber-600 hover:bg-amber-700 shadow-amber-500/20';
        } else {
            iconWrap.className += 'bg-blue-50 text-[#0060ac] border-blue-100/80';
            icon.className = 'fa-solid fa-circle-info';
            submitBtn.className += 'bg-[#0060ac] hover:bg-[#004f8f] shadow-blue-500/20';
        }

        currentConfirmCallback = onConfirm;

        submitBtn.onclick = function() {
            const callback = currentConfirmCallback;
            closeUnifiedConfirmModal();
            if (typeof callback === 'function') {
                callback();
            }
        };

        openModalElement(modal);
    };

    window.closeUnifiedConfirmModal = function() {
        const modal = document.getElementById('unifiedGlobalConfirmModal');
        if (modal) {
            closeModalElement(modal);
        }
        currentConfirmCallback = null;
    };

    // Global Event Listeners: Backdrop Click & ESC Key
    document.addEventListener('DOMContentLoaded', function() {
        // ESC key handler for all modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openModals = document.querySelectorAll('.fixed:not(.hidden)[role="dialog"], .fixed.z-\\[100\\]:not(.hidden), #global-modal:not(.hidden), #modalLogout:not(.hidden)');
                openModals.forEach(m => {
                    closeModalElement(m);
                });
            }
        });

        // Click outside (backdrop click) to close
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('fixed') && (
                event.target.classList.contains('bg-slate-900/60') || 
                event.target.classList.contains('bg-slate-900/50') || 
                event.target.classList.contains('backdrop-blur-sm') ||
                event.target.classList.contains('backdrop-blur-xs')
            )) {
                closeModalElement(event.target);
            }
        });
    });
</script>
