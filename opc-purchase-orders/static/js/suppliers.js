jQuery(document).ready(function ($) { 

    let menuPath = $('li.current').text()
    if ( menuPath === 'All Suppliers' || menuPath === 'New Supplier'){

        // Removes search bar only on this post type
        $('.search-box').remove()

        // Removes wp posts frontend options 
        $('span.inline.hide-if-no-js').remove()
        $('span.view').remove()

        // Publish box simplified
        $('#minor-publishing-actions').hide()
        $('#misc-publishing-actions').hide()

        // Prevent page reload on Enter key hit
        $(window).keydown(function (e) {
            if (e.keyCode == 13) { e.preventDefault() }
        })
    }

});



