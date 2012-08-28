<script type="text/javascript">
$(document).ready(function() {
    var chat = new Chat('<?php echo $file; ?>');
  	chat.init();
  	var name = '<?php echo $name;?>';
  	chat.getUsers(<?php echo "'" . $room ."'";?>,name);
    	
  $("#sendie").keydown(function(event) {  
    var key = event.which;  
  // all keys including return 
    if (key >= 33) {  
      var maxLength = $(this).attr("maxlength");  
      var length = this.value.length;
      if (length >= maxLength) {  
        event.preventDefault();  
      }  
    }
  });
  
  $('#sendie').keyup(function(e) {	
    if (e.keyCode == 13) { 
      var text = $(this).val();
      var maxLength = $(this).attr("maxlength");  
      var length = text.length; 
      if (length <= maxLength + 1) {  
        chat.send(text, name);	
        $(this).val("");
      } else {
        $(this).val(text.substring(0, maxLength));
      }	
    }
  });
});
</script>

<div id="section">
    
    <h2><?php echo $name; ?></h2>
             
    <div id="chat-wrap">
        <div id="chat-area"></div>
    </div>
    
    <div id="userlist"></div>
        
        <form id="send-message-area" action="">
            <textarea id="sendie" maxlength='100'></textarea>
        </form>
    
</div>
        
<?php 
if (isset ($form) ) {echo $form;}
?>
