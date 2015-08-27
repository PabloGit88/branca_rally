/**
 * Created by Agustin on 13/04/2015.
 */
var app = {
    mobileVideoRace: document.getElementById('mobileRace'),
    mobileAdjusts: function(){
        $('.video-race-content').css({height: $('#mobileRace').height()});
    },
    init: function(){
        var _this =  this;
        if(!bowser.msie || bowser.msie.version === 10){
            /**
             * Inicializa el objeto carrera
             */
            carrera.init();
        }else{
            /**
             * Ajustes para IE
             */
            _this.ieCustomize();
        }

        if(window.innerWidth < 1023){
            _this.mobileAdjusts();
        }

        _this.eventos();
    },
    eventos: function(){
        var _this = this;

        $(window).on('resize',function(){
            if(window.innerWidth < 1023) _this.mobileAdjusts();
        });
        $(document).on('click tap','#mobilePlayCarrera',function(e){
            //e.preventDefault();
            _this.fullScreenVideo(_this.mobileVideoRace,function(){
                _this.mobileVideoRace.play();
            });
            $('.carrera-overlay').toggleClass('off');
            $(this).fadeToggle();

        });

        if(_this.mobileVideoRace != null){
            setTimeout(function(){
                _this.mobileVideoRace.play();
                setTimeout(function(){
                    _this.mobileVideoRace.pause();
                    _this.mobileVideoRace.currentTime = 0;
                },10);
            },1000);
            _this.mobileVideoRace.addEventListener('ended',function(){
                _this.mobileVideoRace.currentTime = 0;
                    _this.fullScreenCancellVideo(_this.mobileVideoRace,function(){
                        $('.carrera-overlay').toggleClass('off');
                        $('#mobilePlayCarrera').fadeToggle();
                    });

            });
        }

    },


    fullScreenVideo: function(elem,callback){
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
            //console.log('full');

        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
            //console.log('moz');

        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
            //console.log('webkit');

        }
        if(typeof callback === 'function'){
            callback();
        }
    },

    fullScreenCancellVideo: function(elem,callback){
        if(document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if(document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if(document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
        if(typeof callback === 'function'){
            callback();
        }
    },

    ieCustomize: function(){
        var _this = this;
        var containers = {ind:$('#carreraCotaninerIndividual'),duo:$('#carreraCotaninerDupla')}
        if(containers.ind.length > 0){
            _this.carreraIE.individual(containers.ind);
        }

        if(containers.duo.length > 0){
            _this.carreraIE.dupla(containers.duo);
        }
    },

    carreraIE: {
        individual: function(container){
            var carreraContainer = container;
            var control = $('.carrera-control');
            var ieFix = $('#srcIEfix');
            var audio = $('#ganador_audio').clone().attr('id','nuevoAudioIEfix');
            var reproductor = $('#reproductorCopy').clone();
            var srcImg = ieFix.val();
            var newText = ieFix.attr('data-text');
            var img = '<img src="'+srcImg+'" class="center-block offset-top-30"/>';


            audio.attr('class','sr-only');
            reproductor.attr('class','visible-md visible-lg offset-top-180');
            reproductor.append( audio );
            carreraContainer.html( '' );
            carreraContainer.html( img );
            control.html( reproductor );

            reproductor.find('.sprites').css({top:'53%'});
            reproductor.find('h4').css({fontSize: '28px', padding:'0px 35%'}).html(newText);
            reproductor.find('#fixedIEtexto').append('<p class="text-center" style="padding:0px 25%;"> ¿Te gustaría ver la carrera? Ingresá desde <a href="https://www.google.com/chrome/browser/desktop/" target="_blank"> Chrome</a> o <a href="https://www.mozilla.org/es-AR/firefox/new/" target="_blank">Firefox</a> y viví una experiencia única</p>');
            var audioSelected = document.getElementById('nuevoAudioIEfix');

            reproductor.find('.play').on('click',function(e){
                e.preventDefault();

                audioSelected.play();
                audioSelected.loop = false;
                var icono = $(this).find('span.sprites');
                if(icono.hasClass('play-audio')){
                    icono.removeClass('play-audio');
                    icono.addClass('pause-audio');
                    audioSelected.play();
                }else{
                    icono.addClass('play-audio');
                    icono.removeClass('pause-audio');
                    audioSelected.pause();
                }
            });
            audioSelected.addEventListener('ended',function(){
                var icono = reproductor.find('.play').find('span.sprites');
                icono.addClass('play-audio');
                icono.removeClass('pause-audio');
            });
        },

        dupla:function(container){
            var carreraContainer = container;
            var video = $('#mobileRace').clone().attr('id','videoIE');
            video.attr('class','visible-lg visible-md');
            video.attr('controls','');
            var ieFix = $('#srcIEfix');
            var srcImg = ieFix.val();
            var control = $('.carrera-control').find('a');
            var img = '<img src="'+srcImg+'" class="center-block offset-top-30"/>';
            var divVideo = '<div id="popUp" style="position:fixed; z-index:1050; display: none; left: 50%; top:50%; margin:-191px 0 0 -340px"></div>';
            $('#ieFixVideo').append(divVideo);
            var $popUp = $('#popUp');
            $popUp.append(video);
            carreraContainer.html( '' );
            carreraContainer.html(img );
            video.on('ended',function(){
                $('body,html').css({overflow:'auto'});

                control.html('Revivir Carrera <span class="sprites reload"><span class="sr-only">reload</span></span>')
                $popUp.fadeOut(function(){
                    video.currentTime = 0;
                });
            });

        }
    }
};

