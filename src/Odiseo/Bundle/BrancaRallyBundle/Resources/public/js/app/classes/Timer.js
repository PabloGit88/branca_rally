/**
 * Created by Agustin on 11/03/2015.
 */

function Timer(conf){
    this.defCount =  parseInt(conf.count_to);
    this.sounds = conf.beeps ? conf.beeps : false;
    this.count = 0;
    this.count_to = parseInt(conf.count_to);
    this.regresiva = conf.regresiva != undefined ? conf.regresiva : false;
    this.content = conf.content != undefined ? conf.timer :  null;
    this.timer = conf.timer != undefined ? conf.timer :  null;
    this.intervalo = null;
    this._msgs = {
        1: 'YA! ',
        2: 'LISTOS! ',
        3: 'PREPARADOS! '
    };
    var _this = this;
    (function(){
        if(_this.content == null){
            createElements();
        }
        if(_this.regresiva){
            _this.count = _this.count_to;
            _this.count_to = 0;
        }
    })();

    function createElements(){
        var _this= this;
        _this.content = document.createElement('div');
        _this.content.id = 'timerContent';
        _this.content.className = 'timer-content';

        _this.timer = document.createElement('span');
        _this.timer.id = 'timer';
        _this.timer.className = 'timer-count';

        _this.content.appendChild(_this.timer);
        document.body.appendChild(_this.content);
    }

}




Timer.prototype.counter = function( callBack ){
    var _this = this;
    (function(){
        _this.sounds.play();

        _this.intervalo = setInterval(function(){
           //console.log('empieza intervalo timer');
            _this.count = _this.regresiva ? _this.count - 1 : _this.count + 1 ;
            if(termina()){
                if (typeof callBack === "function") {
                    callBack();
                }
            }
        },1000);
    })();

    function termina(){


        //console.log(_this.count + ' -> ' + _this.count_to);
        if(_this.count == _this.count_to){
            _this.forceReset();
            //console.log('termino intervalo timer');
            document.getElementById('timerContent').className += ' hide';
            return true;
        }else{
            return false;
        }
    }

    function imprime(n){

        if(n < 4){
            n = _this._msgs[n] + " " +n;
            if(_this.sounds){
                switch (n){
                    case 1:
                        _this.sounds.beeps.dos.play();
                        break;
                    case 2 || 3 :
                        _this.sounds.beeps.uno.play();
                        break;
                }
            }

        }
        document.getElementById('timer').innerHTML = n;
    }


};

Timer.prototype.forceReset = function(){
     var _this = this;
    clearInterval(_this.intervalo);
    _this.count = _this.defCount;
    //console.log('cuenta ->' + _this.defCount + ' --> ' + _this.count);
};
