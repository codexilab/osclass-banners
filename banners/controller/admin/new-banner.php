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

class CAdminBannersNew extends AdminSecBaseModel
{
	public function doModel()
	{
		$bannerToUpdate = Banners::newInstance()->getById(Params::getParam('banner'));

		switch (Params::getParam('plugin_action')) {
			case 'new_banner':
				$banner = Params::getFiles('banner');
				if ($bannerToUpdate && !$banner['tmp_name']) {
					$banner['tmp_name'] = BANNERS_ROUTE_SOURCES . $bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension'];
				}
				list($banner['width'], $banner['height'], $banner['mime']) = getimagesize($banner['tmp_name']);
				$banner['mime'] = getmimetype($banner['mime']);

				$data = array(
					'pk_i_id'               => ($bannerToUpdate) ? $bannerToUpdate['pk_i_id'] : false,
					'fk_i_advertiser_id'    => Params::getParam('fk_i_advertiser_id'),
					'fk_i_position_id'      => Params::getParam('fk_i_position_id'),
					's_category'            => (Params::getParam('all_categories')) ? 'all' : implode(',', Params::getParam('s_category')),
					's_url'                 => setURL(Params::getParam('s_url')),
					's_name'                => osc_genRandomPassword(),
					's_content_type'        => (!$banner['type'] && $bannerToUpdate) ? $bannerToUpdate['s_content_type'] : $banner['type'], // image/gif image/jpg image/png
					's_extension'           => $banner['mime'], // png jpg gif
					's_script'              => $_POST['s_script'],
					's_color'               => Params::getParam('s_color'),
		        	'dt_since_date'         => (Params::getParam('dt_since_date') == '') ? todaydate(null, null, '00:00:00') : Params::getParam('dt_since_date'),
					'dt_until_date'         => (Params::getParam('dt_until_date') == '') ? todaydate(1, 'month', '00:00:00') : Params::getParam('dt_until_date'),
					'b_image'               => Params::getParam('b_image'),
					'b_active'              => Params::getParam('b_active')
				);

				// If the banner to update have the same color, not validate the color
				$validateColor = ($bannerToUpdate && $bannerToUpdate['s_color'] == $data['s_color']) ? false : true;

				// If the banner to update have the same date range, not validate
				if ($bannerToUpdate && $data['dt_since_date'] >= $bannerToUpdate['dt_since_date'] && $data['dt_until_date'] <= $bannerToUpdate['dt_until_date']) {
					$validateDateRange = false;
				} else {
					$validateDateRange = true;
				}

				// Validate fields:
				if (!Params::getParam('fk_i_advertiser_id') || ($data['b_image'] >= 1 && !Params::getParam('s_url')) || !Params::getParam('fk_i_position_id') || $data['dt_until_date'] < $data['dt_since_date']) {
					osc_add_flash_error_message(__('All fields cannot be empty and Since date has it be higher that Until date.', BANNERS_PREF), 'admin');
			    
				// Validation of date range (can not be repeated in a position)
				} elseif ($validateDateRange && Banners::newInstance()->detectDisponibleDateRange($data['dt_since_date'], $data['dt_until_date'], $data['fk_i_position_id']) >= 1) {
					osc_add_flash_error_message(__('The banner is not in an available range.', BANNERS_PREF), 'admin');
			    
				// Validation color input field (can not be empty)
				} elseif (!$data['s_color']) {
					osc_add_flash_error_message(__('Select a color to show on the position calendar.', BANNERS_PREF), 'admin');
			    
				// Color validation (can not be repeated in a position)
				} elseif ($validateColor && Banners::newInstance()->detectByColorAndPosition($data['s_color'], $data['fk_i_position_id']) >= 1) {
					osc_add_flash_error_message(__("The color <span style=\"color: $color;\">$color</span> is already in use on this position.", BANNERS_PREF), 'admin');
			    
				} elseif (!Params::getParam('all_categories') && !Params::getParam('s_category')) {
					osc_add_flash_error_message(__('Select a category.', BANNERS_PREF), 'admin');
			    
				} else {
			        
					// Validate coding banner
					if ($data['b_image'] <= 0) {
						if ($data['s_script'] == "") {
							osc_add_flash_error_message(__('The banner script cannot be empty.', BANNERS_PREF), 'admin');            
						} else {
							#unset($data['s_name']);
							unset($data['s_content_type']);
							unset($data['s_extension']);
							if (Params::getParam('s_url') == "") $data['s_url'] = "";
							Banners::newInstance()->set($data);
							osc_add_flash_ok_message(__('The banner has been correctly placed.', BANNERS_PREF), 'admin');
						}

					// Validate upload banner
					} elseif ($data['b_image'] >= 1 && is_valid_mime($banner['mime']) == false) {
						osc_add_flash_error_message(__('The banner have it be a gif, jpg, png or bmp.', BANNERS_PREF), 'admin');
					} else {

						// If you are updating banner without change image
			            if ($bannerToUpdate && $banner['error'] == UPLOAD_ERR_NO_FILE) {
							$data['s_name'] = $bannerToUpdate['s_name'];
							unset($data['dt_date']);
							$data['dt_update'] = todaydate(); // Last update
							Banners::newInstance()->set($data);
							osc_add_flash_ok_message(__('The banner has been correctly updated.', BANNERS_PREF), 'admin');
						} else {
							if ($banner['error'] == UPLOAD_ERR_OK) {
								if (move_uploaded_file($banner['tmp_name'], BANNERS_FOLDER_SOURCES . $data['s_name'].'.'.$data['s_extension'])) {
									if ($bannerToUpdate) {
										unset($data['dt_date']);
										$data['dt_update'] = todaydate(); // Last update
										unlink(BANNERS_FOLDER_SOURCES . $bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension']);
									} else {
										$data['dt_date'] = todaydate(); // Publication date
									}

									Banners::newInstance()->set($data);
									osc_add_flash_ok_message(__('The banner has been correctly uploaded.', BANNERS_PREF), 'admin');
								} else {
									osc_add_flash_error_message(__('An error has occurred to upload file, please try again. (1)', BANNERS_PREF), 'admin');
								}
							} else {
								osc_add_flash_error_message(__('An error has occurred to upload file, please try again. (2)', BANNERS_PREF), 'admin');
							}
						}
					}

				}
				ob_get_clean();
				osc_redirect_to($_SERVER['HTTP_REFERER']);
				break;

			default:
				$this->_exportVariableToView('advertisers', Banners::newInstance()->getAllAdvertisers());
				$this->_exportVariableToView('positions', Banners::newInstance()->getAllPositions());
				$this->_exportVariableToView('bannerToUpdate', $bannerToUpdate);
				break;
		}
	}
}