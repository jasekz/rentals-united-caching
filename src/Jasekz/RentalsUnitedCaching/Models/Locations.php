<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Locations extends Base  {

    protected $table = 'RentalsUnited_Locations';
    
    protected  $primaryKey = 'LocationID';
    
    public function props()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Prop', 'DetailedLocationID', 'LocationID');
    }
}