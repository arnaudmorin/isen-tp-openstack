<?php
/* 
 * This file has automacally been created by Emerginov Project Creation mecanism.
 * The purpose of this file is to provide a sample incoming voice call management.
 * Please edit the file to handle properly your incoming voice calls.
 * You might find many information on how to do so on Emerginov wiki.
 * http://emerginov.ow2.org/xwiki
 */

// Load required configuration & Emerginov Class
require_once("passwords.php");
require_once("Emerginov.php");

// Answer the call
$call->Answer();

// Wait 2 sec;
sleep(2);

// Hangup the call
$call->Hangup();
?>
