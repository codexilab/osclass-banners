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

$categories 	= __get('categories');
$selected 		= __get('selected');

$positions 		= __get('positions');
$advertisers 	= __get('advertisers');

$bannerToUpdate = __get('bannerToUpdate');
?>

<?php banners_admin_menu(); ?>

<form id="dialog-new" class="plugin-configuration form-horizontal" method="post" action="<?php echo osc_route_admin_url('banners-admin-set'); ?>" enctype="multipart/form-data">
	<input type="hidden" name="page" value="plugins" />
	<input type="hidden" name="action" value="renderplugin" />
	<input type="hidden" name="route" value="banners-admin-set" />
	<input type="hidden" name="plugin_action" value="new_banner" />

	<!-- Get banner Id -->
	<?php if ($bannerToUpdate) : ?>
	<input type="hidden" name="banner" value="<?php if (isset($bannerToUpdate['pk_i_id'])) echo $bannerToUpdate['pk_i_id']; ?>">
	<?php endif; ?>

	<div class="form-horizontal">
		<div class="grid-system">

			<div class="grid-row grid-50">
				<div class="form-row">
					<div class="form-label"><?php _e('Sections (*)', BANNERS_PREF); ?></div>
					
                    <div class="form-controls">
                    	<ul id="plugin_sections">
                        	<ul>
                        		<li><span><?php _e('Show sections', BANNERS_PREF); ?></span>
									<ul id="catsections">
										<li>
											<a href="javascript:void(0);" onclick="checkAll('plugin_sections', true); return false;"><?php _e('Check all'); ?></a> &middot;
	                        				<a href="javascript:void(0);" onclick="checkAll('plugin_sections', false); return false;"><?php _e('Uncheck all'); ?></a>
	                        			</li>
										<li><input type="checkbox" name="categories[]" value="home" <?php if (in_array('home', $selected)) echo 'checked="true"'; ?>><span><?php _e('Home page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="page" <?php if (in_array('page', $selected)) echo 'checked="true"'; ?>><span><?php _e('Static page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="contact" <?php if (in_array('contact', $selected)) echo 'checked="true"'; ?>><span><?php _e('Contact page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="item_add" <?php if (in_array('item_add', $selected)) echo 'checked="true"'; ?>><span><?php _e('Publish page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="item_edit" <?php if (in_array('item_edit', $selected)) echo 'checked="true"'; ?>><span><?php _e('Edit page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="item_contact" <?php if (in_array('item_contact', $selected)) echo 'checked="true"'; ?>><span><?php _e('Item contact page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="login" <?php if (in_array('login', $selected)) echo 'checked="true"'; ?>><span><?php _e('Login page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="register" <?php if (in_array('register', $selected)) echo 'checked="true"'; ?>><span><?php _e('Register page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="login_recover" <?php if (in_array('login_recover', $selected)) echo 'checked="true"'; ?>><span><?php _e('Recover page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="login_forgot" <?php if (in_array('login_forgot', $selected)) echo 'checked="true"'; ?>><span><?php _e('Forgot page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="custom" <?php if (in_array('custom', $selected)) echo 'checked="true"'; ?>><span><?php _e('Custom pages', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="pub_profile" <?php if (in_array('pub_profile', $selected)) echo 'checked="true"'; ?>><span><?php _e('Public profile page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="dashboard" <?php if (in_array('dashboard', $selected)) echo 'checked="true"'; ?>><span><?php _e('User dashboard', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="profile" <?php if (in_array('profile', $selected)) echo 'checked="true"'; ?>><span><?php _e('User profile', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="items" <?php if (in_array('items', $selected)) echo 'checked="true"'; ?>><span><?php _e('User\'s items page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="alerts" <?php if (in_array('alerts', $selected)) echo 'checked="true"'; ?>><span><?php _e('User\'s alerts page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="change_email" <?php if (in_array('change_email', $selected)) echo 'checked="true"'; ?>><span><?php _e('Change email page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="change_username" <?php if (in_array('change_username', $selected)) echo 'checked="true"'; ?>><span><?php _e('Change username page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="change_password" <?php if (in_array('change_password', $selected)) echo 'checked="true"'; ?>><span><?php _e('Change password page', BANNERS_PREF); ?></span></li>
										<li><input type="checkbox" name="categories[]" value="error" <?php if (in_array('error', $selected)) echo 'checked="true"'; ?>><span><?php _e('Error 404', BANNERS_PREF); ?></span></li>
										<li>
											<span><?php _e('Search and Item pages\'s categories:', BANNERS_PREF); ?></span><br />
					                        <a href="javascript:void(0);" onclick="checkAll('plugin_tree', true); return false;"><?php _e('Check all'); ?></a> &middot;
					                        <a href="javascript:void(0);" onclick="checkAll('plugin_tree', false); return false;"><?php _e('Uncheck all'); ?></a>
					                        
					                        <br />

					                        <ul id="plugin_tree"><?php CategoryForm::categories_tree($categories, $selected); ?></ul>
										</li>
									</ul>
								</li>
                        	</ul>
                        </ul>

                    	
                    </div>
				</div>

				<div class="form-row">
					<div class="form-label"><?php _e('Position (*)', BANNERS_PREF); ?></div>
					<div class="form-controls">
						<div class="select-box undefined">
							<div class="form-label-checkbox">
								<?php if ($positions) : ?>
								<select name="fk_i_position_id" id="fk_i_position_id" style="opacity: 0;">
									<?php foreach($positions as $position) : ?>
									<option value="<?php echo $position['pk_i_id']; ?>" <?php if (isset($bannerToUpdate['fk_i_position_id'])) echo get_html_selected($bannerToUpdate['fk_i_position_id'], $position['pk_i_id']); ?>><?php echo $position['i_sort_id']; ?> <?php if ($position['s_title'] !== '') echo ' - '.$position['s_title']; ?></option>
									<?php endforeach; ?>
								</select>
								<?php endif; ?>
								<a href="#" onclick="set_position();return false;"><?php _e('Add (+)', BANNERS_PREF); ?></a>
							</div>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-label"><?php _e('Advertiser (*)', BANNERS_PREF); ?></div>
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
								<a href="#" onclick="set_advertiser();return false;"><?php _e('Add (+)', BANNERS_PREF); ?></a>
							</div>
						</div>
					</div>
				</div>

				<div class="form-row">
                    <div class="form-label"><?php _e('URL'); ?></div>
                    <div class="form-controls"><input type="text" class="xlarge" name="s_url" value="<?php if ($bannerToUpdate && isset($bannerToUpdate['s_url']) && $bannerToUpdate['s_url']) echo $bannerToUpdate['s_url']; ?>"></div>
                </div>

				<div class="form-row">
					<div class="form-label"><?php _e('Banner', BANNERS_PREF); ?></div>
					<div class="form-controls">
						<div class="form-label-checkbox">
							<label><input name="b_image" type="radio" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] || !$bannerToUpdate) echo 'checked="checked"'; ?> value="1"><?php _e('Image'); ?></label> 
							<label><input name="b_image" type="radio" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && !$bannerToUpdate['b_image']) echo 'checked="checked"'; ?> value="0"><?php _e('Script'); ?></label>
						</div>
						<div class="desc bannertype1" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] <= 0) echo 'style="display: none;"'; ?>>
                            <input type="file" name="banner" id="banner" /> 
                            <?php if ($bannerToUpdate && isset($bannerToUpdate['s_name']) && isset($bannerToUpdate['s_extension'])) echo '<a href="#" onclick="show_banner(\''.BANNERS_ROUTE_SOURCES.$bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension'].'\');return false;">'.__('View', BANNERS_PREF).'</a>'; ?>
                        </div>
                        <div class="desc bannertype0" <?php if (!$bannerToUpdate || $bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] >= 1) echo 'style="display: none;"'; ?>>
                            <textarea name="s_script"><?php if ($bannerToUpdate && isset($bannerToUpdate['s_script'])) : ?><?php echo htmlentities($bannerToUpdate['s_script']); ?><?php else: ?><?php echo htmlentities('<a href="{URL}">'.__('Click me!', BANNERS_PREF).'</a>'); ?><?php endif; ?></textarea>
                            <br>
                            <strong><?php _e('Optional:', BANNERS_PREF); ?></strong> <?php _e('You can use {URL} tag to count clicks from URL field if you wish.', BANNERS_PREF); ?>
                        </div>
					</div>
				</div>

                <div class="form-row desc bannertype1" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] <= 0) echo 'style="display: none;"'; ?>>
                    <div class="form-label"><?php _e('Title'); ?></div>
                    <div class="form-controls"><input type="text" class="xlarge" name="s_title" value="<?php if ($bannerToUpdate && isset($bannerToUpdate['s_title']) && $bannerToUpdate['s_title']) echo $bannerToUpdate['s_title']; ?>"></div>
                </div>

                <div class="form-row desc bannertype1" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] <= 0) echo 'style="display: none;"'; ?>>
                    <div class="form-label"><?php _e('Alt', BANNERS_PREF); ?></div>
                    <div class="form-controls"><input type="text" class="xlarge" name="s_alt" value="<?php if ($bannerToUpdate && isset($bannerToUpdate['s_alt']) && $bannerToUpdate['s_alt']) echo $bannerToUpdate['s_alt']; ?>"></div>
                </div>

                <div class="form-row desc bannertype1" <?php if ($bannerToUpdate && isset($bannerToUpdate['b_image']) && $bannerToUpdate['b_image'] <= 0) echo 'style="display: none;"'; ?>>
                    <div class="form-label"><?php _e('CSS Class', BANNERS_PREF); ?></div>
                    <div class="form-controls"><input type="text" class="xlarge" name="s_css_class" value="<?php if ($bannerToUpdate && isset($bannerToUpdate['s_css_class']) && $bannerToUpdate['s_css_class']) echo $bannerToUpdate['s_css_class']; ?>"></div>
                </div>

                <div class="form-row">
                    <div class="form-label"><?php _e('From', BANNERS_PREF); ?></div>
                    <div class="form-controls"><input id="dt_from_date" type="text" class="xlarge" name="dt_from_date" value="<?php if (isset($bannerToUpdate['dt_from_date'])) echo $bannerToUpdate['dt_from_date']; ?>" placeholder="<?php echo todaydate(null, null, '00:00:00'); ?>" autocomplete="off"></div>
                </div>

                <div class="form-row">
                    <div class="form-label"><?php _e('To', BANNERS_PREF); ?></div>
                    <div class="form-controls"><input id="dt_to_date" type="text" class="xlarge" name="dt_to_date" value="<?php if (isset($bannerToUpdate['dt_to_date'])) echo $bannerToUpdate['dt_to_date']; ?>" placeholder="<?php echo todaydate(1, 'month', '00:00:00'); ?>" autocomplete="off"></div>
                </div>

                <div class="form-row">
                    <div class="form-controls"><label><input type="checkbox" <?php echo (!$bannerToUpdate || isset($bannerToUpdate['b_active']) && $bannerToUpdate['b_active']) ? 'checked="true"' : ''; ?> name="b_active" value="1"> <?php _e('Activate this banner.', BANNERS_PREF); ?></label></div>
                </div>
			</div>

			<div class="grid-row grid-50 text-left">
				<div class="form-row">
					<div class="form-label"><?php _e('Color in the calendar', BANNERS_PREF); ?></div>
					<div class="form-controls">
                        <input type="color" name="s_color" value="<?php if ($bannerToUpdate) echo $bannerToUpdate['s_color']; ?>">
                    </div>
				</div>

                <div class="form-row center">
                	<div class="text-center fieldset-calendar" style="width: fit-content">
                		<fieldset>
                			<legend><?php _e('Calendar', BANNERS_PREF); ?></legend>
                			<div id="show-calendar-content" style="width: fit-content"></div>
                		</fieldset>
                	</div>
                </div>

                <?php if ($bannerToUpdate && $bannerToUpdate['dt_date'] != 0) : ?>
				<div class="form-row">
					<div class="form-label"><?php _e('Added', BANNERS_PREF); ?></div>
					<div class="form-controls">
						<div class="select-box undefined">
							<div class="form-label-checkbox"><?php echo osc_format_date($bannerToUpdate['dt_date'], osc_date_format() . ' ' . osc_time_format()); ?></div>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<?php if ($bannerToUpdate && $bannerToUpdate['dt_update'] != 0) : ?>
                <div class="form-row">
                    <div class="form-label"><?php _e('Last update'); ?></div>
                    <div class="form-controls">
                        <div class="select-box undefined">
                            <div class="form-label-checkbox"><?php echo osc_format_date($bannerToUpdate['dt_update'], osc_date_format() . ' ' . osc_time_format()); ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($bannerToUpdate && isset($bannerToUpdate['pk_i_id'])) : ?>
                <div class="form-row">
                	<div class="form-label"><?php _e('Clicks', BANNERS_PREF); ?></div>
                	<div class="form-controls">
                		<div class="select-box undefined">
                			<div class="form-label-checkbox"><?php echo Banners::newInstance()->countClicksByBannerId($bannerToUpdate['pk_i_id']); ?></div>
                		</div>
                	</div>
                </div>
            	<?php endif; ?>

			</div>

			<div class="clear"></div>

			<div class="form-actions">
				<a class="btn" href="<?php echo adminReturnBack('banners-admin-advertisers'); ?>"><?php _e('Cancel and go back', BANNERS_PREF); ?></a>
				<?php if ($bannerToUpdate && isset($bannerToUpdate['pk_i_id'])) : ?>
				<a class="btn btn-red" href="#" onclick="delete_dialog(<?php echo $bannerToUpdate['pk_i_id']; ?>);return false;"><?php _e('Delete'); ?></a>
				<?php endif; ?>
				<input type="submit" value="<?php echo ($bannerToUpdate) ? __('Update banner', BANNERS_PREF) : __('Add new banner', BANNERS_PREF); ?>" class="btn btn-submit">
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

<!-- Dialog when it want delete a banner -->
<form id="dialog-banner-delete" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Delete banner', BANNERS_PREF)); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="banners-admin" />
    <input type="hidden" name="plugin_action" value="delete" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to delete this banner?', BANNERS_PREF); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-banner-delete').dialog('close');"><?php _e('Cancel'); ?></a>
            <input id="banner-delete-submit" type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
	$("#plugin_tree, #plugin_sections").treeview({
	    animated: "fast",
	    collapsed: true
	});

    $("input[name$='b_image']").click(function() {
        var test = $(this).val();
        $("div.desc").hide();
        $(".bannertype"+test).show();
    });

    $('#dt_from_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#dt_to_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    /**
     * jQuery get selected option value (text and attribute 'value') 
     * From: https://stackoverflow.com/questions/13089944/jquery-get-selected-option-value-not-the-text-but-the-attribute-value 
     */

    // Ways to retrieve selected option and text outside handler
	var positionId 		= $('#fk_i_position_id option').filter(':selected').val(); 	//console.log('Selected option value ' + positionId); 
	var positionSort 	= $('#fk_i_position_id option').filter(':selected').text(); //console.log('Selected option text ' + positionSort);
	if (positionId > 0) show_calendar(positionId);

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

	// dialog delete
	$("#dialog-banner-delete").dialog({
		autoOpen: false,
		modal: true
	});
});

// check all each checkbox
function checkAll(id, check) {
    aa = $('#' + id + ' input[type=checkbox]').each(function() {
        $(this).prop('checked', check);
    });
}

function checkCat(id, check) {
    aa = $('#cat' + id + ' input[type=checkbox]').each(function() {
        $(this).prop('checked', check);
    });
}

<?php if ($bannerToUpdate && file_exists(BANNERS_FOLDER_SOURCES.$bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension'])) : ?>
function show_banner(img) {
    $('#image_banner').prop('src', img);
    $('#show-banner').dialog('open');
};
<?php endif; ?>

// dialog delete function
function delete_dialog(item_id) {
    $("#dialog-banner-delete input[name='id[]']").attr('value', item_id);
    $("#dialog-banner-delete").dialog('open');
    return false;
}
</script>