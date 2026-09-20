<?php
namespace App\Http\Middleware;
use App\Services\ProfileService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureTalesRunnerAdmin {
 public function __construct(private ProfileService $profiles){}
 public function handle(Request $request, Closure $next): Response { $p=$this->profiles->profile($request); abort_unless($p['admin'],403); return $next($request); }
}
