<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Blocks extends Base{

    protected $table = 'RentalsUnited_PropertyBlocks';
    
    protected  $primaryKey = 'ID';
    
    public function prop()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Prop', 'PropID', 'ID');
    }

}