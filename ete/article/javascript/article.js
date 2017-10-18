$(document).ready(function(){
     $(".button-collapse").sideNav();
     $("#submit").click(function(){
        var title = $("#title").val();
        var author = $("#author").val();
        var content = $("#content").val();
        
        var data = {
            title : title,
            author: author,
            content : content
        };
        
        $.post("../api/article.php",data,
        function(data,status){
            alert( "Data: " + data + "\nStatus: " + status);
        });
    });
})


