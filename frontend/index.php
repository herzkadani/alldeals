<?php

#$json = file_get_contents("https://gianklug.com/alldeals/backend/index.php");
#$json = shell_exec("../backend/api.py");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$deals_json = file_get_contents("/deals/deals-" . date('Y-m-d') . ".json");

// $deals_json = file_get_contents("dev-fixtures.json");

$data = json_decode($deals_json, true);

$colors_json = file_get_contents("brandcolors.json");
$brandcolors = json_decode($colors_json, true);


function time_elapsed_string($datetime, $full = false)
{
    // set timezone
    date_default_timezone_set('Europe/Zurich');
    $now = new DateTime;
    $ago = new DateTime("@" . (round($datetime / 1000)));
    $diff = $now->diff($ago);
    return $diff->format('vor %i Minuten');
}

$htmloutput = '';

foreach ($data as $key => $value) {
    if ($value != []) {
        $isexpired = ($data[$key]['availability'] == '0%' ? 'expired' : '');

        $htmloutput .= '<div class="deal_badge ' . $isexpired . '">
            <div class="progress" data-label="' . $data[$key]['availability'] . '" style="border-color: ' . $brandcolors[$key] . '; text-shadow: -1px -1px 0 ' . $brandcolors[$key] . ', 0 -1px 0' . $brandcolors[$key] . ', 1px -1px 0 ' . $brandcolors[$key] . ', 1px 0 0 ' . $brandcolors[$key] . ', 1px 1px 0 ' . $brandcolors[$key] . ', 0 1px 0 ' . $brandcolors[$key] . ', -1px 1px 0 ' . $brandcolors[$key] . ', -1px 0 0' . $brandcolors[$key] . ';">
            <span class="value" style="width:' . $data[$key]['availability'] . '; background-color: ' . $brandcolors[$key] . ';"></span>
            </div>
            <div class="badge_content">
            <div class="badge_header">
            <div>
            <h1 class="title">' . $data[$key]['title'] . '</h1>
            <h2 class="subtitle">' . $data[$key]['subtitle'] . '</h2>
            </div>
            <img src="assets/img/' . $key . '.jpg" alt="' . $key . ' logo">
            </div>
            <img src="' . $data[$key]['image'] . '" class="deal_img">
            <span class="last_update">Letztes Update: ' . time_elapsed_string($data[$key]['timestamp']) . '</span>
            <div class="badge_footer">
            <div class="prices">
            <h1 class="new_price">CHF ' . $data[$key]['new_price'] . '</h1>
            <h2 class="old_price">CHF ' . $data[$key]['old_price'] . '</h2>
            </div>
            <div><a href="' . $data[$key]['url'] . '" class="view_btn_anchor" target="_blank">
            <div class="view_btn" style="background-color: ' . $brandcolors[$key] . ';">Ansehen</div>
            </a></div>

            </div>
            </div>
            </div>';

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="script.js" defer></script>
    <title>alldeals</title>
</head>

<body>
    <a href="https://github.com/herzkadani/alldeals" style="position: absolute; right: 0;"><img loading="lazy"
            width="149" height="149"
            src="https://github.blog/wp-content/uploads/2008/12/forkme_right_green_007200.png?resize=149%2C149"
            class="attachment-full size-full" alt="Fork me on GitHub" data-recalc-dims="1"></a>
    <header>
        <h1>AllDeals&nbsp;</h1>
        <h3>Alle Tagesangebote kombiniert!</h3>
    </header>
    <div class=deals_wrapper>
        <?= $htmloutput ?>
    </div>
    <div class="modal" id="settings-modal">
        <div class="wrapper" id="modal-wrapper">
            <h2>Settings</h2>
            <form>
                <div class="form-group">
                    <label for="layout">Layout</label>
                    <select name="layout" id="layout" disabled>
                        <option value="grid">Grid</option>
                        <option value="list">Liste</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sort">Sort</label>
                    <select name="sort" id="sort" disabled>
                        <option value="default">Standard</option>
                        <option value="price_asc">Preis aufsteigend</option>
                        <option value="price_desc">Preis absteigend</option>
                        <option value="availability_asc">Verfügbarkeit aufsteigend</option>
                        <option value="availability_desc">Verfügbarkeit absteigend</option>
                    </select>
                </div>
            </form>
            <br>
            <br>
            <button id="close-modal">
                <span class="label">
                    <span class="fa fa-close"></span>&nbsp;&nbsp;Close
                </span>
            </button>
        </div>
    </div>
    <footer>
        <a href="https://github.com/herzkadani/alldeals"><img src="assets/img/GitHub-Mark-120px-plus.png"
                alt="view on github" class="github_icon"></a></img>
        <p>made with <span style="color:red">❤</span> in switzerland <br> help us improve by contributing on GitHub</p>
        <div class="settings">
            <span class="label">Settings</span>
            <span class="fa fa-cog"></span>
        </div>

    </footer>

</body>

</html>