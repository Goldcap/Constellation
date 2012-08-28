# Akamaize.pm   A collection of routines for working with Akamai ARLs.

# Set up our package.  We export our methods for easy use.
package Akamaize;
use Exporter;                           # Let's be a nice, well-behaved little package.
@ISA=qw(Exporter);
@EXPORT=qw(Munge mungeURL);
use strict;                                     # Yes, massochism pays...


##############################################################################
# Munge         Takes a string and "munges" it with the Akamai-standard munging
#                       algorithm.  Inputs to this routine are:
#                               $in             The input string
#                               $cp             The CP code in use
##############################################################################
sub Munge {
    my($in,$cp) = @_;
    my($out,$offset,$c);

    # Start our offset with the CP code, just so not everyone looks the same.
    # This is a *lame* algorithm...
    my($offset) = $cp;

    # Do the munging.
    foreach $c (split //, $in) {

        # Add the offset to our character value, then normalize to 0-255.
        $offset = ($offset + ord($c)) % 256;

        # Save this value in hex.  This is our "munged" character.
        $out .= sprintf("%02x",$offset);
    }

    # We're done.
    return($out);
}

##############################################################################
# RandomSerial          Makes a random serial number.  Note that this routine is
#                                       only moderately random and changes only once per second
#                                       for any given process.  You may need to do something
#                                       different here depending on your OS version.
##############################################################################
sub RandomSerial {
    my($serial);

    # See our random number generator.  Under Linux, 
    srand(time() ^ ($$ + ($$ << 15)) );
    do { $serial = int(rand(2048)) & (-1-3); } while($serial == 0);
}

1;
