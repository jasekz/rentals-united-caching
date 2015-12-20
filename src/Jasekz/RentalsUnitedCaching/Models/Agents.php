<?php
namespace Jasekz\RentalsUnitedCaching\Models;

use DB;

class Agents extends Base  {

    protected $table = 'RentalsUnited_Agents';
    
    protected  $primaryKey = 'AgentID';
    
    public function owners()
    {
        return $this->belongsToMany('Jasekz\RentalsUnitedCaching\Models\Owners', 'RentalsUnited_OwnerAgents', 'AgentsID', 'OwnersID');
    }
    
}