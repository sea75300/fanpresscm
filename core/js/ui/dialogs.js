/**
 * FanPress CM UI Tabs Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_dialogs = {

    create: function(_params) {

        if (_params.clickClose !== undefined) {
            console.error('_params.clickClose is an undefined param for fpcm.ui_dialogs.create()!');
        }

        if (_params.title === undefined) {
            _params.title = '';
        }

        if (_params.id === undefined) {
            _params.id = (new Date()).getTime();
        }

        let _dlgId = 'fpcm-dialog-' + _params.id;
        let _btnbase = 'fpcm-ui-dlgbtn-' + _params.id + '-';
        let _domEx = fpcm.dom.fromId(_dlgId);
        
        if (_domEx.length) {
            
            if (!_domEx.hasClass('fpcm ui-dialog-dom')) {
                _params.content = fpcm.dom.fromId(_dlgId).html();
                _params.modalClass = 'fpcm ui-dialog-dom';
                fpcm.dom.fromId(_dlgId).remove();
            }
            
            _params.keepDom = true;
        }

        if (_params.dlButtons === undefined) {
            _params.dlButtons = [];
        }

        if (_params.closeButton) {
            
            _params.dlButtons.push({
                text: 'GLOBAL_CLOSE',
                icon: 'times',
                clickClose: true,
                class: 'btn-outline-secondary'
            });
            
        }

        if (_params.url) {
            _params.content = fpcm.ui.createIFrame({
                src: _params.url,
                id: _dlgId + '-frame',
                classes: 'w-100 h-100'
            });
            
            if (!_params.useSize) {
                _params.class = 'modal-fullscreen';
            }

            _params.modalBodyClass = 'overflow-hidden' + (_params.modalBodyClass ? ' ' + _params.modalBodyClass : '');
        }

        if (_params.image) {
            _params.content = '<img src = "' + _params.image + '" role="presentation" / >';
        }
        
        if (_params.class === undefined) {
            _params.class = '';
        }
        
        if (_params.modalClass === undefined) {
            _params.modalClass = '';
        }
        
        if (_params.modalBodyClass === undefined) {
            _params.modalBodyClass = '';
        }
        
        if (_params.opener === undefined) {
            _params.opener = '';
        }
        
        if (_params.size === undefined) {
            _params.size = 'lg';
        }
        
        if (_params.content === undefined) {
            _params.content = '';
        }
        
        if (_params.scrollable === undefined || _params.scrollable === true) {            
            _params.class += ' modal-dialog-scrollable';
        }

        if (!fpcm.dom.fromId(_dlgId).length) {            
            let _modal = fpcm.vars.ui.dialogTpl;

            fpcm.dom.appendHtml('#fpcm-body', _modal.replace('{$title}', fpcm.ui.translate(_params.title))
                  .replace(/\{\$id\}/g, _dlgId)
                  .replace('{$opener}', _params.opener)
                  .replace('{$content}', _params.content)
                  .replace('{$class}', _params.class)
                  .replace('{$modalClass}', _params.modalClass)
                  .replace('{$modalBodyClass}', _params.modalBodyClass)
                  .replace('{$size}', _params.size ? 'modal-' + _params.size : '')
                  .replace('{$buttons}', ''));
        }

        let _domEl = document.getElementById(_dlgId);
        let _bsObj = new bootstrap.Modal(_domEl);
        
        if (!_params.keepDom) {
            
            _domEl.addEventListener('hidden.bs.modal', function (event) {
                 _bsObj.dispose(_domEl);
                 fpcm.dom.fromId(_dlgId).remove();
                 
                if (_params.dlOnClose) {
                    _params.dlOnClose(this, _bsObj);
                }
            }, {
                once: true
            });
        }
        else if (_params.dlOnClose) {
            _domEl.addEventListener('hidden.bs.modal', function (event) {
                _params.dlOnClose(this, _bsObj);                 
            }, {
                once: true
            });
        }
        
        if (_params.dlOnOpen) {
            _domEl.addEventListener('show.bs.modal', function (event) {
                _params.dlOnOpen(this, _bsObj);
            }, {
                once: true
            });
        }
        
        if (_params.dlOnOpenAfter) {
            _domEl.addEventListener('shown.bs.modal', function (event) {
                _params.dlOnOpenAfter(this, _bsObj);
            }, {
                once: true
            });
        }

        _bsObj.toggle(_domEl);
        if (_params.dlButtons !== undefined) {
            
            let _footer = document.querySelector('#' + _dlgId + ' div.modal-footer');
            _footer.innerHTML = '';
            
            for (var _idx in _params.dlButtons) {

                if (_params.dlButtons[_idx] == undefined) {
                    continue;
                }

                let _obj = _params.dlButtons[_idx];
                
                if (_obj.showLabel === undefined) {
                    _obj.showLabel = true;
                }                
                
                let _btn = document.createElement('button');

                _btn.type = 'button';
                _btn.className = 'btn' + (_obj.primary ? ' btn-primary' : '') + (_obj.class ? ' ' + _obj.class : '');
                
                if (!_obj.showLabel) {
                    _btn.innerHTML = fpcm.ui.getIcon(_obj.icon);
                    _btn.title = fpcm.ui.translate(_obj.text);
                }
                else {
                    _btn.innerHTML = (_obj.icon ? fpcm.ui.getIcon(_obj.icon) + ' <span class="fpcm-ui-label ps-1">' : '') + fpcm.ui.translate(_obj.text) + (_obj.icon ? '</span>' : '');
                }
                
                
                if (_obj.disabled !== undefined) {
                    _btn.disabled = _obj.disabled;
                }

                if (_obj.click == undefined && _obj.clickClose == undefined) {
                    _footer.appendChild(_btn);
                    continue;
                }

                _btn.onclick = function () {
                    
                    try {
                        
                        if (_obj.click) {
                            _obj.click.call(this, _bsObj);
                        }

                        if (!_obj.clickClose) {
                            return false;
                        }

                        _bsObj.toggle(_domEl);
                        return false;
                       
                    } catch (_e) {
                        fpcm.ui.addMessage({
                            type: 'error',
                            txt: _e
                        });
                        
                    }
                    
                };

                _footer.appendChild(_btn);
                if (_obj.primary || _obj.autofocus) {
                    _btn.focus();
                }

            }

        }

        if (!_params.headlines) {
            return true;
        }
        
        let _headlines = fpcm.dom.fromTag(_domEl).find('h3');
        if (!_headlines.length) {
            return true;
        }

        let _links = [];
        for (var i = 0, max = _headlines.length; i < max; i++) {
            
            let _hl = _headlines[i];
            
            _hl.id = _dlgId + '-hl-' + i;
            
            _links.push('<li class="nav-item"><a class="nav-link" href="#' + _hl.id + '">' + _hl.innerText + '</a></li>');            
        }

        fpcm.dom.fromId(_dlgId + '-navbar').find('ul').append(_links.join('')).removeClass('d-none');
        fpcm.dom.fromId(_dlgId + '-navbar').removeClass('d-none');
    },
    
    confirm: function(_params) {

        if (_params.defaultYes === undefined && _params.defaultNo === undefined) {
            _params.defaultYes = true;
        }
        
        if (_params.clickNoDefault === undefined) {
            _params.clickNoDefault = true;
        }

        fpcm.ui_dialogs.create({
            title: 'GLOBAL_CONFIRM',
            content: fpcm.ui.translate('CONFIRM_MESSAGE'),
            size: '',
            dlButtons: [
                {
                    text: 'GLOBAL_YES',
                    icon: "check",
                    click: _params.clickYes,
                    class: 'btn-success',
                    clickClose: true
                },
                {
                    text: 'GLOBAL_NO',
                    icon: "times",
                    click: _params.clickNo,
                    primary: _params.defaultNo ? true : false,
                    class: 'btn-outline-danger',
                    clickClose: _params.clickNoDefault
                }
            ]
        });

    },
    
    insert: function(_params) {

        var _var = {
            id: _params.id,
            title: fpcm.ui.translate(_params.title),
        };

        _var.dlButtons = _params.dlButtons ? _params.dlButtons : [];

        if (_params.fileManagerAction) {
            _var.dlButtons.push({
                text: 'HL_FILES_MNG',
                icon: "folder-open",
                click: _params.fileManagerAction
            });
        }

        if (_params.insertAction) {
            _var.dlButtons.push({
                text: 'GLOBAL_INSERT',
                icon: "check",
                clickClose: true,
                click: _params.insertAction,
                primary: true
            });
        }
         
        if (_params.dlOnOpen) {
            _var.dlOnOpen = _params.dlOnOpen;
        }
        
        if (_params.dlOnClose) {
            _var.dlOnClose = _params.dlOnClose;
        }
        
        _var.closeButton = true;
        
        if (_params.content) {
            _var.content = _params.content;
        }

        fpcm.ui_dialogs.create(_var);
    },
    
    close: function(_id, _parent) {
        
        if (!_id) {
            return false;
        }

        if (!_parent) {
            _parent = false;
        }
        
        var _domEl = false;
        var _bsObj = false;
        
        _id = 'fpcm-dialog-' + _id;
        
        _domEl = _parent ? window.parent.document.getElementById(_id) : document.getElementById(_id);
        
        if (!_domEl) {
            console.warn('Item ' + _id + ' not found!');
            return false;
        }
        
        _bsObj = _parent ? window.parent.bootstrap.Modal.getOrCreateInstance(_domEl) : window.bootstrap.Modal.getOrCreateInstance(_domEl);
        if (!_bsObj) {
            console.warn('Failed to create bootstrap item instance for ' + _id + '!');
            return false;
        }

        _bsObj.toggle(_domEl);
        return true;
    },
    
    initScrollspy: function (_id) {
        var _spylist = [].slice.call(document.querySelectorAll('#' + _id + ' [data-bs-spy="scroll"]'));
        _spylist.forEach(function (_el) {
            bootstrap.ScrollSpy.getOrCreateInstance(_el).refresh();
        });   
    }
    
}