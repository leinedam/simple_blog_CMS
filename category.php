
<?php include 'includes/db.php'; ?>

<?php include 'includes/header.php'; ?>
<!-- Navigation -->
<?php include 'includes/navigation.php'; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
               
                <h1 class="page-header">
                    Categories Search
                </h1>
                
                <?php
               
                
                if(isset($_GET['category'])){
                    
                    $post_category_id = $_GET['category'];
                    
                    if(is_admin($_SESSION['username'])){
                        
                           //$query = "SELECT * FROM posts WHERE post_category_id = $post_category_id ";
                          //prepared statement ==
                         
                        $stmt1 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ? ");
                        
                        
                        
                    }else{
                        
                          $stmt2 =  mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ? AND post_status = ? ");

                          $published = 'published';
                       
                    }

                            
                    // executing the prepared statements
                                                 
                    if(isset($stmt1)){
                        
                        mysqli_stmt_bind_param($stmt1, "i", $post_category_id);
                        
                        mysqli_stmt_execute($stmt1);
                        
                        //fetching
                        mysqli_stmt_bind_result($stmt1, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content );
                        
                        $stmt = $stmt1;
                        
 
                        
                    }else{
                        
                        mysqli_stmt_bind_param($stmt2,"is", $post_category_id, $published);
                        
                        mysqli_stmt_execute($stmt2);
                        
                        //fetching
                        mysqli_stmt_bind_result($stmt2, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content );
                        
                        $stmt = $stmt2;

                    }  
                    
                    
                    mysqli_stmt_store_result($stmt);
                                                 
                    //end of preparation                    
                    // add stmt mysqli_stmt...
                     if(mysqli_stmt_num_rows($stmt) === 0 ){
                        
                         echo "<h4 class='text-center text-danger'>NO POSTS FOUND ON THIS CATEGORY</h43>";
                        
                    }
                     
                    while(mysqli_stmt_fetch($stmt)):

                        
                ?>

                    <!-- First Blog Post -->
                    <h2>
                        <a href="post.php?p_id=<?php echo $post_id ;?>"><?php echo $post_title; ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="index.php"><?php echo $post_author; ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date; ?></p>
                    <hr>
                    <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                    <hr>
                    <p><?php echo $post_content; ?></p>
                    <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id ;?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <hr>

               <?php  endwhile;  mysqli_stmt_close($stmt); } else{
                    //closing a database connection for better performance
                    
                    header("Location: index.php");
                    
                }  ?>
                    

                    <!-- Pager
                <ul class="pager">
                    <li class="previous">
                        <a href="#">&larr; Older</a>
                    </li>
                    <li class="next">
                        <a href="#">Newer &rarr;</a>
                    </li>
                </ul> -->

            </div>

            <!-- Blog Sidebar Widgets Column -->
          
        <?php include 'includes/sidebar.php'; ?>
        
        </div>
        <!-- /.row -->
        <hr>

<?php include 'includes/footer.php'; ?>