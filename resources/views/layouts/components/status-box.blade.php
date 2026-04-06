@props(['status'])

<div class="bg-white p-4 rounded-lg shadow-md border-l-4 {{ $status === 'dikirim' ? 'border-yellow-500' : ($status === 'menunggu_verifikasi' ? 'border-blue-500' : 'border-green-500') }}">
    <p class="text-sm font-semibold text-gray-600">Status</p>
    <p class="text-lg font-bold">
        @if($status === 'dikirim')
            <span class="text-yellow-600">Pending Payment</span>
        @elseif($status === 'menunggu_verifikasi')
            <span class="text-blue-600">Menunggu Verifikasi</span>
        @elseif($status === 'lunas')
            <span class="text-green-600">Lunas</span>
        @else
            <span class="text-gray-600">{{ ucfirst($status) }}</span>
        @endif
    </p>
</div>