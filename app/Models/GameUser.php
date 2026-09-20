<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GameUser extends Model { protected $connection='game'; protected $table='UserInfo'; protected $primaryKey='fdUserNum'; public $timestamps=false; protected $guarded=[]; }
