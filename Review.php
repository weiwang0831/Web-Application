<html>
  <head>
    <title>Review Submission</title>
  <head>


  <body>
  <h4 style="text-align:left">Start Writting your Review</h4>

    <?php

$email = $_POST['email'];
$password  = $_POST['password'];


echo "<form ACTION=user_process.php METHOD=GET>";
echo "<input name='store_name' type='text' value='Enter restaurant name here' style='width:250px;height:40px'/> <br /> ";
echo "<input name='comment' type='text' value='Enter your comment here' style='width:250px;height:120px'/> <br />";
echo "<button type='submit' value='Submit' style='width:150px;height:20px'>Submit Review</button>";
echo "<input name='email' type='hidden' value='$email'>";
echo "<input name='password' type='hidden' value='$password'>";
echo "</form>";

echo "<form ACTION=user_process.php METHOD=POST>";
      echo "<button type='submit' value='search' style='width: 150px; height: 20px'>Cancel</button>";
      echo "<input name='email' type='hidden' value='$email'>";
      echo "<input name='password' type='hidden' value='$password'>";
      echo "<input name='return' type='hidden' value='1'>";
      echo "</form>";
?> 
      
  
  </body>
</html>