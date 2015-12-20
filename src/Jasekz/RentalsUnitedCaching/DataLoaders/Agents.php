<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class Agents extends Base  {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'GetAgents';

    /**
     * DB table where we'll be caching the data
     *
     * @var string
     */
    protected $table = 'RentalsUnited_Agents';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'Agents.xml';

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
            DB::statement("truncate RentalsUnited_OwnerAgents");

            foreach ($this->getFileContents($this->fileName)->Agents->Agent as $record) {

                $sql = "insert into 
                        {$this->table} 
                        set AgentID=?, 
                            UserName=?,
                            CompanyName=?,
                            FirstName=?,
                            SurName=?,
                            Email=?,
                            Telephone=?,
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->AgentID,
                    (string) $record->UserName,
                    (string) $record->CompanyName,
                    (string) $record->FirstName,
                    (string) $record->SurName,
                    (string) $record->Email,
                    (string) $record->Telephone,
                    date('Y-m-d G:i:s')
                ));

                $sql = "insert into 
                        RentalsUnited_OwnerAgents
                        set AgentID=?, 
                            OwnerID=?,
                            created_at=?;";
                DB::statement($sql, array(
                    (string) $record->AgentID,
                    (string) $this->getFileContents($this->fileName)->Owner->attributes()->OwnerID,
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