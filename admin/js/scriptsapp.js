tinymce.init({ selector:'textarea' });

$(window).load(function(){
    
    var div_box = "<div id='load-screen'><div id='loading'></div></div>";
    
    $("body").prepend(div_box);
    
    $("#load-screen").fadeOut(500, function(){
        $(this).remove();
    });
      
    });

$(document).ready(function(){
    
   $('#selectAllBoxes').click(function(event){
       if(this.checked){
           $('.checkBoxes').each(function(){
               this.checked = true;
           });
          
       } else{
                $('.checkBoxes').each(function(){
               this.checked = false;
                });
           }
   });
       
});

