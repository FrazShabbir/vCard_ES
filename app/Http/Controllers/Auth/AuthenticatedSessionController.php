<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
      
       return view('frontend.pages.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
       

        $request->authenticate();
        if (Auth::user()->status == 1 ) {
            if(Auth::user()->hasRole('Member')){
                $request->session()->regenerate();
                return redirect()->intended(RouteServiceProvider::HOME);

                // return redirect()->route('editProfile',Auth::user()->username);
            }else{
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            }
          

        } elseif(Auth::user()->status == 0 ) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            Session::flash('error','Your Account is not Active. Please Contact Admin');
            return view('backend.auth.login');

        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
