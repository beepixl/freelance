(function($) {
"use strict";
			
	//Shortcodes
   tinymce.PluginManager.add( 'zillaShortcodes', function( editor, url ) {
	
	editor.addCommand("zillaPopup", function ( a, params )
	{
		var popup = params.identifier;
		tb_show("Insert Zilla Shortcode", url + "/popup.php?popup=" + popup + "&width=" + 800);
	});

		editor.addButton( 'zilla_button', {
			type: 'splitbutton',
			icon: false,
			title:  'Zilla Shortcodes',
			onclick : function(e) {},
			menu: [

				{
					text: 'Posts',
					menu: [
						{text: 'Post List',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Post List',identifier: 'posts'})
						}},
						{text: 'Team',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Team',identifier: 'team'})
						}}
					]
				},

				{text: 'Columns',onclick:function(){
					editor.execCommand("zillaPopup", false, {title: 'Columns',identifier: 'columns'})
				}},

				{
					text: 'Typography',
					menu: [
						{text: 'Buttons',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Buttons',identifier: 'button'})
						}},
						{text: 'Dropcaps',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Dropcaps',identifier: 'dropcap'})
						}},
						{text: 'Rules',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Rules',identifier: 'hr'})
						}},
						{text: 'Link',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Link',identifier: 'link'})
						}},
						{text: 'Text Lead',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Text Lead',identifier: 'txt_lead'})
						}},
						{text: 'Lists',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Lists',identifier: 'list'})
						}},
						{text: 'Definition List',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Definition List',identifier: 'dl'})
						}},
						{text: 'Spacer',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Spacers',identifier: 'spacer'})
						}}
					]
				},

				{text: 'Alerts',onclick:function(){
					editor.execCommand("zillaPopup", false, {title: 'Alerts',identifier: 'alert'})
				}},

				{
					text: 'Elements',
					menu: [
						{text: 'Tabs',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Tabs',identifier: 'tabs_new'})
						}},
						{text: 'Accordion',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Accordion',identifier: 'accordion'})
						}},
						{text: 'Carousel',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Carousel',identifier: 'carousel'})
						}},
						{text: 'Call to Action',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Call to Action',identifier: 'cta'})
						}},
						{text: 'Testimonials',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Testimonials',identifier: 'testimonial'})
						}},
						{text: 'Pricing Tables',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Pricing Tables',identifier: 'pricing_tables'})
						}},
						{text: 'Icon Box',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Icon Box',identifier: 'icobox'})
						}},
						{text: 'Jumbotron',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Jumbotron',identifier: 'jumbotron'})
						}},
						{text: 'Infobox',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Infobox',identifier: 'infobox'})
						}},
						{text: 'Box',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Box',identifier: 'box'})
						}},
						{text: 'Slider',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Slider',identifier: 'slider'})
						}}
					]
				},


				{
					text: 'Jobs',
					menu: [
						{text: 'Jobs',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Jobs',identifier: 'jobs'})
						}},
						{text: 'Job',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Job',identifier: 'job'})
						}},
						{text: 'Job Summary',onclick:function(){
							editor.execCommand("zillaPopup", false, {title: 'Job Summary',identifier: 'job_summary'})
						}},
						{text: 'Submit Job Form',onclick:function(){
							editor.execCommand("mceInsertContent", false, '[submit_job_form]')
						}},
						{text: 'Job Dashboard',onclick:function(){
							editor.execCommand("mceInsertContent", false, '[job_dashboard]')
						}}
					]
				}
			]
	});

});
 
})(jQuery);