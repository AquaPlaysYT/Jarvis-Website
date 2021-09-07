<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/navbar.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
maintenance();

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

//Charts

$query = "SELECT * FROM GraphData ORDER BY Date DESC LIMIT 30";
$result = mysqli_query($connect1, $query);
$chart_data1 = '';
$chart_data2 = '';
$chart_data3 = '';
$chart_data4 = '';
while($row = mysqli_fetch_array($result))
{
 $chart_data1 .= "{ Date:'".$row["Date"]."', Members:".$row["Members"]."}, ";
 $chart_data2 .= "{ Date:'".$row["Date"]."', Servers:".$row["Servers"]."}, ";
 $chart_data3 .= "{ Date:'".$row["Date"]."', Patterns:".$row["Patterns"].", BlockedPatterns:".$row["BlockedPatterns"].", TotalPatterns:".$row["TotalPatterns"]."}, ";
 $chart_data4 .= "{ Date:'".$row["Date"]."', TotalResponses:".$row["TotalResponses"]."}, ";
}
$chart_data1 = substr($chart_data1, 0, -2);
$chart_data2 = substr($chart_data2, 0, -2);
$chart_data3 = substr($chart_data3, 0, -2);
$chart_data4 = substr($chart_data4, 0, -2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script data-ad-client="ca-pub-2972734010560512" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:description"  content="View all member, server, pattern and response data from upto 30 days ago!">
    <meta property="og:title" content="Jarvis Stats">
    <meta property="og:type" content="website" />
    <meta name="theme-color" content="#0357ff">
    <meta property="og:image" content="https://jarvischatbot.xyz/images/jarvis_new_new.png" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/bootstrap.min.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/main.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/now-ui-kit.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    <title>JarvisTheChatbot</title>
</head>
<style>
.morris-hover {
  position:absolute;
  z-index:1000;
}

.morris-hover.morris-default-style {     border-radius:10px;
  padding:6px;
  color:#666;
  background:rgba(255, 255, 255, 0.8);
  border:solid 2px rgba(230, 230, 230, 0.8);
  font-family:sans-serif;
  font-size:12px;
  text-align:center;
}

.morris-hover.morris-default-style .morris-hover-row-label {
  font-weight:bold;
  margin:0.25em 0;
}

.morris-hover.morris-default-style .morris-hover-point {
  white-space:nowrap;
  margin:0.1em 0;
}

svg { width: 100%; }
</style>
<body>
  <center>
  <img alt="Jarvis Picture" style="width:200px;height:200px;border-radius:50%;" src="https://jarvischatbot.xyz/images/jarvis_new_new.png">
  <div class="title text-white">Jarvis Statistics</div>
  <div class="container-fluid">
      <div class="container p-3 my-3 bg-dark text-white">
          <div class="card-body">
          <h1>Total Users Interacted With Jarvis</h1>
          <br /><br />
          <div id="members"></div>
          <h1>Total Servers</h1>
          <br /><br />
          <div id="servers"></div>
          <h1>Total Patterns</h1>
          <br /><br />
          <div id="patterns"></div>
          <h1>Daily Responses</h1>
          <br /><br />
          <div id="responses"></div>
      </div>
  </div>
</div>
<div class="footer">
    Notice:<a href="https://jarvischatbot.xyz/privacy.pdf"> Jarvis Privacy Statement</a>
	<a href="//www.dmca.com/Protection/Status.aspx?ID=6c263011-55c1-474d-ae21-479608bfb330" title="DMCA.com Protection Status" class="dmca-badge"> <img src ="https://images.dmca.com/Badges/dmca_protected_sml_120m.png?ID=6c263011-55c1-474d-ae21-479608bfb330"  alt="DMCA.com Protection Status" /></a>  <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
</div>
<br>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://jarvischatbot.xyz/style/js/now-ui-kit.min.js"></script>
<script src="https://jarvischatbot.xyz/scripts/navbar_load.js"></script>
</body>
<script>

Morris.Line({
 element : 'members',
 data:[<?php echo $chart_data1; ?>],
 xkey:'Date',
 ykeys:['Members'],
 labels:['Members'],
 hideHover:'auto',
 stacked:true,
 resize:true,
 pointStrokeColors:'#'
});

     document.addEventListener("DOMContentLoaded", function(event) {
   document.querySelectorAll('img').forEach(function(img){
  	img.onerror = function(){this.style.display='none';};
   })
});

Morris.Line({
 element : 'servers',
 data:[<?php echo $chart_data2; ?>],
 xkey:'Date',
 ykeys:['Servers'],
 labels:['Servers'],
 hideHover:'auto',
 stacked:true,
 resize:true,
 pointStrokeColors:'#'
});

     document.addEventListener("DOMContentLoaded", function(event) {
   document.querySelectorAll('img').forEach(function(img){
  	img.onerror = function(){this.style.display='none';};
   })
});

Morris.Line({
 element : 'patterns',
 data:[<?php echo $chart_data3; ?>],
 xkey:'Date',
 ykeys:['Patterns', 'BlockedPatterns', 'TotalPatterns'],
 labels:['Patterns', 'Blocked Patterns', 'Total Patterns'],
 hideHover:'auto',
 stacked:true,
 resize:true,
 pointStrokeColors:'#'
});

     document.addEventListener("DOMContentLoaded", function(event) {
   document.querySelectorAll('img').forEach(function(img){
  	img.onerror = function(){this.style.display='none';};
   })
});

Morris.Line({
 element : 'responses',
 data:[<?php echo $chart_data4; ?>],
 xkey:'Date',
 ykeys:['TotalResponses'],
 labels:['TotalResponses'],
 hideHover:'auto',
 stacked:true,
 resize:true,
 pointStrokeColors:'#'
});

     document.addEventListener("DOMContentLoaded", function(event) {
   document.querySelectorAll('img').forEach(function(img){
  	img.onerror = function(){this.style.display='none';};
   })
});

</script>
<center>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2972734010560512"
     data-ad-slot="1079864640"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
</center>

</html>
