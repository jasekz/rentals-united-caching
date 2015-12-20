<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class ChangeoverDays extends Base  {

    protected $table = 'RentalsUnited_PropChangeoverDays';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }
    
}