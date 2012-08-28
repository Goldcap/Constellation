<?php 
//return;
$ttz = date_default_timezone_get();
$zones = zoneList(); 
?>

<div class="bo-panel clear">

	<div class="screening-block clearfix">
		<div class="details left">
			<p class="title">Screening hosted by James Lawler</p>
			<p class="time">9:00 PM EST, Decenber 20th, 2011</p>
			<p class="repeat">No Repeat</p>
		</div>
		<div class="status left">Expired</div>
	</div>
	<div class="screening-block clearfix">
		<span class="edit-icon right"></span>
		<span class="delete-icon right"></span>
		<div class="details">
			<p class="title">Archangel Screening</p>
			<p class="time">9:00 PM EST, January 10th, 2012</p>
			<p class="repeat">2 Repeat</p>
		</div>
		<div class="status">Upcoming</div>
	</div>

	<div class="screening-block clearfix">
		<span class="edit-icon right"></span>
		<span class="delete-icon right"></span>

		<div class="details">
			<p class="title">Archangel Screening</p>
			<p class="time">9:00 PM EST, January 24th, 2012</p>
			<p class="repeat">No Repeat</p>
		</div>
		<div class="status">Upcoming</div>

	</div>

	<div class="form-row">
		<span class="button button_green uppercase right" id="add-screening">Add New Screening</span>
	</div>

	<div class="form-fieldset clear" style="display: none;" id="screening-form">
		<div class="form-row hr edit-break"></div>


		<div class="form-row clearfix">
			<label for="screening_name">Custom Title</label>
			<div class="input">
				<input id="screening_name" class="text-input post-data span6" name="screening_name" type="text"/>
			</div>
		</div>
		<div class="form-row clearfix hr"></div>
		<div class="form-row clearfix">
			<label for="screening_date">Date</label>
			<div class="input">
				<input id="screening_date" class="text-input post-data span2" name="screening_date" type="text"/>
			</div>
		</div>				
		<div class="form-row clearfix">
			<label for="screening_time">Time</label>
			<div class="input">
				<input id="screening_time" class="text-input post-data span1" name="screening_time" type="text"/>
			</div>
		</div>
		<div class="form-row clearfix">
			<label for="screening_time">Timezone</label>
			<div class="input">
				<select id="screening_timezone" name="screening_timezone">
				  <?php foreach (array_keys($zones) as $zone) {?>
					<optgroup label="<?php echo strtoupper($zone);?>">
						<?php foreach($zones[$zone] as $key => $atz) {?>
							<option value="<?php echo $key;?>" <?php if ($ttz == $key) {?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo $atz;?></option>
						<?php } ?>
					</optgroup>
					<?php } ?>
				</select>
			</div>
		</div>


		<div class="form-row clearfix hr"></div>
		<div class="form-row clearfix">
			<label for="screening_time">Hosting</label>
			<div class="input">
				<input id="screening_time" class="text-input post-data" name="screening_host" value="1" type="checkbox"/>
				<label>Host this screening</label>
			</div>
		</div>

		<div class="form-row clearfix hr"></div>
		<div class="form-row clearfix">
			<label for="screening_repeat">Repeat Screening</label>
			<div class="input">
				<input id="screening_repeat" class="post-data" name="screening_repeat" type="checkbox"/>
			</div>
		</div>				
		<div id="sub-form-row-repeat" class="sub-form-row" style="display: none">
			<div class="form-row clearfix">
				<label for="screening_repeat_count ">Repeat Count</label>
				<div class="input">
					<input id="screening_repeat_count" class="text-input post-data span1" name="screening_repeat_count" type="text"/>
				</div>
			</div>
			<div class="form-row clearfix">
				<label for="screening_repeat_start_date ">Repeat Start Date</label>
				<div class="input">
					<input id="screening_repeat_start_date" class="text-input post-data span2" name="screening_repeat_start_date" type="text"/>
				</div>
			</div>
			<div class="form-row clearfix">
				<label for="screening_repeat_count ">Repeat Interval</label>
				<div class="input">
					<input id="screening_repeat_interval" class="text-input post-data span2" name="screening_repeat_interval" type="text"/>
					<p class="sublabel">(minutes)</span>
				</div>
			</div>
		</div>	
		<div class="form-row clearfix hr"></div>
		<div class="form-row clearfix">
			<span class="button button_green uppercase right">Save</span>
			<input type="hidden" name="film_id" value="0" />
			<input type="hidden" name="styroname" value="accountFilmScreenings" />
			<span class="link link-cancel uppercase left" data-panel-index="0" id="cancel-screening">Cancel</span>

		</div>	
	</div>

</div>  


<script>

	$(function(){

	$('.edit-icon').live('click', function(){
		screeningForm.appendTo($(this).parent().addClass('edit-mode'));
			screeningForm.fadeIn();
			addScreeningButton.hide();
	});

	$('.delete-icon').live('click', function(){
		$('.screening-block').removeClass('edit-mode');
		var parent = $(this).parent();
		Dialog.open({
			title: 'Delete Showtime',
			body: 'Are you sure you want to delete this showtime',
			buttons: [
				{
					text:'Yes',
					klass: 'test',
					callback: function(){
						parent.remove();
					} 
				},
				{
					text:'No',
					klass: 'test'
				}
			]
		})
	});




		$('#screening_repeat').click(function(){
			var subForm = $('#sub-form-row-repeat');

			if($(this).is(':checked')){
				subForm.fadeIn();
			} else {
				subForm.fadeOut();

			}
		});

		var addScreeningButton = $('#add-screening');
		var screeningForm = $('#screening-form');
		var cancelScreeningButton =  $('#cancel-screening');


		addScreeningButton.bind('click', function(){
			addScreeningButton.parent().after(screeningForm)
			addScreeningButton.hide();
			screeningForm.fadeIn();
		})
		cancelScreeningButton.bind('click', function(){
			addScreeningButton.show();
			screeningForm.hide();
		$('.screening-block').removeClass('edit-mode');

		})


		$('#screening_date').datepicker({
	    	numberOfMonths: 2,
	        minDate: new Date()
	    });
	    // $('#screening_time').timepicker({
	    //     ampm: true
	    // });

	    $('#screening_repeat_start_date').datepicker({
	    	numberOfMonths: 2,
	        minDate: new Date()
	    });

	})
</script>