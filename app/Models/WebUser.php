<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WebUser extends Model { protected $connection='web'; protected $table='Web_User'; protected $primaryKey='fdUserID'; public $incrementing=false; public $timestamps=false; protected $guarded=[]; protected $casts=['fdPoint'=>'integer','fdEXP_VIP'=>'integer','fdAdmin'=>'boolean']; }
