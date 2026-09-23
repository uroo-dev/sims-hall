@extends('Public.layout.app')

@section('title', 'SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan')

@section('content')
    <div class="overflow-x-auto">
    <div class="w-[1280px] h-[5824px] relative bg-white overflow-hidden">

        {{-- ================= HEADER / NAVBAR ================= --}}
        <img class="w-64 h-20 left-[19px] top-[2px] absolute" src="https://placehold.co/272x88" alt="Logo SMK Negeri 2 Karanganyar" />
        <div class="w-[905px] h-20 left-[375px] top-0 absolute bg-sky-700 rounded-bl-[80px] shadow-[0px_4px_50px_0px_rgba(0,0,0,0.25)]"></div>

        <div class="left-[1204px] top-[30px] absolute justify-start text-white text-sm font-semibold font-['Public_Sans'] leading-5">PPDB</div>
        <div class="left-[1056px] top-[30px] absolute justify-start text-white text-sm font-semibold font-['Public_Sans'] leading-5">PKL &amp; BKK</div>
        <div class="w-2.5 h-[5px] left-[1139px] top-[37px] absolute outline outline-[1.5px] outline-offset-[-0.75px] outline-white"></div>
        <div class="left-[872px] top-[30px] absolute justify-start text-white text-sm font-semibold font-['Public_Sans'] leading-5">Produk Unggulan</div>
        <div class="w-2.5 h-[5px] left-[995px] top-[39px] absolute outline outline-[1.5px] outline-offset-[-0.75px] outline-white"></div>
        <div class="left-[444px] top-[31px] absolute justify-start text-white text-sm font-semibold font-['Public_Sans'] leading-5">Profile</div>
        <div class="left-[732px] top-[31px] absolute justify-start text-white text-sm font-semibold font-['Public_Sans'] leading-5">Kesiswaan</div>
        <div class="left-[543px] top-[31px] absolute justify-start text-white text-sm font-semibold font-['Public_Sans'] leading-5">Peminjaman Aula</div>
        <div class="w-2.5 h-[5px] left-[667px] top-[38px] absolute outline outline-[1.5px] outline-offset-[-0.75px] outline-white"></div>
        <div class="w-2.5 h-[5px] left-[811px] top-[39px] absolute outline outline-[1.5px] outline-offset-[-0.75px] outline-white"></div>

        {{-- ================= HERO ================= --}}
        <div class="w-[593px] h-72 left-[79px] top-[224px] absolute inline-flex flex-col justify-start items-start gap-10">
            <div class="flex flex-col justify-start items-start gap-5">
                <div class="w-[550px] justify-start text-sky-700 text-xl font-normal font-['Poppins'] leading-7">Sekolah Pusat Keunggulan</div>
                <div class="w-[550px] justify-start text-slate-700 text-6xl font-semibold font-['Poppins'] leading-[76.8px]">SMKN 2<br/>KARANGANYAR</div>
                <div class="w-[550px] justify-start text-slate-700 text-base font-normal font-['Poppins'] leading-6">Sebagai Sekolah Pusat Keunggulan, kami berkomitmen menghadirkan siswa berkualitas dengan standar industri. Kolaborasi dengan dunia industri menjadikan siswa lebih siap menghadapi tantangan kerja dan peluang masa depan.</div>
            </div>
        </div>
        <div class="w-11 h-4 left-[88px] top-[161px] absolute"></div>
        <img class="w-[481px] h-96 left-[734px] top-[193px] absolute" src="https://placehold.co/481x416" alt="Gedung sekolah SMKN 2 Karanganyar" />
        <div class="w-44 h-11 left-[93.45px] top-[586.67px] absolute bg-sky-700 rounded-2xl shadow-[0px_4px_4px_0px_rgba(0,0,0,0.25)]"></div>
        <div class="left-[108.45px] top-[601.67px] absolute text-center justify-start text-white text-xs font-semibold font-['Poppins'] leading-4">Pelajari Selengkapnya</div>

        {{-- ================= SEKSI PEMINJAMAN AULA ================= --}}
        <div class="w-[1280px] h-[832px] left-0 top-[819px] absolute bg-slate-200/30"></div>
        <div class="left-[368px] top-[870px] absolute justify-start text-black text-4xl font-medium font-['Poppins'] leading-[56px]">Layanan Peminjaman Aula</div>
        <div class="w-[690px] left-[296px] top-[939px] absolute text-center justify-start text-gray-600 text-base font-normal font-['Poppins'] leading-5">Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan acara institusi, perusahaan, dan masyarakat umum.</div>

        <div class="w-[775px] h-[576px] left-[427px] top-[1019px] absolute bg-white rounded-2xl"></div>
        <img class="w-72 h-96 left-[860px] top-[1104px] absolute rounded-[10px] shadow-[0px_4px_50px_0px_rgba(0,0,0,0.25)]" src="https://placehold.co/298x374" alt="Aula SMK Negeri 2 Karanganyar" />
        <img class="w-56 h-48 left-[815px] top-[1339px] absolute rounded-[10px] shadow-[0px_4px_50px_0px_rgba(0,0,0,0.25)]" src="https://placehold.co/217x187" alt="Detail aula" />

        <div class="size-6 left-[451px] top-[1054px] absolute bg-black"></div>
        <div class="w-96 h-52 left-[452px] top-[1120px] absolute justify-center">
            <span class="text-black text-base font-normal font-['Poppins'] leading-5"><br/><br/><br/><br/><br/><br/>Kami menyediakan layanan peminjaman aula sekolah untuk berbagai kebutuhan kegiatan. Mulai dari acara sekolah, organisasi, rapat, seminar, hingga kegiatan instansi luar.<br/><br/>Layanan kami mencakup<br/></span>
            <span class="text-black text-base font-normal font-['Poppins'] leading-5">Booking Aula Online.<br/>Peminjaman Aula Berkualitas.<br/>Fasilitas Lengkap.<br/>Kebersihan &amp; Kenyamanan.<br/>Parkir &amp; Keamanan.<br/></span>
            <span class="text-black text-base font-normal font-['Poppins'] leading-5"><br/>Kami siap membantu menciptakan tempat kegiatan yang nyaman dan berkualitas.</span>
        </div>
        <div class="w-80 h-72 left-[79px] top-[1018px] absolute bg-white rounded-2xl border-2 border-sky-700"></div>
        <div class="w-80 h-10 left-[485px] top-[1047px] absolute justify-center text-black text-2xl font-bold font-['Inter'] leading-5">Informasi Peminjaman Aula</div>
        <div class="w-36 h-8 left-[452px] top-[1474px] absolute bg-sky-700 rounded-2xl shadow-[0px_4px_4px_0px_rgba(0,0,0,0.25)]"></div>
        <div class="w-40 h-8 left-[471px] top-[1475px] absolute justify-center text-white text-xs font-semibold font-['Poppins'] leading-5">Mulai Peminjaman</div>
        <div class="w-80 h-0 left-[451px] top-[1093px] absolute outline outline-[3px] outline-offset-[-1.5px] outline-sky-700"></div>

        {{-- Paket Unggulan --}}
        <div class="w-32 h-6 left-[172px] top-[1005px] absolute bg-sky-700 rounded-[10px]"></div>
        <div class="w-24 h-9 left-[204px] top-[999px] absolute justify-center text-white text-sm font-bold font-['Poppins'] leading-5">Unggulan</div>
        <div class="w-56 h-9 left-[124px] top-[1051px] absolute justify-center">
            <span class="text-neutral-500 text-sm font-normal font-['Poppins'] leading-5">Rp.</span><span class="text-black text-2xl font-bold font-['Poppins'] leading-5"> </span><span class="text-sky-700 text-2xl font-semibold font-['Poppins'] leading-5">6.000.000</span><span class="text-black text-sm font-bold font-['Poppins'] leading-5"> </span><span class="text-neutral-500 text-sm font-normal font-['Poppins'] leading-5">/ 12 Jam</span>
        </div>
        <div class="w-48 h-9 left-[158px] top-[1092px] absolute justify-center text-black text-sm font-normal font-['Poppins'] leading-5">Sound System Medium</div>
        <div class="w-12 h-9 left-[161px] top-[1121px] absolute justify-center text-black text-sm font-normal font-['Poppins'] leading-5">Mic 4</div>
        <div class="size-6 left-[124px] top-[1130px] absolute overflow-hidden">
            <div class="size-4 left-[4px] top-[4px] absolute bg-black"></div>
            <div class="w-2.5 h-2 left-[7.15px] top-[9.15px] absolute bg-black"></div>
        </div>
        <div class="size-6 left-[124px] top-[1100px] absolute overflow-hidden">
            <div class="size-4 left-[4px] top-[4px] absolute bg-black"></div>
            <div class="w-2.5 h-2 left-[7.15px] top-[9.15px] absolute bg-black"></div>
        </div>
        <div class="w-48 h-9 left-[158px] top-[1151px] absolute justify-center text-black text-sm font-normal font-['Poppins'] leading-5">500 Kursi + Cover</div>
        <div class="w-20 h-9 left-[161px] top-[1180px] absolute justify-center text-black text-sm font-normal font-['Poppins'] leading-5">Proyektor 2</div>
        <div class="size-6 left-[124px] top-[1189px] absolute overflow-hidden">
            <div class="size-4 left-[4px] top-[4px] absolute bg-black"></div>
            <div class="w-2.5 h-2 left-[7.15px] top-[9.15px] absolute bg-black"></div>
        </div>
        <div class="size-6 left-[124px] top-[1159px] absolute overflow-hidden">
            <div class="size-4 left-[4px] top-[4px] absolute bg-black"></div>
            <div class="w-2.5 h-2 left-[7.15px] top-[9.15px] absolute bg-black"></div>
        </div>
        <div class="w-56 h-9 left-[124px] top-[1230px] absolute bg-sky-700 rounded-lg"></div>
        <div class="w-20 h-9 left-[206px] top-[1230px] absolute justify-center text-white text-sm font-semibold font-['Poppins'] leading-5">Pilih Paket</div>

        {{-- Paket Terjangkau --}}
        <div class="w-80 h-72 left-[79px] top-[1326px] absolute bg-white rounded-2xl border-2 border-sky-700"></div>
        <div class="w-32 h-6 left-[172px] top-[1313px] absolute bg-sky-700 rounded-[10px]"></div>
        <div class="w-24 h-9 left-[199px] top-[1307px] absolute justify-center text-white text-sm font-bold font-['Poppins'] leading-5">Terjangkau</div>
        <div class="w-56 h-9 left-[124px] top-[1354px] absolute justify-center">
            <span class="text-neutral-500 text-sm font-normal font-['Poppins'] leading-5">Rp.</span><span class="text-black text-2xl font-bold font-['Poppins'] leading-5"> </span><span class="text-sky-700 text-2xl font-bold font-['Poppins'] leading-5">1</span><span class="text-sky-700 text-2xl font-semibold font-['Poppins'] leading-5">.500.000</span><span class="text-black text-sm font-bold font-['Poppins'] leading-5"> </span><span class="text-neutral-500 text-sm font-normal font-['Poppins'] leading-5">/ 4 Jam</span>
        </div>
        <div class="w-48 h-9 left-[158px] top-[1395px] absolute justify-center text-black text-sm font-normal font-['Poppins'] leading-5">Sound System Standar</div>
        <div class="w-12 h-9 left-[161px] top-[1424px] absolute justify-center text-black text-sm font-normal font-['Poppins'] leading-5">Mic 2</div>
        <div class="size-6 left-[124px] top-[1433px] absolute overflow-hidden">
            <div class="size-4 left-[4px] top-[4px] absolute bg-black"></div>
            <div class="w-2.5 h-2 left-[7.15px] top-[9.15px] absolute bg-black"></div>
        </div>
        <div class="size-6 left-[124px] top-[1403px] absolute overflow-hidden">
            <div class="size-4 left-[4px] top-[4px] absolute bg-black"></div>
            <div class="w-2.5 h-2 left-[7.15px] top-[9.15px] absolute bg-black"></div>
        </div>
        <div class="w-48 h-9 left-[158px] top-[1454px] absolute justify-center text-black text-sm font-normal font-['Poppins'] leading-5">100 Kursi</div>
        <div class="w-20 h-9 left-[161px] top-[1483px] absolute justify-center text-black text-sm font-normal font-['Poppins'] leading-5">Proyektor 1</div>
        <div class="size-6 left-[124px] top-[1492px] absolute overflow-hidden">
            <div class="size-4 left-[4px] top-[4px] absolute bg-black"></div>
            <div class="w-2.5 h-2 left-[7.15px] top-[9.15px] absolute bg-black"></div>
        </div>
        <div class="size-6 left-[124px] top-[1462px] absolute overflow-hidden">
            <div class="size-4 left-[4px] top-[4px] absolute bg-black"></div>
            <div class="w-2.5 h-2 left-[7.15px] top-[9.15px] absolute bg-black"></div>
        </div>
        <div class="w-56 h-9 left-[124px] top-[1533px] absolute bg-sky-700 rounded-lg"></div>
        <div class="w-20 h-9 left-[206px] top-[1533px] absolute justify-center text-white text-sm font-semibold font-['Poppins'] leading-5">Pilih Paket</div>

        <div class="w-[140px] h-[150px] left-[1126px] top-[955px] absolute plus-tex" aria-hidden="true"></div>

        {{-- ================= KOMPETENSI KEAIHLIAN ================= --}}
        <div class="left-[434px] top-[1691px] absolute justify-start text-black text-4xl font-medium font-['Poppins'] leading-[56px]">Kompetensi Keahlian</div>
        <div class="w-[690px] left-[293px] top-[1747px] absolute text-center justify-start text-gray-600 text-base font-normal font-['Poppins'] leading-5">Beragam kompetensi keahlian berbasis teknologi dan industri yang membekali siswa dengan keterampilan profesional sesuai kebutuhan dunia kerja.</div>
        <img class="w-72 h-96 left-[58px] top-[1815px] absolute" src="https://placehold.co/302x435" alt="Kompetensi keahlian 1" />
        <img class="w-64 h-96 left-[365px] top-[1818px] absolute" src="https://placehold.co/264x432" alt="Kompetensi keahlian 2" />
        <img class="w-72 h-96 left-[627px] top-[1839px] absolute" src="https://placehold.co/279x411" alt="Kompetensi keahlian 3" />
        <img class="w-64 h-96 left-[911px] top-[1803px] absolute" src="https://placehold.co/271x447" alt="Kompetensi keahlian 4" />

        {{-- ================= MITRA DUDI ================= --}}
        <div class="w-[1131px] h-44 left-[83px] top-[2287px] absolute bg-white rounded-2xl shadow-[0px_7px_15px_0px_rgba(0,0,0,0.25)]"></div>
        <div class="w-96 h-5 left-[110px] top-[2303px] absolute justify-center text-neutral-500 text-xl font-bold font-['Inter'] leading-9">MITRA DUDI &mdash; Kerjasama Industri</div>
        <img class="size-20 left-[839px] top-[2340px] absolute" src="https://placehold.co/77x77" alt="Logo mitra 1" />
        <img class="w-44 h-14 left-[374px] top-[2361px] absolute" src="https://placehold.co/178x60" alt="Logo mitra 2" />
        <img class="w-52 h-16 left-[971px] top-[2340px] absolute" src="https://placehold.co/211x70" alt="Logo mitra 3" />
        <img class="w-32 h-14 left-[147px] top-[2356px] absolute" src="https://placehold.co/132x60" alt="Logo mitra 4" />
        <img class="w-32 h-16 left-[620px] top-[2358px] absolute" src="https://placehold.co/130x63" alt="Logo mitra 5" />
        <div class="w-80 h-0 left-[111px] top-[2333px] absolute outline outline-[3px] outline-offset-[-1.5px] outline-neutral-200"></div>
        <div class="w-40 h-0 left-[111px] top-[2333px] absolute outline outline-[3px] outline-offset-[-1.5px] outline-blue-500"></div>

        {{-- ================= KEHIDUPAN KESISWAAN ================= --}}
        <div class="w-[1280px] h-[832px] left-0 top-[2497px] absolute bg-slate-200/30"></div>
        <div class="left-[171px] top-[2563px] absolute justify-start text-black text-4xl font-medium font-['Poppins'] leading-[56px]">Kehidupan Kesiswaan</div>
        <div class="w-[690px] left-[171px] top-[2619px] absolute justify-start text-gray-600 text-base font-normal font-['Poppins'] leading-5">Membentuk karakter, kedisiplinan, dan potensi non-akademik.</div>

        <div class="w-[128px] h-[138px] left-[17px] top-[2621px] absolute plus-tex" aria-hidden="true"></div>

        <div class="w-[1156px] h-80 left-[65px] top-[2678px] absolute inline-flex flex-col justify-start items-start">
            <div class="self-stretch h-80 p-8 bg-white rounded-lg shadow-[0px_4px_20px_0px_rgba(30,58,95,0.08)] border-t-4 border-sky-700 inline-flex flex-col justify-between items-start">
                <div class="self-stretch flex flex-col justify-start items-start gap-3">
                    <div class="size-7 bg-sky-700"></div>
                    <div class="self-stretch pt-1 flex flex-col justify-start items-start">
                        <div class="self-stretch justify-center text-sky-950 text-2xl font-semibold font-['Montserrat'] leading-8">Ekstrakurikuler</div>
                    </div>
                    <div class="self-stretch flex flex-col justify-start items-start">
                        <div class="w-56 justify-center text-zinc-700 text-base font-normal font-['Inter'] leading-6">pilihan kegiatan mulai<br/>dari kedisiplinan, olahraga, hingga klub teknologi.</div>
                    </div>
                </div>
                <div class="self-stretch pt-6 flex flex-col justify-start items-start">
                    <div class="self-stretch inline-flex justify-start items-center gap-2">
                        <div class="justify-center text-sky-700 text-base font-normal font-['Inter'] leading-6">Daftar Eskul</div>
                        <div class="flex flex-col justify-start items-start">
                            <div class="w-2 h-3 bg-sky-700"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="self-stretch h-80 px-8 pt-8 pb-20 bg-sky-700 rounded-lg shadow-[0px_4px_20px_0px_rgba(30,58,95,0.08)] inline-flex flex-col justify-start items-start gap-3">
                <div class="size-7 bg-white"></div>
                <div class="self-stretch pt-1 flex flex-col justify-start items-start">
                    <div class="self-stretch justify-center text-white text-2xl font-semibold font-['Montserrat'] leading-8">Tata Tertib</div>
                </div>
                <div class="self-stretch pb-3 flex flex-col justify-start items-start">
                    <div class="self-stretch justify-center text-white/80 text-base font-normal font-['Inter'] leading-6">Pedoman kedisiplinan siswa<br/>untuk membentuk etos kerja<br/>profesional.</div>
                </div>
                <div class="px-4 py-2 bg-white/10 rounded-sm outline outline-1 outline-offset-[-1px] outline-white/20 inline-flex justify-center items-center">
                    <div class="text-center justify-center text-white text-sm font-normal font-['Inter'] leading-5">Unduh PDF</div>
                </div>
            </div>
            <div class="w-[566px] h-80 bg-white rounded-2xl"></div>
            <div class="w-[566px] h-80 bg-white rounded-2xl"></div>
            <div class="w-[473.5px] h-9 text-justify justify-start text-neutral-500 text-xs font-normal font-['Poppins'] leading-4">Selamat dan Sukses bagi peserta didik SMKN 2 KARANGANYAR yang telah Lolos SNBT (Seleksi Nasional Berdasarkan Tes) Tahun 2026.</div>
            <div class="w-20 h-4 bg-sky-700 rounded-2xl"></div>
            <div class="w-16 h-2.5 justify-start text-white text-[8px] font-semibold font-['Poppins'] leading-3">AKADEMIK</div>
            <div class="w-[566px] h-56 bg-white rounded-tl-2xl rounded-tr-2xl"></div>
            <div class="w-[566px] h-56 bg-white rounded-tl-2xl rounded-tr-2xl"></div>
            <img class="w-[566px] h-56 rounded-tl-2xl rounded-tr-2xl" src="https://placehold.co/566x221" alt="Prestasi akademik" />
        </div>

        <div class="w-[1156px] h-64 left-[65px] top-[3036px] absolute bg-white rounded-2xl shadow-[0px_4px_20px_0px_rgba(0,0,0,0.25)] overflow-hidden">
            <div class="w-8 h-9 left-[1094px] top-[107px] absolute bg-zinc-300 rounded-full"></div>
            <div class="w-4 h-5 left-[1100px] top-[132.67px] absolute origin-top-left rotate-[-89deg]">
                <div class="w-5 h-4 left-0 top-0 absolute"></div>
                <div class="w-1.5 h-2.5 left-[7.14px] top-[3.06px] absolute outline outline-[1.5px] outline-offset-[-0.75px] outline-white"></div>
            </div>
            <div class="w-8 h-9 left-[67.48px] top-[138.81px] absolute origin-top-left rotate-[174deg] bg-zinc-300 rounded-full"></div>
            <div class="w-4 h-6 left-[60.89px] top-[115.7px] absolute origin-top-left rotate-[89.61deg]">
                <div class="w-6 h-4 left-0 top-0 absolute"></div>
                <div class="w-1.5 h-2.5 left-[6.92px] top-[3.11px] absolute outline outline-[1.5px] outline-offset-[-0.75px] outline-white"></div>
            </div>
            <div class="w-96 h-5 left-[39px] top-[22px] absolute justify-center text-neutral-500 text-xl font-bold font-['Inter'] leading-9">Prestasi Terbaru</div>
            <div class="w-40 h-0 left-[40px] top-[52px] absolute outline outline-[3px] outline-offset-[-1.5px] outline-neutral-200"></div>
            <div class="w-20 h-0 left-[40px] top-[52px] absolute outline outline-[3px] outline-offset-[-1.5px] outline-blue-500"></div>
            <img class="w-96 h-36 left-[103px] top-[71px] absolute rounded-md" src="https://placehold.co/447x142" alt="Prestasi 1" />
            <img class="w-[471px] h-36 left-[582px] top-[71px] absolute rounded-md" src="https://placehold.co/471x142" alt="Prestasi 2" />
        </div>

        <div class="w-[128px] h-[134px] left-[1142px] top-[3184px] absolute plus-tex" aria-hidden="true"></div>

        {{-- ================= PRODUK UNGGULAN ================= --}}
        <div class="left-[445px] top-[3399px] absolute justify-start text-black text-4xl font-medium font-['Poppins'] leading-[56px]">Produk Unggulan</div>
        <div class="w-[690px] left-[280px] top-[3472px] absolute text-center justify-start text-gray-600 text-base font-normal font-['Poppins'] leading-5">Beragam produk unggulan berbasis teknologi dan industri yang mencerminkan keterampilan siswa sesuai kebutuhan dunia kerja.</div>

        <img class="w-[667px] h-60 left-[82px] top-[3874px] absolute rounded-2xl" src="https://placehold.co/667x243" alt="Produk permesinan" />
        <img class="w-[667px] h-60 left-[82px] top-[3874px] absolute rounded-2xl border border-green-800" src="https://placehold.co/667x243" alt="Produk permesinan 2" />
        <img class="w-96 h-60 left-[82px] top-[3593px] absolute rounded-2xl border border-blue-700" src="https://placehold.co/427x243" alt="Produk unggulan 1" />
        <div class="w-52 h-24 left-[116px] top-[3669px] absolute justify-center text-black text-xs font-normal font-['Inter'] uppercase leading-5">Diproses menggunakan mesin modern yang menghasilkan produk dengan kualitas tinggi, presisi, dan hasil yang konsisten.</div>
        <img class="size-32 left-[351px] top-[3665px] absolute" src="https://placehold.co/128x128" alt="Mesin modern" />
        <div class="w-72 h-5 left-[116px] top-[3626px] absolute justify-center text-black text-2xl font-semibold font-['Inter'] uppercase leading-5">TEKNIK PERMESINAN</div>
        <img class="w-80 h-52 left-[384px] top-[3909px] absolute" src="https://placehold.co/312x208" alt="Produk rekayasa perangkat lunak" />
        <div class="w-32 h-8 left-[111px] top-[4067px] absolute bg-neutral-800 rounded-lg">
            <div class="w-28 h-4 left-[10px] top-[7px] absolute text-center justify-center text-white text-xs font-medium font-['Inter'] uppercase leading-4">SEMUA PRODUK</div>
        </div>
        <div class="w-64 h-14 left-[111px] top-[3909px] absolute justify-center text-black text-2xl font-medium font-['Inter'] uppercase leading-7">Rekayasa Perangkat Lunak</div>
        <div class="w-56 h-14 left-[112px] top-[3984px] absolute justify-center text-black text-xs font-normal font-['Inter'] uppercase leading-5">Dari Company Profile,<br/>E-Commerce, hingga Aplikasi Online</div>
        <img class="w-[649px] h-60 left-[537px] top-[3598px] absolute rounded-2xl border border-amber-200" src="https://placehold.co/649x238" alt="Produk pembuatan kain" />
        <img class="w-96 h-60 left-[779px] top-[3877px] absolute rounded-2xl border border-red-600" src="https://placehold.co/407x238" alt="Produk ototronik" />
        <div class="w-32 h-8 left-[571px] top-[3777px] absolute bg-neutral-800 rounded-lg">
            <div class="w-28 h-4 left-[10px] top-[7px] absolute text-center justify-center text-white text-xs font-medium font-['Inter'] uppercase leading-4">SEMUA PRODUK</div>
        </div>
        <div class="w-32 h-8 left-[805px] top-[4067px] absolute bg-neutral-800 rounded-lg">
            <div class="w-28 h-4 left-[10px] top-[7px] absolute text-center justify-center text-white text-xs font-medium font-['Inter'] uppercase leading-4">SEMUA PRODUK</div>
        </div>
        <div class="w-32 h-8 left-[116px] top-[3784px] absolute bg-neutral-800 rounded-lg">
            <div class="w-28 h-4 left-[10px] top-[7px] absolute text-center justify-center text-white text-xs font-medium font-['Inter'] uppercase leading-4">SEMUA PRODUK</div>
        </div>
        <div class="w-72 h-14 left-[571px] top-[3700px] absolute justify-center text-black text-xs font-normal font-['Inter'] uppercase leading-5">Dari kain batik, tenun, hingga kain ecoprint semua diproduksi oleh siswa jurusan Tekstil.</div>
        <div class="w-72 h-14 left-[571px] top-[3626px] absolute justify-center text-black text-2xl font-medium font-['Inter'] uppercase leading-7">TEKNIK PEMBUATAN KAIN</div>
        <img class="w-28 h-48 left-[943px] top-[3611px] absolute" src="https://placehold.co/116x195" alt="Kain produksi siswa" />
        <img class="size-52 left-[1002px] top-[3611px] absolute" src="https://placehold.co/202x202" alt="Kain produksi siswa 2" />
        <div class="w-64 h-5 left-[804px] top-[3914px] absolute justify-center text-black text-2xl font-semibold font-['Inter'] uppercase leading-5">TEKNIK OTOTRONIK</div>
        <img class="size-36 left-[1086.91px] top-[3909px] absolute origin-top-left rotate-[28.57deg]" src="https://placehold.co/142x142" alt="Produk ototronik 2" />
        <div class="w-60 h-24 left-[804px] top-[3957px] absolute justify-center text-black text-xs font-normal font-['Inter'] uppercase leading-5">teknologi otoTRONIK modern dalam perawatan dan perbaikan kendaraan untuk menghasilkan performa yang optimal dan berkualitas.</div>

        {{-- ================= INFORMASI PPDB ================= --}}
        <div class="w-[1280px] h-[832px] left-0 top-[4187px] absolute bg-slate-200/30"></div>
        <div class="left-[702px] top-[4256px] absolute justify-start text-black text-4xl font-medium font-['Poppins'] leading-[56px]">INFORMASI PPDB<br/>SMKN 2 KARANGANYAR</div>
        <img class="w-[604px] h-96 left-[63px] top-[4256px] absolute rounded-[10px] shadow-[0px_4px_50px_0px_rgba(0,0,0,0.25)]" src="https://placehold.co/604x400" alt="Informasi PPDB" />
        <div class="w-[458px] left-[702px] top-[4377px] absolute justify-start">
            <span class="text-black text-base font-normal font-['Poppins'] leading-5">Calon Murid Baru yang akan mengikuti PPDB Tahun 2024 diharapkan menyiapkan seluruh dokumen persyaratan sebelum melakukan pengajuan akun. Kelengkapan berkas yang diunggah akan memperlancar proses verifikasi data dan menghindari kendala saat pendaftaran.<br/><br/>Persyaratan ini mengacu pada </span>
            <span class="text-black text-base font-normal font-['Poppins'] underline leading-5">Petunjuk Operasional Penyelenggaraan SPMB SMA Negeri, SMK Negeri, dan SLB Negeri Provinsi Jawa Tengah Tahun Ajaran 2026/2027</span>
            <span class="text-black text-base font-normal font-['Poppins'] leading-5">.</span>
        </div>
        <div class="w-44 h-10 left-[702px] top-[4610px] absolute bg-sky-700 rounded-2xl shadow-[0px_4px_4px_0px_rgba(0,0,0,0.25)]"></div>
        <div class="w-40 h-8 left-[726px] top-[4615px] absolute justify-center text-white text-xs font-semibold font-['Poppins'] leading-5">Lihat Selengkapnya</div>

        <div class="w-[130px] h-[144px] left-[30px] top-[4209px] absolute plus-tex" aria-hidden="true"></div>
        <div class="w-[128px] h-[134px] left-[1142px] top-[4665px] absolute plus-tex" aria-hidden="true"></div>

        {{-- Daya Tampung --}}
        <div class="w-[1156px] h-64 left-[63px] top-[4732px] absolute bg-white rounded-2xl shadow-[0px_4px_20px_0px_rgba(0,0,0,0.25)] overflow-hidden">
            <div class="w-64 h-36 left-[28px] top-[74px] absolute bg-blue-600/80 rounded-2xl"></div>
            <div class="w-64 h-36 left-[312px] top-[74px] absolute bg-orange-500/80 rounded-2xl"></div>
            <div class="w-64 h-36 left-[595px] top-[74px] absolute bg-red-600/80 rounded-2xl"></div>
            <div class="w-64 h-36 left-[878px] top-[74px] absolute bg-green-600/80 rounded-2xl"></div>
            <div class="left-[47px] top-[86px] absolute justify-start text-white text-xl font-semibold font-['Poppins'] leading-7">TEKNIK PERMESINAN</div>
            <div class="left-[51px] top-[122px] absolute justify-start text-white text-5xl font-semibold font-['Poppins'] leading-[67.2px]">108</div>
            <div class="left-[56px] top-[178px] absolute justify-start text-white text-base font-normal font-['Poppins'] leading-6">Siswa</div>
            <div class="left-[337px] top-[90px] absolute justify-start text-white text-base font-semibold font-['Poppins'] leading-6">TEKNIK PEMBUATAN KAIN</div>
            <div class="left-[337px] top-[122px] absolute justify-start text-white text-5xl font-semibold font-['Poppins'] leading-[67.2px]">108</div>
            <div class="left-[342px] top-[178px] absolute justify-start text-white text-base font-normal font-['Poppins'] leading-6">Siswa</div>
            <div class="left-[619px] top-[86px] absolute justify-start text-white text-xl font-semibold font-['Poppins'] leading-7">TEKNIK OTOTRONIK</div>
            <div class="left-[623px] top-[122px] absolute justify-start text-white text-5xl font-semibold font-['Poppins'] leading-[67.2px]">108</div>
            <div class="left-[628px] top-[178px] absolute justify-start text-white text-base font-normal font-['Poppins'] leading-6">Siswa</div>
            <div class="w-52 left-[910px] top-[88px] absolute justify-start text-white text-base font-semibold font-['Poppins'] leading-6">REKAYASA PERANGKAT LUNAK</div>
            <div class="left-[910px] top-[122px] absolute justify-start text-white text-5xl font-semibold font-['Poppins'] leading-[67.2px]">108</div>
            <div class="left-[915px] top-[178px] absolute justify-start text-white text-base font-normal font-['Poppins'] leading-6">Siswa</div>
        </div>
        <div class="w-96 h-5 left-[94px] top-[4749px] absolute justify-center">
            <span class="text-neutral-500 text-lg font-bold font-['Poppins'] leading-9">Daya Tampung &mdash; </span>
            <span class="text-neutral-500 text-lg font-semibold font-['Poppins'] leading-9">Kompetensi Keahlian</span>
        </div>
        <div class="w-96 h-0 left-[95px] top-[4779px] absolute outline outline-[3px] outline-offset-[-1.5px] outline-neutral-200"></div>
        <div class="w-44 h-0 left-[95px] top-[4779px] absolute outline outline-[3px] outline-offset-[-1.5px] outline-blue-500"></div>

        {{-- ================= FOOTER ================= --}}
        <div class="w-[1284px] h-[549px] left-[-4px] top-[5201px] absolute bg-sky-700 rounded-tl-[80px] rounded-tr-[80px] shadow-[0px_4px_50px_0px_rgba(0,0,0,0.25)]"></div>
        <img class="w-80 h-52 left-[85px] top-[5304px] absolute" src="https://placehold.co/307x205" alt="Logo SMK Negeri 2 Karanganyar" />
        <div class="w-96 h-16 left-[85px] top-[5551px] absolute justify-center text-white text-base font-medium font-['Poppins'] leading-5">SMK Negeri 2 Karanganyar adalah salah satu Sekolah Menengah Kejuruan favorit di Kabupaten Karanganyar. Serta merupakan sekolah yang berpendidikan karakter, berwawasan, disiplin, tanggung jawab, dan bermoral baik.</div>
        <img class="w-[639px] h-80 left-[545px] top-[5304px] absolute rounded-[10px]" src="https://placehold.co/639x347" alt="Lokasi SMK Negeri 2 Karanganyar" />
        <div class="w-[1284px] h-20 left-[-4px] top-[5747px] absolute bg-stone-900"></div>
        <div class="left-[456px] top-[5776px] absolute justify-start text-white text-sm font-normal font-['Public_Sans'] leading-5">&copy; 2026 SMKN 2 Karanganyar. All Rights Reserved</div>

    </div>
    </div>
@endsection