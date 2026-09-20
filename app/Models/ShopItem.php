<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ShopItem extends Model { protected $connection='web'; protected $table='Web_ItemShop'; protected $primaryKey='Num'; public $timestamps=false; protected $guarded=[]; }
