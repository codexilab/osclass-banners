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

$positionToUpdate = __get('positionToUpdate');
?>

<?php if (!$positionToUpdate) : ?>
<div class="text-right"><h2 class="render-title"><?php _e('Add new position', BANNERS_PREF); ?></h2></div>
<?php endif; ?>

<input type="hidden" name="page" value="plugins" />
<input type="hidden" name="action" value="renderplugin" />
<input type="hidden" name="route" value="banners-admin-positions" />
<input type="hidden" name="plugin_action" value="set_position" />
<input type="hidden" name="position_id" id="position_id" value="<?php if (isset($positionToUpdate['pk_i_id'])) echo $positionToUpdate['pk_i_id']; ?>" />

<div class="form-horizontal">
    <div class="grid-system">
        <div class="grid-row grid-<?php echo ($positionToUpdate) ? '50' : '100'; ?>">
            <div class="form-row">
                <?php _e('Title'); ?>
                <input type="text" class="xlarge" name="s_title" value="<?php if (isset($positionToUpdate['s_title'])) echo $positionToUpdate['s_title']; ?>">
            </div>

            <div class="form-row">
                <label><?php _e('Sort number', BANNERS_PREF); ?> <input type="text" class="input-small" name="i_sort_id" value="<?php if (isset($positionToUpdate['i_sort_id'])) echo $positionToUpdate['i_sort_id']; ?>"></label>
            </div>
        </div>

        <?php if ($positionToUpdate) : ?>
        <div class="grid-row grid-50 text-left">
            <div class="form-row text-center" id="show-calendar-content"></div>
        </div>
        <?php endif; ?>
    </div><!-- /.grid-system -->

    <div class="clear"></div>

    <?php if ($positionToUpdate) : ?>
    <div class="form-row center">
        <strong><?php _e('PHP code:', BANNERS_PREF); ?></strong>
        <pre>&lt;?php osc_run_hook('banners_position_<?php if (isset($positionToUpdate['i_sort_id'])) echo $positionToUpdate['i_sort_id']; ?>'); ?&gt;</pre>
        <?php _e('Put this script into the theme.', BANNERS_PREF); ?>
    </div>
    <?php endif; ?>

    <div class="form-actions">
        <div class="wrapper">
            <a class="btn btn-mini button-close" href="javascript:void(0);" onclick="$('#modal-700px, #modal-400px').dialog('close');"><?php _e('Close') ?></a>
            <input type="submit" value="<?php ((!$positionToUpdate) ? _e('Add position', BANNERS_PREF) : _e('Save changes')); ?>" class="btn btn-mini btn-submit">
            <?php if ($positionToUpdate) : ?>
            <a href="#" onclick="delete_position(<?php if (isset($positionToUpdate['pk_i_id'])) echo $positionToUpdate['pk_i_id']; ?>);return false;" class="btn btn-mini btn-red"><?php _e('Delete'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>