<?php
namespace App\Http\Controllers;
use App\Services\ItemCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class GameInfoController extends Controller {
 public function __construct(private ItemCatalogService $catalog){}
 public function index(Request $r): View { $menus=DB::connection('web')->table('Web_AlchemistMenu')->orderBy('Sub')->get(); $sub=(int)$r->integer('sub',($menus->first()->Sub??8000)); $recipes=DB::connection('game')->table('tblAlchemist_recipe')->where('fdItemCategory',$sub)->orderBy('fdRecipeNum')->paginate(20)->withQueryString(); return view('game.alchemist',compact('menus','sub','recipes')); }
}
