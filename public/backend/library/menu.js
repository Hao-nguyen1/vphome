(function($) {
	"use strict";
	var HT = {}; 
    var _token = $('meta[name="csrf-token"]').attr('content');
    var typingTimer;
    var doneTyingInterval = 1000; // 1s


    HT.createMenuCatalogue = () => {
        $(document).on('submit', '.create-menu-catalogue', function(e){
            e.preventDefault()
            let _form = $(this)
            let option = {
                'name' : _form.find('input[name=name]').val(),
                'keyword': _form.find('input[name=keyword]').val(),
                '_token' : _token
            }

            $.ajax({
                url: 'ajax/menu/createCatalogue', 
                type: 'POST', 
                data: option,
                dataType: 'json', 
                success: function(res) {
                    if(res.code == 0){
                       $('.form-error').removeClass('text-danger').addClass('text-success').html(res.message).show()
                       const menuCatalogueSelect = $('select[name=menu_catalogue_id]')
                       menuCatalogueSelect.append('<option value="'+res.data.id+'">'+res.data.name+'</option>')
                    }else{
                        $('.form-error').removeClass('text-success').addClass('text-danger').html(res.message).show()
                    }
                },
                beforeSend: function() {
                    _form.find('.error').html(''); 
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if(jqXHR.status === 422){
                        
                        let errors = jqXHR.responseJSON.errors

                        for(let field in errors){
                            let errorMessage = errors[field]
                            $('.'+ field).html()
                            errorMessage.forEach(function(message){
                               
                                $('.'+ field).html(message)
                            })
                        }
                    }
                  
                }
            });
        })
    }

    HT.createMenuRow = () => {
        $(document).on('click', '.add-menu', function(e){
            e.preventDefault()
            let _this = $(this)
            $('.menu-wrapper').append(HT.menuRowHtml()).find('.notification').hide()

        })
    }

    HT.menuRowHtml = (option) => {
        let html
        let $row = $('<div>').addClass('row mb10 menu-item '+ ((typeof(option) != 'undefined') ? option.canonical : '') +'')
        const colums = [
            { class: 'col-lg-4', name: 'menu[name][]', value: (typeof(option) != 'undefined') ? option.name : '' },
            { class: 'col-lg-4', name: 'menu[canonical][]', value: (typeof(option) != 'undefined') ? option.canonical : '' },
            { class: 'col-lg-2', name: 'menu[order][]', value: 0 },
        ]

        colums.forEach(col => {
            let $col = $('<div>').addClass(col.class)
            let $input = $('<input>')
            .attr('type', 'text')
            .attr('value', col.value)
            .addClass('form-control '+ ((col.name == 'menu[order][]') ? 'int text-right' : ''))
            .attr('name', col.name)
            $col.append($input)
            $row.append($col)
        })

        let $removeCol = $('<div>').addClass('col-lg-2')
        let $removeRow = $('<div>').addClass('form-row text-center')
        let $a = $('<a>').addClass('delete-menu')
        let $image = $('<img>').attr('src', 'backend/close.png')
        let $input = $('<input>').addClass('hidden').attr('name', 'menu[id][]').attr('value',  0)


        $a.append($image)
        $removeRow.append($a)
        $removeCol.append($removeRow)
        $removeCol.append($input)
        $row.append($removeCol)

        return $row
    }

    HT.deleteMenuRow = () => {
        $(document).on('click', '.delete-menu', function(){
            let _this = $(this)
            let menu_id = _this.attr('data-menu-id')
            if(typeof menu_id != 'undefined' && menu_id != 0){
                $.ajax({
                    url: 'ajax/menu/deleteMenu', 
                    type: 'POST', 
                    data: {
                        menu_id: menu_id,
                        _token : _token
                    },
                    dataType: 'json', 
                    success: function(res) {
                        console.log(res);
                    },
                });
            }
            _this.parents('.menu-item').remove()
            HT.checkMenuItemLength()
        })
    }

    HT.checkMenuItemLength = () => {
        if($('.menu-item').length === 0){
            $('.notification').show()
        }
    }

    HT.getMenu = () => {
        $(document).on('click', '.menu-module', function(){
            let _this = $(this)
            let option = {
                model: _this.attr('data-model')
            }
            let target = _this.parents('.panel-default').find('.menu-list')
            let menuRowClass = HT.checkMenuRowExist()
            HT.sendAjaxGetMenu(option, target, menuRowClass)
        })
    }

    HT.checkMenuRowExist = () => {
        let menuRowClass = $('.menu-item').map(function(){
            let allClasses = $(this).attr('class').split(' ').slice(3).join(' ')

            return allClasses

        }).get()

        return menuRowClass
    }

    HT.sendAjaxGetMenu = (option, target, menuRowClass) => {
        $.ajax({
            url: 'ajax/dashboard/getMenu', 
            type: 'GET', 
            data: option,
            dataType: 'json', 
            beforeSend: function() {
                $('.menu-list').html('')
            },
            success: function(res) {
                let html = ''
                for(let i = 0; i < res.data.length; i++){
                    html += HT.renderModelMenu(res.data[i], menuRowClass)
                }
                html +=  HT.menuLinks(res.links).prop('outerHTML')
                target.html(html)
               
            },
            error: function(jqXHR, textStatus, errorThrown) {
              
            }
        });
    }

    HT.menuLinks = (links) => {
        let nav = $('<nav>')
        if(links.length > 3){
            let paginationUl = $('<ul>').addClass('pagination')
            $.each(links, function(index, link){
                let liClass = 'page-item'
                if(link.active){
                    liClass += ' active disabled'
                }else if(!link.url){
                    liClass += ' disabled'
                }
    
                let li = $('<li>').addClass(liClass)
                if(link.label == 'pagination.previous'){
                    let span = $('<span>').addClass('page-link').attr('aria-hidden', true).html('‹')
                    li.append(span)
                }else if(link.label == 'pagination.next'){
                    let span = $('<a>').addClass('page-link').attr('aria-hidden', true).html('›')
                    li.append(span)
                }else if(link.url){
                    let a = $('<a>').addClass('page-link').text(link.label).attr('href', link.url).attr('data-page', link.label)
                    li.append(a)
                }
                paginationUl.append(li)
            })  
            nav.append(paginationUl)
        }
        return nav
    }

    HT.getPaginationMenu = () => {
        $(document).on('click', '.page-link', function(e){
            e.preventDefault()
            let _this = $(this)
            let option = {
                model: _this.parents('.panel-collapse').attr('id'),
                page: _this.text()
            }
            let target = _this.parents('.menu-list')
            let menuRowClass = HT.checkMenuRowExist()
            HT.sendAjaxGetMenu(option, target, menuRowClass)
        })
    }

    HT.renderModelMenu = (object, renderModelMenu) => {
        let html = ''
        html += '<div class="m-item">'
            html += '<div class="uk-flex uk-flex-middle">'
                html += '<input type="checkbox" '+ ( (renderModelMenu.includes(object.canonical)) ? 'checked' : '' ) +' class="m0 choose-menu" value="'+object.canonical+'" name="" id="object_'+object.id+'">'
                html += '<label for="object_'+object.id+'">'+object.name+'</label>'
            html += '</div>'
        html += '</div>'

        return html
    }

    HT.chooseMenu = () => {
        $(document).on('click', '.choose-menu', function(){
            let _this = $(this)
            let canonical = _this.val()
            let name = _this.siblings('label').text()
            let $row = HT.menuRowHtml({
                name: name,
                canonical: canonical
            })
            let isChecked = _this.prop('checked')
            if(isChecked === true){
                $('.menu-wrapper').append($row).find('.notification').hide()
            }else{
                $('.menu-wrapper').find('.'+canonical).remove()
                HT.checkMenuItemLength()
            }

            
        })
    }


    HT.searchMenu = () => {
        $(document).on('keyup', '.search-menu', function(e){
            let _this = $(this)
            let keyword = _this.val()
            let option = {
                model: _this.parents('.panel-collapse').attr('id'),
                keyword : keyword
            }
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function(){
                let menuRowClass = HT.checkMenuRowExist()
                let target = _this.siblings('.menu-list')
                HT.sendAjaxGetMenu(option, target, menuRowClass)
               
            }, doneTyingInterval)
        })
    }


    HT.setupNestable = () => {
        if($('#nestable2').length){
            $('#nestable2').nestable({
                group: 1
            }).on('change', HT.updateNestableOutput);
        }
       
    }

    HT.updateNestableOutput = (e) => {

        var list = $(e.currentTarget), // Sử dụng currentTarget thay vì target
        output = $(list.data('output')); // Lấy element dựa trên selector từ data-output

        let json = window.JSON.stringify(list.nestable('serialize'));
        if(json.length){
            let option = {
                json : json,
                menu_catalogue_id: $('#dataCatalogue').attr('data-catalogueId'),
                _token : _token
            }

            $.ajax({
                url: 'ajax/menu/drag', 
                type: 'POST', 
                data: option,
                dataType: 'json', 
                success: function(res) {
                    console.log(res)
                },
            });
        }
        
    }

    // HT.runUpdateNestableOutput = () => {
    //     updateOutput($('#nestable2').data('output', $('#nestable2-output')));
    // }

    HT.expandAndCollapse = () => {
        $('#nestable-menu').on('click', function (e) {
            var target = $(e.target),
                    action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
    }

    

	$(document).ready(function(){
        HT.createMenuCatalogue()
        HT.createMenuRow()
        HT.deleteMenuRow()
        HT.getMenu()
        HT.chooseMenu()
        HT.getPaginationMenu()
        HT.searchMenu()
        HT.setupNestable()
        // HT.updateNestableOutput()
        // HT.runUpdateNestableOutput()
        HT.expandAndCollapse()
	});

})(jQuery);