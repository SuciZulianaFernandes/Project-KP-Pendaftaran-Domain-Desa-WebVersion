<div class="inv-toolbar">
    <div class="inv-search">
        <i class="fas fa-search"></i>
        <input type="text" id="invSearch" placeholder="{{ $placeholder ?? 'Cari no. invoice...' }}" autocomplete="off">
    </div>
    <select id="invFilter" class="inv-btn-f">
        <option value="">Semua Status</option>
        <option value="belum_bayar">Belum Dibayar</option>
        <!-- <option value="sudah_bayar">Menunggu Verifikasi</option> -->
        <option value="diverifikasi">Sudah Dibayar</option>
    </select>
</div>