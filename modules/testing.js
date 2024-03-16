import {calendar} from './calendar/widget.js';

class fpcm_testing {
    
    _c = null;
    
    constructor(_element) {

        this._c = new calendar('testing-calendar');
        this._c.setData(fpcm.vars.jsvars.centries);
        this._c.setDoubleClick(function (_e) {
            console.log(_e.target);
        });

        this._c.setEntryClick(function (_e) {
            console.log(_e.target);
        });

        this._c.render(); 

    }    
    
    update(_e) {
        
        this._c = null;
        this._c = new calendar('testing-calendar');
        this._c.setStart(_e.target.value);
        this._c.setData(fpcm.vars.jsvars.centries);
        
        this._c.setDoubleClick(function (_e) {
            console.log(_e.target);
        });

        this._c.setEntryClick(function (_e) {
            console.log(_e.target);
        });        
        
        this._c.render();   
    }    
    
}

const _cal = new fpcm_testing();

document.getElementById('calMonths').addEventListener('change', function(_e) {
    _cal.update(_e)
});
