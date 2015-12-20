<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Prop extends Base  {

    protected $table = 'RentalsUnited_Prop';
    
    protected  $primaryKey = 'ID';
    
    public function amenities()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\Amenities', 'RentalsUnited_PropAmenities', 'PropID', 'AmenityID')->withPivot('Count');
    }
    
    public function arrivalInstructions()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\ArrivalInstructions', 'PropID', 'ID');
    }
    
    public function availabilityCalendar()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\AvailabilityCalendar', 'PropID', 'ID');
    }
    
    public function avbPrices()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\AvbPrices', 'PropID', 'ID');
    }
    
    public function basePrices()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\BasePrices', 'PropID', 'ID');
    }
    
    public function blocks()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Blocks', 'PropID', 'ID');
    }
    
    public function building()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Buildings', 'BuildingID', 'BuildingID');
    }
    
    public function cancellationPolicies()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\CancellationPolicies', 'PropID', 'ID');
    }
    
    public function changeLog()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\ChangeLog', 'PropID', 'ID');
    }
    
    public function changeoverDays()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\ChangeoverDays', 'PropID', 'ID');
    }
    
    public function compositionRooms()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\CompositionRooms', 'RentalsUnited_PropCompositionRooms', 'PropID', 'CompositionRoomID')->withPivot('Count');
    }
    
    public function depositType()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\DepositTypes', 'DepositTypeID', 'DepositTypeID');
    }
    
    public function descriptions()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Descriptions', 'PropID', 'ID');
    }
    
    public function discountsLastMinute()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\DiscountsLastMinute', 'PropID', 'ID');
    }
    
    public function discountsLongStay()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\DiscountsLongStay', 'PropID', 'ID');
    }
    
    public function distances()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Distances', 'PropID', 'ID');
    }
    
    public function earlyDepartureFees()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\EarlyDepartureFees', 'PropID', 'ID');
    }
    
    public function externalListings()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\ExternalListings', 'PropID', 'ID');
    }
    
    public function images()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\Images', 'PropID', 'ID');
    }
    
    public function lateArrivalFees()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\LateArrivalFees', 'PropID', 'ID');
    }
    
    public function location()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Locations', 'DetailedLocationID', 'LocationID');
    }
    
    public function minStays()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\MinStays', 'PropID', 'ID');
    }
    
    public function owner()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\Owners', 'OwnerID', 'OwnerID');
    }
    
    public function payementMethods()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\PaymentMethods', 'PropID', 'ID');
    }
    
    public function pickupServices()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\PickupServices', 'PropID', 'ID');
    }
    
    public function propType()
    {
        return $this->belongsTo('Jasekz\RentalsUnitedCaching\Models\PropTypes', 'PropertyTypeID', 'PropertyTypeID');
    }
    
    public function seasonPrices()
    {
        return $this->hasMany('Jasekz\RentalsUnitedCaching\Models\SeasonPrices', 'PropID', 'ID');
    }
}