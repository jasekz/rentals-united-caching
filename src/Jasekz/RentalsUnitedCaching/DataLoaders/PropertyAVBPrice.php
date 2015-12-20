<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyAVBPrice extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'GetPropertyAvbPrice';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyAVBPrice';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyAVBPrice.xml';

    /**
     * Cache RU data to DB
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb($propertyId = null, $dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');
        $dateTo = $dateTo ? $dateTo : date('Y-m-d', strtotime('+1 year'));
        
        $fileName = 'PropertyID_' . $propertyId . '_' . $this->fileName;
        
        $this->downloadXML($fileName, $propertyId, $dateFrom, $dateTo);
        
        try {
            DB::statement("delete from {$this->table} where PropID=?", array($propertyId));
            
            foreach ($this->getFileContents($fileName)->PropertyPrices->PropertyPrice as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set PropID=?, 
                            PropertyPrice=?,
                            NOP=?,
                            Cleaning=?,
                            ExtraPersonPrice=?,
                            Deposit=?,
                            SecurityDeposit=?,
                            created_at=?;";
                DB::statement($sql, array(
                    $propertyId,
                    (string) $record,
                    (string) $record->attributes()->NOP,
                    (string) $record->attributes()->Cleaning,    
                    (string) $record->attributes()->ExtraPersonPrice,
                    (string) $record->attributes()->Deposit,
                    (string) $record->attributes()->SecurityDeposit,
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
                throw new Exception($obj->Status);
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