<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class BasePrices extends Base{

    protected $table = 'RentalsUnited_PropertyBasePrice';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}