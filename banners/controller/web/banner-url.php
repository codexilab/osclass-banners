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

class CWebBannersURL extends WebSecBaseModel
{
	public function doModel()
	{
		$param 	= banners_route_param();
		$url 	= "http://localhost/osclass390/index.php?page=contact"; //osc_base_url();

		$banner = Banners::newInstance()->getByName(Params::getParam($param));

		if (osc_get_preference('show_url_banner', BANNERS_PREF)) {
			$banner = Banners::newInstance()->getByURL(Params::getParam($param));
		}

		if ($banner) {
			$data = array(
				'fk_i_banner_id' 	=> $banner['pk_i_id'],
				's_ip' 				=> Params::getServerParam('REMOTE_ADDR'),
				'dt_date' 			=> todaydate()
			);
			Banners::newInstance()->addClic($data);
			$url = $banner['s_url'];
		}

		ob_get_clean();
		osc_redirect_to($url);
	}
}