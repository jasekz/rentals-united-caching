<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class Prop extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListSpecProp';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'Property.xml';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_Prop';
    
    /**
     * Fetch new properties from RU
     */
    public function getPropByCreationDate($dateFrom, $dateTo)
    {
        $fileName = 'NewProperties.xml';        
    
        try {
            $xml = $this->ru->ListPropByCreationDate( $dateFrom, $dateTo);
            
            $obj = simplexml_load_string($xml['messages']);
            
            if ((string) $obj->Status != 'Success') {
                throw new Exception($obj->Status);
            }
            
            return $obj;
        } 

        catch (Exception $e) {
            throw $e;
        }
        
    }

    /**
     * Cache RU data to DB
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb($propertyId = null)
    {
        $fileName = 'PropertyID_' . $propertyId . '_' . $this->fileName;
        
        $this->downloadXML($fileName, $propertyId);
        
        try {
            $this->property = $this->getFileContents($fileName)->Property;
            $this->cacheProperty();
            $this->cachePropertyDistances();
            $this->cachePropertyCompositionRooms();
            $this->cachePropertyCompositionRoomAmenities();
            $this->cachePropertyAmenities();
            $this->cachePropertyImages();
            $this->cachePropertyArrivalInstructions();
            $this->cachePropertyLateArrivalFees();
            $this->cachePropertyEarlyDepartureFees();
            $this->cachePropertyPaymentMethods();
            $this->cachePropertyCancellationPolicies();
            $this->cachePropertyDescriptions();
            
            $this->deleteXML($fileName);
        } 

        catch (Exception $e) {
            throw $e;
        }
    }

    private function cachePropertyCompositionRoomAmenities()
    {
        $sql = "delete from RentalsUnited_PropCompositionRoomAmenities where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->CompositionRoomsAmenities->CompositionRoomAmenities as $room) {
            
            foreach ($room->Amenities->Amenity as $amenity) {
                
                $sql = "insert into RentalsUnited_PropCompositionRoomAmenities
                        set PropID=?,
                            CompositionRoomID=?,
                            AmenityID=?,  
                            Count=?,                          
                            created_at=?;";
                
                DB::statement($sql, array(
                    (string) $this->property->ID,
                    (string) $room->attributes()->CompositionRoomID,
                    (string) $amenity,
                    (string) $amenity->attributes()->Count,
                    date('Y-m-d G:i:s')
                ));
            }
        }
    }

    private function cachePropertyDescriptions()
    {
        $sql = "delete from RentalsUnited_PropDescriptions where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->Descriptions->Description as $description) {
            
            $sql = "insert into RentalsUnited_PropDescriptions
                    set PropID=?,
                        LanguageID=?,
                        Text=?,
                        Image=?,
                        updated_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $description->attributes()->LanguageID,
                (string) $description->Text,
                (string) $description->Image,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cachePropertyPaymentMethods()
    {
        $sql = "delete from RentalsUnited_PropPaymentMethods where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->PaymentMethods->PaymentMethod as $method) {
            
            $sql = "insert into RentalsUnited_PropPaymentMethods
                    set PropID=?,
                        PaymentMethodID=?,
                        PaymentMethod=?,
                        created_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $method->attributes()->PaymentMethodID,
                (string) $method,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cachePropertyArrivalInstructions()
    {
        $sql = "delete from RentalsUnited_PropArrivalInstructions where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        $sql = "insert into RentalsUnited_PropArrivalInstructions
                    set PropID=?,
                        Landlord=?,
                        Email=?,
                        Phone=?,
                        DaysBeforeArrival=?,
                        created_at=?;";
        
        DB::statement($sql, array(
            (string) $this->property->ID,
            (string) $this->property->ArrivalInstructions->Landlord,
            (string) $this->property->ArrivalInstructions->Email,
            (string) $this->property->ArrivalInstructions->Phone,
            (string) $this->property->ArrivalInstructions->DaysBeforeArrival,
            date('Y-m-d G:i:s')
        ));
        
        // HowToArrive
        $sql = "delete from RentalsUnited_PropHowToArriveText where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        foreach ($this->property->ArrivalInstructions->HowToArrive->Text as $arrivalText) {
            $sql = "insert into RentalsUnited_PropHowToArriveText
                        set PropID=?,
                            LanguageID=?,
                            Text=?,
                            created_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $arrivalText->attributes()->LanguageID,
                (string) $arrivalText,
                date('Y-m-d G:i:s')
            ));
        }
        
        // PickupService
        $sql = "delete from RentalsUnited_PropPickupServiceText where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        foreach ($this->property->ArrivalInstructions->PickupService->Text as $pickupServiceText) {
            $sql = "insert into RentalsUnited_PropPickupServiceText
                        set PropID=?,
                            LanguageID=?,
                            Text=?,
                            created_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $pickupServiceText->attributes()->LanguageID,
                (string) $pickupServiceText,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cachePropertyImages()
    {
        $sql = "delete from RentalsUnited_PropImages where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->Images->Image as $image) {
            
            $sql = "insert into RentalsUnited_PropImages
                    set PropID=?,
                        ImageTypeID=?,
                        Image=?,
                        created_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $image->attributes()->ImageTypeID,
                (string) $image,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cachePropertyLateArrivalFees()
    {
        $sql = "delete from RentalsUnited_PropLateArrivalFees where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->CheckInOut->LateArrivalFees->LateArrivalFee as $fee) {
            $sql = "insert into RentalsUnited_PropLateArrivalFees
                    set PropID=?,
                        `From`=?,
                        `To`=?,
                        LateArrivalFee=?,
                        created_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $fee->attributes()->From,
                (string) $fee->attributes()->To,
                (string) $fee,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cachePropertyEarlyDepartureFees()
    {
        $sql = "delete from RentalsUnited_PropEarlyDepartureFees where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->CheckInOut->EarlyDepartureFees->EarlyDepartureFee as $fee) {
            $sql = "insert into RentalsUnited_PropEarlyDepartureFees
                    set PropID=?,
                        `From`=?,
                        `To`=?,
                        EarlyDepartureFee=?,
                        created_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $fee->attributes()->From,
                (string) $fee->attributes()->To,
                (string) $fee,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cachePropertyCancellationPolicies()
    {
        $sql = "delete from RentalsUnited_PropCancellationPolicies where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->CancellationPolicies->CancellationPolicy as $policy) {
            
            $sql = "insert into RentalsUnited_PropCancellationPolicies
                    set PropID=?,
                        ValidFrom=?,
                        ValidTo=?,
                        CancellationPolicy=?,
                        created_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $policy->attributes()->ValidFrom,
                (string) $policy->attributes()->ValidTo,
                (string) $policy,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cachePropertyAmenities()
    {
        $sql = "delete from RentalsUnited_PropAmenities where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->Amenities->Amenity as $amenity) {
            
            $sql = "insert into RentalsUnited_PropAmenities
                    set PropID=?,
                        AmenityID=?,
                        Count=?,
                        created_at=?;";
            
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $amenity,
                (string) $amenity->attributes()->Count,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cachePropertyCompositionRooms()
    {
        $sql = "delete from RentalsUnited_PropCompositionRooms where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        $compositionRooms = [];
        
        foreach ($this->property->CompositionRooms->CompositionRoomID as $compositionRoom) {          
                $compositionRooms[(int) $compositionRoom]['count'] = (int) $compositionRoom->attributes()->Count;
        }
        
        // Some properties have compositionRoomAmenities, but no composition rooms;
        // that means that there are, in fact, composition rooms, but need to be extracted
        // from compositionRoomAmenities - if that makes any sense
        foreach ($this->property->CompositionRoomsAmenities as $compositionRoomAmenities) {
            foreach ($compositionRoomAmenities->CompositionRoomAmenities as $compositionRoom) {
                
                if (! isset($compositionRooms[(int) $compositionRoom->attributes()->CompositionRoomID])) {
                    $compositionRooms[(int) $compositionRoom->attributes()->CompositionRoomID]['count'] = 1;
                } else {
                    $compositionRooms[(int) $compositionRoom->attributes()->CompositionRoomID]['count'] ++;
                }
            }
        }

        if ($compositionRooms) {
            foreach ($compositionRooms as $roomId => $room) {
                $sql = "insert into RentalsUnited_PropCompositionRooms
                        set PropID=?,
                            CompositionRoomID=?,
                            Count=?,
                            created_at=?;";
                
                DB::statement($sql, array(
                    (string) $this->property->ID,
                    $roomId,
                    $room['count'],
                    date('Y-m-d G:i:s')
                ));
            }
        }
    }

    private function cachePropertyDistances()
    {
        $sql = "delete from RentalsUnited_PropDistances where PropID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        foreach ($this->property->Distances->Distance as $distance) {
            
            $sql = "insert into RentalsUnited_PropDistances 
                    set PropID=?,
                        DestinationID=?,
                        DistanceUnitID=?,
                        DistanceValue=?,
                        created_at=?;";
            DB::statement($sql, array(
                (string) $this->property->ID,
                (string) $distance->DestinationID,
                (string) $distance->DistanceUnitID,
                (string) $distance->DistanceValue,
                date('Y-m-d G:i:s')
            ));
        }
    }

    private function cacheProperty()
    {
        $sql = "delete from RentalsUnited_Prop where ID=?;";
        DB::statement($sql, array(
            (string) $this->property->ID
        ));
        
        $sql = "insert into RentalsUnited_Prop
                    set ID=?,
                        PUID=?,
                        Name=?,
                        OwnerID=?,
                        DetailedLocationID=?,
                        LastMod=?,
                        IMAP=?,
                        IsActive=?,
                        IsArchived=?,
                        CleaningPrice=?,
                        Space=?,
                        StandardGuests=?,
                        CanSleepMax=?,
                        PropertyTypeID=?,
                        Floor=?,
                        Street=?,
                        ZipCode=?,
                        Longitude=?,
                        Latitude=?,
                        DateCreated=?,
                        SecurityDeposit=?,
                        IMU=?,
                        CheckInFrom=?,
                        CheckInTo=?,
                        CheckOutUntil=?,
                        Place=?,
                        Deposit=?,
                        DepositTypeID=?,
                        created_at=?;";
        
        DB::statement($sql, array(
            (string) $this->property->ID,
            (string) $this->property->PUID,
            (string) $this->property->Name,
            (string) $this->property->OwnerID,
            (string) $this->property->DetailedLocationID,
            (string) $this->property->LastMod,
            (string) $this->property->IMAP,
            (string) $this->property->IsActive,
            (string) $this->property->IsArchived,
            (string) $this->property->CleaningPrice,
            (string) $this->property->Space,
            (string) $this->property->StandardGuests,
            (string) $this->property->CanSleepMax,
            (string) $this->property->PropertyTypeID,
            (string) $this->property->Floor,
            (string) $this->property->Street,
            (string) $this->property->ZipCode,
            (string) $this->property->Coordinates->Longitude,
            (string) $this->property->Coordinates->Latitude,
            (string) $this->property->DateCreated,
            (string) $this->property->SecurityDeposit,
            (string) $this->property->IMU,
            (string) $this->property->CheckInOut->CheckInFrom,
            (string) $this->property->CheckInOut->CheckInTo,
            (string) $this->property->CheckInOut->CheckOutUntil,
            (string) $this->property->CheckInOut->Place,
            (string) $this->property->Deposit,
            (string) $this->property->Deposit->attributes()->DepositTypeID,
            date('Y-m-d G:i:s')
        ));
    }

    /**
     * Return all records from table
     *
     * @return array Records
     * @throws Exception
     */
    public function get($propertyID = null)
    {
        try {
            if (! $propertyID) {
                throw new Exception('First arg to get() must by propertyID.');
            }
            
            // get property
            $sql = "select * 
                    from RentalsUnited_Properties";
            $property = DB::statement($sql)->fetch();
            
            // descriptions
            $sql = "select pd.*, l.LanguageCode, l.Language
                    from RentalsUnited_PropertyDescriptions pd
                    left join RentalsUnited_Languages l
                    on pd.LanguageID=l.LanguageID
                    where pd.PropertyID=?
                    order by pd.LanguageID asc";
            $property['descriptions'] = DB::statement($sql, array(
                $propertyID
            ))->fetchAll();
            
            // locations
            $sql = "select l.*
                    from RentalsUnited_Locations l
                    left join RentalsUnited_Properties p
                    on l.LocationID=p.DetailedLocationID
                    where p.ID=?";
            $location1 = DB::statement($sql, array(
                $propertyID
            ))->fetch();
            if ($location1['LocationTypeID'] == 4) { // it's a city
                $property['City'] = $location1['Location'];
                $property['CityID'] = $location1['LocationID'];
            } else 
                if ($location1['LocationTypeID'] == 3) { // it's a region
                    $property['Region'] = $location1['Location'];
                    $property['State'] = $location1['Location'];
                    $property['StateID'] = $location1['LocationID'];
                } else 
                    if ($location1['LocationTypeID'] == 2) { // it's a country
                        $property['Country'] = $location1['Location'];
                        $property['CountryID'] = $location1['LocationID'];
                    }
            
            if ($location1['ParentLocationID']) {
                $sql = "select *
                        from RentalsUnited_Locations
                        where LocationID=?";
                $location2 = DB::statement($sql, array(
                    $location1['ParentLocationID']
                ))->fetch();
                if ($location2['LocationTypeID'] == 3) { // it's a region
                    $property['Region'] = $location2['Location'];
                    $property['State'] = $location2['Location'];
                    $property['StateID'] = $location2['LocationID'];
                } else 
                    if ($location2['LocationTypeID'] == 2) { // it's a country
                        $property['Country'] = $location2['Location'];
                        $property['CountryID'] = $location2['LocationID'];
                    }
            }
            
            if ($location2 && $location2['ParentLocationID']) {
                $sql = "select *
                        from RentalsUnited_Locations
                        where LocationID=?";
                $location3 = DB::statement($sql, array(
                    $location2['ParentLocationID']
                ))->fetch();
                if ($location3['LocationTypeID'] == 3) { // it's a region
                    $property['Region'] = $location3['Location'];
                    $property['State'] = $location3['Location'];
                    $property['StateID'] = $location3['LocationID'];
                } else 
                    if ($location3['LocationTypeID'] == 2) { // it's a country
                        $property['Country'] = $location3['Location'];
                        $property['CountryID'] = $location3['LocationID'];
                    }
            }
            
            // if country is US, make it a state, otherwise a region
            if ($property['Country'] == 'United States') {
                unset($property['Region']);
            } else {
                unset($property['State']);
                unset($property['StateID']);
            }
            
            // currencies
            $sql = "select CurrencyID
                    from RentalsUnited_CityCurrencies
                    where CityID=?";
            $property['currencies'] = DB::statement($sql, array(
                $property['CityID']
            ))->fetchAll();
            
            // amenities
            $sql = "select a.AmenityID
                    from RentalsUnited_Amenities a 
                    join RentalsUnited_PropertyAmenities pa
                    on pa.AmenityID=a.AmenityID
                    where pa.PropertyID=?";
            $property['amenities'] = DB::statement($sql, array(
                $propertyID
            ))->fetchAll();
            
            // rooms
            $sql = "select cr.*
                    from RentalsUnited_CompositionRooms cr 
                    join RentalsUnited_PropertyCompositionRooms pcr
                    on pcr.CompositionRoomID=cr.CompositionRoomID
                    where pcr.PropertyID=?";
            $property['rooms'] = DB::statement($sql, array(
                $propertyID
            ))->fetchAll();
            
            // base pricing
            $sql = "select pbp.*
                    from RentalsUnited_PropertyBasePrice pbp 
                    join RentalsUnited_Properties p
                    on p.ID=pbp.PropertyID
                    where p.ID=?";
            $property['basePricing'] = DB::statement($sql, array(
                $propertyID
            ))->fetchAll();
            
            return $property;
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}