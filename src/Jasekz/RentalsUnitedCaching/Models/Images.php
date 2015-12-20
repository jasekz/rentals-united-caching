<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Images extends Base{

    protected $table = 'RentalsUnited_PropImages';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}