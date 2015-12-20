<?php
namespace Jasekz\RentalsUnitedCaching\DataLoaders;

use RentalsUnited;
use Jasekz\RentalsUnitedCaching\Lib\RentalsUnited as RentalsUnitedLib;
use Exception;
use DB;
use File;

class Base {

    /**
     * Constructor
     *
     * @param mixed $args            
     */
    public function __construct($args = null)
    {
        $this->ru = new RentalsUnitedLib(config('rentals_united_caching.RENTALS_UNITED_USERNAME'), config('rentals_united_caching.RENTALS_UNITED_PASSWORD'));
    }

    /**
     * Return the cache dir path
     *
     * @return string Path to cache dir (set in the .env file)
     */
    protected function getCacheDir()
    {
        return config('rentals_united_caching.XML_CACHE_DIR');
    }

    /**
     * Download and store XML file from RU
     * Each service can override this function and provde the RU service that should be called, if needed
     *
     * @param string The file name
     * @param $arg1 mixed argument to pass on to RU
     * @throws Exception
     * @return void
     */
    public function downloadXML($fileName, $arg1 = null)
    {
        try {
            $xml = $this->ru->{$this->ruFunction}($arg1);
            
            $obj = simplexml_load_string($xml['messages']);
            
            if ((string) $obj->Status != 'Success') {
                throw new Exception('Error downloading xml file.');
            }
            echo "FileName (downloadXML): {$fileName}\r\n";
                
                // create cache dir, if it doesn't exist
            if (! File::exists($this->getCacheDir())) {
                File::makeDirectory($this->getCacheDir());
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
     * Download and store XML file from RU
     * Each service can override this function and provde the RU service that should be called, if needed
     *
     * @param string The file name
     * @throws Exception
     * @return void
     */
    public function deleteXML($fileName = null)
    {
        try {
            if (file_exists($this->getCacheDir() . $fileName)) {
                unlink($this->getCacheDir() . $fileName);
            }
        } 

        catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Load xml file, transform to simplexml object, and return
     *
     * @param string The file name
     * @return boolean|Simplexml
     */
    protected function getFileContents($fileName)
    {
        if (! file_exists($this->getCacheDir() . $fileName)) {
            return false;
        }
        
        return simplexml_load_file($this->getCacheDir() . $fileName);
    }

    /**
     * Cache all Rentals United data
     * This should be used initially to clone the whole RU database
     *
     * @return void
     */
    public function cacheAll()
    {
        // truncate all tables
        $currentDB = DB::connection()->getDatabaseName();
        
        // dictionary data
        RentalsUnited::dataLoader('amenities')->cacheInDb();
        RentalsUnited::dataLoader('propTypes')->cacheInDb();
        RentalsUnited::dataLoader('locationTypes')->cacheInDb();
        RentalsUnited::dataLoader('locations')->cacheInDb();
        RentalsUnited::dataLoader('cities')->cacheInDb();
        RentalsUnited::dataLoader('cityCurrencies')->cacheInDb();
        RentalsUnited::dataLoader('destinations')->cacheInDb();
        RentalsUnited::dataLoader('distanceUnits')->cacheInDb();
        RentalsUnited::dataLoader('compositionRooms')->cacheInDb();
        RentalsUnited::dataLoader('roomAmenities')->cacheInDb();
        RentalsUnited::dataLoader('imageTypes')->cacheInDb();
        RentalsUnited::dataLoader('paymentMethods')->cacheInDb();
        RentalsUnited::dataLoader('reservationStatuses')->cacheInDb();
        RentalsUnited::dataLoader('depositTypes')->cacheInDb();
        RentalsUnited::dataLoader('depositTypes')->cacheInDb();
        RentalsUnited::dataLoader('languages')->cacheInDb();
        RentalsUnited::dataLoader('propExternalStatuses')->cacheInDb();
        RentalsUnited::dataLoader('changeoverTypes')->cacheInDb();
        
        echo "Dictionary data cached\r\n";
        
        // static data
        RentalsUnited::dataLoader('owners')->cacheInDb();
        RentalsUnited::dataLoader('buildings')->cacheInDb();
        RentalsUnited::dataLoader('agents')->cacheInDb();
        
        $owners = RentalsUnited::owners()->all();
        
        if (! $owners->isEmpty()) {
            foreach ($owners as $owner) {
                try {
                    RentalsUnited::dataLoader('ownerProperties')->cacheInDb($owner->OwnerID);
                } 

                catch (Exception $e) {
                    echo $e->getMessage() . "\r\n";
                }
            }
        }
        
        echo "Static data cached (excluding properties)\r\n";
        
        // properties
        $properties = RentalsUnited::prop()->all();
        $count = 0;
        if (! $properties->isEmpty()) {
            echo "Total properties to cache: {$properties->count()}\r\n";
            foreach ($properties as $property) {
                
                echo "Caching property $property->ID\r\n";
                RentalsUnited::dataLoader('propertyChangeLog')->cacheInDb($property->ID);
                
                try {
                    $this->cacheProp($property->ID);
                    $count ++;
                    echo "{$count}) Property $property->ID cached\r\n";
                } 

                catch (Exception $e) {
                    echo $e->getMessage() . "\r\n";
                    continue;
                }
            }
        }
        
        echo "Finished.  Total properties cached: {$count}\r\n";
    }

    /**
     * Cache property static data
     *
     * @param int $propertyID
     * @return void
     */
    public function cacheProp($propertyID)
    {
        try {
            RentalsUnited::dataLoader('prop')->cacheInDb($propertyID);
            RentalsUnited::dataLoader('propertyExternalListings')->cacheInDb($propertyID);
            RentalsUnited::dataLoader('propertyExternalListingsNotifs')->cacheInDb($propertyID);
            RentalsUnited::dataLoader('propertyReviews')->cacheInDb($propertyID);
            RentalsUnited::dataLoader('propertyChangeLog')->cacheInDb($propertyID);
            $this->cachePropAvb($propertyID);
            $this->cachePropPricing($propertyID);
        } 

        catch (Exception $e) {
            echo "Exception: {$e->getMessage()}\r\n";
            throw $e;
        }
    }

    /**
     * Cache property pricing
     *
     * @param int $propertyID
     * @throws Exception
     * @return void
     */
    public function cachePropPricing($propertyID)
    {
        RentalsUnited::dataLoader('propertyBasePrice')->cacheInDb($propertyID);
        RentalsUnited::dataLoader('propertyPrices')->cacheInDb($propertyID);
        RentalsUnited::dataLoader('propertyDiscounts')->cacheInDb($propertyID);
        
        try {
            RentalsUnited::dataLoader('propertyAVBPrice')->cacheInDb($propertyID, date('Y-m-d'), date('Y-m-d', strtotime('+1 year')));
        } 

        catch (Exception $e) {
            echo 'propertyAVBPrice: ' . $e->getMessage() . ' ' . date('Y-m-d') . ' through ' . date('Y-m-d', strtotime('+1 year')) . "\r\n";
        }
    }

    /**
     * Cache property availability
     *
     * @param int $propertyID
     * @return void
     */
    public function cachePropAvb($propertyID)
    {
        try {
            RentalsUnited::dataLoader('propertyBlocks')->cacheInDb($propertyID);
            RentalsUnited::dataLoader('propertyAvailabilityCalendar')->cacheInDb($propertyID);
            RentalsUnited::dataLoader('propertyMinStay')->cacheInDb($propertyID);
        } 

        catch (Exception $e) {
            echo "Exception: {$e->getMessage()}\r\n";
            throw $e;
        }
    }

    /**
     * Cache property change log
     *
     * @param $lastCached - Check for changes since this date
     * @return void
     */
    public function updateChangeLog($lastCached = null)
    {
        $lastCached = $lastCached ? $lastCached : date('Y-m-d h:i:s');
        $cached = false;
        
        // update existing changelog entries
        $results = RentalsUnited::changeLog()->where('created_at', '<', $lastCached)->orderBy('PropID');
        if ($results->count() == 0) {
            echo "No change log entries to update.\r\n";
            return true;
        }
        
        foreach ($results->get() as $result) {
            
            try {
                echo "Updating change log for {$result->prop->ID}\r\n";
                RentalsUnited::dataLoader('propertyChangeLog')->cacheInDb($result->prop->ID);
                $cached = true;
            } 

            catch (Exception $e) {
                echo 'PropID: ' . $result->prop->ID . ' ' . $e->getMessage() . "\r\n";
                continue;
            }
        }
        
        // add new changelog entries, if needed
        $sql = "SELECT p.ID, p.name, p.created_at
                FROM RentalsUnited_Prop p
                LEFT JOIN RentalsUnited_PropertyChangeLog pcl ON p.ID = pcl.PropID
                WHERE pcl.PropId IS NULL 
                order by p.created_at desc";
        $results = DB::select($sql);
        if($results) {
            foreach($results as $result) {
            
                try {
                    echo "Adding new change log for {$result->ID}\r\n";
                    RentalsUnited::dataLoader('propertyChangeLog')->cacheInDb($result->ID);
                    $cached = true;
                } 
    
                catch (Exception $e) {
                    echo 'PropID: ' . $result->ID . ' ' . $e->getMessage() . "\r\n";
                    continue;
                }
            }
        }
        
        if (! $cached) {
            echo "No change logs to update\r\n";
        }
    }

    /**
     * Run property updates
     *
     * @param $lastCached - Check for changes since this date
     * @return void
     */
    public function updateProperties($lastCached = null)
    {
        $lastCached = $lastCached ? $lastCached : date('Y-m-d G:i:s');
        $cached = false;

        // avb        
        $results = RentalsUnited::changeLog()->where('Availability', '>', $lastCached)->orderBy('PropID');
        if ($results->count() == 0) {
            echo "No availability to update for any properties.\r\n";
        } else {
        
            foreach ($results->get() as $result) {

                try {
                    echo "Caching property {$result->prop->ID} availability\r\n";
                    $this->cachePropAvb($result->prop->ID);
                    $result->prop->created_at = date('Y-m-d G:i:s');
                    $result->prop->save();
                    $cached = true;
                } 
    
                catch (Exception $e) {
                    echo 'Can not update property avb ID: ' . $result->prop->ID . ' ' . $e->getMessage() . "\r\n";
                    continue;
                }
            }
        }
        
        
        // pricing         
        $results = RentalsUnited::changeLog()->where('Pricing', '>', $lastCached)->orderBy('PropID');
        if ($results->count() == 0) {
            echo "No pricing to update for any properties.\r\n";
        } else {
        
            foreach ($results->get() as $result) {
                
                try {
                    echo "Caching property {$result->prop->ID} pricing\r\n";
                    $this->cachePropPricing($result->prop->ID);
                    $result->prop->created_at = date('Y-m-d G:i:s');
                    $result->prop->save();
                    $cached = true;
                } 
    
                catch (Exception $e) {
                    echo 'Can not update property pricing ID: ' . $result->prop->ID . ' ' . $e->getMessage() . "\r\n";
                    continue;
                }
            }
        }
        
        
        // static         
        $results = RentalsUnited::changeLog()->where('StaticData', '>', $lastCached)->orderBy('PropID');
        if ($results->count() == 0) {
            echo "No static data to update for any properties.\r\n";
        } else {
        
            foreach ($results->get() as $result) {
                
                try {
                    echo "Caching property {$result->prop->ID} static data\r\n";
                    $this->cacheProp($result->prop->ID);
                    $cached = true;
                } 
    
                catch (Exception $e) {
                    echo 'Can not update property static data ID: ' . $result->prop->ID . ' ' . $e->getMessage() . "\r\n";
                    continue;
                }
            }
        } 
        
        if (! $cached) {
            echo "No properties to update\r\n";
        }
    }
}