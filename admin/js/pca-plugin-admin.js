jQuery(function( $ ) {
	'use strict';

	function uploadImage(el) {
		var imgfile, selectedFiles, times;
		times = new Date().getTime();
		// If the frame already exists, re-open it.
		if (imgfile) {
			imgfile.open();
			return;
		}
		//Extend the wp.media object
		imgfile = wp.media.frames.file_frame = wp.media({
			title: 'Choose Animations',
			button: {
				text: 'Upload'
			},
			multiple: true
		});

		//When a file is selected, grab the URL and set it as the text field's value
		imgfile.on('select', function () {
			selectedFiles = imgfile.state().get('selection');
			selectedFiles.map( function( attachment ) {
				let file = attachment.toJSON();
				el.parent().find("img").attr("src", file.url);
				el.parent().find('input').val(file.url);
			});
		});

		//Open the uploader dialog
		imgfile.open();
	}

	$(document).on("click", "#addImgField", function(e){
		e.preventDefault();
		let times = new Date().getTime();
		let wrapper = $("#rImages");
		wrapper.append(`<div class="image_research"> <span class="removeImg">+</span> <div class="previewBox"><img src="" class="imgPreview"> <input type="hidden" name="research_images[${times}][img]" value=""> <button class="uploadImg button-secondary">Upload</button> </div> <textarea name="research_images[${times}][desc]"></textarea> </div>`);
	});

	$(document).on("click", ".uploadImg", function(e){
		e.preventDefault();
		uploadImage($(this));
	});

	$(document).on("click", ".removeImg", function(){
		$(this).parents(".image_research").remove();
	});

	$("#research__type_value").on("change", function(){
		let wrapper = $("#research_contents");
		let times = new Date().getTime();
		switch ($(this).val()) {
			case 'text':
				wrapper.html(`<div id="rTexts"> <div class="textInput"> <input class="text__input" placeholder="text" type="text" name="research_texts[]" value=""> </div> </div>`);
				break;
			case 'image':
				wrapper.html(`<div id="rImages" class="rImages"><div class="image_research"> <span class="removeImg">+</span> <div class="previewBox"> <img src="" class="imgPreview"> <input type="hidden" name="research_images[${times}][img]" value=""> <button class="uploadImg button-secondary">Upload</button> </div> <textarea name="research_images[${times}][desc]"></textarea> </div></div> <button id="addImgField" class="button-secondary">Add research item</button>`);
				break;
		}
	});

	$(document).on('input', '.text__input', function () {
		if ($(this).val() !== '' && $(this).parent().next().length == 0) {
			$(document).find('#rTexts').append(
				`<div class="textInput"> <input class="text__input" placeholder="text" type="text" name="research_texts[]" value=""> </div>`
			);
		}
		if ($(this).val() === '') {
			$(this).parent().remove();
		}
	});

	$("#getFormFields").on("click", function(e){
		e.preventDefault();
		let formid = $("#formId").val();
		let btn = $(this)
		$.ajax({
			type: "post",
			url: ajaxd.ajaxurl,
			data: {
				action: "get_wpforms_fields",
				form_id: formid
			},
			beforeSend: function(){
				btn.prop("disabled", true);
				$(".fieldIds").html("")
			},
			dataType: "json",
			success: function (response) {
				if(response.data){
					btn.removeAttr("disabled");

					let options = '';
					$.each(response.data, function (ind, elm) { 
						options += '<option value="'+elm.id+'">'+elm.label+'</option>';
					});

					for(let i = 1; i < 11; i++){
						let el = `<label for="pca${i}">PCA-${i}
								<select class="widefat" id="pca${i}" name="form_data[pca${i}]">
									${options}
								</select>
							</label>`
						$(".fieldIds").append(el)
					}
					
				}
			}
		});
	})

});
