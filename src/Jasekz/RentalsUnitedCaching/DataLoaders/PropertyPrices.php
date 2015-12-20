<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyPrices extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListPropertyPrices';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyPrices';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyPrices.xml';

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
            DB::statement("delete from {$this->table} where PropID=?", array(
                $propertyId
            ));
            DB::statement("delete from RentalsUnited_PropertyPricesLOS where PropID=?", array(
                $propertyId
            ));
            DB::statement("delete from RentalsUnited_PropertyPricesLOSP where PropID=?", array(
                $propertyId
            ));
            DB::statement("delete from RentalsUnited_PropertyPricesEGP where PropID=?", array(
                $propertyId
            ));
            
            foreach ($this->getFileContents($fileName)->Prices->Season as $record) {
                
                $sql = "insert into 
                        {$this->table} 
                        set PropID=?, 
                            SeasonDateFrom=?,
                            SeasonDateTo=?,
                            SeasonPrice=?,
                            SeasonExtra=?,
                            created_at=?;";
                DB::statement($sql, array(
                    $propertyId,
                    (string) $record->attributes()->DateFrom,
                    (string) $record->attributes()->DateTo,
                    (string) $record->Price,
                    (string) $record->Extra,
                    date('Y-m-d G:i:s')
                ));
                
                $propertyPricesID = DB::connection()->getPdo()->lastInsertId();
                
                if( isset($record->LOSS->LOS)) {
                    foreach ($record->LOSS->LOS as $los) {
                        
                        $sql = "insert into 
                                RentalsUnited_PropertyPricesLOS
                                set PropID=?, 
                                    PropertyPricesID=?,
                                    Nights=?,
                                    Price=?,
                                    created_at=?;";
                        DB::statement($sql, array(
                            $propertyId,
                            $propertyPricesID,
                            (string) $los->attributes()->Nights,
                            (string) $los->Price,
                            date('Y-m-d G:i:s')
                        ));
                
                        $propertyPricesLOSID = DB::connection()->getPdo()->lastInsertId();
                
                        if( isset($los->LOSPS->LOSP)) {
                            foreach ($los->LOSPS->LOSP as $losp) {
                                
                                $sql = "insert into 
                                        RentalsUnited_PropertyPricesLOSP
                                        set PropID=?, 
                                            PropertyPricesID=?,
                                            PropertyPricesLOSID=?,
                                            NrOfGuests?,
                                            Price=?,
                                            created_at=?;";
                                DB::statement($sql, array(
                                    $propertyId,
                                    $propertyPricesID,
                                    $propertyPricesLOSID,
                                    (string) $los->attributes()->NrOfGuests,
                                    (string) $los->Price,
                                    date('Y-m-d G:i:s')
                                ));
                            }
                        }
                    }
                }
                
                if( isset($record->EGPS->EGP)) {
                    foreach ($record->EGPS->EGP as $egp) {
                        
                        $sql = "insert into 
                                RentalsUnited_PropertyPricesEGP
                                set PropID=?, 
                                    PropertyPricesID=?,
                                    ExtraGuests=?,
                                    Price=?,
                                    created_at=?;";
                        DB::statement($sql, array(
                            $propertyId,
                            $propertyPricesID,
                            (string) $egp->attributes()->ExtraGuests,
                            (string) $egp->Price,
                            date('Y-m-d G:i:s')
                        ));
                    }
                }
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