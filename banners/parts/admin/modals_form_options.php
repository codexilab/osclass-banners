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

?>

<form id="modal-500px" method="post" action="" class="has-form-actions hide"></form>

<form id="modal-300px" method="post" action="" class="has-form-actions hide"></form>

<script>
	$(document).ready(function() {
		$("#modal-500px").dialog({
	        autoOpen: false,
	        width: "500px",
	        modal: true,
	        title: '<?php echo osc_esc_js( __('Banners', BANNERS_PREF) ); ?>',
            position: "top"
	    });

	    $("#modal-300px").dialog({
	        autoOpen: false,
	        width: "300px",
	        modal: true,
	        title: '<?php echo osc_esc_js( __('Banners', BANNERS_PREF) ); ?>',
            position: "top"
	    });
	});

	function loading_modal() {
		return '<div class="text-center">Loading...</div>';
	}

	function set_advertiser(advertiser_id = null) {
        $("#modal-500px").html(loading_modal());
        $('#modal-500px').dialog('open');
        var url = '<?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=banners_controller_requests&route=set_advertiser_iframe&id='+advertiser_id;
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html"
        }).done(function(data) {
            $("#modal-500px").html(data);
        });
	};

	function set_position(id = null) {
        var modal = (id == null) ? '#modal-300px' : '#modal-500px';
        $(modal).html(loading_modal());
	    $(modal).dialog('open');
	    var url = '<?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=banners_controller_requests&route=set_position_iframe&id='+id;
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html"
        }).done(function(data) {
            $(modal).html(data);
        });
	};

	/** 
     * pad es una función para poner ceros a la izquierda.
     * n es el número al que queremos poner ceros a la izquierda.
     * lenght es el número de dígitos que tendrá el número resultante.
     * ejemplo: pad(14, 4); devolverá 0014.
     */
    function pad(n, length) {
        var  n = n.toString();
        while(n.length < length)
        	n = "0" + n;
        return n;
    };

    function show_calendar(id, year = null, month = null) {
    	$("#show-calendar-content").html(loading_modal());
        if (year == null && month == null) {
            var year    = '<?php echo date("Y"); ?>';
            var month   = '<?php echo date("m"); ?>';
        }
        var url = '<?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=banners_controller_requests&route=position_calendar_iframe&position='+id+'&month='+year+'-'+month;
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html"
        }).done(function(data) {
            $("#show-calendar-content").html(data);

            $("#calendar-current-button").click(function() {
                show_calendar(id);
            });
            $("#calendar-next-button").click(function() {
                // va aumentando el numero del mes
                month++;
                // si sobre pasa el ultimo mes que dicimebre (12), aumenta de año y reseta el mes a 1 (enero)
                if (month > 12) {
                    year++;
                    month = 1;
                }
                show_calendar(id, year, pad(month, 2));
            });
            $("#calendar-previous-button").click(function() {
                // va restando el numero del mes
                month--;
                // si es menor que el primer mes que es enero (1), disminuye de año y reseta el mes a 12 (diciembre)
                if (month < 1) {
                    year--;
                    month = 12;
                }
                show_calendar(id, year, pad(month, 2));
            });
        });
    };
</script>