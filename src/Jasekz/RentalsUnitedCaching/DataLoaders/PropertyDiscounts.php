<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyDiscounts extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListPropertyDiscounts';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyDiscountsLongStays';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyDiscounts.xml';

    /**
     * Cache RU data to DB
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb($propertyId = null)
    {
        $fileName = 'PropertyID_' . $propertyId . '_' . $this->fileName;
        
        $this->downloadXML($fileName, $propertyId);
        
        try {
            DB::statement("delete from {$this->table} where PropID=?", array($propertyId));
            DB::statement("delete from RentalsUnited_PropertyDiscountsLastMinutes where PropID=?", array($propertyId));
            
            foreach ($this->getFileContents($fileName)->Discounts->LongStays->LongStay as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set PropID=?, 
                            DateFrom=?,
                            DateTo=?,
                            Bigger=?,
                            Smaller=?,
                            LongStay=?, 
                            created_at=?;";
                DB::statement($sql, array(
                    $propertyId,
                    (string) $record->attributes()->DateFrom,
                    (string) $record->attributes()->DateTo,
                    (string) $record->attributes()->Bigger,
                    (string) $record->attributes()->Smaller,
                    (string) $record,
                    date('Y-m-d G:i:s')
                ));
            }
            
            foreach ($this->getFileContents($fileName)->Discounts->LastMinutes->LastMinute as $record) {

                $sql = "insert into 
                        RentalsUnited_PropertyDiscountsLastMinutes
                        set PropID=?, 
                            DateFrom=?,
                            DateTo=?,
                            DaysArrivalFrom=?,
                            DaysArrivalTo=?,
                            LastMinute=?, 
                            created_at=?;";
                DB::statement($sql, array(
                    $propertyId,
                    (string) $record->attributes()->DateFrom,
                    (string) $record->attributes()->DateTo,
                    (string) $record->attributes()->DaysToArrivalFrom,
                    (string) $record->attributes()->DaysToArrivalTo,
                    (string) $record,
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