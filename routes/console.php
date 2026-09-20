<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('talesrunner:health', function () {
    $ok = true;
    foreach (['game','web'] as $name) {
        try { DB::connection($name)->getPdo(); $this->info(strtoupper($name).' DB connection: OK'); }
        catch (Throwable $e) { $ok=false; $this->error(strtoupper($name).' DB: '.$e->getMessage()); continue; }
        $required = $name === 'game'
            ? ['UserInfoFromPublisher','UserInfo','UserInfoGame','UserInfoLogin','tblGift','tblAvatarItemDesc']
            : ['Web_User','Web_ItemShopMenu','Web_ItemShop','Log_BuyItems','LogTopup','Web_VIP','Web_VIP_Reward','Web_VIP_RewardLog'];
        $dbName = config("database.connections.$name.database");
        foreach ($required as $table) {
            $exists = DB::connection($name)->table('information_schema.tables')->where('table_schema',$dbName)->where('table_name',$table)->exists();
            if($exists) $this->line("  [OK] $table"); else { $ok=false; $this->error("  [MISSING] $table"); }
        }
    }
    $this->newLine();
    if(config('talesrunner.shared_transactions')) $this->info('Shared cross-schema transactions: ENABLED (recommended when both schemas share one MySQL server/user).');
    else $this->warn('Shared cross-schema transactions: DISABLED. Cross-database operations cannot be fully atomic on separate servers.');
    return $ok ? 0 : 1;
})->purpose('Check TalesRunner game/web MySQL connections and required tables');
