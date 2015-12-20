<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyAvailabilityCalendar extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListPropertyAvailabilityCalendar';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyAvailabilityCalendar';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyAvailability.xml';

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

            foreach ($this->getFileContents($fileName)->PropertyCalendar->CalDay as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set PropID=?, 
                            Date=?,
                            IsBlocked=?,
                            MinStay=?,
                            Changeover=?,
                            created_at=?;";
                DB::insert($sql, array(
                    $propertyId,
                    (string) $record->attributes()->Date,
                    (string) $record->IsBlocked == 'true' ? 1 : 0,
                    (string) $record->MinStay,
                    (string) $record->Changeover,
                    date('Y-m-d G:i:s')
                ));
                
                $this->deleteXML($fileName);
            }
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