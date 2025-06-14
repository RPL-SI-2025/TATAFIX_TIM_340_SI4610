<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'address'  => 'required|string|max:255',
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
            'password.regex' => 'Format kata sandi tidak valid. Kata sandi harus mengandung setidaknya satu huruf kecil, satu huruf kapital, satu angka, dan satu karakter khusus.',
            'email.email' => 'Mohon Isikan Format Email dengan benar',
            'email.unique' => 'Email yang anda daftarkan sudah tersedia',
            'name.unique' => 'Nama yang anda daftarkan sudah tersedia',
        ]);

        if ($validator->fails()) {
            return redirect('/register')
                   ->withErrors($validator)
                   ->withInput($request->except(['password', 'password_confirmation'])); // Tambahkan ini
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
            
            return redirect('/email/verify')->with('success', 'Registrasi berhasil dan silahkan cek email!');
        }
    }
}
