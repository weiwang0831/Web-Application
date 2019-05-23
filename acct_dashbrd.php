<html>
  <head>
	<title>Personal Account Dashboard</title>
  <head>
  <link rel="stylesheet" type="text/css" href="start.css">


  <body>
  <h3 style="text-align:center">Welcome to Your Account Page</h3>
  

	<?php 
         $email  = $_POST['email'];
         $password = $_POST['password'];
         //$password  = $_POST['password'];
         if(isset($_POST['like'])){
            $fav_storeID=$_POST['storeID'];
            require 'database.php';
            $query = "INSERT INTO FavTable (`storeID`, `fav_email`, `fav`) VALUES ('$fav_storeID','$email',1) ON DUPLICATE KEY UPDATE `fav`=`fav`";
            if ($is_query_run = mysql_query($query)) {
                echo "Already add restaurant No.";
                echo $fav_storeID;
                echo " to your list";
            }
         }
         if(isset($_POST['disfav'])){
            $disfav_storeID=$_POST['storeID'];
            require 'database.php';
            $query = "DELETE FROM FavTable WHERE storeID='$disfav_storeID' AND fav_email='$email'";
            //echo $query;
            if ($is_query_run = mysql_query($query)) {
                echo "</br>";
                echo "Already delete restaurant No.";
                echo $disfav_storeID;
                echo " to your list";
            }
         }
          //show log out button
      echo "<form ACTION=user_process.php METHOD=POST>";
      echo "<button type='submit' value='search' style='width: 150px; height: 40px'>Home Page</button>";
      echo "<input name='email' type='hidden' value='$email'>";
      echo "<input name='password' type='hidden' value='$password'>";
      echo "<input name='return' type='hidden' value='1'>";
      echo "</form>";
        //  echo $email;
        //  echo $password;
    ?>
   
    
      <?php
      require 'database.php';

      //fetch data including 
      $query = "SELECT User.`email`, `Last_login`,count(DISTINCT post.`postID`) `posts`,count(DISTINCT FavTable.`storeID`) `favs`,COALESCE(COUNT(DISTINCT LikeTable.`postID`),0) `likes`  FROM USER LEFT JOIN post USING(`email`) LEFT JOIN FavTable ON user.`email` = FavTable.`fav_email` LEFT JOIN LikeTable ON user.`email` = LikeTable.`like_email` WHERE user.`email`='$email' GROUP BY post.`email`,`Last_login`"; 
      // mysql_query will execute the query to fetch data 
      if ($is_query_run = mysql_query($query)) 
        { 
          echo "<table border='1' >
            <tr>
            <th width=30>email</th>
            <th width=60>Last Login</th>
            <th width=80>Total Posts</th>
            <th width=80>Favourites</th>
            <th width=80>Total Likes</th>
            </tr>";
            echo "<h4 style=>Your Activity Statistics</h4>";
          while ($query_executed = mysql_fetch_assoc($is_query_run)) {
            echo "<tr>";
            echo "<td>" . $query_executed['email'] . "</td>";
            echo "<td>" . $query_executed['Last_login'] . "</td>";
            echo "<td>" . $query_executed['posts'] . "</td>";
            echo "<td>" . $query_executed['favs'] . "</td>";
            echo "<td>" . $query_executed['likes'] . "</td>";
          }
        } 
      else
      { 
        echo "Error in execution"; 
      } 

      showtable($email);

      ?>	
      <div >
    <div class="like_summary">
    <?php
      require 'database.php';

      //fetch data including 
      $query = "SELECT storeID,store_name, like_email, COALESCE(No_Like,0) `No_Like` FROM `store` LEFT JOIN (SELECT like_email, `storeID`, `like`,postID, SUM(`like`) `No_Like` FROM `post` JOIN `LikeTable` USING (`postID`) JOIN User ON User.email=LikeTable.like_email WHERE like_email='$email' GROUP BY `storeID`,`like_email`,`like`,`postID`) `A` USING (`storeID`) ORDER BY No_Like DESC "; 
      // mysql_query will execute the query to fetch data 
      if ($is_query_run = mysql_query($query)) 
        { 
          //echo $email;
          echo "<table border='1' >
            <tr>
            <th width=30>storeID</th>
            <th width=60>Restaurant</th>
            <th width=60>Like Level</th>
            <th width=80>Fav them</th>
            </tr>";
            echo "<h4>Like Summary for You</h4>";
          while ($query_executed = mysql_fetch_assoc($is_query_run)) {
            echo "<tr>";
            echo "<td>" . $query_executed['storeID'] . "</td>";
            echo "<td>" . $query_executed['store_name'] . "</td>";
            echo "<td>" . $query_executed['No_Like'] . "</td>";
            echo "<td align=center>".'<form action=acct_dashbrd.php method=POST>
                <button type=submit value=like >Favourite</button>
                <input name=like type=hidden value=1>
                <input name=email type=hidden value='.$email.'>
                <input name=password type=hidden value='.$password.'>
                <input name=storeID type=hidden value='.$query_executed['storeID'].'>
                </form>'. "</td>";
          }
        } 
      else
      { 
        echo "Error in execution"; 
      } 
    ?>
    </div>

    <div class="fav" >
	  <br>
      <?php
      require 'database.php';

      //fetch data including 
      $query = "SELECT * FROM FavTable JOIN store USING(`storeID`) WHERE fav_email='$email'"; 
      // mysql_query will execute the query to fetch data 
      if ($is_query_run = mysql_query($query)) 
        { 
          echo "<table border='1' >
            <tr>
            <th width=30>storeID</th>
            <th width=60>Restaurant</th>
            <th width=80>DisFav them</th>
            </tr>";
            echo "<h4>Your favourite restaurant</h4>";

          while ($query_executed = mysql_fetch_assoc($is_query_run)) {
            echo "<tr>";
            echo "<td>" . $query_executed['storeID'] . "</td>";
            echo "<td>" . $query_executed['store_name'] . "</td>";
            echo "<td align=center>".'<form action=acct_dashbrd.php method=POST>
                <button type=submit value=like >DisFavourite</button>
                <input name=disfav type=hidden value=1>
                <input name=email type=hidden value='.$email.'>
                <input name=password type=hidden value='.$password.'>
                <input name=storeID type=hidden value='.$query_executed['storeID'].'>
                </form>'. "</td>";
          }
        } 
      else
      { 
        echo "Error in execution"; 
      } 
      ?>
      </div>

      <div class="recommend" >
      <?php
      require 'database.php';

      //fetch data including 
      $query = "SELECT  DISTINCT `also_fav_store`, `store_name` FROM recommendation WHERE `also_fav_store`  NOT IN  (SELECT DISTINCT `storeID` FROM recommendation WHERE `Afav`='$email')"; 
      // mysql_query will execute the query to fetch data 
      if ($is_query_run = mysql_query($query)) 
        { 
          echo "<table border='1' >
            <tr>
            <th width=80>Restaurant</th>
            </tr>";
            echo "<h4 style=>Recommendation Restaurants</h4>";
          while ($query_executed = mysql_fetch_assoc($is_query_run)) {
            echo "<tr>";
            echo "<td>" . $query_executed['store_name'] . "</td>";
          }
        } 
      else
      { 
        echo "Error in execution"; 
      } 

      ?>
      </div>
  </div>


  <?php
function showtable($email)
{
    require 'database.php';

    $query2 = "SELECT * FROM `Post` JOIN `store` USING (`storeID`) JOIN `User` USING (`email`) JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post` LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`) WHERE `User`.`email`='$email' ORDER BY `No_Like` DESC, `Time` DESC";
    
        echo "<table border='1'>
        <tr>
        <th width=30>Time</th>
        <th width=60>Post Content</th>
        <th width=60>Restaurant</th>
        <th width=40>User</th>
        <th width=40>Like</th>
        </tr>";
        echo "<h4>Your Posts</h4>";
   
    if ($is_query_run2 = mysql_query($query2)) {
        while ($query_executed2 = mysql_fetch_assoc($is_query_run2)) {
            echo "<tr>";
            echo "<td>" . $query_executed2['Time'] . "</td>";
            echo "<td>" . wordwrap($query_executed2['content'], 50, "<br />\n") . "</td>";
            echo "<td>" . $query_executed2['store_name'] . "</td>";
            echo "<td>" . $query_executed2['first_name'] . "</td>";
            echo "<td>" . $query_executed2['No_Like'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No Post Yet";
    }
}
?>
  </body>
</html>