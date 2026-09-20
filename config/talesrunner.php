<?php
return [
 'register_cash'=>(int)env('TR_REGISTER_CASH',10000),
 'shared_transactions'=>(bool)env('TR_SHARED_DB_TRANSACTIONS',true),
 'server_host'=>env('TR_SERVER_HOST','127.0.0.1'),'agent_port'=>(int)env('TR_AGENT_PORT',9153),
 'facebook_url'=>env('TR_FACEBOOK_URL','https://www.facebook.com/talesheroofficial/'),
 'features'=>['register'=>(bool)env('TR_ENABLE_REGISTER',true),'download'=>(bool)env('TR_ENABLE_DOWNLOAD',true),'shop'=>(bool)env('TR_ENABLE_SHOP',true),'topup'=>(bool)env('TR_ENABLE_TOPUP',true),'vip'=>(bool)env('TR_ENABLE_VIP',true),'meeta_legacy'=>(bool)env('TR_ENABLE_MEETA_LEGACY',false)],
 'meeta_password'=>env('TR_MEETA_PASSWORD',''),
 'vip_thresholds'=>[0=>0,1=>5000,2=>20000,3=>35000,4=>50000,5=>80000,6=>100000,7=>130000,8=>150000,9=>170000,10=>200000],
 'topup'=>[
   'cash'=>[50=>5000,90=>9000,150=>15000,300=>30000,500=>50000,1000=>100000],
   'point'=>[50=>150,90=>270,150=>450,300=>900,500=>1500,1000=>3000],
   'vip_exp'=>[50=>5000,90=>9000,150=>15000,300=>30000,500=>50000,1000=>100000],
 ],
 'tmpay'=>['enabled'=>(bool)env('TMPAY_ENABLED',false),'access_ip'=>env('TMPAY_ACCESS_IP','127.0.0.1'),'merchant_id'=>env('TMPAY_MERCHANT_ID','WHITERAN'),'endpoint'=>env('TMPAY_ENDPOINT','https://www.tmpay.net/TPG/backend.php'),'timeout'=>(int)env('TMPAY_TIMEOUT',10)],
];
