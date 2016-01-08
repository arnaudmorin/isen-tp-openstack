<?php

$answers = array(
    "amazon"    => "ec2",
    "openstack" => "nova",
    "clef_ssh"  => "ssh-keygen",
    "connexion" => "ssh",
    "ip"        => "2",
    "asterisk"  => "pabx",
    "asterisk2" => "status",
    "laputen"   => "netstat",
    "firstcall" => "demo",
    "filtrews"  => "sip",
    "1reponse"  => "401",
    "2reponse"  => "407",
    "stun"      => "avant",
    "register"  => "2",
    "dialogue"  => "3",
    "debitgsm"  => "13",
    "debitpcma" => "64",
    "gsmtc"     => "30",
    "pcmatc"    => "70",
    "webrtc"    => "websocket",
    "astvocal"  => "voicemail",
    "astpont"   => "confbridge",
    "asttransf" => "tt",
);

//print_r($answers);

// Recupere la question et la reponse
$q = ( isset($_REQUEST['q']) ) ? $_REQUEST['q'] : null;
$r = ( isset($_REQUEST['r']) ) ? $_REQUEST['r'] : null;

if ($q && $r && isset($answers["$q"])){
    if ($answers["$q"] == strtolower($r)){
        echo "true";
    }
    else{
        echo "false";
    }
}
else{
    echo "false";
}
?>
