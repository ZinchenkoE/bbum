!function (app) {
    var c = { // классы используемые в функционале селектов
        inputBox            : 'inputBox',
        selectField         : 'selectField',
        viewBox             : 'viewBox',
        dropdownBox         : 'dropdownBox',
        initialized         : 'initialized',
        searchSelect        : 'searchSelect',
        invalid             : 'invalid',
        searchSelectInput   : 'searchSelectInput',
        searchSelectIcon    : 'searchSelectIcon',
    };

    app.Select = {
        upgrade: function () {
            $('select:not(.' + c.initialized + ')').each(function () {
                var $select = $(this);
                var selectBox = $select.closest('.' + c.selectField);
                var dropdownBox = '';
                var selectedText = $select.find('option:selected').text();
                if ($select.hasClass(c.searchSelect))
                    dropdownBox += '<li class="forSearchSelectInput">' +
                                   '    <input class="' + c.searchSelectInput + '">' +
                                   '    <i class="material-icons ' + c.searchSelectIcon + '">search</i>' +
                                   '</li>';
                selectBox.find('select option').each(function () {
                    var $option = $(this);
                    dropdownBox += '<li class="' + ($option.attr('class') || '') + '">' + $option.text() + '</li>';
                });
                $('<div class="' + c.viewBox + '">' + selectedText + '</div><ul class="' + c.dropdownBox + '">' +
                    dropdownBox + '</ul>').appendTo(selectBox);
                $(this).addClass(c.initialized);
            });
        },
        initHandlers: function () {
            $(document).on('click', '.' + c.selectField + ' .' + c.viewBox, function () {
                var thisSelect = $(this).next();
                $('ul.' + c.dropdownBox).not(thisSelect).slideUp(200).closest('.' + c.selectField).removeClass('active');
                thisSelect.slideToggle(200).closest('.' + c.selectField).toggleClass('active');
            });

            $(document).on('click', '.' + c.selectField + ' li', function () {
                var $li = $(this);
                var selectBox = $li.closest('.' + c.selectField);
                var select = selectBox.find('select');
                var clickIndex = select.hasClass(c.searchSelect) ? $li.index() - 1 : $li.index();
                var targetValue = select.find('option').eq(clickIndex).attr('value');
                selectBox.find('ul.' + c.dropdownBox).slideUp(200).closest('.' + c.selectField).removeClass('active');
                selectBox.find('.' + c.viewBox).text($li.text());
                select.val(targetValue);
                select.change();
            });

            $(document).on('change', '.' + c.invalid + ' select', function () {
                $(this).closest('.' + c.invalid).removeClass(c.invalid).find('p.error').remove();
            });

            $(document).on('click', '.' + c.searchSelectInput, function (e) {
                e.stopPropagation();
            });

            $(document).on('input', '.' + c.searchSelectInput, function () {
                var $input = $(this);
                var searchStr = $input.val().toLowerCase();
                var selWrap = $input.closest('.' + c.inputBox);
                if (searchStr) $input.next().text('close');
                else $input.next().text('search');
                selWrap.find('li:not(:first)').each(function () {
                    var $t = $(this);
                    if ($t.text().toLowerCase().indexOf(searchStr) === -1) $t.hide();
                    else $t.show();

                    $('div').show();
                    document.querySelectorAll('div').forEach(function(el){
                        el.style.display = 'block';
                    });

                });
            });

            $(document).on('click', '.' + c.searchSelectIcon, function (e) {
                e.stopPropagation();
                $(this).text('search').prev().val('').trigger('input');
            });
        }
    };

    $.fn.setVal = function(value){
        if(this.length === 0) console.error('Нет элемента по даному селектору.');
        else if(this.length > 1) console.error('Функция должна применятся только к одному селекту');
        else if(!this.find('option[value="' + value +'"]').length) console.error('Неверное значение value; option[value="' + value +'"]');
        else{
            var i = this.find('option[value="' + value +'"]').index();
            i = this.hasClass('searchSelect') ? ++i : i;
            this.closest('.' + c.selectField).find('li').eq(i).click();
        }
        return this;
    };
}(a);