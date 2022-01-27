<?php

$json = file_get_contents("https://api.gk.wtf/");
$data = json_decode($json, true);


$digitecNumberOfItems = array_values($data["digitec"]["apidata"])[1]["salesInformation"]["numberOfItems"];
$digitecNumberOfItemsSold = array_values($data["digitec"]["apidata"])[1]["salesInformation"]["numberOfItemsSold"];
// calculate percentage still available
$digitecPercentage = ($digitecNumberOfItems - $digitecNumberOfItemsSold) / $digitecNumberOfItems * 100;

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
    <div class=deals_wrapper>
        <div class="deal_badge">
            <div class="progress" data-label="<?= $data["digitec"]["status"] ?>" style="border-color: #005598; text-shadow: -1px -1px 0 #005598, 0 -1px 0 #005598, 1px -1px 0 #005598, 1px 0 0 #005598, 1px 1px 0 #005598, 0 1px 0 #005598, -1px 1px 0 #005598, -1px 0 0 #005598;">
                <span class="value" style="width:<?=$digitecPercentage?>%; background-color: #005598;"></span>
            </div>
            <div class="badge_content">
                <div class="badge_header">
                    <div>
                        <h1 class="title"><?= $data["digitec"]["product"] ?></h1>
                        <h2 class="subtitle"><?= $data["digitec"]["category"] ?></h2>
                    </div>
                    <img src="assets/img/digitec.jpg" alt="digitec logo">
                </div>
                <img src="<?=reset($data["digitec"]["apidata"])["images"][0]["url"]?>" class="deal_img">
                <div class="badge_footer">
                    <div class="prices">
                        <h1 class="new_price"><?= $data["digitec"]["new_price"] ?>.-</h1>
                        <h2 class="old_price"><?= $data["digitec"]["old_price"] ?>.-</h2>
                    </div>
                    <div><a href="https://www.digitec.ch/de/liveshopping/" class="view_btn_anchor">
                            <div class="view_btn" style="background-color: #005598;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
        <div class="deal_badge">
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
                    <h1 class="new_price"><?= $data["daydeal_daily"]["new_price"] ?>.-</h1>
                        <h2 class="old_price"><?= $data["daydeal_daily"]["old_price"] ?>.-</h2>
                    </div>
                    <div><a href="https://daydeal.ch/" class="view_btn_anchor">
                            <div class="view_btn" style="background-color: #3FAA35;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
        <div class="deal_badge">
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
                    <h1 class="new_price"><?= $data["daydeal_weekly"]["new_price"] ?>.-</h1>
                        <h2 class="old_price"><?= $data["daydeal_weekly"]["old_price"] ?>.-</h2>
                    </div>
                    <div><a href="https://www.daydeal.ch/deal-of-the-week" class="view_btn_anchor">
                            <div class="view_btn" style="background-color: #3FAA35;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
        <div class="deal_badge">
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
                    <h1 class="new_price"><?= $data["blickdeal"]["new_price"] ?>.-</h1>
                        <h2 class="old_price"><?= $data["blickdeal"]["old_price"] ?>.-</h2>
                    </div>
                    <div><a href="https://blickdeal.ch/" class="view_btn_anchor">
                            <div class="view_btn" style="background-color: #E20000;">Ansehen</div>
                        </a></div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>