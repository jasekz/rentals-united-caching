<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class ArrivalInstructions extends Base  {

    protected $table = 'RentalsUnited_PropArrivalInstructions';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }
    
}