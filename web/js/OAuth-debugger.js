
$(document).ready(function() {

	$('#check_remote_token').click(function() {

		$.ajax({
			url: 'http://cubbyholeclient.com/check_remote_token',
		})
		.done(function(data) {
			console.log(data);
		})
		.fail(function() {
			console.log("error");
		});

	}).mouseover(function() {
		$(this).tooltip('show');
	}).mouseout(function() {
		$(this).tooltip('hide');
	});
	$('#check_local_token').click(function() {

		console.log("Local access token = "+session_access_token);
	}).mouseover(function() {
		$(this).tooltip('show');
	}).mouseout(function() {
		$(this).tooltip('hide');
	});


	$('#check_remote_user').click(function() {

		$.ajax({
			url: 'http://cubbyholeclient.com/check_remote_user',
		})
		.done(function(data) {
			console.log(data);
		})
		.fail(function() {
			console.log("error");
		});

	}).mouseover(function() {
		$(this).tooltip('show');
	}).mouseout(function() {
		$(this).tooltip('hide');
	});
	$('#check_local_user').click(function() {

		console.log("Local user informations = "+JSON.stringify(session_user));
	}).mouseover(function() {
		$(this).tooltip('show');
	}).mouseout(function() {
		$(this).tooltip('hide');
	});
	$('#delete_remote_user').click(function() {

		$.ajax({
			url: 'http://cubbyholeclient.com/delete_remote_user',
		})
		.done(function(data) {
			console.log(data);
		})
		.fail(function() {
			console.log("error");
		});
	}).mouseover(function() {
		$(this).tooltip('show');
	}).mouseout(function() {
		$(this).tooltip('hide');
	});

});