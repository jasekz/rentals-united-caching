<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentalsUnitedTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDictionaryTables();
        $this->upStaticPropertyTables();
        $this->upAvailablityAndPriceTables();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDictionaryTables();
        $this->downStaticPropertyTables();
        $this->downAvailablityAndPriceTables();
    }

    private function upAvailablityAndPriceTables()
    {
        Schema::create('RentalsUnited_PropertyBlocks', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->date('DateFrom');
            $table->date('DateTo');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyAvailabilityCalendar', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->date('Date');
            $table->integer('IsBlocked');
            $table->integer('MinStay');
            $table->integer('Changeover');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyMinStay', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('MinStay');
            $table->date('DateFrom');
            $table->date('DateTo');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyBasePrice', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('DayOfWeek');
            $table->float('BasePrice');
            $table->float('BasePriceExtra');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyPrices', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->date('SeasonDateFrom');
            $table->date('SeasonDateTo');
            $table->float('SeasonPrice');
            $table->float('SeasonExtra');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyPricesLOS', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('PropertyPricesID');
            $table->integer('Nights');
            $table->float('Price');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyPricesLOSP', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('PropertyPricesID');
            $table->integer('PropertyPricesLOSID');
            $table->integer('NrOfGuests');
            $table->float('Price');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyPricesEGP', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('PropertyPricesID');
            $table->integer('ExtraGuests');
            $table->float('Price');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyAVBPrice', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('PropertyPricesID');
            $table->float('PropertyPrice');
            $table->integer('NOP');
            $table->float('Cleaning');
            $table->float('ExtraPersonPrice');
            $table->float('Deposit');
            $table->float('SecurityDeposit');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyDiscountsLongStays', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->date('DateFrom');
            $table->date('DateTo');
            $table->integer('Bigger');
            $table->integer('Smaller');
            $table->integer('LongStay');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyDiscountsLastMinutes', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->date('DateFrom');
            $table->date('DateTo');
            $table->integer('DaysArrivalFrom');
            $table->integer('DaysArrivalTo');
            $table->integer('LastMinute');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Reservations', function (Blueprint $table) // NOT IMPLEMEMTED
        {            
            $table->increments('ID');
            $table->integer('ReservationID');
            $table->integer('Status');
            $table->datetime('LastMod');
            $table->integer('PropID');
            $table->date('DateFrom');
            $table->date('DateTo');
            $table->integer('NumberOfGuests');
            $table->float('RUPrice');
            $table->float('ClientPrice');
            $table->float('AlreadyPaid');
            $table->integer('StayID');
            $table->integer('HotelID');
            $table->integer('RoomID');
            $table->integer('RateID');
            $table->string('CustomerName');
            $table->string('CustomerSurName');
            $table->string('CustomerEmail');
            $table->string('CustomerPhone');
            $table->string('CustomerSkypeID');
            $table->string('CustomerAddress');
            $table->string('CustomerZipCode');
            $table->string('CustomerCountryID');
            $table->string('Creator');
            $table->text('Comments');
            $table->string('CCNumber');
            $table->string('CVC');
            $table->string('NameOnCard');
            $table->string('Expiration');
            $table->string('BillingAddress');
            $table->string('CardType');
            $table->text('CCComments');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyChangeLog', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('NLA');
            $table->integer('IsActive');
            $table->datetime('StaticData');
            $table->datetime('Pricing');
            $table->datetime('Availability');
            $table->datetime('Image');
            $table->datetime('Description');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyPriceChanges', function (Blueprint $table) // NOT IMPLEMENTED
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->date('Day');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyAvbChanges', function (Blueprint $table) // NOT IMPLEMENTED
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->date('Day');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropLeads', function (Blueprint $table) // NOT IMPLEMENTED
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('ReservationID');
            $table->string('ExternalLeadID');
            $table->date('DateFrom');
            $table->date('DateTo');
            $table->integer('NumberOfGuests');
            $table->string('CustomerName');
            $table->string('CustomerSurName');
            $table->string('CustomerEmail');
            $table->string('CustomerPhone');
            $table->string('CustonmerSkypeID');
            $table->string('CustomerAddress');
            $table->string('CustomerZipCode');
            $table->integer('CustomerCountryID');
            $table->text('Comments');
            $table->string('Creator');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropChangeoverDays', function (Blueprint $table) // NOT IMPLEMENTED
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->date('StartDate');
            $table->date('EndDate');
            $table->string('Changeover');
            $table->timestamps();
        });
    }

    private function downAvailablityAndPriceTables()
    {
        Schema::drop('RentalsUnited_PropertyBlocks');
        Schema::drop('RentalsUnited_PropertyAvailabilityCalendar');
        Schema::drop('RentalsUnited_PropertyMinStay');
        Schema::drop('RentalsUnited_PropertyBasePrice');
        Schema::drop('RentalsUnited_PropertyPrices');
        Schema::drop('RentalsUnited_PropertyPricesLOS');
        Schema::drop('RentalsUnited_PropertyPricesLOSP');
        Schema::drop('RentalsUnited_PropertyPricesEGP');
        Schema::drop('RentalsUnited_PropertyAVBPrice');
        Schema::drop('RentalsUnited_PropertyDiscountsLongStays');
        Schema::drop('RentalsUnited_PropertyDiscountsLastMinutes');
        Schema::drop('RentalsUnited_Reservations');
        Schema::drop('RentalsUnited_PropertyChangeLog');
        Schema::drop('RentalsUnited_PropertyPriceChanges');
        Schema::drop('RentalsUnited_PropertyAvbChanges');
        Schema::drop('RentalsUnited_PropLeads');
        Schema::drop('RentalsUnited_PropChangeoverDays');
    }

    private function upStaticPropertyTables()
    {
        Schema::create('RentalsUnited_Prop', function (Blueprint $table)
        {    
            $table->primary('ID');
            $table->integer('ID');
            $table->integer('PUID');
            $table->string('Name');
            $table->integer('BuildingID');
            $table->string('BuildingName');
            $table->integer('OwnerID');
            $table->integer('DetailedLocationID');
            $table->integer('LocationTypeID');
            $table->datetime('LastMod');
            $table->integer('LastModNLA');
            $table->integer('IMAP');
            $table->date('DateCreated');
            $table->float('CleaningPrice');
            $table->integer('Space');
            $table->integer('StandardGuests');
            $table->integer('CanSleepMax');
            $table->integer('PropertyTypeID');
            $table->integer('Floor');
            $table->string('Street');
            $table->string('ZipCode');
            $table->string('Latitude');
            $table->string('Longitude');
            $table->integer('IsActive');
            $table->integer('IsArchived');
            $table->float('SecurityDeposit');
            $table->string('IMU');
            $table->string('CheckInFrom');
            $table->string('CheckInTo');
            $table->string('CheckOutUntil');
            $table->string('Place');
            $table->integer('DepositTypeID');
            $table->float('Deposit');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropDistances', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('DestinationID');
            $table->integer('DistanceUnitID');
            $table->float('DistanceValue');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropCompositionRooms', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('CompositionRoomID');
            $table->integer('Count');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropCompositionRoomAmenities', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('CompositionRoomID');
            $table->integer('AmenityID');
            $table->integer('Count');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropAmenities', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('AmenityID');
            $table->integer('Count');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropImages', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('ImageTypeID');
            $table->string('Image');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropArrivalInstructions', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->string('Landlord');
            $table->string('Email');
            $table->string('Phone');
            $table->integer('DaysBeforeArrival');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropHowToArriveText', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('LanguageID');
            $table->text('Text');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropPickupServiceText', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('LanguageID');
            $table->text('Text');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropLateArrivalFees', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->string('From');
            $table->string('To');
            $table->float('LateArrivalFee');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropEarlyDepartureFees', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->string('From');
            $table->string('To');
            $table->float('EarlyDepartureFee');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropPaymentMethods', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('PaymentMethodID');
            $table->string('PaymentMethod');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropCancellationPolicies', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('ValidFrom');
            $table->integer('ValidTo');
            $table->float('CancellationPolicy');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropDescriptions', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('LanguageID');
            $table->string('Text');
            $table->string('Image');
            $table->timestamps();
        });
        
        Schema::create('RentalsUnited_Buildings', function (Blueprint $table)
        {
            $table->primary('BuildingID');
            $table->integer('BuildingID');
            $table->string('BuildingName');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_BuildingProperties', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('BuildingsID');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Owners', function (Blueprint $table)
        {
            $table->primary('OwnerID');
            $table->integer('OwnerID');
            $table->string('FirstName');
            $table->string('SurName');
            $table->string('Email');
            $table->string('Phone');
            $table->string('ScreenName');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Agents', function (Blueprint $table)
        {
            $table->primary('AgentID');
            $table->integer('AgentID');
            $table->string('UserName');
            $table->string('CompanyName');
            $table->string('FirstName');
            $table->string('SurName');
            $table->string('Email');
            $table->string('Telephone');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_OwnerAgents', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('OwnersID');
            $table->integer('AgentsID');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyExternalListings', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->string('Url');
            $table->integer('Status');
            $table->text('Description');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyExternalListingsNotifs', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->string('Notif');
            $table->integer('StatusID');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyReviews', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('ReviewID');
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('DisplayName');
            $table->string('Email');
            $table->integer('Rating');
            $table->date('ArrivalDate');
            $table->date('Submitted');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropertyReviewsText', function (Blueprint $table)
        {            
            $table->increments('ID');
            $table->integer('PropID');
            $table->integer('ReviewID');
            $table->integer('LanguageID');
            $table->text('Text');
            $table->timestamps();
        });
    }

    private function downStaticPropertyTables()
    {
        Schema::drop('RentalsUnited_Prop');
        Schema::drop('RentalsUnited_PropDistances');
        Schema::drop('RentalsUnited_PropCompositionRooms');
        Schema::drop('RentalsUnited_PropCompositionRoomAmenities');
        Schema::drop('RentalsUnited_PropAmenities');
        Schema::drop('RentalsUnited_PropImages');
        Schema::drop('RentalsUnited_PropArrivalInstructions');
        Schema::drop('RentalsUnited_PropHowToArriveText');
        Schema::drop('RentalsUnited_PropPickupServiceText');
        Schema::drop('RentalsUnited_PropLateArrivalFees');
        Schema::drop('RentalsUnited_PropEarlyDepartureFees');
        Schema::drop('RentalsUnited_PropPaymentMethods');
        Schema::drop('RentalsUnited_PropCancellationPolicies');
        Schema::drop('RentalsUnited_PropDescriptions');
        
        Schema::drop('RentalsUnited_Buildings');
        Schema::drop('RentalsUnited_BuildingProperties');
        Schema::drop('RentalsUnited_Owners');
        Schema::drop('RentalsUnited_Agents');
        Schema::drop('RentalsUnited_OwnerAgents');
        Schema::drop('RentalsUnited_PropertyExternalListings');
        Schema::drop('RentalsUnited_PropertyExternalListingsNotifs');
        Schema::drop('RentalsUnited_PropertyReviews');
        Schema::drop('RentalsUnited_PropertyReviewsText');
    }

    private function upDictionaryTables()
    {
        Schema::create('RentalsUnited_PropTypes', function (Blueprint $table)
        {
            $table->primary('PropertyTypeID');
            $table->integer('PropertyTypeID');
            $table->string('PropertyType');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_LocationTypes', function (Blueprint $table)
        {
            $table->primary('LocationTypeID');
            $table->integer('LocationTypeID');
            $table->string('LocationType');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Locations', function (Blueprint $table)
        {
            $table->primary('LocationID');
            $table->integer('LocationID');
            $table->integer('LocationTypeID');
            $table->integer('ParentLocationID');
            $table->string('Location');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Cities', function (Blueprint $table)
        {
            $table->primary('LocationID');
            $table->integer('LocationID');
            $table->string('CityProps');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Currencies', function (Blueprint $table)
        {
            $table->increments('ID');
            $table->string('CurrencyCode');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_CityCurrencies', function (Blueprint $table)
        {
            $table->increments('ID');
            $table->integer('CurrencyID');
            $table->string('CityID');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Destinations', function (Blueprint $table)
        {
            $table->primary('DestinationID');
            $table->integer('DestinationID');
            $table->string('Destination');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_DistanceUnits', function (Blueprint $table)
        {
            $table->primary('DistanceUnitID');
            $table->integer('DistanceUnitID');
            $table->string('DistanceUnit');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_CompositionRooms', function (Blueprint $table)
        {
            $table->primary('CompositionRoomID');
            $table->integer('CompositionRoomID');
            $table->string('CompositionRoom');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Amenities', function (Blueprint $table)
        {
            $table->primary('AmenityID');
            $table->integer('AmenityID');
            $table->string('Amenity');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_RoomAmenities', function (Blueprint $table)
        {
            $table->primary(['AmenityID', 'CompositionRoomID']);
            $table->integer('AmenityID');
            $table->string('CompositionRoomID');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_ImageTypes', function (Blueprint $table)
        {
            $table->primary('ImageTypeID');
            $table->integer('ImageTypeID');
            $table->string('ImageType');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PaymentMethods', function (Blueprint $table)
        {
            $table->primary('PaymentMethodID');
            $table->integer('PaymentMethodID');
            $table->string('PaymentMethod');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_ReservationStatuses', function (Blueprint $table)
        {
            $table->primary('ReservationStatusID');
            $table->integer('ReservationStatusID');
            $table->string('ReservationStatus');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_DepositTypes', function (Blueprint $table)
        {
            $table->primary('DepositTypeID');
            $table->integer('DepositTypeID');
            $table->string('DepositType');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_Languages', function (Blueprint $table)
        {
            $table->primary('LanguageID');
            $table->integer('LanguageID');
            $table->string('LanguageCode');
            $table->string('Language');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_PropExternalStatuses', function (Blueprint $table)
        {
            $table->primary('ID');
            $table->integer('ID');
            $table->string('Status');
            $table->timestamps();
        });
        Schema::create('RentalsUnited_ChangeoverTypes', function (Blueprint $table)
        {
            $table->primary('ChangeoverTypeID');
            $table->integer('ChangeOverTypeID');
            $table->string('ChangeOverType');
            $table->timestamps();
        });
    }

    private function downDictionaryTables()
    {
        Schema::drop('RentalsUnited_PropTypes');
        Schema::drop('RentalsUnited_LocationTypes');
        Schema::drop('RentalsUnited_Locations');
        Schema::drop('RentalsUnited_Cities');
        Schema::drop('RentalsUnited_Currencies');
        Schema::drop('RentalsUnited_CityCurrencies');
        Schema::drop('RentalsUnited_Destinations');
        Schema::drop('RentalsUnited_DistanceUnits');
        Schema::drop('RentalsUnited_CompositionRooms');
        Schema::drop('RentalsUnited_Amenities');
        Schema::drop('RentalsUnited_RoomAmenities');
        Schema::drop('RentalsUnited_ImageTypes');
        Schema::drop('RentalsUnited_PaymentMethods');
        Schema::drop('RentalsUnited_ReservationStatuses');
        Schema::drop('RentalsUnited_DepositTypes');
        Schema::drop('RentalsUnited_Languages');
        Schema::drop('RentalsUnited_PropExternalStatuses');
        Schema::drop('RentalsUnited_ChangeoverTypes');
    }
}
