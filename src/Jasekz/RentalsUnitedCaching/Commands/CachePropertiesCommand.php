<?php
namespace Jasekz\RentalsUnitedCaching\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use RentalsUnited;


class CachePropertiesCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rentals_united:cache_properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache specified property. Ex: rentals_united:cache_properties --id=4';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {        
        /*
         * Option to cache specified properties (ID)
         * 
         * Examples:
         * artisan rentals_united:cache_properties --id=4 // cache property (ID) 4
         * artisan rentals_united:cache_properties --id=4,5 // cache properties (ID) 4 & 5
         * artisan rentals_united:cache_properties --id=new // find and cache all new properties
         * 
         */ 

        $ids = null;
        if($this->option('id')) {
            $ids = explode(',', $this->option('id'));
        }
        
        if( ! $ids && ! $this->option('new')) {
            die("Please specify property id(s):\r\n"
                . "Examples:\r\n"
                . "artisan rentals_united:cache_properties --id=4 // cache property (ID) 4\r\n"
                . "artisan rentals_united:cache_properties --id=4,5 // cache properties (ID) 4 & 5\r\n"
                . "artisan rentals_united:cache_properties --new // find and cache all new properties\r\n");
        }
        
        if($this->option('new')) {
            try {
                $ids = [];
                $props = RentalsUnited::dataLoader('prop')->getPropByCreationDate(date('Y-m-d', strtotime('-2 hours')), date('Y-m-d'));
                if($props->Properties->Property->count() > 0) {
                    foreach($props->Properties->Property as $property) {
                       $ids[] = (int) $property->ID;
                    }
                }
            }
            
            catch (Exception $e) {
                die($e->getMessage());
            }
        }
                
        if($ids) {
            foreach($ids as $id) {
                RentalsUnited::dataLoader()->cacheProp($id);
            }
        }
    }
 
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            array('id', 'id', InputOption::VALUE_OPTIONAL),
            array('new', 'new', InputOption::VALUE_NONE),
        );
    }
}
