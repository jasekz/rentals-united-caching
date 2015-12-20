<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class LateArrivalFees extends Base  {

    protected $table = 'RentalsUnited_PropLateArrivalFees';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }
    
}