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
use RuntimeException;
class ShopController extends Controller {
 public function __construct(private ProfileService $profiles, private TalesDatabase $db, private ItemCatalogService $catalog){}
 public function index(Request $r): View { abort_unless(config('talesrunner.features.shop'),404); $profile=$this->profiles->profile($r); $menus=DB::connection('web')->table('Web_ItemShopMenu')->orderBy('MenuNum')->get(); $menu=(int)$r->integer('menu',($menus->first()->MenuNum??0)); $sort=$r->string('sort','last')->toString(); $q=DB::connection('web')->table('Web_ItemShop')->where('ItemMenu',$menu)->where('ItemDelete',0); $sort==='hot'?$q->orderByDesc('ItemHot'):$q->orderByDesc('Num'); $items=$q->paginate(12)->withQueryString(); return view('shop.index',compact('profile','menus','menu','sort','items')); }
 public function buy(Request $r): RedirectResponse { $id=(int)$r->validate(['item'=>'required|integer|min:1'])['item']; $profile=$this->profiles->profile($r); if(!$profile['user_num']||$profile['nickname']==='No character yet') throw ValidationException::withMessages(['item'=>'Please create a character first.']);
  $newPoint=$this->db->transaction(function(TalesDatabase $db) use($id,$profile){ $item=$db->webTable('Web_ItemShop')->where('Num',$id)->lockForUpdate()->first(); if(!$item||$item->ItemDelete) throw ValidationException::withMessages(['item'=>'Item not found.']); $user=$db->webTable('Web_User')->where('fdUserID',$profile['id'])->lockForUpdate()->first(); if(!$user) throw new RuntimeException('Web user not found.'); if($item->ItemLimit && $item->ItemCount<=0) throw ValidationException::withMessages(['item'=>'This item is sold out.']); if($item->ItemVIP>$profile['vip_level']) throw ValidationException::withMessages(['item'=>'Your VIP level is too low for this item.']); if((int)$user->fdPoint<(int)$item->ItemPrice) throw ValidationException::withMessages(['item'=>'You do not have enough Points.']); if($item->ItemDateTime){ $now=now(); if($item->Item_DateStart&&$now->lt($item->Item_DateStart)) throw ValidationException::withMessages(['item'=>'This item is not on sale yet.']); if($item->Item_DateEnd&&$now->gt($item->Item_DateEnd)) throw ValidationException::withMessages(['item'=>'This item is no longer available.']); }
   if($item->ItemLimit){ $changed=$db->webTable('Web_ItemShop')->where('Num',$id)->where('ItemCount','>',0)->decrement('ItemCount'); if($changed!==1) throw ValidationException::withMessages(['item'=>'This item is sold out.']); }
   $before=(int)$user->fdPoint; $after=$before-(int)$item->ItemPrice; $db->webTable('Web_User')->where('fdUserID',$profile['id'])->update(['fdPoint'=>$after]); $db->webTable('Web_ItemShop')->where('Num',$id)->increment('ItemHot'); $db->webTable('Log_BuyItems')->insert(['ItemOrder'=>$id,'ItemNum'=>(int)$item->ItemNum,'ItemPrice'=>(int)$item->ItemPrice,'UserID'=>$profile['id'],'UserPoint_Before'=>$before,'UserPoint_After'=>$after,'DateTime'=>now()]); $db->gameTable('tblGift')->insert(['fdSendUserNum'=>$profile['user_num'],'fdReceiveUserNum'=>$profile['user_num'],'fdSendNickname'=>$profile['nickname'],'fdGiftItemDescNum'=>(int)$item->ItemNum,'fdNotified'=>1,'fdMemo'=>"Item Buy Success. #{$id}"]); return $after; });
  return back()->with('success',"Item purchased successfully. Remaining Points: ".number_format($newPoint)); }
}
