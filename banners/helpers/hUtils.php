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
        if (isset($parsed['scheme']) && $parsed['scheme'] && in_array($parsed['scheme'], $allowed)) {
            
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
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
                return 'gif';
                break;

            case 2:
                return 'jpg';
                break;

            case 3:
                return 'png';
                break;

            case 4:
                return 'swf';
                break;

            case 5:
                return 'psd';
                break;

            case 6:
                return 'bmp';
                break;
        }
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
 * All valid formats for the banners in the site.
 *
 * @return array
 */
if (!function_exists('get_banner_mimes')) {
    function get_banner_mimes() {
        return array('gif', 'jpg', 'bmp', 'png', 'svg');
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