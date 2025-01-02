thisPath = "";
sitename = $( "#sitename" ).html();

$(function() {

	if ($( "#intro" ).html() == " ") {
		$.ajax({
			url: thisPath + "memberpage.php",
			success: function(data) {
				$( "#container" ).html(data);		
			}
		})
	}
	else if ($( "#reset" ).html() == " ") {
		$.ajax({
			url: thisPath + "login.php?action=reset",
			success: function(data) {
				$( "#container" ).html(data);		
			}
		})
	}
	else if ($( "#active" ).html() == " ") {
		if ($("#g").html() == "active") {
			$.ajax({
				url: thisPath + "resetPassword.php?action=active",
				success: function(data) {
					$( "#container" ).html(data);	
				}
			})
		}
		else {
			$.ajax({
				url: thisPath + "login.php?action=active",
				success: function(data) {
					$( "#container" ).html(data);		
				}
			})
		}
	}
	else if ($( "#resetAccount" ).html() == " ") {
		$.ajax({
			url: thisPath + "login.php?action=resetAccount",
			success: function(data) {
				$( "#container" ).html(data);		
			}
		})
	}
	else if ($( "#key" ).length) {
		$.ajax({
			url: thisPath + "resetPassword.php?key=" + $( "#key" ).html(),
			success: function(data) {
				$( "#container" ).html(data);		
			}
		})
	}
	else if ($( "#x" ).length) {
		x = $( "#x" ).html();
		y = $( "#y" ).html();
		$.ajax({
			url: thisPath + "activate.php?x=" + x + "&y=" + y,
			success: function(data) {
				$( "#container" ).html(data);		
			}
		})
	}
	else {
		$.ajax({
			url: thisPath + "register.php",
			success: function(data) {
				$( "#container" ).html(data);		
			}
		})
	}
});


function login() {
	$.ajax({
		url: thisPath + "login.php",
		success: function(data) {
			$( "#container" ).html(data);		
		}
	})
}

function reset() {
	$.ajax({
		url: thisPath + "reset.php",
		success: function(data) {
			$( "#container" ).html(data);		
		}
	})
}

function reset() {
	$.ajax({
		type: 'POST',
		url: thisPath + 'reset.php',
		data: {
			email: $("#email").val(),
			submit: "submit",
			reset: "reset"
		},
		dataType: 'html',
		success: function(data, thisPath) {
			$( "#container" ).html(data);
		}	
	});
}

function changePass() {
	$.ajax({
		type: 'POST',
		url: thisPath + 'resetPassword.php?key=' + $( "#key" ).html(),
		data: {
			password: $("#password").val(),
			passwordConfirm: $("#passwordConfirm").val(),
			submit: "submit"
		},
		dataType: 'html',
		success: function(data, thisPath) {
			$( "#container" ).html(data);
		}	
	});
}

function openRegister() {

	$( "#registry_form" ).show();

	$( "#open_register" ).remove();

	$("<button/>", {
		id: "open_register",
		html: "Hide the registration form",
	})
	.appendTo("#orp")
	.click(function() {
		closeRegister();
	});
}

function closeRegister() {

	$( "#registry_form" ).hide();

	$( "#open_register" ).remove();

	$("<button/>", {
		id: "open_register",
		html: "Sign up or sign in now.",
	})
	.appendTo("#orp")
	.click(function() {
		openRegister();
	});
}

function register() {

	$.ajax({
		type: 'POST',
		url: thisPath + 'submitted.php',
		data: {
			user: $("#user").val(),
			email: $("#email").val(),
			password: $("#password").val(),
			passwordConfirm: $("#passwordConfirm").val(),
			submit: "submit"
		},
		dataType: 'html',
		success: function(data, thisPath) {
			if (data == "success") {
				$( "#container" ).html("<h2 class='bg-success'>Registration successful, please check your email to activate your account.</h2>");
			}
			else {
					$( "#container" ).html(data);
			}
		}
	});
}

function addGroup(user_key, entity_key) {
	$.ajax({
		url: thisPath + "addgroup.php",
		type: 'POST',
		data: {
			user_key: user_key,
			entity_key: entity_key
		},
		success: function(data) {
			$( "#container" ).html(data);		
		}
	})
}

function memberPage() {
	$.ajax({
		url: thisPath + "memberpage.php",
		success: function(data) {
			$( "#container" ).html(data);		
		}
	})
}

function adminError() {
	$.ajax({
		url: thisPath + "adminerror.php",
		success: function(data) {
			$( "#container" ).html(data);		
		}
	})
}

function tutorial() {
	$.ajax({
		url: thisPath + "tutorial.php",
		success: function(data) {
			$( "#container" ).html(data);		
		}
	})
}