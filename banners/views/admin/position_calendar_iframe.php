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

// Params obtained from ajax.php page=ajax&action=runhook&hook=custom_plugin_requests&route=position_calendar_iframe&position=5&month=2020-09
$month         = __get('month');                       // If no month has been selected, we put the current and the year: date("Y-m")
$positionId    = __get('positionId');                  // Position ID
$position      = position_by_id($positionId);      // Position by its ID
$banners       = banners_by_position($positionId);  // Array of banners by its ID position

// For build the calendar
$week = 1;
for ($i = 1; $i <= date('t', strtotime($month)); $i++) {
     $day_week = date('N', strtotime($month.'-'.$i));
     $calendar[$week][$day_week] = $i;
     if ($day_week == 7) $week++;
}

// Save an array each one of the days of a interval, to be compared with days of calendar
function daysinterval($fromDate, $toDate, $color) {
     $array = array();
     $date = $fromDate;
     while(strtotime($date) <= strtotime($toDate)) {
          $array[]['date'] = $date;
          $date = date("Y-m-j", strtotime($date . " + 1 day"));
     }
     $array['color'] = $color;
     return $array;
}
$intervals = array();
foreach ($banners as $banner) {
     $intervals[] = daysinterval($banner['dt_from_date'], $banner['dt_to_date'], $banner['s_color']);
}

// Comparison
$comp = array();

function check_values(&$value = null, $key = null) {
     return (!$value) ? false : true;
}
?>

<h3 class="render-title"><?php _e('Position', BANNERS_PREF); ?> <?php if (isset($position['i_sort_id'])) echo $position['i_sort_id']; ?> <?php if (isset($position['s_title']) && $position['s_title']) echo ' - ' . $position['s_title']; ?></h3>

<table class="table" cellpadding="0" cellspacing="0">
     <thead>
          <tr>
               <td colspan="7"><b><?php echo strftime('%B %Y', strtotime($month)); ?></b></td>
          </tr>
          <tr>
               <td colspan="7">
                    <div style="float: left; width: 33.333333333%"><a href="javascript:void(0);" id="calendar-previous-button">&laquo; Previous</a></div>
                    <div style="float: left; width: 33.333333333%"><a href="javascript:void(0);" id="calendar-current-button">Current</a></div>
                    <div style="float: left; width: 33.333333333%"><a href="javascript:void(0);" id="calendar-next-button">Next &raquo;</a></div>
               </td>
          </tr>
          <tr>
               <td>Mon</td>
               <td>Tue</td>
               <td>Wed</td>
               <td>Thu</td>
               <td>Frid</td>
               <td>Sat</td>        
               <td>Sun</td>
          </tr>
     </thead>
   
     <tbody>
          <?php foreach ($calendar as $days) : ?>
          <tr>
          <?php for ($i = 1; $i <= 7; $i++) : ?>
               <?php for ($j = 0; $j < count($intervals); $j++) : // Run intervals to be compared ?>
                    <?php @$comp[$j] = in_array($month.'-'.$days[$i], array_column($intervals[$j], 'date')); ?>
                    <?php if ($comp[$j]) : ?>
                    <td style="background: <?php echo $intervals[$j]['color']; ?>; color: white">
                    <?php endif; ?>
               <?php endfor; // end for ?>

               <?php if (!array_filter($comp, 'check_values')) : // If the comparisions have negative results ?>
                    <td>
               <?php endif; ?>

               <?php echo isset($days[$i]) ? $days[$i] : ''; ?>
               </td>
          <?php endfor; ?>
          </tr>
          <?php endforeach; ?>
     </tbody>
</table>