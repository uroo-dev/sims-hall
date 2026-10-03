@extends('Admin.layout.app')

@section('title', 'Edit Artikel - SIMS Sekolah')

@section('content')

    <!-- BREADCRUMB / HEADER -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.artikel.index') }}"
            class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center justify-center transition shadow-xs">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Edit Artikel</h2>
            <p class="text-xs text-gray-500">Perbarui konten, kategori, atau status publikasi artikel</p>
        </div>
    </div>


    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow border border-gray-100/80">
        <form action="{{ route('admin.artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- LEFT 2 COLUMNS: CONTENT -->
                <div class="lg:col-span-2 space-y-5">
                    
                    <!-- Judul Artikel -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                            Judul Artikel <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul', $artikel->judul) }}" required
                            placeholder="Masukkan judul artikel..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">
                    </div>

                    <!-- Ringkasan / Excerpt -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                            Ringkasan / Sinopsis Singkat
                        </label>
                        <textarea name="ringkasan" rows="3" maxlength="500"
                            placeholder="Tulis ringkasan singkat artikel sebagai pengantar..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">{{ old('ringkasan', $artikel->ringkasan) }}</textarea>
                        <span class="block text-[11px] text-gray-400 mt-1">Maksimal 500 karakter.</span>
                    </div>

                    <!-- Konten Artikel -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide">
                                Isi Lengkap Artikel <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-gray-400 font-medium flex items-center gap-1">
                                <i class="fa-solid fa-pen-nib text-[#0073c6]"></i> WYSIWYG Editor
                            </span>
                        </div>
                        <div id="editorWrapper" class="relative">
                            <div id="editor" class="bg-white">{!! old('konten', $artikel->konten) !!}</div>
                            <input type="hidden" name="konten" id="kontenInput" value="{{ old('konten', $artikel->konten) }}">
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1.5">Gunakan toolbar di atas untuk format teks, heading, list, kutipan, tautan, dan gambar.</p>
                    </div>

                </div>

                <!-- RIGHT 1 COLUMN: METADATA & UPLOAD -->
                <div class="space-y-5">
                    
                    <!-- Kategori Artikel -->
                    <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-4">
                        <h4 class="font-bold text-gray-800 text-xs uppercase tracking-wide flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-[#0073c6]"></i> Pengaturan Publikasi
                        </h4>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select name="kategori_artikel_id" required
                                class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] bg-white text-gray-800">
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_artikel_id', $artikel->kategori_artikel_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Artikel -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">
                                Status Publikasi <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" required
                                class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] bg-white text-gray-800">
                                <option value="published" {{ old('status', $artikel->status) === 'published' ? 'selected' : '' }}>
                                    Published (Terbit)
                                </option>
                                <option value="draft" {{ old('status', $artikel->status) === 'draft' ? 'selected' : '' }}>
                                    Draft (Simpan sebagai Konsep)
                                </option>
                            </select>
                        </div>

                        <div class="text-[11px] text-gray-500 pt-2 border-t border-gray-200/60 space-y-1">
                            <div><span class="text-gray-400">Dibuat:</span> {{ $artikel->created_at->format('d M Y H:i') }}</div>
                            <div><span class="text-gray-400">Terbit:</span> {{ $artikel->published_at ? $artikel->published_at->format('d M Y H:i') : 'Belum Terbit' }}</div>
                        </div>
                    </div>

                    <!-- Gambar Sampul -->
                    <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-3">
                        <h4 class="font-bold text-gray-800 text-xs uppercase tracking-wide flex items-center gap-2">
                            <i class="fa-solid fa-image text-[#0073c6]"></i> Gambar Sampul
                        </h4>

                        @if($artikel->gambar)
                            <div class="mb-3">
                                <span class="block text-[10px] text-gray-400 mb-1">Gambar saat ini:</span>
                                <div class="w-full h-36 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-2xs">
                                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-[#0073c6] transition cursor-pointer relative bg-white" onclick="document.getElementById('gambarInput').click()">
                            <input type="file" name="gambar" id="gambarInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                            
                            <div id="previewContainer" class="hidden mb-2">
                                <img id="previewImg" src="#" alt="Preview Baru" class="w-full h-36 object-cover rounded-xl shadow-xs">
                            </div>

                            <div id="uploadPrompt" class="py-2 space-y-1">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400"></i>
                                <p class="text-xs font-semibold text-gray-700">Ganti file gambar sampul</p>
                                <p class="text-[10px] text-gray-400">Biarkan kosong jika tidak ingin mengubah</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.artikel.index') }}"
                    class="px-5 py-2.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 text-xs font-bold text-white bg-[#0073c6] hover:bg-sky-700 rounded-xl transition shadow-sm hover:shadow-md cursor-pointer active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    /* Custom Styling untuk Quill WYSIWYG */
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        border-color: #e5e7eb;
        background-color: #f8fafc;
        padding: 0.6rem 0.75rem;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        border-color: #e5e7eb;
        font-family: 'Inter', sans-serif;
        font-size: 0.875rem;
        background-color: #ffffff;
    }
    .ql-editor {
        min-height: 300px;
        line-height: 1.7;
        font-size: 0.875rem;
        color: #1f2937;
    }
    .ql-editor.ql-blank::before {
        font-style: normal;
        color: #9ca3af;
        font-size: 0.875rem;
    }
    .ql-snow .ql-stroke {
        stroke: #4b5563;
    }
    .ql-snow .ql-fill {
        fill: #4b5563;
    }
    .ql-snow .ql-picker {
        color: #4b5563;
        font-size: 0.8125rem;
    }
    .ql-container.ql-snow:focus-within {
        border-color: #0073c6;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    // Inisialisasi Quill WYSIWYG
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tuliskan isi artikel sekolah secara lengkap di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Custom Image Handler: Upload gambar langsung ke storage lokal server
    const toolbar = quill.getModule('toolbar');
    toolbar.addHandler('image', function() {
        selectLocalImage();
    });

    function selectLocalImage() {
        const fileInput = document.createElement('input');
        fileInput.setAttribute('type', 'file');
        fileInput.setAttribute('accept', 'image/jpeg,image/png,image/jpg,image/webp,image/gif');
        fileInput.click();

        fileInput.onchange = function() {
            const file = fileInput.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran gambar maksimal 5MB.');
                    return;
                }
                uploadImageToStorage(file);
            }
        };
    }

    function uploadImageToStorage(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        const range = quill.getSelection(true);

        fetch('{{ route('admin.artikel.upload-image') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message || 'Gagal mengunggah gambar'); });
            }
            return response.json();
        })
        .then(result => {
            if (result.success && result.url) {
                quill.insertEmbed(range.index, 'image', result.url);
                quill.setSelection(range.index + 1);
                syncQuillContent();
            } else {
                alert('Gagal mengunggah gambar.');
            }
        })
        .catch(err => {
            console.error('Upload error:', err);
            alert(err.message || 'Terjadi kesalahan saat mengunggah gambar ke storage.');
        });
    }

    const form = document.querySelector('form');
    const kontenInput = document.getElementById('kontenInput');

    function syncQuillContent() {
        const text = quill.getText().trim();
        if (text.length === 0 && !quill.root.querySelector('img, iframe, video')) {
            kontenInput.value = '';
        } else {
            kontenInput.value = quill.root.innerHTML;
        }
    }

    quill.on('text-change', syncQuillContent);

    // Initial sync
    syncQuillContent();

    if (form) {
        form.addEventListener('submit', function(e) {
            syncQuillContent();
            if (!kontenInput.value.trim()) {
                e.preventDefault();
                alert('Isi lengkap artikel wajib diisi!');
                quill.focus();
            }
        });
    }

    function previewImage(input) {
        const preview = document.getElementById('previewImg');
        const container = document.getElementById('previewContainer');
        const prompt = document.getElementById('uploadPrompt');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
                prompt.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
