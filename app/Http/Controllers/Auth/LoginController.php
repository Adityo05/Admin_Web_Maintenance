<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (Session::has('user')) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        try {
            // Cari user berdasarkan email
            $karyawan = Karyawan::where('email', $request->email)->first();

            if (!$karyawan) {
                \Log::warning('Login failed: User not found', ['email' => $request->email]);
                return back()->withErrors([
                    'email' => 'Email atau password salah'
                ])->withInput($request->only('email'));
            }

            // Cek apakah user aktif
            if (!$karyawan->is_active) {
                \Log::warning('Login failed: User inactive', ['email' => $request->email]);
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Hubungi administrator.'
                ])->withInput($request->only('email'));
            }

            // Verifikasi password
            // Support untuk $2b$ (bcrypt dari Node.js) dan $2y$ (bcrypt dari PHP)
            $passwordHash = $karyawan->password_hash;
            
            // Konversi $2b$ ke $2y$ jika diperlukan (keduanya kompatibel)
            if (str_starts_with($passwordHash, '$2b$')) {
                $passwordHash = '$2y$' . substr($passwordHash, 4);
            }
            
            if (!Hash::check($request->password, $passwordHash)) {
                \Log::warning('Login failed: Invalid password', ['email' => $request->email]);
                return back()->withErrors([
                    'email' => 'Email atau password salah'
                ])->withInput($request->only('email'));
            }

            // Ambil daftar aplikasi yang bisa diakses user
            $karyawanAplikasi = DB::table('karyawan_aplikasi')
                ->join('aplikasi', 'karyawan_aplikasi.aplikasi_id', '=', 'aplikasi.id')
                ->where('karyawan_aplikasi.karyawan_id', $karyawan->id)
                ->select('karyawan_aplikasi.role', 'aplikasi.kode_aplikasi')
                ->get();

            $availableApps = $karyawanAplikasi->map(function ($ka) {
                return [
                    'kode_aplikasi' => $ka->kode_aplikasi,
                    'role' => $ka->role
                ];
            })->toArray();

            \Log::info('Login attempt: Available apps', ['email' => $request->email, 'apps' => $availableApps]);

            // Cek apakah user memiliki akses admin (role: Superadmin, Manajer, Admin, KASIE Teknisi)
            $adminRoles = ['Superadmin', 'Manajer', 'Admin', 'KASIE Teknisi'];
            $hasAdminAccess = false;
            $userRole = null;

            foreach ($availableApps as $app) {
                if ($app['kode_aplikasi'] === 'MT' && in_array($app['role'], $adminRoles)) {
                    $hasAdminAccess = true;
                    $userRole = $app['role'];
                    break;
                }
            }

            if (!$hasAdminAccess) {
                \Log::warning('Login failed: No admin access', ['email' => $request->email, 'apps' => $availableApps]);
                return back()->withErrors([
                    'email' => 'Akses ditolak. Aplikasi ini hanya untuk admin.'
                ])->withInput($request->only('email'));
            }

            // Simpan data user di session
            Session::put('user', [
                'id' => $karyawan->id,
                'email' => $karyawan->email,
                'full_name' => $karyawan->full_name ?? explode('@', $karyawan->email)[0],
                'role' => $userRole,
            ]);

            \Log::info('Login successful', ['email' => $request->email, 'role' => $userRole]);

            // Redirect ke dashboard
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage(), [
                'email' => $request->email,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors([
                'email' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput($request->only('email'));
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        Session::forget('user');
        Session::flush();
        
        return redirect()->route('login');
    }
}
