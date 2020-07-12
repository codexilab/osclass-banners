<?php 
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

if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

$bannerRoutePage 	= banners_route_page();
$bannerRouteParam 	= banners_route_param();
$showURLBanner 		= osc_get_preference('show_url_banner', BANNERS_PREF);
?>

<?php banners_admin_menu(); ?>

<h2 class="render-title"><?php _e('Banner route internal struct', BANNERS_PREF); ?></h2>
<form>
	<input type="hidden" name="page" value="plugins" />
	<input type="hidden" name="action" value="renderplugin" />
	<input type="hidden" name="route" value="banners-admin-settings" />
	<input type="hidden" name="plugin_action" value="done" />

	<div class="form-horizontal">
        <div class="form-row">
            <div class="form-label"><?php _e('Route page', BANNERS_PREF); ?></div>
            <div class="form-controls">
                <input type="text" class="xlarge" id="banner_route_page" name="banner_route_page" value="<?php echo $bannerRoutePage; ?>" autocomplete="off">
            </div>
        </div>
        <div class="form-row">
            <div class="form-label"><?php _e('Route param', BANNERS_PREF); ?></div>
            <div class="form-controls"><input type="text" class="xlarge" id="banner_route_param" name="banner_route_param" value="<?php echo $bannerRouteParam; ?>" autocomplete="off"></div>
        </div>
        <div class="form-row">
            <div class="form-controls"><label><input type="checkbox" <?php echo ($showURLBanner) ? 'checked="true"' : ''; ?> name="show_url_banner" value="1"> <?php _e('Show URL Banner.', BANNERS_PREF); ?></label></div>
        </div>
        <div class="form-row">
        	<div class="form-controls">
        		<span class="help-box"><b><?php _e('Example:', BANNERS_PREF); ?></b>
        		
                <span id="bannerRoutePage"></span><?php echo (osc_rewrite_enabled()) ? '/?' : '&'; ?><span id="bannerRouteParam"></span>=<span id="URLBanner"></span>

        		</span>
        	</div>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="<?php echo adminReturnBack('banners-admin'); ?>"><?php _e('Cancel'); ?></a>
                <input type="submit" value="<?php _e('Apply'); ?>" class="btn btn-submit">
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function() {
    var bannerRoutePage     = $('input[name="banner_route_page"]').val();
    var bannerRouteParam    = $('input[name="banner_route_param"]').val();
    $('#bannerRoutePage').html(bannerRoutePage);
    $('#bannerRouteParam').html(bannerRouteParam);

    $('input[name="banner_route_page"], input[name="banner_route_param"]').on('input', function(){
        bannerRoutePage     = $('input[name="banner_route_page"]').val();
        bannerRouteParam    = $('input[name="banner_route_param"]').val();
        $('#bannerRoutePage').html(bannerRoutePage);
        $('#bannerRouteParam').html(bannerRouteParam);
    });

    if ($('input[name="show_url_banner"]').is(':checked')) {
        $("#URLBanner").html("https://google.com/");
    } else {
        $("#URLBanner").html("sUAjk1t4");
    }

    $('input[name="show_url_banner"]').click(function () {
        if ($(this).is(':checked')) {
            $("#URLBanner").html("https://google.com/");
        } else {
            $("#URLBanner").html("sUAjk1t4");
        }
    });
});
</script>