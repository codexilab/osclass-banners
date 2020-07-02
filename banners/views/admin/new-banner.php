<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

	/*
	 * MIT License
	 * 
	 * Copyright (c) 2020 XenoTrue
	 * 
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 * 
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 * 
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */


$categories 	= osc_get_categories();
$positions 		= __get('positions');
$advertisers 	= __get('advertisers');
$bannerToUpdate = __get('bannerToUpdate');
?>

<style type="text/css">
input[type="text"].bg-text-gray {
    background-color : #d1d1d1;
}
</style>

<?php banners_admin_menu(); ?>

<form id="dialog-new" method="post" action="<?php echo osc_route_admin_url('banners-admin-new'); if ($bannerToUpdate) echo '&banner='.$bannerToUpdate['pk_i_id']; ?>" enctype="multipart/form-data">
	<input type="hidden" name="page" value="plugins" />
	<input type="hidden" name="action" value="renderplugin" />
	<input type="hidden" name="route" value="banners-admin-new" />
	<input type="hidden" name="plugin_action" value="new_banner" />

	<div class="form-horizontal">
		<div class="grid-system">

			<div class="grid-row grid-50">
				<div class="form-row">
					<div class="form-label"><?php _e("Categories", BANNERS_PREF) ?></div>
					<div class="form-controls">
						<div class="form-label-checkbox">
						<?php if ($categories) : ?>
							<?php if ($categories >= 1) : ?>
							<label><input id="all_categories" type="checkbox" name="all_categories" value="1" <?php echo (isset($bannerToUpdate['s_category'])) ? get_html_checked($bannerToUpdate['s_category'], "all") : ''; ?>><?php _e("All categories", BANNERS_PREF); ?></label><br><br>
							<?php endif; ?>
							<div id="categories">
								<?php $categoriesBannerToUpdate = (isset($bannerToUpdate['s_category'])) ? explode(',', $bannerToUpdate['s_category']) : array(); ?>
								<?php foreach ($categories as $category) : ?>
									<?php if (in_array($category['pk_i_id'], $categoriesBannerToUpdate)) : ?>
									<label><input type="checkbox" name="s_category[]" value="<?php echo $category['pk_i_id']; ?>" checked="true"><?php echo $category['s_name']; ?></label><br>
									<?php else: ?>
									<label><input type="checkbox" name="s_category[]" value="<?php echo $category['pk_i_id']; ?>"><?php echo $category['s_name']; ?></label><br>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-label"><?php _e("Position", BANNERS_PREF); ?></div>
					<div class="form-controls">
						<div class="select-box undefined">
							<div class="form-label-checkbox">
								<?php if ($positions) : ?>
								<select name="fk_i_position_id" id="fk_i_position_id" style="opacity: 0;">
									<?php foreach($positions as $position) : ?>
									<option value="<?php echo $position['pk_i_id']; ?>" <?php if (isset($bannerToUpdate['fk_i_position_id'])) echo get_html_selected($bannerToUpdate['fk_i_position_id'], $position['pk_i_id']); ?>><?php echo $position['i_sort_id']; ?></option>
									<?php endforeach; ?>
								</select>
								<?php endif; ?>
								<a href="#" onclick="set_position();return false;"><?php _e("Add (+)", BANNERS_PREF); ?></a>
							</div>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-label"><?php _e("Advertiser", BANNERS_PREF); ?></div>
					<div class="form-controls">
						<div class="select-box undefined">
							<div class="form-label-checkbox">
								<?php if ($advertisers) : ?>
									<select name="fk_i_advertiser_id" id="fk_i_advertiser_id" style="opacity: 0;">
									<?php foreach($advertisers as $advertiser) : ?>
										<?php if ($advertiser['s_business_sector']) : ?>
										<option value="<?php echo $advertiser['pk_i_id']; ?>" <?php if (isset($bannerToUpdate['fk_i_advertiser_id'])) echo get_html_selected($bannerToUpdate['fk_i_advertiser_id'], $advertiser['pk_i_id']); ?>><?php echo ($advertiser['fk_i_user_id']) ? get_user_name($advertiser['fk_i_user_id']).' ('.get_user_email($advertiser['fk_i_user_id']).') ' : $advertiser['s_name']; ?> - <?php echo $advertiser['s_business_sector']; ?></option>
										<?php else : ?>
										<option value="<?php echo $advertiser['pk_i_id']; ?>" <?php if (isset($bannerToUpdate['fk_i_advertiser_id'])) echo get_html_selected($bannerToUpdate['fk_i_advertiser_id'], $advertiser['pk_i_id']); ?>><?php echo ($advertiser['fk_i_user_id']) ? get_user_name($advertiser['fk_i_user_id']).' ('.get_user_email($advertiser['fk_i_user_id']).') ' : $advertiser['s_name']; ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
									</select>
								<?php endif; ?>
								<a href="#" onclick="set_advertiser();return false;"><?php _e("Add (+)", BANNERS_PREF); ?></a>
							</div>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-label"><?php _e("Banner", BANNERS_PREF); ?></div>
					<div class="form-controls">
						<div class="form-label-checkbox">
							<label><input id="bannertype" name="b_image" type="radio" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] || !$bannerToUpdate) echo 'checked="checked"'; ?> value="1"><?php _e("Image", BANNERS_PREF); ?></label> <label><input id="bannertype" name="b_image" type="radio" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && !$bannerToUpdate['b_image']) echo 'checked="checked"'; ?> value="0"><?php _e("Script", BANNERS_PREF); ?></label>
						</div>
						<div id="bannertype1" class="desc" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] <= 0) echo 'style="display: none;"'; ?>>
                            <input type="file" name="banner" id="banner" /> 
                            <?php if ($bannerToUpdate && isset($bannerToUpdate['s_name']) && isset($bannerToUpdate['s_extension'])) echo '<a href="#" onclick="show_banner(\''.BANNERS_ROUTE_SOURCES.$bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension'].'\');return false;">'.__("View", BANNERS_PREF).'</a>'; ?>
                        </div>
                        <div id="bannertype0" class="desc" <?php if (!$bannerToUpdate || $bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] >= 1) echo 'style="display: none;"'; ?>>
                            <textarea name="s_script"><?php if ($bannerToUpdate && isset($bannerToUpdate['s_script'])) : ?><?php echo htmlentities($bannerToUpdate['s_script']); ?><?php else: ?><?php echo htmlentities('<a href="{URL}">'.__("Click me!", BANNERS_PREF).'</a>'); ?><?php endif; ?></textarea>
                            <br>
                            <strong><?php _e("Optional:", BANNERS_PREF); ?></strong> <?php _e("You can use {URL} tag to count clicks from URL field if you wish.", BANNERS_PREF); ?>
                        </div>
					</div>
				</div>

				<div class="form-row">
                    <div class="form-label"><?php _e("URL", BANNERS_PREF); ?></div>
                    <div class="form-controls"><input type="text" class="xlarge" name="s_url" value="<?php if ($bannerToUpdate && isset($bannerToUpdate['s_url']) && $bannerToUpdate['s_url']) echo $bannerToUpdate['s_url']; ?>"></div>
                </div>

                <div class="form-row">
                    <div class="form-label"><?php _e("Since", BANNERS_PREF); ?></div>
                    <div class="form-controls"><input id="dt_since_date" type="text" class="xlarge" name="dt_since_date" value="<?php if (isset($bannerToUpdate['dt_since_date'])) echo $bannerToUpdate['dt_since_date']; ?>" placeholder="<?php echo todaydate(null, null, '00:00:00'); ?>"></div>
                </div>

                <div class="form-row">
                    <div class="form-label"><?php _e("Until", BANNERS_PREF); ?></div>
                    <div class="form-controls"><input id="dt_until_date" type="text" class="xlarge" name="dt_until_date" value="<?php if (isset($bannerToUpdate['dt_until_date'])) echo $bannerToUpdate['dt_until_date']; ?>" placeholder="<?php echo todaydate(1, 'month', '00:00:00'); ?>"></div>
                </div>

                <div class="form-row">
                    <div class="form-controls"><label><input type="checkbox" <?php echo (!$bannerToUpdate || isset($bannerToUpdate['b_active']) && $bannerToUpdate['b_active']) ? 'checked="true"' : ''; ?> name="b_active" value="1"> <?php _e("Activate this banner.", BANNERS_PREF); ?></label></div>
                </div>
			</div>

			<div class="grid-row grid-50 text-left">
				<div class="form-row">
					<div class="form-label"><?php _e("Color in the calendar", BANNERS_PREF); ?></div>
					<div class="form-controls">
                        <input type="color" name="s_color" value="<?php if ($bannerToUpdate) echo $bannerToUpdate['s_color']; ?>">
                    </div>
				</div>

				<?php if ($bannerToUpdate) : ?>
				<div class="form-row">
					<div class="form-label"><?php _e("Added", BANNERS_PREF); ?></div>
					<div class="form-controls">
						<div class="select-box undefined">
							<div class="form-label-checkbox"><?php echo osc_format_date($bannerToUpdate['dt_date'], osc_date_format() . ' ' . osc_time_format()); ?></div>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<?php if ($bannerToUpdate && $bannerToUpdate['dt_update'] != 0) : ?>
                <div class="form-row">
                    <div class="form-label"><?php _e("Last update", BANNERS_PREF); ?></div>
                    <div class="form-controls">
                        <div class="select-box undefined">
                            <div class="form-label-checkbox"><?php echo osc_format_date($bannerToUpdate['dt_update'], osc_date_format() . ' ' . osc_time_format()); ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-row center">
                	<div class="text-center fieldset-calendar" style="width: fit-content">
                		<fieldset>
                			<legend><?php _e("Calendar", BANNERS_PREF); ?></legend>
                			<div id="show-calendar-content" style="width: fit-content"></div>
                		</fieldset>
                	</div>
                </div>
			</div>

			<div class="clear"></div>

			<div class="form-actions">
				<a class="btn" href="<?php echo adminReturnBack('banners-admin-advertisers'); ?>"><?php _e('Cancel and go back', BANNERS_PREF); ?></a>
				<input type="submit" value="<?php echo ($bannerToUpdate) ? __("Update banner", BANNERS_PREF) : __("Add new banner", BANNERS_PREF); ?>" class="btn btn-submit">
			</div>

		</div>
	</div>
</form>

<!-- Modal Windows of Show Banner -->
<div id="show-banner" class="has-form-actions hide">
    <div class="form-horizontal">
        <div class="form-row text-center">
            <br>
            <img id="image_banner" src="">
        </div>        
        <div class="form-actions">
            <div class="wrapper">
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
	if ($("#all_categories").is(':checked')) {
        $("#categories").hide();
    } else {
        $("#categories").show();
    }
    $('#all_categories').click(function () {
        if ($(this).is(':checked')) {
            $("#categories").hide();
        } else {
            $("#categories").show();
        }
    });

    $("input[name$='b_image']").click(function() {
        var test = $(this).val();
        $("div.desc").hide();
        $("#bannertype"+test).show();
    });

    $('#dt_since_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#dt_until_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    /*
     * jQuery get selected option value (text and attribute 'value') 
     * From: https://stackoverflow.com/questions/13089944/jquery-get-selected-option-value-not-the-text-but-the-attribute-value 
     */

    // Ways to retrieve selected option and text outside handler
	var positionId 		= $('#fk_i_position_id option').filter(':selected').val(); 	//console.log('Selected option value ' + positionId); 
	var positionSort 	= $('#fk_i_position_id option').filter(':selected').text(); //console.log('Selected option text ' + positionSort);
	show_calendar(positionId);

	$('#fk_i_position_id').on('change', function () {
		// Ways to retrieve selected option and text outside handler
		positionId 		= this.value; 												//console.log('Changed option value ' + positionId);
		positionSort 	= $(this).find('option').filter(':selected').text(); 	//console.log('Changed option text ' + positionSort);
		show_calendar(positionId);
	});

<?php if ($bannerToUpdate && isset($bannerToUpdate['s_name']) && isset($bannerToUpdate['s_extension']) && file_exists(BANNERS_FOLDER_SOURCES.$bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension'])) : ?>
	<?php
	$banner = array();
	list($banner['width'], $banner['height']) = getimagesize(BANNERS_ROUTE_SOURCES.$bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension']);
	?>
    $("#show-banner").dialog({
        autoOpen: false,
        width: "<?php echo (isset($banner['width']) && isset($banner['height']) && $banner['width'] >= $banner['height']) ? $banner['width'].'px' : $banner['height'].'px'; ?>",
        modal: true,
        title: '<?php echo osc_esc_js( __('Show banner', BANNERS_PREF) ); ?>'
    });
<?php endif; ?>
});

<?php if ($bannerToUpdate && file_exists(BANNERS_FOLDER_SOURCES.$bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension'])) : ?>
function show_banner(img) {
    $('#image_banner').prop('src', img);
    $('#show-banner').dialog('open');
};
<?php endif; ?>
</script>