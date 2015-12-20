<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class Locations extends Base  {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListLocations';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_Locations';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'Locations.xml';

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
            
            foreach ($this->getFileContents($this->fileName)->Locations->Location as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set LocationID=?, 
                            LocationTypeID=?, 
                            ParentLocationID=?,
                            Location=?,
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->attributes()->LocationID,
                    (string) $record->attributes()->LocationTypeID,
                    (string) $record->attributes()->ParentLocationID,
                    (string) $record,
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