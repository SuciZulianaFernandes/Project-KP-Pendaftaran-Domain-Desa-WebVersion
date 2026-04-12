@props(['status'])

@switch($status)
    @case('belum_bayar')
        <span class="badge bg-warning">Belum Bayar</span>
        @break
    @case('sudah_bayar')
        <span class="badge bg-success">Sudah Bayar</span>
        @break
    @case('kedaluarsa')
        <span class="badge bg-danger">Kedaluarsa</span>
        @break
    @default
        <span class="badge bg-secondary">{{ $status }}</span>
@endswitch