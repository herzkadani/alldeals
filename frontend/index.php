<?php

#$json = file_get_contents("https://gianklug.com/alldeals/backend/index.php");
#$json = shell_exec("../backend/api.py");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$json = file_get_contents("https://gianklug.com/alldeals-beta/frontend/deals.json");
$data = json_decode($json, true);

$json =  file_get_contents("brandcolors.json");
$brandcolors = json_decode($json, true);


function time_elapsed_string($datetime, $full = false) {
    // set timezone
    date_default_timezone_set('UTC');
    $now = new DateTime;
    $ago = new DateTime("@".$datetime);
    $diff = $now->diff($ago);
    return $diff->format('vor %i Minuten');
}

$htmloutput = '';

foreach ($data as $key => $value) {
    $isexpired = ($data[$key]['availability']=='0%' ? 'expired' : '');

    $htmloutput.='<div class="deal_badge' .$isexpired.'">
    <div class="progress" data-label="'.$data[$key]['availability'].'" style="border-color: '.$brandcolors[$key].'; text-shadow: -1px -1px 0 '.$brandcolors[$key].', 0 -1px 0'.$brandcolors[$key].', 1px -1px 0 '.$brandcolors[$key].', 1px 0 0 '.$brandcolors[$key].', 1px 1px 0 '.$brandcolors[$key].', 0 1px 0 '.$brandcolors[$key].', -1px 1px 0 '.$brandcolors[$key].', -1px 0 0'.$brandcolors[$key].';">
        <span class="value" style="width:'.$data[$key]['availability'].'; background-color: '.$brandcolors[$key].';"></span>
    </div>
    <div class="badge_content">
        <div class="badge_header">
            <div>
                <h1 class="title">'.$data[$key]['title'].'</h1>
                <h2 class="subtitle">'.$data[$key]['subtitle'].'</h2>
            </div>
            <img src="assets/img/'.$key.'.jpg" alt="'.$key.' logo">
        </div>
        <img src="'.$data[$key]['image'].'" class="deal_img">
        <div class="badge_footer">
            <div class="prices">
                <h1 class="new_price">CHF '.$data[$key]['new_price'].'</h1>
                <h2 class="old_price">CHF '.$data[$key]['old_price'].'</h2>
            </div>
            <span class="last_update">Letztes Update: '.time_elapsed_string($data[$key]['timestamp']).'</span>
            <div><a href="https://www.digitec.ch/de/liveshopping/" class="view_btn_anchor" target="_blank">
                    <div class="view_btn" style="background-color: '.$brandcolors[$key].';">Ansehen</div>
                </a></div>

        </div>
    </div>
</div>';

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>alldeals</title>
</head>

<body>
    <header>
        <h1>AllDeals&nbsp;</h1><h3>Alle Tagesangebote kombiniert!</h3>
    </header>
    <div class=deals_wrapper>
        <?php echo $htmloutput; ?>
    </div>
</body>

</html>
