<?php
namespace App\Http\Controllers;
use App\Services\TalesDatabase;
use App\Services\TalesRunnerAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class AuthController extends Controller {
 public function __construct(private TalesRunnerAuth $auth, private TalesDatabase $db){}
 public function loginForm(Request $r): View|RedirectResponse { if($this->auth->check($r)) return redirect()->route('home'); return view('auth.login'); }
 public function login(Request $r): RedirectResponse { $d=$r->validate(['account'=>'required|string|max:64','password'=>'required|string|max:64']); if(!$this->auth->attempt($r,$d['account'],$d['password'])) return back()->withErrors(['account'=>'Incorrect username or password.'])->onlyInput('account'); return redirect()->route('home'); }
 public function registerForm(): View { abort_unless(config('talesrunner.features.register'),404); return view('auth.register'); }
 public function register(Request $r): RedirectResponse { abort_unless(config('talesrunner.features.register'),404); $d=$r->validate(['account'=>['required','alpha_num','min:4','max:14'],'password'=>['required','string','min:3','max:14','confirmed']]); $this->auth->register($r,$this->db,$d['account'],$d['password']); return redirect()->route('home')->with('success','Account created successfully.'); }
 public function logout(Request $r): RedirectResponse { $this->auth->logout($r); return redirect()->route('login'); }
 public function passwordForm(): View { return view('auth.password'); }
 public function password(Request $r): RedirectResponse { $d=$r->validate(['password_current'=>'required|string','password'=>'required|string|min:3|max:14|confirmed']); $this->auth->changePassword($r,$d['password_current'],$d['password']); return back()->with('success','Password changed successfully.'); }
}
