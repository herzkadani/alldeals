<?php

#$json = file_get_contents("https://gianklug.com/alldeals/backend/index.php");
#$json = shell_exec("../backend/api.py");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$json = file_get_contents("/var/www/alldeals-beta/frontend/deals.json");
$data = json_decode($json, true);
function time_elapsed_string($datetime, $full = false) {
    // set timezone
    date_default_timezone_set('UTC');
    $now = new DateTime;
    $ago = new DateTime("@".$datetime);
    $diff = $now->diff($ago);
    return $diff->format('vor %i Minuten');
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
    <div class="deal_badge <?php if ($data["digitec"]["availability"]==0){echo "expired";}?>">
            <div class="progress" data-label="<?= floor($data["digitec"]["availability"]) ?>%" style="border-color: #005598; text-shadow: -1px -1px 0 #005598, 0 -1px 0 #005598, 1px -1px 0 #005598, 1px 0 0 #005598, 1px 1px 0 #005598, 0 1px 0 #005598, -1px 1px 0 #005598, -1px 0 0 #005598;">
                <span class="value" style="width:<?=$data["digitec"]["availability"]?>; background-color: #005598;"></span>
            </div>
            <div class="badge_content">
                <div class="badge_header">
                    <div>
                        <h1 class="title"><?= $data["digitec"]["title"] ?></h1>
                        <h2 class="subtitle"><?= $data["digitec"]["subtitle"] ?></h2>
                    </div>
                    <img src="assets/img/digitec.jpg" alt="digitec logo">
                </div>
                <img src="<?=$data["digitec"]["image"] ?>" class="deal_img">
                <div class="badge_footer">
                    <div class="prices">
                        <h1 class="new_price">CHF <?= $data["digitec"]["new_price"] ?></h1>
                        <h2 class="old_price">CHF <?= $data["digitec"]["old_price"] ?></h2>
                    </div>
                    <span class="last_update">Letztes Update: <?= time_elapsed_string($data["digitec"]["timestamp"]) ?></span>
                    <div><a href="https://www.digitec.ch/de/liveshopping/" class="view_btn_anchor" target="_blank">
                            <div class="view_btn" style="background-color: #005598;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
        <div class="deal_badge <?php if ($data["galaxus"]["availability"]==0){echo "expired";}?>">
            <div class="progress" data-label="<?= floor($data["galaxus"]["availability"]) ?>%" style="border-color: #222; text-shadow: -1px -1px 0 #222, 0 -1px 0 #222, 1px -1px 0 #222, 1px 0 0 #222, 1px 1px 0 #222, 0 1px 0 #222, -1px 1px 0 #222, -1px 0 0 #222;">
                <span class="value" style="width:<?= $data["galaxus"]["availability"] ?>; background-color: #222;"></span>
            </div>
            <div class="badge_content">
                <div class="badge_header">
                    <div>
                        <h1 class="title"><?= $data["galaxus"]["title"] ?></h1>
                        <h2 class="subtitle"><?= $data["galaxus"]["subtitle"] ?></h2>
                    </div>
                    <img src="assets/img/galaxus.jpg" alt="galaxus logo">
                </div>
                <img src="<?=$data["galaxus"]["image"] ?>" class="deal_img">
                <div class="badge_footer">
                    <div class="prices">
                        <h1 class="new_price">CHF <?= $data["galaxus"]["new_price"] ?></h1>
                        <h2 class="old_price">CHF <?= $data["galaxus"]["old_price"] ?></h2>
                    </div>
                    <span class="last_update">Letztes Update: <?= time_elapsed_string($data["galaxus"]["timestamp"]) ?></span>
                    <div><a href="https://www.galaxus.ch/de/liveshopping/" class="view_btn_anchor" target="_blank">
                            <div class="view_btn" style="background-color: #222;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
        <div class="deal_badge <?php if ($data["daydeal_daily"]["availability"]==0){echo "expired";}?>">
            <div class="progress" data-label="<?= $data["daydeal_daily"]["availability"] ?>" style="border-color: #3FAA35; text-shadow: -1px -1px 0 #3FAA35, 0 -1px 0 #3FAA35, 1px -1px 0 #3FAA35, 1px 0 0 #3FAA35, 1px 1px 0 #3FAA35, 0 1px 0 #3FAA35, -1px 1px 0 #3FAA35, -1px 0 0 #3FAA35;">
                <span class="value" style="width:<?= $data["daydeal_daily"]["availability"] ?>; background-color: #3FAA35;"></span>
            </div>
            <div class="badge_content">
                <div class="badge_header">
                    <div>
                        <h1 class="title"><?= $data["daydeal_daily"]["title"] ?></h1>
                        <h2 class="subtitle"><?= $data["daydeal_daily"]["subtitle"] ?></h2>
                    </div>
                    <img src="assets/img/daydeal_daily.jpg" alt="daydeal_daily logo">
                </div>
                <img src="<?= $data["daydeal_daily"]["image"] ?>" class="deal_img">
                <div class="badge_footer">
                    <div class="prices">
                    <h1 class="new_price">CHF <?= $data["daydeal_daily"]["new_price"] ?></h1>
                        <h2 class="old_price">CHF <?= $data["daydeal_daily"]["old_price"] ?></h2>
                    </div>
                    <span class="last_update">Letztes Update: <?= time_elapsed_string($data["daydeal_daily"]["timestamp"]) ?></span>
                    <div><a href="https://daydeal.ch/" class="view_btn_anchor" target="_blank">
                            <div class="view_btn" style="background-color: #3FAA35;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
        <div class="deal_badge <?php if ($data["daydeal_weekly"]["availability"]==0){echo "expired";}?>">
        <div class="progress" data-label="<?= $data["daydeal_weekly"]["availability"] ?>" style="border-color: #3FAA35; text-shadow: -1px -1px 0 #3FAA35, 0 -1px 0 #3FAA35, 1px -1px 0 #3FAA35, 1px 0 0 #3FAA35, 1px 1px 0 #3FAA35, 0 1px 0 #3FAA35, -1px 1px 0 #3FAA35, -1px 0 0 #3FAA35;">
                <span class="value" style="width:<?= $data["daydeal_weekly"]["availability"] ?>; background-color: #3FAA35;"></span>
            </div>
            <div class="badge_content">
                <div class="badge_header">
                    <div>
                    <h1 class="title"><?= $data["daydeal_weekly"]["title"] ?></h1>
                        <h2 class="subtitle"><?= $data["daydeal_weekly"]["subtitle"] ?></h2>
                    </div>
                    <img src="assets/img/daydeal_weekly.jpg" alt="daydeal_weekly logo">
                </div>
                <img src="<?= $data["daydeal_weekly"]["image"] ?>" class="deal_img">
                <div class="badge_footer">
                    <div class="prices">
                    <h1 class="new_price">CHF <?= $data["daydeal_weekly"]["new_price"] ?></h1>
                        <h2 class="old_price">CHF <?= $data["daydeal_weekly"]["old_price"] ?></h2>
                    </div>
                    <span class="last_update">Letztes Update: <?= time_elapsed_string($data["daydeal_weekly"]["timestamp"]) ?></span>
                    <div><a href="https://www.daydeal.ch/deal-of-the-week" class="view_btn_anchor" target="_blank">
                            <div class="view_btn" style="background-color: #3FAA35;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
        <div class="deal_badge <?php if ($data["blickdeal"]["availability"]==0){echo "expired";}?>">
        <div class="progress" data-label="<?= $data["blickdeal"]["availability"] ?>" style="border-color: #E20000; text-shadow: -1px -1px 0 #E20000, 0 -1px 0 #E20000, 1px -1px 0 #E20000, 1px 0 0 #E20000, 1px 1px 0 #E20000, 0 1px 0 #E20000, -1px 1px 0 #E20000, -1px 0 0 #E20000;">
                <span class="value" style="width:<?= $data["blickdeal"]["availability"] ?>; background-color: #E20000;"></span>
            </div>
            <div class="badge_content">
                <div class="badge_header">
                    <div>
                    <h1 class="title"><?= $data["blickdeal"]["title"] ?></h1>
                        <h2 class="subtitle"><?= $data["blickdeal"]["subtitle"] ?></h2>
                    </div>
                    <img src="assets/img/blickdeal.jpg" alt="blickdeal logo">
                </div>
                <img src="<?= $data["blickdeal"]["image"] ?>" class="deal_img">
                <div class="badge_footer">
                    <div class="prices">
                    <h1 class="new_price">CHF <?= $data["blickdeal"]["new_price"] ?></h1>
                        <h2 class="old_price">CHF <?= $data["blickdeal"]["old_price"] ?></h2>
                    </div>
                    <span class="last_update">Letztes Update: <?= time_elapsed_string($data["blickdeal"]["timestamp"]) ?></span>
                    <div><a href="https://blickdeal.ch/" class="view_btn_anchor" target="_blank">
                            <div class="view_btn" style="background-color: #E20000;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
