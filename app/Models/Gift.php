<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Gift extends Model { protected $connection='game'; protected $table='tblGift'; public $timestamps=false; protected $guarded=[]; }
