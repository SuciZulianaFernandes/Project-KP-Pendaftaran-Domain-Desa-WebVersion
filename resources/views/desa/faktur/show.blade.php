@extends('layouts.desa')
@section('title', 'Invoice')

@section('content')
@include('components.inv-styles')

<style>
.show-grid{display:grid;grid-template-columns:1fr;gap:24px}
@media(min-width:1024px){.show-grid{grid-template-columns:280px 1fr}}
.show-card{background:#fff;border-radius:14px;border:1px solid var(--inv-border);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.show-header{padding:22px 24px;background:linear-gradient(135deg,#1e293b,#334155);color:#fff}
.show-header .lbl{font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.6px}
.show-header .num{font-size:20px;font-weight:800;margin-top:2px}
.show-meta{padding:0 24px 16px}
.show-meta p{font-size:15px;font-weight:700;color:#1e293b}
.show-body{padding:0 24px 20px}
.show-row{display:flex;justify-content:space-between;align-items:center;padding:13px 0;border-bottom:1px solid #f1f5f9}
.show-row:last-child{border-bottom:none}
.show-row .k{font-size:14px;color:var(--inv-text)}
.show-row .v{font-size:14px;font-weight:600;color:#1e293b}
.show-row .v.price{font-size:18px;font-weight:800;color:var(--inv-accent)}
.show-pay{background:#f8fafc;border:1px solid var(--inv-border);border-radius:12px;padding:20px;margin:0 24px 20px}
.show-pay h3{font-size:14px;font-weight:700;color:#1e293b;margin-bottom:10px}
.show-pay .note{font-size:13px;color:var(--inv-text);margin-bottom:10px}
.show-pay .br{display:flex;justify-content:space-between;padding:5px 0;font-size:13px}
.show-pay .br .bk{color:var(--inv-text)}
.show-pay .br .bv{font-weight:600;color:#1e293b;text-align:right;max-width:60%}
.show-upload{margin:0 24px 20px}
.show-upload label{display:block;font-size:14px;font-weight:600;color:#1e293b;margin-bottom:8px}
.drop-zone{border:2px dashed #cbd5e1;border-radius:12px;padding:30px 20px;text-align:center;cursor:pointer;transition:.2s;background:#fafbfc}
.drop-zone:hover{border-color:var(--inv-accent);background:#fffbeb}
.drop-zone.has-file{border-color:var(--inv-green,#10b981);background:#ecfdf5}
.drop-zone i{font-size:28px;color:#94a3b8;display:block;margin-bottom:8px}
.drop-zone p{font-size:13px;color:var(--inv-text)}
.drop-zone .hint{font-size:11px;color:#94a3b8;margin-top:4px}
.drop-zone input{display:none}
.fname{font-size:13px;font-weight:600;color:#1e293b;margin-top:8px;display:none}
.err-msg{font-size:12px;color:var(--inv-red);margin-top:6px}
.show-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:calc(100% - 48px);margin:0 24px 24px;padding:14px;background:var(--inv-accent);color:#fff;font-size:15px;font-weight:700;border:none;border-radius:12px;cursor:pointer;font-family:inherit;transition:.2s}
.show-btn:hover{background:var(--inv-accent-hover);box-shadow:0 4px 12px rgba(245,158,11,.3);transform:translateY(-1px)}

/* Modal Pop-up */
.inv-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;display:flex;align-items:center;justify-content:center;animation:invFadeIn .2s ease}
.inv-modal{background:#fff;border-radius:20px;padding:40px 36px 28px;text-align:center;max-width:380px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.15);animation:invPopIn .3s ease}
.inv-modal .ic{width:64px;height:64px;border-radius:50%;background:#ecfdf5;display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.inv-modal .ic i{font-size:28px;color:#10b981}
.inv-modal h3{font-size:20px;font-weight:800;color:#1e293b;margin:0 0 8px}
.inv-modal p{font-size:14px;color:var(--inv-text);margin:0 0 24px;line-height:1.5}
.inv-modal button{width:100%;padding:12px;background:var(--inv-accent);color:#fff;font-size:15px;font-weight:700;border:none;border-radius:12px;cursor:pointer;font-family:inherit;transition:.2s}
.inv-modal button:hover{background:var(--inv-accent-hover);box-shadow:0 4px 12px rgba(245,158,11,.3)}
@keyframes invFadeIn{from{opacity:0}to{opacity:1}}
@keyframes invPopIn{from{opacity:0;transform:scale(.9) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}

/* Bukti Pembayaran */
.show-bukti{margin:0 24px 24px}
.show-bukti .lbl{font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.6px;color:var(--inv-text);margin-bottom:10px}
.show-bukti img{max-width:100%;max-height:300px;border-radius:12px;border:1px solid var(--inv-border);object-fit:contain}
</style>

<div style="padding:0 24px;max-width:1200px">
    <div class="show-grid">
        <!-- Status Domain -->
        <div>
            <x-status-domain :status="$faktur->status" />
        </div>

        <!-- Konten Utama -->
        <div class="show-card">
            <div class="show-header">
                <div class="lbl">Invoice</div>
                <div class="num">INV-#{{ $faktur->no_invoice }}</div>
            </div>
            <div class="show-meta">
                <p>{{ $faktur->nama_desa }}</p>
            </div>

            <div class="show-body">
                <div class="show-row">
                    <span class="k">Domain</span>
                    <span class="v">{{ $faktur->nama_domain }}.desa.id</span>
                </div>
                <div class="show-row">
                    <span class="k">Aplikasi</span>
                    <span class="v">Registrasi</span>
                </div>
                <div class="show-row">
                    <span class="k">Masa Aktif</span>
                    <span class="v">1 Tahun</span>
                </div>
                <div class="show-row">
                    <span class="k">Harga</span>
                    <span class="v price">Rp {{ number_format($faktur->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="show-pay">
                <h3>Petunjuk Pembayaran</h3>
                <p class="note">Silakan lakukan pembayaran ke rekening berikut:</p>
                <div class="br"><span class="bk">Penerima</span><span class="bv">PANDI (Pengelola Nama Domain Internet Indonesia)</span></div>
                <div class="br"><span class="bk">Bank</span><span class="bv">Bank BCA KCU Sudirman</span></div>
                <div class="br"><span class="bk">No. Rekening</span><span class="bv">888-88-8888</span></div>
            </div>

            @if($faktur->bukti_pembayaran_path)
            <div class="show-bukti">
                <div class="lbl">Bukti Pembayaran</div>
                <img src="{{ Storage::url($faktur->bukti_pembayaran_path) }}" alt="Bukti Pembayaran">
            </div>
            @endif

            @if($faktur->status == 'belum_bayar')
            <form action="{{ route('desa.faktur.konfirmasi', $faktur->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="show-upload">
                    <label>Bukti Pembayaran *</label>
                    <div class="drop-zone" id="dropZone" onclick="document.getElementById('bukti').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik atau seret file ke sini</p>
                        <p class="hint">JPG, PNG maks. 2MB</p>
                        <input type="file" name="bukti_pembayaran" id="bukti" required accept="image/*">
                    </div>
                    <div class="fname" id="fname"></div>
                    @error('bukti_pembayaran')
                        <p class="err-msg">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="show-btn">
                    <i class="fas fa-paper-plane"></i> Kirim
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

{{-- Pop-up Sukses --}}
@if(session('success'))
<div class="inv-overlay" id="successModal">
    <div class="inv-modal">
        <div class="ic"><i class="fas fa-check"></i></div>
        <h3>Sukses</h3>
        <p>{{ session('success') }}</p>
        <button onclick="document.getElementById('successModal').remove()">OK</button>
    </div>
</div>
@endif

@if($faktur->status == 'belum_bayar')
<script>
document.addEventListener('DOMContentLoaded',function(){
    var fi=document.getElementById('bukti'),dz=document.getElementById('dropZone'),fn=document.getElementById('fname');
    fi.addEventListener('change',function(){
        if(this.files.length){fn.textContent=this.files[0].name;fn.style.display='block';dz.classList.add('has-file')}
        else{fn.style.display='none';dz.classList.remove('has-file')}
    });
    dz.addEventListener('dragover',function(e){e.preventDefault();this.style.borderColor='var(--inv-accent)';this.style.background='#fffbeb'});
    dz.addEventListener('dragleave',function(){if(!fi.files.length){this.style.borderColor='';this.style.background=''}});
    dz.addEventListener('drop',function(e){e.preventDefault();fi.files=e.dataTransfer.files;fi.dispatchEvent(new Event('change'))});
});
</script>
@endif
@endsection