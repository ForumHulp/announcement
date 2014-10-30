	tinymce.init({
		selector: "textarea.mceEditor",
		relative_urls: false,
		forced_root_block : "", 
		force_br_newlines : true,
		force_p_newlines : false,
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen","pagebreak",
			"insertdatetime media table contextmenu paste textcolor"
		],
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | fore-color | link image | forecolor | backcolor",   
	});
