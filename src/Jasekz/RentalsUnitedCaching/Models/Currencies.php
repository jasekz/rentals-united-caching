<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Currencies extends Base  {
    
    protected $table = 'RentalsUnited_Currencies';
    
    protected  $primaryKey = 'ID';
    
    public function cities()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\Cities', 'RentalsUnited_CityCurrencies', 'CurrencyID', 'CityID');
    }
}