<?php

require_once( "./lib/MyHarvest.php");

$api = new MyHarvest();

//query all clients

$clientResult = $api->getClients();
if( !$clientResult->isSuccess() ) {
    //print clients
    //foreach( $clientResult->data as $client ) {
    //echo "Client: " . $client->name . "<br/>";
    //}

    echo "something went wrong <br/>";
    die();

}

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="en" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Hello, world!</h1>
        <p>Now lets see what you did there!</p>
      </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="span8">
                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="form-horizontal" id="billingform" accept-charset="utf-8">


                    <div class="control-group">
                        <label for="country" class="control-label">
                            Country
                        </label>
                        <div class="controls">
                            <select name="client_id" id="country">
                                <option value=""></option>
                                <?php

                                foreach( $clientResult->data as $client ) {
                                    echo '<option value="' . $client->id. '">'. $client->name . '</option>';
                                }

                                ?>
                            </select>
                        </div>
                      </div>



                    <div class="form-actions">
                        <button type="submit" name="run-report" class="btn btn-large btn-primary">Get report! Go!</button>
                    </div>
                </form>
            </div> <!-- .span8 -->
        </div>
    </div>

    <hr>


    <?php if( isset($_POST["run-report"]) && isset($_POST["client_id"]) && $_POST['client_id'] != "" ) {
        //grab clients
        $clientProjects = $api->getClientProjects( (int)$_POST['client_id'] );
        $projectsTimes = array();
        if( $clientProjects->isSuccess() ) {

            //aktueller Monat
            $timerange = new Harvest_Range( date('Ym01'), date('Ymd') );

            foreach( $clientProjects->data as $project ) {
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

        }

        //display data

    ?>

        <div class="container">
            <div class="row">
                <div class="span12">
                    <div class="menu">
                        <div class="accordion">
                            <div class="accordion-group">

                                <?php
                                $counter = 0;
                                foreach( $projectsTimes as $project ) {
                                    $counter++;
                                ?>

                                <div class="accordion-heading project clearfix ">
                                    <img src="http://placehold.it/100x30" alt="project flag" style="float:left; margin: 3px 10px 0 3px; text-align:center;"/>
                                    <a class="accordion-toggle" data-toggle="collapse" href="#project_<?=$counter?>"><?=$project->name?></a>
                                </div>
                                <div id="project_<?=$counter?>" class="accordion-body collapse">
                                    <div class="accordion-inner">
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Time spent</th>
                                                <th>Description</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                        <?php foreach( $project->tracks as $track ) { ?>
                                            <tr>
                                                <td><?=$track->get("spent-at")?></td>
                                                <td><?=$track->get("hours")?></td>
                                                <td><?=$track->get("notes")?></td>
                                            </tr>
                                        <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    <?php } ?>




      <footer>
        <p>&copy; Company 2013</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
