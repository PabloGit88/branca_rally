/**
 * Created by Agustin on 10/03/2015.
 */
var soundControl, timerRace, carrera; //inicializamos los objetos vacios
var countTimer = document.getElementById('timerContent').attributes['data-counter'].value;


paper.install(window); //inicializamos paper.js incluyendolo en el objeto window

/**
 * Objeto Carrera
 * @type {{equipos: {rojo: null, azul: null}, pista: null, recorridos: {ganador: null, perdedor: null}, init: Function, initProp: Function, initClases: Function, correr: Function}}
 */
carrera = {
    _corridas: 0,
    _play: false,
    equipos: {
        rojo: null
    },
    pista: null,
    recorridos: {
        ganador: null
    },
    init: function(){
        window.Racer = this;
        var _this = this;
        _this.initProp();
        _this.initClases();

    },

    initProp: function(){
        var _this = this;

        var agent = navigator.userAgent.toLowerCase();
        if (agent.indexOf('firefox') != -1) {
            _this.transform = 'MozTransform';
        } else if (agent.indexOf('msie') != -1) {
            _this.transform = 'msTransform';
        } else {
            _this.transform = 'WebkitTransform';
        }

        if (agent.indexOf('android') != -1) {
            _this.android = true;
        }
    },

    initClases: function(){
        var _this = this;
        //inicializa ganador
        _this.recorridos.ganador =  new Track( document.getElementById('ganador') , document.getElementById('ganador_recorrido'));
        var configRojo = {
            laps: document.getElementById('ganador').attributes['data-laps'].value,
            aceleration: 10,
            friction: 0.9,
            maxVel: 6,
            path: _this.recorridos.ganador.path ,
            container: document.getElementById('contenedor_ganador'),
            tracker: document.getElementById('auto_ganador'),
            nombre: document.getElementById('contenedor_ganador').attributes['data-name'].value
        };
        _this.equipos.rojo = new Car(configRojo);

    },

    correr: function(){
        var _this = this;
        _this.equipos.rojo.play();
    },

    frenar: function(){
        var _this = this;
        _this.equipos.rojo.pause();
    },

    reset: function(callback){
        var _this = this;
        _this.equipos.rojo.lapCount = 0;
        var reiniciaTimer;
        //console.log('empieza intervalo reset');

        reiniciaTimer = setInterval(function(){
            _this.equipos.rojo.posInicial();
        },1000 / 30);

        setTimeout(function(){
            if(typeof callback === 'function'){
                callback();
            }
            //console.log('termina intervalo reset');
            clearInterval(reiniciaTimer);
        },1000)
    },
    toogleCorrer: function(){
        var _this = this;
        if(_this._play){
            _this.frenar();
            _this._play = false;
        }else{
            _this.correr();
            _this._play = true;
        }
    }
};



/**
 * Objeto timer
 * @type {Timer}
 */
var sounds = {
    participante : document.getElementById('ganador_audio'),
    largada : document.getElementById('timerAudio')
};
window.onload = function(){
    if(!bowser.msie || bowser.msie.version === 10){
        sounds.participante.play();
        sounds.largada.play();
        setTimeout(function(){
            sounds.participante.pause();
            sounds.largada.pause();
        },5);
    }

};

timerRace = new Timer({
    beeps: sounds.largada,
    count_to: countTimer,
    regresiva: true,
    content: document.getElementById('timerContent'),
    timer: document.getElementById('timerRace')
});  //llamamos el callback cuando termine de contar

var miIntervalo = null;

function carreraPlay(){

    //console.log("estoy por empezar a correr");

    if (!carrera.equipos.rojo._running ) {
        carrera.correr();
    }else{
        carrera.frenar();
    }


    sounds.participante.loop = true;
    sounds.participante.play();

    var bridge = document.getElementById('bridge');
    var limitGanador =  bridge.attributes['data-limit-ganador'].value ;
    limitGanador = eval("(function(){return " + limitGanador + ";})()");

    //Falso evento---> cada 10 milisegundos nos fijamos si terminaron de correr
    //Peligroso pero sirve, sino crear evento con jQuery porque en javascript IE no soporta
   // console.log("el estado de la carrera es -->" + carrera.equipos.rojo._running);
    miIntervalo = setInterval(function () {
        if (!carrera.equipos.rojo._running ) {
            sounds.participante.pause();
            sounds.participante.currentTime = 0;
        }
        carrera.equipos.rojo.checkBridge(bridge, {
            maximo: limitGanador.max,
            minimo: limitGanador.min
        }, document.getElementById('contenedor_perdedor'));

        if (!carrera.equipos.rojo._running ) {
            //console.log("el estado de la carrera es -->" + carrera.equipos.rojo._running);
            limpiarIntervalo();
            $('.carrera-control').find('#verCarrera').html('Revivir carrera <span class="sprites reload"><span class="sr-only">icono</span></span>');

            $('.carrera-control').fadeIn('slow',function(){
                $('.carrera-overlay').fadeToggle();
                $('#ganadoresCarrera').removeClass('off');
                carrera._corridas ++;
            });
        }
    }, 100);
}

function limpiarIntervalo() {
    //console.log("por limpiar intervalo");
    clearInterval(miIntervalo);
    //console.log(miIntervalo);
}

function eventos(){

    window.addEventListener('keypress',function(e){
        var spacebar = e.which == 32;
        var tab = e.which == 0;

        if(spacebar){
            /*soundControl.tooglePlay();*/
            carrera.toogleCorrer();
        }

        if(tab){
            //console.log(carrera.equipos.rojo.getPointActual());
            //console.log(carrera.equipos.azul.getPointActual());
        }

    });
}