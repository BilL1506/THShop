<?php
namespace App\Http\Middleware;
use App\Services\TalesRunnerAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureTalesRunnerAuthenticated {
 public function __construct(private TalesRunnerAuth $auth){}
 public function handle(Request $request, Closure $next): Response { if(!$this->auth->check($request)) return redirect()->route('login')->with('error','Please log in first.'); return $next($request); }
}
