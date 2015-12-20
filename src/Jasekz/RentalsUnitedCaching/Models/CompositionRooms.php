<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class CompositionRooms extends Base {

    protected $table = 'RentalsUnited_CompositionRooms';
    
    protected  $primaryKey = 'CompositionRoomID';
    
    public function props()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\Prop', 'RentalsUnited_PropCompositionRooms', 'CompositionRoomID', 'PropID')->withPivot('Count');
    }
    
    public function propAmenities()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\Amenities', 'RentalsUnited_PropCompositionRoomAmenities', 'CompositionRoomID', 'AmenityID');
    }
}