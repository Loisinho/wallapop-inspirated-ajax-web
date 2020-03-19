// ######################################################################################
// ##### app.js #########################################################################
// ######################################################################################

$(document).ready(function() {

    $.ajaxSetup({ cache: false });

    // TODO: Modify the Web URL. (Produce an 404 error).
    // window.history.pushState({}, '', 'inicio');

    offset = 0;
    limit = 10;
    filter = '';
    ownads = false;

    // Cookie control.
    date = new Date();
    username = '';

    function userCookie() {
        date.setTime(date.getTime() + (1 * 60 * 1000));
        document.cookie = "user=" + username + ";expires=" + date.toUTCString() + ";path=/";
    }

    cookies = (document.cookie).replace(/\s/g, '').split(';');
    for (c of cookies)
        if (c.match(/^user/gim))
            username = c.split("=")[1];
    if (username != '') {
        userCookie();
        $('nav').load('inc/navbar_logedin.php', function() {
            $('#dropdown01').text(username);
            $('main').after('<div class="modal" id="delete_modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Are you sure?</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body">Remember if you delete the account you will never log in again. And your ads will be deleted.</div><div class="modal-footer"><a id="delaccount-modal-link" href="./crud.php?op=9" class="btn btn-danger">Delete</a></div></div></div></div>');
        });
    }

    // Load 10 ads each time.
    function adsLoading (clean) {
        $.get('crud.php?op=0', {filter: filter, ownads: ownads, offset: offset, limit: limit}, function (result) {
            // console.log(result);
            colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
            
            if (clean)
                $('#ads-box').empty().append('<div class="card-columns"></div>');
                
            if (result.status === undefined) {
                // JSON.parse(result);
                for (ad in result) {
                    let card = '';
                    let adDateParts = result[ad].fecha.split('-');
                    let adDate = new Date(adDateParts[0], adDateParts[1] - 1, adDateParts[2]);
                    let newAd = date - adDate;
                    let n = parseInt(Math.random() * 7);
                    
                    if (newAd < 86400000)
                        card = '<div class="card glowingShadow bg-' + colors[n] + ' text-light">';
                    else
                        card = '<div class="card bg-' + colors[n] + ' text-light">';

                    if (result[ad].imagen != null)
                        card += '<img class="card-img-top" src="public/img/' + result[ad].imagen + '" alt="ERROR ' + result[ad].imagen + '">';
                    else
                        card += '<img class="card-img-top" src="" alt="NO IMG">';
                    
                    card += '<div class="card-body"><h5 class="card-title">' + result[ad].titulo +'</h5><p class="card-text">' + result[ad].descripcion + '</p></div>';
                    card += '<div class="card-footer bg-light"><small class="text-muted">' + result[ad].fecha + '</small>';
                    if (ownads) {
                        card += '<br><a class="editad-link" href="#">Edit</a> <a class="deletead-link" href="#">Delete</a>';
                        card += '<input type="hidden" id="id" name="id" value="' + result[ad].id + '">';
                    }
                    card += '</div></div>';
                    $('.card-columns').append(card);
                }
            } else if(!$('#ads-box > div.alert').length) {
                $('#more-btn').hide();
                $('#ads-box').append('<div class="alert alert-danger" role="alert">' + result.status + '</div>');
            }
            if (!$('#more-btn').length && !$('#ads-box > div.alert').length) {
                $('#ads-box').append('<button id="more-btn" type="button" class="btn btn-primary">Load More</button>');
            }
        });
        if (username != '') userCookie();
    }

    function mainMsg() {
        if (username == '')
            $('main > .starter-template').prepend('<h1>WALLAPUSH</h1><p class="lead">Join us for free.<br/>We are more than 30 million users.<br>Sell, exchange and buy used products.</p><br>');
        else
            $('main > .starter-template').prepend('<h1>WALLAPUSH</h1><p class="lead"><span class="text-primary">' + username + '</span>, we are ready!<br/>Sell, exchange and buy used products.</p><br>');
    }

    function focusthis() {
        $('main input:eq(0)').focus();
    }

// ######################################################################################
// ##### main_default_app.js ############################################################
// ######################################################################################

    // Main ads load call.
    if ($('#ads-box').length) {
        mainMsg();
        adsLoading(true);
    }

    // Scroll ads load call.
    $(window).scroll(function() {
        // TODO: console.log('zoom', window.devicePixelRatio)
        //       solve zoomed browser problems.
        // console.log(window.devicePixelRatio);
        // console.log(window.devicePixelRatio * $(window).scrollTop());

        // console.log($(document).height() + " - " + $(window).height() + " = " + ($(document).height() - $(window).height()) + " == " + $(window).scrollTop());

        if ($(document).height() - $(window).height() <= $(window).scrollTop()) {
            if ($('.card-columns').length != 0 && $('main div.alert').length == 0) {
                offset += limit; 
                adsLoading(false);
            }
        }
    });

    // Load more button.
    $('main').on('click', '#more-btn', function(){
        if ($('.card-columns').length != 0 && $('main div.alert').length == 0) {
            offset += limit; 
            adsLoading(false);
        }
    });

// ######################################################################################
// ##### navbar_default_app.js & navbar_logedin_app.js ##################################
// ######################################################################################
    
    // Search form submit.
    $(document).on('submit', 'nav form', function(e) {
        e.preventDefault();
        let time = 0;
        offset = 0;
        filter = $('input[name="filter"]').val();
        ownads = false;
        if ($('html, body').scrollTop() != 0) {
            time = 500;
            $('html, body').animate({scrollTop: 0}, time);
        }
        setTimeout(function() {
            if ($('nav a.active').removeClass('active').attr('id') != 'wallapush')
                $('main').load('inc/main_default.php', function() {
                    mainMsg();
                    adsLoading(true);
                });
            else
                adsLoading(true);
        }, time);
    });

    // Navbar links.
    $('nav').on('click', 'a', function(e) {
        e.preventDefault();
        let time = 0;
        let active = $('nav a.active').removeClass('active').attr('id');
        offset = 0;
        filter = '';
        ownads = false;
        $('body > div.alert').remove();
        if ($('html, body').scrollTop() != 0) {
            time = 500;
            $('html, body').animate({scrollTop: 0 }, time);
        }
        if (username != '') userCookie();
        switch ($(this).attr('id')) {
            case 'wallapush':
                $(this).addClass('active');
                setTimeout(function(){
                    if (active != 'wallapush') {
                        $('main').load('inc/main_default.php', function() {
                            mainMsg();
                            adsLoading(true);
                        });
                    } else if ($('.card-columns > div').length > 10) {
                        $('.card-columns > div').slice(-($('.card-columns > div').length - 10)).remove();
                        $('#ads-box > div.alert').remove();
                    }
                }, time);
                break;
            case 'myads-link':
                $(this).addClass('active');
                setTimeout(function(){
                    $('main').load('inc/main_myads.php', function() {
                        ownads = true;
                        adsLoading(true);
                    });
                }, time);
                break;
            case 'makead-link':
                $(this).addClass('active');
                setTimeout(function(){
                    $('main').load('inc/main_makead.php', function() {
                        focusthis();
                    });
                }, time);
                break;
            case 'signin-link':
                $(this).addClass('active');
                setTimeout(function(){
                    $('main').load('inc/main_signin.php', function() {
                        focusthis();
                    });
                }, time);
                break;
            case 'login-link':
                $(this).addClass('active');
                setTimeout(function(){
                    $('main').load('inc/main_login.php', function() {
                        focusthis();
                    });
                }, time);
                break;
            case 'logout-link':
                $.get('crud.php?op=3', function(result) {
                    if (result.status == 'OK') {
                        document.cookie = "user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        username = '';
                        $('nav').load('inc/navbar_default.php');
                        $('main').load('inc/main_default.php', function() {
                            mainMsg();
                            adsLoading(true);
                        });
                    } else
                        $('main').before('<div class="alert alert-danger" role="alert">Logout Error.</div>');
                });
                break;
        }
    });

    // Delete account link.
    $(document.body).on('click', 'a#delaccount-modal-link', function(e) {
        e.preventDefault();
        $.get('crud.php?op=9', function(result) {
            document.cookie = "user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            username = '';
            $('.modal').modal('hide'); 
            $('.modal-backdrop').hide();
            $('body').removeClass('modal-open');
            $('nav').load('inc/navbar_default.php');
            $('main').load('inc/main_default.php', function() {
                mainMsg();
                adsLoading(true);
            });
        });
    });

// ######################################################################################
// ##### main_signin_app.js #############################################################
// ######################################################################################

    // Sign In button.
    $('main').on('submit', 'form#signin', function(e){
        e.preventDefault();
        $.post('crud.php?op=1', {
            nombre: $('input[name="nombre"]').val(), 
            apellidos: $('input[name="apellidos"]').val(), 
            nick: $('input[name="nick"]').val(), 
            email: $('input[name="email"]').val(), 
            password: $('input[name="password"]').val()
        }, function (result) {
            $('div.alert').remove();
            $('main input').removeClass('border border-danger');
            switch (result.status) {
                case 'BLANK':
                    $('main').before('<div class="alert alert-danger" role="alert">Some required field is in blank.</div>');
                    $('main input').each(function(){
                        if ($.trim($(this).val()) == '')
                            $(this).addClass('border border-danger');
                    });
                    break;
                case 'WRONG_EMAIL':
                    $('main').before('<div class="alert alert-danger" role="alert">Email is not valid.</div>');
                    $('main input[name="email"]').addClass('border border-danger');
                    break;
                case 'WRONG_PW':
                    $('main').before('<div class="alert alert-danger" role="alert">Password is not valid.</div>');
                    $('main input[name="password"]').addClass('border border-danger');
                    break;
                case 'USED_NICK':
                    $('main').before('<div class="alert alert-danger" role="alert">Nick already in use.</div>');
                    $('main input[name="nick"]').addClass('border border-danger');
                    break;
                case 'USED_EMAIL':
                    $('main').before('<div class="alert alert-danger" role="alert">Email already in use.</div>');
                    $('main input[name="email"]').addClass('border border-danger');
                    break;
                default:
                    $('main').load('inc/main_login.php', function(){
                        $('main').before('<div class="alert alert-success" role="alert">User with nick ' + result.nick + ' inserted correctly.</div>');
                        focusthis();
                    });
            }
        });
    });
    
// ######################################################################################
// ##### main_login_app.js ##############################################################
// ######################################################################################

    // Log In button.
    $('main').on('submit', 'form#login', function(e){
        e.preventDefault();
        $.post('crud.php?op=2', {
            user: $('input[name="user"]').val(), 
            password: $('input[name="password"]').val()
        }, function (result) {
            $('div.alert').remove();
            $('main input').removeClass('border border-danger');
            switch (result.status) {
                case 'BLANK':
                    $('main').before('<div class="alert alert-danger" role="alert">Some required field is in blank.</div>');
                    $('main input').each(function(){
                        if ($.trim($(this).val()) == '')
                            $(this).addClass('border border-danger');
                    });
                    break;
                case 'WRONG':
                    $('main').before('<div class="alert alert-danger" role="alert">Some wrong field.</div>');
                    break;
                default:
                    username = result.nick;
                    userCookie();
                    $('nav').load('inc/navbar_logedin.php', function() {
                        $('#dropdown01').text(username);
                        $('main').after('<div class="modal" id="delete_modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Are you sure?</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body">Remember if you delete the account you will never log in again. And your ads will be deleted.</div><div class="modal-footer"><a id="delaccount-modal-link" href="./crud.php?op=9" class="btn btn-danger">Delete</a></div></div></div></div>');
                    });
                    $('main').load('inc/main_default.php', function() {
                        mainMsg();
                        adsLoading(true);
                    });
            }
        });
    });

    // Forgot password? link.
    $('main').on('click', '#forgot-link', function(e) {
        e.preventDefault();
        $('main').load('inc/main_forgot.php', function() {
            focusthis();
        });
    });

// ######################################################################################
// ##### main_makead_app.js ##############################################################
// ######################################################################################

    // Make Ad button.
    $('main').on('submit', 'form#makead', function(e) {
        e.preventDefault();

        let datos = new FormData();
        datos.append('titulo', $('input[name="titulo"]').val());
        datos.append('descripcion', $('input[name="descripcion"]').val());
        datos.append('img', $('input[type=file]')[0].files[0]);

        $.ajax({
            url: 'crud.php?op=4',
            dataType: 'text',
            data: datos,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (result) {
                $('div.alert').remove();
                $('main input').removeClass('border border-danger');
                result = JSON.parse(result);
                switch (result.status) {
                    case 'OK':
                        $('main').load('inc/main_myads.php', function() {
                            $('nav a.active').removeClass('active');
                            $('nav #myads-link').addClass('active');
                            $('main').before('<div class="alert alert-success" role="alert">Ad has been made correctly.</div>');
                            offset = 0;
                            ownads = true;
                            adsLoading(true);
                        });
                        break;
                    case 'BLANK':
                        $('main').before('<div class="alert alert-danger" role="alert">Missing one or more required fields.</div>');
                        $('main input').each(function(){
                            if ($.trim($(this).val()) == '')
                                $(this).addClass('border border-danger');
                        });
                        break;
                    default:
                        $('main').before('<div class="alert alert-danger" role="alert">Fail uploading image.</div>');
                }
            },
            error: function (result) {
                console.log('Error en la petición ajax!');
            }
        });
    });

    // Edit button.
    $('main').on('click', 'a.editad-link', function(e) {
        e.preventDefault();
        let adid = $(this).parent().children('input#id').val();
        if (username != '') userCookie();
        $('main').load('inc/main_editad.php', function() {
            $.get('crud.php?op=10', {id: adid}, function(result) {
                $('main input:eq(0)').val(result.titulo);
                $('main input:eq(1)').val(result.descripcion);
                if (result.imagen !== null) {
                    $('main input:eq(2)').val(result.imagen);
                    $('main img#print').attr('src', 'public/img/' + result.imagen);
                }
                $('main input:eq(4)').val(result.id);
            });
            focusthis();
        });
    });

    // Delete button.
    $('main').on('click', 'a.deletead-link', function(e) {
        e.preventDefault();
        let ad = $(this).parent().parent();
        let adid = $(this).parent().children('input#id').val();
        if (username != '') userCookie();
        if (confirm('You are going to delete the add. Are you sure?')) {
            $('div.alert').remove();
            $.get('crud.php?op=6', {id: adid}, function(result) {
                if (result.status == 'OK')
                    ad.remove();
                else
                    $('main').before('<div class="alert alert-danger" role="alert">Something wrong occurred.</div>');
            });
        }
    });

// ######################################################################################
// ##### main_editad_app.js #############################################################
// ######################################################################################

    // Edit submit.
    $('main').on('submit', 'form#editad', function(e) {
        e.preventDefault();

        let datos = new FormData();
        datos.append('titulo', $('input[name="titulo"]').val());
        datos.append('descripcion', $('input[name="descripcion"]').val());
        datos.append('oldimg', $('input[name="oldimg"]').val());
        datos.append('img', $('input[type=file]')[0].files[0]);
        datos.append('id', $('input#id').val());

        $.ajax({
            url: 'crud.php?op=5',
            dataType: 'text',
            data: datos,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (result) {
                $('div.alert').remove();
                $('main input').removeClass('border border-danger');
                console.log(result); // Why this need a JSON.parse?
                result = JSON.parse(result);
                console.log(result);
                switch (result.status) {
                    case 'OK':
                        $('main').load('inc/main_myads.php', function() {
                            $('main').before('<div class="alert alert-success" role="alert">Ad has been modified correctly.</div>');
                            offset = 0;
                            ownads = true;
                            adsLoading(true);
                        });
                        break;
                    case 'BLANK':
                        $('main').before('<div class="alert alert-danger" role="alert">Missing one or more required fields.</div>');
                        $('main input').each(function(){
                            if ($.trim($(this).val()) == '')
                                $(this).addClass('border border-danger');
                        });
                        break;
                    default:
                        $('main').before('<div class="alert alert-danger" role="alert">Something wrong occurred.</div>');
                }
            },
            error: function (result) {
                console.log('Error en la petición ajax!');
            }
        });
    });

// ######################################################################################
// ##### main_forgot_app.js #############################################################
// ######################################################################################

    $('main').on('submit', 'form#recover', function(e) {
        e.preventDefault();
        $.post('crud.php?op=7', {email: $('input[name="email"]').val()}, function(result) {
            $('div.alert').remove();
            switch (result.status) {
                case 'OK':
                    $('main').load('inc/main_blank.php');
                    $('main').before('<div class="alert alert-success" role="alert">An email was sent to your email.</div>');
                    break;
                case 'NOTEXIST':
                    $('main').before('<div class="alert alert-danger" role="alert">This email does not exist.</div>');
                    break;
                case 'WRONG':
                    $('main').before('<div class="alert alert-danger" role="alert">Wrong email.</div>');
                    break;
            }
        });
    });

// ######################################################################################
// ##### main_recover_app.js ############################################################
// ######################################################################################

    $('main').on('submit', 'form#reset', function(e) {
        e.preventDefault();
        $.post('crud.php?op=8', {
            password: $('input[name="password"]').val(),
            email: $('input[name="email"]').val(),
            token: $('input[name="token"]').val()
        }, function(result) {
            $('div.alert').remove();
            if (result.status == 'OK') {
                $('main').load('inc/main_login.php', function() {
                    $('main').before('<div class="alert alert-success" role="alert">Password has been updated successfully.</div>');
                    focusthis();
                });
            } else {
                $('main').before('<div class="alert alert-danger" role="alert">Password has not been reset.</div>');
            }
        });
    });

});
    