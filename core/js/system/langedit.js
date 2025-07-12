/**
 * FanPress CM language editor namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.langedit = {

    init: function() {

        if (!fpcm.vars.jsvars.langfile) {
            return false;
        }

        let _root = document.getElementById('fpcm-id-langedit-list');
        let _list = _root.childNodes.item(1);

        for (var _var in fpcm.vars.jsvars.langfile) {

            let _row = document.createElement('div');
            _row.classList.add('row', 'py-2', 'border-bottom', 'border-1', 'border-secondary');
            _row.setAttribute('data-var', _var);

            let _btn1 = document.createElement('button');
            _btn1.dataset.varname = _var;
            _btn1.classList.add('btn', 'btn-light', 'me-1');
            _btn1.innerHTML = (new fpcm.ui.forms.icon('edit')).getString();
            _btn1.setAttribute('title', fpcm.ui.translate('GLOBAL_EDIT'));
            _btn1.addEventListener('click', function (_e) {

                _e.preventDefault();

                let _varName = _e.currentTarget.dataset.varname;
                let _form = fpcm.langedit.dialogForm(_varName, fpcm.vars.jsvars.langfile[_varName]);

                fpcm.ui_dialogs.create({
                    id: 'langform-' + _varName.toLowerCase(),
                    title: 'Edit language var: ' + _varName,
                    content: _form,
                    closeButton: true,
                    dlButtons: [
                        {
                            text: 'GLOBAL_SAVE',
                            icon: "check",
                            clickClose: true,
                            click: function () {

                                let _varName = document.getElementById(fpcm.ui.prepareId(_form[0].id, true)).value;
                                let _varValue = document.getElementById(fpcm.ui.prepareId(_form[1].id, true)).value;

                                _varName = _varName.toUpperCase();

                                if (_form[0].value !== _varName) {
                                    delete(fpcm.vars.jsvars.langfile[_form[0].value]);
                                }

                                fpcm.vars.jsvars.langfile[_varName] = _varValue;
                                fpcm.langedit.executeSave();
                            }
                        }
                    ]
                });


            });

            let _btn2 = document.createElement('button');
            _btn2.dataset.varname = _var;
            _btn2.classList.add('btn', 'btn-light');
            _btn2.innerHTML = (new fpcm.ui.forms.icon('trash')).getString();
            _btn2.setAttribute('title', fpcm.ui.translate('GLOBAL_DELETE'));
            _btn2.addEventListener('click', function (_e) {

                _e.preventDefault();

                delete(fpcm.vars.jsvars.langfile[_e.currentTarget.dataset.varname]);
                _e.currentTarget.parentElement.parentElement.remove();
                fpcm.langedit.executeSave();
            });


            let _col1 = document.createElement('div');
            _col1.classList.add('col-auto', 'align-self-center');
            _col1.appendChild(_btn1);
            _col1.appendChild(_btn2);

            _row.appendChild(_col1)

            let _col2 = document.createElement('div');
            _col2.classList.add('col-4', 'align-self-center', 'flex-grow-0', 'text-truncate');
            _col2.innerText = _var;
            _row.appendChild(_col2);

            let _col3 = document.createElement('div');
            _col3.classList.add('col-4', 'align-self-center', 'flex-grow-1', 'text-wrap');


            let _varVal = fpcm.vars.jsvars.langfile[_var];
            if (_varVal instanceof Object) {
                _varVal = JSON.stringify(_varVal);
            }

            _col3.innerText = _varVal;
            _row.appendChild(_col3);

            _list.appendChild(_row);
        }


    },

    initAfter: function () {

        fpcm.dom.bindClick('#btnNew', function() {

            let _form = fpcm.langedit.dialogForm('', '');

            fpcm.ui_dialogs.create({
                id: 'langform-new',
                title: 'New language variable ',
                content: _form,
                closeButton: true,
                dlButtons: [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "save",
                        primary: true,
                        clickClose: true,
                        click: function () {

                            let _varName = document.getElementById(fpcm.ui.prepareId(_form[0].id, true)).value;
                            let _varValue = document.getElementById(fpcm.ui.prepareId(_form[1].id, true)).value;

                            if (!_varName || !_varValue) {
                                fpcm.ui.addMessage({
                                    txt: `Variable name or value must not be empty!`,
                                    type: 'error'
                                });

                                return false;
                            }

                            _varName = _varName.toUpperCase();

                            if (fpcm.vars.jsvars.langfile[_varName]) {
                                fpcm.ui.addMessage({
                                    txt: `Variable "${_varName}" already exists with value "${fpcm.vars.jsvars.langfile[_varName]}".`,
                                    type: 'error'
                                });

                                return false;
                            }

                            fpcm.vars.jsvars.langfile[_varName] = _varValue;
                            fpcm.langedit.executeSave();
                        }
                    }
                ]
            });
        });

        fpcm.dom.bindClick('#btnSave', function () {

            if (!document.getElementsByName('lang').length) {
                fpcm.langedit.executeSave(true);
            }
            
            return true;

        }, false, true);
    },

    executeSave: function (_noClick) {
        let _saveArea = document.createElement('textarea');
        _saveArea.classList.add('d-none');
        _saveArea.name = 'lang';
        _saveArea.value = JSON.stringify(fpcm.vars.jsvars.langfile);

        document.getElementById('fpcm-ui-form').append(_saveArea);

        if (_noClick) {
            return true;
        }

        fpcm.dom.fromId('btnSave').trigger('click');
    },

    dialogForm: function (_varName, _valInputVal) {

        if (_valInputVal instanceof Object) {
            _valInputVal = JSON.stringify(_valInputVal);
        }

        let _varInput = new fpcm.ui.forms.input();
        _varInput.type = 'text';
        _varInput.id = 'input-var';
        _varInput.label = 'Variable name';
        _varInput.placeholder = _varInput.label;
        _varInput.value = _varName;

        let _valueInput = new fpcm.ui.forms.textarea();
        _valueInput.id = 'input-value';
        _valueInput.label = 'Variable value';
        _valueInput.placeholder = _valueInput.label;
        _valueInput.value = _valInputVal;

        return [
            _varInput,
            _valueInput
        ];
    }

};