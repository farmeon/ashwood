$(document).ready(function() {

    var overlay = $('#overlay');
    var open_modal = $('.open_modal');
    var close = $('.modal_close, #overlay');
    var modal = $('.modal_div');

    open_modal.click( function(event){
        event.preventDefault();
        var div = $(this).attr('href');
        overlay.fadeIn(400,
            function(){
                $(div)
                    .css('display', 'block')
                    .animate({opacity: 1, top: '50%'}, 200);
            });
    });

    close.click( function(){
        modal
            .animate({opacity: 0, top: '45%'}, 200,
                function(){
                    $(this).css('display', 'none');
                    overlay.fadeOut(400);
                }
            );
    });
	
    $("#register_form").submit(function() {
        var data = $("#register_form").serialize();
        $.ajax({
            type: "POST",
            url: "src/ajax/ajaxRequest.php",
            data: data,
            success: function(data) {
                if(data == 'true'){
                    $('#modal1 .response').html("<div class='resp-succ'>Регистрация выполнена успешно, пожалуйста, проверьте электронную почту.</div>");
                }else{
                    $('#modal1 .response').html("<div class='resp-error'>Пользователь уже существует.</div>");
                }
            },
            error: function() {
                $('.response').html("<div class='resp-error'>Ошибка запроса.</div>");
            }
        });
        return false;
    })

    $("#auth_form").submit(function() {
        var data = $("#auth_form").serialize();
        $.ajax({
            type: "POST",
            url: "src/ajax/ajaxRequest.php",
            data: data,
            success: function(data){
                if(data == 'true'){
                    location.reload(true);
                }else{
                    $('#modal2 .response').html("<div class='resp-error'>Неправильно введены данные!!!.</div>");
                }
            },
            error: function() {
                $('.response').html("<div class='resp-error'>Ошибка запроса.</div>");
            }
        });
        return false;
    });

    $('.logout').click(function(){
        $.ajax({
            type: "POST",
            url: "src/ajax/ajaxRequest.php",
            data: {'action' : 'logout'},
            success: function(data){
                location.reload(true);
            },
            error: function() {}
        });
        return false;
    });

    $("#review_form").submit(function() {
        var data = $("#review_form").serialize();
        $.ajax({
            type: "POST",
            url: "src/ajax/ajaxRequest.php",
            data: data,
            success: function(data){
                if(data != 'false'){
                    var obj = JSON.parse(data);
                    $('.content .message-block').append("<div class='review-block'><p>"+obj.username+"</p><div>"+obj.message+"</div></div>");
                }else{
                    $('#modal2 .response').html("<div class='resp-error'>Неправильно введены данные!!!.</div>");
                }
            },
            error: function() {
                $('.response').html("<div class='resp-error'>Ошибка запроса.</div>");
            }
        });

        return false;
    });


});
