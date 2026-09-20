<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class TmpayService
{
    public function sendCard(string $accountId, string $code): void
    {
        if (!config('talesrunner.tmpay.enabled')) throw new RuntimeException('TMPAY integration is disabled.');
        $response = Http::asForm()->timeout(config('talesrunner.tmpay.timeout'))->post(config('talesrunner.tmpay.endpoint'), [
            'merchant_id'=>config('talesrunner.tmpay.merchant_id'),'password'=>$code,'resp_url'=>route('tmpay.callback'),
        ]);
        if (!$response->successful()) throw new RuntimeException('TMPAY gateway is unavailable.');
    }
}
