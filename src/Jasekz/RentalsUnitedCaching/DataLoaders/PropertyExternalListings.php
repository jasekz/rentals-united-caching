<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyExternalListings extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'GetPropertyExternalListing';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyExternalListings';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyExternalListings.xml';

    /**
     * Cache RU data to DB
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb($propertyID)
    {
        $this->downloadXML($this->fileName, $propertyID);
        
        try {
            $sql = "delete from {$this->table} where PropID=?;";
            DB::statement($sql, array(
                $propertyID
            ));
            
            foreach ($this->getFileContents($this->fileName)->Property as $property) {
                
                foreach ($property->ExternalListing as $listing) {
                    
                    $sql = "insert into 
                            {$this->table} 
                            set PropID=?,
                                Url=?,
                                Status=?,
                                Description=?,
                                created_at=?;";
                    DB::statement($sql, array(
                        $propertyID,
                        (string) $listing->Url,
                        (string) $listing->Status,
                        (string) $listing->Description,
                        date('Y-m-d G:i:s')
                    ));
                }
                
                foreach ($property->Notifs->Notif as $notif) {
                    
                    $sql = "insert into 
                            {$this->table} 
                            set PropID=?,
                                Url=?,
                                StatusID=?,
                                created_at=?;";
                    DB::statement($sql, array(
                        $propertyID,
                        (string) $notif->Url,
                        (string) $notif->StatusID,
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