<?php

#$json = file_get_contents("https://gianklug.com/alldeals/backend/index.php");
#$json = shell_exec("../backend/api.py");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$deals_json = file_get_contents("/var/www/alldeals/frontend/deals.json");
$data = json_decode($deals_json, true);

$colors_json =  file_get_contents("brandcolors.json");
$brandcolors = json_decode($colors_json, true);


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

    $htmloutput.='<div class="deal_badge ' .$isexpired.'">
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
        <div><a href="'.$data[$key]['url']. '" class="view_btn_anchor" target="_blank">
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
<a href="https://github.com/herzkadani/alldeals" style="position: absolute; right: 0;"><img loading="lazy" width="149" height="149" src="https://github.blog/wp-content/uploads/2008/12/forkme_right_green_007200.png?resize=149%2C149" class="attachment-full size-full" alt="Fork me on GitHub" data-recalc-dims="1"></a>
    <header>
        <h1>AllDeals&nbsp;</h1><h3>Alle Tagesangebote kombiniert!</h3>
    </header>
    <div class=deals_wrapper>
        <?=$htmloutput?>
    </div>
</body>

</html>
