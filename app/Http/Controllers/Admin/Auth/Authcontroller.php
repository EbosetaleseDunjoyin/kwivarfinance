<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class Authcontroller extends Controller
{
        
    /**
     * Show Admin Login Form
     * 
     * showLoginForm
     *
     * @return View
     */
    public function showLoginForm() : View
    {
        return view('auth.admin.login');
    }
    
    /**
     * Show Admin Register Form 
     * 
     * showRegisterForm
     *
     * @return View
     */
    public function showRegisterForm() : View
    {
        return view('auth.admin.register');
    }


    /**
     * Login Admin
     * 
     * login
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function login(Request $request) : RedirectResponse
    {
        // Validate the request data
        $validate = $request->validate([
            'login' => 'required | string', 
            'password' => 'required | string'
        ]);

        // Determine if the login input is an email or a username
        $loginField = filter_var($validate['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Check if the email exists in the admin table
        if($loginField == "email") {
            $admin = Admin::where('email', $validate['login'])->first();

            if(!$admin){
                return redirect()->back()->withErrors(['general' => 'Username/Email doesn`t exist.']);
            }
        }

        
        $credentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];
        

        
        try {
            if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
                return redirect()->route('admin.dashboard');
            } else {
                
                Log::error('Admin authentication failed for email: ' . $credentials['email']);
                return redirect()->back()->withErrors(['general' => 'Invalid credentials']);
            }
        } catch (\Exception $e) {
            
            Log::error('Exception during admin authentication: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Something went wrong. Please try again later.']);
        }
    }


    /**
     * Register Admin
     * register
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function register(Request $request) : RedirectResponse
    {

        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Admin::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        
        if(!$admin){
            return redirect()->back()->withErrors(['general'=>'Issue occured with credentials']);
        }
        $authenticate = Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]);
        
        if ($authenticate) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->withErrors(['general' => 'Issue occured with signing up']);
        }
    }


    
    /**
     * Logout Admin
     * 
     * destroy
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route("admin.login");
    }


}
