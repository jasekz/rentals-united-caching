<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use DB;
use Exception;

class CityCurrencies extends Base  {

    /**
     * RU API function to call
     *
     * @var string
     */
    protected $ruFunction = 'ListCurrenciesWithCities';

    /**
     * Cached file name
     *
     * @var string
     */
    protected $fileName = 'CityCurrencies.xml';

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
            DB::statement("truncate RentalsUnited_Currencies");
            DB::statement("truncate RentalsUnited_CityCurrencies");

            foreach ($this->getFileContents($this->fileName)->Currencies->Currency as $record) {

                $sql = "insert into 
                        RentalsUnited_Currencies 
                        set CurrencyCode=?, 
                            created_at=?;";
                DB::insert($sql, array(
                    (string) $record->attributes()->CurrencyCode,
                    date('Y-m-d G:i:s')
                ));  
                $currencyId = DB::connection()->getPdo()->lastInsertId();
                
                foreach($record->Locations->LocationID as $location) {
                    $sql = "insert into 
                            RentalsUnited_CityCurrencies 
                            set CityID=?, 
                                CurrencyID=?,
                                created_at=?;";
                    DB::statement($sql, array(
                        (string) $location,
                        $currencyId,
                        date('Y-m-d G:i:s')
                    ));
                }
            }
            
            $this->deleteXML($this->fileName);
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}