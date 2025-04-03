(function($) {
	"use strict";
	var HT = {}; 
    var _token = $('meta[name="csrf-token"]').attr('content');
    var typingTimer;
    var doneTyingInterval = 100; 

    $.fn.elExist = function () {
        return this.length > 0
    }


    HT.searchModel = () => {
        $(document).on('keyup', '.search-model', function(e){
            e.preventDefault()
            let _this = $(this)
            
            let keyword = _this.val()
            let option = {
                model: 'Product',
                keyword : keyword
            }
            HT.sendAjax(option)
            
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
                            data-warranty="${data[i].warranty}"
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
                $('.construction-product-result').append(HT.modelTemplate(data))
            }else{
                $('#model-'+data.id).remove()
                _this.find('.auto-icon').html('')
                _this.attr('data-flag', 0)
            }
        })
    }

    HT.modelTemplate = (data) => {
        let html = `
            <div class="row uk-flex uk-flex-middle mb10 search-result-item" id="model-${data.id}" data-modelid="${data.id}">
                <div class="col-lg-4">
                    <div class="form-row">
                        <input 
                            type="text"
                            readonly
                            class="form-control"
                            value="${data.name}"
                            name="product[name][]"
                        >
                        <input 
                            type="hidden"
                            name="product[id][]"
                            value="${data.id}"
                        >
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-row">
                        <input 
                            type="text"
                            name="product[color][]"
                            class="form-control"
                            value=""
                        >
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-row">
                        <input 
                            type="text"
                            name="product[quantity][]"
                            class="form-control text-right int"
                            value=""
                        >
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-row">
                        <input 
                            type="text"
                            name="product[warranty][]"
                            class="form-control text-right int"
                            value="${data.warranty}"
                        >
                    </div>
                </div>
                <div class="col-lg-1 text-right">
                    <button type="button" class="remove-attribute btn btn-danger"><svg data-icon="TrashSolidLarge" aria-hidden="true" focusable="false" width="15" height="16" viewBox="0 0 15 16" class="bem-Svg" style="display: block;"><path fill="currentColor" d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z"></path></svg></button>
                </div>
            </div>
        `;

        return html
    }


    HT.removeModel = () => {
        $(document).on('click', '.remove-attribute', function(){
            let _this = $(this)
            _this.parents('.search-result-item').remove()
        })
    }

    
	$(document).ready(function(){
        HT.searchModel()
        HT.unfocusSearchBox()
        HT.addModel()
        HT.removeModel()
	});
    
})(jQuery);
