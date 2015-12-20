<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class Cities extends Base  {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListCitiesProps';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_Cities';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'Cities.xml';
    
    protected  $primaryKey = 'LocationID';

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
            
            foreach ($this->getFileContents($this->fileName)->CitiesProps->CityProps as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set LocationID=?, 
                            CityProps=?, 
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->attributes()->LocationID,
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