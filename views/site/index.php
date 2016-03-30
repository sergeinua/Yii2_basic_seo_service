<?php
use app\components\gapi;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>


<?php
//require Yii::$app->basePath . '/components/gapi.php';
define('ga_profile_id','86449576');
$ga = new gapi("356532283258-compute@developer.gserviceaccount.com", Yii::$app->basePath . '/components/Reclamare-fb1d45c039ea.p12');
//$ga->requestReportData(ga_profile_id,array('browser','browserVersion'),array('pageviews','visits'));
$ga->requestReportData(ga_profile_id,array('source'),['visits']);
?>
<!--<table>-->
<!--    <tr>-->
<!--        <th>Browser &amp; Browser Version</th>-->
<!--        <th>Pageviews</th>-->
<!--        <th>Visits</th>-->
<!--    </tr>-->
<!--    --><?php
//    foreach($ga->getResults() as $result):
//        ?>
<!--        <tr>-->
<!--            <td>--><?php //echo $result ?><!--</td>-->
<!--            <td>--><?php //echo $result->getPageviews() ?><!--</td>-->
<!--            <td>--><?php //echo $result->getVisits() ?><!--</td>-->
<!--        </tr>-->
<!--        --><?php
//    endforeach
//    ?>
<!--</table>-->
<!---->
<!--<table>-->
<!--    <tr>-->
<!--        <th>Total Results</th>-->
<!--        <td>--><?php //echo $ga->getTotalResults() ?><!--</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th>Total Pageviews</th>-->
<!--        <td>--><?php //echo $ga->getPageviews() ?>
<!--    </tr>-->
<!--    <tr>-->
<!--        <th>Total Visits</th>-->
<!--        <td>--><?php //echo $ga->getVisits() ?><!--</td>-->
<!--    </tr>-->
<!--</table>-->

<div>

</div>
<div>
    <table>
       <?php foreach($ga->getResults() as $result):
            ?>
<?php //dump($result);?>
               <?php $item_visits = $result->getMetrics(); ?>
           <?php $item_dimensions = $result->getDimensions(); ?>
<hr>
        <?php echo $item_visits['visits']; ?>
        <?php echo $item_dimensions['source']; ?>
           <hr>



            <?php
        endforeach
        ?>
    </table>
</div>