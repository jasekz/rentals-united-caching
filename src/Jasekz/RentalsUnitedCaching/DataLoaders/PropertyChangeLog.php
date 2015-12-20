<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class PropertyChangeLog extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListPropertyChangeLog';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_PropertyChangeLog';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'PropertyChangeLog.xml';

    /**
     * Cache RU data to DB
     *
     * @throws Exception
     * @return void
     */
    public function cacheInDb($propertyId = null)
    {
        $fileName = 'PropertyID_' . $propertyId . '_' . $this->fileName;
        
        // we always want a fresh copy of this
        $this->downloadXML($fileName, $propertyId);
        
        try {
            DB::statement("delete from {$this->table} where PropID=?", array(
                $propertyId
            ));
            
            $sql = "insert into 
                        {$this->table} 
                        set PropID=?, 
                            NLA=?,
                            IsActive=?,
                            StaticData=?,
                            Pricing=?,
                            Availability=?,
                            Image=?,
                            Description=?,
                            created_at=?;";
                        
            $record = $this->getFileContents($fileName)->ChangeLog;
            DB::statement($sql, array(
                $propertyId,
                (string) $record->attributes()->NLA == 'true' ? 1 : 0,
                (string) $record->attributes()->IsActive == 'true' ? 1 : 0,
                (string) $record->StaticData,
                (string) $record->Pricing,
                (string) $record->Availability,
                (string) $record->Image,
                (string) $record->Description,
                date('Y-m-d G:i:s')
            ));
            
            $this->deleteXML($fileName);
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}