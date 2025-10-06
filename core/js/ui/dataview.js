/**
 * FanPress CM Data View Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.dataview = {

    _baseItem: {},

    render: function (id, params) {

        if (!params) {
            params = {};
        }

        if (!fpcm.vars.jsvars.dataviews) {
            return;
        }

        if (!fpcm.dataview.exists(id)) {
            console.log('Dataview ' + id + ' does not exists in fpcm.vars.jsvars.dataviews!');
            return false;
        }

        if (!fpcm.vars.jsvars.dataviews[id].init) {
            return false;
        }

        if (!fpcm.vars.jsvars.dataviews[id].columns.length) {
            console.log('Dataview ' + id + ' does not contain any columns!');
            return false;
        }

        var obj         = fpcm.vars.jsvars.dataviews[id];
        var style       = '';

        obj.fullId      = fpcm.dataview.getFullId(id);
        obj.headId      = obj.fullId + '-head';
        obj.rowsId      = obj.fullId + '-rows';

        fpcm.dataview._baseItem = document.getElementById(obj.fullId);
        if (!fpcm.dataview._baseItem) {
            return false;
        }

        fpcm.dataview._createHead(obj, obj.columns);
        fpcm.dataview._createRows(obj);

        if (typeof params.onRenderAfter === 'function') {
            params.onRenderAfter.call();
        }

        fpcm.ui.assignCheckboxes();

        let _placeholder = fpcm.dataview._baseItem.querySelectorAll('div.row.placeholder-wave');
        for (var _i = 0; _i < _placeholder.length; _i++) {
            _placeholder[_i].remove();
        }

    },

    updateAndRender: function (id, params) {

        if (!fpcm.vars.jsvars.dataviews || !fpcm.dataview.exists(id)) {
            return;
        }

        fpcm.dom.fromId(fpcm.dataview.getFullId(id)).empty();
        fpcm.dataview.render(id, params);
    },

    getDataViewWrapper: function (id, _class) {

        if (!_class) {
            _class = 'fpcm-ui-dataview-wrapper';
        }

        return '<div id="'+ fpcm.dataview.getFullId(id) +'" class="' + _class + '"></div>';
    },

    getFullId: function (id) {
        return 'fpcm-dataview-'+ id;
    },

    getAlignString: function(_align, _prefix, _preprend) {

        if (_prefix === undefined) {
            _prefix = '';
        }
        else {
            _prefix += '-';
        }

        switch (_align) {
            case 'left' :
                _align = 'start';
                break;
            case 'right' :
                _align = 'end';
                break;
        }

        let _return = [];

        if (_preprend !== undefined) {
            _return.push(_preprend);
        }

        _return.push('text-' + _prefix + _align);

        return _return;
    },

    getSizeString: function(item) {

        if (!item.size) {
            return ['col'];
        }

        return [
            'col-12',
            'col-lg-' + item.size
        ];
    },

    exists: function(id) {

        if (!fpcm.vars.jsvars.dataviews[id]) {
            return false;
        }

        return true;
    },

    _createHead: function (_cfg) {

        let _el = document.createElement('div');
        _el.classList.add('row', 'bg-gradient');

        if (fpcm.ui.darkModeEnabled()) {
            _el.classList.add('bg-primary-subtle');
        }
        else  {
            _el.classList.add('text-bg-primary');
        }

        _el.classList.add('py-1', 'fpcm', 'ui-dataview-head');
        _el.id = _cfg.headId;

        for (var _i in _cfg.columns) {

            let _col = _cfg.columns[_i];

            let _style = [
                'fpcm',
                'align-self-center',
                'py-0',
                'py-md-1'
            ];

            if(_col.class) {
                let _ccTmp = _col.class.split(' ');
                _style = _style.concat(_ccTmp);
            }

            if (!_col.descr) {
                _style.push('d-none', 'd-lg-block');
            }

            _style = _style.concat(
                fpcm.dataview.getAlignString(_col.align, 'md', 'text-center'),
                fpcm.dataview.getSizeString(_col)
            );

            let _colEl = document.createElement('div');

            _colEl.id = _cfg.fullId + '-dataview-headcol-' + _col.name + _i;

            if (_col.descr) {
                _colEl.innerHTML = fpcm.ui.translate(_col.descr);
            }
            else {
                let _ede = document.createElement('span');
                _ede.classList.add('d-block', 'd-md-none');
                _ede.innerHTML = '&nbsp;';
                _colEl.appendChild(_ede);
            }

            _colEl.classList.add(..._style);


            _el.appendChild(_colEl);
            _colEl = null;
        }

        fpcm.dataview._baseItem.appendChild(_el);
    },

    _createRows: function (_cfg) {

        let _el = document.createElement('div');
        _el.classList.add('fpcm', 'ui-dataview-rows');
        _el.id = _cfg.rowsId;

        for (var _i in _cfg.rows) {
            fpcm.dataview._addRow(_i, _cfg.rows[_i], _cfg, _el);
        }


        fpcm.dataview._baseItem.appendChild(_el);
    },

    _addRow: function(index, row, obj, _domEl) {

        let _rowId           = obj.fullId + '-dataview-row-' + index;

        let _rowStyle = [
            'fpcm',
            'row',
            'py-2',
            'border-bottom',
            'border-2',
            'border-secondary',
            'border-opacity-50'
        ];

        if (row.isheadline) {
            _rowStyle.push('fpcm-ui-dataview-subhead', 'bg-dark-subtle', 'bg-gradient');
        }
        else {
            _rowStyle.push('ui-background-transition');
        }

        if (row.class) {
            let _rcTmp = row.class.split(' ');
            _rowStyle = _rowStyle.concat(_rcTmp);
        }

        if (row.isNotFound) {
            _rowStyle.push('ui-dataview-notfound');
        }

        let _rowEl = document.createElement('div');
        _rowEl.id = _rowId;
        _rowEl.classList.add(..._rowStyle);

        for (var _i in row.columns) {

            var _colMeta   = obj.columns[_i] ? obj.columns[_i] : false;
            if (!_colMeta) {
                return;
            }

            let _colData = row.columns[_i];

            if (!_colMeta.size) {
                _colMeta.size = '';
            }

            var colId = _rowId + '-dataview-rowcol-' + _colData.name + index;

            let _style = [
                'fpcm',
                'ui-dataview-type' + _colData.type,
                'align-self-center',
                'my-1'
            ];

            if(_colMeta.class) {
                let _cmcTmp = _colMeta.class.split(' ');
                _style = _style.concat(_cmcTmp);
            }

            if(_colData.class) {
                let _cdcTmp = _colData.class.split(' ');
                _style = _style.concat(_cdcTmp);
            }

            if (row.isNotFound === true) {
                _style.push('text-start', 'col');
            }
            else {
                _style = _style.concat(
                    fpcm.dataview.getAlignString(_colMeta.align),
                    fpcm.dataview.getSizeString(_colMeta)
                );
            }

            let _value = '';
            if (_colData.value !== '') {
                _value = fpcm.ui.translate(_colData.value);
            }
            else {
                _style.push('d-none', 'd-lg-block');
            }

            let _colEl = document.createElement('div');
            _colEl.id = colId;
            _colEl.classList.add(..._style);

            if (_colData.type == fpcm.vars.jsvars.dataviews.rolColTypes.coltypeValue && _colData.value) {
                let _vwe = document.createElement('div');
                _vwe.classList.add('fpcm', 'ui-dataview-col-value');
                _vwe.innerHTML = _colData.value;
                _colEl.appendChild(_vwe);
            }
            else if (_colData.value) {
                _colEl.innerHTML = _value;
            }
            else {
                let _ede = document.createElement('span');
                _ede.classList.add('d-none', 'd-lg-block');
                _ede.innerHTML = '&nbsp;';
                _colEl.appendChild(_ede);
            }

            _rowEl.appendChild(_colEl);
            _colEl = null;
        }

        _domEl.appendChild(_rowEl);
    }

};