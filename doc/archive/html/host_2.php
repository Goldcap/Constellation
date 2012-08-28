<?php require 'includes/head.php'; ?>

<link href="/css/styles.css" rel="stylesheet" type="text/css" />
    
    <?php require 'partials/header.php' ?>
    
    <div id="content" class="host_show content">
        <div class="inner_container clearfix">
    
            <div class="host_show_header">
                <h3>Host a showtime of <span>Hesher</span></h3>
                <ul class="host_show_steps">
                    <li>1. Date &amp; Time</li>
                    <li class="host_show_steps_sep">&raquo;</li>
                    <li class="active">2. User Information</li>
                </ul>
            </div>
            
            <div class="host_container">
                <div class="host_container_top"></div>
                <div class="host_container_center clearfix">
                    <div class="film_details">
                        <img src="http://constellation.tv/uploads/screeningResources/32/logo/small_poster6f6ae03bad1537c3b1ac732dbd3ecc27.jpg" width="150" height="220"/>
                        <ul class="hosted_by">
                            <li class="film_title">Rashomon</li>
                            <li class="film_Time">3:00 PM EDT <br/> Friday Sep 30th</li>
                            <li class="film_host">Hosted by You</li>
                        </ul>                             
                    </div>
                    <div class="host_form">
                    <form>
                        <div class="form_row">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name"/>
                        </div>
                        <div class="form_row">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name"/>
                        </div>
                        <div class="form_row">
                            <label for="email">E-mail Address</label>
                            <input type="text" name="email" id="email"/>
                        </div>
                        <div class="form_row">
                            <label for="email_confirm">Confirm E-mail Address</label>
                            <input type="text" name="email_confirm" id="email_confirm"/>
                        </div>
                        <div class="form_row">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city"/>
                        </div>
                        <div class="form_row">
                            <label for="state">State</label>
                            <div class="selectOveride">
                                <div class="displayDiv">Select your timezone</div>
                            <?php include 'partials/state.php' ?>
                            </div>
                        </div>
                        <div class="form_row">
                            <label for="zip">Zip Code</label>
                            <input type="text" name="zip" id="zip"/>
                        </div>
                        <div class="form_row">
                            <label for="zip">Country</label>
                            <div class="selectOveride">
                                <div class="displayDiv">Select your timezone</div>
                            <?php include 'partials/country.php' ?>
                            </div>
                        </div>
                        <div class="form_row">
                            <button type="submit" class="button_green button uppercase">Purchase</button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="host_container_bottom"></div>
            </div>
        </div>
    </div>
    

<script>

    function onSelectChange(event){
        var $select = $(event.target);
//        $select
        var text = $('option:selected', $select).html();
        $select.prev().html(text);
    }
$('select').each(function(select){
    var $select = $(this);
    $select.bind('change', onSelectChange);
    
});
</script>

    <?php include 'includes/footer.php' ?>    
    <?php include 'includes/footscripts.php' ?>    

</body> 
</html> 
