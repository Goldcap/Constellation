# Authtoken		This package implements an initial cut at authentication token
#			generation.  It lays the groundwork for great things to come,
#			while providing a reasonable level of functionality and
#			security in the initial version.
package MakeToken;
use Exporter;
@ISA = qw(Exporter);
@EXPORT = qw(MakeToken MakeCDEtypeToken binary_to_64);

use Digest::MD5;

use Crypt::Rijndael;
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
		renddur => 64,
		);

# MakeToken	Input is a hash with the following elements:
#		path     The PATH of the request.
#		ip       The IP address of the user connecting.
#		profile  The authentication profile.
#		passwd   The site-specific password.
#		payload  Extra profile-specific information.
#		salt     Salt value. If omitted, salt is created. This
#		         means that for creating tokens, just leave off
#		         the salt; when checking, it is required.
#		key      The encryption key used by the Rijndael cypher,
#		         required for E-type tokens.
#		renddur  How long the player is allowed to play the clip.

sub MakeToken {
    my(%arg) = @_;
    my($string,@ASCII,$i,@token,@token_ints,$token,$index,$flag,$item, $type, $renddur);
    my($unit);
    my($time_buf) = "";    # This helps to generate the "t-w" format that the C code generates
    
    
    # Default is to create an A-type token.
    # Check to see if a C-type is requested
    if(defined $arg{type}) {
	$type = lc($arg{type});
	if ($type eq "c" || $type eq "d" || $type eq "e"){
	    return &MakeCDEtypeToken(
				     type=>$type,
				     path=>$arg{path},
				     ip=>$arg{ip},
				     profile=>$arg{profile},
				     passwd=>$arg{passwd},
				     window=>$arg{window},
				     time=>$arg{time},
				     payload=>$arg{payload},
				     key=>$arg{key},
				     renddur=>$arg{renddur}
				     );
	}
    }
    # If no salt was given, make some.  This generates two random characters
    # in the approved range for salt (from the crypt(3) man page,
    # "a-zA-Z0-9./").
    if(! defined $arg{salt}) {
	srand( time() ^ ($$ + ($$ << 15)));
	$arg{salt} = &to64(int rand(64)) . &to64(int rand(64));
    }

    # If a time window is specified, add it onto the token.
    if($arg{window}) {

	# Strip off any units identifier.  's' means seconds, 'm' means
	# minutes, 'h' hours, 'd' days.  If no units are given, seconds are
	# presumed.  Convert to seconds.
	if($arg{window} =~ /[a-zA-Z]$/) {
	    ($arg{window},$unit) = split(/[a-zA-Z]$/,$arg{window},2);
	    if($unit eq 'm') { $arg{window} *= 60; }
	    elsif($unit eq 'h') { $arg{window} *= 60*60; }
	    elsif($unit eq 'd') { $arg{window} *= 60*60*24; }
	}

	# For the moment, the time window is presumed to extend "window"
	# units ahead of the specified time (or current time) and "window"
	# units behind the current time.  This is to allow some compensation
	# for clocks being set somewhat inaccurately.  Of course, the tighter
	# you set your window, the more important it becomes to synchronize
	# clocks.

	# If a "time" argument is not passed in, the current time (seconds since Jan 1, 1970 UTC)
	# is used.
        if(! $arg{time}) { $arg{time} = time; }

        # Convert our time and window to base 64.
        $arg{time} = &to64($arg{time});
	$arg{window} = &to64($arg{window});

	$time_buf = $arg{time} . "-" . $arg{window}
    }

    # Roll up everything into an 8-byte string.  We do this by appending
    # all the above components up into a long string, then "folding"
    # it on 8 character boundaries and adding it all together.  The
    # resulting values are "modded" into the 0-255 range.
    $string = $arg{path} . $arg{ip} . $time_buf . $arg{profile} . $arg{passwd} . $arg{payload};
    @ASCII = unpack("C*",$string);

    # Initialize token_ints[] to the necessary size of 8.
    for $i (0 .. 7) {
	$token_ints[$i] = 0;
    }

    # Now do the folding.
    for $i (0 .. $#ASCII) {
	$index = $i % 8;
	$token_ints[$index] += $ASCII[$i];
    }

    # "Mod" everything to 1-255 range. Can't have '\0' (end-of string)
    for $i (0 .. $#token_ints) { $token[$i] = (1 + ($token_ints[$i] % 255)); }

    # Make the array into a string (8 bytes long).
    $token = pack("C*",@token);

    # Temporary fix to convert crypt-hostile '_'s with '.'s.
    # Can't use crypt-friendly '/'s because those are URL-hostile.
    $arg{salt} =~ s/_/./g;

    # Run crypt on it.
    $token = crypt("$token",$arg{salt});

    # Convert '/' to '_'.  Crypt allows '/' as one of its characters.
    # This is inconvenient to us since '/' is a directory marker in a PATH.
    # We simply convert from '/' to '_' to avoid this. 
    $token =~ s/\//_/g;

    # Make up the "flag" byte.
    $flag = 0;
    foreach $item (keys %flagbits) {
	if ((defined $arg{$item}) && (length($arg{$item}) > 0)) {$flag += $flagbits{$item}; }
    }
    $flag = &to64($flag);

    # Add on the version and flag bytes.
    $token = 'a' . $flag . $token;

    if ($arg{time} && $arg{window}) {
	# Add the time & window to the token
	$token = $token . $arg{time} . '-' . $arg{window};
    }

    # Append the trailer to the token
    $token = $token . &MakeTrailer( token=>$token,
				    profile=>$arg{profile},
				    payload=>$arg{payload} );

    # We're done!
    return $token;
}


# MakeCDEtypeToken    Input is a hash with the following elements:
#		path     The PATH of the request.
#		ip       The IP address of the user connecting.
#		profile  The authentication profile
#		passwd   The site-specific password.
#		payload  Extra profile-specific information.
sub MakeCDEtypeToken {
    my(%arg) = @_;
    my($string,@ASCII,$i,@token,@token_ints,$ascii,$token,$index,$flag,$item, $renddur, $type);
    my($unit);
    my($time_buf) = "";    # This helps to generate the "t-w" format that the C code generates
    my($ctx, $digest, $tmp_digest_buf, $encoded_digest, $dectx);

    $type = lc($arg{type});
    # If a time window is specified, add it onto the token.
    if($arg{window}) {

	# Strip off any units identifier.  's' means seconds, 'm' means
	# minutes, 'h' hours, 'd' days.  If no units are given, seconds are
	# presumed.  Convert to seconds.
	if($arg{window} =~ /[a-zA-Z]$/) {
	    ($arg{window},$unit) = split(/[a-zA-Z]$/,$arg{window},2);
	    if($unit eq 'm') { $arg{window} *= 60; }
	    elsif($unit eq 'h') { $arg{window} *= 60*60; }
	    elsif($unit eq 'd') { $arg{window} *= 60*60*24; }
	}

	# For the moment, the time window is presumed to extend "window"
	# units ahead of the specified time (or current time) and "window"
	# units behind the current time.  This is to allow some compensation
	# for clocks being set somewhat inaccurately.  Of course, the tighter
	# you set your window, the more important it becomes to synchronize
	# clocks.

	# If a "time" argument is not passed in, the current time (seconds since Jan 1, 1970 UTC)
	# is used.
        if(! $arg{time}) { $arg{time} = time; }

	# Convert our time and window to base 64.
        $arg{time} = &to64($arg{time});
	$arg{window} = &to64($arg{window});

	$time_buf = $arg{time} . "-" . $arg{window}
    }

    # If a renddur is specified, add it onto the token.
    if($arg{renddur}) {
	# Convert our time  to base 64.
        $arg{renddur} = &to64($arg{renddur});
    }



    $ascii = $arg{path} . $arg{ip} . $time_buf . $arg{profile} . $arg{passwd} . $arg{payload}. $arg{renddur};

    # Use MD5 to create the digest field.
    $ctx = Digest::MD5->new;

    $ctx->add($ascii);

    $digest = $ctx->digest;

    #For C type token, we are done.
    #For D and E type tokens, we need to do one more MD5 of (digest,passwd).

    if ($type eq "d" || $type eq "e") {
	$dectx = Digest::MD5->new;
	$tmp_digest_buf = $digest . $arg{passwd};
	$dectx->add($tmp_digest_buf);
	$digest = $dectx->digest;	
    }


    #For E type token, we need to encrypt the token with the key as well if a key is supplied.
    #The way rijndael cipher works is complex. We generate tokens at two different places. One
    #is when the actual token generator calls MakeCDEtypeToken. At that time, we need to 
    #encrypt the double MD5 digest so that when the token floats in public, it is not
    #vulnerable to key recovery attack. Another time this function is called is when
    #we generate a dummy token to compare against the token generated in the first case.
    #If we encrypt the double digest again and compare it with original, it will not work
    #because rijndael-encrypt(block, key) will return different values for same input.
    #So the way to get around is by decrypting the digest in original token, and generating
    #the new token without encrypting the double digest, and the compare.


    my ($final_digest, $decoded_key, $encoded_digest, $cipher, $encrypted_digest);
    my ($index, $tmp, @udigist, @xordigest, @toiv, $in, $iv);
    if($type eq "e"){
	if ($arg{key}) {
	    #Encrypt the 16 byte digest with key, save it in digest.#
	    srand;
	    $decoded_key = $arg{key};
	    @udigist=unpack("C*", $digest);
	    for $index (0...15){
		$tmp= int(rand 255);
#		$tmp= int(255);
		push(@toiv, $tmp);
	    }
	    $iv = pack("C*", @toiv);
	    $cipher = new Crypt::Rijndael $decoded_key, Crypt::Rijndael::MODE_CBC;    
	    $cipher->set_iv($iv);
	    $encrypted_digest = $cipher->encrypt($digest);
	    $final_digest = pack "a16a16", $iv, $encrypted_digest;
#	    print "decoded_key: ";
#	    &binary_to_64($decoded_key);	    
#	    print "\ndigest:";
#	    &binary_to_64($digest);	    
	}
	else {
	    $final_digest = $digest;
	}
	    
	$encoded_digest = &binary_to_64($final_digest);
    }
    else{
	# Convert the binary digest for our base64 representation
	@ASCII = unpack("C*",$digest);

	# Base64 the ints in the digest.
	for $i (0 .. 15) {
	    my($encoded_char) = &to64($ASCII[$i]);
	    if (length($encoded_char) < 2) {
		$encoded_digest .= "a";
	    }
	    $encoded_digest .= $encoded_char;
	}
    }
    # Make up the "flag" byte.
    $flag = 0;

    foreach $item (keys %flagbits) {
	if ((defined $arg{$item}) && (length($arg{$item}) > 0)) {
	    if($item eq 'renddur'){
		if( $arg{renddur}){
		    $flag += $flagbits{$item}; 
		}
	   }
	    else{
	       $flag += $flagbits{$item}; 
	   }
    
	}
    }

    $flag = &to64($flag);

    if (length($flag) == 1) {
	# Add a leading 0 ('a') to the flag representation
	$flag = 'a' . $flag;
    }

    # Add on the version and flag bytes.
    $token = $type . $flag . $encoded_digest;

    if ($arg{time} && $arg{window}) {
	# Add the time & window to the token
	$token = $token . '-' . $arg{time} . '-' . $arg{window};
    }

    if ($arg{renddur}) {
	# Add the renddur to the token
	$token = $token . '-' . $arg{renddur};
    }

    # Append the trailer to the token
    $token = $token . &MakeTrailer( token=>$token,
				    profile=>$arg{profile},
				    payload=>$arg{payload} );

    # We're done!
    return $token;
}



# to64	Converts an integer between 0 and essentially infinity, to an ascii
#		character in the range "a-zA-Z0-9._".  This allows us
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

sub binary_to_64{
    my ($barray) = @_;
    my (@ASCII, $en64, $encoded_char, $i);

    @ASCII = unpack("C*", $barray);
    for $i (0 .. $#ASCII) {
	my($encoded_char) = &to64($ASCII[$i]);
	if (length($encoded_char) < 2) {
	    $en64 .= "a";
	}
	$en64 .= $encoded_char;
    }
    return $en64;
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

sub from64_to_binary{
    my ($txt64) = @_;
    my (@ASCII, $i);
    my (@DARRAY, $dec);
    @ASCII = split //, $txt64;
    for $i (0 .. $#ASCII/2) {
	my($str) = $ASCII[$i*2].$ASCII[2*$i+1];
	$dec = &from64($str);
	push(@DARRAY, $dec);
    }
    return pack("C*", @DARRAY);
}

#
#
sub MakeTrailer {
    my(%arg) = @_;
    
    my ($trailer);

    if ((defined $arg{profile}) && (length($arg{profile}) > 0)) {
      $trailer .= "-" . $arg{profile}
    }

    if ( $arg{token} =~ /^a/ ) {
      if ((defined $arg{payload}) && (length($arg{payload}) > 0)) {
	$trailer .= "-" . $arg{payload}
      }
    }
    elsif( $arg{token} =~ /^c/ || $arg{token} =~ /^d/ || $arg{token} =~ /^e/) {
      if ((defined $arg{payload}) && (length($arg{payload}) > 0)) {
	$trailer .= "-";
	my( $ch );
	foreach $ch (split( //, $arg{payload} )) {
	  my( $enc_ch ) = to64( ord( $ch ) );
	  if( length( $enc_ch ) < 2 ) {
	    $trailer .= 'a';
	  }
	  $trailer .= $enc_ch;
	}
      }
    }
    else {
      return -1;
    }
    return Obfuscate($arg{token}, $trailer);
}

#
#
sub Obfuscate {
    my($token, $string) = @_;
    
    my($ch,$obfuscated);
    my($rot_base) = 9;
    my($rot_val) = 0;
    my($indx) = 0;
    my($encrypted_part);
    my($encrypted_len);
    
    my(@e_digits);

    if( $token =~ /^a/ ) {
      $encrypted_len = 13;
      $encrypted_part = substr($token,2,$encrypted_len);
    }
    elsif( $token =~ /^c/ ||$token =~ /^d/||$token =~ /^e/) {
      $encrypted_len = 32;
      $encrypted_part = substr($token,3,$encrypted_len);
    }
    else {
      return -1;
    }

    foreach $ch (split(//,$encrypted_part)) {
	push(@e_digits,(from64($ch) % 10));
    }

    foreach $ch (split(//,$string)) {
	$ch = ord($ch);
	# Rotate the character by the current rot value.
	$rot_val = $rot_base + $e_digits[$indx % $encrypted_len];
	$indx += 1;
	
	if ($ch >= ord('a') && $ch <= ord('z')) {
	    $ch = $ch + $rot_val;
	    if ($ch > ord('z')) {
		# extend the rotation into the capitals
		$ch = ord('A') + $ch - ord('z') - 1;
	    }
	} elsif ( ($ch >= ord('A')) && ($ch <= ord('Z')) ) {
	    $ch = $ch + $rot_val;
	    if ( $ch > ord('Z') ) {
		# extend the rotation into the digits
		$ch = ord('0') + $ch - ord('Z') - 1;
		#  see if we extended past all the digits 
		if ( $ch > ord('9') ) {
		    # extend the rotation into the lowers
		    $ch = ord('a') + ($ch - ord('9') - 1);
		}
	    }
	} elsif ( $ch >= ord('0') && $ch <= ord('9') ) {
	    $ch = $ch + $rot_val;
	    if ( $ch > ord('9') ) {
		# extend the rotation into the lowers
		$ch = ord('a') + $ch - ord('9') - 1;
	    }
	}
	$obfuscated .= chr($ch);
    }
    return $obfuscated;
}

###########################################################################
# MungeArl Munge the arl.
###########################################################################
sub MungeArl
{
    my( $cpCode,        # The CpCode of the arl.
        $stringToMunge  # The Url to munge.
    ) = @_;
    my ($hash) = $cpCode+0;
    my ($result) = "1a1a1a";
    my ($character);
    $stringToMunge=~s/^\///;
    foreach $character (split //, $stringToMunge) {
        $hash=($hash+ord($character))%256;
        $result.=sprintf("%02x",$hash);
    }
    return($result);
}


###########################################################################
# MakeAuthArl Creates and Arl to authenticated content
###########################################################################
sub MakeAuthArl
{
  my( $url,        # The url of the authenticed content
      $useurl,     # Should the url be part of the token (path)
      $ip,         # IP of end user
      $profile,    # The authentication profile
      $password,   # Password for above profile, stored in _authf file
      $payload,    # Any extra payload information
      $window,     # Time Window the token is valid for
      $time,       # Center time token is valid (+/- $window)
      $salt,       # Salt to create the token with
      $type,       # Type of token to create
      $format,     # Type of media 0 - real , 1 - Wms
      $munged,     # Munge the output arl or not.
      $cpcode,     # cpcode of the arl
      $serial,     # serial number 
      $key,        # key file
      $renddur     # rendering duration 
  ) = @_;
  my ($token);
  my($munged_url);
  my($aurl);
  my($typecode);
  my($arl);

  chomp ( $token );
  if ($munged) {
    $munged_url="/".(&MungeArl($cpcode,$url));
    $aurl=$munged_url;
    $typecode=5;
  } else {
    $typecode=7;
    $aurl=$url;
  }
  my $binKey;
  if ($key)
  {
      $binKey = &from64_to_binary($key);
  }
  $type = lc($type);
  if ($useurl) {
      if ($type ne "c" && $type ne "d" && $type ne "e") {
        my($tmpurl);  # use this to add on pieces from the arl that are
                      # in a type tokens.
        $tmpurl = "/$typecode/$serial/$cpcode/v001$url";
        if ($format == 0) {
          $tmpurl = ("/ondemand" . $tmpurl );
        }
        $token = &MakeToken(path=>$tmpurl, 
          ip=>$ip, profile=>$profile, passwd=>$password, type=>$type,
          payload=>$payload, window=>$window, time=>$time, salt=>$salt);
      } else { 
        # c d e type tokens use just the url so we are good already.
        $token = &MakeToken(path=>$url, ip=>$ip,
          profile=>$profile, passwd=>$password, type=>$type,
          payload=>$payload, window=>$window, time=>$time, salt=>$salt, key=>$binKey, renddur=>$renddur);
      }
  } else {
      $token = &MakeToken(path=>undef , ip=>$ip,
        profile=>$profile, passwd=>$password, type=>$type,
        payload=>$payload, window=>$window, time=>$time, salt=>$salt, key=>$binKey, renddur=>$renddur);
  }

  if ($format == 1){
    $arl = "mms://a$serial.m.akastream.net/$typecode/$serial/$cpcode/v001$aurl?auth=$token&aifp=abcd";
  } elsif ($format == 2){
    $arl = "rtsp://a$serial.m.akastream.net/$typecode/$serial/$cpcode/v001$aurl?auth=$token&aifp=abcd";
  }else {
    $arl = "rtsp://a$serial.r.akareal.net/ondemand/$typecode/$serial/$cpcode/v001$aurl/$token/abcd";
  }
  return ($arl,$token);
}





