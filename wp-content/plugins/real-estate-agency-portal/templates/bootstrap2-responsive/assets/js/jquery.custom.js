
$(document).ready(function(){

    jQuery('ul.nav li.dropdown').hover(function() {
        //jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn();
    }, function() {
        //jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut();
    });
    
    jQuery('ul.nav li.dropdown').click(function() {
        if($(this).hasClass('open'))
        {
            window.location = $(this).find('a').attr('href');
        }
        else if( $(this).find('.dropdown-menu').css('display') != 'block')
        {
          $(this).addClass('open');
          jQuery(this).find('.dropdown-menu').fadeIn();
          return false;
        }
    });
    
    $('.selectpicker').selectpicker({
        style: 'btn-large'
    });
    
    $('.selectpicker-small').selectpicker({
        style: 'btn-default'
    });
    
    $("a.developed_by").hover(
      function () {
        $(this).animate({ 
            opacity: 1
        }, 200 );
      }, 
      function () {
        $(this).animate({ 
            opacity: 0.4
        }, 200 );
      }
    );
    
    // iOS (touchstart), other (click)
    $(document).on('touchstart click', 'a.preview:not(.direct-download)', function () {
        var myLinks = new Array();
        var current = $(this).attr('href');
        var curIndex = 0;
         
        $('.files-list a:not(.direct-download)').each(function (i) {
            var img_href = $(this).attr('href');
            myLinks[i] = {href:img_href,
                          title:$(this).attr('title')};
            if(current == img_href)
                curIndex = i;
        });
        
        options = {index: curIndex}
        
        blueimp.Gallery(myLinks,options);
        
        return false;
    }); 
    
    
});

function support_history_api()
{
    return !!(window.history && history.pushState);
}

function custom_number_format(val)
{
    return val.toFixed(2);
}
