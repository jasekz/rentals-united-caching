<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Amenities extends Base{

    protected $table = 'RentalsUnited_Amenities';
    
    protected  $primaryKey = 'AmenityID';
    
    public function props()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\Prop', 'RentalsUnited_PropAmenities', 'AmenityID', 'PropID')->withPivot('Count');
    }
    
    public function compositionRooms()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\CompositionRooms', 'RentalsUnited_PropCompositionRoomAmenities', 'AmenityID', 'CompositionRoomID');
    }
}