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

class CAdminBanners extends AdminSecBaseModel
{
	public function doModel()
	{
		switch (Params::getParam('plugin_action')) {
			case 'delete':
		        $i = 0;
		        $bannersId = Params::getParam('id');

		        if (!is_array($bannersId)) {
		            osc_add_flash_error_message(__('Select banner.', BANNERS_PREF), 'admin');
		        } else {
		            foreach ($bannersId as $id) {
		                if (Banners::newInstance()->delete($id)) $i++;
		            }
		            if ($i == 0) {
		                osc_add_flash_error_message(__('No banners have been deleted.', BANNERS_PREF), 'admin');
		            } else {
		                osc_add_flash_ok_message(sprintf(__('%s banner(s) have been deleted.', BANNERS_PREF), $i), 'admin');
		            }
		        }
		        ob_get_clean();
		        $this->redirectTo($_SERVER['HTTP_REFERER']);
		        break;

			case 'activate':
				$i = 0;
				$bannersId = Params::getParam('id');

				if (!is_array($bannersId)) {
					osc_add_flash_error_message(__('Select banner.', BANNERS_PREF), 'admin');
				} else {
					foreach ($bannersId as $id) {
						$data = array(
							'pk_i_id'   => $id,
							'dt_update' => todaydate(),
							'b_active'  => 1
						);
						if (Banners::newInstance()->set($data)) $i++;
					}
					if ($i == 0) {
						osc_add_flash_error_message(__('No banners have been activated.', BANNERS_PREF), 'admin');
					} else {
						osc_add_flash_ok_message(sprintf(__('%s banner(s) have been activated.', BANNERS_PREF), $i), 'admin');
					}
				}
				ob_get_clean();
				$this->redirectTo($_SERVER['HTTP_REFERER']);
				break;

			case 'deactivate':
				$i = 0;
				$bannersId = Params::getParam('id');

				if (!is_array($bannersId)) {
					osc_add_flash_error_message(__('Select banner.', BANNERS_PREF), 'admin');
				} else {
					foreach ($bannersId as $id) {
						$data = array(
							'pk_i_id'   => $id,
							'dt_update' => todaydate(),
							'b_active'  => 0
						);
						if (Banners::newInstance()->set($data)) $i++;
					}
					if ($i == 0) {
						osc_add_flash_error_message(__('No banner have been deactivated.', BANNERS_PREF), 'admin');
					} else {
						osc_add_flash_ok_message(sprintf(__('%s banner(s) have been deactivated.', BANNERS_PREF), $i), 'admin');
					}
				}
				ob_get_clean();
				$this->redirectTo($_SERVER['HTTP_REFERER']);
				break;

			default:
				$this->_exportVariableToView('advertisers', Banners::newInstance()->getAllAdvertisers());
				$this->_exportVariableToView('positions', Banners::newInstance()->getAllPositions());

				require_once BANNERS_PATH . 'classes/datatables/BannersDataTable.php';

				if( Params::getParam('iDisplayLength') != '' ) {
                    Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                    Cookie::newInstance()->set();
                } else {
                    // Set a default value if it's set in the cookie
                    $listing_iDisplayLength = (int) Cookie::newInstance()->get_value('listing_iDisplayLength');
                    if ($listing_iDisplayLength == 0) $listing_iDisplayLength = 10;
                    Params::setParam('iDisplayLength', $listing_iDisplayLength );
                }
                $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                // Table header order by related
                if( Params::getParam('sort') == '') {
                    Params::setParam('sort', 'date');
                }
                if( Params::getParam('direction') == '') {
                    Params::setParam('direction', 'desc');
                }

                $page  = (int)Params::getParam('iPage');
                if($page==0) { $page = 1; };
                Params::setParam('iPage', $page);

                $params = Params::getParamsAsArray();

                $bannersDataTable = new BannersDataTable();
                $bannersDataTable->table($params);
                $aData = $bannersDataTable->getData();

                if(count($aData['aRows']) == 0 && $page!=1) {
                    $total = (int)$aData['iTotalDisplayRecords'];
                    $maxPage = ceil( $total / (int)$aData['iDisplayLength'] );

                    $url = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];

                    if($maxPage==0) {
                        $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                        ob_get_clean();
                        $this->redirectTo($url);
                    }

                    if($page > $maxPage) {
                        $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                        ob_get_clean();
                        $this->redirectTo($url);
                    }
                }

                $this->_exportVariableToView('aData', $aData);

                $bulk_options = array(
                    array('value' => '', 'data-dialog-content' => '', 'label' => __('Bulk actions')),
                    array('value' => 'activate', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected banners?', BANNERS_PREF), strtolower(__('Activate'))), 'label' => __('Activate')),
                    array('value' => 'deactivate', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected banners?', BANNERS_PREF), strtolower(__('Deactivate'))), 'label' => __('Deactivate')),
                    array('value' => 'delete', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected banners?', BANNERS_PREF), strtolower(__('Delete'))), 'label' => __('Delete'))
                );

                $bulk_options = osc_apply_filter('banner_bulk_filter', $bulk_options);
                $this->_exportVariableToView('bulk_options', $bulk_options);
				break;
		}
	}
}