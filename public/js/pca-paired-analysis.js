jQuery(document).ready(function ($) {
	$("#paired_analysis").show();

	$(document).on('input', '.item__input', function () {
		if ($(this).val() !== '' && $(this).parent().next().length == 0) {
			$('#lines').append(
				`<div class="item__line"><input type="text" class="item__input"></div>`
			);
		}
		if ($(this).val() === '') {
			$(this).parent().remove();
		}
	});

	function* doPairs(iterable) {
		const seenItems = new Set();
		for (const currentItem of iterable) {
			if (!seenItems.has(currentItem)) {
				if(currentItem.length > 0){
					for (const seenItem of seenItems) {
						yield [seenItem, currentItem];
					}
					seenItems.add(currentItem);
				}
			}
		}
	}

	function renderPairedItems(pairedItems) {
		switch (ajaxdata.type) {
			case 'text':
				pairedItems.forEach((el, ind) => {
					$('#list__items').append(`<li class="list__item ${
						ind === 0 ? 'active' : '' }"> 
						<span>${ajaxdata.pquestion} </span>
						<div class="item__one pitem" data-value="${el[0]}">${el[0]}</div> 
						<div class="item__two pitem" data-value="${el[1]}">${el[1]}</div> 
						</li>`);
					});
				break;
			case 'image':
				pairedItems.forEach((el, ind) => {
					$('#list__items').append(`<li class="list__item ${
						ind === 0 ? 'active' : '' }"> 
						<span>${ajaxdata.pquestion} </span>
							<div class="item__one pitem imgItem" data-value="${el[0]}">
								<img src="${el[0]}"/>
							</div>
							<div class="item__two pitem imgItem" data-value="${el[1]}">
								<img src="${el[1]}"/>
							</div> 
						</li>`);
				});
				break;
			default:
				$('#items__form').addClass('noned');
				$('#items_questions').removeClass('noned');

				pairedItems.forEach((el, ind) => {
					$('#list__items').append(`<li class="list__item ${
						ind === 0 ? 'active' : '' }"> 
						<span>${ajaxdata.pquestion} </span>
						<div class="item__one pitem" data-value="${el[0]}">${el[0]}</div> 
						<div class="item__two pitem" data-value="${el[1]}">${el[1]}</div> 
						</li>`);
					});
				break;
		}
	}

	// For research TEXT
	if(ajaxdata.type === 'text'){
		if(ajaxdata.data.length > 1){
			const pairsOfNumbers = doPairs(ajaxdata.data);
			const pairedItems = Array.from(pairsOfNumbers);
			
			renderPairedItems(pairedItems);
		}
	}
	// For research IMAGE
	if(ajaxdata.type === 'image'){
		if(ajaxdata.data.length > 1){
			const pairsOfNumbers = doPairs(ajaxdata.data);
			const pairedItems = Array.from(pairsOfNumbers);
			
			renderPairedItems(pairedItems);
		}
	}

	$('#item__submit').on('click', function () {
		let data = [];
		$(document)
		.find('input.item__input')
		.each(function () {
			if ($(this).val() !== '') {
				data.push($(this).val());
			}
		});
		if(data.length > 1){
			const pairsOfNumbers = doPairs(data);
			const pairedItems = Array.from(pairsOfNumbers);
		
			renderPairedItems(pairedItems);
		}
	});

	$(document).on('click', '.pitem', function () {
		$(this).addClass('selected');
		$(this).parents('.list__item').css({
		opacity: '.5',
		'pointer-events': 'none',
		});

		setTimeout(() => {
			$(this).parents('li.active').removeClass('active');
			$(this).parents('li').next('li').addClass('active');
		}, 500);

		if ($(this).parents('li.active').next().length === 0) {
			let selectedItems = [];
			$('.pitem.selected').each(function () {
				selectedItems.push($(this).data('value'));
			});

			const scores = {};
			selectedItems.forEach((element) => {
				scores[element] = (scores[element] || 0) + 1;
			});

			setTimeout(() => {
				$("#items_questions").html(ajaxdata.form);
				let sortable = [];
				for (let el in scores) {
					sortable.push([el, scores[el]]);
				}
				sortable.sort(function(a, b) {
					return b[1] - a[1];
				});
				
				for(let i = 0; i < 10; i++){
					if(sortable[i] !== undefined){
						let el = sortable[i];
						
						let valu = el[0]+" - "+el[1]+" points";

						if(i == 0){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca1).val(valu);
						}
						if(i == 1){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca2).val(valu);
						}
						if(i == 2){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca3).val(valu);
						}
						if(i == 3){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca4).val(valu);
						}
						if(i == 4){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca5).val(valu);
						}
						if(i == 5){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca6).val(valu);
						}
						if(i == 6){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca7).val(valu);
						}
						if(i == 7){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca8).val(valu);
						}
						if(i == 8){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca9).val(valu);
						}
						if(i == 9){
							$("#wpforms-"+ajaxdata.form_id+"-field_"+ajaxdata.pca10).val(valu);
						}
					}
				}
			}, 500);
		}
	});
});
