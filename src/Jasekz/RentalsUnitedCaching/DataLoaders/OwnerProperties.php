<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class OwnerProperties extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListOwnerProp';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_Prop';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'Properties.xml';

    /**
     * Cache RU data to DB
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb($ownerId = null)
    {
        $fileName = 'OwnerID_' . $ownerId . '_' . $this->fileName;
        
        $this->downloadXML($fileName, $ownerId);
        
        try {
            foreach ($this->getFileContents($fileName)->Properties->Property as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set ID=?, 
                            Name=?, 
                            OwnerID=?,
                            DetailedLocationID=?,
                            LastMod=?,
                            DateCreated=?,
                            IMAP=?,
                            IMU=?,
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->ID,
                    (string) $record->Name,
                    (string) $record->OwnerID,
                    (string) $record->DetailedLocationID,
                    (string) $record->LastMod,
                    (string) $record->DateCreated,
                    (string) $record->IMAP,
                    (string) $record->IMU,
                    date('Y-m-d G:i:s')
                ));
            }
            
            $this->deleteXML($fileName);
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}