<?php
date_default_timezone_set('Europe/Istanbul');
$host     = "localhost";
$table    = "mysql";
$user     = "root";
$password = "";
try {
  $db = new PDO("mysql:host=".$host.";dbname=".$table.";charset=utf8",$user,$password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Bağlantı hatası: " . $e->getMessage();
}
function GetValue($index)
{
  if (isset($_GET[$index])) {
    return $_GET[$index];
  }else {
    return false;
  }
}
function DateDMYHMS($date){
  return iconv('UTF-8','UTF-8',strftime(' %d %B %Y %H:%M:%S',strtotime($date)));
}
if (GetValue('type')=="ac") {
  $db->query("SET global general_log = 1");
  $db->query("SET global log_output = 'table'");
  $db->query("TRUNCATE mysql.general_log");
  echo '<span class="text-center d-block m-2 mt-0">Sorgu kayıtları aktif edildi.</span>';
}
if (GetValue('type')=="kapat") {
  $db->query("SET global general_log = 0");
  $db->query("TRUNCATE mysql.general_log");
  echo '<span class="text-center d-block m-2 mt-0">Sorgu kayıtları pasif edildi.</span>';
}
if (GetValue('type')=="ajax") {
  $data = [];
  $queries = $db->query("SELECT * FROM mysql.general_log WHERE event_time >= '".date('Y-m-d H:i:s',time()-1)."'")->fetchAll(PDO::FETCH_OBJ);
  foreach ($queries as $key => $value) {
    $commandType = ["Quit","Connect"];
    $queries = [];
    if (!in_array($value->command_type,$commandType) && stristr($value->argument,"SELECT * FROM mysql.general_log WHERE event_time")=="") {
      $data[] = [
        'argument' => $value->argument,
        'event_time' => DateDMYHMS($value->event_time),
      ];
    }
  }
  echo json_encode($data);
  exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <title>Mysql Gelen Sorgu Listesi</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<script type="text/javascript">
function TableAjax() {
  $.ajax({
    type: "get",
    url: "index.php?type=ajax",
    success: function(response){
      $.each(response, function(index, val) {
        $('tbody').prepend('<tr> \
          <td width="75%">'+val.argument+'</td> \
          <td width="15%">'+val.event_time+'</td> \
        </tr>');
      });
    },
    dataType: "json"
  });
}
$(document).ready(function() {
  TableAjax();
  setInterval(TableAjax,1000);
});
</script>
<body>
  <div class="text-center m-2">
    <a class="btn btn-success w-25" href="index.php?type=ac">Aktif et</a>
    <a class="btn btn-danger w-25" href="index.php?type=kapat">Pasif et</a>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>Komut</th>
        <th>Tarih</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</body>
</html>
