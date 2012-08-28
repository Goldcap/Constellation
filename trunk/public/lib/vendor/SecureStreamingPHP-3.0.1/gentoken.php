#!/usr/bin/php

<?PHP

require 'StreamTokenFactory.php';

$userPath = null;
$userIP = null;
$userProfile = null;
$userPasswd = null;
$userTime = 0;
$userWindow = 0;
$userDuration = 0;
$userPayload = null;
$userKey = null;
$userSalt = null;
$userToken = null;

function displayVersion() {
    $factory = new StreamTokenFactory;
    echo "Akamai Secure Streaming, PHP token generator version " .
            $factory->getVersion() . "\n";
}

function displayHelp($message) {
    displayVersion();

    if ($message != null)
        echo "Unrecognized command-line argument: " . $message . "\n";

    echo "usage: gentoken.php [options]\n";
    echo "   -f path              path being requested\n";
    echo "   -i ip                ip address of requester\n";
    echo "   -r profile           authentication profile\n";
    echo "   -p passwd            password\n";
    echo "   -k key               key filename and path (for e type tokens 32 bytes binary file)\n";
    echo "   -t time              time of token creation\n";
    echo "   -w window            time window in seconds\n";
    echo "   -d render_duration   rendering duration (valid for c,d and e tokens only)\n";
    echo "   -x payload           eXtra payload\n";
    echo "   -y token_type        'c', 'd' or 'e'\n";
    echo "   -v                   Display program version\n";
    echo "   -h                   Display help message\n";
}

if ($argc == 0) {
    displayHelp(null);
    exit("Not enough command line arguments supplied.\n");
}

// Parse command line arguements
for($i=1;$i<$argc;$i++) {
    $arg = $argv[$i];

    // Modify flags to one case for comparison
    if( $arg[0] == "-" ) {
        $arg = strtolower($arg);
    }

    switch($arg) {
        case "-h":
            displayHelp(null);
            exit(0);
        case "-v":
            displayVersion();
            exit(0);
        case "-f":
            $userPath = $argv[++$i];
            continue 2;
        case "-i":
            $userIP = $argv[++$i];
            continue 2;
        case "-r":
            $userProfile = $argv[++$i];
            continue 2;
        case "-p":
            $userPasswd = $argv[++$i];
            continue 2;
        case "-t":
            $userTime  = $argv[++$i];
            continue 2;
        case "-w":
            $userWindow = $argv[++$i];
            continue 2;
        case "-d":
            $userDuration  = $argv[++$i];
            continue 2;
        case "-x":
            $userPayload = $argv[++$i];
            continue 2;
        case "-y":
            $userToken = $argv[++$i];
            continue 2;
        case "-k":
            $userKey = file_get_contents( $argv[++$i] );
            continue 2;
        case "--":
            continue 2;
        default:
            displayHelp($arg);
            exit(2);
    }
}

$factory = new StreamTokenFactory;
$token = $factory->getToken($userToken, $userPath,
         $userIP, $userProfile, $userPasswd, $userTime, $userWindow, $userDuration,
         $userPayload, $userKey);
echo "Token: " . $token->getToken() . "\n";


?>
