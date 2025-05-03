<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255', 'unique:users,name'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20'],
            'address'  => ['required', 'string', 'max:255'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/'
            ],
        ], [
            'name.required' => 'Nama Wajib Diisikan',
            'email.required' => 'Alamat Email Wajib Diisikan',
            'phone.required' => 'No HP Wajib Diisikan',
            'address.required' => 'Alamat Wajib Diisikan',
            'password.required' => 'Password Wajib Diisikan',
            'password.min' => 'Kata sandi minimal harus terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
            'password.regex' => 'Kata sandi harus memiliki minimal satu huruf besar, satu huruf kecil, satu angka, dan satu karakter spesial seperti (@,#,_,!).',
            'email.email' => 'Mohon Isikan Format Email dengan benar',
            'email.unique' => 'Email yang anda daftarkan sudah tersedia!',
            'name.unique' => 'Nama yang anda daftarkan sudah tersedia!',
        ]);

        if ($validator->fails()) {
            return redirect('/register')->withErrors($validator);
        } else {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'address'  => $request->address,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole('customer');

            event(new Registered($user));

            Auth::login($user);

            return redirect('/email/verify')->with('success', 'Registrasi berhasil dan silahkan cek emai!');
        }
    }
}
