<?php

############################################
#
# INDEX.PHP
#
############################################

// Function for the dashboard admin record counts
function recordCount($table){
    
    global $connection;
    
    $query = "SELECT * FROM ". $table;
    $select_all_post = mysqli_query($connection, $query);
    
    $result = mysqli_num_rows($select_all_post);
    
    confirmQuery($result);
                 
    return $result;
    
}

function checkStatus($table, $column, $status){
    
     global $connection;
    
     $query ="SELECT * FROM $table WHERE $column = '{$status}'";
    
     $result = mysqli_query($connection, $query);
    
     return  mysqli_num_rows($result);
    
}


############################################
#
# GENERAL HELPER FUNCTIONS
#
#############################################


function imagePlaceholder($image = ''){
    
    if(!$image){
        
        return 'placeholder.jpg';
        
    }else{
        
        return $image;
        
    }
    
}

// escaping queires for security
function escape($string){
    
   global $connection;
    
   return mysqli_real_escape_string($connection, trim($string));
}

// query failed message
function confirmQuery($result){
    
    global $connection;
    
    if(!$result){
            die("QUERY FAILED ." . mysqli_error($connection));
    }
   
}

function redirect($location){
    
    return header("Location: " . $location);
    exit;
    
}

function ifItIsMethod($method=null){
    
    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        
        return true;
        
    }
    return false;
}

function isLoggedIn(){
    
    if(isset($_SESSION['user_role'])){
        
        return true;
        
    }
    return false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation=null){
    
    if(isLoggedIn()){
        
        redirect($redirectLocation);
    }
    
}

############################################
#
# CATEGORIES.PHP
#
#############################################


function insert_categories(){
    
      global $connection;
    
      if(isset($_POST['submit'])){
                                
            $cat_title = $_POST['cat_title'];

            if($cat_title == "" || empty($cat_title)){
                
                echo "This field should not be empty";
                
            }else{
                
                $stmt = mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES(?) ");

                mysqli_stmt_bind_param($stmt, 's', $cat_title);
                
                mysqli_stmt_execute($stmt);

                if(!$stmt){
                    
                    die('QUERY FAILED' . mysqli_error($connection));
                    
                }
            }
          
          mysqli_stmt_close($stmt);
        }
        ?>
        
        <form action="" method="post">
            <div class="form-group">
                <label for="cat-title">Add Category</label>
                <input type="text" class="form-control" name="cat_title">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="Add Category">
            </div>
        </form>

      <?php 
        if(isset($_GET['edit'])){
            $cat_id = $_GET['edit'];
            include "includes/update_categories.php";
        }
}



function findAllCategories(){
    
        global $connection;

        $query = "SELECT * FROM categories";

        $select_categories = mysqli_query($connection, $query);

        while($row = mysqli_fetch_assoc($select_categories )){

            $cat_title = $row['cat_title'];
            $cat_id = $row['cat_id'];
            
             echo "<tr>";
             echo "<td>{$cat_id}</td>";
             echo "<td>{$cat_title}</td>";
             echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
             echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
             echo "</tr>";
    }

}

function deleteCategories(){
    
      global $connection;

      if(isset($_GET['delete'])){

            $the_cat_id = $_GET['delete'];

            $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";

            $delete_query = mysqli_query($connection,$query);
            // refresh page
            header("Location: categories.php");
     }
}

############################################
#
# REGISTRATION SYSTEM IMPROVEMENTS
#
#############################################


function is_admin($username = ''){
    
    global $connection;
    
    $query = "SELECT user_role FROM users WHERE username = '$username' ";
    
    $result = mysqli_query($connection, $query);
    
    confirmQuery($result);
    
    $row = mysqli_fetch_array($result);
    
    if($row['user_role'] === 'admin'){
        
        return true;
        
    }else{
        
        return false;
    }
}
############################################
#
# REGISTRATION IMPROVEMENTS
#
#############################################

function username_exists($username){
    
    global $connection;
    
    $query = "SELECT username FROM users WHERE username = '$username'";
    
    $result = mysqli_query($connection, $query);
    
    if(!$result ){
            die('QUERY FAILEd' . mysqli_error($connection));
    }
  
    if(mysqli_num_rows($result) > 0){
        
        return true;
        
    }else{
        
        return false;
        
    }
}


function email_exists($email){
    
    global $connection;
    
    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    
    $result = mysqli_query($connection, $query);
    
    if(!$result ){
            die('QUERY FAILEd' . mysqli_error($connection));
    }
  
    if(mysqli_num_rows($result) > 0){
        
        return true;
        
    }else{
        
        return false;
        
    }
}


function register_user($username, $email, $password){

        global $connection;
        
                    
            $username = mysqli_real_escape_string($connection,$username);
            $email = mysqli_real_escape_string($connection,$email);
            $password = mysqli_real_escape_string($connection,$password);
            
            $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
            
            $query= "INSERT INTO users (username,user_email,user_password,user_role) ";
            
            $query .= "VALUES('{$username}','{$email}','{$password}','subscriber') ";
            
            $register_user_query = mysqli_query($connection, $query);
            
                if(!$register_user_query){
                    die("Query failed" . mysqli_error($connection));
                }
        
              
            
}


function login_user($username, $password){
    
        global $connection;
    
        
        $username = trim($username);
        $password = trim($password);
        
        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);
        
        $query = "SELECT * FROM users WHERE username = '{$username}' ";
        $select_user_query = mysqli_query($connection, $query);
        
        if(!$select_user_query){
            die("QUERY FAILED" . mysqli_error($connection));
        }
        
        while($row = mysqli_fetch_array($select_user_query)){
            
            $db_id = $row['user_id'];
            $db_username = $row['username'];
            $db_user_firstname = $row['user_firstname'];
            $db_user_lastname = $row['user_lastname'];
            $db_user_password = $row['user_password'];
            $db_user_role = $row['user_role'];
            
            
             //de-encripting pass to match the stored and encrypted on the database
            //$password = crypt($password, $db_user_password );
        
            if(password_verify($password, $db_user_password)){


                $_SESSION['username'] = $db_username;
                $_SESSION['firstname'] = $db_user_firstname;
                $_SESSION['lastname'] = $db_user_lastname;
                $_SESSION['user_role'] = $db_user_role;

                redirect("/cms/admin");

            }else{

                 return false;

            } 

        }
    return true;
              
}




############################################
#
# POST.PHP
#
#############################################




function insert_comment(){
    global $connection;
    
    
    if(isset($_POST['create_comment'])){
                    
        $the_post_id = $_GET['p_id'];
        $comment_author = $_POST['comment_author'];
        $comment_email = $_POST['comment_email'];
        $comment_content = $_POST['comment_content'];

        if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content) ){

        $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content,comment_status, comment_date)";

        $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}','Unapproved', now())";

        $create_comment_query = mysqli_query($connection, $query);

        if(!$create_comment_query ){
            die('QUERY FAILEd' . mysqli_error($connection));

        }

        //updating comment count dinamyc
        //$query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
       // $query .= "WHERE post_id = $the_post_id ";

       // $update_comment_count = mysqli_query($connection, $query);


        }
        else{
        echo "<script>alert('Fields cannot be empty')</script>";
        }
    }
    
}



############################################
#
# AJAX
#
#############################################

//admin_header / navigation.php

function users_online(){
    
    if(isset($_GET['onlineusers'])){

        session_start();

        include("../includes/db.php");

            $session = session_id();
            $time = time();
            $time_out_in_seconds = 60;
            $time_out = $time - $time_out_in_seconds;

            $query = "SELECT * FROM users_online WHERE session = '$session' ";
            $send_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($send_query);

            if($count == NULL){
                mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session','$time') ");
            }else{
                mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session' ");
            }

        $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'  ");

        echo $count_user = mysqli_num_rows($users_online_query);

     }
     
}

 users_online();


?>