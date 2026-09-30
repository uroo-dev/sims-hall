<!-- GLOBAL MODAL DIALOG -->
<div id="global-modal"
    class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative transform transition-all scale-95 opacity-0"
        id="modal-content">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modal-title" class="text-lg font-bold text-slate-900">Modal Title</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <p id="modal-body" class="text-slate-600 text-sm leading-relaxed mb-6">Modal body text...</p>
        <div class="text-right">
            <button onclick="closeModal()"
                class="bg-brand-blue text-white font-semibold text-xs px-5 py-2.5 rounded-lg hover:bg-brand-darkBlue">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- CHATBOT "NANYA AI" -->
<x-chatbot />
