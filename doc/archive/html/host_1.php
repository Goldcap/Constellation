<?php require 'includes/head.php'; ?>

<link href="/css/styles.css" rel="stylesheet" type="text/css" />
<link href="/css/jquery-ui-1.8.16.host-show.css" rel="stylesheet" type="text/css" />
    
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
                    </div>
                    <div class="host_form clearfix">
                        <div class="form_row">
                            <label>Date</label>
                            <input type="text" id="datepicker" value="Select a date">
                        </div>
                        <div class="form_row">
                            <label for="last_name">Time</label>
                            <input type="text" id="timepicker" value="Select a time">
                        </div>
                        <div class="form_row">
                            <label for="zip">Time Zone</label>
                            <div class="selectOveride">
                                <div class="displayDiv">Select your timezone</div>
                                <?php include 'partials/time-zone.php' ?>
                            </div>
                        </div>
                        <div class="form_row">
                            <input type="checkbox" name="use_camera" /> <label class="checkbox_label">Host with a video camera?</label>
                        </div>
                        <div class="form_row">
                            <button type="submit" class="button button_orange uppercase">Next &raquo;</button>
                        </div>
                    </div>
                    
                </div>
                <div class="host_container_bottom"></div>
            </div>
        </div>
    </div>


    <?php include 'includes/footer.php' ?>    
    <?php include 'includes/footscripts.php' ?>    
    <script src="/js/jquery-ui-host-show.js" type="text/javascript"></script>
    <script src="/js/host_show.js" type="text/javascript"></script>
<script>
$(function() {
    
        function onSelectChange(event){
            var $select = $(event.target);
    //        $select
            var text = $('option:selected', $select).html();
            $select.prev().html(text);
        }
    
        $('#datepicker').datepicker({
        	numberOfMonths: 2,
            minDate: new Date()
        });
        $('#timepicker').timepicker({
            ampm: true
        });
        
        $('select').each(function(select){
            var $select = $(this);
            $select.bind('change', onSelectChange);
            
        });
    });
 </script>

</body> 
</html> 
