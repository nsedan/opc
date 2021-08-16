jQuery(document).ready(function ($) {
    
    let urlpath = window.location.pathname;
    
    let today = '.flatpickr-day.today';
    let disabled = 'flatpickr-disabled';
    let day = '.flatpickr-day';
    let thursday = 'thursday';
    let friday = 'friday';
    let saturday = 'saturday';
    
    function dates(){       
        
        $(today).addClass(disabled).css({'border': '1px solid lightgrey'})

        $(day+':eq(4)').addClass(thursday)
        $(day+':eq(5)').addClass(friday)
        $(day+':eq(6)').addClass(saturday)
        
        $(day+':eq(11)').addClass(thursday)
        $(day+':eq(12)').addClass(friday)
        $(day+':eq(13)').addClass(saturday)
        
        $(day+':eq(18)').addClass(thursday)
        $(day+':eq(19)').addClass(friday)
        $(day+':eq(20)').addClass(saturday)
        
        $(day+':eq(25)').addClass(thursday)
        $(day+':eq(26)').addClass(friday)
        $(day+':eq(27)').addClass(saturday)
        
        $(day+':eq(32)').addClass(thursday)
        $(day+':eq(33)').addClass(friday)
        $(day+':eq(34)').addClass(saturday)
        
        $(day+':eq(39)').addClass(thursday)
        $(day+':eq(40)').addClass(friday)
        $(day+':eq(41)').addClass(saturday)


        if ( $(today).hasClass(thursday) ){
            $(today).next().addClass(disabled)
            $(today).next().next().next().next().addClass(disabled)
        }
        else if ( $(today).hasClass(friday) ){
            $(today).next().next().next().addClass(disabled)
            $(today).next().next().next().next().addClass(disabled)
        }
        else if ( $(today).hasClass(saturday) ){
            $(today).next().next().addClass(disabled)
            $(today).next().next().next().addClass(disabled)
        }
        else{
            $(today).next().addClass(disabled)
            $(today).next().next().addClass(disabled)
        }
    }
    
    if ( urlpath === '/checkout/'){
        setInterval(function(){ 
            if ( $('.flatpickr-input').hasClass('active') ) {
                dates()
            }    
        }, 250)
    }
    
    


})