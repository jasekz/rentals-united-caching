<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class Buildings extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListBuildings';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_Buildings';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'Buildings.xml';

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
            
            foreach ($this->getFileContents($this->fileName)->Buildings->Building as $record) {
                
                $sql = "insert into 
                        {$this->table} 
                        set BuildingID=?, 
                            BuildingName=?, 
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->attributes()->BuildingID,
                    (string) $record->attributes()->BuildingName,
                    date('Y-m-d G:i:s')
                ));
                
                DB::statement("truncate BuildingProperties where BuildingsID=?", array((string) $record->attributes()->BuildingID));
                
                foreach ($this->getFileContents($this->fileName)->Buildings->Building->PropertyID as $prop) {
                    
                    $sql = "insert into 
                            BuildingProperties 
                            set PropID=?,
                                BuildingsID=?, 
                                created_at=?;";
                    DB::statement($sql, array(
                        (string) $prop,
                        (string) $record->attributes()->BuildingID,
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