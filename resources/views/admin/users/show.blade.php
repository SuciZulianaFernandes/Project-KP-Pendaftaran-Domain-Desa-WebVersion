@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Detail User</h1>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <!-- Informasi Akun (Selalu Muncul) -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Informasi Akun</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">ID User:</dt>
                    <dd class="w-2/3 text-sm text-gray-900">{{ $user->id_user }}</dd>
                </div>
                <div class="flex">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Username:</dt>
                    <dd class="w-2/3 text-sm text-gray-900">{{ $user->username }}</dd>
                </div>
                <div class="flex">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Role:</dt>
                    <dd class="w-2/3 text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </dd>
                </div>
                
                <div class="flex">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Email:</dt>
                    <dd class="w-2/3 text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                
            </dl>
        </div>

        <!-- Detail Khusus Admin -->
        @if($user->role === 'admin')
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Detail Informasi Pribadi</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Nama Lengkap:</dt>
                    <dd class="w-2/3 text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                <div class="flex">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">No. HP:</dt>
                    <dd class="w-2/3 text-sm text-gray-900">{{ $user->no_hp ?? '-' }}</dd>
                </div>
            </dl>
        </div>
        @endif

        <!-- Detail Khusus Desa -->
        @if($user->role === 'desa' && $user->desa)
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Detail Informasi Desa</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Nama Desa:</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $user->desa->nama_desa }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Nama Kepala Desa:</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $user->desa->nama_kepala_desa ?? '-' }}</dd>
                    </div>
                    <div class="flex md:col-span-2">
                        <dt class="w-1/6 text-sm font-medium text-gray-500">NIP Kepala Desa:</dt>
                        <dd class="w-5/6 text-sm text-gray-900">{{ $user->desa->nip_kepala_desa ?? '-' }}</dd>
                    </div>
                    <!-- Tambahkan field desa lainnya sesuai kebutuhan -->
                     <div class="flex md:col-span-2">
                        <dt class="w-1/6 text-sm font-medium text-gray-500">Provinsi:</dt>
                        <dd class="w-5/6 text-sm text-gray-900">{{ $user->desa->provinsi ?? '-' }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Klasifikasi:</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $user->desa->klasifikasi_instansi ?? '-' }}</dd>
                    </div>
                    <div class="flex md:col-span-2">
                        <dt class="w-1/6 text-sm font-medium text-gray-500">Alamat:</dt>
                        <dd class="w-5/6 text-sm text-gray-900">{{ $user->desa->alamat ?? '-' }}</dd>
                    </div>
                    
                    <div class="flex md:col-span-2">
                        <dt class="w-1/6 text-sm font-medium text-gray-500">Kab/Kota:</dt>
                        <dd class="w-5/6 text-sm text-gray-900">{{ $user->desa->kota_kabupaten ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        @endif

        <!-- Informasi Waktu -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Informasi Waktu</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Dibuat:</dt>
                    <dd class="w-2/3 text-sm text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</dd>
                </div>
                <div class="flex">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Diupdate:</dt>
                    <dd class="w-2/3 text-sm text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-8 flex justify-end border-t pt-6">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection