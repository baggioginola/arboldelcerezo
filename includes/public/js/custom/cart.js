/**
 * Created by mario on 16/ene/2017.
 */
jQuery(document).ready(function () {
    jQuery('.add_cart').click(function () {
        var id = jQuery(this).attr('id');

        var $element = jQuery('#bigPic');

        var $picture = $element.clone();

        var pictureOffsetOriginal = $element.offset();

        if ($picture.size())
            $picture.css({
                'position': 'absolute',
                'top': pictureOffsetOriginal.top,
                'left': pictureOffsetOriginal.left
            });

        var pictureOffset = $picture.offset();

        var cartBlockOffset = jQuery('#cart_productos').offset();

        $picture.appendTo('body');
        $picture.css({'position': 'absolute', 'top': $picture.css('top'), 'left': $picture.css('left')})
            .animate({
                'width': $element.attr('width') * 0.66,
                'height': $element.attr('height') * 0.66,
                'opacity': 0.2,
                'top': cartBlockOffset.top + 30,
                'left': cartBlockOffset.left + 15
            }, 1000)
            .fadeOut(100, function () {
            });

        var url = BASE_ROOT + 'cart/add';
        jQuery.ajax({
            url: url,
            type: "POST",
            cache: false,
            data: {
                id_producto: id
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                jQuery('#cart_productos').html(response.data.total + ' producto(s)');
                console.log(response.data.total);
            }
        });
        return false;
    });

    jQuery('.id_delete_cart').click(function () {
        var id = jQuery(this).attr('id');

        var url = BASE_ROOT + 'cart/delete';
        jQuery.ajax({
            url: url,
            type: "POST",
            cache: false,
            data: {
                id_producto: id
            },
            dataType: 'json',
            success: function (response) {
                if(response.status == 200) {
                    window.location = BASE_ROOT + 'cart';
                }
            }
        });
        return false;
    });
});