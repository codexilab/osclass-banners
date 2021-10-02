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

class CAdminBannersNew extends AdminSecBaseModel
{
	public function doModel()
	{
		$bannerToUpdate = Banners::newInstance()->getById(Params::getParam('banner'));

		switch (Params::getParam('plugin_action')) {
			case 'set_banner':
				$error = 0;

				$banner = Params::getFiles('banner');
				if ($bannerToUpdate && !$banner['tmp_name']) {
					$banner['tmp_name'] = BANNERS_ROUTE_SOURCES . $bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension'];
				}
				if (isset($banner['tmp_name']) && $banner['tmp_name']) {
					list($banner['width'], $banner['height'], $banner['mime']) = getimagesize($banner['tmp_name']);
				}
				$banner['mime'] = (isset($banner['mime']) && $banner['mime']) ? getmimetype($banner['mime']) : '';

				$data = array(
					'pk_i_id'               => ($bannerToUpdate) ? $bannerToUpdate['pk_i_id'] : false,
					'fk_i_advertiser_id'    => Params::getParam('fk_i_advertiser_id'),
					'fk_i_position_id'      => Params::getParam('fk_i_position_id'),
					's_category'            => implode(',', Params::getParam('categories')),
					's_url'                 => setURL(Params::getParam('s_url')),
					's_name'                => osc_genRandomPassword(),
					's_title' 				=> Params::getParam('s_title'),
					's_alt' 				=> Params::getParam('s_alt'),
					's_css_class' 			=> Params::getParam('s_css_class'),
					's_content_type'        => (!$banner['type'] && $bannerToUpdate) ? $bannerToUpdate['s_content_type'] : $banner['type'], // image/gif image/jpg image/png
					's_extension'           => $banner['mime'], // png jpg gif
					's_script'              => $_POST['s_script'],
					's_color'               => Params::getParam('s_color'),
		        	'dt_from_date'         	=> (Params::getParam('dt_from_date') == '') ? todaydate(null, null, '00:00:00') : Params::getParam('dt_from_date'),
					'dt_to_date'         	=> (Params::getParam('dt_to_date') == '') ? todaydate(1, 'month', '00:00:00') : Params::getParam('dt_to_date'),
					'b_image'               => Params::getParam('b_image'),
					'b_active'              => Params::getParam('b_active')
				);
				
				if (!$bannerToUpdate) $data['dt_date'] = todaydate(); // Publication date
				if ($bannerToUpdate) $data['dt_update'] = todaydate(); // Last update

				// If the banner to update have the same color, not validate the color
				$color = $data['s_color'];
				$validateColor = ($bannerToUpdate && $bannerToUpdate['s_color'] == $data['s_color']) ? false : true;

				// Validate fields with (*)
				if (!Params::getParam('fk_i_advertiser_id') || ($data['b_image'] >= 1 && !Params::getParam('s_url')) || !Params::getParam('fk_i_position_id')) {
					$error++;
					osc_add_flash_error_message(__('All fields cannot be empty.', BANNERS_PREF), 'admin');
			    
			    // Validation of date range
			    } elseif ($data['dt_to_date'] < $data['dt_from_date']) {
			    	$error++;
			    	osc_add_flash_error_message(__('From date has it be higher that To date.', BANNERS_PREF), 'admin');
			    	
				// Availability validation in date range
				} elseif (banners_detect_daterange($data['dt_from_date'], $data['dt_to_date'], $data['fk_i_position_id'], $data['pk_i_id']) >= 1) {
					$error++;
					osc_add_flash_error_message(__('The banner is not in an available range.', BANNERS_PREF), 'admin');
			    
				// Validation color input field (can not be empty)
				} elseif (!$data['s_color']) {
					$error++;
					osc_add_flash_error_message(__('Select a color to show on the position calendar.', BANNERS_PREF), 'admin');
			    
				// Color validation (can not be repeated in a position)
				} elseif ($validateColor && Banners::newInstance()->detectByColorAndPosition($data['s_color'], $data['fk_i_position_id']) >= 1) {
					$error++;
					osc_add_flash_error_message(sprintf(__('The color %s is already in use on this position.', BANNERS_PREF), '<span style="color: '.$color.';">'.$color.'</span>'), 'admin');
			    
				} elseif (!Params::getParam('categories')) {
					$error++;
					osc_add_flash_error_message(__('Select a category.', BANNERS_PREF), 'admin');
			    
				} else {
			        
					// Validate coding banner
					if ($data['b_image'] <= 0) {
						if ($data['s_script'] == "") {
							$error++;
							osc_add_flash_error_message(__('The banner script cannot be empty.', BANNERS_PREF), 'admin');            
						} else {
							if ($bannerToUpdate) $data['s_name'] = $bannerToUpdate['s_name']; #unset($data['s_name']);
							unset($data['s_content_type']);
							unset($data['s_extension']);
							if (Params::getParam('s_url') == '') $data['s_url'] = '';
							Banners::newInstance()->set($data);
							osc_add_flash_ok_message(__('The banner has been correctly placed.', BANNERS_PREF), 'admin');
						}

					// Validate upload banner
					} else if ($data['b_image'] >= 1 && is_valid_mime($banner['mime']) == false) {
						$error++;
						osc_add_flash_error_message(__('The banner have it be a gif, jpg, png or bmp.', BANNERS_PREF), 'admin');
					} else {

						// If you are updating banner without change image
			            if ($bannerToUpdate && $banner['error'] == UPLOAD_ERR_NO_FILE) {
							$data['s_name'] = $bannerToUpdate['s_name'];
							unset($data['dt_date']);
							Banners::newInstance()->set($data);
							osc_add_flash_ok_message(__('The banner has been correctly updated.', BANNERS_PREF), 'admin');
						} else {
							if ($banner['error'] == UPLOAD_ERR_OK) {
								if (move_uploaded_file($banner['tmp_name'], BANNERS_FOLDER_SOURCES . $data['s_name'].'.'.$data['s_extension'])) {
									if ($bannerToUpdate) {
										unset($data['dt_date']);
										@unlink(BANNERS_FOLDER_SOURCES . $bannerToUpdate['s_name'].'.'.$bannerToUpdate['s_extension']);
									}

									Banners::newInstance()->set($data);
									osc_add_flash_ok_message(__('The banner has been correctly uploaded.', BANNERS_PREF), 'admin');
								} else {
									$error++;
									osc_add_flash_error_message(sprintf(__('An error has occurred to upload file, please try again. (%s)', BANNERS_PREF), '1'), 'admin');
								}
							} else {
								$error++;
								osc_add_flash_error_message(sprintf(__('An error has occurred to upload file, please try again. (%s)', BANNERS_PREF), '2'), 'admin');
							}
						}
					}

				}
				ob_get_clean();
				$this->redirectTo((($error > 0) ? $_SERVER['HTTP_REFERER'] : osc_route_admin_url('banners-admin')));
				break;

			default:
				$this->_exportVariableToView("categories", Category::newInstance()->toTreeAll());
				$this->_exportVariableToView("selected", (isset($bannerToUpdate['s_category'])) ? explode(',', $bannerToUpdate['s_category']) : array());
				
				$this->_exportVariableToView("advertisers", Banners::newInstance()->getAllAdvertisers());
				$this->_exportVariableToView("positions", Banners::newInstance()->getAllPositions());
				
				$this->_exportVariableToView("bannerToUpdate", $bannerToUpdate);
				break;
		}
	}
}