<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class Owners extends Base {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListAllOwners';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_Owners';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'Owners.xml';

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
            
            foreach ($this->getFileContents($this->fileName)->Owners->Owner as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set OwnerID=?, 
                            FirstName=?,
                            SurName=?,
                            Email=?,
                            Phone=?,
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->attributes()->OwnerID,
                    (string) $record->FirstName,
                    (string) $record->SurName,
                    (string) $record->Email,
                    (string) $record->Phone,
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