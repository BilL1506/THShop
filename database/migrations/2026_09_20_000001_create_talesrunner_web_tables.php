<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 protected $connection='web';
 public function up(): void {
  $s=Schema::connection('web');
  if(!$s->hasTable('Web_User'))$s->create('Web_User',function(Blueprint $t){$t->string('fdUserID',64)->primary();$t->unsignedBigInteger('fdPoint')->default(0);$t->unsignedBigInteger('fdEXP_VIP')->default(0);$t->tinyInteger('fdAdmin')->default(0);$t->string('MEETA_Account',128)->nullable()->index();$t->string('fdProfile_Picture',1024)->nullable();$t->integer('fdRank')->default(0);});
  if(!$s->hasTable('Web_ItemShopMenu'))$s->create('Web_ItemShopMenu',function(Blueprint $t){$t->increments('MenuNum');$t->text('MenuName');});
  if(!$s->hasTable('Web_ItemShop'))$s->create('Web_ItemShop',function(Blueprint $t){$t->bigIncrements('Num');$t->unsignedBigInteger('ItemNum');$t->text('ItemName');$t->unsignedBigInteger('ItemPrice')->default(0);$t->integer('ItemMenu')->default(0)->index();$t->tinyInteger('ItemDelete')->default(0)->index();$t->tinyInteger('ItemLimit')->default(0);$t->unsignedBigInteger('ItemCount')->default(0);$t->integer('ItemVIP')->default(0);$t->tinyInteger('ItemDateTime')->default(0);$t->string('Item_DateStart',64)->nullable();$t->string('Item_DateEnd',64)->nullable();$t->dateTime('DateTime')->useCurrent();$t->unsignedBigInteger('ItemHot')->default(0)->index();});
  if(!$s->hasTable('Log_BuyItems'))$s->create('Log_BuyItems',function(Blueprint $t){$t->bigIncrements('Num');$t->unsignedBigInteger('ItemOrder');$t->unsignedBigInteger('ItemNum');$t->unsignedBigInteger('ItemPrice')->default(0);$t->string('UserID',64)->index();$t->unsignedBigInteger('UserPoint_Before')->default(0);$t->unsignedBigInteger('UserPoint_After')->default(0);$t->dateTime('DateTime')->useCurrent()->index();});
  if(!$s->hasTable('LogTopup'))$s->create('LogTopup',function(Blueprint $t){$t->bigIncrements('Num');$t->string('Password',64)->index();$t->string('Account_ID',64)->index();$t->unsignedBigInteger('Amount')->default(0);$t->integer('Status')->default(0);$t->dateTime('DateTime')->useCurrent();$t->index(['Status','DateTime']);});
  if(!$s->hasTable('Web_VIP'))$s->create('Web_VIP',function(Blueprint $t){$t->integer('No')->primary();$t->unsignedBigInteger('Exp')->default(0);});
  if(!$s->hasTable('Web_VIP_Reward'))$s->create('Web_VIP_Reward',function(Blueprint $t){$t->bigIncrements('Num');$t->integer('VIP_Num')->index();$t->unsignedBigInteger('VIP_Value')->default(0);$t->integer('VIP_Type')->default(0);$t->text('VIP_ItemDesc')->nullable();});
  if(!$s->hasTable('Web_VIP_RewardLog'))$s->create('Web_VIP_RewardLog',function(Blueprint $t){$t->bigIncrements('Num');$t->unsignedBigInteger('UserNum');$t->integer('VIP_Num');$t->unsignedBigInteger('VIP_Value')->default(0);$t->integer('VIP_Type')->default(0);$t->dateTime('DateTime')->useCurrent();$t->unique(['UserNum','VIP_Num'],'uq_vip_reward_user_level');});
  if(!$s->hasTable('Web_Download'))$s->create('Web_Download',function(Blueprint $t){$t->increments('Num');$t->string('FileTitle');$t->text('FileDescription')->nullable();$t->text('FileURL');});
  if(!$s->hasTable('Character'))$s->create('Character',function(Blueprint $t){$t->integer('Character_Num')->primary();$t->string('Character_Name2')->nullable();$t->text('Character_Image')->nullable();});
  if(!$s->hasTable('Web_AlchemistMenu'))$s->create('Web_AlchemistMenu',function(Blueprint $t){$t->integer('Sub')->primary();$t->text('Name');$t->text('Icon')->nullable();});
  if(!$s->hasTable('Webboard_Category'))$s->create('Webboard_Category',function(Blueprint $t){$t->increments('Num');$t->string('URL')->unique();$t->text('Title');});
  foreach([0=>0,1=>5000,2=>20000,3=>35000,4=>50000,5=>80000,6=>100000,7=>130000,8=>150000,9=>170000,10=>200000] as $no=>$exp) DB::connection('web')->table('Web_VIP')->updateOrInsert(['No'=>$no],['Exp'=>$exp]);
 }
 public function down(): void { $s=Schema::connection('web'); foreach(['Webboard_Category','Web_AlchemistMenu','Character','Web_Download','Web_VIP_RewardLog','Web_VIP_Reward','Web_VIP','LogTopup','Log_BuyItems','Web_ItemShop','Web_ItemShopMenu','Web_User'] as $t)$s->dropIfExists($t); }
};
