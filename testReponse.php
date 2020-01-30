<?php

$answers = array(
    "amazon"        => "ec2",
    "openstack"     => "nova",
    "compris"       => "oui",
    "flavors"       => "115",
    "images"        => "21",
    "servers"       => "openstack server list",
    "debug"         => "7",
    "connexion"     => "ssh",
    "sshlogin"      => "clef",
    "keylocation"   => "/home/jump/.ssh/id_rsa",
    "ip"            => "2",
    "wtfrole"       => "fuck",
    "asterisk"      => "pabx",
    "asterisk2"     => array("service asterisk status", "systemctl status asterisk"),
    "laputen"       => "netstat",
    "firstcall"     => "demo",
    "filtrews"      => "sip",
    "1reponse"      => "401",
    "register"      => "2",
    "debitgsm"      => "13",
    "debitpcma"     => "64",
    "gsmtc"         => "30",
    "pcmatc"        => "70",
    "astvocal"      => "voicemail",
    "astvocal2"     => "voicemailmain",
    "astpont"       => "confbridge",
    "asttransf"     => "tt",
);

//print_r($answers);

// Recupere la question et la reponse
$q = ( isset($_REQUEST['q']) ) ? $_REQUEST['q'] : null;
$r = ( isset($_REQUEST['r']) ) ? $_REQUEST['r'] : null;

if ($q && $r && isset($answers["$q"])){
    // Cas multiple reponses possibles
    if (is_array($answers["$q"])){
        $found = false;
        foreach ($answers["$q"] as $answer){
            if ($answer == strtolower($r)){
                $found = true;
            }
        }
        if ($found) {
            echo "true";
        }
        else{
            echo "false";
        }
    }
    // Cas simple
    else {
        if ($answers["$q"] == strtolower($r)){
            echo "true";
        }
        else{
            echo "false";
        }
    }
}
else{
    echo "false";
}
?>
