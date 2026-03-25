<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

public function edit()
{
return view('admin.profile');
}

public function update(Request $request)
{

$user = Auth::user();

$request->validate([
'username'=>'required',
'password_lama'=>'required',
'password_baru'=>'nullable|confirmed'
]);

if(!Hash::check($request->password_lama,$user->password)){
return back()->with('error','Password lama salah');
}

$user->username = $request->username;

if($request->password_baru){
$user->password = Hash::make($request->password_baru);
}

$user->save();

return back()->with('success','Profil berhasil diperbarui');

}

}