@extends('layouts.admin')

@section('title','Profil')

@section('content')

<div class="max-w-xl">

<div class="bg-white rounded-xl shadow p-6">

<div class="flex justify-between items-center mb-6">
<h2 class="text-lg font-semibold">Profil</h2>

<form action="/logout" method="POST">
@csrf
<button @click="logoutConfirm = true"
class="text-red-600 border border-red-200 px-3 py-1 rounded hover">
Keluar →
</button>
</form>
</div>

<form action="/admin/profile" method="POST">
@csrf

<!-- Nama Admin -->
<label class="text-sm text-gray-600">Nama Admin</label>
<input 
type="text"
name="nama_lengkap"
value="{{ auth()->user()->nama_lengkap }}"
class="w-full border rounded-lg px-3 py-2 mb-3"
>

<!-- Username -->
<label class="text-sm text-gray-600">Username</label>
<input 
type="text"
name="username"
value="{{ auth()->user()->username }}"
class="w-full border rounded-lg px-3 py-2 mb-3"
>

<!-- Email -->
<label class="text-sm text-gray-600">Email</label>
<input 
type="email"
name="email"
value="{{ auth()->user()->email }}"
class="w-full border rounded-lg px-3 py-2 mb-3"
>

<!-- No HP -->
<label class="text-sm text-gray-600">No. Hp</label>
<input 
type="text"
name="no_hp"
value="{{ auth()->user()->no_hp }}"
class="w-full border rounded-lg px-3 py-2 mb-4"
>

<h3 class="font-medium mb-2">Ubah Password</h3>

<input type="password" name="password_lama"
placeholder="Password Lama"
class="w-full border rounded-lg px-3 py-2 mb-2">

<input type="password" name="password_baru"
placeholder="Password Baru"
class="w-full border rounded-lg px-3 py-2 mb-2">

<input type="password" name="password_baru_confirmation"
placeholder="Konfirmasi Password"
class="w-full border rounded-lg px-3 py-2 mb-6">

<button class="bg-red-700 hover:bg-red-800 text-white px-6 py-2 rounded-lg">
Simpan Perubahan
</button>

</form>

</div>
</div>

<button
@click="logoutConfirm = false"
class="px-4 py-2 rounded border border-gray-300 hover">
Batal

@endsection