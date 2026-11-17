<?php

namespace App\Http\Controllers;

use App\Models\AuthorProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showUserLogin(): View
    {
        return view('auth.user-login');
    }

    public function showAuthorLogin(): View
    {
        return view('auth.portal-login', [
            'portalRole' => 'author',
            'portalTitle' => 'Author Studio Sign In',
            'demoEmail' => 'author@gmail.com',
            'demoPassword' => 'password',
        ]);
    }

    public function showAdminLogin(): View
    {
        return view('auth.portal-login', [
            'portalRole' => 'admin',
            'portalTitle' => 'Admin Console Sign In',
            'demoEmail' => 'admin@gmail.com',
            'demoPassword' => 'password',
        ]);
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            /** @var User $user */
            $user = Auth::user();

            if ($user->status === 'banned') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account has been suspended by an administrator.',
                ])->onlyInput('email');
            }

            return redirect()->intended($user->dashboardRoute())
                ->with('success', 'Welcome back, '.$user->name.'!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['nullable', 'string', 'max:100', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'register_as_author' => ['nullable', 'boolean'],
        ]);

        $username = $validated['username'] ?? null;
        if (! $username) {
            $base = Str::slug($validated['name']) ?: 'reader';
            $username = $base;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $base.'-'.$counter++;
            }
        }

        $wantsAuthor = $request->boolean('register_as_author');
        $roleId = $wantsAuthor ? 2 : 3;

        $user = User::create([
            'role_id' => $roleId,
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'avatar' => $wantsAuthor ? 'assets/img/avatar-author.svg' : 'assets/img/avatar-user.svg',
            'bio' => $wantsAuthor ? 'Contributing writer at Zaira News Portal.' : 'Reader at Zaira News Portal.',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        if ($wantsAuthor) {
            AuthorProfile::create([
                'user_id' => $user->id,
                'designation' => 'Contributing Author',
                'about' => $user->bio,
                'is_approved' => true,
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect($user->dashboardRoute())
            ->with('success', 'Account created successfully! Welcome to Zaira News Portal.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }

    /**
     * Quick 1-click Demo Role Switcher (for university project defense & QA testing).
     */
    public function demoSwitch(Request $request, string $role): RedirectResponse
    {
        $email = match ($role) {
            'admin' => 'admin@gmail.com',
            'author' => 'author@gmail.com',
            default => 'user@gmail.com',
        };

        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Demo user not found. Please run database seeder.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect($user->dashboardRoute())
            ->with('success', 'Switched to demo account: '.$user->name.' ('.strtoupper($role).')');
    }
}
