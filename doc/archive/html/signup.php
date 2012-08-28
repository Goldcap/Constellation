<?php require 'includes/head.php'; ?>
<link href="/css/styles.css" rel="stylesheet" type="text/css" />
    
    <?php require 'partials/header.php' ?>
    <div class="sub_head">
        <div class="inner_container clearfix">
            <h2 class="page_title">All Films</h2>
            <p class="sort_head">Sort By 
                <span id="toggleTime" class="button_toggle active"><span>Upcoming Showtimes</span></span>
                <span id="toggleAlpha" class="button_toggle"><span>A-Z</span></span></p>
        </div>
    </div>
    
    <div id="content" class="content">
        <div class="inner_container clearfix">
            <div class="film_collection_container loading">
                <ul id="filmContainer" class="film_collection clearfix"></ul>
            </div>
            <div class="button_container clearfix">
                <span id="moreFilms" class="button_large more"><span>More Films</span></span></div>
            </div>
        
        </div>
           
    </div>

    

    <?php include 'includes/footer.php' ?>    
    <?php include 'includes/footscripts.php' ?>    
    <script src="/js/all_films.js"></script>
    <script>
        AllFilms.init();
        modal.modalIn();
    </script>
    
    <?php $isFirstStep = true;?>
   <?php if($isFirstStep):?>
   <div class="pops" id="signup-modal">
        <h4>Sign up</h4>
        <p class="sublabel">Already have an account? <a href="">Log in here &raquo;</a></p>
        <p class="sublabel"><a href="/services/Facebook/login?dest=http://stage.constellation.tv/film/56" style="overflow: hidden;"><img alt="" src="https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt1/fb_connect.png"></a></p>
    
        <form class="form form_login">
            <p class="form_row">
                <label for="username">User Name</label>
                <input type="text" name="username" id="username" />
            </p>
            <p class="form_row">
                <label for="email">E-mail Address</label>
                <input type="text" name="email" id="email" />
            </p>
            <p class="form_row">
                <label for="password">Password</label>
                <input type="text" name="password" id="password"/>
            </p>
            <p class="clearfix">
                <a href="" class="forgot_pass left">Forgot your password?</a>
                <span class="button button_orange uppercase right">Sign up</span>
            </p>
        
        </form>
    </div>
    <?php else:?>
    <div class="pops" id="signup-modal">
        <h4>Welcome to Constellation!</h4>
        <p class="sublabel">Tell us a bit abnout yourself</p>
            
        <form class="form form_login">
            <div class="form_row clearfix">
                <label for="username">Profile Picture</label>
                <div id="signup-avatar-preview" class="left">
                    <img src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png" />
                </div>
                <div id="signup-avatar-input" class="left">
                    <input type="file" name="avatar" id="avatar-upload"/>
                    <span class="button button_small">Choose File</span>
                    <span id="avatar_name">No File selected</span>
                </div>
            </div>
            <div class="form_row clear">
                <label for="username">First &amp; Last</label>
                <input type="text" name="username" id="username" />
            </div>
            <div class="form_row">
                <label for="email">Timezone <span>(Required)</span></label>
                <div class="selectOveride">
                    <div class="displayDiv">Select your timezone</div>
                    <?php include 'partials/time-zone.php' ?>
                </div>
                
            </div>
            <div class="clearfix">
                <span class="button button_orange uppercase right">Check Out the Films</span>
            </div>
        
        </form>
    </div>
    <?php endif;?>
    
    
    <script>
    $(function() {
        
            function onSelectChange(event){
                var $select = $(event.target);
                var text = $('option:selected', $select).html();
                $select.prev().html(text);
            }

            
            $('.form_login select').each(function(select){
                var $select = $(this);
                $select.bind('change', onSelectChange);
            });
            
            $('#avatar-upload').bind('change', function(){
                $('#avatar_name').html($(this).val());
            })
            
        });
     </script>
    
    
</body> 
</html> 
