<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class ChangeLog extends Base{

    protected $table = 'RentalsUnited_PropertyChangeLog';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}