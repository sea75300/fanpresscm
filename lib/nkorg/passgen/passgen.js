/**
 * nkorg Js Chatmap, based on https://github.com/pallant/js-charMap
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.3.0
 */

nkorgPassGen = {

    getPassword: function (_size, _regex) {
        
        var tmpValue = '';

        for (var i = 0; i < _size; i++) {

            tmpValue = nkorgPassGen._generate(_size, _regex);
            if (nkorgPassGen._validate(tmpValue)) {
                break;
            }

        }
        
        return tmpValue;
    },
    
    _generate: function(_size) {

        var randomValues = crypto.getRandomValues(new Int8Array(_size));
        var current = 0;
        var newpass = [];

        for (var i = 0; i < randomValues.length; i++) {

            if (!randomValues[i]) {
                continue;
            }
            
            var current = Math.abs(randomValues[i]);

            if (current < 33) {
                current += 33;
            }
            else if (current > 126) {
                var diff = current - 126;
                current -= diff + Math.floor(Math.random() * 10);
            }

            newpass.push(String.fromCharCode(current));
        }

        return newpass.join('');
    },
    
    _validate: function (_value, _regex) {

        if (!_regex) {
            _regex = /^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
        }

        return (RegExp(_regex)).test(_value);
    }


};