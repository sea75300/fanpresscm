/**
 * FanPress CM texts namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.testing = {

    init: function() {

        var _c = new fpcm.ui.calendar('testing-calendar');
        _c.setDoubleClick(function (_e) {
           
            console.log(_e.target);
            
        });

        _c.render(); 
    },
    
    exec: function (_params) {
        
    },
    
    update: function (_e, _ui) {

        var _c = new fpcm.ui.calendar('testing-calendar');
        _c.setStart(_ui.dataset.month);
        _c.render();   
    }

};