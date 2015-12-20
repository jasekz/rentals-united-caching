<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class MinStays extends Base{

    protected $table = 'RentalsUnited_PropertyMinStays';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}