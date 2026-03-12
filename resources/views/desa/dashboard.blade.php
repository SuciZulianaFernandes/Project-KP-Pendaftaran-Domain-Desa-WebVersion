@extends('layouts.desa')

@section('title','Dashboard')

@section('content')

<div class="space-y-6">

    {{-- STAT CARD --}}
    <div class="grid grid-cols-3 gap-6">

        <div class="bg-blue-100 p-6 rounded-xl">
            <p class="text-gray-600 text-sm">Total Domain .desa.id</p>
            <h2 class="text-3xl font-bold mt-2">8</h2>
        </div>

        <div class="bg-blue-100 p-6 rounded-xl">
            <p class="text-gray-600 text-sm">Total Domain Aktif</p>
            <h2 class="text-3xl font-bold mt-2">3</h2>
        </div>

        <div class="bg-blue-100 p-6 rounded-xl">
            <p class="text-gray-600 text-sm">Total Domain Aktif</p>
            <h2 class="text-3xl font-bold mt-2">3</h2>
        </div>

    </div>


    {{-- ILUSTRASI --}}
    <div class="bg-white p-10 rounded-xl shadow text-center">

        <img src="https://cdn-icons-png.flaticon.com/512/4661/4661320.png"
             class="mx-auto w-80 opacity-70">

    </div>

</div>

@endsection