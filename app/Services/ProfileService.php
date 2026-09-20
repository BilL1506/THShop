<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function __construct(private TalesRunnerAuth $auth) {}

    public function vipLevel(int $exp): int
    {
        $level = 0;
        foreach (config('talesrunner.vip_thresholds') as $lv=>$need) if ($exp >= $need) $level = (int)$lv;
        return $level;
    }

    public function profile(Request $request): array
    {
        $id = $this->auth->userId($request);
        $userNum = $this->auth->userNum($request);
        DB::connection('web')->table('Web_User')->insertOrIgnore(['fdUserID'=>$id]);
        $publisher = DB::connection('game')->table('UserInfoFromPublisher')->where('fdUserID',$id)->first();
        $web = DB::connection('web')->table('Web_User')->where('fdUserID',$id)->first();
        $info = $userNum ? DB::connection('game')->table('UserInfo')->where('fdUserNum',$userNum)->first() : null;
        $game = $userNum ? DB::connection('game')->table('UserInfoGame')->where('fdUserNum',$userNum)->first() : null;
        $nickname = $info?->fdNickname ?: ($userNum ? 'Unnamed' : 'No character yet');
        $icon = asset('images/avatar.png');
        if ($game && isset($game->fdAvatarCharacterSettingNum)) {
            $setting = DB::connection('game')->table('tblAvatarCharacterSetting')->where('fdItemCharacterSettingNum',$game->fdAvatarCharacterSettingNum)->first();
            if ($setting && isset($setting->fdCharacter)) {
                $char = DB::connection('web')->table('Character')->where('Character_Num',$setting->fdCharacter)->first();
                if ($char?->Character_Image) $icon = str_starts_with($char->Character_Image,'http') ? $char->Character_Image : asset(ltrim($char->Character_Image,'/'));
            }
        }
        $vipExp = (int)($web?->fdEXP_VIP ?? 0);
        return [
            'id'=>$id,'user_num'=>$userNum,'nickname'=>$nickname,'character_icon'=>$icon,
            'cash'=>(int)($publisher?->fdCash ?? 0),'tr'=>(int)($game?->fdGameMoney ?? 0),
            'point'=>(int)($web?->fdPoint ?? 0),'vip_exp'=>$vipExp,'vip_level'=>$this->vipLevel($vipExp),
            'admin'=>(bool)($web?->fdAdmin ?? false),'meeta'=>$web?->MEETA_Account,
        ];
    }

    public function isOnline(?int $userNum): bool
    {
        if (!$userNum) return false;
        return (int) DB::connection('game')->table('UserInfoLogin')->where('fdUserNum',$userNum)->value('fdServerNum') !== 0;
    }
}
