<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class PropTypes extends Base {

    protected $table = 'RentalsUnited_PropTypes';
    
    protected  $primaryKey = 'PropertyTypeID';
    
    public function props()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropertyTypeID', 'PropertyTypeID');
    }
}