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
 * Get the current date or the sum depending of her parameters.
 *
 * - Example of use 1: echo todaydate(); Show the current date.
 * - Example of use 2: echo todaydate(3, 'days'); Show the current date but sum 3 days.
 * - Example of use 3: echo todaydate(3, 'years'); Show the current date but sum 3 years.
 *
 * @param string $time The default value is H:i:s but can change during the estatement of function.
 * @param int $num It defined null at the start as it can be empty, on the contrary must have a integer numeric value.
 * @param string $ymd It defined null at the start as ir can be empty, on the contrary so it specific 'days', 'years' or 'month'.
 * @return string $date Return the current in the H:i:s format.
 * @return string $dateplus Return the current date added.
 */
if (!function_exists('todaydate')) {
    function todaydate($num = null, $ymd = null, $time = 'H:i:s') {
        $date = date('Y-m-d '.$time);

        if ($num && $ymd) {
            $dateplus = strtotime('+'.$num.' '.$ymd, strtotime($date));
            $dateplus = date('Y-m-d H:i:s', $dateplus);
            return $dateplus;
        } else {
            return $date;
        }
    }
}

/**
 * Make a text string be a valid URI address (include mailto).
 * 
 * - Example of use 1: echo setURL("name@email.com"); Show mailto:name@email.com
 * - Example of use 2: echo setURL("mailto:name@email.com"); Show mailto:name@email.com
 * - Example of use 3: echo setURL("websitename.com"); Show http://websitename.com
 * - Example of use 4: echo setURL("http://websitename.com"); Show http://websitename.com
 * - Example of use 5: echo setURL("https://websitename.com"); Show https://websitename.com
 *
 * @param string $url
 * @return string
 */
if (!function_exists('setURL')) {
    function setURL($url) {
        $allowed = ['mailto'];
        $parsed = parse_url($url);
        if (in_array($parsed['scheme'], $allowed)) {
            return $url;

        } elseif (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $url)) {
        return 'mailto:'.$url;

        // wthout localhost  '/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'
        // with localhost    '/^(http|https):\/\/+(localhost|[A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'
        } elseif (preg_match('/^((http|https):\/\/?)[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/?))/i', $url)) {
            return $url;

        } else {
            return 'http://'.$url;
        }
    }
}

if (!function_exists('currentURL')) {
    function currentURL() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}

if (!function_exists('adminReturnBack')) {
    function adminReturnBack($route) {
        if ($_SERVER['HTTP_REFERER'] != currentURL()) {
            return $_SERVER['HTTP_REFERER'];
        } 
        return osc_route_admin_url($route);
    }
}

/**
 * Get the user name by Id.
 *
 * @param int $id
 * @return string
 */
 if (!function_exists('get_user_name')) {
    function get_user_name($id) {
        $user = User::newInstance()->findByPrimaryKey($id);
        return isset($user['s_name']) ? $user['s_name'] : "";
    }
}

/**
 * Get the user name by Id.
 *
 * @param int $id
 * @return string
 */
 if (!function_exists('get_user_email')) {
    function get_user_email($id) {
        $user = User::newInstance()->findByPrimaryKey($id);
        return isset($user['s_email']) ? $user['s_email'] : "";
    }
}

/**
 * Check if a image file exist through 'getimageszie'
 *
 * @return bool
 */
if (!function_exists('image_exists')) {
    function image_exists($url) {
        return (getimagesize($url)) ? true : false;
    }
}

/**
 * Compare a mime type and return the separated format.
 *
 * - Usage example 1: echo getmimetype('image/jpg'); show jpg.
 *
 * @param string $type
 * @return string
 */
if (!function_exists('getmimetype')) {
    function getmimetype($type) {
        switch ($type) {
            case 1:
                $return = 'gif';
                break;

            case 2:
                $return = 'jpg';
                break;

            case 3:
                $return = 'png';
                break;

            case 4:
                $return = 'swf';
                break;

            case 5:
                $return = 'psd';
                break;

            case 6:
                $return = 'bmp';
                break;
        }
        return $return;
    }
}

/**
 * Compare two variables, if they are equals return the html 'selected' atribute.
 *
 * @return string
 */
if (!function_exists('get_html_selected')) {
    function get_html_selected($a, $b) {
        return ($a == $b) ? 'selected="selected"' : '';
    }
}

/**
 * Compare two variables, if they are equals return the html 'checked' atribute.
 *
 * @return string
 */
if (!function_exists('get_html_checked')) {
    function get_html_checked($a, $b) {
        return ($a == $b) ? 'checked="true"' : '';
    }
}

/**
 * All valid formats for the banners in the site.
 *
 * @return array
 */
if (!function_exists('get_banner_mimes')) {
    function get_banner_mimes() {
        return array("gif", "jpg", "bmp", "png");
    }
}

/**
 * Compare that a mime be a valid format (previously specified).
 *
 * @return bool
 */
if (!function_exists('is_valid_mime')) {
    function is_valid_mime($mime) {
        return in_array($mime, get_banner_mimes());
    }
}

/**
 * Buil the full internal uri of redirect
 * get_banner_route()
 *
 * @return string
 */
function get_banner_route($url) {
    if (osc_rewrite_enabled()) {
        return osc_route_url(banners_route_page()).'?'.banners_route_param().'='.$url;
    }
    return osc_route_url(banners_route_page()).'&'.banners_route_param().'='.$url;
}

/**
 * Return array with specific information of banner according to its parameters of disposition.
 *
 * - Example of use: 
 * $banners1 = get_banners_position(1, osc_item_category_id()); Show banner of position 1 in determinated category of a item.
 * $banners2 = get_banners_position(3, 'all'); Show banner of position 3 in all categories.
 * 
 *  if (isset($banners1) && $banners1) {
 *      if ($banner1['type']) {
 *          echo "<a href=\"".$banner1['url']."\" style=\"background: url(".$banner1['source'].")\"></a>";
 *      } else {
 *          echo $banner1['script'];
 *      }
 *  }
 * 
 *  if (isset($banners2) && $banners2) {
 *      if ($banner2['type']) {
 *          echo "<a href=\"".$banner2['url']."\" style=\"background: url(".$banner2['source'].")\"></a>";
 *      } else {
 *          echo $banner2['script'];
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