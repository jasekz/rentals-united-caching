<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class CancellationPolicies extends Base  {

    protected $table = 'RentalsUnited_PropCancellationPolicies';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }
    
}