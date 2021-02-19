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
 * Model of Banners
 */
class Banners extends DAO
{
	private static $instance;

	/**
	 * Singleton Pattern
	 */
	public static function newInstance()
	{
		if(!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	function __construct()
	{
		parent::__construct();
	}

	public function getTable_banners_positions()
	{
		return DB_TABLE_PREFIX.'t_banners_positions';
	}

	public function getTable_banners_advertisers()
	{
		return DB_TABLE_PREFIX.'t_banners_advertisers';
	}

	public function getTable_banners()
	{
		return DB_TABLE_PREFIX.'t_banners';
	}

	public function getTable_banners_clicks()
	{
		return DB_TABLE_PREFIX.'t_banners_clicks';
	}

	/**
	 * Import tables to database using a sql file
	 */
	public function import($file)
	{
		$sql  = file_get_contents($file);

		if(!$this->dao->importSQL($sql)) {
			throw new Exception("Error importSQL::Banners".$file);
		}
	}

	/**
	 * Config the plugin in osclass database, settings the preferences table 
	 * and import sql tables of plugin from struct.sql
	 */
	public function install()
	{
		$this->import(BANNERS_PATH.'struct.sql');
		osc_set_preference('version', '1.0.2', BANNERS_PREF, 'STRING');
		osc_set_preference('banner_route_page', 'banner-url', BANNERS_PREF, 'STRING');
		osc_set_preference('banner_route_param', 'ref', BANNERS_PREF, 'STRING');
		osc_set_preference('show_url_banner', '0', BANNERS_PREF, 'BOOLEAN');
		osc_run_hook('banners_install');
	}

	/**
	 * Delete all fields from the 'preferences' table and also delete all tables of plugin
	 */
	public function uninstall()
	{
        // Delete all files from BANNERS_FOLDER_SOURCES
        $exclude = array('php'); // Exclude .php files
        $files = glob(BANNERS_FOLDER_SOURCES . '*');
        foreach($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if(!in_array($extension, $exclude)) @unlink($file);
        }

		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_banners_clicks()));
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_banners()));
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_banners_advertisers()));
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_banners_positions()));
		Preference::newInstance()->delete(array('s_section' => BANNERS_PREF));
		osc_run_hook('banners_uninstall');
	}

    /**
     * Add clic
     */
    public function addClick($data)
    {
        return $this->dao->insert($this->getTable_banners_clicks(), $data);
    }

	/**
     * Delete clic
     */
    public function deleteClickById($id)
    {
        return $this->dao->delete($this->getTable_banners_clicks(), array('pk_i_id' => $id));
    }


	/**
     * Get all clicks of a specific banner
     *
     * @access public
     * @param int $bannerId
     * @return array
     */
    public function getClicksByBannerId($bannerId)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTable_banners_clicks());
        $this->dao->where('fk_i_banner_id', $bannerId);
        $result = $this->dao->get();
        if($result) {
            return $result->result();
        }
        return array();
    }

	/**
	 * Count clicks a banner
	 *
	 * @access public
	 * @param int $bannerId
	 * @return int
	 */
	public function countClicksByBannerId($bannerId)
	{
		$this->dao->select('COUNT(*) as total') ;
		$this->dao->from($this->getTable_banners_clicks());
		$this->dao->where('fk_i_banner_id', $bannerId);
		$result = $this->dao->get();
		if($result) {
			$row = $result->row();
			if(isset($row['total'])) {
				return $row['total'];
			}
		}
		return 0;
	}

    /**
     * addAvertiser: Create/Update advertiser
     */
    public function setAdvertiser($data)
    {
        // Create
        if (!$data['pk_i_id']) {
            unset($data['pk_i_id']);
            return $this->dao->insert($this->getTable_banners_advertisers(), $data);

        // Update
        } else {
            return $this->dao->update($this->getTable_banners_advertisers(), $data, array('pk_i_id' => $data['pk_i_id']));
        }
    }

    /**
     * Delete advertiser.
     *
     * Note: Firts delete all your banners.
     *
     * @access public
     * @param integer $id
     * @return bool Return true if has been deleted, on contrary will return false.
     */
    public function deleteAdverstiser($id)
    {
        $banners = $this->getByAdvertiserId($id);
        if($banners) {
            foreach ($banners as $banner) {
                $this->delete($banner['pk_i_id']);
            }
        }
        return $this->dao->delete($this->getTable_banners_advertisers(), array('pk_i_id' => $id));
    }

	/**
	 * Get all advertisers
	 *
	 * @access public
	 * @return array
	 */
	public function getAllAdvertisers()
	{
		$this->dao->select('*');
		$this->dao->from($this->getTable_banners_advertisers());
		$this->dao->orderBy('s_name', 'ASC');
		$result = $this->dao->get();
		if($result) {
			return $result->result();
		}
		return array();
	}

    /**
     * Search advertisers
     *
     * This function is for search with parameters in the AdvertisersDataTable.php
     *
     * @access public
     * @param array $params Is a array variable witch containt all parameters for the search and pagination
     * @return array
     */
    public function advertisers($params)
    {
        $start = (isset($params['start']) && $params['start']!='' ) ? $params['start']: 0;
        $limit = (isset($params['limit']) && $params['limit']!='' ) ? $params['limit']: 10;
        $name = (isset($params['name']) && $params['name']!='') ? $params['name'] : '';
        $userId = (isset($params['userId']) && $params['userId']!='') ? $params['userId'] : '';

        $this->dao->select('*');
        $this->dao->from($this->getTable_banners_advertisers());
        $this->dao->orderBy('dt_date', 'DESC');
        if ($name!='') {
            $this->dao->like('s_name', $name);
            $this->dao->orLike('s_business_sector', $name);
            if (strtolower($name) == 'active') {
                $this->dao->orLike('b_active', 1);
            }
            if (strtolower($name) == 'deactive') {
                $this->dao->orLike('b_active', 0);
            }
        }

        if ($userId != '') {
            $this->dao->orLike('fk_i_user_id', $userId);
        }

        $this->dao->limit($limit, $start);
        $result = $this->dao->get();
        if($result) {
            return $result->result();
        }
        return array();
    }

    /**
     * Count total advertisers
     *
     * @access public
     * @return integer
     */
    public function advertisersTotal()
    {
        $this->dao->select('COUNT(*) as total') ;
        $this->dao->from($this->getTable_banners_advertisers());
        $result = $this->dao->get();
        if($result) {
            $row = $result->row();
            if(isset($row['total'])) {
                return $row['total'];
            }
        }
        return 0;
    }

    /**
     * Get advertiser by is user registered
     *
     * @access public
     * @param integer $userId
     * @return array
     */
    public function getAdvertiserByUserId($userId)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTable_banners_advertisers());
        $this->dao->where('fk_i_user_id', $userId);
        $result = $this->dao->get();
        if($result) {
            return $result->result();
        }
        return array();
    }

	/**
     * Get advertiser
     *
     * @access public
     * @param integer $id
     * @return array
     */
    public function getAdvertiserById($id)
    {
    	$this->dao->select('*');
        $this->dao->from($this->getTable_banners_advertisers());
        $this->dao->where('pk_i_id', $id);
        $result = $this->dao->get();
        if($result) {
            return $result->row();
        }
        return false;
    }

    /**
     * addPosition: Create/Update position
     */
    public function setPosition($data)
    {
        // Create
        if (!$data['pk_i_id']) {
            unset($data['pk_i_id']);
            return $this->dao->insert($this->getTable_banners_positions(), $data);

        // Update
        } else {
            return $this->dao->update($this->getTable_banners_positions(), $data, array('pk_i_id' => $data['pk_i_id']));
        }
    }

    /**
     * Count total of positions
     *
     * @access public
     * @return integer
     */
    public function positionsTotal()
    {
        $this->dao->select('COUNT(*) as total') ;
        $this->dao->from($this->getTable_banners_positions());
        $result = $this->dao->get();
        if($result) {
            $row = $result->row();
            if(isset($row['total'])) {
                return $row['total'];
            }
        }
        return 0;
    }

	/**
	 * Get all positions
	 *
	 * @access public
	 * @param integer $bannerId
	 * @return array
	 */
	public function getAllPositions()
	{
		$this->dao->select('*');
		$this->dao->from($this->getTable_banners_positions());
		$this->dao->orderBy('i_sort_id', 'ASC');
		$result = $this->dao->get();
		if($result) {
			return $result->result();
		}
		return array();
	}

	/**
     * Get position by it's ID
     *
     * @access public
     * @param integer $id
     * @return array
     */
	public function getPositionById($id)
	{
		$this->dao->select('*');
		$this->dao->from($this->getTable_banners_positions());
		$this->dao->where('pk_i_id', $id);
		$result = $this->dao->get();
		if($result) {
			return $result->row();
		}
		return false;
	}

    /**
     * Get position by sort number
     *
     * @access public
     * @param integer $sortId
     * @return array
     */
    public function getPositionBySortId($sortId)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTable_banners_positions());
        $this->dao->where('i_sort_id', $sortId);
        $result = $this->dao->get();
        if($result) {
            return $result->row();
        }
        return false;
    }

    /**
     * Delete position
     */
    public function deletePositionById($id)
    {
        return $this->dao->delete($this->getTable_banners_positions(), array('pk_i_id' => $id));
    }

    /**
     * getBannerByName: Get banner by her image name
     *
     * @access public
     * @param string $name
     * @return array
     */
    public function getByName($name)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTable_banners());
        $this->dao->where('s_name', $name);
        $result = $this->dao->get();
        if($result) {
            return $result->row();
        }
        return false;
    }


    /**
     * getBannerByURL: Get banner by her URL
     *
     * @access public
     * @param string $url
     * @return array
     */
    public function getByURL($url)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTable_banners());
        $this->dao->where('s_url', $url);
        $result = $this->dao->get();
        if($result) {
            return $result->row();
        }
        return false;
    }

    /**
     * detectBannerByColorAndPosition: Count banner by her Color
     *
     * @access public
     * @param string $url
     * @return array
     */
    public function detectByColorAndPosition($color, $positionId)
    {
        $this->dao->select('COUNT(*) as total') ;
        $this->dao->from($this->getTable_banners());
        $this->dao->where('s_color', $color);
        $this->dao->where('fk_i_position_id', $positionId);
        $result = $this->dao->get();
        if($result) {
            $row = $result->row();
            if(isset($row['total'])) {
                return $row['total'];
            }
        }
        return 0;
    }

    /**
     * Get an array of banners primary key Id in a date range of position
     * 
     * @access public
     * @param string $fromDate
     * @param string $toDate
     * @param string $positionId
     * @return array
     */
    public function getByDateRange($fromDate, $toDate, $positionId)
    {
        $this->dao->select('pk_i_id');
        $this->dao->from($this->getTable_banners());
        $this->dao->where('fk_i_position_id', $positionId);
        $this->dao->where("(('$fromDate' BETWEEN dt_from_date AND date_sub(dt_to_date, interval +1 day)) 
                            OR ('$toDate' BETWEEN date_sub(dt_from_date, interval -1 day) AND dt_to_date) 
                            OR (dt_from_date <= '$fromDate' AND dt_to_date >= '$fromDate')
                            OR (dt_from_date >= '$fromDate' AND dt_to_date <= '$toDate'))");
        $result = $this->dao->get();
        if($result) {
            return $result->result();
        }
        return array();
    }

	/**
	 * addBanner: Setting information about banner uploaded
	 * 
	 * In this same function can be used for add or update, depending if exist a banner ID.
	 * If exist banner ID, proceed to update, on contrary add new.
	 *
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function set($data)
	{
		// Add
		if (!$data['pk_i_id']) {
			unset($data['pk_i_id']);
			return $this->dao->insert($this->getTable_banners(), $data);

		// Update
		} else {
			return $this->dao->update($this->getTable_banners(), $data, array('pk_i_id' => $data['pk_i_id']));
		}
	}

    /**
     * Count total banners from a especific advertiser
     *
     * This function is used for show the total uploaded banners from a advertiser specific by her id.
     *
     * @access public
     * @param array $advertiserId
     * @return integer
     */
    public function getAdvertiserBannersTotal($advertiserId)
    {
        $this->dao->select('COUNT(*) as total') ;
        $this->dao->from($this->getTable_banners());
        $this->dao->where('fk_i_advertiser_id', $advertiserId);
        $result = $this->dao->get();
        if($result) {
            $row = $result->row();
            if(isset($row['total'])) {
                return $row['total'];
            }
        }
        return 0;
    }

    /**
     * getBannerById: Get especific banner by it's ID
     *
     * @access public
     * @param array $id
     * @return array
     */
    public function getById($id)
    {
    	$this->dao->select('*');
        $this->dao->from($this->getTable_banners());
        $this->dao->where('pk_i_id', $id);
        $result = $this->dao->get();
        if($result) {
            return $result->row();
        }
        return false;
    }

    /**
     * getBannersByPositionId: Get banners by her position
     *
     * @access public
     * @param integer $positionId
     * @return array
     */
    public function getByPositionId($positionId)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTable_banners());
        $this->dao->where('fk_i_position_id', $positionId);
        $result = $this->dao->get();
        if($result) {
            return $result->result();
        }
        return array();
    }

    /**
     * getBannersByAdvertiserId: Get banners by a especific id advertiser
     *
     * @access public
     * @param array $advertiserId
     * @return array
     */
    public function getByAdvertiserId($advertiserId)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTable_banners());
        $this->dao->where('fk_i_advertiser_id', $advertiserId);
        $result = $this->dao->get();
        if($result) {
            return $result->result();
        }
        return array();
    }

    /**
     * Search banners
     *
     * This function is for thorough search with parameters in the BannersDataTable.php.
     * The results can be ordered by date, update, from date, to date, position of banner, ascendant or descendant.
     *
     * @access public
     * @param array $params Is a array variable witch containt all parameters for the search and pagination
     * @return array
     */
    public function banners($params)
	{
		$start = (isset($params['start']) && $params['start'] != '' ) ? $params['start']: 0;
        $limit = (isset($params['limit']) && $params['limit'] != '' ) ? $params['limit']: 10;

        $sort = (isset($params['sort']) && $params['sort'] != '') ? $params['sort'] : '';
        $sort = strtolower($sort);

        switch ($sort) {
        	case 'date':
        		$sort = 'dt_date';
        		break;
        	case 'update':
        		$sort = 'dt_update';
        		break;
        	case 'from_date':
        		$sort = 'dt_from_date';
        		break;
        	case 'to_date':
        		$sort = 'dt_to_date';
        		break;
        	case 'position':
        		$sort = 'fk_i_position_id';
        		break;
        	default:
        		$sort = 'dt_date';
        		break;
        }

        $direction = (isset($params['direction']) && $params['direction'] == 'ASC') ? $params['direction'] : 'DESC';
        $direction = strtoupper($direction);

        $advertiserId = (isset($params['fk_i_advertiser_id']) && $params['fk_i_advertiser_id']!='') ? $params['fk_i_advertiser_id'] : '';
        $positionId = (isset($params['fk_i_position_id']) && $params['fk_i_position_id']!='') ? $params['fk_i_position_id'] : '';
        $url = (isset($params['s_url']) && $params['s_url']!='') ? $params['s_url'] : '';
        $imageType = (isset($params['s_content_type']) && $params['s_content_type']!='') ? $params['s_content_type'] : '';

        $fromDate = (isset($params['dt_from_date']) && $params['dt_from_date']!='') ? $params['dt_from_date'] : '';
        $fromDateControl = (isset($params['from_date_control']) && $params['from_date_control']!='') ? $params['from_date_control'] : '';

        $toDate = (isset($params['dt_to_date']) && $params['dt_to_date']!='') ? $params['dt_to_date'] : '';
        $toDateControl = (isset($params['to_date_control']) && $params['to_date_control']!='') ? $params['to_date_control'] : '';

        $date = (isset($params['dt_date']) && $params['dt_date']!='') ? $params['dt_date'] : '';
        $dateControl = (isset($params['date_control']) && $params['date_control']!='') ? $params['date_control'] : '';

        $update = (isset($params['dt_update']) && $params['dt_update']!='') ? $params['dt_update'] : '';
        $updateControl = (isset($params['update_control']) && $params['update_control']!='') ? $params['update_control'] : '';

        $status = (isset($params['b_active']) && $params['b_active']!='') ? $params['b_active'] : '';

        $this->dao->select('*');
        $this->dao->from($this->getTable_banners());
        $this->dao->orderBy($sort, $direction);

        if ($advertiserId != '') {
        	$this->dao->where('fk_i_advertiser_id', $advertiserId);
        }

        if ($positionId != '') {
        	$this->dao->where('fk_i_position_id', $positionId);
        }

        if ($imageType != '') {
            if ($imageType == "script") {
                $this->dao->where('b_image', 0);
            } else {
                switch ($imageType) {
                    case "gif":
                        $imageType = 'image/gif';
                        break;

                    case "jpg":
                        $imageType = 'image/jpg';
                        break;

                    case "bmp":
                        $imageType = 'image/bmp';
                        break;

                    case "png":
                        $imageType = 'image/png';
                        break;
                }
                $this->dao->where('s_content_type', $imageType);
            }
        }

        if ($fromDate != '') {
        	switch ($fromDateControl) {
        		case 'equal':
        			$this->dao->where('dt_from_date', $fromDate);
        			break;

        		case 'greater':
        			$this->dao->where("dt_from_date > '$fromDate'");
        			break;

        		case 'greater_equal':
        			$this->dao->where("dt_from_date >= '$fromDate'");
        			break;

        		case 'less':
        			$this->dao->where("dt_from_date < '$fromDate'");
        			break;

        		case 'less_equal':
        			$this->dao->where("dt_from_date <= '$fromDate'");
        			break;

        		case 'not_equal':
        			$this->dao->where("dt_from_date != '$fromDate'");
        			break;
        		
        		default:
        			$this->dao->where('dt_from_date', $fromDate);
        			break;
        	}	
        }

        if ($toDate != '') {
        	switch ($toDateControl) {
        		case 'equal':
        			$this->dao->where('dt_to_date', $toDate);
        			break;

        		case 'greater':
        			$this->dao->where("dt_to_date > '$toDate'");
        			break;

        		case 'greater_equal':
        			$this->dao->where("dt_to_date >= '$toDate'");
        			break;

        		case 'less':
        			$this->dao->where("dt_to_date < '$toDate'");
        			break;

        		case 'less_equal':
        			$this->dao->where("dt_to_date <= '$toDate'");
        			break;

        		case 'not_equal':
        			$this->dao->where("dt_to_date != '$toDate'");
        			break;
        		
        		default:
        			$this->dao->where('dt_to_date', $toDate);
        			break;
        	}
        }

        if ($date != '') {
        	switch ($dateControl) {
        		case 'equal':
        			$this->dao->where('dt_date', $date);
        			break;

        		case 'greater':
        			$this->dao->where("dt_date > '$date'");
        			break;

        		case 'greater_equal':
        			$this->dao->where("dt_date >= '$date'");
        			break;

        		case 'less':
        			$this->dao->where("dt_date < '$date'");
        			break;

        		case 'less_equal':
        			$this->dao->where("dt_date <= '$date'");
        			break;

        		case 'not_equal':
        			$this->dao->where("dt_date != '$date'");
        			break;
        		
        		default:
        			$this->dao->where('dt_date', $date);
        			break;
        	}
        }

        if ($update != '') {
        	switch ($updateControl) {
        		case 'equal':
        			$this->dao->where('dt_update', $update);
        			break;

        		case 'greater':
        			$this->dao->where("dt_update > '$update'");
        			break;

        		case 'greater_equal':
        			$this->dao->where("dt_update >= '$update'");
        			break;

        		case 'less':
        			$this->dao->where("dt_update < '$update'");
        			break;

        		case 'less_equal':
        			$this->dao->where("dt_update <= '$update'");
        			break;

        		case 'not_equal':
        			$this->dao->where("dt_update != '$update'");
        			break;
        		
        		default:
        			$this->dao->where('dt_update', $update);
        			break;
        	}
        }

        if ($status != '') {
        	if ($status == 0) {
	        	$this->dao->where('b_active', 0);
	        } else {
	        	$this->dao->where('b_active', 1);
	        }
        }

        if ($url != '') {
        	$this->dao->like('s_url', $url);
        }
        
        $this->dao->limit($limit, $start);
        $result = $this->dao->get();
        if($result) {
            return $result->result();
        }
        return array();
	}

	/**
     * bannersTotal: Get total of banners
     *
     * @access public
     * @return integer
     */
	public function total()
	{
        $this->dao->select('COUNT(*) as total') ;
        $this->dao->from($this->getTable_banners());
        $result = $this->dao->get();
        if($result) {
            $row = $result->row();
            if(isset($row['total'])) {
                return $row['total'];
            }
        }
        return 0;
    }

	/**
     * deleteBanner: Delete banner by it's ID
     *
     * Notes: Firts delete all her clicks; delete banner file if exist!
     *
     * @access public
     * @param integer $id
     * @return bool
     */
    public function delete($id)
    {
        $clicks = $this->getClicksByBannerId($id);
        if ($clicks) {
            foreach ($clicks as $click) {
                $this->deleteClickById($click['pk_i_id']);
            }
        }

    	$banner = $this->getById($id);

        $banner = BANNERS_FOLDER_SOURCES . $banner['s_name'].'.'.$banner['s_extension'];
        if (image_exists($banner) === true) {
            @unlink($banner);
        }
        return $this->dao->delete($this->getTable_banners(), array('pk_i_id' => $id));
    }

}