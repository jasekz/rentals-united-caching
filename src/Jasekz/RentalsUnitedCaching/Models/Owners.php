<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Owners extends Base {
    
    protected $table = 'RentalsUnited_Owners';
    
    protected  $primaryKey = 'OwnerID';
    
    public function agents()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\Agents', 'RentalsUnited_OwnerAgents', 'OwnersID', 'AgentsID');
    }
    
    public function props()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Prop', 'OwnerID', 'OwnerID');
    }
}