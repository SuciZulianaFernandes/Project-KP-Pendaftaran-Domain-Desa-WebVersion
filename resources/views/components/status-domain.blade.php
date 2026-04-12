@props(['status' => 'draft'])

<?php
 $map = [
    'belum_bayar'     => ['label' => 'Belum Dibayar',  'color' => '#f97316'],
    'perlu_perbaikan' => ['label' => 'Perlu Perbaikan',  'color' => '#8B0000'],
    'draft'           => ['label' => 'Draft',            'color' => '#64748b'],
    'ditinjau'        => ['label' => 'Ditinjau',         'color' => '#eab308'],
    'diproses'        => ['label' => 'Diproses',         'color' => '#16a34a'],
    'sudah_bayar'     => ['label' => 'Sudah Dibayar',  'color' => '#22c55e'],
    'diverifikasi'    => ['label' => 'Aktif',           'color' => '#10b981'],
];
 $s = $map[$status] ?? $map['draft'];
?>

<div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:24px">
    <div style="font-size:13px;color:#64748b;margin-bottom:16px">Status Domain</div>
    <div style="display:flex;align-items:center;gap:8px;font-size:14px">
        <span style="width:6px;height:6px;border-radius:50%;background:#1e293b;flex-shrink:0"></span>
        <span style="color:#1e293b">Status :</span>
        <span style="font-weight:600;color:{{ $s['color'] }}">{{ $s['label'] }}</span>
    </div>
</div>