<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class DownloadController extends Controller { public function __invoke(): View { abort_unless(config('talesrunner.features.download'),404); $files=DB::connection('web')->table('Web_Download')->orderBy('Num')->get(); return view('download',compact('files')); } }
