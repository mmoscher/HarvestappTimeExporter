<?php
/**
 * HarvestappTimeExporter
 *
 * client_test.php created at ..
 *
 *      Date: 20.11.13
 *      Time: 17:51
 *
 * .. with love by ..
 *
 *      User: Marco Moscher
 *
 * MIT Licence
 */

require_once( "./lib/MyHarvest.php");

$api = new MyHarvest();

//query all clients

$clientResult = $api->getClients();
if( $clientResult->isSuccess() ) {
    //print clients
    foreach( $clientResult->data as $client ) {
        echo "Client: " . $client->name . "<br/>";
        echo "<pre>";
        print_r($client);
        echo "</pre>";
        echo "<hr>";
        //fetch project datat

        $projects = $api->getClientProjects( $client->id );
        //echo "<pre>";
      //  print_r($projects);
       // echo "</pre>";

        $projectsTimes = array();

        $timerange = new Harvest_Range( date('Ym01'), date('Ymd') );
        var_dump( $timerange );

        foreach( $projects->data as $project ) {
            $tmpProjectData = array();
            $tmpProjectData["name"] = $project->name;
            $tmpProjectData["id"] = $project->id;
            $tmpProjectData["tracks"] = array();



            $entries = $api->getProjectEntries( $project->id, $timerange );

           // echo $entries->code;
            //var_dump($entries);
            //break;

            if( $entries->isSuccess() ) {
                foreach( $entries->data as $projectTimetrack ) {
                    $tmpProjectData["tracks"][] = $projectTimetrack;
                }
            }else{
                echo "failure";
            }



            $projectsTimes[] = (object)$tmpProjectData;

            //break;
        }

        echo "<pre>";
        print_r($projectsTimes);
        echo "</pre>";
        break;
    }

}else{
    echo "something went wrong <br/>";
}

?>