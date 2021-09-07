<?php
 mysqli_connect("", "root", "", "chatbot");

 $no = $_POST['getresult'];
 $select = mysql_query("SELECT * FROM GraphData as date ORDER BY Date");
 while($row = mysql_fetch_array($select))
 {
  echo "
    <hr style='height:2px;border-width:0;color:white;background-color:white'>
    <h1></h1>
    <hr style='height:2px;border-width:0;color:white;background-color:white'>
  ";
 }
?>