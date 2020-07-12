<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * My Plugin - It's a basic plugin for Osclass as resource for a tutorial about how to implement it.
 * Copyright (C) 2020  Adrián Olmedo
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

	class AdvertisersDataTable extends DataTable
	{
		public function __construct()
        {
        	osc_add_filter('datatable_advertisers_status_class', array(&$this, 'row_class'));
            osc_add_filter('datatable_advertisers_status_text', array(&$this, '_status'));
        }

        /**
         * Build the table in the php file: controller/admin/crud.php
         *
         * Build the table of all registers with filter and pagination
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

            $advertisers = Banners::newInstance()->advertisers(array(
				'start' 	=> $this->start,
				'limit' 	=> $this->limit,
				'name'  	=> Params::getParam('search'),
				'userId'    => Params::getParam('userId')
			));
			$this->processData($advertisers);

            $this->total = Banners::newInstance()->advertisersTotal();
            $this->total_filtered = $this->total;

            return $this->getData();
        }

        private function addTableHeader()
        {
            $this->addColumn('status-border', '');
            $this->addColumn('status', __('Status'));
            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');

            $this->addColumn('name', __('Name'));
            $this->addColumn('business', __('Business sector', BANNERS_PREF));
            $this->addColumn('banners', __('Banners', BANNERS_PREF));

            $dummy = &$this;
            osc_run_hook("admin_advertisers_table", $dummy);
        }

        private function processData($advertisers)
        {
            if(!empty($advertisers)) {

                foreach($advertisers as $aRow) {
                    $row            = array();
                    $options        = array();
                    $options_more   = array();
                    $moreOptions    = '';

                    // Actions of DataTable
                    $options[] = '<a href="#" onclick="set_advertiser('.$aRow['pk_i_id'].');return false;">'.__('Edit').'</a>';
                    $options[] = '<a href="#" onclick="delete_dialog('.$aRow['pk_i_id'].');return false;">'.__('Delete').'</a>';

                    if( $aRow['b_active'] == 1 ) {
                        $options[]  = '<a href="#" onclick="deactivate_dialog('.$aRow['pk_i_id'].');return false;">' . __('Deactivate') . '</a>';
                    } else {
                        $options[]  = '<a href="#" onclick="activate_dialog('.$aRow['pk_i_id'].');return false;">' . __('Activate') . '</a>';
                    }

                    // more actions
                    $options_more = osc_apply_filter('more_actions_manage_advertisers', $options_more, $aRow);
                    if (count($options_more) > 0 && $options_more != "" && $options_more != NULL) {
                        $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL;
                        foreach( $options_more as $actual ) {
                            $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                        }
                        $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL;
                    }
                    
                    $actions = '';
                    $options = osc_apply_filter('actions_manage_advertisers', $options, $aRow);
                    if (count($options) > 0 && $options != "" && $options != NULL) {
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
                    $row['status']          = $aRow['b_active'];
                    $row['bulkactions']     = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" /></div>';
                    
                    $row['name']            = (get_user_name($aRow['fk_i_user_id'])) ? '<a href="'. osc_admin_base_url(true) . '?page=users&action=edit&id=' . $aRow['fk_i_user_id'] .'">'.get_user_name($aRow['fk_i_user_id']).' ('.get_user_email($aRow['fk_i_user_id']).')'.'</a>' . $actions : $aRow['s_name'] . $actions;
                    $row['business']        = $aRow['s_business_sector'];
                    $row['banners'] 		= Banners::newInstance()->getAdvertiserBannersTotal($aRow['pk_i_id']);

                    $row = osc_apply_filter('banners_advertisers_processing_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }
            }
        }

        public function _status($status)
        {
            return (!$status) ? __('Inactive') : __('Active');
        }

        /**
         * Get the status of the row. There are two status:
         *     - inactive
         *     - active
         */
        private function get_row_status_class($status)
        {
            return (!$status) ? 'status-inactive' : 'status-active';
        }

        public function row_class($status)
        {
            return $this->get_row_status_class($status);
        }

	}