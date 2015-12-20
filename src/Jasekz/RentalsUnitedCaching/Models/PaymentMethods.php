<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class PaymentMethods extends Base  {

    protected $table = 'RentalsUnited_PaymentMethods';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }
}