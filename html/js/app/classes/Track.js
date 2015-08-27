/**
 * Created by Agustin on 12/03/2015.
 */

/**
 * Class Track
 * @param canvas
 * @param recorre
 * @constructor
 */
function Track(canvas,recorre) {
    var _this = this;
    _this._canvas = null;
    _this._context = null;
    _this._paper = null;

    (function() {
        initCanvas();
        initPaper();
    })();

    function initCanvas() {
        _this._canvas = canvas;
        _this._context = _this._canvas.getContext('2d');
    }

    function initPaper() {

        _this._paper = new paper.PaperScope(); //create multiple scopes because this Track class would be used for multiple lanes
        _this._paper.setup(_this._canvas.id);


        var svg = recorre;
        var layer = new _this._paper.Layer();

        var path = layer.importSvg(svg).firstChild.firstChild;
        _this.path = path;
    }
}
