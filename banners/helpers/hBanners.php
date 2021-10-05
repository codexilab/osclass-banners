<?php
/**
 * Routes.
 *
 * @return array
 */
function banners_plugin_routes() {
    $routes = [
        [
            'slug' => 'banners-admin', 'regexp' => BANNERS_FOLDER.'views/admin/banners', 'url' => BANNERS_FOLDER.'views/admin/banners', 'file' => BANNERS_FOLDER.'views/admin/banners.php'
        ],
        [
            'slug' => 'banners-admin-set', 'regexp' => BANNERS_FOLDER.'views/admin/set-banner', 'url' => BANNERS_FOLDER.'views/admin/set-banner', 'file' => BANNERS_FOLDER.'views/admin/set-banner.php'
        ],
        [
            'slug' => 'banners-admin-advertisers', 'regexp' => BANNERS_FOLDER.'views/admin/advertisers', 'url' => BANNERS_FOLDER.'views/admin/advertisers', 'file' => BANNERS_FOLDER.'views/admin/advertisers.php'
        ],
        [
            'slug' => 'banners-admin-positions', 'regexp' => BANNERS_FOLDER.'views/admin/positions', 'url' => BANNERS_FOLDER.'views/admin/positions', 'file' => BANNERS_FOLDER.'views/admin/positions.php'
        ],
        [
            'slug' => 'banners-admin-settings', 'regexp' => BANNERS_FOLDER.'views/admin/settings', 'url' => BANNERS_FOLDER.'views/admin/settings', 'file' => BANNERS_FOLDER.'views/admin/settings.php'
        ]
    ];
    return $routes;
}

/**
 * Get an array with only slugs of all routes for this plugin.
 *
 * @return array
 */
function banners_slug_routes() {
    $slug = array();
    foreach (banners_plugin_routes() as $route) {
        $slug[] = $route['slug'];
    }
    return $slug;
}

/**
 * Get position by primary key ID.
 *
 * @param int $id
 * @return array
 */
function position_by_id($id) {
    return Banners::newInstance()->getPositionById($id);
}

/**
 * Internal admin menu.
 *
 * @return string
 */
function banners_admin_menu() {
    require_once BANNERS_PATH . 'parts/admin/menu.php';
}

/**
 * Get the internal name of the page to build the redirectionament to the banner uri.
 * get_banner_route_page()
 *
 * @return string
 */
function banners_route_page() {
    return (osc_get_preference('banner_route_page', BANNERS_PREF)) ? osc_get_preference('banner_route_page', BANNERS_PREF) : 'banners-banner-url';
}

/**
 * Get the final parameter of the page to build the redirectionament to the banner uri.
 * get_banner_route_param()
 *
 * @return string
 */
function banners_route_param() {
    return (osc_get_preference('banner_route_param', BANNERS_PREF)) ? osc_get_preference('banner_route_param', BANNERS_PREF) : 'ref';
}

/**
 * Get the sort number of position by its primary key.
 *
 * @param int $id
 * @return int
 */
function banners_sort_position($id) {
    $position = position_by_id($id);
    return ($position) ? $position['i_sort_id'] : 0;
}

/**
 * Get total of all positions.
 *
 * @return int
 */
function banners_positions_total() {
    return Banners::newInstance()->positionsTotal();
}

/**
 * Get total of advertisers.
 *
 * @return int
 */
function banners_advertisers_total() {
    return Banners::newInstance()->advertisersTotal();
}

/**
 * Get total of banners.
 *
 * @return int
 */
function banners_count_total() {
    return Banners::newInstance()->total();
}

/**
 * Get banners by position Id.
 *
 * @param int $positionId
 * @return array
 */
function banners_by_position($positionId) {
    return Banners::newInstance()->getByPositionId($positionId);
}

/**
 * Detect banners in a date range (From date xxxx-xx-xx to date nnnn-nn-nn).
 *
 * In case there is banner to update: compare each Id thrown with the id of banner to update.
 * If its found the same Id banner ignore that comparison.
 *
 * If it keeps detecting ids, it means that there are banners in the date range that are being queried,
 * it is still colliding (detected) and a banner cannot be placed there, because those spaces or days in the calendar are already occupied.
 *
 * @param string $fromDate
 * @param string $toDate
 * @param int $positionId
 * @param int $bannerId
 * @return int
 */
function banners_detect_daterange($fromDate, $toDate, $positionId, $bannerId = null) {
    $banners        = Banners::newInstance()->getByDateRange($fromDate, $toDate, $positionId);
    $detected       = 0;
    if ($banners) {
        foreach ($banners as $banner) {
            $detected++;
            if (isset($bannerId) && $bannerId == $banner['pk_i_id']) {
                $detected--; // Ignore this collision if is the same banner
            }
        }
    }

    return $detected;
}

/**
 * Build the full internal uri of redirect
 *
 * @return string
 */
if (!function_exists('get_banner_route')) {
    function get_banner_route($url) {
    $rw = (!osc_rewrite_enabled()) ? '&' : '?';
        return osc_route_url(banners_route_page()).$rw.banners_route_param().'='.$url;
    }
}

/**
 * Returns an array with references fo allowed sections where a banner can be shown,
 * depending on the current location of the logged in user or visitor.
 *
 * @return array Example: ['home', '1']
 */
function banners_allowed_sections() {
    $sections = [];
    //$sections[] = 'all';
    if (osc_is_404()) $sections[] = 'error';
    if (osc_is_home_page()) $sections[] = 'home';
    //if (osc_is_search_page()) $sections[] = 'search';
    if (osc_is_static_page()) $sections[] = 'page';
    if (osc_is_contact_page()) $sections[] = 'contact';
    //if (osc_is_ad_page()) $sections[] = 'item';
    if (osc_is_publish_page()) $sections[] = 'item_add';
    if (osc_is_edit_page()) $sections[] = 'item_edit';
    if (osc_is_item_contact_page()) $sections[] = 'item_contact';
    if (osc_is_login_page()) $sections[] = 'login';
    if (osc_is_register_page()) $sections[] = 'register';
    if (osc_is_recover_page()) $sections[] = 'login_recover';
    if (osc_is_forgot_page()) $sections[] = 'login_forgot';

    if (osc_is_custom_page()) $sections[] = 'custom';

    if (osc_is_public_profile())$sections[] = 'pub_profile';
    if (osc_is_user_dashboard()) $sections[] = 'dashboard';
    if (osc_is_user_profile()) $sections[] = 'profile';
    if (osc_is_list_items()) $sections[] = 'items';
    if (osc_is_list_alerts()) $sections[] = 'alerts';
    if (osc_is_change_email_page()) $sections[] = 'change_email';
    if (osc_is_change_username_page()) $sections[] = 'change_username';
    if (osc_is_change_password_page()) $sections[] = 'change_password';
    if (osc_category_id()) $sections[] = osc_category_id();
    return $sections;
}

/**
 * Return array with specific information of banner according to its parameters of disposition.
 *
 * - Example of use: 
 * $banners1 = banners_position_sort(1); Show banner of position 1 in determinated category of a item.
 * $banners2 = banners_position_sort(3); Show banner of position 3 in all categories.
 * 
 *  if ($banners1) {
 *      if ($banners1['type']) {
 *          echo "<a href=\"".$banners1['url']."\" target=\"_blank\"><img ".$banners1['attrs']." /></a>";
 *      } else {
 *          echo $banners1['script'];
 *      }
 *  }
 * 
 *  if ($banners2) {
 *      if ($banners2['type']) {
 *          echo "<a href=\"".$banners2['url']."\" target=\"_blank\"><img ".$banners2['attrs']." /></a>";
 *      } else {
 *          echo $banners2['script'];
 *      }
 *  }
 * 
 *
 * Note: In the end of foreach there are a break because most only show one banner.
 *
 * @param integer $sort Position number.
 * @param integer $category To know if a banner most show in determinated category.
 * @return array $b['url'] URL banner, $b['attrs'] Image attributes, $b['type'] Type of banner: false is Script, true is a image uploaded.
 */
function banners_position_sort($sort) {
    $position   = Banners::newInstance()->getPositionBySortId($sort);
    $banners    = (isset($position['pk_i_id'])) ? banners_by_position($position['pk_i_id']) : [];
    $sections   = banners_allowed_sections(); // You can add more elements with array_unshift or array_push
    $showBanner = false;
    $b          = [];

    if ($banners) {
        foreach ($banners as $banner) {
            
            if (date('Y-m-d') >= $banner['dt_from_date'] && date('Y-m-d') <= $banner['dt_to_date'] && $banner['b_active'] == true) {

                $category = explode(',', $banner['s_category']); // Convert value $banner['s_category'] = 'home,1,2,3'; to $category = array('home', '1', '2', '3');
                $showBanner = !empty(array_intersect($sections, $category)); // Boolean value

                $advertiser = Banners::newInstance()->getAdvertiserById($banner['fk_i_advertiser_id']);
                if ($showBanner == true && $advertiser['b_active'] == true) {
                    $src            = BANNERS_ROUTE_SOURCES.$banner['s_name'].'.'.$banner['s_extension'];
                    $title          = ($banner['s_title']) ? ' title="'.$banner['s_title'].'"' : ''; // <img title="Title" /> : <img />
                    $alt            = ($banner['s_alt']) ? ' alt="'.$banner['s_alt'].'"' : '';
                    $css_class      = ($banner['s_css_class']) ? ' class="'.$banner['s_css_class'].'"' : '';

                    $b['url']       = (osc_get_preference('show_url_banner', BANNERS_PREF)) ? get_banner_route($banner['s_url']) : get_banner_route($banner['s_name']);
                    $b['attrs']     = 'src="'.$src.'"'.$title.$alt.$css_class;
                    $b['type']      = $banner['b_image'];
                    $kwords         = array('{URL}');
                    $rwords         = array($b['url']); // Replace {URL} by URL of Banner in the content
                    $b['script']    = str_ireplace($kwords, $rwords, $banner['s_script']);
                }
                break;

            }
        }
    }

    return $b;
}