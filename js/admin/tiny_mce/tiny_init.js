$(document).ready(function() {
	var options = {
		// Location of TinyMCE script
		script_url : '/js/admin/tiny_mce/tiny_mce.js',
		language : "ru",
        plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,filemanager,imagemanager,jbimages",
        theme : "advanced",
		skin : "o2k7",
        theme_advanced_buttons1 : "formatselect,fontselect,fontsizeselect,|,bold,italic,underline,forecolor,backcolor,link,unlink,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,|,pasteword,pastetext,table,|,code,fullscreen,|,insertfile,image,jbimages",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_align : "left",
		theme_advanced_toolbar_location : "top",

		paste_auto_cleanup_on_paste : false,
		paste_retain_style_properties : "color,font-weight,font-style,font-variant,border,background,background-color,border-width,border-style,border-color",
		paste_strip_class_attributes : "mso",
		paste_block_drop : true,
		paste_remove_spans : false,
	
		document_base_url : '/',
		force_br_newlines : false,
		force_p_newlines : false,
		relative_urls : false,
		element_format : "xhtml",
		content_css: "/css/admin/tiny_mce.css"
	}
	$('.tinymce').tinymce(options);	
	
	var optionsSimple = {
		language : "ru",
		mode : "specific_textareas",
		skin : "o2k7",
		editor_selector : "simple_editor",
		theme : "simple",
		document_base_url : '/',
		force_br_newlines : false,
		force_p_newlines : false,
		relative_urls : false,
		element_format : "xhtml",
		content_css: "/css/admin/tiny_mce.css"		
	}
	
	$('.tinymceSimple').tinymce(optionsSimple);	
});