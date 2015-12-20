<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class Destinations extends Base  {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListDestinations';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_Destinations';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'Destinations.xml';

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
            
            foreach ($this->getFileContents($this->fileName)->Destinations->Destination as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set DestinationID=?, 
                            Destination=?,
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->attributes()->DestinationID,
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