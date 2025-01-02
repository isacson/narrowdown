$(function() {

	//make the "user" and "password" inputs update upon hitting "return"
	$( "#user, #password, #usera, #passworda" ).bind('keyup',function(e) {  
		if (e.keyCode == '13')  {

			loginButton();
		}
	});  
});


function loginButton() {
	rememberme = 0;
	if ($("#rememberme").prop("checked") == true) {
		rememberme = 1;
	}
	$.ajax({
		type: 'POST',
		url: thisPath + 'login.php',
		data: {
			user: $("#usera").val(),
			password: $("#passworda").val(),
			logsubmit: "submit",
			loggin: "login",
			rememberme: rememberme
		},
		dataType: 'html',
		success: function(data, thisPath) {
			if (data == "success") {
				$.ajax({
					url: "memberpage.php",
					success: function(data) {
						$( "#container" ).html(data);
					}
				})
			}
			else {
				$( "#container" ).html(data);
			}
		}
	});	
}