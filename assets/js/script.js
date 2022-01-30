
$(document).ready(function() {
   $(".test-data").find("div:first").show();

   $(".pagination a").on('click', function() {
       let navActive = "nav-active";
       let pageId = $(this).data('id');
       // let prevPageId = (pageId - 1);
       // if (pageId > prevPageId) {
       //     $('.page-' + prevPageId).remove();
       // }

       if ($(this).attr('class') === navActive) {
           return false;
       }

       let pagination = ".pagination > a.nav-active";
       let link = $(this).attr("href");
       let prevActive = $(pagination).attr("href");

       $(pagination).removeClass(navActive);
       $(this).addClass(navActive);

       $(prevActive).fadeOut(100, function() {
           $(link).fadeIn(100);
       });

       return false;
   });

   $(".end-test").click(function() {
       let testId = $(this).data('test-id');
       let result = {'testId': testId};

       $(".question").each(function() {
           var id = $(this).data('id');
           result[id] = $('input[name=question-' + id + ']:checked').val();
       });

       $.ajax({
           url: 'index.php',
           type: 'POST',
           data: result,
           success: function(html) {
               $(".content").html(html);
           },
           error: function() {
               alert('Error');
           }
       });
   });
});
