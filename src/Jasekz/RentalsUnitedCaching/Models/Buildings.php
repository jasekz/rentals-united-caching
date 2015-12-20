<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Buildings extends Base {
    
    protected $table = 'RentalsUnited_Buildings';
    
    protected  $primaryKey = 'BuildingID';
    
    public function props()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Prop', 'BuildingID', 'BuildingID');
    }
}