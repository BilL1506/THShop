<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VipReward extends Model { protected $connection='web'; protected $table='Web_VIP_Reward'; protected $primaryKey='Num'; public $timestamps=false; protected $guarded=[]; }
