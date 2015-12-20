<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Distances extends Base  {

    protected $table = 'RentalsUnited_PropDistances';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }
    
}