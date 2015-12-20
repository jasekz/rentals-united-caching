<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class ExternalListings extends Base{

    protected $table = 'RentalsUnited_PropertyExternalListings';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}