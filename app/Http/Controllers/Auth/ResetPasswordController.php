<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Simulasi kirim email: response JSON dulu
        return response()->json([
            'message' => 'Token reset berhasil dibuat',
            'token' => $token
        ]);
    }

    public function reset(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'token' => 'required|string',
        'password' => 'required|confirmed|min:6',
    ]);

    $record = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

    if (!$record) {
        return response()->json(['message' => 'Token tidak valid'], 400);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan'], 404);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    // hapus token setelah reset sukses
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Password berhasil direset']);
}
}
