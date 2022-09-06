$(function() {

    function getNewLocationSearch(params) {
        let resultSearch = '';
        $.each(params,function(getParam,value) {
            if(document.location.search.includes(getParam)) {
                const pattern = `${getParam}=[^&]+`;
                const re = new RegExp(pattern,'g');
                if(resultSearch.length > 0)
                    resultSearch = resultSearch.replace(re,getParam + '=' + value);
                else
                    resultSearch = document.location.search.replace(re,getParam + '=' + value);
            }
            else {
                if(document.location.search.length > 0 || resultSearch.length > 0) {
                    if(resultSearch.length > 0)
                        resultSearch += '&' + getParam + '=' + value;
                    else
                        resultSearch = document.location.search + '&' + getParam + '=' + value;
                }
                else
                    resultSearch = '?' + getParam + '=' + value;
            }
        });
        return resultSearch;
    }

    $('.dropdown-menu .dropdown-item').click(function () {
        $(this).parent('.dropdown-menu').find('.dropdown-item').each(function() {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });
    $('#sort-apply-button').click(function() {
        let sortField, sortOrder;
        sortField  = sortOrder = '';
        $('.dropdown-sort-field li').each(function() {
            if($(this).hasClass('active')) {
                sortField = $(this).attr('data-field');
                return false;
            }
        });
        $('.dropdown-sort-order li').each(function() {
            if($(this).hasClass('active')) {
                sortOrder = $(this).attr('data-field');
                return false;
            }
        });
        if(sortField.length > 0 && sortOrder.length > 0) {
            const search = {
                'sortField': sortField,
                'sortOrder': sortOrder
            }
            const searchResult = getNewLocationSearch(search);
            document.location.href = document.location.pathname + searchResult;
        }
    });
    $('.select-page-size').change(function() {
        const search = {
            'pageSize' : $(this).val()
        }
        document.location.href = document.location.pathname + getNewLocationSearch(search);
    });
});