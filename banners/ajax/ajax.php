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

class CBannersAdminAjax extends AdminSecBaseModel
{
	//Business Layer...
    public function doModel()
    {
        switch (Params::getParam("route")) {
            case 'set_advertiser_iframe':
                $advertiserToUpdate = Banners::newInstance()->getAdvertiserById(Params::getParam('id'));
                $this->_exportVariableToView('advertiserToUpdate', $advertiserToUpdate);
                $this->doView('admin/set_advertiser_iframe.php');
                break;

            case 'set_position_iframe':
            	$positionToUpdate = Banners::newInstance()->getPositionById(Params::getParam('id'));
                $this->_exportVariableToView('positionToUpdate', $positionToUpdate);
                $this->doView('admin/set_position_iframe.php');
                break;

            case 'position_calendar_iframe':
            	$positionId = (Params::getParam('position')) ? Params::getParam('position') : 0;    			
    			// If no month has been selected, we put the current and the year
    			$month 		= (Params::getParam('month')) ? Params::getParam('month') : date("Y-m");
    			
    			$this->_exportVariableToView('positionId', $positionId);
    			$this->_exportVariableToView('month', $month);

            	$this->doView('admin/position_calendar_iframe.php');
            	break;

            default:
                echo __('no action defined');
                break;
        }
    }

    //hopefully generic...
    function doView($file)
    {
        include BANNERS_PATH . 'views/'.$file;
    }
}