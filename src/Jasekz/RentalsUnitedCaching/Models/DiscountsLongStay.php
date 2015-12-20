<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class DiscountsLongStay extends Base{

    protected $table = 'RentalsUnited_PropertyDiscountsLongStays';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}