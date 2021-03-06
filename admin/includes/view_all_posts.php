<?php

include("delete_modal.php");

    if(isset($_POST['checkBoxArray'])){
        
        foreach($_POST['checkBoxArray'] as $checkBoxValue){
            
          $bulk_options = escape($_POST['bulk_options']);
            
            switch($bulk_options){
                    
                case 'published':
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$checkBoxValue} ";
                    $update_published_status = escape(mysqli_query($connection, $query));
                    confirmQuery($update_published_status);
                    break;
                 case 'draft':
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$checkBoxValue} ";
                    $update_published_status = escape(mysqli_query($connection, $query));
                    confirmQuery($update_published_status);
                    break;
                case 'delete':
                    $query = "DELETE from posts WHERE post_id = {$checkBoxValue} ";
                    $delete_bulk_posts = escape(mysqli_query($connection, $query));
                    confirmQuery($delete_bulk_posts);
                    break;
                case 'clone':
                     $query = "SElECT * from posts WHERE post_id = {$checkBoxValue} ";
                   
                $select_posts_query = mysqli_query($connection, $query);
                                
                while($row = mysqli_fetch_assoc($select_posts_query)){
                            
                        $post_id = escape($row['post_id']);
                        $post_author = escape($row['post_user']);
                        $post_title = escape($row['post_title']);
                        $post_category_id = escape($row['post_category_id']);
                        $post_status = escape($row['post_status']);
                        $post_image = escape($row['post_image']);
                        $post_tags = escape($row['post_tags']);
                        $post_content = escape($row['post_content']);
                        $post_comment_count = escape($row['post_comment_count']);
                        $post_date = escape($row['post_date']);
                        $post_views_count = escape($row['post_views_count']);
                            
                    }
                    
                    $query = "INSERT INTO posts(post_category_id, post_title, post_user, post_date, post_image, post_content, post_tags,  post_status, post_views_count) ";
        
                    $query .= "VALUES({$post_category_id},'{$post_title}','{$post_author}', now(),'{$post_image}','{$post_content}','{$post_tags}','{$post_status}', {$post_views_count}) ";

                    $clone_post_query = mysqli_query($connection, $query);

                    confirmQuery($clone_post_query);
                    
                    break;
            }
        }
    }
?>
           

<form action="" method="post">
    <table class="table table-bordered table-hover">
         <div class="form-group">
              <div id="bulkOptionsContainer" class="col-xs-4">
               <select name="bulk_options" id="" class="form-control">
                   <option value="">Select Option Value</option>
                   <option value="published">Publish</option>
                   <option value="draft">Draft</option>
                   <option value="delete">Delete</option>
                   <option value="clone">Clone</option>
               </select>
              </div>
              <div class="col-xs-4">
                  <input type="submit" name="submit" class="btn btn-success" value="Apply" />
                  <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
              </div>   
         </div>

            <thead>
                <tr>
                    <th><input id="selectAllBoxes" type="checkbox"  /></th>
                    <th>Id</th>
                    <th>Users</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Tags</th>
                    <th>Comments</th>
                    <th>Date</th>
                    <th>Views</th>
                    <th>View</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
               <?php

                    $query  = " SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, ";
                    $query .= " posts.post_category_id, posts.post_status, posts.post_image, posts.post_tags, ";
                    $query .= " posts.post_comment_count, posts.post_date, posts.post_views_count, ";
                    $query .= " categories.cat_title, categories.cat_id FROM posts ";
                    $query .= " LEFT JOIN categories ON posts.post_category_id = categories.cat_id ORDER BY posts.post_id DESC";


                    $select_posts = mysqli_query($connection, $query);

                        while($row = mysqli_fetch_assoc($select_posts)){
                            $post_id = $row['post_id'];
                            $post_author = $row['post_author'];
                            $post_user = $row['post_user'];
                            $post_title = $row['post_title'];
                            $post_category_id = $row['post_category_id'];
                            $post_status = $row['post_status'];
                            $post_image = $row['post_image'];
                            $post_tags = $row['post_tags'];
                            $post_comment_count = $row['post_comment_count'];
                            $post_date = $row['post_date'];
                            $post_views_count = $row['post_views_count'];
                            $cat_title = $row['cat_title'];
                            $cat_id = $row['cat_id'];

                            echo "<tr>";
                            echo "<th><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='{$post_id}'/></th>";
                            echo "<td>{$post_id}</td>";

                        if(!empty($post_author)){

                            echo "<td>{$post_author}</td>";

                        }elseif(!empty($post_user)) {

                             echo "<td>{$post_user}</td>";
                        }
                        echo "<td>{$post_title}</td>";
                        echo "<td>{$cat_title}</td>";
                        echo "<td>{$post_status}</td>";
                        echo "<td><img width='100' src='../images/{$post_image}' alt='post image'></td>";
                        echo "<td>{$post_tags}</td>";


                        $query = "SELECT * FROM comments WHERE comment_post_id = $post_id ";   
                        $send_comment_query = mysqli_query($connection, $query);

                        $row = mysqli_fetch_array($send_comment_query);
                        $comment_id = $row['comment_id'];
                        $count_comments = mysqli_num_rows($send_comment_query);

                        echo "<td><a href='post_comments.php?id=$post_id'>{$count_comments}</a></td>";


                        echo "<td>{$post_date}</td>";
                        echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to reset the views ?'); \" href='posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
                        echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a></td>";
                        echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
                       // echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete?'); \" href='posts.php?delete={$post_id}'>Delete</a></td>";
                            
                        //submit delete request via POST for more security
                            
                        ?>
                           
                            <form method="post">
                                <input type="hidden" name="post_id" value="<?php echo $post_id ;?>">
                                
                                <?php
                                echo '<td><input class="btn btn-danger" type="submit" name="delete" value="Delete"></td>';
                                ?>
                            </form>
                            
                         <?php
                            
                        //echo "<td><a rel='$post_id' href='javascript:void(0)' class='delete_link'>Delete</a></td>";  
                            
                        echo "</tr>";
                    }
                ?>
            </tbody>
    </table>
</form>
                        
<?php
    
    if(isset($_POST['delete'])){
        
        $the_post_id = escape($_POST['post_id']);
        $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
        $delete_query = mysqli_query($connection, $query);
        
        confirmQuery($delete_query);
        
         // refresh page
        header("Location: posts.php");

    }

 if(isset($_GET['reset'])){
        
        $the_post_id = escape($_GET['reset']);
        $query = "UPDATE posts SET post_views_count = 0 WHERE post_id = ". mysqli_real_escape_string($connection, $_GET['reset']) . " ";
        $update_views_query = mysqli_query($connection, $query);
        
        confirmQuery($update_views_query);
        
         // refresh page
        header("Location: posts.php");
    }
?>

<script>
    $(document).ready(function(){
        
        $(".delete_link").on('click', function(){
            
            var id = $(this).attr("rel");
            
            var delete_url = "posts.php?delete="+ id +" ";
            
            $(".modal_delete_link").attr("href", delete_url);
            
            $("#myModal").modal('show');
            
        });
    });
    
</script>