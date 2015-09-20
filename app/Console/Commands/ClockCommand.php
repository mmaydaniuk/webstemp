<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

//
// The clock command is the primary engine command that updates the game status on a regular
// basis.
//
//
class ClockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stemp:clock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the game clock one cycle.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function moveFleets() {

        // foreach fleet

        // if moving

        // update position

            // if we have moved in conflict with someone, create a new battle record
    }

    public function updateBattles() {
        // foreach battle

        // get combatants

        // create list of aggressors and defenders, need current HP and firepower

        // battle algorithm currently gives all ships a chance to shoot - even if they receive a shot
        // that destroys them in the main loop

        // foreach ship

            // pick a target from opposing side

            // shoot it - update hitpoints of target


        // foreach ship

            // if hitpoints < 1, destroy it
    }

    //
    //
    public function updateProduction() {
        // get planets, sort by owner

        // foreach planet

            // bz += planet_class * random(1000);

        // update owner bank account 
    }


    public function updateManufacturing() {
        // foreach in production ship

        // increment amount complete

        // if amount complete > amount required, ship is ready
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $redis = Redis::connection();

        $clock = $redis->get('stemp:clock');

        if ($clock == null) {
            $redis->set('stemp:clock',0);
        }

        $clock++;

        // perform less time critical updates on a limited schedule
        //
        if ($clock % 10 == 10) {
            // update production
            $this->updateProduction();
            $this->updateManufacturing();
        }

        //
        // update battles on a lower priority
        if ($clock % 10 == 10) {
            // update any in progress battles
            $this->updateBattles();
        }

        // move all in transit fleets
        $this->moveFleets();


        $redis->set('stemp:clock',$clock);

        // TODO: do we need a timeout here?  
    }
}
