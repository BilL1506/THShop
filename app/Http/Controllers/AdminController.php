<?php
namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class AdminController extends Controller {
 public function index(Request $r): View { $menus=DB::connection('web')->table('Web_ItemShopMenu')->orderBy('MenuNum')->get(); $menu=(int)$r->integer('menu',($menus->first()->MenuNum??0)); $items=DB::connection('web')->table('Web_ItemShop')->when($menu,fn($q)=>$q->where('ItemMenu',$menu))->orderByDesc('Num')->paginate(25)->withQueryString(); return view('admin.index',compact('menus','menu','items')); }
 public function storeMenu(Request $r): RedirectResponse { $d=$r->validate(['name'=>'required|string|max:255']); DB::connection('web')->table('Web_ItemShopMenu')->insert(['MenuName'=>$d['name']]); return back()->with('success','Shop menu created.'); }
 public function updateMenu(Request $r,int $menu): RedirectResponse { $d=$r->validate(['name'=>'required|string|max:255']); DB::connection('web')->table('Web_ItemShopMenu')->where('MenuNum',$menu)->update(['MenuName'=>$d['name']]); return back()->with('success','Shop menu updated.'); }
 public function storeItem(Request $r): RedirectResponse { $d=$this->itemData($r); $d['DateTime']=now(); DB::connection('web')->table('Web_ItemShop')->insert($d); return back()->with('success','Shop item created.'); }
 public function updateItem(Request $r,int $item): RedirectResponse { DB::connection('web')->table('Web_ItemShop')->where('Num',$item)->update($this->itemData($r)); return back()->with('success','Shop item updated.'); }
 public function deleteItem(int $item): RedirectResponse { DB::connection('web')->table('Web_ItemShop')->where('Num',$item)->update(['ItemDelete'=>1]); return back()->with('success','Shop item hidden.'); }
 private function itemData(Request $r): array { $d=$r->validate(['ItemNum'=>'required|integer|min:1','ItemName'=>'required|string|max:255','ItemPrice'=>'required|integer|min:0','ItemMenu'=>'required|integer|min:1','ItemLimit'=>'nullable|boolean','ItemCount'=>'nullable|integer|min:0','ItemVIP'=>'nullable|integer|min:0|max:100','ItemDateTime'=>'nullable|boolean','Item_DateStart'=>'nullable|date','Item_DateEnd'=>'nullable|date|after_or_equal:Item_DateStart']); return ['ItemNum'=>$d['ItemNum'],'ItemName'=>$d['ItemName'],'ItemPrice'=>$d['ItemPrice'],'ItemMenu'=>$d['ItemMenu'],'ItemDelete'=>0,'ItemLimit'=>(int)($d['ItemLimit']??0),'ItemCount'=>(int)($d['ItemCount']??0),'ItemVIP'=>(int)($d['ItemVIP']??0),'ItemDateTime'=>(int)($d['ItemDateTime']??0),'Item_DateStart'=>$d['Item_DateStart']??null,'Item_DateEnd'=>$d['Item_DateEnd']??null]; }
}
