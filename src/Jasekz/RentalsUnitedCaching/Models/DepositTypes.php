<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class DepositTypes extends Base  {

    protected $table = 'RentalsUnited_DepositTypes';
    
    protected  $primaryKey = 'DepositTypeID';
    
    public function props()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Prop', 'DepositTypeID', 'DepositTypeID');
    }
}