/**
 * Created by Agustin on 25/03/2015.
 */
Requests = {
    QueryString : function(item){
        var svalue = location.search.match(new RegExp("[\?\&]" + item + "=([^\&]*)(\&?)","i"));
        return svalue ? svalue[1] : svalue;
    }
};

$(document).ready(function(){
    var mobile = window.innerWidth < 480;
    var desktop = window.innerWidth > 1023;
    var tablet = window.innerWidth > 480 && window.innerWidth < 1025;
    var iconScroll = $('.scroll-down, #comoParticipar');
    var verCarrera = $('#verCarrera');
    var replayCarrera = $('#replayCarrear');
    var overLay = $('.carrera-overlay');

    //APP DE LA CARRERA
    if($('#carrera').length > 0 ){
        app.init();
    }

    //////////timer
    var timer = $('#timerCarreraFecha');


    /////////// config reproductor
    var equipo = $('header div:first-child').attr('class').match('branca') ? 'branca': 'unico';
    var colors = {
        'branca':{
            claro: '#e9a042',
            oscuro: '#e0d7ba',
            font: '#cfb67e'
        },
        'unico':{
            claro: '#58bbb8',
            oscuro : '#d4d4d4',
            font: '#a9aba8'
        }
    };

    var reproductorConfig = {
        skin: 'blackControllers',
        autoPlay:false,
        playerBg: colors[equipo].claro,
        bufferEmptyColor: '#fff',
        bufferFullColor: colors[equipo].oscuro,
        selectedCategBg: colors[equipo].oscuro,
        categoryRecordBgOnColor:colors[equipo].oscuro,

        volumeOffColor: '#fff',
        volumeOnColor: colors[equipo].oscuro,
        songTitleColor: '#000',
        playlistBgColor:'#fff',
        searchAreaBg: colors[equipo].oscuro
    };
    var brancaRep = {
        skin: 'blackControllers',
        autoPlay:false,
        playerBg: colors['branca'].claro,
        bufferEmptyColor: '#fff',
        bufferFullColor: colors['branca'].oscuro,
        selectedCategBg: colors['branca'].oscuro,
        categoryRecordBgOnColor:colors['branca'].oscuro,
        seekbarColor: '#ffffff',
        volumeOffColor: '#fff',
        volumeOnColor: colors['branca'].oscuro,
        songTitleColor: '#000',
        playlistBgColor:'#fff',
        searchAreaBg: colors['branca'].oscuro
    };
    var unicoRep = {
        skin: 'blackControllers',
        autoPlay:false,
        playerBg: colors['unico'].claro,
        bufferEmptyColor: '#fff',
        bufferFullColor: colors['unico'].oscuro,
        selectedCategBg: colors['unico'].oscuro,
        categoryRecordBgOnColor:colors['unico'].oscuro,
        seekbarColor: '#ffffff',
        volumeOffColor: '#fff',
        volumeOnColor: colors['unico'].oscuro,
        songTitleColor: '#000',
        playlistBgColor:'#fff',
        searchAreaBg: colors['unico'].oscuro
    };

    if( $('#reproductorBranca').length > 0 ) $('#reproductorBranca').audio2_html5(brancaRep);
    if( $('#reproductorUnico').length > 0 ) $('#reproductorUnico').audio2_html5(unicoRep);


    ///////////////////// COMIENZA DESKTOP

    if(desktop){

        //////////////////////// ANIMIACION DEL SCROLL
        var i = 47;
        var animateScroll = setInterval(function(){
            i--;
                iconScroll.css({backgroundPosition:'center -'+i+'px'});
            if(i<0){
                i = 47;
            }
        },60);
        ///////////////////// ANIMIACION DEL SCROLL
        ///////////////////// EVENTOS PARA ACCIONAR LA CARRERA
        $(document).on('click','#replayCarrera',function(e){
            e.preventDefault();
            if(!window.location.href.match('scroll')){
                window.location = window.location.href + '?scroll=carrera';
            }else{
                window.location.reload();
            }
        });

        if(Requests.QueryString('scroll')){
            $('.carrera-control').fadeOut(10,function(){
                overLay.fadeToggle();
                $(this).addClass('off');
                timerRace.counter(carreraPlay);
            });
            $('body,html').animate({
                scrollTop: $('#carrera').offset().top + 80
            }, 5);
        }

        verCarrera.on('click',function(e){
            e.preventDefault();

            if(bowser.msie){
                var popVideoIe = $('#ieFixVideo').find('#popUp');
                $('body,html').css({overflow:'hidden'});
                popVideoIe.fadeIn('fast');
                console.log(popVideoIe.attr('style'));

            }else{
                overLay.fadeToggle();
                if(carrera._corridas != 0 ){
                    carrera.reset(function(){
                        $('.carrera-control').fadeOut('slow',function(){
                            $(this).addClass('off');
                            timerRace.counter(carreraPlay);
                        });
                    });
                }else{
                    $('.carrera-control').fadeOut('slow',function(){
                        $(this).addClass('off');
                        timerRace.counter(carreraPlay);
                    });
                }
            }



        });

        replayCarrera.on('click',function(e){
            e.preventDefault();
            overLay.fadeToggle();

            $('.carrera-ganadores').fadeOut('slow',function(){
                $(this).addClass('off');
                timerRace.counter(carreraPlay);
            });
        });
        ///////////////////// EVENTOS PARA ACCIONAR LA CARRERA

        //////////////////// reproductor
        $('#reproductor').audio2_html5(reproductorConfig);
    }



    iconScroll.on('click',function(){
       $('body,html').animate({scrollTop: $('section').offset().top + 70},600);
    });

    function compararFecha(fecha){
        var dateActual = new Date();
        var difDate = new Date ( fecha - dateActual);
        var dif = {
            dia : difDate.getDate().toString().length > 1 ? difDate.getDate() :  '0'+difDate.getDate(),
            hora : difDate.getHours().toString().length > 1 ? difDate.getHours() :  '0'+difDate.getHours(),
            min : difDate.getMinutes().toString().length > 1 ? difDate.getMinutes() :  '0'+difDate.getMinutes(),
            sec : difDate.getSeconds().toString().length > 1 ? difDate.getSeconds() :  '0'+difDate.getSeconds()
        };
        timer.html( dif.dia + ':' + dif.hora + ':' + dif.min);
    }

    //////////timer
    var timerDias;
    if(timer.length > 0){

        var fullDate =  timer.attr('data-time');
        var fullDateSplited = fullDate.split(' ');
        var dia = parseInt(fullDateSplited[0]);
        var horas = parseInt(fullDateSplited[1])-1;
        var minutos = parseInt(fullDateSplited[2]);
        var date = new Date(2015,3,dia,horas,minutos);

        compararFecha(date);

        timerDias = setInterval(compararFecha(date), 1000 );

    }
    //////////timer

    ///////////////////// TERMINA DESKTOP

    if(mobile){
        $('*[class*="equipo-text-"]').each(function(i){
            var numero = $(this).find('span.hidden-xs').html();
            $(this).append('<a href="tel:/'+numero+'" style="color:inherit">'+numero+'</a>');
        });
    }

    //////////////////// MOBILE

    if(mobile || tablet){
        $('#mobileMenuDrop').on('click tap',function(e){
            e.preventDefault();
            var menuMobile =$('.menu-list-mobile');

            if(parseInt(menuMobile.css('top')) != 0){
                menuMobile.removeClass('ocultar');
                menuMobile.animate({top:0},{ duration: 300, easing : 'easeOutBounce'});
            }else{
                menuMobile.addClass('ocultar');
                menuMobile.animate({top:'-100%'},{ duration: 300, easing : 'easeOutBounce'});
            }


        });

        $('#reproductorMobile').audio2_html5(reproductorConfig);
        $('#mobileAudioSingle').on('click tap',function(e){
            e.preventDefault();
            var audio = document.getElementById('ganador_audio');
            audio.loop = false;
            var icono = $(this).find('span.sprites');
            if(icono.hasClass('play-audio')){
                icono.removeClass('play-audio');
                icono.addClass('pause-audio');
                audio.play();
            }else{
                icono.addClass('play-audio');
                icono.removeClass('pause-audio');
                audio.pause();
            }
        });
    }


    ///////////////todos los disposit ivos

   $('#audioExampleButton, #audioExampleButton1').on('click tap',function(e){
       e.preventDefault();
       var icono = $(this).find('span.sprites');
       var audio = document.getElementById('exampleAudio');
       if(icono.hasClass('play-audio')){
           icono.removeClass('play-audio');
           icono.addClass('pause-audio');
           audio.play();
       }else{
           icono.addClass('play-audio');
           icono.removeClass('pause-audio');
           audio.pause();
       }

   });

    $('#exampleAudio').on('ended',function(){
        var icono = $('#audioExampleButton').find('span.sprites');
        icono.addClass('play-audio');
        icono.removeClass('pause-audio');
    });

   $('.shareButton').click(function(e)
   {
	   if($(this).attr('href') == '#')
	   {
		   e.preventDefault();
		   
		   facebookUtil.sharePost($(this).data('shareUrl'));
	   }
   });
   
   $(document).on('post_completed', function(response)
   {
	   var dataUrl = $('#sharedOk').data('urlSharedOk');
	   var participationId = $('#sharedOk').data('id');

	   $.ajax({
		   type: "POST",
		   url: dataUrl,
		   data: { id: participationId },
		   cache: false,
		   error: function(jqXHR, textStatus, errorThrown)
		   {
			   sending = false;
			   console.log(textStatus);
			   console.log(errorThrown);
		   },	
		   success: function(data)
		   {
			   sending = false;
		
			   if (data.error === true) 
			   {
				   console.log("error!");
			   } else 
			   {
				   console.log("success!");;
			   }
	        }					
	    });
   });
});