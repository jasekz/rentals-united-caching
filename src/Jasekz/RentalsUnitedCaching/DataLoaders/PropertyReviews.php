<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyReviews extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListPropertyReviews';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyReviews';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyReviews.xml';

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
            
            foreach ($this->getFileContents($this->fileName)->Reviews->Review as $record) {
                
                $sql = "insert into 
                            {$this->table} 
                            set PropID=?,
                                ReviewID=?,
                                FirstName=?,
                                LastName=?,
                                DisplayName=?,
                                Email=?,
                                Rating=?,
                                ArrivalDate=?,
                                Submitted=?,
                                created_at=?;";
                DB::statement($sql, array(
                    $propertyID,
                    (string) $record->attributes()->ID,
                    (string) $record->FirstName,
                    (string) $record->LastName,
                    (string) $record->DisplayName,
                    (string) $record->Email,
                    (string) $record->Rating,
                    (string) $record->ArrivalDate,
                    (string) $record->Submitted,
                    date('Y-m-d G:i:s')
                ));
            }
            
            $this->deleteXML($this->fileName);
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}