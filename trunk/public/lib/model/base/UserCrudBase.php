<?php
       
   class UserCrudBase extends Utils_PageWidget { 
   
    var $User;
   
       var $user_id;
   var $user_fb_uid;
   var $user_t_uid;
   var $user_full_name;
   var $user_fname;
   var $user_lname;
   var $user_username;
   var $user_email;
   var $user_editable_email;
   var $user_password;
   var $user_password_legacy;
   var $user_bio;
   var $user_facebook_url;
   var $user_twitter_url;
   var $user_website_url;
   var $user_status;
   var $user_created_at;
   var $user_updated_at;
   var $user_type;
   var $user_contact_email_id;
   var $user_t_username;
   var $user_photo_url;
   var $user_image;
   var $user_default_timezone;
   var $user_birthday_date;
   var $user_broadcast_status;
   var $user_t_oauth_token;
   var $user_t_oauth_token_secret;
   var $user_ual;
   var $user_optin;
   var $user_optin_date;
   var $user_tagline;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getUserId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->User = UserPeer::retrieveByPK( $id );
    } else {
      $this ->User = new User;
    }
  }
  
  function hydrate( $id ) {
      $this ->User = UserPeer::retrieveByPK( $id );
  }
  
  function getUserId() {
    if (($this ->postVar("user_id")) || ($this ->postVar("user_id") === "")) {
      return $this ->postVar("user_id");
    } elseif (($this ->getVar("user_id")) || ($this ->getVar("user_id") === "")) {
      return $this ->getVar("user_id");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserId();
    } elseif (($this ->sessionVar("user_id")) || ($this ->sessionVar("user_id") == "")) {
      return $this ->sessionVar("user_id");
    } else {
      return false;
    }
  }
  
  function setUserId( $str ) {
    $this ->User -> setUserId( $str );
  }
  
  function getUserFbUid() {
    if (($this ->postVar("user_fb_uid")) || ($this ->postVar("user_fb_uid") === "")) {
      return $this ->postVar("user_fb_uid");
    } elseif (($this ->getVar("user_fb_uid")) || ($this ->getVar("user_fb_uid") === "")) {
      return $this ->getVar("user_fb_uid");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserFbUid();
    } elseif (($this ->sessionVar("user_fb_uid")) || ($this ->sessionVar("user_fb_uid") == "")) {
      return $this ->sessionVar("user_fb_uid");
    } else {
      return false;
    }
  }
  
  function setUserFbUid( $str ) {
    $this ->User -> setUserFbUid( $str );
  }
  
  function getUserTUid() {
    if (($this ->postVar("user_t_uid")) || ($this ->postVar("user_t_uid") === "")) {
      return $this ->postVar("user_t_uid");
    } elseif (($this ->getVar("user_t_uid")) || ($this ->getVar("user_t_uid") === "")) {
      return $this ->getVar("user_t_uid");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserTUid();
    } elseif (($this ->sessionVar("user_t_uid")) || ($this ->sessionVar("user_t_uid") == "")) {
      return $this ->sessionVar("user_t_uid");
    } else {
      return false;
    }
  }
  
  function setUserTUid( $str ) {
    $this ->User -> setUserTUid( $str );
  }
  
  function getUserFullName() {
    if (($this ->postVar("user_full_name")) || ($this ->postVar("user_full_name") === "")) {
      return $this ->postVar("user_full_name");
    } elseif (($this ->getVar("user_full_name")) || ($this ->getVar("user_full_name") === "")) {
      return $this ->getVar("user_full_name");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserFullName();
    } elseif (($this ->sessionVar("user_full_name")) || ($this ->sessionVar("user_full_name") == "")) {
      return $this ->sessionVar("user_full_name");
    } else {
      return false;
    }
  }
  
  function setUserFullName( $str ) {
    $this ->User -> setUserFullName( $str );
  }
  
  function getUserFname() {
    if (($this ->postVar("user_fname")) || ($this ->postVar("user_fname") === "")) {
      return $this ->postVar("user_fname");
    } elseif (($this ->getVar("user_fname")) || ($this ->getVar("user_fname") === "")) {
      return $this ->getVar("user_fname");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserFname();
    } elseif (($this ->sessionVar("user_fname")) || ($this ->sessionVar("user_fname") == "")) {
      return $this ->sessionVar("user_fname");
    } else {
      return false;
    }
  }
  
  function setUserFname( $str ) {
    $this ->User -> setUserFname( $str );
  }
  
  function getUserLname() {
    if (($this ->postVar("user_lname")) || ($this ->postVar("user_lname") === "")) {
      return $this ->postVar("user_lname");
    } elseif (($this ->getVar("user_lname")) || ($this ->getVar("user_lname") === "")) {
      return $this ->getVar("user_lname");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserLname();
    } elseif (($this ->sessionVar("user_lname")) || ($this ->sessionVar("user_lname") == "")) {
      return $this ->sessionVar("user_lname");
    } else {
      return false;
    }
  }
  
  function setUserLname( $str ) {
    $this ->User -> setUserLname( $str );
  }
  
  function getUserUsername() {
    if (($this ->postVar("user_username")) || ($this ->postVar("user_username") === "")) {
      return $this ->postVar("user_username");
    } elseif (($this ->getVar("user_username")) || ($this ->getVar("user_username") === "")) {
      return $this ->getVar("user_username");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserUsername();
    } elseif (($this ->sessionVar("user_username")) || ($this ->sessionVar("user_username") == "")) {
      return $this ->sessionVar("user_username");
    } else {
      return false;
    }
  }
  
  function setUserUsername( $str ) {
    $this ->User -> setUserUsername( $str );
  }
  
  function getUserEmail() {
    if (($this ->postVar("user_email")) || ($this ->postVar("user_email") === "")) {
      return $this ->postVar("user_email");
    } elseif (($this ->getVar("user_email")) || ($this ->getVar("user_email") === "")) {
      return $this ->getVar("user_email");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserEmail();
    } elseif (($this ->sessionVar("user_email")) || ($this ->sessionVar("user_email") == "")) {
      return $this ->sessionVar("user_email");
    } else {
      return false;
    }
  }
  
  function setUserEmail( $str ) {
    $this ->User -> setUserEmail( $str );
  }
  
  function getUserEditableEmail() {
    if (($this ->postVar("user_editable_email")) || ($this ->postVar("user_editable_email") === "")) {
      return $this ->postVar("user_editable_email");
    } elseif (($this ->getVar("user_editable_email")) || ($this ->getVar("user_editable_email") === "")) {
      return $this ->getVar("user_editable_email");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserEditableEmail();
    } elseif (($this ->sessionVar("user_editable_email")) || ($this ->sessionVar("user_editable_email") == "")) {
      return $this ->sessionVar("user_editable_email");
    } else {
      return false;
    }
  }
  
  function setUserEditableEmail( $str ) {
    $this ->User -> setUserEditableEmail( $str );
  }
  
  function getUserPassword() {
    if (($this ->postVar("user_password")) || ($this ->postVar("user_password") === "")) {
      return $this ->postVar("user_password");
    } elseif (($this ->getVar("user_password")) || ($this ->getVar("user_password") === "")) {
      return $this ->getVar("user_password");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserPassword();
    } elseif (($this ->sessionVar("user_password")) || ($this ->sessionVar("user_password") == "")) {
      return $this ->sessionVar("user_password");
    } else {
      return false;
    }
  }
  
  function setUserPassword( $str ) {
    $this ->User -> setUserPassword( $str );
  }
  
  function getUserPasswordLegacy() {
    if (($this ->postVar("user_password_legacy")) || ($this ->postVar("user_password_legacy") === "")) {
      return $this ->postVar("user_password_legacy");
    } elseif (($this ->getVar("user_password_legacy")) || ($this ->getVar("user_password_legacy") === "")) {
      return $this ->getVar("user_password_legacy");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserPasswordLegacy();
    } elseif (($this ->sessionVar("user_password_legacy")) || ($this ->sessionVar("user_password_legacy") == "")) {
      return $this ->sessionVar("user_password_legacy");
    } else {
      return false;
    }
  }
  
  function setUserPasswordLegacy( $str ) {
    $this ->User -> setUserPasswordLegacy( $str );
  }
  
  function getUserBio() {
    if (($this ->postVar("user_bio")) || ($this ->postVar("user_bio") === "")) {
      return $this ->postVar("user_bio");
    } elseif (($this ->getVar("user_bio")) || ($this ->getVar("user_bio") === "")) {
      return $this ->getVar("user_bio");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserBio();
    } elseif (($this ->sessionVar("user_bio")) || ($this ->sessionVar("user_bio") == "")) {
      return $this ->sessionVar("user_bio");
    } else {
      return false;
    }
  }
  
  function setUserBio( $str ) {
    $this ->User -> setUserBio( $str );
  }
  
  function getUserFacebookUrl() {
    if (($this ->postVar("user_facebook_url")) || ($this ->postVar("user_facebook_url") === "")) {
      return $this ->postVar("user_facebook_url");
    } elseif (($this ->getVar("user_facebook_url")) || ($this ->getVar("user_facebook_url") === "")) {
      return $this ->getVar("user_facebook_url");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserFacebookUrl();
    } elseif (($this ->sessionVar("user_facebook_url")) || ($this ->sessionVar("user_facebook_url") == "")) {
      return $this ->sessionVar("user_facebook_url");
    } else {
      return false;
    }
  }
  
  function setUserFacebookUrl( $str ) {
    $this ->User -> setUserFacebookUrl( $str );
  }
  
  function getUserTwitterUrl() {
    if (($this ->postVar("user_twitter_url")) || ($this ->postVar("user_twitter_url") === "")) {
      return $this ->postVar("user_twitter_url");
    } elseif (($this ->getVar("user_twitter_url")) || ($this ->getVar("user_twitter_url") === "")) {
      return $this ->getVar("user_twitter_url");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserTwitterUrl();
    } elseif (($this ->sessionVar("user_twitter_url")) || ($this ->sessionVar("user_twitter_url") == "")) {
      return $this ->sessionVar("user_twitter_url");
    } else {
      return false;
    }
  }
  
  function setUserTwitterUrl( $str ) {
    $this ->User -> setUserTwitterUrl( $str );
  }
  
  function getUserWebsiteUrl() {
    if (($this ->postVar("user_website_url")) || ($this ->postVar("user_website_url") === "")) {
      return $this ->postVar("user_website_url");
    } elseif (($this ->getVar("user_website_url")) || ($this ->getVar("user_website_url") === "")) {
      return $this ->getVar("user_website_url");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserWebsiteUrl();
    } elseif (($this ->sessionVar("user_website_url")) || ($this ->sessionVar("user_website_url") == "")) {
      return $this ->sessionVar("user_website_url");
    } else {
      return false;
    }
  }
  
  function setUserWebsiteUrl( $str ) {
    $this ->User -> setUserWebsiteUrl( $str );
  }
  
  function getUserStatus() {
    if (($this ->postVar("user_status")) || ($this ->postVar("user_status") === "")) {
      return $this ->postVar("user_status");
    } elseif (($this ->getVar("user_status")) || ($this ->getVar("user_status") === "")) {
      return $this ->getVar("user_status");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserStatus();
    } elseif (($this ->sessionVar("user_status")) || ($this ->sessionVar("user_status") == "")) {
      return $this ->sessionVar("user_status");
    } else {
      return false;
    }
  }
  
  function setUserStatus( $str ) {
    $this ->User -> setUserStatus( $str );
  }
  
  function getUserCreatedAt() {
    if (($this ->postVar("user_created_at")) || ($this ->postVar("user_created_at") === "")) {
      return $this ->postVar("user_created_at");
    } elseif (($this ->getVar("user_created_at")) || ($this ->getVar("user_created_at") === "")) {
      return $this ->getVar("user_created_at");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserCreatedAt();
    } elseif (($this ->sessionVar("user_created_at")) || ($this ->sessionVar("user_created_at") == "")) {
      return $this ->sessionVar("user_created_at");
    } else {
      return false;
    }
  }
  
  function setUserCreatedAt( $str ) {
    $this ->User -> setUserCreatedAt( $str );
  }
  
  function getUserUpdatedAt() {
    if (($this ->postVar("user_updated_at")) || ($this ->postVar("user_updated_at") === "")) {
      return $this ->postVar("user_updated_at");
    } elseif (($this ->getVar("user_updated_at")) || ($this ->getVar("user_updated_at") === "")) {
      return $this ->getVar("user_updated_at");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserUpdatedAt();
    } elseif (($this ->sessionVar("user_updated_at")) || ($this ->sessionVar("user_updated_at") == "")) {
      return $this ->sessionVar("user_updated_at");
    } else {
      return false;
    }
  }
  
  function setUserUpdatedAt( $str ) {
    $this ->User -> setUserUpdatedAt( $str );
  }
  
  function getUserType() {
    if (($this ->postVar("user_type")) || ($this ->postVar("user_type") === "")) {
      return $this ->postVar("user_type");
    } elseif (($this ->getVar("user_type")) || ($this ->getVar("user_type") === "")) {
      return $this ->getVar("user_type");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserType();
    } elseif (($this ->sessionVar("user_type")) || ($this ->sessionVar("user_type") == "")) {
      return $this ->sessionVar("user_type");
    } else {
      return false;
    }
  }
  
  function setUserType( $str ) {
    $this ->User -> setUserType( $str );
  }
  
  function getUserContactEmailId() {
    if (($this ->postVar("user_contact_email_id")) || ($this ->postVar("user_contact_email_id") === "")) {
      return $this ->postVar("user_contact_email_id");
    } elseif (($this ->getVar("user_contact_email_id")) || ($this ->getVar("user_contact_email_id") === "")) {
      return $this ->getVar("user_contact_email_id");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserContactEmailId();
    } elseif (($this ->sessionVar("user_contact_email_id")) || ($this ->sessionVar("user_contact_email_id") == "")) {
      return $this ->sessionVar("user_contact_email_id");
    } else {
      return false;
    }
  }
  
  function setUserContactEmailId( $str ) {
    $this ->User -> setUserContactEmailId( $str );
  }
  
  function getUserTUsername() {
    if (($this ->postVar("user_t_username")) || ($this ->postVar("user_t_username") === "")) {
      return $this ->postVar("user_t_username");
    } elseif (($this ->getVar("user_t_username")) || ($this ->getVar("user_t_username") === "")) {
      return $this ->getVar("user_t_username");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserTUsername();
    } elseif (($this ->sessionVar("user_t_username")) || ($this ->sessionVar("user_t_username") == "")) {
      return $this ->sessionVar("user_t_username");
    } else {
      return false;
    }
  }
  
  function setUserTUsername( $str ) {
    $this ->User -> setUserTUsername( $str );
  }
  
  function getUserPhotoUrl() {
    if (($this ->postVar("user_photo_url")) || ($this ->postVar("user_photo_url") === "")) {
      return $this ->postVar("user_photo_url");
    } elseif (($this ->getVar("user_photo_url")) || ($this ->getVar("user_photo_url") === "")) {
      return $this ->getVar("user_photo_url");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserPhotoUrl();
    } elseif (($this ->sessionVar("user_photo_url")) || ($this ->sessionVar("user_photo_url") == "")) {
      return $this ->sessionVar("user_photo_url");
    } else {
      return false;
    }
  }
  
  function setUserPhotoUrl( $str ) {
    $this ->User -> setUserPhotoUrl( $str );
  }
  
  function getUserImage() {
    if (($this ->postVar("user_image")) || ($this ->postVar("user_image") === "")) {
      return $this ->postVar("user_image");
    } elseif (($this ->getVar("user_image")) || ($this ->getVar("user_image") === "")) {
      return $this ->getVar("user_image");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserImage();
    } elseif (($this ->sessionVar("user_image")) || ($this ->sessionVar("user_image") == "")) {
      return $this ->sessionVar("user_image");
    } else {
      return false;
    }
  }
  
  function setUserImage( $str ) {
    $this ->User -> setUserImage( $str );
  }
  
  function getUserDefaultTimezone() {
    if (($this ->postVar("user_default_timezone")) || ($this ->postVar("user_default_timezone") === "")) {
      return $this ->postVar("user_default_timezone");
    } elseif (($this ->getVar("user_default_timezone")) || ($this ->getVar("user_default_timezone") === "")) {
      return $this ->getVar("user_default_timezone");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserDefaultTimezone();
    } elseif (($this ->sessionVar("user_default_timezone")) || ($this ->sessionVar("user_default_timezone") == "")) {
      return $this ->sessionVar("user_default_timezone");
    } else {
      return false;
    }
  }
  
  function setUserDefaultTimezone( $str ) {
    $this ->User -> setUserDefaultTimezone( $str );
  }
  
  function getUserBirthdayDate() {
    if (($this ->postVar("user_birthday_date")) || ($this ->postVar("user_birthday_date") === "")) {
      return $this ->postVar("user_birthday_date");
    } elseif (($this ->getVar("user_birthday_date")) || ($this ->getVar("user_birthday_date") === "")) {
      return $this ->getVar("user_birthday_date");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserBirthdayDate();
    } elseif (($this ->sessionVar("user_birthday_date")) || ($this ->sessionVar("user_birthday_date") == "")) {
      return $this ->sessionVar("user_birthday_date");
    } else {
      return false;
    }
  }
  
  function setUserBirthdayDate( $str ) {
    $this ->User -> setUserBirthdayDate( $str );
  }
  
  function getUserBroadcastStatus() {
    if (($this ->postVar("user_broadcast_status")) || ($this ->postVar("user_broadcast_status") === "")) {
      return $this ->postVar("user_broadcast_status");
    } elseif (($this ->getVar("user_broadcast_status")) || ($this ->getVar("user_broadcast_status") === "")) {
      return $this ->getVar("user_broadcast_status");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserBroadcastStatus();
    } elseif (($this ->sessionVar("user_broadcast_status")) || ($this ->sessionVar("user_broadcast_status") == "")) {
      return $this ->sessionVar("user_broadcast_status");
    } else {
      return false;
    }
  }
  
  function setUserBroadcastStatus( $str ) {
    $this ->User -> setUserBroadcastStatus( $str );
  }
  
  function getUserTOauthToken() {
    if (($this ->postVar("user_t_oauth_token")) || ($this ->postVar("user_t_oauth_token") === "")) {
      return $this ->postVar("user_t_oauth_token");
    } elseif (($this ->getVar("user_t_oauth_token")) || ($this ->getVar("user_t_oauth_token") === "")) {
      return $this ->getVar("user_t_oauth_token");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserTOauthToken();
    } elseif (($this ->sessionVar("user_t_oauth_token")) || ($this ->sessionVar("user_t_oauth_token") == "")) {
      return $this ->sessionVar("user_t_oauth_token");
    } else {
      return false;
    }
  }
  
  function setUserTOauthToken( $str ) {
    $this ->User -> setUserTOauthToken( $str );
  }
  
  function getUserTOauthTokenSecret() {
    if (($this ->postVar("user_t_oauth_token_secret")) || ($this ->postVar("user_t_oauth_token_secret") === "")) {
      return $this ->postVar("user_t_oauth_token_secret");
    } elseif (($this ->getVar("user_t_oauth_token_secret")) || ($this ->getVar("user_t_oauth_token_secret") === "")) {
      return $this ->getVar("user_t_oauth_token_secret");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserTOauthTokenSecret();
    } elseif (($this ->sessionVar("user_t_oauth_token_secret")) || ($this ->sessionVar("user_t_oauth_token_secret") == "")) {
      return $this ->sessionVar("user_t_oauth_token_secret");
    } else {
      return false;
    }
  }
  
  function setUserTOauthTokenSecret( $str ) {
    $this ->User -> setUserTOauthTokenSecret( $str );
  }
  
  function getUserUal() {
    if (($this ->postVar("user_ual")) || ($this ->postVar("user_ual") === "")) {
      return $this ->postVar("user_ual");
    } elseif (($this ->getVar("user_ual")) || ($this ->getVar("user_ual") === "")) {
      return $this ->getVar("user_ual");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserUal();
    } elseif (($this ->sessionVar("user_ual")) || ($this ->sessionVar("user_ual") == "")) {
      return $this ->sessionVar("user_ual");
    } else {
      return false;
    }
  }
  
  function setUserUal( $str ) {
    $this ->User -> setUserUal( $str );
  }
  
  function getUserOptin() {
    if (($this ->postVar("user_optin")) || ($this ->postVar("user_optin") === "")) {
      return $this ->postVar("user_optin");
    } elseif (($this ->getVar("user_optin")) || ($this ->getVar("user_optin") === "")) {
      return $this ->getVar("user_optin");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserOptin();
    } elseif (($this ->sessionVar("user_optin")) || ($this ->sessionVar("user_optin") == "")) {
      return $this ->sessionVar("user_optin");
    } else {
      return false;
    }
  }
  
  function setUserOptin( $str ) {
    $this ->User -> setUserOptin( $str );
  }
  
  function getUserOptinDate() {
    if (($this ->postVar("user_optin_date")) || ($this ->postVar("user_optin_date") === "")) {
      return $this ->postVar("user_optin_date");
    } elseif (($this ->getVar("user_optin_date")) || ($this ->getVar("user_optin_date") === "")) {
      return $this ->getVar("user_optin_date");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserOptinDate();
    } elseif (($this ->sessionVar("user_optin_date")) || ($this ->sessionVar("user_optin_date") == "")) {
      return $this ->sessionVar("user_optin_date");
    } else {
      return false;
    }
  }
  
  function setUserOptinDate( $str ) {
    $this ->User -> setUserOptinDate( $str );
  }
  
  function getUserTagline() {
    if (($this ->postVar("user_tagline")) || ($this ->postVar("user_tagline") === "")) {
      return $this ->postVar("user_tagline");
    } elseif (($this ->getVar("user_tagline")) || ($this ->getVar("user_tagline") === "")) {
      return $this ->getVar("user_tagline");
    } elseif (($this ->User) || ($this ->User === "")){
      return $this ->User -> getUserTagline();
    } elseif (($this ->sessionVar("user_tagline")) || ($this ->sessionVar("user_tagline") == "")) {
      return $this ->sessionVar("user_tagline");
    } else {
      return false;
    }
  }
  
  function setUserTagline( $str ) {
    $this ->User -> setUserTagline( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->User = UserPeer::retrieveByPK( $id );
    }
    
    if ($this ->User ) {
       
    	       (is_numeric(WTVRcleanString($this ->User->getUserId()))) ? $itemarray["user_id"] = WTVRcleanString($this ->User->getUserId()) : null;
          (WTVRcleanString($this ->User->getUserFbUid())) ? $itemarray["user_fb_uid"] = WTVRcleanString($this ->User->getUserFbUid()) : null;
          (WTVRcleanString($this ->User->getUserTUid())) ? $itemarray["user_t_uid"] = WTVRcleanString($this ->User->getUserTUid()) : null;
          (WTVRcleanString($this ->User->getUserFullName())) ? $itemarray["user_full_name"] = WTVRcleanString($this ->User->getUserFullName()) : null;
          (WTVRcleanString($this ->User->getUserFname())) ? $itemarray["user_fname"] = WTVRcleanString($this ->User->getUserFname()) : null;
          (WTVRcleanString($this ->User->getUserLname())) ? $itemarray["user_lname"] = WTVRcleanString($this ->User->getUserLname()) : null;
          (WTVRcleanString($this ->User->getUserUsername())) ? $itemarray["user_username"] = WTVRcleanString($this ->User->getUserUsername()) : null;
          (WTVRcleanString($this ->User->getUserEmail())) ? $itemarray["user_email"] = WTVRcleanString($this ->User->getUserEmail()) : null;
          (WTVRcleanString($this ->User->getUserEditableEmail())) ? $itemarray["user_editable_email"] = WTVRcleanString($this ->User->getUserEditableEmail()) : null;
          (WTVRcleanString($this ->User->getUserPassword())) ? $itemarray["user_password"] = WTVRcleanString($this ->User->getUserPassword()) : null;
          (WTVRcleanString($this ->User->getUserPasswordLegacy())) ? $itemarray["user_password_legacy"] = WTVRcleanString($this ->User->getUserPasswordLegacy()) : null;
          (WTVRcleanString($this ->User->getUserBio())) ? $itemarray["user_bio"] = WTVRcleanString($this ->User->getUserBio()) : null;
          (WTVRcleanString($this ->User->getUserFacebookUrl())) ? $itemarray["user_facebook_url"] = WTVRcleanString($this ->User->getUserFacebookUrl()) : null;
          (WTVRcleanString($this ->User->getUserTwitterUrl())) ? $itemarray["user_twitter_url"] = WTVRcleanString($this ->User->getUserTwitterUrl()) : null;
          (WTVRcleanString($this ->User->getUserWebsiteUrl())) ? $itemarray["user_website_url"] = WTVRcleanString($this ->User->getUserWebsiteUrl()) : null;
          (WTVRcleanString($this ->User->getUserStatus())) ? $itemarray["user_status"] = WTVRcleanString($this ->User->getUserStatus()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->User->getUserCreatedAt())) ? $itemarray["user_created_at"] = formatDate($this ->User->getUserCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->User->getUserUpdatedAt())) ? $itemarray["user_updated_at"] = formatDate($this ->User->getUserUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->User->getUserType())) ? $itemarray["user_type"] = WTVRcleanString($this ->User->getUserType()) : null;
          (is_numeric(WTVRcleanString($this ->User->getUserContactEmailId()))) ? $itemarray["user_contact_email_id"] = WTVRcleanString($this ->User->getUserContactEmailId()) : null;
          (WTVRcleanString($this ->User->getUserTUsername())) ? $itemarray["user_t_username"] = WTVRcleanString($this ->User->getUserTUsername()) : null;
          (WTVRcleanString($this ->User->getUserPhotoUrl())) ? $itemarray["user_photo_url"] = WTVRcleanString($this ->User->getUserPhotoUrl()) : null;
          (WTVRcleanString($this ->User->getUserImage())) ? $itemarray["user_image"] = WTVRcleanString($this ->User->getUserImage()) : null;
          (WTVRcleanString($this ->User->getUserDefaultTimezone())) ? $itemarray["user_default_timezone"] = WTVRcleanString($this ->User->getUserDefaultTimezone()) : null;
          (WTVRcleanString($this ->User->getUserBirthdayDate())) ? $itemarray["user_birthday_date"] = WTVRcleanString($this ->User->getUserBirthdayDate()) : null;
          (WTVRcleanString($this ->User->getUserBroadcastStatus())) ? $itemarray["user_broadcast_status"] = WTVRcleanString($this ->User->getUserBroadcastStatus()) : null;
          (WTVRcleanString($this ->User->getUserTOauthToken())) ? $itemarray["user_t_oauth_token"] = WTVRcleanString($this ->User->getUserTOauthToken()) : null;
          (WTVRcleanString($this ->User->getUserTOauthTokenSecret())) ? $itemarray["user_t_oauth_token_secret"] = WTVRcleanString($this ->User->getUserTOauthTokenSecret()) : null;
          (WTVRcleanString($this ->User->getUserUal())) ? $itemarray["user_ual"] = WTVRcleanString($this ->User->getUserUal()) : null;
          (WTVRcleanString($this ->User->getUserOptin())) ? $itemarray["user_optin"] = WTVRcleanString($this ->User->getUserOptin()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->User->getUserOptinDate())) ? $itemarray["user_optin_date"] = formatDate($this ->User->getUserOptinDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->User->getUserTagline())) ? $itemarray["user_tagline"] = WTVRcleanString($this ->User->getUserTagline()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->User = UserPeer::retrieveByPK( $id );
    } elseif (! $this ->User) {
      $this ->User = new User;
    }
        
  	 ($this -> getUserId())? $this ->User->setUserId( WTVRcleanString( $this -> getUserId()) ) : null;
    ($this -> getUserFbUid())? $this ->User->setUserFbUid( WTVRcleanString( $this -> getUserFbUid()) ) : null;
    ($this -> getUserTUid())? $this ->User->setUserTUid( WTVRcleanString( $this -> getUserTUid()) ) : null;
    ($this -> getUserFullName())? $this ->User->setUserFullName( WTVRcleanString( $this -> getUserFullName()) ) : null;
    ($this -> getUserFname())? $this ->User->setUserFname( WTVRcleanString( $this -> getUserFname()) ) : null;
    ($this -> getUserLname())? $this ->User->setUserLname( WTVRcleanString( $this -> getUserLname()) ) : null;
    ($this -> getUserUsername())? $this ->User->setUserUsername( WTVRcleanString( $this -> getUserUsername()) ) : null;
    ($this -> getUserEmail())? $this ->User->setUserEmail( WTVRcleanString( $this -> getUserEmail()) ) : null;
    ($this -> getUserEditableEmail())? $this ->User->setUserEditableEmail( WTVRcleanString( $this -> getUserEditableEmail()) ) : null;
    ($this -> getUserPassword())? $this ->User->setUserPassword( WTVRcleanString( $this -> getUserPassword()) ) : null;
    ($this -> getUserPasswordLegacy())? $this ->User->setUserPasswordLegacy( WTVRcleanString( $this -> getUserPasswordLegacy()) ) : null;
    ($this -> getUserBio())? $this ->User->setUserBio( WTVRcleanString( $this -> getUserBio()) ) : null;
    ($this -> getUserFacebookUrl())? $this ->User->setUserFacebookUrl( WTVRcleanString( $this -> getUserFacebookUrl()) ) : null;
    ($this -> getUserTwitterUrl())? $this ->User->setUserTwitterUrl( WTVRcleanString( $this -> getUserTwitterUrl()) ) : null;
    ($this -> getUserWebsiteUrl())? $this ->User->setUserWebsiteUrl( WTVRcleanString( $this -> getUserWebsiteUrl()) ) : null;
    ($this -> getUserStatus())? $this ->User->setUserStatus( WTVRcleanString( $this -> getUserStatus()) ) : null;
          if (is_valid_date( $this ->User->getUserCreatedAt())) {
        $this ->User->setUserCreatedAt( formatDate($this -> getUserCreatedAt(), "TS" ));
      } else {
      $Useruser_created_at = $this -> sfDateTime( "user_created_at" );
      ( $Useruser_created_at != "01/01/1900 00:00:00" )? $this ->User->setUserCreatedAt( formatDate($Useruser_created_at, "TS" )) : $this ->User->setUserCreatedAt( null );
      }
          if (is_valid_date( $this ->User->getUserUpdatedAt())) {
        $this ->User->setUserUpdatedAt( formatDate($this -> getUserUpdatedAt(), "TS" ));
      } else {
      $Useruser_updated_at = $this -> sfDateTime( "user_updated_at" );
      ( $Useruser_updated_at != "01/01/1900 00:00:00" )? $this ->User->setUserUpdatedAt( formatDate($Useruser_updated_at, "TS" )) : $this ->User->setUserUpdatedAt( null );
      }
    ($this -> getUserType())? $this ->User->setUserType( WTVRcleanString( $this -> getUserType()) ) : null;
    ($this -> getUserContactEmailId())? $this ->User->setUserContactEmailId( WTVRcleanString( $this -> getUserContactEmailId()) ) : null;
    ($this -> getUserTUsername())? $this ->User->setUserTUsername( WTVRcleanString( $this -> getUserTUsername()) ) : null;
    ($this -> getUserPhotoUrl())? $this ->User->setUserPhotoUrl( WTVRcleanString( $this -> getUserPhotoUrl()) ) : null;
    ($this -> getUserImage())? $this ->User->setUserImage( WTVRcleanString( $this -> getUserImage()) ) : null;
    ($this -> getUserDefaultTimezone())? $this ->User->setUserDefaultTimezone( WTVRcleanString( $this -> getUserDefaultTimezone()) ) : null;
    ($this -> getUserBirthdayDate())? $this ->User->setUserBirthdayDate( WTVRcleanString( $this -> getUserBirthdayDate()) ) : null;
    ($this -> getUserBroadcastStatus())? $this ->User->setUserBroadcastStatus( WTVRcleanString( $this -> getUserBroadcastStatus()) ) : null;
    ($this -> getUserTOauthToken())? $this ->User->setUserTOauthToken( WTVRcleanString( $this -> getUserTOauthToken()) ) : null;
    ($this -> getUserTOauthTokenSecret())? $this ->User->setUserTOauthTokenSecret( WTVRcleanString( $this -> getUserTOauthTokenSecret()) ) : null;
    ($this -> getUserUal())? $this ->User->setUserUal( WTVRcleanString( $this -> getUserUal()) ) : null;
    ($this -> getUserOptin())? $this ->User->setUserOptin( WTVRcleanString( $this -> getUserOptin()) ) : null;
          if (is_valid_date( $this ->User->getUserOptinDate())) {
        $this ->User->setUserOptinDate( formatDate($this -> getUserOptinDate(), "TS" ));
      } else {
      $Useruser_optin_date = $this -> sfDateTime( "user_optin_date" );
      ( $Useruser_optin_date != "01/01/1900 00:00:00" )? $this ->User->setUserOptinDate( formatDate($Useruser_optin_date, "TS" )) : $this ->User->setUserOptinDate( null );
      }
    ($this -> getUserTagline())? $this ->User->setUserTagline( WTVRcleanString( $this -> getUserTagline()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->User ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->User = UserPeer::retrieveByPK($id);
    }
    
    if (! $this ->User ) {
      return;
    }
    
    $this ->User -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('User_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "UserPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $User = UserPeer::doSelect($c);
    
    if (count($User) >= 1) {
      $this ->User = $User[0];
      return true;
    } else {
      $this ->User = new User();
      return false;
    }
  }
  
    //Pass an array of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function checkUnique( $vals ) {
    $c = new Criteria();
    
    foreach ($vals as $key =>$value) {
      $name = "UserPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $User = UserPeer::doSelect($c);
    
    if (count($User) >= 1) {
      $this ->User = $User[0];
      return true;
    } else {
      $this ->User = new User();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>