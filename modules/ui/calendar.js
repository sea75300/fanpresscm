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

    _dblClick = null;
    _entryClick = null;

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
    
    setDoubleClick(_event) {
        this._dblClick = _event;
    }
    
    setEntryClick(_event) {
        this._entryClick = _event;
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
        var _dbEv = this._dblClick;
        var _eDbEv = this._entryClick;

        for (var i = 0; i < this._dayArray.length; i++) {

            let _row = document.createElement('div');
            _row.id = 'fpcm-id-calendar-' + this._id + '-row-' + i;
            _row.classList.add('row');

            let _is = this._dayArray[i];

            for (var _d = 0; _d < _is.length; _d++) {
                
                let _cur = _is[_d];
                let _ts = this._toTimeStamp(_is[_d]);

                let _col = this._colCell(_cur, _dbEv);
                let _txt = this._colText(_cur);
                
                _col.appendChild(_txt);

                let _lookup = `${_cur.getFullYear()}-${_cur.getMonth()+1}-${_cur.getDate()}`;

                if (!this._entries[_lookup]) {
                    _row.appendChild(_col);
                    continue;
                }

                for (var _e in this._entries[_lookup]) {

                    let _entry = this._colEntry(this._entries[_lookup][_e], _eDbEv);
                    _col.appendChild(_entry);

                }                    

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
    
    _colCell(_date, _dbEv) {

        let _col = document.createElement('div');
        _col.id = 'fpcm-id-calendar-' + this._id + '-col-' + _date.getMilliseconds();
        _col.classList.add('col-12');
        _col.classList.add('col-md');
        _col.classList.add('px-2');
        _col.classList.add('pt-2');
        _col.classList.add('pb-5');
        _col.classList.add('m-2');
        _col.classList.add('border');
        _col.classList.add('border-1');
        _col.classList.add('border-secondary-subtle');
        _col.classList.add('text-wrap');
        
        this._addDataset(_col, _date.toDateString());

        if (this._today === _date.toDateString()) {
            _col.classList.add('bg-body-secondary');
            _col.classList.add('bg-opacity-50');
        }

        if (this._dblClick === null) {
            return _col;    
        }

        _col.addEventListener('dblclick', function (_e) {
            _e.preventDefault();
            _dbEv(_e);
        });

        return _col;
    }
    
    _colText(_date) {

        let _txt = document.createElement('div');
        _txt.innerHTML = _date.getDate();
        _txt.classList.add('d-flex');
        _txt.classList.add('justify-content-end');
        _txt.classList.add('pe-none');
        _txt.classList.add('user-select-none');
        _txt.classList.add('border-bottom');
        _txt.classList.add('border-1');
        _txt.classList.add('mb-1');
        _txt.classList.add('fs-3');
        
        if (this._today === _date.toDateString()) {
            _txt.classList.add('fw-bold');
        }        
        else {
            _txt.classList.add('text-body-tertiary');
        }

        return _txt;
    }
    
    _colEntry(_entry, _eDbEv) {

        let _colEntry = document.createElement('div');
        _colEntry.innerHTML = _entry.label;
        
        if (!_entry.id) {
            _entry.id = fpcm.ui.getUniqueID();
        }

        _colEntry.dataset.id = _entry.id;
        
        _colEntry.classList.add('d-flex');
        _colEntry.classList.add('btn');
        _colEntry.classList.add('btn-sm');
        
        if (_entry.class) {
            _colEntry.className += ' ' + _entry.class;
        }
        
        if (_entry.src) {
            _colEntry.dataset.src = _entry.src;
        }

        if (this._entryClick === null) {
            return _colEntry;    
        }

        _colEntry.addEventListener('click', function (_e) {
            _e.preventDefault();
            _eDbEv(_e);
        });

        return _colEntry;
    }
    
    _addDataset(_item, _date) {
        _item.dataset.date = _date;
        _item.dataset.timestamp = this._toTimeStamp(_date);
    }
    
    _toTimeStamp(_dateStr) {
        return Date.parse(_dateStr) / 1000;
    }

}