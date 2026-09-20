<?php
namespace App\Http\Controllers;
use App\Services\ItemCatalogService;
use App\Services\ProfileService;
use App\Services\TalesDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
class VipController extends Controller {
 public function __construct(private ProfileService $profiles, private TalesDatabase $db, private ItemCatalogService $catalog){}
 public function index(Request $r): View { abort_unless(config('talesrunner.features.vip'),404); $profile=$this->profiles->profile($r); $levels=DB::connection('web')->table('Web_VIP')->orderBy('No')->get(); $rewards=DB::connection('web')->table('Web_VIP_Reward')->orderBy('VIP_Num')->orderBy('Num')->get()->groupBy('VIP_Num'); $claimed=$profile['user_num']?DB::connection('web')->table('Web_VIP_RewardLog')->where('UserNum',$profile['user_num'])->pluck('VIP_Num')->map(fn($v)=>(int)$v)->all():[]; return view('vip.index',compact('profile','levels','rewards','claimed')); }
 public function claim(Request $r): RedirectResponse { $target=(int)$r->validate(['vip'=>'required|integer|min:1|max:100'])['vip']; $profile=$this->profiles->profile($r); if(!$profile['user_num']) throw ValidationException::withMessages(['vip'=>'Please create a character first.']); if($this->profiles->isOnline($profile['user_num'])) throw ValidationException::withMessages(['vip'=>'Please log out of the game before claiming this reward.']); if($target>$profile['vip_level']) throw ValidationException::withMessages(['vip'=>'Your VIP level is too low.']);
  $this->db->transaction(function(TalesDatabase $db) use($target,$profile){ if($db->webTable('Web_VIP_RewardLog')->where('UserNum',$profile['user_num'])->where('VIP_Num',$target)->exists()) throw ValidationException::withMessages(['vip'=>'You have already claimed this reward.']); $rewards=$db->webTable('Web_VIP_Reward')->where('VIP_Num',$target)->orderBy('Num')->get(); if($rewards->isEmpty()) throw ValidationException::withMessages(['vip'=>'No rewards available.']); $db->webTable('Web_VIP_RewardLog')->insert(['UserNum'=>$profile['user_num'],'VIP_Num'=>$target,'VIP_Value'=>0,'VIP_Type'=>-1,'DateTime'=>now()]); foreach($rewards as $reward){ $value=(int)$reward->VIP_Value; switch((int)$reward->VIP_Type){ case 0:$db->gameTable('tblGift')->insert(['fdSendUserNum'=>$profile['user_num'],'fdReceiveUserNum'=>$profile['user_num'],'fdSendNickname'=>$profile['nickname'],'fdGiftItemDescNum'=>$value,'fdNotified'=>1,'fdMemo'=>"Reward item for VIP {$target}"]);break; case 1:$db->gameTable('UserInfoGame')->where('fdUserNum',$profile['user_num'])->increment('fdGameMoney',$value);break; case 2:$db->gameTable('UserInfoFromPublisher')->where('fdUserID',$profile['id'])->increment('fdCash',$value);break; case 3:$db->webTable('Web_User')->where('fdUserID',$profile['id'])->increment('fdPoint',$value);break; } } });
  return back()->with('success','Reward claimed successfully. Check your Gift Box in the Dressing Room.'); }
}
