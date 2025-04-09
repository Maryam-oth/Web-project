<?php

//check connecting to database
$conn = mysqli_connect('localhost:3307','root','','thameen') or die("<p>Couldn't connect to database</p></body></html>");

//open thameen database
if(!mysqli_select_db($conn,"thameen"))
    die("<p>Couldn't opent thameen database</p></body></html>");

/* Set the character set of the MySQL connection to 'utf8,
to ensure proper handling and storage of text data in various languages and character sets. */
mysqli_query($conn,"SET NAMES 'utf8'");

?>
