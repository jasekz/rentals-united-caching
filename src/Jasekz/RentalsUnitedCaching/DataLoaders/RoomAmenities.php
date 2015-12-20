<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class RoomAmenities extends Base  {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListAmenitiesAvailableForRooms';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_RoomAmenities';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'RoomAmenities.xml';

    /**
     * Cache RU data to DB
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb()
    {
        $this->downloadXML($this->fileName);
        
        try {
            DB::statement("truncate {$this->table}");

            foreach ($this->getFileContents($this->fileName)->AmenitiesAvailableForRooms->AmenitiesAvailableForRoom as $record) {
 
                foreach($record->Amenity as $amenity) {

                    $sql = "insert into 
                        {$this->table} 
                        set AmenityID=?, 
                            CompositionRoomID=?, 
                            created_at=?;";
                    DB::statement($sql, array(
                        (string) $amenity->attributes()->AmenityID,
                        (string) $record->attributes()->CompositionRoomID,
                        date('Y-m-d G:i:s')
                    ));
                }
            }
            
            $this->deleteXML($this->fileName);
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}