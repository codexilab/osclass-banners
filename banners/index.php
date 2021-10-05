<?php
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

/*
Plugin Name: Banners
Plugin URI: https://github.com/codexilab/osclass-banners
Description: Put banners on the site, in any format and check its display time
Version: 1.0.4
Author: CodexiLab
Author URI: https://github.com/codexilab
Short Name: banners
Plugin update URI: https://github.com/codexilab/osclass-banners/
*/

// Paths
define('BANNERS_FOLDER', osc_plugin_folder(__FILE__));
define('BANNERS_PATH', osc_plugins_path() . BANNERS_FOLDER);
define('BANNERS_PREF', basename(BANNERS_FOLDER));

define('BANNERS_FOLDER_SOURCES', BANNERS_PATH . 'uploads/'); // Folder where to it will save the banners
define('BANNERS_ROUTE_SOURCES', WEB_PATH . 'oc-content/plugins/' . BANNERS_FOLDER . 'uploads/');

// Prepare model, controllers and helpers
require_once BANNERS_PATH . 'oc-load.php';

// URL routes
foreach (banners_plugin_routes() as $route) {
	osc_add_route($route['slug'], $route['regexp'], $route['url'], $route['file']);
}
osc_add_route(banners_route_page(), banners_route_page().'/', banners_route_page().'/', osc_plugin_folder(__FILE__).'views/web/banner-url.php');

// Add custom CSS Styles in oc-admin
function banners_custom_css_admin() {
	if (Params::getParam('page') == 'plugins' && in_array(Params::getParam('route'), banners_slug_routes())) {
		osc_enqueue_script('jquery-treeview');
		osc_enqueue_style('banners-css', osc_base_url() . 'oc-content/plugins/'.BANNERS_FOLDER.'assets/css/admin/main.css');
	}
}
osc_add_hook('init_admin', 'banners_custom_css_admin');

// Add responsive CSS styles for the banners widths
function banners_widths_css_web() {
	if (!OC_ADMIN) {
		osc_enqueue_style('banners-widths-css', osc_base_url() . 'oc-content/plugins/'.BANNERS_FOLDER.'assets/css/web/banners-widths.css');
	}
}
osc_add_hook('init', 'banners_widths_css_web');

// Headers in the admin panel
osc_add_hook('admin_menu_init', function() {
	$routes = banners_plugin_routes();
	osc_add_admin_submenu_divider(
		'plugins', __('Banners', BANNERS_PREF), BANNERS_PREF, 'administrator'
    );

	osc_add_admin_submenu_page(
		'plugins', __('Manage banners', BANNERS_PREF), osc_route_admin_url('banners-admin'), 'banners-admin', 'administrator'
	);

	osc_add_admin_submenu_page(
		'plugins', __('Settings'), osc_route_admin_url('banners-admin-settings'), 'banners-admin-settings', 'administrator'
	);
});

// Load admin controllers, depend of url route
function banners_admin_controllers() {
	switch (Params::getParam("route")) {
		case 'banners-admin':
			$filter = function($string) {
                return __('Banners', BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter('admin_title', $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter('custom_plugin_title', $filter);

            $do = new CAdminBanners();
            $do->doModel();
			break;

		case 'banners-admin-set':
			$filter = function($string) {
                return __('Banners', BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter('admin_title', $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter('custom_plugin_title', $filter);

            $do = new CAdminBannersNew();
            $do->doModel();
			break;

		case 'banners-admin-positions':
			$filter = function($string) {
                return __('Positions - Banners', BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter('admin_title', $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter('custom_plugin_title', $filter);

            $do = new CAdminBannersPositions();
            $do->doModel();
			break;

		case 'banners-admin-advertisers':
			$filter = function($string) {
                return __('Advertisers - Banners', BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter('admin_title', $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter('custom_plugin_title', $filter);

            $do = new CAdminBannersAdvertisers();
            $do->doModel();
			break;

		case 'banners-admin-settings':
			$filter = function($string) {
                return __('Settings - Banners', BANNERS_PREF);
            };

            // Page title (in <head />)
            osc_add_filter('admin_title', $filter, 10);

            // Page title (in <h1 />)
            osc_add_filter('custom_plugin_title', $filter);

            $do = new CAdminBannersSettings();
            $do->doModel();
			break;
	}
}
osc_add_hook('renderplugin_controller', 'banners_admin_controllers');


/**
 * The content of this function it will show by ajax request on this url:
 * <?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=banners_controller_requests
 */
function banners_controller_requests() {
	$do = new CBannersAdminAjax();
	$do->doModel();
}
osc_add_hook('ajax_banners_controller_requests', 'banners_controller_requests');

function modals_form_options() {
    if (Params::getParam('page') == 'plugins' && in_array(Params::getParam('route'), banners_slug_routes())) {
        include BANNERS_PATH . 'parts/admin/modals_form_options.php';
    }
}
osc_add_hook('admin_footer', 'modals_form_options');


/**
 * Build position hooks.
 *
 * If exists a position with the number sort '4', automatically there will be a hook available with that number
 * Example: osc_run_hook('banners_position_4');
 */
$positions = Banners::newInstance()->getAllPositions();
foreach ($positions as $pos) {
    $sort = $pos['i_sort_id'];

    osc_add_hook('banners_position_'.$sort, function() use ($sort) {

        $banner = banners_position_sort($sort);
        if ($banner) {
            if ($banner['type']) {
                echo "<a href=\"".$banner['url']."\" target=\"_blank\"><img ".$banner['attrs']." /></a>";
            } else {
                echo $banner['script'];
            }
        }

    });

}


// When the an account is deleted, it will delete all trail of activity on this plugin
function banners_when_delete_user($id) {
	// Check if is advertiser
	$advertiser = Banners::newInstance()->getAdvertiserByUserId($id);
	if ($advertiser) {
		Banners::newInstance()->deleteAdverstiser($advertiser['pk_i_id']); // Include banners
	}
	
}
osc_add_hook('delete_user', 'banners_when_delete_user');


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