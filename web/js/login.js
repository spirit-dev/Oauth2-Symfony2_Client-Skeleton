var access_token;

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
		.done(function(data, textStatus, xhr) {

			if (xhr.status == 200) {
				access_token = data.response_data.access_token;

				window.location.replace(data.response_data.redirect_value);
			}
			else if (xhr.status == 206) {
				console.log(data);
			}
			
		})
		.fail(function(data) {
			console.log(data);
		});

	});

	$('#check_token_remote').click(function() {

		$.ajax({
			url: 'http://cubbyholeclient.com/enttest',
		})
		.done(function(data) {
			access_token = data.response_data.access_token;
			console.log("Remote access token = "+access_token);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	});

	$('#check_token_local').click(function() {

		console.log("Local access token = "+access_token);
	});

});