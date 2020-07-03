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

$advertiserToUpdate = __get('advertiserToUpdate');
?>

<style type="text/css">
input[type="text"].bg-text-gray {
    background-color : #d1d1d1;
}
</style>

<?php // banners_admin_menu(); ?>

<!--<form id="new-advertiser" method="post" action="" class="has-form-actions hide">-->
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="banners-admin-advertisers" />
    <input type="hidden" name="plugin_action" value="set_advertiser" />
    <input type="hidden" name="id" value="<?php if (isset($advertiserToUpdate['pk_i_id'])) echo $advertiserToUpdate['pk_i_id']; ?>" />

    <div class="form-horizontal">
        <div class="form-row">
            <div class="form-label"><?php _e("Name", BANNERS_PREF); ?></div>
            <div id="nAdv" class="form-controls">
                <input id="fAdv" type="text" class="xlarge fAdv ui-autocomplete-input" name="s_name" value="<?php if (isset($advertiserToUpdate['fk_i_user_id']) || isset($advertiserToUpdate['s_name'])) echo (get_user_name($advertiserToUpdate['fk_i_user_id'])) ? get_user_name($advertiserToUpdate['fk_i_user_id']) : $advertiserToUpdate['s_name']; ?>"><a id="cAdv" href="#" class="btn btn-mini hide"><?php _e("Clear", BANNERS_PREF); ?></a>
                <input id="fAdvId" name="fk_i_user_id" type="hidden" value="" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-label"><?php _e("Business sector", BANNERS_PREF); ?></div>
            <div class="form-controls"><input type="text" class="xlarge" name="s_business_sector" value="<?php if (isset($advertiserToUpdate['s_business_sector'])) echo $advertiserToUpdate['s_business_sector']; ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-controls">
                <div class="form-label-checkbox">
                    <label><input type="checkbox" name="b_active" value="1" <?php if (!$advertiserToUpdate || isset($advertiserToUpdate['b_active']) && $advertiserToUpdate['b_active']) echo 'checked="true"'; ?>> <?php _e("Active", BANNERS_PREF); ?></label>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn button-close" href="javascript:void(0);" onclick="$('#modal-500px').dialog('close'); clean_modal();"><?php _e('Cancel', BANNERS_PREF); ?></a>
                <input type="submit" value="<?php echo ($advertiserToUpdate) ? __('Update advertiser', BANNERS_PREF) : __('Add new advertiser', BANNERS_PREF); ?>" class="btn btn-submit">
            </div>
        </div>
    </div>
<!--</form>-->

<script>
$(document).ready(function() {
    // add advertiser autocomplete
    $('input[name="s_name"]').attr( "autocomplete", "off" );
    $('#fAdv').autocomplete({
        source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax", //+$('input[name="user"]').val(), // &term=
        minLength: 0,
        select: function( event, ui ) {
            if(ui.item.id=='')
                return false;
            $('#fAdv').attr('disabled', true).addClass('bg-text-gray');
            $('#cAdv').removeClass('hide');
            $('#fAdvId').val(ui.item.id);
        },
        search: function() {
            $('#fAdvId').val('');
        }
    });

    $('.ui-autocomplete').css('zIndex', 10000);

    <?php if ($advertiserToUpdate && $advertiserToUpdate['fk_i_user_id']) { ?>
    $('#fAdvId').val(<?php echo $advertiserToUpdate['fk_i_user_id']; ?>);
    <?php } ?>

    // clear advertiser autocomplete selected
    $("#nAdv").dblclick(function() {
        $('#fAdv').attr('disabled', false).removeClass('bg-text-gray');
        $('#cAdv').addClass('hide');
        $('#fAdvId').val('');
    });

    // clear advertiser autocomplete selected
    $("#cAdv").on('click', function() {
        $('#fAdv').attr('disabled', false).removeClass('bg-text-gray');
        $('#cAdv').addClass('hide');
        $('#fAdvId').val('');
    });

    // Clean iframe after click on x icon to close dialog
    $(".ui-dialog-titlebar-close").click(function() {
        $(".has-form-actions").html('');
    });
});
</script>