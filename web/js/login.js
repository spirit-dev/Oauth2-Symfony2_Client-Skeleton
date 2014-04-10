$(document).ready(function() {


	$('#form_login').submit(function(e) {
		e.preventDefault();

		$.ajax({
			url: "http://cubbyholeclient.com/auth_test",
			type: 'POST',
			data: {
				"username": $('#login_usr').val(),
				"password": $('#login_pw').val()
			}
		})
		.done(function(data) {
			console.log(data);
			
		})
		.fail(function(data) {
			console.log(data);
		});

	});

});