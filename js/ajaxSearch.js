// $(document).ready(function(){
//
//   $('#search').submit(function(event){
//     event.preventDefault();
//     data = $(this).serializeArray();
//     $.ajax({
//     			url:  'ajaxCalls/search.php',
//     			type: 'POST',
//     			data: data,
//     			success: function(data){
//     				if (data) {
//     					$('.signup-message').html(data);
//     				}
//     			},
//     			error: function() {
//     				$('.signup-message').html("<p class='alert alert-danger'>there was an error please try again later</p>");
//     			}
//     		});
//   });
// });
