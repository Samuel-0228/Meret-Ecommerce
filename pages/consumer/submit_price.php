<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/mock_data.php';
if (!isLoggedIn() || getUserRole() !== 'consumer') { header('Location: ../../login.php'); exit; }

$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prices = loadJSON('prices');
    $prices[] = [
      'id'=>time(),
      'city'=>$_POST['city'] ?? 'Addis Ababa',
      'crop'=>$_POST['crop'] ?? '',
      'value'=>floatval($_POST['value'] ?? 0),
      'consumer_id'=>$_SESSION['user_id'] ?? null,
      'submitted_at'=>date('c')
    ];
    saveJSON('prices',$prices);
    $notice = "Price submitted (mock).";
}
$prices = loadJSON('prices');
function cityAvg($prices,$city,$crop){
  $vals = array_map(function($p){ return $p['value']; }, array_filter($prices,function($p) use($city,$crop){ return $p['city']==$city && $p['crop']==$crop; }));
  if(empty($vals)) return null;
  return array_sum($vals)/count($vals);
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Submit Price</title><link rel="stylesheet" href="../../assets/css/style.css"></head><body>
<main class="center card">
  <h2>Submit Market Price</h2>
  <?php if ($notice) echo "<div class='notice'>$notice</div>"; ?>
  <form method="post" class="form">
    <label>Crop <input name="crop" required></label>
    <label>Price (ETB/kg) <input name="value" type="number" step="0.01" required></label>
    <label>City
      <select name="city">
        <option>Addis Ababa</option>
        <option>Bahir Dar</option>
        <option>Mekelle</option>
        <option>Hawassa</option>
      </select>
    </label>
    <button class="btn" type="submit">Submit Price</button>
  </form>

  <h3>Average Prices (sample)</h3>
  <ul>
    <?php
      $crops = array_unique(array_map(function($p){ return $p['crop']; }, $prices));
      foreach(['Addis Ababa','Bahir Dar','Mekelle','Hawassa'] as $city){
        foreach($crops as $crop){
          $avg = cityAvg($prices,$city,$crop);
          if($avg!==null) echo "<li>$city — $crop: ".number_format($avg,2)." ETB (based on submissions)</li>";
        }
      }
    ?>
  </ul>
  <p><a href="../../dashboard.php">&larr; Back</a></p>
</main>
</body></html>
