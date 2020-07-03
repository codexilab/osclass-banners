<?php
/**
 * Internal admin menu.
 *
 * @return string
 */
if (!function_exists('banners_admin_menu')) {
    function banners_admin_menu() {
        require_once BANNERS_PATH . "parts/admin/menu.php";
    }
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
 * Get the sort number of position.
 *
 * @param int $id
 * @return int
 */
if (!function_exists('banners_sort_position')) {
    function banners_sort_position($id) {
        $position = Banners::newInstance()->getPositionById($id);
        return ($position) ? $position['i_sort_id'] : 0;
    }
}

/**
 * Get total of positions.
 *
 * @return string
 */
if (!function_exists('banners_positions_total')) {
    function banners_positions_total() {
        return Banners::newInstance()->positionsTotal();
    }
}

/**
 * Get total of advertisers.
 *
 * @return int
 */
if (!function_exists('banners_advertisers_total')) {
    function banners_advertisers_total() {
        return Banners::newInstance()->advertisersTotal();
    }
}

/**
 * Get total of banners.
 *
 * @return int
 */
if (!function_exists('banners_count_total')) {
    function banners_count_total() {
        return Banners::newInstance()->total();
    }
}

/**
 * Build the full internal uri of redirect
 *
 * @return string
 */
function get_banner_route($url) {
    $r = (!osc_rewrite_enabled()) ? '&' : '?';
    return osc_route_url(banners_route_page()).$r.banners_route_param().'='.$url;
}

/**
 * Return array with specific information of banner according to its parameters of disposition.
 *
 * - Example of use: 
 * $banners1 = get_banners_position(1, osc_item_category_id()); Show banner of position 1 in determinated category of a item.
 * $banners2 = get_banners_position(3, 'all'); Show banner of position 3 in all categories.
 * 
 *  if (isset($banners1) && $banners1) {
 *      if ($banners1['type']) {
 *          echo "<a href=\"".$banners1['url']."\"><img src=\"".$banner['source']."\" /></a>";
 *      } else {
 *          echo $banners1['script'];
 *      }
 *  }
 * 
 *  if (isset($banners2) && $banners2) {
 *      if ($banners2['type']) {
 *          echo "<a href=\"".$banners2['url']."\"><img src=\"".$banner['source']."\" /></a>";
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
 * @return array $b['url'] URL banner, $b['source'] URL of banner image, $b['type'] Type of banner: false is Script, true is a image uploaded.
 */
function get_banners_position($sort, $category = 'all') {
    $position = Banners::newInstance()->getPositionBySortId($sort);
    if ($position) {
        $banners = Banners::newInstance()->getByPositionId($position['pk_i_id']);
        if ($banners) {
            foreach ($banners as $banner) {
                $b = array();
                if (todaydate() >= $banner['dt_since_date'] && todaydate() <= $banner['dt_until_date'] && $banner['b_active'] == true) {

                    $showBanner = false;
                    if ($banner['s_category'] != 'all') {
                        $categories = explode(',', $banner['s_category']);
                        if (in_array($category, $categories)) $showBanner = true;
                    } else if (is_int($banner['s_category'])) {
                        if ($category == $banner['s_category']) $showBanner = true;
                    } else {
                        $showBanner = true;
                    }

                    $advertiser = Banners::newInstance()->getAdvertiserById($banner['fk_i_advertiser_id']);
                    if ($showBanner == true && $advertiser['b_active'] == true) {
                        $b['url']       = (osc_get_preference('show_url_banner', BANNERS_PREF)) ? get_banner_route($banner['s_url']) : get_banner_route($banner['s_name']);
                        $b['source']    = BANNERS_ROUTE_SOURCES.$banner['s_name'].'.'.$banner['s_extension'];
                        $b['type']      = $banner['b_image'];
                        $kwords         = array('{URL}');
                        $rwords         = array($b['url']); // Replace {URL} by URL of Banner in the content
                        $b['script']    = str_ireplace($kwords, $rwords, $banner['s_script']);
                    }
                    break;

                }
            }
            return $b;
        }
        return array();
    }
    return array();
}