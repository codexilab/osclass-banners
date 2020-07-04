<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
	 * PackagesDataTable class
	 *
	 * @package Packages
	 * @subpackage classes
	 * @author CodexiLab
	 */
	class BannersDataTable extends DataTable
	{
		
		public function __construct()
		{
			osc_add_filter('datatable_banners_status_class', array(&$this, 'row_class'));
			osc_add_filter('datatable_banners_status_text', array(&$this, '_status'));
		}

		/**
         * Build the table in the php file: views/admin/banners.php
         *
         * Build the table of all banners with search and pagination
         *
         * @access public
         * @param array $params
         * @return array
         */
		public function table($params)
		{
			$this->addTableHeader();

            $start = ((int)$params['iPage']-1) * $params['iDisplayLength'];

            $this->start = intval($start);
            $this->limit = intval($params['iDisplayLength']);

            $banners = Banners::newInstance()->banners(array(
                'start' => $this->start,
                'limit' => $this->limit,

                'sort'                  => Params::getParam('sort'),
                'direction'             => Params::getParam('direction'),

                'fk_i_advertiser_id'    => Params::getParam('advertiserId'),
                'fk_i_position_id'      => Params::getParam('positionId'),
                's_url'                 => Params::getParam('s_url'),
                's_content_type'        => Params::getParam('s_type'),
                'dt_since_date'         => Params::getParam('sinceDate'),
                'since_control'         => Params::getParam('sinceDateControl'),
                'dt_until_date'         => Params::getParam('untilDate'),
                'until_control'         => Params::getParam('untilDateControl'),
                'dt_date'               => Params::getParam('date'),
                'date_control'          => Params::getParam('dateControl'),
                'dt_update'             => Params::getParam('update'),
                'update_control'        => Params::getParam('updateControl'),
                'b_active'              => Params::getParam('b_active')
            ));
            $this->processData($banners);

            $this->total = Banners::newInstance()->total();
            $this->total_filtered = $this->total;

            return $this->getData();
		}

		/**
         * Build the haeder table
         *
         * Add html columns in the header of table
         *
         * @access private
         */
        private function addTableHeader()
        {
            $this->addColumn('status-border', '');
            $this->addColumn('status', __('Status'));
            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');

            $this->addColumn('category', __('Category', BANNERS_PREF));
            $this->addColumn('position', __('Position', BANNERS_PREF));
            $this->addColumn('advertiser', __('Advertiser', BANNERS_PREF));
            $this->addColumn('banner', __('Banner', BANNERS_PREF));
            $this->addColumn('date-intervals', __('Since/Until', BANNERS_PREF));
            $this->addColumn('clicks', __('Clicks', BANNERS_PREF));

            $dummy = &$this;
            osc_run_hook("admin_banners_table", $dummy);
        }

		/**
		 * Build the rows content of the table
		 *
		 * Add html rows with her contents
		 *
		 * @access private
		 * @param array $banners
		 */
		private function processData($banners)
		{
			if(!empty($banners)) {
				foreach($banners as $aRow) {
					$row = array();

					$options        = array();
					$options_more   = array();
					$moreOptions 	= '';

					$options[] = '<a href="'.osc_route_admin_url('banners-admin-set').'&banner='.$aRow['pk_i_id'].'">' . __("Edit", BANNERS_PREF) . '</a>';
					$options[] = '<a href="#" onclick="delete_dialog('.$aRow['pk_i_id'].');return false;">' . __("Delete", BANNERS_PREF) . '</a></center>';

					// more actions
					if (count($options_more) > 0) {
						$options_more = osc_apply_filter('more_actions_manage_banners', $options_more, $aRow);
						$moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __("Show more...", BANNERS_PREF) .'</a>'. PHP_EOL .'<ul>'. PHP_EOL;
						foreach( $options_more as $actual ) {
							$moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
						}
						$moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL;
					}

					$actions = '';
					if (count($options) > 0) {
						$options = osc_apply_filter('actions_manage_banners', $options, $aRow);
						// create list of actions
						$auxOptions = '<ul>'.PHP_EOL;
						foreach( $options as $actual ) {
							$auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
						}
						$auxOptions  .= $moreOptions;
						$auxOptions  .= '</ul>'.PHP_EOL;

						$actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;
					}

					$row['status-border']   = '';
					$row['status'] 			= $aRow['b_active'];
					$row['bulkactions'] 	= '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" />';

					$category = '';
					if ($aRow['s_category'] != 'all') {
						$categoriesID = explode(',', $aRow['s_category']);
						$categories = array();
						foreach ($categoriesID as $categoryID) {
							$c = Category::newInstance()->findRootCategory($categoryID);
							$categories[] = ($c) ? '- '.$c['s_name'] : '::category no exist::';
                        }
						$category = implode('<br>', $categories);
					} else if (is_int($aRow['s_category'])) {
						$category = ($c) ? '- '.$c['s_name'] : '::category no exist::';
					} else {
						$category = 'All';
					}
					$row['category'] 		= $category . $actions;

					$year 		= date("Y", strtotime($aRow['dt_until_date']));
					$month 		= date("m", strtotime($aRow['dt_until_date']));
					$position 	= position_by_id($aRow['fk_i_position_id']);
					$position['s_title'] = (isset($position['s_title'])) ? ' title="'.$position['s_title'].'"' : '';
					$row['position'] 		= '<a'.$position['s_title'].' href="#" onclick="show_position('.$aRow['fk_i_position_id'].', '.$year.', '.$month.');return false;"><div class="center"><div class="text-center">'.banners_sort_position($aRow['fk_i_position_id']).'</div><div class="color-banner-box" style="background: '.$aRow['s_color'].';"></div></div></a>';
					
					$advertiser = Banners::newInstance()->getAdvertiserById($aRow['fk_i_advertiser_id']);
					$row['advertiser'] 		= (get_user_name($advertiser['fk_i_user_id'])) ? '<a href="'. osc_admin_base_url(true) . '?page=users&action=edit&id=' . $advertiser['fk_i_user_id'] .'">'.get_user_name($advertiser['fk_i_user_id']).' ('.get_user_email($advertiser['fk_i_user_id']).')'.'</a>' : $advertiser['s_name'];

					$banner = '';
					if ($aRow['b_image']) {
						$banner = '<div class="text-center"><a href="#" onclick="show_banner(\''.BANNERS_ROUTE_SOURCES.$aRow['s_name'].'.'.$aRow['s_extension'].'\');return false;">' . __("View", BANNERS_PREF) . '</a><br>'.$aRow['s_content_type'].'</div>';
					} else {
						$banner = '<div class="text-center">'.htmlentities("</script>").'</div>';
					}
					$row['banner'] 			= $banner;

					$sinceDate = osc_format_date($aRow['dt_since_date'], osc_date_format());
                    $untilDate = osc_format_date($aRow['dt_until_date'], osc_date_format());
                    $row['date-intervals'] 	= $sinceDate.'/'.$untilDate;

                    $clicks = Banners::newInstance()->countClicksByBannerId($aRow['pk_i_id']);
                    $row['clicks'] 			= '<div class="text-center">'.$clicks.'</div>';

                    $row = osc_apply_filter('banners_processing_row', $row, $aRow);

                    $this->addRow($row);
			    	$this->rawRows[] = $aRow;

				}
			}
		}

		public function _status($status)
		{
			return (!$status) ? __('Inactive') : __('Active');
		}

		public function row_class($row)
		{
			$status_class = $this->get_row_status_class($row['status']);
			return $status_class;
		}

		private function get_row_status_class($status)
		{
			return (!$status) ? 'status-inactive' : 'status-active';
		}

	}