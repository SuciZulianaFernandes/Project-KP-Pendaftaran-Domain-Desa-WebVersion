@extends('layouts.admin')

@section('title', 'Detail Faktur')

@section('content')

@include('components.inv-styles')

<style>
.show-grid{display:grid;grid-template-columns:1fr;gap:24px}
@media(min-width:1024px){.show-grid{grid-template-columns:280px 1fr}}

.show-card{
    background:#fff;
    border-radius:14px;
    border:1px solid var(--inv-border);
    overflow:hidden;
    box-shadow:0 1px 3px rgba(0,0,0,.04)
}

.show-header{
    padding:22px 24px;
    background:linear-gradient(135deg,#1e293b,#334155);
    color:#fff
}

.show-header .lbl{
    font-size:11px;
    opacity:.6;
    text-transform:uppercase;
    letter-spacing:.6px
}

.show-header .num{
    font-size:20px;
    font-weight:800;
    margin-top:2px
}

.show-meta{
    padding:0 24px 16px
}

.show-meta p{
    font-size:15px;
    font-weight:700;
    color:#1e293b
}

.show-body{
    padding:0 24px 20px
}

.show-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:13px 0;
    border-bottom:1px solid #f1f5f9
}

.show-row:last-child{
    border-bottom:none
}

.show-row .k{
    font-size:14px;
    color:var(--inv-text)
}

.show-row .v{
    font-size:14px;
    font-weight:600;
    color:#1e293b;
    text-align:right
}

.show-row .v.price{
    font-size:18px;
    font-weight:800;
    color:var(--inv-accent)
}

.show-box{
    background:#f8fafc;
    border:1px solid var(--inv-border);
    border-radius:12px;
    padding:20px;
    margin:0 24px 20px
}

.show-box h3{
    font-size:14px;
    font-weight:700;
    color:#1e293b;
    margin-bottom:12px
}

.show-box .item{
    display:flex;
    justify-content:space-between;
    padding:7px 0;
    border-bottom:1px solid #e2e8f0;
    gap:16px
}

.show-box .item:last-child{
    border-bottom:none
}

.show-box .item .k{
    font-size:13px;
    color:var(--inv-text)
}

.show-box .item .v{
    font-size:13px;
    font-weight:600;
    color:#1e293b;
    text-align:right
}

.status-badge{
    padding:5px 12px;
    border-radius:9999px;
    font-size:12px;
    font-weight:700;
    color:#fff
}

.status-warning{background:#f59e0b}
.status-success{background:#10b981}
.status-danger{background:#ef4444}

.note-box{
    background:#fff;
    border:1px solid var(--inv-border);
    border-radius:10px;
    padding:14px;
    font-size:14px;
    color:#475569;
    line-height:1.6
}

.show-bukti{
    margin:0 24px 24px
}

.show-bukti .lbl{
    font-size:12px;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:.6px;
    color:var(--inv-text);
    margin-bottom:10px
}

.show-bukti img{
    max-width:100%;
    max-height:320px;
    border-radius:12px;
    border:1px solid var(--inv-border);
    object-fit:contain
}

.back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    color:#2563eb;
    font-size:14px;
    font-weight:600;
    margin-bottom:20px
}

.back-btn:hover{
    text-decoration:underline
}
</style>

<div style="padding:0 24px;max-width:1200px">

    <div class="show-grid">

        <!-- CARD KIRI -->
        <div>
            <x-status-domain :status="$faktur->status" />
        </div>

        <!-- CARD KANAN -->
        <div class="show-card">

            <!-- HEADER -->
             <a href="{{ url()->previous() }}"
   class=" text-black font-bold py-2 px-4 rounded inline-flex items-center justify-center">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
            <div class="show-header">
                <div class="lbl">Invoice</div>
                <div class="num">INV-#{{ $faktur->no_invoice }}</div>
            </div>

            <!-- META -->
            <div class="show-meta">
                <p>{{ $faktur->nama_desa }}</p>
            </div>

            <!-- BODY -->
            <div class="show-body">

                <div class="show-row">
                    <span class="k">Domain</span>
                    <span class="v">{{ $faktur->nama_domain }}.desa.id</span>
                </div>

                <div class="show-row">
                    <span class="k">No. Invoice</span>
                    <span class="v">{{ $faktur->no_invoice }}</span>
                </div>

                <div class="show-row">
                    <span class="k">Jenis Aplikasi</span>
                    <span class="v">Informasi Desa</span>
                </div>

                <div class="show-row">
                    <span class="k">Durasi</span>
                    <span class="v">1 Tahun</span>
                </div>

                <div class="show-row">
                    <span class="k">Total Pembayaran</span>
                    <span class="v price">
                        Rp {{ number_format($faktur->total, 0, ',', '.') }}
                    </span>
                </div>

            </div>

            <!-- INFORMASI PEMBAYARAN -->
            <div class="show-box">
                <h3>Informasi Pembayaran</h3>

                <div class="item">
                    <span class="k">Tanggal Terbit</span>
                    <span class="v">
                        {{ $faktur->created_at->format('d F Y') }}
                    </span>
                </div>

                @if($faktur->status == 'sudah_bayar')
                <div class="item">
                    <span class="k">Tanggal Pembayaran</span>
                    <span class="v">
                        {{ $faktur->tanggal_konfirmasi ? $faktur->tanggal_konfirmasi->format('d F Y') : $faktur->updated_at->format('d F Y') }}
                    </span>
                </div>
                @endif

                <div class="item">
                    <span class="k">Batas Pembayaran</span>
                    <span class="v">
                        {{ $faktur->expired_at->format('d F Y') }}
                    </span>
                </div>
            </div>

            <!-- CATATAN -->
            @if($faktur->catatan)
            <div class="show-box">
                <h3>Catatan</h3>

                <div class="note-box">
                    {{ $faktur->catatan }}
                </div>
            </div>
            @endif

            <!-- BUKTI PEMBAYARAN -->
            @if($faktur->status == 'sudah_bayar' && $faktur->bukti_pembayaran_path)
            <div class="show-bukti">
                <div class="lbl">Bukti Pembayaran</div>

                <img src="{{ asset('storage/' . $faktur->bukti_pembayaran_path) }}"
                     alt="Bukti Pembayaran">
            </div>
            @endif

        </div>
    </div>
</div>
@endsection