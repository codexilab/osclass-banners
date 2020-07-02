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

/*
Plugin Name: Banners
Plugin URI: https://github.com/codexilab/osclass-banners
Description: Put banners on the site, in any format and check its display time
Version: 1.0.0
Author: XenoTrue
Author URI: https://github.com/codexilab
Short Name: banners
Plugin update URI: https://github.com/codexilab/osclass-banners
*/

// Paths
define('BANNERS_FOLDER', osc_plugin_folder(__FILE__));
define('BANNERS_PATH', osc_plugins_path() . BANNERS_FOLDER);
define('BANNERS_PREF', basename(BANNERS_FOLDER));

define('BANNERS_FOLDER_SOURCES', BANNERS_PATH . 'uploads/'); // Folder where to it will save the banners
define('BANNERS_ROUTE_SOURCES', WEB_PATH . 'oc-content/plugins/' . BANNERS_FOLDER . 'uploads/');

// Prepare model, controllers and helpers
require_once BANNERS_PATH . "oc-load.php";

// URL routes
osc_add_route('banners-admin', BANNERS_FOLDER.'views/admin/banners', BANNERS_FOLDER.'views/admin/banners', BANNERS_FOLDER.'views/admin/banners.php');
osc_add_route('banners-admin-new', BANNERS_FOLDER.'views/admin/new-banner', BANNERS_FOLDER.'views/admin/new-banner', BANNERS_FOLDER.'views/admin/new-banner.php');
osc_add_route('banners-admin-advertisers', BANNERS_FOLDER.'views/admin/advertisers', BANNERS_FOLDER.'views/admin/advertisers', BANNERS_FOLDER.'views/admin/advertisers.php');
osc_add_route('banners-admin-positions', BANNERS_FOLDER.'views/admin/positions', BANNERS_FOLDER.'views/admin/positions', BANNERS_FOLDER.'views/admin/positions.php');
osc_add_route('banners-admin-settings', BANNERS_FOLDER.'views/admin/settings', BANNERS_FOLDER.'views/admin/settings', BANNERS_FOLDER.'views/admin/settings.php');
osc_add_route(banners_route_page(), banners_route_page().'/', banners_route_page().'/', osc_plugin_folder(__FILE__).'views/web/banner-url.php');

// Headers in the admin panel
osc_add_hook('admin_menu_init', function() {
	osc_add_admin_submenu_divider(
		"plugins", __("Banners", BANNERS_PREF), BANNERS_PREF, "administrator"
    );

	osc_add_admin_submenu_page(
		"plugins", __("Manage banners", BANNERS_PREF), osc_route_admin_url("banners-admin"), "banners-admin", "administrator"
	);

	osc_add_admin_submenu_page(
		"plugins", __("Settings", BANNERS_PREF), osc_route_admin_url("banners-admin-settings"), "banners-admin-settings", "administrator"
	);
});

// Add custom CSS Styles in oc-admin
function banners_custom_css_admin() {
	if (Params::getParam('page') == "plugins") {
		osc_enqueue_style('banners-css', osc_base_url() . 'oc-content/plugins/'. BANNERS_FOLDER. 'assets/css/admin/main.css');
	}
}
osc_add_hook('init_admin', 'banners_custom_css_admin');

// Load admin controllers, depend of url route
function banners_admin_controllers() {
	switch (Params::getParam("route")) {
		case 'banners-admin':
			$filter = function($string) {
                return __("Banners", BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter("admin_title", $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter("custom_plugin_title", $filter);

            $do = new CAdminBanners();
            $do->doModel();
			break;

		case 'banners-admin-new':
			$filter = function($string) {
                return __("New banner - Banners", BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter("admin_title", $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter("custom_plugin_title", $filter);

            $do = new CAdminBannersNew();
            $do->doModel();
			break;

		case 'banners-admin-positions':
			$filter = function($string) {
                return __("Positions - Banners", BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter("admin_title", $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter("custom_plugin_title", $filter);

            $do = new CAdminBannersPositions();
            $do->doModel();
			break;

		case 'banners-admin-advertisers':
			$filter = function($string) {
                return __("Advertisers - Banners", BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter("admin_title", $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter("custom_plugin_title", $filter);

            $do = new CAdminBannersAdvertisers();
            $do->doModel();
			break;

		case 'banners-admin-settings':
			$filter = function($string) {
                return __("Settings - Banners", BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter("admin_title", $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter("custom_plugin_title", $filter);

            $do = new CAdminBannersSettings();
            $do->doModel();
			break;

		case banners_route_page():
            $do = new CWebBannersURL();
        	$do->doModel();
			break;
	}
}
osc_add_hook('renderplugin_controller', 'banners_admin_controllers');

/*function banners_web_controllers() {
	if (Params::getParam("route") == banners_route_page()) {
		$do = new CWebBannersURL();
        $do->doModel();
	}
}
osc_add_hook('renderplugin_controller', 'banners_web_controllers');*/

/**
 * The content of this function it will show by ajax request on this url:
 * <?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=position_calendar
 */
/*function position_calendar() {
    $positionId = (isset($_GET['position']) && $_GET['position']) ? $_GET['position'] : 0;
    $banners = Banners::newInstance()->getByPositionId($positionId);

    // Si no se ha seleccionado mes, ponemos el actual y el año
    $month = (isset($_GET['month']) && $_GET['month']) ? $_GET['month'] : date("Y-m");

    // for, than build the calendar
    $week = 1;
    for ($i = 1; $i <= date('t', strtotime($month)); $i++) {
        $day_week = date('N', strtotime($month.'-'.$i));
        $calendar[$week][$day_week] = $i;
        if ($day_week == 7) $week++;
    }

    // save an array each one of the days of a interval, to be compared with days of calendar
    function daysinterval($since, $until, $color) {
        $array = array();
        $date = $since;
        while(strtotime($date) <= strtotime($until)) {
            $array[]['date'] = $date;
            $date = date("Y-m-j", strtotime($date . " + 1 day"));
        }
        $array['color'] = $color;
        return $array;
    }
    $intervals = array();
    foreach ($banners as $banner) {
        $intervals[] = daysinterval($banner['dt_since_date'], $banner['dt_until_date'], $banner['s_color']);
    }

    // comparison
    $comp = array();

    function check_values(&$value = null, $key = null) {
        return (!$value) ? false : true;
    }
    ?>

    <h2 class="render-title">Position <?php echo banners_sort_position($positionId); ?></h2>

    <table class="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <td colspan="7"><b><?php echo strftime('%B %Y', strtotime($month)); ?></b></td>
            </tr>
            <tr>
                <td colspan="7">
                    <div style="float: left; width: 33.333333333%"><a href="#" id="calendar-previous-button">&laquo; Previous</a></div>
                    <div style="float: left; width: 33.333333333%"><a href="#" id="calendar-current-button">Current</a></div>
                    <div style="float: left; width: 33.333333333%"><a href="#" id="calendar-next-button">Next &raquo;</a></div>
                </td>
            </tr>
            <tr>
                <td>Mon</td>
                <td>Tue</td>
                <td>Wed</td>
                <td>Thu</td>
                <td>Frid</td>
                <td>Sat</td>        
                <td>Sun</td>
            </tr>
        </thead>
        
        <tbody>
        <?php foreach ($calendar as $days) : ?>
            <tr>
            <?php for ($i = 1; $i <= 7; $i++) : ?>
                <?php for ($j=0; $j < count($intervals); $j++) : // run intervals to be compared ?>
                	<?php @$comp[$j] = in_array($month.'-'.$days[$i], array_column($intervals[$j], 'date')); ?>
	                <?php if ($comp[$j]) : ?>
	                <td style="background: <?php echo $intervals[$j]['color']; ?>; color: white">
	                <?php endif; ?>
                <?php endfor; // end for ?>

                <?php if (!array_filter($comp, 'check_values')) : // if the comparisions have negative results ?>
                	<td>
                <?php endif; ?>

                <?php echo isset($days[$i]) ? $days[$i] : ''; ?>
                </td>
            <?php endfor; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php 
}
osc_add_hook("ajax_position_calendar", "position_calendar");*/

function modal_form_options() {
	if (Params::getParam("page") == "plugins") {
		include BANNERS_PATH . 'parts/admin/modal_form_options.php';
	}
}
osc_add_hook('admin_footer', 'modal_form_options');

/**
 * The content of this function it will show by ajax request on this url:
 * <?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=banners_controller_requests
 */
function banners_controller_requests() {
	$do = new CBannersAdminAjax();
	$do->doModel();
}
osc_add_hook("ajax_banners_controller_requests", "banners_controller_requests");


// 'Configure' link
function banners_configure_admin_link() {
	osc_redirect_to(osc_route_admin_url('banners-admin-settings'));
}
// Show 'Configure' link at plugins table
osc_add_hook(osc_plugin_path(__FILE__).'_configure', 'banners_configure_admin_link');


// Call uninstallation method from model (model/Banners.php)
function banners_uninstall() {
	Banners::newInstance()->uninstall();
}
// Show an Uninstall link at plugins table
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'banners_uninstall');


// Call the process of installation method
function banners_install() {
	Banners::newInstance()->install();
}
// Register plugin's installation
osc_register_plugin(osc_plugin_path(__FILE__), 'banners_install');