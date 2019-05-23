<html>

<head>
    <title>Visiting Page</title>

    <head>

        <body>
        
            <?php
$email    = "";
$password = "";
$namenow  = "visitor";
$canpost  = "false";
//user login
if (isset($_POST['email'])&& isset($_POST['password'])) {
    $canpost  = "true";
    $email    = $_POST['email'];
    $password = $_POST['password'];
    if(!isset($_POST['return'])&&!isset($_POST['like'])&&!isset($_POST['tag'])){
        require 'database.php';
    $query_time="UPDATE `user` SET `Last_login` = CURRENT_TIME() WHERE `email`='$email'";
    if ($is_query_run_time = mysql_query($query_time)) {
        echo "log in time update success";
    } else {
        echo "5: Error in execution";
    }
    }
    // echo $email;
    // echo $password;
    //user submit like, like in database add one
    if(isset($_POST['like'])){
        //get the user email who like the post, and the post id
        $postid=$_POST['postid'];
        //echo $postid;
        //connect to database and insert value into like table
        require 'database.php';
        $query = "INSERT INTO LikeTable (`postID`, `like_email`, `like`) VALUES ('$postid','$email',1) ON DUPLICATE KEY UPDATE `like`=`like`";
        // mysql_query will execute the query to fetch data 
        if ($is_query_run = mysql_query($query)) {
            echo "like successfully updated";
        } else {
            echo "like: Error in execution";
        }
    }
    //when the submit 
    if(isset($_POST['tag'])){
        $taginput=$_POST['taginput'];
        $postid=$_POST['postid'];
        require 'database.php';
        $query = "INSERT INTO tag (`postID`, `tag_email`, `tag_content`) VALUES ('$postid','$email','$taginput') ON DUPLICATE KEY UPDATE `tag_content`=`tag_content`";
        // mysql_query will execute the query to fetch data 
        if ($is_query_run = mysql_query($query)) {
            echo "tag successfully updated";
        } else {
            echo "tag: Fail to insert tag";
        }
    }
}
//user submit post to share opintion about restaurant
if (isset($_GET['comment'])) {
    $canpost = "true";
    $comment    = $_GET['comment'];
    $store_name = $_GET['store_name'];
    $email      = $_GET['email'];
    $password   = $_GET['password'];
    //echo $comment;
    //echo $store_name;
    require 'database.php';
    // sql query to fetch all the data 
    $query3  = "SELECT * FROM  `store` WHERE store_name = '$store_name'";

    if ($is_query_run3 = mysql_query($query3)) {
        //if the restaurant already in database
        $query_executed3 = mysql_fetch_assoc($is_query_run3);
        $store_ID = $query_executed3['storeID'];

        $query = "INSERT INTO `post` (`content`, `email`, `storeID`) VALUES ('$comment', '$email', '$store_ID')";
        // mysql_query will execute the query to fetch data 
        if ($is_query_run = mysql_query($query)) {
            // echo "Query Executed"; 
            echo "Review successfully updated";
        } else {
            echo "2: Error in execution";
        }
    }else{
        echo "query wrong!";
    }

    $is_query_run    = mysql_query($query3);
    

    
}
//user submit post
if(isset($_POST['search'])){
    $input=$_POST['search'];
    if($canpost=='true'){
        $email=$_POST['email'];
        $password=$_POST['password'];
    }
}

?>

                <?php
require 'database.php';
// sql query to fetch all the data 
if ($canpost == "true") {

    $query = "SELECT * FROM `User` WHERE `email`= '$email'";
    // mysql_query will execute the query to fetch data 
    if ($is_query_run = mysql_query($query)) {
        // echo "Query Executed"; 
        // loop will iterate until all data is fetched 
        while ($query_executed = mysql_fetch_assoc($is_query_run)) {
            // these four line is for four column 
            if ($password == $query_executed['password']) {
                $namenow = $query_executed['email'];
                echo "<h3>Welcome to this page $namenow</h3>";

                //show th navigation to dashboard
                echo "<form ACTION=acct_dashbrd.php METHOD=POST>";
                echo "<button type='submit' value='acct' style='width: 150px; height: 40px; float:right'>Dashboard</button>";
                echo "<input name='email' type='hidden' value='$email'>";
                echo "<input name='password' type='hidden' value='$password'>";
                echo "</form>";
                echo "  ";
                
                show_searchform($email,$password);

                //show log out button
                echo "<form ACTION=start_page.html>";
                echo " <button type='submit' value='search' style='width: 150px; height: 40px; float:right'>Log Out</button>";
                echo "</form>";

               //show_review_form($email,$password);
               echo "<form ACTION=Review.php METHOD=POST>";
               echo "<button type='submit' value='Review' style='width: 150px; height: 40px; float:right'>Write a Review</button>";
               echo "<input name='email' type='hidden' value='$email'>";
               echo "<input name='password' type='hidden' value='$password'>";
               echo "</form>";
               echo "  ";

                //show the content of table based on the query
                showtable($canpost,$email,$password);
                echo "<br/>";

                 
                
                
            } else {
                echo "Wrong email address or password, please go back and enter again!" . '<br>';

            }
        }
    } else {
        echo "3: Error in execution";
    }
} else {
        show_visitorsearch();
        
		if($canpost=="true"){
			echo "<br/>";
			//show log out button
            echo "<form ACTION=start_page.html>";
            echo " <button type='submit' value='search' style='width: 150px; height: 40px; float:right'>Log Out</button>";
            echo "</form>";

            //show_review_form($email,$password);
            echo "<form ACTION=Review.php METHOD=POST>";
            echo "<button type='submit' value='Review' style='width: 150px; height: 40px; float:right'>Write a Review</button>";
            echo "<input name='email' type='hidden' value='$email'>";
            echo "<input name='password' type='hidden' value='$password'>";
            echo "</form>";
            echo "  ";
        }else{
            //Visitor logic goes to here
            //show log out button
            echo "<form ACTION=start_page.html>";
            echo " <button type='submit' value='search' style='width: 150px; height: 40px'>Sign In</button>";
            echo "</form>";
        }
        
		showtable($canpost,'','');
        
    
}
?>
                    <br>

                    <?php
function showtable($canpost,$email,$password)
{

    $query2 = "SELECT * FROM `Post` JOIN `store` USING (`storeID`) JOIN `User` USING (`email`) JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post` LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`) WHERE user.`email`<>'$email' ORDER BY `No_Like` DESC, `Time` DESC";
    if(isset($_POST['search'])){
    $input=$_POST['search'];
    $query2 = "SELECT * FROM `post` JOIN `user` USING(`email`) JOIN `store` USING(`storeID`) LEFT JOIN `tag` USING (`postID`) JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post` LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable 	GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`) WHERE  match(`store_name`) against ('$input')  OR match(`first_name`) against ('$input')  OR match(`last_name`) against ('$input') OR match(`content`) against ('$input') OR match(`tag_content`) against ('$input') ORDER BY `Time`, `No_Like` DESC";
    }


    if($canpost=='true'){
        echo "<table border='1'>
        <tr>
        <th width=30 height=30>Time</th>
        <th width=60 height=30>Post Content</th>
        <th width=60 height=30>Restaurant</th>
        <th width=40 height=30>User</th>
        <th width=40 height=30>    </th>
        <th width=40 height=30>Like</th>
        <th width=100 height=30>Tag</th>
        </tr>";
    }else{
        echo "<table border='1'>
        <tr>
        <th width=30 height=30>Time</th>
        <th width=60 height=30>Post Content</th>
        <th width=60 height=30>Restaurant</th>
        <th width=40 height=30>User</th>
        <th width=40 height=30>Like</th>
        <th width=100 height=30>Tag</th>
        </tr>";
    }
   
    if ($is_query_run2 = mysql_query($query2)) {
        while ($query_executed2 = mysql_fetch_assoc($is_query_run2)) {
            echo "<tr>";
            echo "<td  height=80>" . $query_executed2['Time'] . "</td>";
            echo "<td height=30>" . wordwrap($query_executed2['content'], 50, "<br />\n") . "</td>";
            echo "<td height=30>" . $query_executed2['store_name'] . "</td>";
            echo "<td height=30>" . $query_executed2['first_name'] . "</td>";
            if($canpost=='true'){
                echo "<td align=center height=30>".'<form action=user_process.php method=POST>
                <button type=submit value=like>like</button>
                <input name=like type=hidden value=1>
                <input name=email type=hidden value='.$email.'>
                <input name=password type=hidden value='.$password.'>
                <input name=postid type=hidden value='.$query_executed2['postID'].'>
                </form>'. "</td>";
            }
            echo "<td height=30>" . $query_executed2['No_Like'] . "</td>";
            echo "<td align=center height=30>";
            if($canpost=='true'){
                echo '<form action=user_process.php method=POST>
                <input name=taginput type=text value="Enter tag" style="width:90">
                <button type=submit value=like>submit</button>
                <input name=tag type=hidden value=1>
                <input name=email type=hidden value='.$email.'>
                <input name=password type=hidden value='.$password.'>
                <input name=postid type=hidden value='.$query_executed2['postID'].'>
                </form>';
            }
            $get_postid=$query_executed2['postID'];
            $query_tag="SELECT * FROM `tag` WHERE `postID`='$get_postid'";
            $is_query_run_tag = mysql_query($query_tag);
            while($query_executed_tag = mysql_fetch_assoc($is_query_run_tag)){
                echo "#";
                echo $query_executed_tag['tag_content'];
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Execution wrong";
    }
}

function show_searchform($email,$password){
    echo "<form ACTION=user_process.php METHOD=POST>";
    echo "<input name='search' type='text' value='Enter what you like to search' style='width:400px;height:40px' />";
    echo " <button type='submit' value='search'>Search</button>";
    echo " <input name='email' type='hidden' value='$email'>";
    echo "<input name='password' type='hidden' value='$password'>";
    echo "</form>";
}
function show_visitorsearch(){
    echo "<form ACTION=user_process.php METHOD=POST>";
    echo "<input name='search' type='text' value='Enter what you like to search' style='width:400px;height:40px' />";
    echo " <button type='submit' value='search'>Search</button>";
    echo "</form>";
}

?>
                        <br>

        </body>

</html>