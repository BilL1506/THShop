<?php
namespace App\Http\Controllers;
use App\Services\TalesDatabase;
use App\Services\TalesRunnerAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class MeetaController extends Controller {
 public function __construct(private TalesDatabase $db){}
 public function __invoke(Request $r): RedirectResponse {
  abort_unless(config('talesrunner.features.meeta_legacy') && config('talesrunner.meeta_password'),404);
  $meeta=base64_decode((string)$r->query('i',''),true); if($meeta===false||!preg_match('/^[A-Za-z0-9_.@-]{1,128}$/',$meeta)) return redirect()->route('login')->with('error','Invalid MEETA identity.');
  $web=DB::connection('web')->table('Web_User')->where('MEETA_Account',$meeta)->first(); $id=$web?->fdUserID;
  if(!$id){$id=DB::connection('web')->table('Web_User')->where('fdUserID',$meeta)->exists()?strtolower(Str::random(12)):$meeta;$hash=md5(config('talesrunner.meeta_password'));$this->db->transaction(function(TalesDatabase $db)use($id,$meeta,$hash){$db->gameTable('UserInfoFromPublisher')->insert(['fdUserID'=>$id,'fdGameID'=>$id,'fdPassword'=>$hash,'fdCash'=>config('talesrunner.register_cash')]);$db->webTable('Web_User')->insert(['fdUserID'=>$id,'MEETA_Account'=>$meeta]);});} else {$hash=(string)DB::connection('game')->table('UserInfoFromPublisher')->where('fdUserID',$id)->value('fdPassword');}
  $r->session()->regenerate();$r->session()->put(TalesRunnerAuth::USER_KEY,$id);$r->session()->put(TalesRunnerAuth::HASH_KEY,$hash);return redirect()->route('home');
 }
}
