
function watched (file_name) {
     console.log("watched called");
     $.ajax({
         url:"http://watched.php",    //the page containing php script
	 type: "post",    //request type,
	 dataType: 'json',
	 data: {name: "file_name"},
         success:function(result){
	      console.log(result.abc);
	 }
/*	 error: function(err) {
	     alert('Error occured');
	     console.log(err);
	} */
    });
}
