/**
 * Created by Agustin on 25/03/2015.
 */

$(document).ready(function(){
    var mobile = window.innerWidth < 480;
    var desktop = window.innerWidth > 1023;
    var tablet = window.innerWidth > 480 && window.innerWidth < 1025;
    var iconScroll = $('.scroll-down');
    var verCarrera = $('#verCarrera');
    var replayCarrera = $('#replayCarrear');
    var overLay = $('.carrera-overlay');

    //////////timer
    var timer = $('#timer');


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

        verCarrera.on('click',function(e){
            e.preventDefault();
            overLay.fadeToggle();
            $('.carrera-control').fadeOut('slow',function(){
                $(this).addClass('off');
                timerRace.counter(carreraPlay);

            });
        });

        console.log(timerRace.count);
        replayCarrera.on('click',function(e){
            e.preventDefault();
            overLay.fadeToggle();
            console.log(timerRace.count_to);

            carrera.reset();
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
    if(timer.length>0){
        var fullDate =  timer.attr('data-time');
        var fullDateSplited = fullDate.split(' ');
        var dia = parseInt(fullDateSplited[0]);
        var horas = parseInt(fullDateSplited[1])-1;
        var minutos = parseInt(fullDateSplited[2]);
        var date = new Date(2015,3,dia,horas,minutos);

        compararFecha(date);
        setInterval(compararFecha(date), 100 );
    }
    //////////timer

    ///////////////////// TERMINA DESKTOP


    //////////////////// MOBILE

    if(mobile || tablet){
        $('#mobileMenuDrop').on('click',function(e){
            e.preventDefault();
            $('.menu-list-mobile').toggleClass('open');
        });

        $('#reproductorMobile').audio2_html5(reproductorConfig);

    }


    ///////////////todos los dispositivos

   $('.mic-content .play').on('click',function(e){
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
});