<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApiDocsController extends Controller
{
    /**
     * Tampilkan halaman dokumentasi API dan generator token Sanctum.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tokens = $user->tokens()->latest()->get();

        return view('api.docs', [
            'tokens' => $tokens,
            'plainToken' => session('plainToken'),
        ]);
    }

    /**
     * Buat token Sanctum baru untuk user saat ini.
     */
    public function createToken(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|max:50',
        ], [
            'token_name.required' => 'Nama token wajib diisi.',
        ]);

        $user = Auth::user();
        $token = $user->createToken($request->token_name)->plainTextToken;

        return redirect()->route('api.docs')
            ->with('success', 'Token API Sanctum berhasil dibuat!')
            ->with('plainToken', $token);
    }

    /**
     * Hapus token Sanctum.
     */
    public function revokeToken(Request $request, int $id)
    {
        $user = Auth::user();
        $user->tokens()->where('id', $id)->delete();

        return redirect()->route('api.docs')
            ->with('success', 'Token API berhasil dicabut.');
    }
}
