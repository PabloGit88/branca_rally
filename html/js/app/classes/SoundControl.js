/**
 * Created by Agustin on 11/03/2015.
 */
/**
 *
 * @param conf
 * @constructor
 */
function ControlSound(conf){
    var _this = this;
    _this.play = conf.autoPlay;
    _this.classes = {
        on: 'fa fa-volume-up',
        off: 'fa fa-volume-off',
        mute: 'fa fa-lock'
    };

    var audios = {
        left: conf.audio1,
        right: conf.audio2
    };

    var obj = {
        left: conf.left,
        right: conf.right
    };

    _this.protoAudio = audios;
    _this.protoObj = obj;

    (function(){
        //eventos();
        audios.left.loop = true;
        audios.right.loop = true;
        _this.volumen(1,0); //arranca el sonido de la izquierda
        _this.toogleFa(obj.right,this);
        _this.tooglePlay();
    })();



    function eventos(){
        obj.left.addEventListener('mouseover',function(){
            _this.toogleFa(obj.right,this);
            _this.volumen(0,1);
        });

        obj.right.addEventListener('mouseover',function(){
            _this.toogleFa(obj.left,this);
            _this.volumen(1,0);
        });


    }


}
ControlSound.prototype.volumen = function(state1, state2){
    this.protoAudio.left.volume = state1;
    this.protoAudio.right.volume = state2;
};

ControlSound.prototype.toogleFa = function(_obj,__this){
    var _this = this;
    if(_this.play){
        __this.innerHTML= "<i class='"+ _this.classes.on+"'></i>";
        _obj.innerHTML= "<i class='"+ _this.classes.off+"'></i>";
    }else{
        __this.innerHTML= "<i class='"+ _this.classes.off+"'></i> <i class='"+ _this.classes.mute +"'></i>";
        _obj.innerHTML= "<i class='"+ _this.classes.off+"'></i> <i class='"+ _this.classes.mute +"'></i>";
    }
};

ControlSound.prototype.tooglePlay = function(obj){
    var button = obj != undefined ? obj: document.getElementById('buttonPause');
    var _this = this;
    var laClase;
    if(_this.play){

        _this.protoAudio.left.pause();
        _this.protoAudio.right.pause();
        _this.play = false;

    }else{

        _this.protoAudio.left.play();
        _this.protoAudio.right.play();
        _this.play = true;

    }

    if(_this.protoAudio.left.volume > 0){
        laClase = {
            u : _this.classes.off,
            d : _this.classes.on
        }
    }else{
        laClase = {
            u : _this.classes.on,
            d : _this.classes.off
        }
    }
    _this.protoObj.left.innerHTML = !_this.play ? "<i class='"+ _this.classes.off+"'></i> <i class='"+ _this.classes.mute +"'></i>" : "<i class='"+ laClase.u +"'></i>";
    _this.protoObj.right.innerHTML = !_this.play ? "<i class='"+ _this.classes.off+"'></i> <i class='"+ _this.classes.mute +"'></i>" : "<i class='"+ laClase.d +"'></i>";

    button.innerHTML = _this.play ? "<i class='fa fa-pause'></i>" : "<i class='fa fa-play'></i>";

};


