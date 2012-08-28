<div class="movie_block movie_block_history"> 
    <div class="movie_block_content"> 
        <div class="movie_block_poster">
            <span class="screening_container">
			<?php if ($purchase["film_logo"] != '') :?> 
					<img class="user_host" src="http://www.constellation.tv/uploads/screeningResources/<?php echo $purchase["film_id"];?>/logo/small_poster<?php echo $purchase["film_logo"];?>" width="126" /> 
			<?php else : ?>
					<img class="user_host" src="https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt/featured_still.jpg" width="126" /> 
			<?php endif; ?>
            </span>
        </div>
        <div class="movie_block_details"> 
            <h6 class="movie_block_title">
            <?php if($purchase['screening_name'] !=''):?>
            <?php echo $purchase['screening_name'];?>
            <?php elseif($purchase['screening_user_full_name']!= ''):?>
            Live with <?php echo $purchase["screening_user_full_name"];?> - <?php echo $purchase["film_name"];?>
            <?php else:?>
            <?php echo $purchase["film_name"];?>
            <?php endif;?>
			</h6>
            <p><span class="icon-calendar"></span><?php echo date("D, M dS \@ g:i A T",strtotime($purchase["screening_date"]));?><p>                        
            <div class="showtime_history_details">
                <div class="price">
                    <span>Price</span>
                    <p><?php echo $purchase["payment_amount"];?></p>
                </div>
                <div class="card">
                    <?php if (($purchase["payment_order_processor"] == "paypal_wpp") || ($purchase["payment_order_processor"] == "Paypal Web Payments Pro")) {?>
										<span>Card</span>
                    <p>*********<?php echo $purchase["payment_last_four"];?></p>
                    <?php } else if ($purchase["payment_order_processor"] == "Paypal") {?>
                    <span>Transaction</span>
                    <p>*********<?php echo $purchase["transaction_number"];?></p>
                    <?php } else if (($purchase["payment_order_processor"] == "sponsor_code") && ($purchase["sponsor_code"] != '')) {?>
                    <span>Code</span>
                    <p><?php echo $purchase["sponsor_code"];?></p>
                    <?php } elseif ($purchase["payment_order_processor"] == "Admin") { ?>
                    <span>Source</span>
                    <p>Admin</p>
                    <?php }?>
                </div>
                <div class="date">
                    <span>Payment Date</span>
                    <p><?php echo formatDate($purchase["payment_date"],"TS");?></p>
                </div>
                
            </div>
            <div class="showtime_history_details">
                <div class="code">
                    <span>Screening Code</span>
                    <p><?php echo $purchase["screening_unique_key"];?></p>
                </div>
								<?php if ($purchase["audience_invite_code"] != '') {?>
								<div class="code">
                    <span>Ticket Code</span>
                    <p><?php echo $purchase["audience_invite_code"];?></p>
                </div>
                <?php } ?>
								<?php if ($purchase["audience_invite_status"] == 0) {?>
								<div class="code">
                    <span>Exchange</span>
                    <p style="color: green">Available</p>
                </div>
                <?php } ?>
            </div>
          	
        </div>
    </div>
</div> 
