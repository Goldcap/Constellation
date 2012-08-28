<?php

function setPromotionValue( $cart, $code ) {
    $d = new WTVRData( null );
    
    foreach($cart["data"] as $item) {
      $sql = "select product_promotion_value 
            from product_promotion_code 
            inner join product_promotion
            on product_promotion_object = '".$item["fk_product_sku"]."'
            where product_promotion_used is null
        	  and ".$item["user_cart_quantity"]." >= product_promotion_limit 
            and product_promotion_code_value = '".mysql_escape_string($code)."' limit 1;";
     
     $res = $d -> propelQuery($sql);
     if($res) {
      while($res -> next()) {
        return $res -> get(1);
      }
     }
    }
    
    return 0;
}

function validatePromotionValue( $cart, $code, $user_id=0, $user_email="" ) {
    
    $d = new WTVRData( null );
    
    if ($user_id > 0) {
      $w=" and fk_user_id = ".$user_id." ";
    } elseif ($user_email != "") {
      $w=" and fk_user_email = '".mysql_escape_string($user_email)."' ";
    }
    
    foreach($cart["data"] as $item) {
      $sql = "select product_promotion_value 
              from product_promotion_code 
              inner join product_promotion
              on product_promotion_object = '".$item["fk_product_sku"]."'
              where product_promotion_used is null
          	  and ".$item["user_cart_quantity"]." >= product_promotion_limit "
              .$w."
              and product_promotion_code_value = '".mysql_escape_string($code)."' limit 1;";
    
      $res = $d -> propelQuery($sql);
      if($res) {
        while($res -> next()) {
          return $res -> get(1);
        }
      }
    }
    
    return 0;
}

function usePromotionValue( $code, $order_id, $user_id=0, $user_email="" ) {
    
    $d = new WTVRData( null );
    
    if ($user_id > 0) {
      $w=" where fk_user_id = ".$user_id." ";
    } elseif ($user_email != "") {
      $w=" where fk_user_email = '".mysql_escape_string($user_email)."' ";
    }
    $sql = "update product_promotion_code 
            set product_promotion_used = '".$order_id."'"
            .$w."
            and product_promotion_code_value = '".mysql_escape_string($code)."';";
    
    $d -> propelQuery($sql);
    
    return 0;
}

//Defaults to promotion for Tattoo Test Drive
function createPromotion( $user_id=0, $user_email="",$key="null",$promotion=15 ) {
    
    $code = new ProductPromotionCodeCrud( null );
    if ($user_id > 0) {
      $vars = array("fk_user_id"=>$user_id,"fk_product_promotion_id"=>$promotion,"product_promotion_unique_key"=>$key);
    } elseif ($user_email != "") {
      $vars = array("fk_user_email"=>$user_email,"fk_product_promotion_id"=>$promotion,"product_promotion_unique_key"=>$key);
    }
    $result = $code -> checkUnique($vars);
    if (! $result) {
      $code -> setProductPromotionCodeValue ( generateCouponCode() );
      $code -> setFkUserId( $user_id );
      $code -> setFkUserEmail( $user_email );
      $code -> setFkProductPromotionId( $promotion );
      $code -> setProductPromotionUniqueKey( $key );
      $code -> save();
    }
    return $code -> getProductPromotionCodeValue();
    
}

function generateCouponCode ( $min = 3, $max = 4 ) {
  
  
  $d = new WTVRData( null );
  $sql = "select dictionary_word from dictionary where length(dictionary_word) >= ".$min." and length(dictionary_word) <= ".$max." order by rand() limit 1;";
  // start with a blank password
  $temp = $d -> propelQuery( $sql );
  
  if ($temp) {
    while ($temp->next()) {
      $couponcode = $temp -> get(1);
    }
  }
  
  
  $couponcode = strtolower($couponcode) . sprintf("%02d",rand(0,99));
  // done!
  
  return $couponcode;

} 
?>
