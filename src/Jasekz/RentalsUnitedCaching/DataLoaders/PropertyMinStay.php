<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyMinStay extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListPropertyMinStay';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyMinStay';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyMinStay.xml';

    /**
     * Cache RU data to DB
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb($propertyId = null)
    {
        $fileName = 'PropertyID_' . $propertyId . '_' . $this->fileName;
        
        $this->downloadXML($fileName, $propertyId, date('Y-m-d'), date('Y-m-d', strtotime('+1 year')));
        
        try {
            $sql = "delete from {$this->table} where PropID=?;";
            DB::statement($sql, array(
                $propertyId
            ));

            foreach ($this->getFileContents($fileName)->PropertyMinStay->MinStay as $record) {
                
                $sql = "insert into 
                        {$this->table} 
                        set PropID=?, 
                            MinStay=?,
                            DateFrom=?,
                            DateTo=?,
                            created_at=?;";
                DB::statement($sql, array(
                    $propertyId,
                    (string) $record,
                    (string) $record->attributes()->DateFrom,
                    (string) $record->attributes()->DateTo,
                    date('Y-m-d G:i:s')
                ));
            }
            
            $this->deleteXML($fileName);
        } 

        catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Download and store XML file from RU
     * Each service can override this function and provde the RU service that should be called, if needed
     *
     * @param
     *            mixed Argument to pass on to RU
     * @throws Exception
     * @return void
     */
    public function downloadXML($fileName, $propertyId = null, $dateFrom = null, $dateTo = null)
    {
        try {
            $xml = $this->ru->{$this->ruFunction}($propertyId, $dateFrom, $dateTo);
            
            $obj = simplexml_load_string($xml['messages']);
            
            if ((string) $obj->Status != 'Success') {
                throw new Exception('Error downloading xml file.');
            }
            
            $h = fopen($this->getCacheDir() . $fileName, 'w');
            fwrite($h, $xml['messages']);
            fclose($h);
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}