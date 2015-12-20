<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class CompositionRooms extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListCompositionRooms';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_CompositionRooms';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'CompositionRooms.xml';

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
            
            foreach ($this->getFileContents($this->fileName)->CompositionRooms->CompositionRoom as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set CompositionRoomID=?, 
                            CompositionRoom=?,
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->attributes()->CompositionRoomID,
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