<!-- FLOATING CHATBOT WIDGET -->
<div class="fixed bottom-6 right-6 z-50">
    <button id="chatbot-btn" onclick="toggleChatbot()"
        class="bg-white border-2 border-brand-blue text-brand-blue hover:bg-brand-blue hover:text-white transition-all duration-300 rounded-full px-4 py-2.5 shadow-2xl flex items-center gap-2 group floating-button-shadow">
        <div
            class="w-8 h-8 rounded-full bg-blue-100 group-hover:bg-white text-brand-blue flex items-center justify-center">
            <i class="fa-solid fa-robot text-base"></i>
        </div>
        <span class="font-bold text-sm">ChatBot</span>
    </button>

    <!-- ChatBot Modal Popover Window -->
    <div id="chatbot-window"
        class="hidden absolute bottom-16 right-0 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden text-slate-800">
        <div class="bg-brand-blue text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-robot"></i>
                <span class="font-bold text-sm">Asisten Virtual SMKN 2</span>
            </div>
            <button onclick="toggleChatbot()" class="text-white hover:text-slate-200"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="p-4 h-60 overflow-y-auto space-y-3 text-xs" id="chat-messages">
            <div class="bg-slate-100 p-2.5 rounded-xl max-w-[85%] text-slate-700">
                Halo! Ada yang bisa saya bantu mengenai informasi SMKN 2 Karanganyar atau PPDB 2026?
            </div>
        </div>
        <div class="p-2 border-t border-slate-100 flex gap-2">
            <input id="chat-input" type="text" placeholder="Tulis pertanyaan..."
                class="flex-1 text-xs border rounded-lg px-3 py-2 focus:outline-none focus:border-brand-blue">
            <button onclick="sendChatMessage()"
                class="bg-brand-blue text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-brand-darkBlue">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

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
