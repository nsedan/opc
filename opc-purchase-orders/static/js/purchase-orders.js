jQuery(document).ready(function ($) { 

    // Begin field population
    $(this).find(".acf-row").each(function () {
        var $el = $(this);
        fieldEvents($el)
    })

    acf.addAction('append', function ($el) {
        fieldEvents($el)
    });

    function fieldEvents($el) {

        var select = $el.find(".product_code select");
        var qty = $el.find(".quantity_requested input");
        var unit = $el.find(".unit_cost input");
        disableFields($el);

        select.on("change", function (e) { // Clear fields
            $el.find("[name]:not('select')").val('')
            requestProduct($(e.target).val(), $el)
            addProductLink()
        })

        qty.focusout( () => { qty_calc($el) })
        unit.focusout( () => { qty_calc($el) })
        $(qty).keydown( (e) => { if (e.keyCode == 13) { qty_calc($el) } });
        $(unit).keydown( (e) => { if (e.keyCode == 13) { qty_calc($el) } });
        
    }

    function requestProduct(id, row) {
        $.ajax({
            type: 'POST',
            url: acf.get('ajaxurl'),
            data: {
                action: 'productRequest',
                id: id
            },
            success: function (data, textStatus, XMLHttpRequest) {
                var data = JSON.parse(data)
                productData(data, row);
                $('.acf-input-prepend .c5-dashicons-spin').removeClass('c5-dashicons-spin')
                return null;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }

    function productData(data, row) {
        row.find(`.name input`).val(data.product.name);

        if (data.product_meta) {
            var keys = Object.keys(data.product_meta);

            keys.forEach(element => {
                row.find(`.${element} input`).val(data.product_meta[element]);
            });
        }
    }
    // End field population

    // Total cost calculations
    function qty_calc($el) {
        var qty_req = $el.find(".quantity_requested input").val();
        var unit_cost = $el.find(".unit_cost input").val();
        var rowTotal = parseInt(qty_req) * parseFloat(unit_cost);

        $el.find(".total_cost input").val(rowTotal.toFixed(2));
    }

    // Disables specific fields 
    function disableFields($el) {
        $el.find(".name input").prop("readonly", true)
        $el.find(".total_cost input").prop("readonly", true)
        $el.find(".per_pack input").prop("readonly", true)
        $el.find(".per_carton input").prop("readonly", true)
        $el.find(".flat_size input").prop("readonly", true)
        $el.find(".folded_size input").prop("readonly", true)
        $el.find(".face input").prop("readonly", true)
        $el.find(".finishes input").prop("readonly", true)
        $el.find(".packing input").prop("readonly", true)
        $el.find(".material input").prop("readonly", true)
        $el.find(".reverse input").prop("readonly", true)
        $el.find(".coating input").prop("readonly", true)
    }

    // Adds a link to product edit page
    if ( $('.name input').length ) {
        $('.acf-input-append').append('<i class="dashicons dashicons-edit"></i>')
        $('.acf-input-prepend').append('<i class="dashicons dashicons-update item-line-update"></i>')
    }
    function addProductLink(){
        $('.product_code select option').each(function(i) {
            let $id = $(this).val()
            let productLink =`<a href="post.php?post=${$id}&action=edit"></a>`;
            $(this).closest('tr.acf-row').find('.acf-input-append').wrap(productLink);
        });
    }
    addProductLink()
    $('.item-line-update').on('click', function(e){
        $(this).css({'cursor':'default'}).addClass('c5-dashicons-spin').removeClass('item-line-update')
        var $el = $(this).closest('.acf-row');
        let $val = $el.find(".product_code select").val()
        requestProduct($val, $el)
    })


    // Ajax requests for suppliers addresses
    setTimeout( () => { // Triggers only on page load. Waits 500ms for ACF to load field.
        if ( $('.select2-selection__clear') ){ $(".select2-selection__clear").hide() }

        let pSupplier = $('#print-address .select2-selection__rendered').attr('title'); 
        if ( pSupplier != undefined ){
            let pSupplierName = pSupplier.split(' - ')[1];
            $.ajax({
                data: { 
                    action: 'supplierRequest',
                    pSupplierName: pSupplierName
                },
                type: "POST",
                url: ajaxurl,
                success: function(data, textStatus, XMLHttpRequest){
                    data = JSON.parse(data)
                    let pAddress = `
                        <div id="print-address-div" style="padding-top:5px">
                            <p>${data[0]}</p>
                            <p>${data[1]}</p>
                            <p>${data[2]}</p>
                            <p>${data[3]}</p>
                            <p>${data[4]}</p>
                        </div>`
                    
                    $('#print-address').append(pAddress)
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("The request failed. " + errorThrown);
                }
            });
        }
        
        let dSupplier = $('#delivery-address .select2-selection__rendered').attr('title'); 
        if ( dSupplier != undefined ){
            let dSupplierName = dSupplier.split(' - ')[1];
            $.ajax({
                data: { 
                    action: 'deliveryRequest',
                    dSupplierName: dSupplierName
                },
                type: "POST",
                url: ajaxurl,
                success: function(data, textStatus, XMLHttpRequest){
                    data = JSON.parse(data)
                    let dAddress = `
                        <div id="delivery-address-div" style="padding-top:5px">
                            <p>${data[0]}</p>
                            <p>${data[1]}</p>
                            <p>${data[2]}</p>
                            <p>${data[3]}</p>
                            <p>${data[4]}</p>
                        </div>`
                    
                    $('#delivery-address').append(dAddress)
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("The request failed. " + errorThrown);
                }
            });
        }
    }, 500);
    
    $('#print-address').on('change', () => { // Triggers on selection change
        if ( $("#print-address-div") ){ $("div").remove("#print-address-div") }
        if ( $('.select2-selection__clear') ){ $(".select2-selection__clear").remove() }

        let pSupplier = $('#print-address .select2-selection__rendered').attr('title');
        if ( pSupplier != undefined ){
            let pSupplierName = pSupplier.split(' - ')[1];
            $.ajax({
                data: { 
                    action: 'supplierRequest',
                    pSupplierName: pSupplierName
                },
                type: "POST",
                url: ajaxurl,
                success: function(data, textStatus, XMLHttpRequest){
                    data = JSON.parse(data)
                    let pAddress = `
                        <div id="print-address-div" style="padding-top:5px">
                            <p>${data[0]}</p>
                            <p>${data[1]}</p>
                            <p>${data[2]}</p>
                            <p>${data[3]}</p>
                            <p>${data[4]}</p>
                        </div>`
                    
                    $('#print-address').append(pAddress)
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("The request failed. " + errorThrown);
                }
            });
        }
    })
    
    $('#delivery-address').on('change', () => { // Triggers on selection change
        if ( $("#delivery-address-div") ){ $("div").remove("#delivery-address-div") }
        if ( $('.select2-selection__clear') ){ $(".select2-selection__clear").remove() }

        let dSupplier = $('#delivery-address .select2-selection__rendered').attr('title'); 
        if ( dSupplier != undefined ){
            let dSupplierName = dSupplier.split(' - ')[1];
            $.ajax({
                data: { 
                    action: 'deliveryRequest',
                    dSupplierName: dSupplierName
                },
                type: "POST",
                url: ajaxurl,
                success: function(data, textStatus, XMLHttpRequest){
                    data = JSON.parse(data)
                    let dAddress = `
                        <div id="delivery-address-div" style="padding-top:5px">
                            <p>${data[0]}</p>
                            <p>${data[1]}</p>
                            <p>${data[2]}</p>
                            <p>${data[3]}</p>
                            <p>${data[4]}</p>
                        </div>`
                    
                    $('#delivery-address').append(dAddress)
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("The request failed. " + errorThrown);
                }
            });
        }
    })

    // Minor styling and preferences changes
    $('.products .acf-input .acf-repeater .acf-table').wrap("<div class='products-div'></div>")
    $('.products-div').css({'max-width':'200em', 'overflow-x':'scroll'})

    let menuPath = $('li.current').text()
    if ( menuPath === 'All Purchase Orders' || menuPath === 'New Purchase Order'){

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



