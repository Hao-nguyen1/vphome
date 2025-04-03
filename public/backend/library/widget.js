(function($) {
	"use strict";
	var HT = {}; 
    var _token = $('meta[name="csrf-token"]').attr('content');
    var typingTimer;
    var doneTyingInterval = 300; // 1s

    HT.searchModel = () => {
        $(document).on('keyup', '.search-model', function(e){
            e.preventDefault()
            let _this = $(this)
            if($('input[type=radio]:checked').length === 0){
                alert('Bạn chưa chọn Module');
                _this.val('')
                return false;
            }

            
            let keyword = _this.val()
            let option = {
                model: $('input[type=radio]:checked').val(),
                keyword : keyword
            }
            HT.sendAjax(option)
            
        })
    }

    HT.chooseModel = () => {
        $(document).on('change', '.input-radio', function(){
            let _this = $(this)
            let option = {
                model: _this.val(),
                keyword : $('.search-model').val()
            }
            $('.search-model-result').html('');
            if(option.keyword.length >= 2){
                HT.sendAjax(option)
            }
            

        })
    }

    HT.sendAjax = (option) => {
        clearTimeout(typingTimer);
            typingTimer = setTimeout(function(){
                $.ajax({
                    url: 'ajax/dashboard/findModelObject', 
                    type: 'GET', 
                    data: option,
                    dataType: 'json', 
                    success: function(res) {
                        let html = HT.renderSearchResult(res)
                        if(html.length){
                            $('.ajax-search-result').html(html).show()
                        }else{
                            $('.ajax-search-result').html(html).hide()
                        }
                    },
                    beforeSend: function() {
                        $('.ajax-search-result').html('').hide()
                    },
                });
               
            }, doneTyingInterval)
    }

    HT.renderSearchResult = (data) => {
        let html = ''
        if(data.length){
            for(let i = 0; i < data.length; i++){

                let flag = ($('#model-'+data[i].id).length) ? 1 : 0;
                let setChecked = ($('#model-'+data[i].id).length) ? HT.setChecked() : ''

                html += `<button 
                            class="ajax-search-item" 
                            data-flag="${flag}" 
                            data-canonical="${data[i].languages[0].pivot.canonical}" data-image="${data[i].image}" 
                            data-name="${data[i].languages[0].pivot.name}" 
                            data-id="${data[i].id}"
                        >
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <span>${data[i].languages[0].pivot.name}</span>
                    <div class="auto-icon">
                        ${setChecked}
                    </div>
                </div>
            </button>`
            }
        }
        return html
    }

    HT.setChecked = () => {
        return '<svg class="svg-next-icon button-selected-combobox svg-next-icon-size-12" width="12" height="12"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26"><path d="m.3,14c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l1.4-1.4c0.4-0.4 1-0.4 1.4,0l.1,.1 5.5,5.9c0.2,0.2 0.5,0.2 0.7,0l13.4-13.9h0.1v-8.88178e-16c0.4-0.4 1-0.4 1.4,0l1.4,1.4c0.4,0.4 0.4,1 0,1.4l0,0-16,16.6c-0.2,0.2-0.4,0.3-0.7,0.3-0.3,0-0.5-0.1-0.7-0.3l-7.8-8.4-.2-.3z"></path></svg></svg>'
    }


    HT.unfocusSearchBox = () => {
        $(document).on('click', 'html', function(e){
            if(!$(e.target).hasClass('search-model-result') && !$(e.target).hasClass('search-model')){
                $('.ajax-search-result').html('')
            }
        })

        $(document).on('click', '.ajax-search-result', function(e){
            e.stopPropagation();
        })
    }

    HT.addModel = () => {
        $(document).on('click', '.ajax-search-item' , function(e){
            e.preventDefault()
            let _this = $(this)
            let data = _this.data()
            let html = HT.modelTemplate(data)
            let flag = _this.attr('data-flag')
            if(flag == 0){
                _this.find('.auto-icon').html(HT.setChecked())
                _this.attr('data-flag', 1)
                $('.search-model-result').append(HT.modelTemplate(data))
            }else{
                $('#model-'+data.id).remove()
                _this.find('.auto-icon').html('')
                _this.attr('data-flag', 0)
            }
        })
    }


    HT.modelTemplate = (data) => {
        let html = `<div class="search-result-item" id="model-${data.id}" data-modelid="${data.id}">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="uk-flex uk-flex-middle">
                    <span class="image img-cover"><img src="${data.image}" alt=""></span>
                    <span class="name">${data.name}</span>
                    <div class="hidden">
                        <input type="text" name="modelItem[id][]" value="${data.id}">
                        <input type="text" name="modelItem[name][]" value="${data.name}">
                        <input type="text" name="modelItem[image][]" value="${data.image}">
                    </div>
                </div>
                <div class="deleted">
                    <svg class="svg-next-icon svg-next-icon-size-12" width="12" height="12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                            <path d="M18.263 16l10.07-10.07c.625-.625.625-1.636 0-2.26s-1.638-.627-2.263 0L16 13.737 5.933 3.667c-.626-.624-1.637-.624-2.262 0s-.624 1.64 0 2.264L13.74 16 3.67 26.07c-.626.625-.626 1.636 0 2.26.312.313.722.47 1.13.47s.82-.157 1.132-.47l10.07-10.068 10.068 10.07c.312.31.722.468 1.13.468s.82-.157 1.132-.47c.626-.625.626-1.636 0-2.26L18.262 16z">
                                
                            </path>
                        </svg>
                    </svg>
                </div>
            </div>
        </div>`
        return html
    }

    HT.removeModel = () => {
        $(document).on('click', '.deleted', function(){
            let _this = $(this)
            _this.parents('.search-result-item').remove()
        })
    }

   
	$(document).ready(function(){
        HT.searchModel()
        HT.chooseModel()
        HT.unfocusSearchBox()
        HT.addModel()
        HT.removeModel()
	});

})(jQuery);
