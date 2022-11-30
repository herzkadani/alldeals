<style>
  td,
  th {
    padding: 5px;
    border: 2px solid lightgrey;
    font-family: "Arial";
    font-family: Arial, Helvetica, sans-serif;
  }

  thead tr th {
    font-weight: bold;
    background-color: lightgrey;
    position: sticky;
    top: 0;
  }


  .sortable th:first-child {
    border-top-left-radius: 4px;
  }

  .sortable th:last-child {
    border-top-right-radius: 4px;
  }

  .sortable th {
    background: #808080;
    color: #fff;
    cursor: pointer;
    font-weight: normal;
    text-align: left;
    text-transform: capitalize;
    vertical-align: baseline;
    white-space: nowrap;
  }

  .sortable th:hover {
    color: #000;
  }

  .sortable th:hover::after {
    color: inherit;
    font-size: 1.2em;
    content: ' \025B8';
  }

  .sortable th::after {
    font-size: 1.2em;
    color: transparent;
    content: ' \025B8';
  }

  .sortable th.dir-d {
    color: #000;
  }

  .sortable th.dir-d::after {
    color: inherit;
    content: ' \025BE';
  }

  .sortable th.dir-u {
    color: #000;
  }

  .sortable th.dir-u::after {
    color: inherit;
    content: ' \025B4';
  }
</style>
<table class="sortable">
  <thead>
    <tr>
      <th>Day</th>
      <th>Store</th>
      <th>Title</th>
      <th>Subtitle</th>
      <th>Price</th>
      <th>Old Price</th>
      <th>Percent remaining</th>
    </tr>
  </thead>
  <tbody>
    <?php

    function randomColor($seed)
    {
      srand($seed);
      $baseColors = [
        1 => 'r',
        2 => 'g',
        3 => 'b'
      ];
      $colorMap = [];
      $minValue = 155;
      $maxValue = 200;

      $primaryColorIndex = rand(1, 3);

      $primaryColor = $baseColors[$primaryColorIndex];
      unset($baseColors[$primaryColorIndex]);

      $colorMap[$primaryColor] = 255;

      foreach ($baseColors as $baseColor) {
        $colorMap[$baseColor] = rand($minValue, $maxValue);
      }

      krsort($colorMap);

      $color = '';
      foreach ($colorMap as $value) {
        $color .= $value;
        if ($value !== end($colorMap)) {
          $color .= ',';
        }
      }

      return 'rgb(' . $color . ')';
    }




    $alldeals = [];
    $output = "";
    $files = array_diff(scandir("/deals"), array('.', '..'));
    foreach ($files as $file) {
      $content = file_get_contents("file:///deals/" . $file);
      $json = json_decode($content, true);
      foreach ($json as $key => $val) {
        $val = array_merge($val, array("store" => $key));
        array_push($alldeals, $val);
      }
    }

    foreach ($alldeals as $d) {
      $fmtdate = gmdate('Y-m-d', $d['timestamp'] / 1000);
      $bgcolor = randomColor(gmdate('Ymd', $d['timestamp'] / 1000));
      $new_price_sort = str_replace([".-", ".–"], "", $d['new_price']);
      $old_price_sort = str_replace([".-", ".–"], "", $d['old_price']);
      if ($d["timestamp"] != "") {
        echo "<tr>  <td style='background-color: {$bgcolor};'>{$fmtdate}</td> <td data-sort='{$d['store']}'><img height='50' src='https://deals.gk.wtf/assets/img/{$d['store']}.jpg'></img></td> <td>{$d['title']}</td> <td>{$d['subtitle']}</td>  <td data-sort='{$new_price_sort}'>{$d['new_price']}</td> <td data-sort='{$old_price_sort}'>{$d['old_price']}</td> <td>{$d['availability']}</td> </tr>";
      }
    }




    ?>
  </tbody>
</table>
<script src="https://cdn.jsdelivr.net/gh/tofsjonas/sortable/sortable.min.js"></script>
