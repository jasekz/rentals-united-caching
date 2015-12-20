<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class PickupServices extends Base{

    protected $table = 'RentalsUnited_PropPickupServiceText';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}