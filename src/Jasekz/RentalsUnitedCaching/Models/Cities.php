<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Cities extends Base  {
    
    protected $table = 'RentalsUnited_Cities';
    
    protected  $primaryKey = 'LocationID';
    
    public function currencies()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\Currencies', 'RentalsUnited_CityCurrencies', 'CityID', 'CurrencyID');
    }
}