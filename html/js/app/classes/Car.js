/**
 * Created by Agustin on 12/03/2015.
 */
/**
 * Class Car
 * @param config
 * @constructor
 */
function Car( config ) {
    this._path = config.path;
    var _this = this;
    this.bridgeHide = false;
    this.nombre = config.nombre;
    this._rotation = 0;
    this._elapsed = 0;
    this._velocity = 0;
    this._throttle = 0;
    this._car = null;
    this._container = null;

    this.lapCount = 0;
    this.laps = 2;
    this._running = false;

    this.ACCELERATION = config.aceleration ;
    this.FRICTION = config.friction;
    this.MAXVELOCITY = config.maxVel;

    (function() {
        initCar();
        setPath();
        //requestAnimationFrame(render);
        _this.posInicial();
        console.log(_this.laps)
    })();

    function initCar() {

        _this._container = config.container;
        _this._car = config.tracker;

        _this._position = _this._path.getPointAt(_this._path.length);

        _this._car.style[Racer.transform+'Origin'] = '0px 0px'; //set the transform point;
    }

    function setPath() {
        _this._position = _this._path.getPointAt(_this._path.length);
        _this._rotation = 0;
        _this._elapsed = 0;
        _this._velocity = new Point(2, 0);
        _this._velocity.length = 0;
        _this._throttle = 0;

        //initially render the car
        _this._velocity.length = 0.15;
        render();
        _this._velocity.length = 0;
    }

    function render() {
        var trackOffset = _this._path.length - (_this._elapsed % _this._path.length);
        var trackPoint = _this._path.getPointAt(trackOffset);
        var trackAngle = _this._path.getTangentAt(trackOffset).angle;

        _this._velocity.length += _this._throttle; //apply the throttle

        if (!_this._throttle) {
            //slow down since the throttle is off
            _this._velocity.length *= _this.FRICTION;
        }

        if (_this._velocity.length > _this.MAXVELOCITY) {
            //stop the velocity at a certain point
            _this._velocity.length = _this.MAXVELOCITY;
        }

        _this._velocity.angle = trackAngle;

        trackOffset -= _this._velocity.length;
        _this._elapsed += _this._velocity.length;

        //find if a lap has been completed
        if (trackOffset < 0) {
            while (trackOffset < 0) trackOffset += _this._path.length;
            trackPoint = _this._path.getPointAt(trackOffset);
            _this.lapCount ++;
            if(_this.lapCount == _this.laps){
                _this.pause();
            }
        }



        if (_this._velocity.length > 0.1) {
            //render the car if there is actually velocity
            renderCar(trackPoint);
        }

        requestAnimationFrame(render);
    }

    function renderCar(point) {
        var velocityPoint = _this._position.add(_this._velocity.multiply(Math.random(0.2)+10));
        var difference = point.subtract(velocityPoint);
        var preference = 0.1;
        var midpoint = difference.multiply(preference);

        _this._rotation = difference.angle;
        _this._rotation = parseFloat(_this._rotation.toFixed(20));
        _this._rotation = _this._rotation.toFixed(10);
        _this._position = _this._position.add(midpoint);
        _this._position.x = parseFloat(_this._position.x.toFixed(20));
        _this._position.y = parseFloat(_this._position.y.toFixed(20));

        /*if (Racer.android) _this._car.style[Racer.transform] = _this.getMatrix();
        else*/
        _this._car.style[Racer.transform] = 'translate3d('+_this._position.x+'px, '+_this._position.y+'px, 0px)rotate('+_this._rotation+'deg)';
    }

}

Car.prototype.getMatrix = function(){
    var _this = this;
    var rad = _this._rotation.rotation * (Math.PI * 2 / 360);
    var cos = Math.cos(rad);
    var sin = Math.sin(rad);
    var a = parseFloat(cos).toFixed(8);
    var b = parseFloat(sin).toFixed(8);
    var c = parseFloat(-sin).toFixed(8);
    var d = a;
    return 'matrix('+a+', '+b+', '+c+', '+d+', '+_this._position.x+', '+_this._position.y+')';
};

Car.prototype.play = function(){
    this._throttle = this.ACCELERATION;
    this._running = true;
    //console.log('arranca');
};

Car.prototype.pause = function(){
    this._throttle = 0;
    this._running = false;
    //console.log('freno');
};


Car.prototype.posInicial = function(){
    var _this = this;
    point = _this._path.getPointAt(0);

    var velocityPoint = _this._position.add(_this._velocity.multiply(10));
    var difference = point.subtract(velocityPoint);
    var preference = 0.1;
    var midpoint = difference.multiply(preference);

    _this._rotation = point.angle + 120;
    _this._rotation = parseFloat(_this._rotation.toFixed(20));
    _this._rotation = _this._rotation.toFixed(10);
    _this._position = _this._position.add(midpoint);
    _this._position.x = parseFloat(_this._position.x.toFixed(20));
    _this._position.y = parseFloat(_this._position.y.toFixed(20));

    /*if (Racer.android) _this._car.style[Racer.transform] = _this.getMatrix();
    else*/

    _this._car.style[Racer.transform] = 'translate3d('+_this._position.x+'px, '+_this._position.y+'px, 0px)rotate('+_this._rotation+'deg)';
};

Car.prototype.getPointActual = function(){
    var _this = this;
    return "Auto " + _this.nombre + " : " + (_this._path.length - (_this._elapsed % _this._path.length));
};

Car.prototype.checkBridge = function(obj, limit, auto){
    var _this = this;

    var offsetTrakPosition = (_this._path.length - (_this._elapsed % _this._path.length));
    if( offsetTrakPosition < limit.maximo && offsetTrakPosition > limit.minimo ){
        if(!_this.bridgeHide){
            obj.style.zIndex = 10;
            auto.style.zIndex = 11;
            _this.bridgeHide = true;
        }
    }else{
        if(_this.bridgeHide){
            obj.style.zIndex = 1;
            auto.style.zIndex = 1;
            _this.bridgeHide = false;
        }
    }
};