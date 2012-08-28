# AuthToken		This package implements an initial cut at authentication token
#			generation.  It lays the groundwork for great things to come,
#			while providing a reasonable level of functionality and
#			security in the initial version.
package ParseToken;
use Exporter;
use IO::Handle;
@ISA = qw(Exporter);
@EXPORT = qw(ParseToken);

# Be a masochist.  It's good for you!
use strict;

# Some handy constants.
my %flagbits = (
		ip => 1,
		path => 2,
		profile => 4,
		passwd => 8,
		window => 16,
		payload => 32,
		);

# ParseToken	Extract all of the fields contained in the given token:
#			token	The token to parse.
#			version	extracted from token
#			flag	extracted from token.
#			salt	extracted from token.
#			time	extracted from token
#			window	extracted from token
#			profile	extracted from token
#			payload	extracted from token
sub ParseToken {
    my(%arg) = @_;
    my($version,$salt,$flags,$time,$window,$now,$profile,$passwd,$payload);
    my($encrypted_str,$trailer);

    # Get the version byte.
    $version = substr($arg{token},0,1);

    # First, see if we should branch to a different parser routine.
    if($version eq 'c' || $version eq 'd' || $version eq 'e') { return &ParseCDEtypeToken( %arg ); }

    # None of the variations above matched.
    # If this token does not start with the default 'a' then return an error.
    if($version ne 'a') { return 0; }

    # Get the flag byte.
    $flags = &from64(substr($arg{token},1,1));

    # Get the salt.
    $salt = substr($arg{token},2,2);

    ($encrypted_str,$trailer) =
	($arg{token} =~ /^(.{15})(.*)$/);

    # If the flags byte indicates that time is included, strip off the time
    # information.
    if($flags & $flagbits{window}) {

	# Pull out all the possible elements
	($time, $window) = split("-", $trailer);
	
	# remove the time & window from the trailer
	$trailer = substr( $trailer, length($time) + length($window) + 1 );
	
	# Convert the time elements back to decimal.
	$time = &from64($time);
	$window = &from64($window);
    }

    # Extract the profile and the payload from the trailer
    ($profile, $passwd, $payload) = &ParseTrailer(trailer => $trailer, encrypted_str => $encrypted_str, flags => $flags);

    # return all of the token's fields
    return ( ($version, $flags, $salt, $time, $window, $profile, $payload) );
}

# ParseCDEtypeToken	Extract all of the fields contained in the given token:
#			token	The token to parse.
#			version	extracted from token
#			flag	extracted from token.
#			time	extracted from token
#			window	extracted from token
#			profile	extracted from token
#			payload	extracted from token
sub ParseCDEtypeToken {
    my(%arg) = @_;
    my($version,$flags,$time,$window,$now,$profile,$passwd,$payload);
    my($encrypted_str,$trailer);
    my($dummy);

    # Get the version byte.
    $version = substr($arg{token},0,1);

    # The supplied token is not of the expected type, return an error.
#    open OUT, ">/tmp/abc.txt";
#    print OUT "into ParseCDE token\n";
#    autoflush OUT 1;
    if($version ne 'c'&& $version ne 'd'&&$version ne 'e') { return 0; }

    # Get the flag byte.
    $flags = &from64(substr($arg{token},1,2));

    ($encrypted_str,$trailer) =
	($arg{token} =~ /^(.{35})(.*)$/);

    # If the flags byte indicates that time is included, strip off the time
    # information.
    if($flags & $flagbits{window}) {

	# Pull out all the possible elements
	($dummy, $time, $window) = split("-", $trailer);
	
	# remove the time & window from the trailer
	$trailer = substr( $trailer, length($time) + 1 + length($window) + 1 );
	
	# Convert the time elements back to decimal.
	$time = &from64($time);
	$window = &from64($window);
    }

    # Extract the profile and the payload from the trailer
    ($profile, $passwd, $payload) = &ParseTrailer(trailer => $trailer, encrypted_str => $encrypted_str, flags => $flags);

    # return all of the token's fields
    return ( ($version, $flags, "", $time, $window, $profile, $payload) );
}


# ParseCtypeToken	Extract all of the fields contained in the given token:
#			token	The token to parse.
#			version	extracted from token
#			flag	extracted from token.
#			time	extracted from token
#			window	extracted from token
#			profile	extracted from token
#			payload	extracted from token
sub ParseCtypeToken {
    my(%arg) = @_;
    my($version,$flags,$time,$window,$now,$profile,$passwd,$payload);
    my($encrypted_str,$trailer);
    my($dummy);

    # Get the version byte.
    $version = substr($arg{token},0,1);

    # The supplied token is not of the expected type, return an error.
    if($version ne 'c') { return 0; }

    # Get the flag byte.
    $flags = &from64(substr($arg{token},1,2));

    ($encrypted_str,$trailer) =
	($arg{token} =~ /^(.{35})(.*)$/);

    # If the flags byte indicates that time is included, strip off the time
    # information.
    if($flags & $flagbits{window}) {

	# Pull out all the possible elements
	($dummy, $time, $window) = split("-", $trailer);
	
	# remove the time & window from the trailer
	$trailer = substr( $trailer, length($time) + 1 + length($window) + 1 );
	
	# Convert the time elements back to decimal.
	$time = &from64($time);
	$window = &from64($window);
    }

    # Extract the profile and the payload from the trailer
    ($profile, $passwd, $payload) = &ParseTrailer(trailer => $trailer, encrypted_str => $encrypted_str, flags => $flags);

    # return all of the token's fields
    return ( ($version, $flags, "", $time, $window, $profile, $payload) );
}

# to64	Converts an integer between 0 and essentially infinity, to an ascii
#		character in the range "a-zA-Z0-9./".  This allows us
#		to encode numbers as text characters that are valid URL components.
#		Note that the results are invalid if you pass in a number less than
#		zero.
sub to64 {
    my($value) = @_;
    my($string,$byte);

    while($value >= 64) {
	$byte = $value % 64;
	$value = int($value / 64);
	if($byte < 26) { $string = chr($byte + 97) . $string; }		# a-z
	elsif($byte < 52) { $string = chr($byte - 26 + 65) . $string; }	# A-Z
	elsif($byte < 62) { $string = chr($byte - 52 + 48) . $string; }	# 0-9
	elsif($byte == 62) { $string = chr(46) . $string; }				# .
	elsif($byte == 63) { $string = chr(95) . $string; }				# _
    }
    $byte = $value % 64;
    $value = int($value / 64);
    if($byte < 26) { $string = chr($byte + 97) . $string; }		# a-z
    elsif($byte < 52) { $string = chr($byte - 26 + 65) . $string; }	# A-Z
    elsif($byte < 62) { $string = chr($byte - 52 + 48) . $string; }	# 0-9
    elsif($byte == 62) { $string = chr(46) . $string; }				# .
    elsif($byte == 63) { $string = chr(95) . $string; }				# _
    $string;
}

# from64	Undoes what to64 does.
sub from64 {
    my($string) = @_;
    my($byte,$value,$bit);

    $bit = 0;
    foreach $byte (reverse split(//,$string)) {
	$byte = ord($byte);
	if($byte == 46) { $byte = 62; }		# .
	elsif($byte == 95) { $byte = 63; }		# _
	elsif($byte <= 57) { $byte = $byte - 48 + 52; }	# 0-9
	elsif($byte <= 90) { $byte = $byte - 65 + 26; }	# A-Z
	elsif($byte <= 122) { $byte = $byte - 97; }		# a-z
	$value += $byte * 64**$bit;
	$bit++;
    }
    return $value;
}

#
#
sub DeObfuscate {
    my(%arg) = @_;
    
    my($ch,$original);
    my($rot_base) = 9;
    my($rot_val) = 0;
    my($indx) = 0;
    my($encrypted_part);
    my($encoded_indx);
    my($digest_len);
    
    my(@e_digits);

    # Get the version byte.
    my($version) = substr($arg{encrypted_part},0,1);

    if ($version eq 'c'|| $version eq 'd' || $version eq 'e') {
	$encoded_indx = 3;
	$digest_len = 32;
    } else {
	$encoded_indx = 2;
	$digest_len = 13;
    }

    $encrypted_part = substr($arg{encrypted_part}, $encoded_indx, $digest_len);
    
    foreach $ch (split(//,$encrypted_part)) {
	push(@e_digits,(from64($ch) % 10));
    }
    
    foreach $ch (split(//,$arg{obfuscated})) {
	$ch = ord($ch);
	# increment the rot<x> with values between 11 and 17
	$rot_val = $rot_base + $e_digits[$indx % $digest_len];
	$indx += 1;
	
	if ( $ch >= ord('a') && $ch <= ord('z') ) {
	    $ch = $ch - $rot_val;
	    if ( $ch < ord('a') ) {
		# extend the rotation into the digits
		$ch = ord('9') - (ord('a') - $ch - 1);
		# see if we extended past all of the digits
		if ( $ch < ord('0') ) {
		    # extend into the capitals
		    $ch = ord('Z') - (ord('0') - $ch - 1);
		}
	    }
	} elsif ( $ch >= ord('A') && $ch <= ord('Z') ) {
	    $ch = $ch - $rot_val;
	    if ( $ch < ord('A') ) {
		# extend the rotation into the lower case
		$ch = ord('z')  - (ord('A') - $ch - 1);
	    }
	} elsif ( $ch >= ord('0') && $ch <= ord('9') ) {
	    $ch = $ch - $rot_val;
	    if ( $ch < ord('0') ) {
		# extend the rotation into the capitals
		$ch = ord('Z') - (ord('0') - $ch - 1);
	    }
	}
	$original .= chr($ch);
    }
    return $original;
}

# ParseTrailer	Extract and DeObfuscate the profile and passwd from the token.
#               The arguments hold:
#			token	The token from which to extract the token.
sub ParseTrailer {
    my(%arg) = @_;
    my($trailer,$dummy,$profile,$passwd,$payload);
    my($first_field, $second_field);

    $trailer = &DeObfuscate(obfuscated => $arg{trailer}, encrypted_part => $arg{encrypted_str});

    ($dummy, $first_field, $second_field) = split("-", $trailer);

    if ($arg{flags} & $flagbits{profile}) {
	$profile = $first_field;
	if ($arg{flags} & $flagbits{payload}) {
	    $payload = $second_field;
	}
    } else {
	# There is no profile, if there is a field, it must be the payload
	if ($arg{flags} & $flagbits{payload}) {
	    $payload = $first_field;
	}
    }

    # If this is a C-type token, then decode the payload from base64.

    # Get the version byte.
    my($version) = substr($arg{encrypted_str},0,1);

    if ($version eq 'c' || $version eq 'e' || $version eq 'd') {
	# Decode every pair of base64 characters in payload into the original string.
	my($encoded_payload) = $payload;
	$payload = "";   # Reset payload to get ready to accept the untranslated version
	
	my($base64_str) = "";
	my($ch);
	my($encoded_indx) = 0;
	foreach $ch (split( //, $encoded_payload)) {
	    $base64_str .= $ch;
	    if ($encoded_indx == 1) {
		# First decode this character
		$payload .= chr(from64($base64_str));
		# Then reset for the next pair of base64 characters
		$encoded_indx = 0;
		$base64_str = "";
	    } else {
		$encoded_indx++;
	    }
	}
    }

    return ( ($profile, $passwd, $payload) );
}
