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

$positionToUpdate = __get('positionToUpdate');
?>

<input type="hidden" name="page" value="plugins" />
<input type="hidden" name="action" value="renderplugin" />
<input type="hidden" name="route" value="banners-admin-positions" />
<input type="hidden" name="plugin_action" value="set_position" />
<input type="hidden" name="position_id" id="position_id" value="<?php if (isset($positionToUpdate['pk_i_id'])) echo $positionToUpdate['pk_i_id']; ?>" />

<div class="form-horizontal">
    <div class="form-row text-center">
        <label><?php _e("Sort number:", BANNERS_PREF); ?> <input type="text" class="input-small" name="i_sort_id" value="<?php if (isset($positionToUpdate['i_sort_id'])) echo $positionToUpdate['i_sort_id']; ?>"></label>
    </div>

    <?php if ($positionToUpdate) : ?>
    <div class="form-row text-center" id="show-calendar-content"></div>
    <?php endif; ?>

    <div class="form-actions">
        <div class="wrapper">
            <a class="btn btn-mini" href="javascript:void(0);" onclick="$('#modal-300px').dialog('close'); clean_modal();"><?php _e('Close') ?></a>
            <input type="submit" value="<?php ((!$positionToUpdate) ? _e('Add position') : _e('Save change')); ?>" class="btn btn-mini btn-submit">
            <?php if ($positionToUpdate) : ?>
            <a href="#" onclick="delete_position(<?php if (isset($positionToUpdate['pk_i_id'])) echo $positionToUpdate['pk_i_id']; ?>);return false;" class="btn btn-mini btn-red"><?php _e('Delete'); ?></a>
            <?php endif; ?>
        </div>
    </div>        
</div>