/**
 * FanPress CM system javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.reminders = {

    _lightbox: null,

    set: function (
        _remindertype,
        _rid,
        _oid,
        _uid,
        _dt,
        _comment,
        _callback
    )
    {
        fpcm.ajax.post('reminder/set', {
            quiet: true,
            data: {
                type: _remindertype,
                rid: _rid,
                oid: _oid,
                uid: _uid,
                time: _dt,
                comment: _comment
            },
            execDone: function (_res) {
                
                if (_res.txt) {
                    fpcm.ui.addMessage(_res);
                }
                
                _callback(_res);

            }
        });
        
    },
    
    get: function (_remindertype, _rid, _callback) {

        fpcm.ajax.get('reminder/get', {
            quiet: true,
            data: {
                oid: _rid,
                type: _remindertype
            },
            execDone: function (_res) {

                if (_res.uid === undefined ||
                    _res.dateTime === undefined ||
                    _res.comment === undefined) {
                    return false;
                }
                
                _callback(_res);
            }
        });
    },
    
    delete: function (_remindertype, _rid, _callback) {
        
        fpcm.ajax.post('reminder/delete', {
            quiet: true,
            data: {
                type: _remindertype,
                rid: _rid
            },
            execDone: function (_res) {

                if (_res.txt) {
                    fpcm.ui.addMessage(_res);
                }
                
                _callback(_res);

            }
        });  
        
    }
};