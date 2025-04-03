(function($) {
	"use strict";
	var HT = {}; 

    let dateValues = {
        startDate: null,
        endDate: null
    };
    

    HT.setupDatepicker = () => {
        if($('.datepickerReport').length){
            $('.datepickerReport').datetimepicker({
                timepicker:true,
                format:'d/m/Y',
                maxDate:new Date(),
            });
        }
        
    }

    HT.setupDateRangePicker = () => {
        if($('.rangepicker').length > 0){
            $('.rangepicker').daterangepicker({
                timePicker: true,
                locale: {
                    format: 'dd-mm-yy'
                }
            })
        }
    }

	$(document).ready(function(){
        HT.setupDatepicker()
        HT.setupDateRangePicker()
	});

})(jQuery);
