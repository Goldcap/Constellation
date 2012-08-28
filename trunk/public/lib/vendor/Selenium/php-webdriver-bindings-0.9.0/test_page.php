<html>
    <head>
        <title>Test page</title>
    </head>
<body>


<?php

    if(isset($_FILES['file1'])){
        
        echo 'UPLOADED FILE: ';
        $file = dirname(__FILE__).'\\'.$_FILES['file1']['name'];
        
        if(move_uploaded_file($_FILES['file1']['tmp_name'], $file )){
            echo $_FILES['file1']['name'];
            @unlink( $file );
        }
        else echo 'ERROR';
    }
?>

<?php 
if (isset($_POST["product_name"])) {
   $prod = $_POST["product_name"];
?><div id="result1" style="background:green"><?php echo $prod; ?><br/><br/></div> 
<?php
} else {
?>

<form name="form1" method="post" enctype="multipart/form-data">

product name: <input type="text" name="product_name" id="prod_name" size="40" value="<?php if (isset($prod)) echo $prod;?>"/>
<select name="sel1">
  <option id="1" value="1">option 1</option>
  <option id="2" value="2">option 2</option>
  <option id="3" value="3">option 3</option>
  <option id="4" value="4">option 4</option>
</select>
<br/>
<input type="file" id="file1" name="file1"/>
<br/>
<input type="checkbox" name="chbox1"/>checkbox<br/>
<br/>
<input type="submit" value="Confirm"/>
</form>
<?php } ?>

<br/><div name="div1">lorem ipsum</div>

<a href="javascript:sayHelloAlert('computer')">say hello (javascript)</a>

<script type="text/javascript">
function sayHello(name) {
  return "hello "+name+" !!!";
}

function sayHelloAlert(name) {
  alert(sayHello(name));
}

</script>

</body>
</html>