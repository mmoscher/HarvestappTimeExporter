<?php
/**
 * HarvestappTimeExporter
 *
 * MyHarvest.php created at ..
 *
 *      Date: 20.11.13
 *      Time: 18:18
 *
 * .. with love by ..
 *
 *      User: Marco Moscher
 *
 * MIT Licence
 */

require_once( "./lib/HaPi/HarvestAPI.php");
spl_autoload_register(array('HarvestAPI', 'autoload') );

//require config
require_once("./lib/config.secret.php");

class MyHarvest extends HarvestAPI {

    function __construct() {

        //parent::__construct(); -- parent has no constructor

        global $HARVEST_ACCOUNT;

        $this->setUser( $HARVEST_ACCOUNT["user"] );
        $this->setPassword( $HARVEST_ACCOUNT["password"] );
        $this->setAccount( $HARVEST_ACCOUNT["account"] );

        $this->setSSL( true );

        $this->setRetryMode( HarvestAPI::RETRY );

        //ready to go
    }


}
?>