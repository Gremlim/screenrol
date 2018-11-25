$(document).ready(function () {
	$('#botonEntrar').click(function () {
		var fallo = false;
		if ($('#email').val() == "") {
			fallo = true;
		} else {
			var email = $('#email').val().split("@");
			if (!email[1]) {
				fallo = true;
			} else {
				var email2 = email[1].split(".");
				if (!email2[1]) {
					fallo = true;
				}
			}
		}

		if (fallo == true) {
			$('#formMail').addClass('has-error');
		} else {
			$('#formu').submit();
		}
	});
});