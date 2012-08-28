Akamai Secure Streaming, PHP token tools version 3.0.1
June 27, 2006

There is a command line PHP script available for generating tokens on the
command line. The script is compatible with both PHP4 and PHP5 on Windows and
Linux.

The command line parameters for gentoken.php are:
   -f path              path being requested
   -i ip                ip address of requester
   -r referer           referer or username
   -p passwd            password
   -k key               key filename and path (for e type tokens 32
                        bytes binary file)
   -t time              time of token creation
   -w window            time window in seconds
   -d render_duration   rendering duration (valid for c, d and e
                        tokens only)
   -x payload           eXtra payload
   -y token_type        'c', 'd' or 'e'
   -v version           Display program version

Creating tokens in PHP pages.

To create tokens directly from PHP, import the StreamTokenFactory.php script.
This class provides methods for creating C, D, and E type tokens and accepts
parameters similar to the command line tool. The only exception is the user
key for the type E token which requires a 32 byte binary string.

Example:

<?PHP

require 'StreamTokenFactory.php';

$factory = new StreamTokenFactory();

$userKey = file_get_contents("my_user_key.bin");

$typeEToken = $factory->makeTypeEToken(
    "/customer.download.akamai.com/1234/_!/filename.wmv",  // path
    NULL,         // User IP
    "my_profile", // User Profile
    "my_passwd",  // password
    NULL,         // Time (Null causes current time to be used.)
    86400,        // User window
    NULL,         // User duration
    NULL,         // User payload
    $userKey);    // User key (shared secret) 

echo "Token: " . $typeEToken->getToken();

?>

Alternatively, the getToken() method can be used which accepts the token type
as the first parameter. The valid values are "c", "d" and "e".

Example:

<?PHP

// Create the factory
$factory = new StreamTokenFactory;

// Load the binary key from disk
$userKey = file_get_contents("my_user_key.bin");

// Generate an E token.
$token = $factory->getToken("e",
    "/customer.download.akamai.com/1234/_!/filename.wmv",  // path (Required)
    NULL,         // User IP (NULL excludes paramters from the token.)
    "my_profile", // User Profile (NULL excludes paramters from the token.)
    "my_passwd",  // password (Required for D and E tokens.)
    NULL,         // Time (Null causes current time to be used.)
    86400,        // User window (Required)
    NULL,         // User duration (NULL excludes paramters from the token.)
    NULL,         // User payload  (NULL excludes paramters from the token.)
    $userKey);    // User key (shared secret)  (Required for type E tokens.
                  //   Ignored for other tokens.)

// Use token.
echo "Token: " . $token->getToken() . "\n";

?>
