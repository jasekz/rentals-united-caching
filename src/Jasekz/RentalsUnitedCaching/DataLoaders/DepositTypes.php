<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class DepositTypes extends Base  {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListDepositTypes';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_DepositTypes';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'DepositTypes.xml';

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
            
            foreach ($this->getFileContents($this->fileName)->DepositTypes->DepositType as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set DepositTypeID=?, 
                            DepositType=?, 
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->attributes()->DepositTypeID,
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