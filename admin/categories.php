<?php include "includes/admin_header.php"; ?>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php"; ?>

        <div id="page-wrapper">
           
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           Categories
                        </h1>
                        
                        <div class="col-xs-6">
                           
                           <?php 
                                insert_categories();
                            ?>
                          
                        </div>
                        
                        <div class="col-xs-6">
                       
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Id Category Title</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                  <?php
                                    // FIND all categories query
                                
                                        findAllCategories();
                                  
                                  //delete categories
                                
                                        deleteCategories();
                                  
                                     ?>
                            </tbody>
                        </table>
                        </div>
                        
                        
                        
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
     
<?php include "includes/admin_footer.php"; ?>