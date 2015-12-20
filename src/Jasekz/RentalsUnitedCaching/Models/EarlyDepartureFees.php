<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class EarlyDepartureFees extends Base  {

    protected $table = 'RentalsUnited_PropEarlyDepartureFees';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }
    
}