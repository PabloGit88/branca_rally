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
            aceleration: 10,
            friction: 0.9,
            maxVel: 6,
            path: _this.recorridos.ganador.path ,
            container: document.getElementById('contenedor_ganador'),
            tracker: document.getElementById('auto_ganador'),
            nombre: document.getElementById('contenedor_ganador').attributes['data-name'].value
        };
        _this.equipos.rojo = new Car(configRojo);

        //inicializa perdedor
        _this.recorridos.perdedor = new Track(document.getElementById('perdedor') , document.getElementById('perdedor_recorrido'));
        var configAzul = {
            aceleration: 10,
            friction: 0.9,
            maxVel: 7,
            path : _this.recorridos.perdedor.path,
            container: document.getElementById('contenedor_perdedor'),
            tracker: document.getElementById('auto_perdedor'),
            nombre: document.getElementById('contenedor_perdedor').attributes['data-name'].value
        };
        _this.equipos.azul = new Car(configAzul);


    },

    correr: function(){
        var _this = this;
        _this.equipos.rojo.play();
        _this.equipos.azul.play();
    },

    frenar: function(){
        var _this = this;
        _this.equipos.rojo.pause();

        _this.equipos.azul.pause();
    },

    reset: function(){
        var _this = this;
        _this.equipos.rojo.posInicial();
        _this.equipos.azul.posInicial();
    },
    toogleCorrer: function(){
        var _this = this;
        if(!_this._play){
            _this.frenar();
            _this._play = true;

        }else{
            _this.correr();
            _this._play = false;
        }
    }
};
/**
 * Inicializa el objeto carrera
 */
carrera.init();

/**
 * Objeto timer
 * @type {Timer}
 */

timerRace = new Timer({
    beeps: document.getElementById('timerAudio'),
    count_to: countTimer,
    regresiva: true,
    content: document.getElementById('timerContent'),
    timer: document.getElementById('timerRace')
});  //llamamos el callback cuando termine de contar

function carreraPlay(){
    carrera.toogleCorrer();
    var bridge = document.getElementById('bridge');
    var limitGanador =  bridge.attributes['data-limit-ganador'].value ;
    limitGanador = eval("(function(){return " + limitGanador + ";})()");
    var limitPerdedor = bridge.attributes['data-limit-perdedor'].value;
    limitPerdedor = eval("(function(){return " + limitPerdedor + ";})()");

    //Falso evento---> cada 10 milisegundos nos fijamos si terminaron de correr
    //Peligroso pero sirve, sino crear evento con jQuery porque en javascript IE no soporta
    var checkEndRace = setInterval(function () {
        if (!carrera.equipos.azul._running || !carrera.equipos.rojo._running) {

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
            clearInterval(checkEndRace);
            $('.carrera-overlay').fadeIn('slow',function(){
                $('#ganadoresCarrera').removeClass('off');
            });
        }
    }, 10);
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