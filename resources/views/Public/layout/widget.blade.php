@php
    $sekolah = config('sekolah');
@endphp

<!-- FLOATING CHATBOT WIDGET -->
<div class="fixed bottom-6 right-6 z-50">
    <button id="chatbot-btn" type="button" aria-controls="chatbot-window" aria-expanded="false"
        class="bg-white border-2 border-brand-blue text-brand-blue hover:bg-brand-blue hover:text-white transition-all duration-300 rounded-full px-4 py-2.5 shadow-2xl flex items-center gap-2 group floating-button-shadow">
        <span class="w-8 h-8 rounded-full bg-blue-100 group-hover:bg-white text-brand-blue flex items-center justify-center">
            <i class="fa-solid fa-robot text-base"></i>
        </span>
        <span class="font-bold text-sm">ChatBot</span>
    </button>

    <!-- ChatBot Popover Window -->
    <div id="chatbot-window"
        class="hidden absolute bottom-16 right-0 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden text-slate-800">
        <div class="bg-brand-blue text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-robot"></i>
                <span class="font-bold text-sm">Asisten Virtual {{ $sekolah['nama_pendek'] }}</span>
            </div>
            <button type="button" data-chatbot-close class="text-white hover:text-slate-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div id="chat-messages" class="p-4 h-60 overflow-y-auto space-y-3 text-xs">
            <div class="bg-slate-100 p-2.5 rounded-xl max-w-[85%] text-slate-700">
                Halo! Ada yang bisa saya bantu mengenai informasi {{ $sekolah['nama_pendek'] }}?
            </div>
        </div>
        <form id="chat-form" class="p-2 border-t border-slate-100 flex gap-2">
            <label for="chat-input" class="sr-only">Pertanyaan</label>
            <input id="chat-input" type="text" autocomplete="off" placeholder="Tulis pertanyaan..."
                class="flex-1 text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:border-brand-blue">
            <button type="submit" class="bg-brand-blue text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-brand-dark-blue">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<!-- GLOBAL MODAL DIALOG -->
<div id="global-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title"
    class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div id="modal-content"
        class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative transform transition-all scale-95 opacity-0">
        <div class="flex items-start justify-between gap-4 mb-4">
            <h3 id="modal-title" class="text-lg font-bold text-slate-900">Detail</h3>
            <button type="button" data-modal-close class="text-slate-400 hover:text-slate-600 text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <img id="modal-image" src="" alt="" class="hidden w-full h-44 object-cover rounded-xl mb-4 border border-slate-100">
        <p id="modal-meta" class="hidden text-[11px] font-bold uppercase tracking-wider text-brand-blue mb-2"></p>
        <p id="modal-body" class="text-slate-600 text-sm leading-relaxed mb-6 whitespace-pre-line"></p>
        <div class="text-right">
            <button type="button" data-modal-close
                class="bg-brand-blue text-white font-semibold text-xs px-5 py-2.5 rounded-lg hover:bg-brand-dark-blue">
                Tutup
            </button>
        </div>
    </div>
</div>
