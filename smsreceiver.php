<?php
/* 
 * This file has automacally been created by Emerginov Project Creation mecanism.
 * The purpose of this file is to provide a sample SMS receiver management.
 * Please edit the file to handle properly your incoming SMS.
 * You might find many information on how to do so on Emerginov wiki.
 * http://emerginov.ow2.org/xwiki
 */

// Load required configuration & Emerginov Class
require_once("passwords.php");
require_once("Emerginov.php");

// Get SMS information
$sender = $_REQUEST["SOA"];
$content = $_REQUEST["Content"];

// Open a log file
$fh = fopen("log","a");

// Write SMS content to this log file
fwrite($fh, "---- On ".date("r").", I received a SMS from: $sender. Content is: $content\n");

// Close the log file
fclose($fh);
?>
