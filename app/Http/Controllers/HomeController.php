<?php
namespace App\Http\Controllers;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class HomeController extends Controller {
 public function __construct(private ProfileService $profiles){}
 public function __invoke(Request $r): View {
  $profile=$this->profiles->profile($r);
  $online=DB::connection('game')->table('UserInfoLogin')->where('fdServerNum','!=',0)->count();
  $players=DB::connection('game')->table('UserInfoLogin as l')->leftJoin('UserInfo as u','u.fdUserNum','=','l.fdUserNum')->where('l.fdServerNum','!=',0)->limit(50)->pluck('u.fdNickname')->filter()->values();
  $members=DB::connection('game')->table('UserInfoFromPublisher')->count();
  $keys=['MultiplyEXP','MultiplyTR','BonusPetExp','DoubleExp','DoubleTR'];
  $settings=DB::connection('game')->table('tblServerSettingInfo')->whereIn('fdKey',$keys)->pluck('fdValue','fdKey');
  $errno=0;$errstr='';$socket=@fsockopen(config('talesrunner.server_host'),config('talesrunner.agent_port'),$errno,$errstr,0.5);$serverOnline=(bool)$socket;if($socket)fclose($socket);
  return view('home',compact('profile','online','players','members','settings','serverOnline'));
 }
}
