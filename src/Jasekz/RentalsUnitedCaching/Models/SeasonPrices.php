<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class SeasonPrices extends Base{

    protected $table = 'RentalsUnited_PropertyPrices';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}