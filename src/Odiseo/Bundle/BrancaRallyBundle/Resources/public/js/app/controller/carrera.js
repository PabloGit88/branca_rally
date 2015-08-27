/**
 * Created by Agustin on 10/03/2015.
 */
var soundControl, timerRace, carrera; //inicializamos los objetos vacios
var countTimer = document.getElementById('timerContent').attributes['data-counter'].value;
var intervaloCarrera = null;
paper.install(window); //inicializamos paper.js incluyendolo en el objeto window

/**
 * Objeto Carrera
 * @type {{equipos: {rojo: null, azul: null}, pista: null, recorridos: {ganador: null, perdedor: null}, init: Function, initProp: Function, initClases: Function, correr: Function}}
 */
carrera = {
    _corridas: 0,
    _play: true,
    equipos: {
        rojo: null,
        azul: null
    },
    pista: null,
    recorridos: {
        ganador: null,
        perdedor: null
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
            aceleration: 8,
            friction: 0.9,
            maxVel: 7,
            path: _this.recorridos.ganador.path ,
            container: document.getElementById('contenedor_ganador'),
            tracker: document.getElementById('auto_ganador'),
            nombre: document.getElementById('contenedor_ganador').attributes['data-name'].value
        };
        _this.equipos.rojo = new Car(configRojo);

        //inicializa perdedor
        _this.recorridos.perdedor = new Track(document.getElementById('perdedor') , document.getElementById('perdedor_recorrido'));
        var configAzul = {
            laps: document.getElementById('perdedor').attributes['data-laps'].value,
            aceleration: 7,
            friction: 0.9,
            maxVel: 6,
            path : _this.recorridos.perdedor.path,
            container: document.getElementById('contenedor_perdedor'),
            tracker: document.getElementById('auto_perdedor'),
            nombre: document.getElementById('contenedor_perdedor').attributes['data-name'].value
        };
        _this.equipos.azul = new Car(configAzul);


    },


    reset: function(callback){
        var _this = this;
        _this.equipos.rojo.lapCount = 0;
        _this.equipos.azul.lapCount = 0;
        var reiniciaTimer;
        //console.log('empieza intervalo reset');

        reiniciaTimer = setInterval(function(){
            _this.equipos.rojo.posInicial();
            _this.equipos.azul.posInicial();
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
    eventos();
};

timerRace = new Timer({
    beeps: document.getElementById('timerAudio'),
    count_to: countTimer,
    regresiva: true,
    content: document.getElementById('timerContent'),
    timer: document.getElementById('timerRace')
});  //llamamos el callback cuando termine de contar

function carreraPlay(){

    if (!carrera.equipos.rojo._running ) {
        carrera.equipos.rojo.play();
    }

    if (!carrera.equipos.azul._running ) {
        carrera.equipos.azul.play();
    }

    sounds.participante.play();

    var bridge = document.getElementById('bridge');
    var limitGanador =  bridge.attributes['data-limit-ganador'].value ;
    limitGanador = eval("(function(){return " + limitGanador + ";})()");
    var limitPerdedor = bridge.attributes['data-limit-perdedor'].value;
    limitPerdedor = eval("(function(){return " + limitPerdedor + ";})()");

    //Falso evento---> cada 10 milisegundos nos fijamos si terminaron de correr
    //Peligroso pero sirve, sino crear evento con jQuery porque en javascript IE no soporta
    intervaloCarrera = setInterval(function () {
        if (!carrera.equipos.azul._running && !carrera.equipos.rojo._running) {
            sounds.participante.pause();
        }

        carrera.equipos.rojo.checkBridge(bridge, {
            maximo: limitGanador.max,
            minimo: limitGanador.min
        }, document.getElementById('contenedor_perdedor'));

        carrera.equipos.azul.checkBridge(bridge, {
            maximo: limitPerdedor.max,
            minimo: limitPerdedor.min
        }, document.getElementById('contenedor_ganador'));

        if (!carrera.equipos.azul._running && !carrera.equipos.rojo._running) {
            console.log("checkeando ganador " + carrera.equipos.rojo._running);
            console.log("checkeando perdedor " + carrera.equipos.azul._running);
            cancelarCarreraIntervalo();
            carrera._corridas ++;

            $('.carrera-overlay').fadeIn();
            $('#ganadoresCarrera').removeClass('off');
        }
    }, 100);
}

function cancelarCarreraIntervalo(){
    clearInterval(intervaloCarrera);
    //console.log('termina carrera');
}

function eventos(){

    window.addEventListener('keypress',function(e){
        var spacebar = e.which == 32;
        var tab = e.which == 0;

        if(spacebar){
            /*soundControl.tooglePlay();*/
            if (!carrera.equipos.rojo._running ) {
                carrera.equipos.rojo.play();
            }else{
                console.log('termina rojo');
                carrera.equipos.rojo.pause();
            }

            if (!carrera.equipos.azul._running ) {
                carrera.equipos.azul.play();
            }else{
                console.log('termina azul');
                carrera.equipos.azul.pause();
            }
        }

        if(tab){
            //console.log(carrera.equipos.rojo.getPointActual());
            //console.log(carrera.equipos.azul.getPointActual());
        }

    });
}