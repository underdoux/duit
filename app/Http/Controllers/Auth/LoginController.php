<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Routing\Controller as BaseController;

trait AuthenticatesUsers
{
    public function showLoginForm(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request): void
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request): bool
    {
        return Auth::attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function credentials(Request $request): array
    {
        return $request->only($this->username(), 'password');
    }

    protected function sendLoginResponse(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request): void
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function username(): string
    {
        return 'email';
    }

    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/duit/home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        return redirect('/login');
    }
	
    public function authenticate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt([
            'email' => $validated['email'], 
            'password' => $validated['password'], 
            'status' => 'Active'
        ])) {
            $user = DB::table('users')->where('email', $validated['email'])->first();
            if ($user) {
                return redirect()->intended($this->redirectPath());
            }
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => \Lang::get('auth.failed'),
            ]);
    }

    public function showLoginForm(): \Illuminate\View\View
    {
        return view('auth.login');
    }
   

}
