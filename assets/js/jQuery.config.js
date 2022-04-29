/**
 * Basic Error and Success Messages Using Toaster.js
 * @author Pradeep K Sharma - Future Profilez
 * @website https://www.futureprofilez.com
 * @copyright Future Profilez © 2021
 * @updated 12th October 2021
 * @version v1.0.1
*/

// Vars
let loader = $('div#loader'),
	loader_text = $('#loader-text');

function showLoader(msg = "Processing..."){
	loader_text.text(msg);
	loader.show();
}

function hideLoader(msg = 'Processing...'){
	loader_text.text(msg);
	loader.hide();
}

function success(content, title = "Success"){

	if(toastr !== undefined)
		toastr["success"](content, title);
	else alert(title +': \n'+content);

}

function error(content, title = "Error"){

	if(toastr !== undefined)
		toastr["error"](content, title);
	else alert(title +': \n'+content);

}

function errors(error_msgs, title = "Error"){

	var msgs = '';
	$.each(error_msgs ,function(key,value){
		msgs += key.toUpperCase() +': '+value + "<br>";
	});
	if(toastr !== undefined)
		toastr["error"](msgs, title);
	else alert(title +': \n'+msgs);

}

function httpError(error){

	var errorMsg = '';
	if(error.responseText!==undefined)
	{
		errorMsg = error.responseText;	
	}
	else if(error.responseText.message!==undefined){
		errorMsg = error.responseText.message;
	}

	if($.isFunction('toastr'))
		toastr["error"](errorMsg, 'Error in Request');
	else alert(errorMsg);
}

function setupAjax(setupContent){
	$.ajaxSetup(setupContent);
}

$(() => {

	if(Laravel !== undefined) {

		$.ajaxSetup({
			headers:{
		    	'X-CSRF-TOKEN': Laravel.csrfToken
		  	},
		  	processData: false,
        	contentType: false
		});

	}
	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": true,
	  "progressBar": true,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": true,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "20000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};

});


