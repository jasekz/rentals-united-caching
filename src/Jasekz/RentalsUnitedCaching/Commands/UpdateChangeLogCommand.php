<?php
namespace Jasekz\RentalsUnitedCaching\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use RentalsUnited;


class UpdateChangeLogCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rentals_united:update_change_log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache property change log.  Ex: rentals_united:update_change_log --since="-1 month"';

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
        $datetime = null;
        
        
        /*
         * Option to check for updates 'since' given date/time
         * Argument passed in can be an valid php strtotime arg - http://php.net/manual/en/function.strtotime.php
         * 
         * Examples:
         * artisan rentals_united:update_change_log --since="-1 month"
         * artisan rentals_united:update_change_log --since="2014-03-26 12:51:00"
         * 
         * When no option is passed, the default is -10 minutes
         */ 
        if($this->option('since')) {
            $datetime = date('Y-m-d G:i:s', strtotime($this->option('since')));
        }
        
        // Update change logs for all properties
        RentalsUnited::dataLoader()->updateChangeLog( $datetime );
    }
 
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            array('since', 'since', InputOption::VALUE_OPTIONAL),
        );
    }
}
