

<div class="bo-panel clear">
<form method="post" action="/services/Account"> 

	<div class="form-fieldset">
		<div class="form-row clearfix">
			<label for="film_ticket_price ">Ticket Price</label>
			<div class="input">$
				<input class="text-input span1 post-data" id="film_name" name="film_ticket_price" value="<?php echo $post["film_ticket_price"];?>"  />
			</div>
		</div>
		<div class="form-row hr clearfix"></div>
		<div class="form-row clearfix">
			<label for="film_name ">Enable On Demand</label>
			<div class="input">
				<input id="film_allow_hostbyrequest_no" class="post-data" id="film_allow_hostbyrequest_no" name="film_allow_hostbyrequest" type="radio" value="0" checked />
				<label for="film_allow_hostbyrequest_no">No</label>
				<input id="film_allow_hostbyrequest_yes" class="post-data" name="film_allow_hostbyrequest" type="radio" value="1"  />
				<label for="film_allow_hostbyrequest_yes">Yes</label>

			</div>
		</div>
		<div id="sub-form-row-wn" class="sub-form-row" style="display: none">
			<div class="form-row clearfix">
				<label for="film_hostbyrequest_price ">On Demand Price</label>
				<div class="input">$
					<input class="text-input span1 post-data" id="film_name" name="film_hostbyrequest_price" value="<?php echo $post["film_hostbyrequest_price"];?>"  />
				</div>
			</div>
		</div>
		<div class="form-row hr clearfix"></div>

		<div class="form-row clearfix">
			<label for="film_name ">Enable Geoblocking</label>
			<div class="input">
				<input id="film_geoblocking_enabled_no" class="post-data" name="film_geoblocking_enabled" type="radio" value="0" checked />
				<label for="film_geoblocking_enabled_no" checked>No</label>
				<input id="film_geoblocking_enabled_yes" class="post-data" name="film_geoblocking_enabled" type="radio" value="1"  />
				<label for="film_geoblocking_enabled_yes">Yes</label>
			</div>
		</div>
		<div  id="sub-form-row-geo" class="sub-form-row" style="display: none">
			<div class="form-row clearfix">
				<label for="film_name ">Block Users from</label>
				<div class="input">
				<ul class="input-list">
					<li>
						<input type="checkbox" value="AF" class="" name="film_geoblocking_region[]" id="film_geoblocking_region_1">
						<label for="film_geoblocking_region_1">Africa</label>
					</li>
					<li>
						<input type="checkbox"  value="AS" class="" name="film_geoblocking_region[]" id="film_geoblocking_region_2">
						<label for="film_geoblocking_region_2">Asia</label>
					</li>
					<li>
						<input type="checkbox"  value="EU" class="" name="film_geoblocking_region[]" id="film_geoblocking_region_3">
						<label for="film_geoblocking_region_3">Europe</label>
					</li>
					<li>
						<input type="checkbox"  value="NA" class="" name="film_geoblocking_region[]" id="film_geoblocking_region_4">
						<label for="film_geoblocking_region_4">North America</label>
					</li>
					<li>
						<input type="checkbox"  value="SA" class="" name="film_geoblocking_region[]" id="film_geoblocking_region_5">
						<label for="film_geoblocking_region_5">South America</label>
					</li>
					<li>
						<input type="checkbox"  value="OC" class="" name="film_geoblocking_region[]" id="film_geoblocking_region_6">
						<label for="film_geoblocking_region_6">Oceania</label>
					</li>
					<li>
						<input type="checkbox" value="AN" class="" name="film_geoblocking_region[]" id="film_geoblocking_region_7">
						<label for="film_geoblocking_region_7">Antartica</label>
					</li>
				</ul>
			</div>
		</div>
		</div>


	</div>

	<div class="form-row">
		<span class="link link-cancel left">Cancel</span>
		<button class="button button_green uppercase right" type="submit">Save</button>
		<input type="hidden" name="film_id" value="0" />
		<input type="hidden" name="styroname" value="accountFilmSettings" />
	</div>
</form>
</div>  


<script>
jQuery(function(){
	$('input[name=film_allow_hostbyrequest]').bind('change', function(){
		var value = $(this).val();
		switch (value) {
			case '1':
				$('#sub-form-row-wn').fadeIn();
				break;
			case '0':
				$('#sub-form-row-wn').fadeOut();
				break;
		}
	});
	$('input[name=film_geoblocking_enabled]').bind('change', function(){
		var value = $(this).val();
		switch (value) {
			case '1':
				$('#sub-form-row-geo').fadeIn();
				break;
			case '0':
				$('#sub-form-row-geo').fadeOut();
				break;
		}
	});


	/*new CTV.AddTemplate({
		// domNode: '',
		templateContainer: $('#geo-template-container'),
		addButton: $('#add-geo'),
		template: $('#geoblock').html()
	})*/

	// $('.add-geo').bind('click', function(){
	// 	var html = $('#geoblock').html();
	// 	console.log(html);
	// 	$(this).parents('.input').before($(html));
	// })

	/*$('.filter-options').live('change', function(){
		var value = $(this).val();
		var inputs = $(this).siblings('select, input');
		switch (value){
			case 'country':
				inputs.hide();
				$(inputs.get(1)).show();
				break;
			case 'region':
				inputs.hide();
				$(inputs.get(0)).show();
				break;
			case 'ip':
				inputs.hide();
				$(inputs.get(2)).show();
				break;
			default:
				inputs.hide();
				break;
		}
	})*/
})
</script>

<script type="template/text" id="geoblock">
				<div class="sub-input">
					<select class="filter-options">
						<option value="">Select Type</option>
						<option value="region">Region</option>
						<option value="country">Country</option>
						<option value="ip">IP</option>
					</select>

					<select name="film_geoblocking_region[]" style="display: none;">
						<option value="AF">AF</option>
						<option value="AS">AS</option>
						<option value="EU">EU</option>
						<option value="NA">NA</option>
						<option value="SA">SA</option>
						<option value="OC">OC</option>
						<option value="AN">AN</option>
					</select>
					<select name="film_geoblocking_country[]" style="display: none;">
						<option value="AF">France</option>
						<option value="AS">England</option>
						<option value="EU">Germany</option>
					</select>
					<input type="text" class="text-input s span3" name="film_geoblocking_region[]" style="display: none;"/>
				</div>
</script>