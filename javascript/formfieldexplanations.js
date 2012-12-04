
/*
 *@author: nicolaas[at] sunnysideup.co.nz
 **/

;(function($) {
	$(document).ready(
		function() {
			formfieldexplanations.init();
		}
	);
})(jQuery);


var formfieldexplanations = {

	addLinkSelector: ".formfieldexplanations a.addFieldExplanation",

	editLinkSelector: ".formfieldexplanations a.editFieldExplanation",

	moreInfoSelector: ".formfieldexplanations",

	creatingEntryMsg: "creating entry",

	fadeOutClass: "makeMeFadeOut",

	fadeInClass: "makeMeFadeIn",

	holderSelector: ".field",

	moreInfoPrefix: "moreinfo-",

	init: function () {
		//add links
		jQuery(formfieldexplanations.addLinkSelector).click(
			function() {
				jQuery(this).text(formfieldexplanations.creatingEntryMsg);
				var href = jQuery(this).attr("href");
				jQuery(this).parent().load(
					href
				);
				return false;
			}
		);
		//edit links
		jQuery(formfieldexplanations.editLinkSelector).attr("target", "CMS");

			//right labels
		jQuery(formfieldexplanations.moreInfoSelector).hide().each(
			function() {
				var idPlus = jQuery(this).attr("id");
				var id = idPlus.replace(formfieldexplanations.moreInfoPrefix, "");
				jQuery("#"+id).find("input, select, textarea").focus(
					function() {
						//alert("doing it");
						id = jQuery(this).attr("id");
						var selectorOut = formfieldexplanations.moreInfoSelector;
						var selectorIn = "."+formfieldexplanations.moreInfoPrefix+id;
						if(jQuery(selectorIn).length < 1) {
							var parents = jQuery(this).parents(formfieldexplanations.holderSelector);
							if(parents.length) {
								var firstField = parents[0];
								if(firstField) {
									selectorIn = "."+formfieldexplanations.moreInfoPrefix+firstField.id;
									if(jQuery(selectorIn).length < 1) {
										var parents = jQuery(this).parents(formfieldexplanations.holderSelector);
										if(parents.length > 1) {
											var firstField = parents[1];
											if(firstField) {
												selectorIn = "."+formfieldexplanations.moreInfoPrefix+firstField.id;
											}
										}
									}
								}
							}
						}
						if(jQuery(selectorIn).length < 1) {
							selectorIn = selectorIn.replace(".", "#");
						}
						//alert(selectorIn);
						jQuery(selectorOut).removeClass(formfieldexplanations.fadeInClass);
						jQuery(selectorOut).addClass(formfieldexplanations.fadeOutClass);
						jQuery(selectorIn).removeClass(formfieldexplanations.fadeOutClass);
						jQuery(selectorIn).addClass(formfieldexplanations.fadeInClass);
						jQuery("."+formfieldexplanations.fadeOutClass).fadeOut(700);
						jQuery("."+formfieldexplanations.fadeInClass).fadeIn(700);
					}
				);
			}
		);
	},

	add_info: function(id, html, forID) {
		if(jQuery("#"+forID).length < 1) {
			forID = id;
		}
		jQuery("#"+id).append('<div class="formfieldexplanations '+formfieldexplanations.moreInfoPrefix+forID+'" id="'+formfieldexplanations.moreInfoPrefix+''+id+'">'+html+'</div>');
	},

	replace_title: function(id, html, forID) {
		if(jQuery("#"+forID).length < 1) {
			forID = id;
		}
		jQuery("#"+id).children("label.left").html(html);
	}



}





