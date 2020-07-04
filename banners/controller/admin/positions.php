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

class CAdminBannersPositions extends AdminSecBaseModel
{
	public function doModel()
	{
		$positionId = Params::getParam('position_id');
		$position 	= Banners::newInstance()->getPositionById($positionId);

		switch (Params::getParam('plugin_action')) {
			case 'set_position':
				$validateSortNumber = false;
				if ($position) {
					if ($position['i_sort_id'] == Params::getParam('i_sort_id')) {
						$validateSortNumber = true;
					}
				}

				// If the fields are right
				if (!Params::getParam('i_sort_id') || !is_numeric(Params::getParam('i_sort_id'))) {
					osc_add_flash_error_message(__('Set number of position.', BANNERS_PREF), 'admin');
				} elseif (!$validateSortNumber) {
					osc_add_flash_error_message(__('This position already exist.', BANNERS_PREF), 'admin');
				} else {
					$data = array(
						// If the field 'position_id' have value, proceed to update!
						'pk_i_id'   => (Params::getParam('position_id') >= 1) ? Params::getParam('position_id') : false,
						'i_sort_id' => Params::getParam('i_sort_id'),
						's_title' 	=> Params::getParam('s_title')
					);
					if (Banners::newInstance()->setPosition($data)) {
						osc_add_flash_ok_message(__('Position has been correctly placed.', BANNERS_PREF), 'admin');
					}
				}
				ob_get_clean();
    			osc_redirect_to($_SERVER['HTTP_REFERER']);
		        break;

			case 'delete_position':
				// If exist this position
				if ($position) {
					// If exist banners using this position
					if (Banners::newInstance()->getByPositionId($position['pk_i_id'])) {
						osc_add_flash_error_message(__('The position can\'t be deleted, there are banners using it.', BANNERS_PREF), 'admin');
					} else {
						Banners::newInstance()->deletePositionById($position['pk_i_id']);
					}
				}
				ob_get_clean();
				osc_redirect_to($_SERVER['HTTP_REFERER']);
				break;

			default:
				$this->_exportVariableToView('positions', Banners::newInstance()->getAllPositions());
				break;
		}
	}
}