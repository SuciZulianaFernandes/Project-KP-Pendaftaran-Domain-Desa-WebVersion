@extends('layouts.admin')

@section('title', 'Detail Perpanjangan Domain')

@section('content')

<div class="space-y-6">
    <div class="flex flex-col lg:flex-row gap-6">

        <!-- SIDEBAR STATUS -->
        <div class="w-full lg:w-72 flex-shrink-0 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/80">
                    <h3 class="font-bold text-slate-800 text-sm">Status Domain</h3>
                </div>

                <div class="p-5 text-sm space-y-4">
                    
                    <div>
                        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Domain</p>
                        <h3 class="font-extrabold text-[#1A85A5] text-base">{{ $pengajuan->nama_domain }}<span class="text-slate-400">.desa.id</span></h3>
                    </div>

                    @php
                        $aktivasiTerakhir = $pengajuan->aktivasi()->orderBy('masa_berlaku', 'desc')->first();
                        $finalStatus = $pengajuan->status_pengajuan;

                        if (!$faktur) {
                            $finalStatus = 'diproses';
                        } elseif ($faktur->status == 'belum_bayar') {
                            $finalStatus = 'diproses';
                        } elseif ($faktur->status == 'sudah_bayar' && $aktivasiTerakhir && $aktivasiTerakhir->created_at->lt($faktur->updated_at)) {
                            $finalStatus = 'menunggu_aktivasi';
                        } elseif ($aktivasiTerakhir) {
                            $finalStatus = $aktivasiTerakhir->status_akt;
                        }
                    @endphp

                    <div>
                        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Status Saat Ini</p>
                        
                        @if($finalStatus == 'ditinjau')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-100">
                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Ditinjau
                            </span>
                        @elseif($finalStatus == 'perlu_perbaikan')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Perlu Perbaikan
                            </span>
                        @elseif($finalStatus == 'diproses')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-600 border border-sky-100">
                                <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span> Diproses
                            </span>
                        @elseif($finalStatus == 'menunggu_aktivasi')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100">
                                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span> Menunggu Aktivasi
                            </span>
                        @elseif($finalStatus == 'aktif')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                            </span>
                        @elseif($finalStatus == 'kadaluarsa')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Kadaluarsa
                            </span>
                        @elseif($finalStatus == 'nonaktif')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-50 text-slate-400 border border-slate-100">
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span> Nonaktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-50 text-slate-500 border border-slate-100">
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span> {{ ucfirst(str_replace('_', ' ', $finalStatus)) }}
                            </span>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="flex-1">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">

                <!-- HEADER -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Detail Perpanjangan Domain</h2>
                        <p class="text-sm text-slate-400 mt-1">Informasi lengkap perpanjangan domain desa.</p>
                    </div>
                    <a href="{{ route('admin.perpanjang.list') }}"
                       class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl inline-flex items-center justify-center transition-colors text-sm">
                        <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali
                    </a>
                </div>

                {{-- NOTIFIKASI: FAKTUR BELUM DIBUAT --}}
                @if(!$faktur || $menungguFakturBaru)
                <div class="mb-6 bg-sky-50 p-5 rounded-xl border border-sky-200/60">
                    <div class="flex items-start gap-3">
                        <div class="bg-sky-100 p-2 rounded-lg text-sky-600 flex-shrink-0">
                            <i class="fas fa-file-invoice text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800">Desa Menyetujui Pembayaran</h3>
                            <p class="text-sm text-slate-500 mt-1 mb-4">Desa telah mengkonfirmasi kesiapan pembayaran. Silakan terbitkan faktur perpanjangan.</p>
                            
                            <form action="{{ route('admin.faktur.storePerpanjangan', $pengajuan->id_pengajuan) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-br from-[#109696] to-[#1A85A5] hover:shadow-md text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 shadow-sm js-confirm-print">
                                    <i class="fas fa-print text-xs"></i> Cetak Faktur Perpanjangan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <!-- INFORMASI INSTANSI -->
                <div class="mb-6">
                    <div class="border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-base font-bold text-slate-800">Informasi Instansi</h3>
                        <p class="text-sm text-slate-400 mt-0.5">Detail data instansi dan informasi domain.</p>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-x-10 gap-y-3 text-sm">
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Nama Organisasi</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 font-medium break-words">{{ $pengajuan->nama_desa }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Provinsi</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->provinsi }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Kabupaten</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->kota_kabupaten }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Kecamatan</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->kecamatan }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Desa</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->desa_kelurahan }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Telepon</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->telepon }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Faksimili</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->faksimili }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Kode Pos</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->kode_pos }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-52 text-slate-400 font-medium">Email Registran</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->email }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row xl:col-span-2">
                            <span class="sm:w-52 text-slate-400 font-medium">Alamat</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->alamat }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row xl:col-span-2">
                            <span class="sm:w-52 text-slate-400 font-medium">Tanggal Pembuatan</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="text-slate-700 break-words">{{ $pengajuan->created_at->format('d M Y') }}</span></div>
                        </div>
                    </div>
                </div>

                {{-- DATA PERPANJANGAN AKTIF --}}
                @if($faktur)
                <div class="mb-6 bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-base font-bold text-slate-800">Data Perpanjangan Saat Ini</h3>
                        <p class="text-sm text-slate-400 mt-0.5">Informasi faktur dan status pembayaran perpanjangan domain item ini.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-3 text-sm">
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-44 text-slate-400 font-medium">No Invoice</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="font-semibold text-slate-700">{{ $faktur->no_invoice }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-44 text-slate-400 font-medium">Tanggal Faktur</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="font-semibold text-slate-700">{{ $faktur->created_at->format('d M Y H:i') }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-44 text-slate-400 font-medium">Status Pembayaran</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span>
                                <span class="font-semibold">
                                    @if($faktur->status == 'sudah_bayar')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Belum Lunas
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-44 text-slate-400 font-medium">Total Tagihan</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="font-semibold text-slate-800">Rp {{ number_format($faktur->total,0,',','.') }}</span></div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- MASA BERLAKU -->
                <div class="mb-6 bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-base font-bold text-slate-800">Riwayat Masa Berlaku Domain</h3>
                        <p class="text-sm text-slate-400 mt-0.5">Informasi akumulasi masa aktif seluruh sistem domain saat ini.</p>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-60 text-slate-400 font-medium">Tanggal Aktivasi Terakhir</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="font-semibold text-slate-700">{{ $aktivasiTerakhir ? $aktivasiTerakhir->tgl_aktivasi->format('d M Y') : '-' }}</span></div>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="sm:w-60 text-slate-400 font-medium">Masa Berlaku Hingga</span>
                            <div class="flex"><span class="hidden sm:inline w-4 text-center text-slate-300">:</span><span class="font-extrabold text-emerald-600 text-base">{{ $aktivasiTerakhir ? $aktivasiTerakhir->masa_berlaku->format('d M Y') : '-' }}</span></div>
                        </div>
                    </div>
                </div>

                <!-- RIWAYAT INVOICE -->
                @if($pengajuan->faktur->isNotEmpty())
                <div class="mb-6 bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="mb-4">
                        <h3 class="text-base font-bold text-slate-800">Riwayat Invoice & Faktur Global</h3>
                        <p class="text-sm text-slate-400">Daftar semua tagihan perpanjangan maupun pendaftaran baru untuk domain ini.</p>
                    </div>

                    <div class="space-y-3">
                        @foreach($pengajuan->faktur->sortByDesc('created_at') as $fakturItem)
                        <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div class="space-y-2 text-sm flex-1">
                                    <div>
                                        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Nomor Invoice (Tipe: {{ ucfirst($fakturItem->tipe) }})</p>
                                        <p class="font-bold text-slate-800 mt-0.5">{{ $fakturItem->no_invoice }}</p>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
                                        <div>
                                            <p class="text-slate-400 text-xs">Total Tagihan</p>
                                            <p class="font-medium text-slate-700">Rp {{ number_format($fakturItem->total,0,',','.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 text-xs">Status Pembayaran</p>
                                            @if($fakturItem->status == 'belum_bayar')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-100 mt-0.5">
                                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>Belum Bayar
                                                </span>
                                            @elseif($fakturItem->status == 'sudah_bayar')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 mt-0.5">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Lunas
                                                </span>
                                            @elseif($fakturItem->status == 'kedaluarsa')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100 mt-0.5">
                                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Kadaluarsa
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-start lg:justify-end flex-shrink-0">
                                    <a href="{{ route('admin.faktur.show', $fakturItem->id) }}"
                                       class="inline-flex items-center gap-2 bg-[#109696]/10 text-[#109696] hover:bg-[#109696] hover:text-white text-sm font-semibold px-4 py-2 rounded-lg transition-all duration-200">
                                        <i class="fas fa-eye text-xs"></i> Detail Faktur
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <hr class="my-6 border-slate-100">

                {{-- BAGIAN AKSI INTERAKTIF ADMIN --}}
                @if($faktur && $finalStatus == 'menunggu_aktivasi')
                
                @php
                    $durasiPerpanjangan = $faktur->durasi_tahun ?? 1;
                    $defaultTglMulai = $aktivasiTerakhir ? $aktivasiTerakhir->tgl_aktivasi->format('Y-m-d') : date('Y-m-d');
                @endphp

                <div class="bg-gradient-to-br from-emerald-50 to-white p-6 rounded-2xl border-2 border-emerald-200/60 shadow-sm mt-2">
                    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                        
                        <!-- Kiri: Info & Input Tanggal -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-emerald-100 p-2.5 rounded-xl text-emerald-600 flex-shrink-0">
                                    <i class="fas fa-shield-alt text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">Aktivasi Perpanjangan Domain</h3>
                                    <p class="text-sm text-slate-500">Tentukan masa berlaku perpanjangan pada inputan di bawah.</p>
                                </div>
                            </div>
                            
                            <!-- Info Pilihan Desa & Masa Aktif Lama -->
                            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm mb-4 space-y-3">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Permintaan Desa</p>
                                    <p class="text-sm text-slate-700 mt-1">Desa memilih perpanjangan: <strong class="text-[#1A85A5]">{{ $durasiPerpanjangan }} Tahun</strong></p>
                                </div>
                                
                                @if($aktivasiTerakhir)
                                <div class="border-t border-slate-100 pt-3">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Masa Aktif Sebelumnya</p>
                                    <div class="flex items-center gap-3 mt-1 text-sm">
                                        <span class="text-slate-500">{{ $aktivasiTerakhir->tgl_aktivasi->format('d M Y') }}</span>
                                        <span class="text-slate-300">—</span>
                                        <span class="font-semibold text-slate-800">{{ $aktivasiTerakhir->masa_berlaku->format('d M Y') }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Form Input Tanggal -->
                            <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" id="formAktivasi" class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai Aktif <span class="text-rose-500">*</span></label>
                                        <input type="date" name="tgl_mulai" value="{{ $defaultTglMulai }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] text-sm p-2.5 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Berlaku Sampai <span class="text-rose-500">*</span></label>
                                        <input type="date" name="tgl_selesai" required class="w-full bg-slate-50 border border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] text-sm p-2.5 transition">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Kanan: Tombol Polos -->
                        <button type="submit" 
                                form="formAktivasi"
                                class="js-confirm-btn bg-gradient-to-br from-[#109696] to-[#1A85A5] hover:shadow-md text-white font-semibold py-3 px-8 rounded-xl shadow-sm transition-all duration-200 text-sm flex-shrink-0 h-fit"
                                data-confirm-message="Apakah Anda yakin ingin mengaktifkan perpanjangan domain ini sesuai tanggal yang diinput?">
                            <i class="fas fa-check-circle mr-2"></i> Aktivasi Domain
                        </button>

                    </div>
                </div>

                @elseif($faktur && $faktur->status == 'sudah_bayar' && $finalStatus == 'aktif' && !$menungguFakturBaru)
                <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200/60">
                    <p class="text-emerald-700 font-semibold flex items-center gap-2">
                        <i class="fas fa-check-double"></i> Selesai! Masa berlaku domain berhasil diakumulasikan dan saat ini berstatus Aktif.
                    </p>
                </div>

                @elseif($faktur && $faktur->status == 'belum_bayar')
                <div class="bg-sky-50 p-4 rounded-xl border border-sky-200/60">
                    <p class="text-sky-800 font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-hourglass-half"></i> Faktur perpanjangan telah diterbitkan. Menunggu berkas pembayaran diunggah oleh desa.
                    </p>
                    <a href="{{ route('admin.faktur.index') }}" class="text-sm underline text-sky-600 hover:text-sky-800 font-medium">
                        Lihat di Manajemen Faktur &rarr;
                    </a>
                </div>

                @elseif($aktivasiTerakhir && $aktivasiTerakhir->status_akt == 'kadaluarsa')
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <p class="text-slate-600 font-semibold flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i> Masa berlaku domain ini telah kadaluarsa. Menunggu desa mengajukan perpanjangan baru.
                    </p>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection