/**
 * Created by Agustin on 16/03/2015.
 */
function Animate(conf){
    var _this = this;
    var def = conf ? conf : initDefault();
    _this.vel = def.vel;
    _this.imgs = def.imgs;
    _this.cont = def.cont;
    _this.format = def.format;
    _this.prefix = def.prefix;
    _this.path = def.path;
    _this.delay = parseInt(def.delay) + 2;
    _this.css = def.css;
    _this.play = false;
    _this.cssProprety = 'background-image';
    _this.loop = null;
    _this.loopCount = 0;
    _this.secPosition = 1;
    (function(){
        init();
    })();

    function initDefault(){
        return {
            vel: 1000,
            cont: document.getElementById('animDefault'),
            imgs: 0, //cantidad de imagenes de la animacion
            path: '',
            format:'.png',
            prefix: '0',
            css: 'default',
            delay : 10
        };
    }

    function init(){
        _this.play = true;
        _this.cont.setAttribute('class', _this.css);

        setTimeout(function(){
            _this.cont.style['opacity'] = 0.7;
            _this.loop = _this.playFor('loop');
        }, _this.delay * 1000);

    }



    function crearLoop(){
        return setInterval(function(){
            _this.secPosition = _this.secPosition > _this.imgs ? 1 : _this.secPosition + 1;
            _this.cont.style[_this.cssProprety] =  'url("'+_this.path + _this.prefix +  _this.secPosition + _this.format +'")';
            if(!_this.play){
                clearInterval(_this.loop);
            }
        },_this.vel);
    }
}

Animate.prototype.playFor = function(veces){
    var _this = this;

    return setInterval(function(){

        _this.secPosition = _this.secPosition > _this.imgs ? 1 : _this.secPosition + 1;
        if(_this.secPosition === 1){
            _this.loopCount++;
        }

        _this.cont.style[_this.cssProprety] =  'url("'+_this.path + _this.secPosition + _this.format +'")';

        if(veces != 'loop')
        {
            if(_this.loopCount == veces )
            {
                _this.loopCount = 0;
                _this.cont.style['opacity'] = 0;
                clearInterval(_this.loop);
            }
        }
    },_this.vel);
};

Animate.prototype.stopLoop = function(){
    var _this = this;
    _this.loopCount = 0;
    _this.cont.style['opacity'] = 0;
    clearInterval(_this.loop);
};