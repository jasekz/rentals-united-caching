<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class DiscountsLastMinute extends Base{

    protected $table = 'RentalsUnited_PropertyDiscountsLastMinutes';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}