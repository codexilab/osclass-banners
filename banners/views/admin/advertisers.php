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

$aData          = __get('aData');
$iDisplayLength = __get('iDisplayLength');
$sort           = Params::getParam('sort');
$direction      = Params::getParam('direction');

$columns        = $aData['aColumns'];
$rows           = $aData['aRows'];
?>

<?php banners_admin_menu(); ?>

<h2 class="render-title"><?php _e("Manage advertisers", BANNERS_PREF); ?> <a href="#" onclick="set_advertiser();return false;" class="btn btn-mini"><?php _e("Add new", BANNERS_PREF); ?></a></h2>

<!-- DataTable -->
<div class="relative">
	<div id="users-toolbar" class="table-toolbar">
		<div id="users-toolbar" class="table-toolbar">
	        <div class="float-right">
	            <form method="get" action="<?php echo osc_admin_base_url(true); ?>"  class="inline nocsrf">
	                <?php foreach ( Params::getParamsAsArray('get') as $key => $value ) : ?>
	                <?php if ( $key != 'iDisplayLength' ) : ?>
	                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo osc_esc_html($value); ?>" />
	                <?php endif; ?>
	                <?php endforeach; ?>

	                <select name="iDisplayLength" class="select-box-extra select-box-medium float-left" onchange="this.form.submit();" >
	                    <option value="10"><?php printf(__("%d Advertisers", BANNERS_PREF), 10); ?></option>
	                    <option value="25" <?php if ( Params::getParam('iDisplayLength') == 25 ) echo 'selected'; ?> ><?php printf(__("%d Advertisers", BANNERS_PREF), 25); ?></option>
	                    <option value="50" <?php if ( Params::getParam('iDisplayLength') == 50 ) echo 'selected'; ?> ><?php printf(__("%d Advertisers", BANNERS_PREF), 50); ?></option>
	                    <option value="100" <?php if ( Params::getParam('iDisplayLength') == 100 ) echo 'selected'; ?> ><?php printf(__("%d Advertisers", BANNERS_PREF), 100); ?></option>
	                </select>
	            </form>
	        </div>
	    </div>
	</div>

    <form class="" id="datatablesForm" method="post" action="<?php echo osc_route_admin_url('my-plugin-admin-crud'); ?>">
    	<input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="route" value="banners-admin-advertisers" />

        <!-- Bulk actions -->
        <div id="bulk-actions">
            <label>
                <?php osc_print_bulk_actions('bulk_actions', 'plugin_action', __get('bulk_options'), 'select-box-extra'); ?>
                <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __("Apply", BANNERS_PREF) ); ?>" />
            </label>
        </div>

        <!-- DataTable -->
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
                        $row['status'] = osc_apply_filter('datatable_advertisers_status_text', $row['status']);
                         ?>
                        <tr class="<?php echo osc_apply_filter('datatable_advertisers_status_class',  $status); ?>">
                            <?php foreach($row as $k => $v) { ?>
                                <td class="col-<?php echo $k; ?>"><?php echo $v; ?></td>
                            <?php }; ?>
                        </tr>
                    <?php }; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="<?php echo count($columns)+1; ?>" class="text-center">
                            <p><?php _e("No data available in table", BANNERS_PREF); ?></p>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div id="table-row-actions"></div> <!-- used for table actions -->
        </div>
    </form>
</div>

<!-- DataTable pagination -->
<?php
function showingResults(){
    $aData = __get('aData');
    echo '<ul class="showing-results"><li><span>'.osc_pagination_showing((Params::getParam('iPage')-1)*$aData['iDisplayLength']+1, ((Params::getParam('iPage')-1)*$aData['iDisplayLength'])+count($aData['aRows']), $aData['iTotalDisplayRecords'], $aData['iTotalRecords']).'</span></li></ul>';
}
osc_add_hook('before_show_pagination_admin','showingResults');
osc_show_pagination_admin($aData);
?>

<!-- Modal Windows of Bulk actions dialog delete advertiser -->
<form id="dialog-advertiser-delete" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Delete advertiser', BANNERS_PREF)); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="banners-admin-advertisers" />
    <input type="hidden" name="plugin_action" value="delete" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to delete this advertiser?'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-advertiser-delete').dialog('close');"><?php _e('Cancel'); ?></a>
            <input id="advertiser-delete-submit" type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
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

<script>
    $(document).ready(function() {
        // users autocomplete
        $('input[name="search"]').attr( "autocomplete", "off" );
        $('#user,#fUser').autocomplete({
            source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax", //+$('input[name="user"]').val(), // &term=
            minLength: 0,
            select: function( event, ui ) {
                if(ui.item.id=='')
                    return false;
                $('#userId').val(ui.item.id);
                $('#fUserId').val(ui.item.id);
            },
            search: function() {
                $('#userId').val('');
                $('#fUserId').val('');
            }
        });

        $('.ui-autocomplete').css('zIndex', 10000);

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

        // dialog delete
        $("#dialog-advertiser-delete").dialog({
            autoOpen: false,
            modal: true
        });

        // dialog bulk actions
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
        // dialog bulk actions function
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
        // /dialog bulk actions
    });    

    // dialog delete function
    function delete_dialog(item_id) {
        $("#dialog-advertiser-delete input[name='id[]']").attr('value', item_id);
        $("#dialog-advertiser-delete").dialog('open');
        return false;
    }
</script>
