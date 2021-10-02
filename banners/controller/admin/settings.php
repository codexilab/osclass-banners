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

if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

class CAdminBannersSettings extends AdminSecBaseModel
{

    // Business Layer...
    public function doModel()
    {

        switch (Params::getParam('plugin_action')) {
            case 'done':
				if (Params::getParam('banner_route_page') == '' || Params::getParam('banner_route_param') == '') {
					osc_add_flash_error_message(__('All fields cannot be empty.', BANNERS_PREF), 'admin');
				} else {
					osc_set_preference('banner_route_page', Params::getParam('banner_route_page'), BANNERS_PREF, 'STRING');
					osc_set_preference('banner_route_param', Params::getParam('banner_route_param'), BANNERS_PREF, 'STRING');
					osc_set_preference('show_url_banner', Params::getParam('show_url_banner'), BANNERS_PREF, 'BOOLEAN');
					osc_add_flash_ok_message(__('The plugin is now configured.', BANNERS_PREF), 'admin');
				}
				
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('banners-admin-settings'));
                break;
        }
    }
    
}