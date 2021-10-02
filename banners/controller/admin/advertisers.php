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

class CAdminBannersAdvertisers extends AdminSecBaseModel
{
	public function doModel()
	{
		switch (Params::getParam('plugin_action')) {
			case 'set_advertiser':
				$advertiserToUpdate = Banners::newInstance()->getAdvertiserById(Params::getParam('id'));
				$userId = Params::getParam('fk_i_user_id');

				// This is for not compare the same number to validation field
				if ($advertiserToUpdate && $advertiserToUpdate['fk_i_user_id'] == $userId) {
					$userId = 0;
				}

				// If the fields are empty
				if (!Params::getParam('s_name') && Params::getParam('fk_i_user_id') == '') {
					osc_add_flash_error_message(__('Write advertiser name.', BANNERS_PREF), 'admin');
					ob_get_clean();
					$this->redirectTo($_SERVER['HTTP_REFERER']);

				// If the user is a advertiser
				} elseif (Banners::newInstance()->getAdvertiserByUserId($userId)) {
					osc_add_flash_error_message(__('This user has been already added.', BANNERS_PREF), 'admin');
					ob_get_clean();
					$this->redirectTo($_SERVER['HTTP_REFERER']);
				} else {
					$data = array(
						'pk_i_id'           => ($advertiserToUpdate) ? $advertiserToUpdate['pk_i_id'] : false,
						's_name'            => Params::getParam('s_name'),
						's_business_sector' => Params::getParam('s_business_sector'),
						'dt_date'           => todaydate(),
						'b_active'          => Params::getParam('b_active')
					);
					if ($advertiserToUpdate) unset($data['dt_date']);
					if (Params::getParam('fk_i_user_id')) {
						$data['fk_i_user_id']   = Params::getParam('fk_i_user_id');
					} else {
						$data['fk_i_user_id']   = null;
					}
					Banners::newInstance()->setAdvertiser($data);
					if (!$advertiserToUpdate) {
						osc_add_flash_ok_message(__('The advertiser it has been added correctly.', BANNERS_PREF), 'admin');
					} else {
						osc_add_flash_ok_message(__('The advertiser it has been updated correctly.', BANNERS_PREF), 'admin');
					}
				}
				ob_get_clean();
				$this->redirectTo($_SERVER['HTTP_REFERER']);
				break;

			case 'delete':
		        $i = 0;
		        $advertisersId = Params::getParam('id');

		        if (!is_array($advertisersId)) {
		            osc_add_flash_error_message(__('Select advertiser.', BANNERS_PREF), 'admin');
		        } else {
		            foreach ($advertisersId as $id) {
		                if (Banners::newInstance()->deleteAdverstiser($id)) $i++;
		            }
		            if ($i == 0) {
		                osc_add_flash_error_message(__('No advertisers have been deleted.', BANNERS_PREF), 'admin');
		            } else {
		                osc_add_flash_ok_message(sprintf(__('%s advertiser(s) have been deleted.', BANNERS_PREF), $i), 'admin');
		            }
		        }
		        ob_get_clean();
		        $this->redirectTo($_SERVER['HTTP_REFERER']);
		        break;

			case 'activate':
				$i = 0;
				$advertisersId = Params::getParam('id');

				if (!is_array($advertisersId)) {
					osc_add_flash_error_message(__('Select advertiser.', BANNERS_PREF), 'admin');
				} else {
					foreach ($advertisersId as $id) {
						$data = array(
							'pk_i_id'   => $id,
							'dt_update' => todaydate(),
							'b_active'  => 1
						);
						if (Banners::newInstance()->setAdvertiser($data)) $i++;
					}
					if ($i == 0) {
						osc_add_flash_error_message(__('No advertiser have been activated.', BANNERS_PREF), 'admin');
					} else {
						osc_add_flash_ok_message(sprintf(__('%s advertiser(s) have been activated.', BANNERS_PREF), $i), 'admin');
					}
				}
				ob_get_clean();
				$this->redirectTo($_SERVER['HTTP_REFERER']);
				break;

			case 'deactivate':
				$i = 0;
				$advertisersId = Params::getParam('id');

				if (!is_array($advertisersId)) {
					osc_add_flash_error_message(__('Select advertiser.', BANNERS_PREF), 'admin');
				} else {
					foreach ($advertisersId as $id) {
						$data = array(
							'pk_i_id'   => $id,
							'dt_update' => todaydate(),
							'b_active'  => 0
						);
						if (Banners::newInstance()->setAdvertiser($data)) $i++;
					}
					if ($i == 0) {
						osc_add_flash_error_message(__('No advertiser have been deactivated.', BANNERS_PREF), 'admin');
					} else {
						osc_add_flash_ok_message(sprintf(__('%s advertiser(s) have been deactivated.', BANNERS_PREF), $i), 'admin');
					}
				}
				ob_get_clean();
				$this->redirectTo($_SERVER['HTTP_REFERER']);
				break;

			default:
				require_once BANNERS_PATH . 'classes/datatables/AdvertisersDataTable.php';

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

                $advertisersDataTable = new AdvertisersDataTable();
                $advertisersDataTable->table($params);
                $aData = $advertisersDataTable->getData();

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
                    array('value' => 'activate', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected advertiser?', BANNERS_PREF), strtolower(__('Activate'))), 'label' => __('Activate')),
                    array('value' => 'deactivate', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected advertiser?', BANNERS_PREF), strtolower(__('Deactivate'))), 'label' => __('Deactivate')),
                    array('value' => 'delete', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected advertiser?', BANNERS_PREF), strtolower(__('Delete'))), 'label' => __('Delete'))
                );

                $bulk_options = osc_apply_filter('advertiser_bulk_filter', $bulk_options);
                $this->_exportVariableToView('bulk_options', $bulk_options);
				break;
		}
	}
}