/**
 * FanPress CM UI calendar widget Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class calendar {  

    _id = '';
    _element = '';
    _start = null;
    _entries = [];
    _dayArray = [ [] ];
    _ctr = 0;
    _today = 0;

    constructor(_element) {

        this._id = _element;
        this._element = fpcm.ui.prepareId(this._id, true);
        this._today = (new Date()).toDateString();

    }
    
    setData(_data) {
        this._entries = _data;
    }
    
    setStart(_start) {
        this._start = _start;
    }

    render() {

        let _current = this._start ? new Date(this._start) : new Date();
        let _y = _current.getFullYear();
        let _m = _current.getMonth();

        let _first = new Date(_y, _m, 1);
        let _last = new Date(_y, _m+1, 0);
        
        let _firstDayOfWeek = _first.getDay();
        let _lastDayOfWeek = _last.getDay();
        let _lastDaysOM = _last.getDate();

        this._prependDays(_firstDayOfWeek, _y, _m);        

        for (var _i = 1; _i <= _lastDaysOM; _i++) {
            this._pushDay(_y, _m, _i);
        }
        
        this._appendDays(_lastDayOfWeek, _y, _m);
        
        if (!this._dayArray.length || !this._dayArray[0].length) {
            return false;
        }
        
        let _wrapper = document.getElementById(this._element);
        
        if (!_wrapper) {
            return false;
        }
        
        _wrapper.innerHTML = '';

        for (var i = 0; i < this._dayArray.length; i++) {

            let _row = document.createElement('div');
            _row.id = 'fpcm-id-calendar-' + this._id + '-row-' + i;
            _row.classList.add('row');
            _row.classList.add('g-0');

            let _is = this._dayArray[i];

            for (var z = 0; z < _is.length; z++) {

                let _col = document.createElement('div');
                _col.id = 'fpcm-id-calendar-' + this._id + '-col-' + _is[z].getMilliseconds();
                _col.classList.add('col');
                _col.classList.add('px-2');
                _col.classList.add('pt-2');
                _col.classList.add('pb-5');
                _col.classList.add('m-1');
                _col.classList.add('border');
                _col.classList.add('border-1');
                _col.classList.add('h-25');

                if (this._today === _is[z].toDateString()) {
                    _col.classList.add('bg-body-tertiary');
                    _col.classList.add('bg-opacity-50');
                }
                
                let _txt = document.createElement('span');
                _txt.innerHTML = _is[z].getDate();
                _txt.classList.add('d-flex');
                _txt.classList.add('justify-content-end');
                _txt.classList.add('fs-6');
                _col.appendChild(_txt);

                _row.appendChild(_col);
            }
            
            
            _wrapper.appendChild(_row);
            _wrapper.classList.add('w-100');

        }

    }
    
    _pushDay(_y, _m, _d) {
        
        let _tmp = new Date(_y, _m, _d);

        let _dow = _tmp.getDay();

        if (this._dayArray[this._ctr].length == 7) {
            this._ctr++;
            this._dayArray[this._ctr] = [];
        }


        this._dayArray[this._ctr].push(_tmp);
        
        _tmp = null;
        _dow = null;
        
    }
    
    _prependDays(_fdow, _y, _m) {
        
//        if (_fdow === 1) {
//            return;
//        }        

        let _domPrev = ( new Date(_y, _m, 0) ).getDate();

        let _count = 0;
        
        if (_fdow === 0) {
            _count = 5;
        }
        else {
            _count = _fdow - 1;
        }
        
        if (_count < 1) {
            return;
        }

        let _prvm = _m - 1;
        let _prvw = _domPrev - _count + 1;

        for (var _x = _prvw; _x <= _domPrev; _x++) {
            this._pushDay(_y, _prvm, _x);
        }     
        
    }
    
    _appendDays(_fdow, _y, _m) {
        
        if (_fdow === 0) {
            return;
        }      

        let _count = 7 - _fdow;

        let _nrvm = _m + 1;

        for (var _x = 1; _x <= _count; _x++) {
            this._pushDay(_y, _nrvm, _x);
        }     
        
    }

}