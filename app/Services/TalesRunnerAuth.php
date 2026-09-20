<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TalesRunnerAuth
{
    public const USER_KEY = 'tr_user_id';
    public const HASH_KEY = 'tr_password_hash';

    public function check(Request $request): bool
    {
        $id = (string) $request->session()->get(self::USER_KEY, '');
        $hash = (string) $request->session()->get(self::HASH_KEY, '');
        if ($id === '' || !preg_match('/^[a-f0-9]{32}$/i', $hash)) return false;
        return DB::connection('game')->table('UserInfoFromPublisher')
            ->where('fdUserID', $id)->where('fdPassword', strtolower($hash))->exists();
    }

    public function attempt(Request $request, string $id, string $password): bool
    {
        $id = strtolower(trim($id));
        $hash = md5($password);
        $ok = DB::connection('game')->table('UserInfoFromPublisher')
            ->where('fdUserID', $id)->where('fdPassword', $hash)->exists();
        if (!$ok) return false;
        $request->session()->regenerate();
        $request->session()->put(self::USER_KEY, $id);
        $request->session()->put(self::HASH_KEY, $hash);
        return true;
    }

    public function logout(Request $request): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function userId(Request $request): string { return (string) $request->session()->get(self::USER_KEY, ''); }

    public function userNum(Request $request): ?int
    {
        $id = $this->userId($request);
        if ($id === '') return null;
        $num = DB::connection('game')->table('UserInfo')->where('fdUID', $id)->value('fdUserNum');
        return $num === null ? null : (int) $num;
    }

    public function register(Request $request, TalesDatabase $db, string $id, string $password): void
    {
        $id = strtolower(trim($id));
        $hash = md5($password);
        if (DB::connection('game')->table('UserInfoFromPublisher')->where('fdUserID', $id)->exists()) {
            throw ValidationException::withMessages(['account' => "Username {$id} is already in use."]);
        }
        $db->transaction(function (TalesDatabase $db) use ($id, $hash) {
            $db->gameTable('UserInfoFromPublisher')->insert([
                'fdUserID'=>$id,'fdGameID'=>$id,'fdPassword'=>$hash,'fdCash'=>config('talesrunner.register_cash'),
            ]);
            $db->webTable('Web_User')->insertOrIgnore(['fdUserID'=>$id]);
        });
        $request->session()->regenerate();
        $request->session()->put(self::USER_KEY, $id);
        $request->session()->put(self::HASH_KEY, $hash);
    }

    public function changePassword(Request $request, string $current, string $new): void
    {
        $id = $this->userId($request);
        $currentHash = md5($current);
        $row = DB::connection('game')->table('UserInfoFromPublisher')->where('fdUserID', $id)->first();
        if (!$row || strtolower((string)$row->fdPassword) !== $currentHash) {
            throw ValidationException::withMessages(['password_current'=>'The current password is incorrect.']);
        }
        $newHash = md5($new);
        if ($newHash === strtolower((string)$row->fdPassword)) {
            throw ValidationException::withMessages(['password'=>'Your new password is the same as your current password.']);
        }
        DB::connection('game')->table('UserInfoFromPublisher')->where('fdUserID',$id)->update(['fdPassword'=>$newHash]);
        $request->session()->put(self::HASH_KEY, $newHash);
    }
}
