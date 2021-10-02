<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

	/*
	 * MIT License
	 * 
	 * Copyright (c) 2021 CodexiLab
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

$positions = __get('positions');
?>

<?php banners_admin_menu(); ?>

<h2 class="render-title">
	<?php _e('Positions:', BANNERS_PREF); ?>
    <?php if ($positions) : ?>
    <small>
        <?php foreach ($positions as $position) : ?>
        <a href="#" onclick="set_position(<?php echo $position['pk_i_id']; ?>); show_calendar(<?php echo $position['pk_i_id']; ?>); return false;" class="btn btn-mini"><?php echo $position['i_sort_id']; ?></a>
        <?php endforeach; ?>
    </small>
    <?php endif; ?>
	<a href="#" onclick="set_position();return false;" class="btn btn-mini">+</a>
</h2>

<!-- Modal Windows of Delete position form -->
<form id="delete-position" method="post" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="banners-admin-positions" />
    <input type="hidden" name="plugin_action" value="delete_position" />
    <input type="hidden" name="position_id" id="delete_position" value="" />

    <div class="form-horizontal">
        <div class="form-row text-center">
            <?php _e('Really want to delete the selected position?', BANNERS_PREF); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#delete-position').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="position-delete-submit" type="submit" value="<?php _e('Delete'); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $("#delete-position").dialog({
            autoOpen: false,
            width: "300px",
            modal: true,
            title: '<?php echo osc_esc_js( __('Delete position', BANNERS_PREF) ); ?>'
        });
    });

    function delete_position(id) {
        $('#delete_position').prop('value', id);
        $('#delete-position').dialog('open');
    };
</script>