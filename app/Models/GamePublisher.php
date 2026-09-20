<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GamePublisher extends Model { protected $connection='game'; protected $table='UserInfoFromPublisher'; protected $primaryKey='fdUserID'; public $incrementing=false; public $timestamps=false; protected $guarded=[]; }
