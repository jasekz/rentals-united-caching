<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyBasePrice extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListPropertyBasePrice';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyBasePrice';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyBasePrice.xml';

    /**
     * Cache RU data to DB
     * 
     * As per RU, the 'ListPropertyBasePrice' should not be used.  Instead, use 'ListPropertyPrices'.
     * 
     * "To my best knowledge, we'll be abandoning Base Prices and only use seasonal prices from that point on. 
     *  Most probably the property has no base prices whatsoever. Therefore, if possible, please don't use that 
     *  method to prevent complications later on."
     *  
     *  Because of this, we will use seasonal prices and 'convert' them to base pricing.
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb($propertyId = null)
    {
        $fileName = 'PropertyID_' . $propertyId . '_' . $this->fileName;
        
        $this->ruFunction = 'ListPropertyPrices';
        
        $this->downloadXML($fileName, $propertyId, date('Y-m-d'), date('Y-m-d', strtotime('+1 year')));
        
        // get the lowest price
        $lowestPrice = null;
        $lowestPriceExtra = null;
        foreach($this->getFileContents($fileName)->Prices->Season as $seasonPrice) {
            
            if($lowestPrice === null) {
                $lowestPrice = (float) $seasonPrice->Price;
                $lowestPriceExtra = (float) $seasonPrice->Extra;
                continue;
            }
            
            if((float) $seasonPrice->Price < $lowestPrice) {
                $lowestPrice = (float) $seasonPrice->Price;
                $lowestPriceExtra = (float) $seasonPrice->Extra;
            }
        }
        
        
        try {
            DB::statement("delete from {$this->table} where PropID=?", array($propertyId));
            
            for($i = 0; $i < 7; $i++) {

                $sql = "insert into 
                        {$this->table} 
                        set PropID=?, 
                            DayOfWeek=?,
                            BasePrice=?,
                            BasePriceExtra=?, 
                            created_at=?;";
                DB::statement($sql, array(
                    $propertyId,
                    (string) $i,
                    (string) $lowestPrice,
                    (string) $lowestPriceExtra,                    
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


    /**
     * Cache RU data to DB - THIS IS THE ORIGINAL FUNCTION.  DO NOT USE.
     *
     * As per RU, the 'ListPropertyBasePrice' should not be used.  Instead, use 'ListPropertyPrices'.
     *
     * "To my best knowledge, we'll be abandoning Base Prices and only use seasonal prices from that point on.
     *  Most probably the property has no base prices whatsoever. Therefore, if possible, please don't use that
     *  method to prevent complications later on."
     *
     *  Because of this, we will use seasonal prices and 'convert' them to base pricing.
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb_Deprecated($propertyId = null)
    {
        $fileName = 'PropertyID_' . $propertyId . '_' . $this->fileName;
        
        $this->downloadXML($fileName, $propertyId);
        
        try {
            DB::statement("delete from {$this->table} where PropID=?", array($propertyId));
            
            foreach ($this->getFileContents($fileName)->PropertyBasePrices->BasePrice as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set PropID=?, 
                            DayOfWeek=?,
                            BasePrice=?,
                            BasePriceExtra=?, 
                            created_at=?;";
                DB::statement($sql, array(
                    $propertyId,
                    (string) $record->attributes()->DayOfWeek,
                    (string) $record->attributes()->Price,
                    (string) $record->attributes()->Extra,                    
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