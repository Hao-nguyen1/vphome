(function($) {
	"use strict";
	var HT = {}; 

    HT.seoPreview = () => {
        $('input[name=meta_title]').on('keyup', function(){
            let input = $(this)
            let value = input.val()
            $('.meta-title').html(value)
        })


       $('.seo-canonical').each(function(){
            let _this = $(this)
            _this.css({
                'padding-left':   parseInt($('.baseUrl').outerWidth()) + 10
            })
       })

       $('input[name=canonical]').on('keyup', function(){
            let input = $(this)
            let value = HT.removeUtf8(input.val())
            $('.canonical').html(BASE_URL + value + SUFFIX) 
        })

       


        $(document).on('keyup', '.change-title', function(e){
            let _this = $(this)
            let flag = _this.attr('data-flag')
            let canonical = HT.removeUtf8(_this.val())
            if(flag == 0){
                $('.seo-canonical').val(canonical).trigger('keyup')
            }
        })
    }

    HT.checkSeoDescriptionLength = () => {
        $('textarea[name=meta_description]').on('keyup change', function(){
            let input = $(this)
            let value = input.val()
            $('.countD').html(value.length)
            $('.meta-description').html(value)
            if(value.length > 160){
                input.css({
                    'border': '1px solid red'
                })
                $('.countD').css({
                    'color': 'red'
                })
            }else{
                input.css({
                    'border': '1px solid #c4cdd5'
                })
                $('.countD').css({
                    'color': '#676a6c'
                })
            }

        })
    }

    HT.removeUtf8 = (str) => {
        str = str.toLowerCase(); // chuyen ve ki tu biet thuong
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|,|\.|\:|\;|\'|\–| |\"|\&|\#|\[|\]|\\|\/|~|$|_/g, "-");
        str = str.replace(/-+-/g, "-");
        str = str.replace(/^\-+|\-+$/g, "");
        return str;
    }




	$(document).ready(function(){
        HT.seoPreview()
        HT.checkSeoDescriptionLength()
	});

    

})(jQuery);
