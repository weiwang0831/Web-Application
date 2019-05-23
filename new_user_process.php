<html>
  <head>
	<title>New User Visiting Page</title>
  <head>


  <body>
  <!--h4 style="text-align:center">You have successfully sign in! Now explore</h4>-->

	<?php 
		     $email  = $_POST['email'];
         $pass  = $_POST['password'];
         $first  = $_POST['firstname'];  
         $last  = $_POST['lastname'];
    ?>
    
    <?php
      require 'database.php';
      // sql query to fetch all the data 
      $query = "INSERT INTO `user` (`first_name`, `last_name`, `email`, `password`,`Last_login`) VALUES ('$first', '$last', '$email', '$pass',CURRENT_TIME())"; 
      // mysql_query will execute the query to fetch data 
      if ($is_query_run = mysql_query($query)) 
        { 
          // echo "Query Executed"; 
          echo "<h3>Hi $first $last You have successfully sign in! Now explore!</h3>";
          //go to personal page
          echo "<form ACTION=user_process.php METHOD=POST>";
          // echo "  <label for="name">SIGN IN</label><br />";
          echo "  <button type='submit' value='sign in'>Explore Home Page</button>";
          echo "  <input name='email' type='hidden' value='$email' /> <br />";
          echo "  <input name='password' type='hidden' value='$pass' /> <br />";
          echo " </form>";
        } 
      else
      { 
        echo "Error in execution"; 
      } 
      
    ?>

	  <br>

	  <?php
	  ?>	<br>
  
  </body>
</html>