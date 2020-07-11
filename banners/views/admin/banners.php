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

$advertisers 	= __get('advertisers');
$positions 		= __get('positions');

$aData          = __get('aData');
$iDisplayLength = __get('iDisplayLength');
$sort           = Params::getParam('sort');
$direction      = Params::getParam('direction');

$columns        = $aData['aColumns'];
$rows           = $aData['aRows'];

$mimes 			= get_banner_mimes();
?>

<?php banners_admin_menu(); ?>

<h2 class="render-title"><?php _e('Manage banners', BANNERS_PREF); ?>
	<a href="<?php echo osc_route_admin_url('banners-admin-set'); ?>" class="btn btn-mini"><?php _e('Add new'); ?></a>
</h2>

<!-- DataTable -->
<div class="relative">
	<div id="users-toolbar" class="table-toolbar">
		<div class="float-right">
			<form method="get" action="<?php echo osc_admin_base_url(true); ?>"  class="inline nocsrf">
				<?php foreach( Params::getParamsAsArray('get') as $key => $value ) : ?>
				<?php if( $key != 'iDisplayLength' ) : ?>
				<input type="hidden" name="<?php echo $key; ?>" value="<?php echo osc_esc_html($value); ?>" />
				<?php endif; ?>
				<?php endforeach; ?>

				<select name="iDisplayLength" class="select-box-extra select-box-medium float-left" onchange="this.form.submit();" >
                    <option value="10"><?php printf(__('%d Banners', BANNERS_PREF), 10); ?></option>
                    <option value="25" <?php if ( Params::getParam('iDisplayLength') == 25 ) echo 'selected'; ?> ><?php printf(__('%d Banners', BANNERS_PREF), 25); ?></option>
                    <option value="50" <?php if ( Params::getParam('iDisplayLength') == 50 ) echo 'selected'; ?> ><?php printf(__('%d Banners', BANNERS_PREF), 50); ?></option>
                    <option value="100" <?php if ( Params::getParam('iDisplayLength') == 100 ) echo 'selected'; ?> ><?php printf(__('%d Banners', BANNERS_PREF), 100); ?></option>
                </select>
			</form>

			<form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="shortcut-filters" class="inline nocsrf">
				<input type="hidden" name="page" value="plugins" />
				<input type="hidden" name="action" value="renderplugin" />
				<input type="hidden" name="route" value="banners-admin" />

				<a id="btn-display-filters" href="#" class="btn"><?php _e('Show filters'); ?></a>
			</form>
		</div>
	</div><!-- /.table-toolbar -->

	<form id="datatablesForm" method="post" action="<?php echo osc_route_admin_url('banners-admin'); ?>">
		<input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="route" value="banners-admin" />

        <!-- Bulk actions -->
        <div id="bulk-actions">
            <label>
                <?php osc_print_bulk_actions('bulk_actions', 'plugin_action', __get('bulk_options'), 'select-box-extra'); ?>
                <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ); ?>" />
            </label>
        </div>

		<div class="table-contains-actions">
			<table class="table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<?php foreach($columns as $k => $v) {
							echo '<th class="col-'.$k.' '.($sort==$k?($direction=='desc'?'sorting_desc':'sorting_asc'):'').'">'.$v.'</th>';
						}; ?>
					</tr>
				</thead>
				<tbody>
				<?php if( count($rows) > 0 ) { ?>
					<?php foreach($rows as $key => $row) {
						$status = $row['status'];
						$row['status'] = osc_apply_filter('datatable_banners_status_text', $row['status']);
						?>
						<tr class="<?php echo osc_apply_filter('datatable_banners_status_class',  $status); ?>">
							<?php foreach($row as $k => $v) { ?>
							<td class="col-<?php echo $k; ?>"><?php echo $v; ?></td>
							<?php }; ?>
						</tr>
					<?php }; ?>
				<?php } else { ?>
					<tr>
						<td colspan="<?php echo count($columns)+1; ?>" class="text-center">
							<p><?php _e('No data available in table'); ?></p>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<div id="table-row-actions"></div> <!-- used for table actions -->
		</div>
	</form>
</div><!-- /.relative -->

<!-- DataTable pagination -->
<?php
function showingResults(){
    $aData = __get('aData');
    echo '<ul class="showing-results"><li><span>'.osc_pagination_showing((Params::getParam('iPage')-1)*$aData['iDisplayLength']+1, ((Params::getParam('iPage')-1)*$aData['iDisplayLength'])+count($aData['aRows']), $aData['iTotalDisplayRecords'], $aData['iTotalRecords']).'</span></li></ul>';
}
osc_add_hook('before_show_pagination_admin','showingResults');
osc_show_pagination_admin($aData);
?>

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

<!-- Dialog when it want activate a banner -->
<form id="dialog-banner-activate" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Activate banner', BANNERS_PREF)); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="banners-admin" />
    <input type="hidden" name="plugin_action" value="activate" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to activate this banner?', BANNERS_PREF); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-banner-activate').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="banner-activate-submit" type="submit" value="<?php echo osc_esc_html( __('Activate') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog when it want deactivate a banner -->
<form id="dialog-banner-deactivate" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Deactivate banner', BANNERS_PREF)); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="banners-admin" />
    <input type="hidden" name="plugin_action" value="deactivate" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to deactivate this banner?', BANNERS_PREF); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-banner-deactivate').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="banner-deactivate-submit" type="submit" value="<?php echo osc_esc_html( __('Deactivate') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog for bulk actions of toolbar -->
<div id="dialog-bulk-actions" title="<?php _e('Bulk actions'); ?>" class="has-form-actions hide">
    <div class="form-horizontal">
        <div class="form-row"></div>
        <div class="form-actions">
            <div class="wrapper">
                <a id="bulk-actions-cancel" class="btn" href="javascript:void(0);"><?php _e('Cancel'); ?></a>
                <a id="bulk-actions-submit" href="javascript:void(0);" class="btn btn-red" ><?php echo osc_esc_html( __('Delete') ); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<!-- Form of 'Show filters' -->
<form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="display-filters" class="has-form-actions hide nocsrf">
	<input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="banners-admin" />

    <input type="hidden" name="iDisplayLength" value="<?php echo Params::getParam('iDisplayLength'); ?>" />

    <div class="form-horizontal">
    	<div class="grid-system">

    		<!-- Grid left -->
    		<div class="grid-row grid-50">
    			<div class="row-wrapper">
    				<?php if ($advertisers) : ?>
    					<div class="form-row">
    						<div class="form-label"><?php _e('Advertiser', BANNERS_PREF); ?></div>
    						<div class="form-controls">
    							<select name="advertiserId">
    								<option value="" <?php echo ((Params::getParam('advertiserId') == '') ? 'selected="selected"' : '' ); ?>><?php _e('Choose advertiser', BANNERS_PREF); ?></option>
    								<?php foreach ($advertisers as $advertiser) : ?>
									<?php if ($advertiser['s_business_sector']) : ?>
		                                <option value="<?php echo $advertiser['pk_i_id']; ?>" <?php echo get_html_selected(Params::getParam('advertiserId'), $advertiser['pk_i_id']); ?>><?php echo ($advertiser['fk_i_user_id']) ? get_user_name($advertiser['fk_i_user_id']).' ('.get_user_email($advertiser['fk_i_user_id']).') ' : $advertiser['s_name']; ?> - <?php echo $advertiser['s_business_sector']; ?></option>
		                            <?php else : ?>
		                                <option value="<?php echo $advertiser['pk_i_id']; ?>" <?php echo get_html_selected(Params::getParam('advertiserId'), $advertiser['pk_i_id']); ?>><?php echo ($advertiser['fk_i_user_id']) ? get_user_name($advertiser['fk_i_user_id']).' ('.get_user_email($advertiser['fk_i_user_id']).') ' : $advertiser['s_name']; ?></option>
		                            <?php endif; ?>
    								<?php endforeach; ?>
    							</select>
    						</div>
    					</div>
    				<?php endif; ?>

    				<?php if ($positions) : ?>
    					<div class="form-row">
    						<div class="form-label"><?php _e('Position', BANNERS_PREF); ?></div>
    						<div class="form-controls">
    							<select name="positionId">
    								<option value="" <?php echo ( (Params::getParam('positionId') == '') ? 'selected="selected"' : '' ); ?>><?php _e('Choose position', BANNERS_PREF); ?></option>
    								<?php foreach ($positions as $position) : ?>
    								<option value="<?php echo $position['pk_i_id']; ?>" <?php echo get_html_selected(Params::getParam('positionId'), $position['pk_i_id']); ?>><?php echo $position['i_sort_id']; ?><?php if ($position['s_title'] !== '') echo ' - '.$position['s_title']; ?></option>
    								<?php endforeach; ?>
    							</select>
    						</div>
    					</div>
    				<?php endif; ?>

    				<div class="form-row">
                        <div class="form-label">
                            <?php _e('URL'); ?>
                        </div>
                        <div class="form-controls">
                            <input name="s_url" type="text" value="<?php echo osc_esc_html(Params::getParam('s_url')); ?>" />
                        </div>
                    </div>

					<div class="form-row">
						<div class="form-label"><?php _e('Banner type', BANNERS_PREF); ?></div>
						<div class="form-controls">
							<select name="s_type">
								<option value="" <?php echo ( (Params::getParam('s_type') == '') ? 'selected="selected"' : '' )?>><?php _e('Choose mime', BANNERS_PREF); ?></option>
								<?php foreach ($mimes as $mime) : ?>
								<option value="<?php echo $mime; ?>" <?php echo get_html_selected(Params::getParam('s_type'), $mime); ?>><?php echo strtoupper($mime); ?></option>
								<?php endforeach; ?>
								<option value="script" <?php echo ( (Params::getParam('s_type') == 'script') ? 'selected="selected"' : '' )?>>SCRIPT</option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="form-label"><?php _e('From date', BANNERS_PREF); ?></div>
						<div class="form-controls">
							<input id="fromDate" type="text" class="xlarge" name="fromDate" value="<?php echo Params::getParam('fromDate'); ?>" placeholder="<?php echo todaydate(null, null, '00:00:00'); ?>">
							<select name="fromDateControl">
								<option value="equal" <?php echo ( (Params::getParam('fromDateControl') == '=') ? 'selected="selected"' : '' )?>>=</option>
								<option value="greater" <?php echo ( (Params::getParam('fromDateControl') == '>') ? 'selected="selected"' : '' )?>>></option>
								<option value="greater_equal" <?php echo ( (Params::getParam('fromDateControl') == '>=') ? 'selected="selected"' : '' )?>>>=</option>
								<option value="less" <?php echo ( (Params::getParam('fromDateControl') == '<') ? 'selected="selected"' : '' )?>><</option>
								<option value="less_equal" <?php echo ( (Params::getParam('fromDateControl') == '<=') ? 'selected="selected"' : '' )?>><=</option>
								<option value="not_equal" <?php echo ( (Params::getParam('fromDateControl') == '!=') ? 'selected="selected"' : '' )?>>!=</option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="form-label"><?php _e('To date', BANNERS_PREF); ?></div>
						<div class="form-controls">
							<input id="toDate" type="text" class="xlarge" name="toDate" value="<?php echo Params::getParam('toDate'); ?>" placeholder="<?php echo todaydate(1, 'month', '00:00:00'); ?>">
							<select name="toDateControl">
								<option value="equal" <?php echo ( (Params::getParam('toDateControl') == '=') ? 'selected="selected"' : '' )?>>=</option>
								<option value="greater" <?php echo ( (Params::getParam('toDateControl') == '>') ? 'selected="selected"' : '' )?>>></option>
								<option value="greater_equal" <?php echo ( (Params::getParam('toDateControl') == '>=') ? 'selected="selected"' : '' )?>>>=</option>
								<option value="less" <?php echo ( (Params::getParam('toDateControl') == '<') ? 'selected="selected"' : '' )?>><</option>
								<option value="less_equal" <?php echo ( (Params::getParam('toDateControl') == '<=') ? 'selected="selected"' : '' )?>><=</option>
								<option value="not_equal" <?php echo ( (Params::getParam('toDateControl') == '!=') ? 'selected="selected"' : '' )?>>!=</option>
							</select>
						</div>
					</div>
    			</div><!-- /.row-wrapper -->
    		</div><!-- /.grid-row .grid-50 -->

    		<!-- Grid right -->
			<div class="grid-row grid-50">
				<div class="row-wrapper">
					<div class="form-row">
						<div class="form-label"><?php _e('Publication date', BANNERS_PREF); ?></div>
						<div class="form-controls">
							<input id="date" type="text" class="xlarge" name="date" value="<?php echo Params::getParam('date'); ?>" placeholder="<?php echo todaydate(null, null, '00:00:00'); ?>">
							<select name="dateControl">
								<option value="equal" <?php echo ( (Params::getParam('dateControl') == '=') ? 'selected="selected"' : '' )?>>=</option>
								<option value="greater" <?php echo ( (Params::getParam('dateControl') == '>') ? 'selected="selected"' : '' )?>>></option>
								<option value="greater_equal" <?php echo ( (Params::getParam('dateControl') == '>=') ? 'selected="selected"' : '' )?>>>=</option>
								<option value="less" <?php echo ( (Params::getParam('dateControl') == '<') ? 'selected="selected"' : '' )?>><</option>
								<option value="less_equal" <?php echo ( (Params::getParam('dateControl') == '<=') ? 'selected="selected"' : '' )?>><=</option>
								<option value="not_equal" <?php echo ( (Params::getParam('dateControl') == '!=') ? 'selected="selected"' : '' )?>>!=</option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="form-label"><?php _e('Last update'); ?></div>
						<div class="form-controls">
							<input id="update" type="text" class="xlarge" name="update" value="<?php echo Params::getParam('update'); ?>" placeholder="<?php echo todaydate(null, null, '00:00:00'); ?>">
							<select name="updateControl">
								<option value="equal" <?php echo ( (Params::getParam('updateControl') == '=') ? 'selected="selected"' : '' )?>>=</option>
								<option value="greater" <?php echo ( (Params::getParam('updateControl') == '>') ? 'selected="selected"' : '' )?>>></option>
								<option value="greater_equal" <?php echo ( (Params::getParam('updateControl') == '>=') ? 'selected="selected"' : '' )?>>>=</option>
								<option value="less" <?php echo ( (Params::getParam('updateControl') == '<') ? 'selected="selected"' : '' )?>><</option>
								<option value="less_equal" <?php echo ( (Params::getParam('updateControl') == '<=') ? 'selected="selected"' : '' )?>><=</option>
								<option value="not_equal" <?php echo ( (Params::getParam('updateControl') == '!=') ? 'selected="selected"' : '' )?>>!=</option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="form-label"><?php _e('Status'); ?></div>
						<div class="form-controls">
							<select id="b_active" name="b_active">
								<option value="" <?php echo ( (Params::getParam('b_active') == '') ? 'selected="selected"' : '' )?>><?php _e('Choose an option'); ?></option>
								<option value="1" <?php echo ( (Params::getParam('b_active') == '1') ? 'selected="selected"' : '' )?>><?php _e('ACTIVE', BANNERS_PREF); ?></option>
								<option value="0" <?php echo ( (Params::getParam('b_active') == '0') ? 'selected="selected"' : '' )?>><?php _e('DEACTIVE', BANNERS_PREF); ?></option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="form-label"><?php _e('Order by', BANNERS_PREF); ?></div>
						<div class="form-controls">
							<select name="sort">
								<option value="date" <?php echo ( (Params::getParam('sort') == 'date') ? 'selected="selected"' : '' )?>><?php _e('DATE', BANNERS_PREF); ?></option>
								<option value="update" <?php echo ( (Params::getParam('sort') == 'update') ? 'selected="selected"' : '' )?>><?php _e('UPDATE', BANNERS_PREF); ?></option>
								<option value="from_date" <?php echo ( (Params::getParam('sort') == 'from_date') ? 'selected="selected"' : '' )?>><?php _e('FROM DATE', BANNERS_PREF); ?></option>
								<option value="to_date" <?php echo ( (Params::getParam('sort') == 'to_date') ? 'selected="selected"' : '' )?>><?php _e('TO DATE', BANNERS_PREF); ?></option>
								<option value="position" <?php echo ( (Params::getParam('sort') == 'position') ? 'selected="selected"' : '' )?>><?php _e('POSITION', BANNERS_PREF); ?></option>
							</select>
							<select name="direction">
								<option value="desc" <?php echo ( (Params::getParam('direction') == 'desc') ? 'selected="selected"' : '' )?>><?php _e('DESC', BANNERS_PREF); ?></option>
								<option value="asc" <?php echo ( (Params::getParam('direction') == 'asc') ? 'selected="selected"' : '' )?>><?php _e('ASC', BANNERS_PREF); ?></option>
							</select>
						</div>
					</div>
				</div><!-- /.row-wrapper -->
			</div><!-- /.grid-row .grid-50 -->

			<div class="clear"></div>

    	</div><!-- /.grid-system -->
    </div><!-- /.form-horizontal -->
	<div class="form-actions">
		<div class="wrapper">
			<input id="show-filters" type="submit" value="<?php echo osc_esc_html( __('Apply filters')); ?>" class="btn btn-submit" />
			<a class="btn" href="<?php echo osc_route_admin_url('banners-admin'); ?>"><?php _e('Reset filters'); ?></a>
		</div>
	</div>
</form>

<div id="show-banner" class="has-form-actions hide">
	<div class="form-horizontal">
		<div class="form-row text-center">
			<br>
			<img id="image_banner" src="">
		</div>        
		<div class="form-actions">
			<div class="wrapper"></div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$("#show-banner").dialog({
		autoOpen: false,
		width: "1000px",
		modal: true,
		title: '<?php echo osc_esc_js( __('Show banner', BANNERS_PREF) ); ?>'
	});

	// dialog delete
	$("#dialog-banner-delete").dialog({
		autoOpen: false,
		modal: true
	});

	// Dialog activate
    $("#dialog-banner-activate").dialog({
        autoOpen: false,
        modal: true
    });

    // Dialog deactivate
    $("#dialog-banner-deactivate").dialog({
        autoOpen: false,
        modal: true
    });

	// check_all bulkactions
	$("#check_all").change(function() {
		var isChecked = $(this).prop("checked");
		$('.col-bulkactions input').each( function() {
			if(isChecked == 1) {
				this.checked = true;
			} else {
				this.checked = false;
			}
		});
	});

    // Dialog Bulk actions
    $("#dialog-bulk-actions").dialog({
        autoOpen: false,
        modal: true
    });
    $("#bulk-actions-submit").click(function() {
        $("#datatablesForm").submit();
    });
    $("#bulk-actions-cancel").click(function() {
        $("#datatablesForm").attr('data-dialog-open', 'false');
        $('#dialog-bulk-actions').dialog('close');
    });

    // Dialog bulk actions function
    $("#datatablesForm").submit(function() {
        if( $("#bulk_actions option:selected").val() == "" ) {
            return false;
        }

        if( $("#datatablesForm").attr('data-dialog-open') == "true" ) {
            return true;
        }

        $("#dialog-bulk-actions .form-row").html($("#bulk_actions option:selected").attr('data-dialog-content'));
        $("#bulk-actions-submit").html($("#bulk_actions option:selected").text());
        $("#datatablesForm").attr('data-dialog-open', 'true');
        $("#dialog-bulk-actions").dialog('open');
        return false;
    });

	// dialog filters
	$('#display-filters').dialog({
		autoOpen: false,
		modal: true,
		width: 700,
		title: '<?php echo osc_esc_js( __('Filters') ); ?>'
	});
	$('#btn-display-filters').click(function(){
		$('#display-filters').dialog('open');
		return false;
	});

	$('#fromDate').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$('#toDate').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$('#date').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$('#update').datepicker({
		dateFormat: 'yy-mm-dd'
	});
});

function show_banner(img) {
	$('#image_banner').prop('src', img);
	$('#show-banner').dialog('open');
};

// Note: this function can be moved to modals_form_options
function show_position(id = null, year = null, month = null) {
    $("#modal-300px").html('<div id="show-calendar-content" class="text-center"></div>'); show_calendar(id, year, month);
    $('#modal-300px').dialog('open');
};

// dialog delete function
function delete_dialog(item_id) {
    $("#dialog-banner-delete input[name='id[]']").attr('value', item_id);
    $("#dialog-banner-delete").dialog('open');
    return false;
}

// Dialog activate function
function activate_dialog(item_id) {
    $("#dialog-banner-activate input[name='id[]']").attr('value', item_id);
    $("#dialog-banner-activate").dialog('open');
    return false;
}

// Dialog deactivate function
function deactivate_dialog(item_id) {
    $("#dialog-banner-deactivate input[name='id[]']").attr('value', item_id);
    $("#dialog-banner-deactivate").dialog('open');
    return false;
}
</script>