/**
 * FanPress CM Integration assistant Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 5.3.0-dev
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.integration = {

    init: function() {

        fpcm.dom.bindClick('#btnProcess', function () {
            fpcm.integration.processArticles();
            fpcm.integration.processTitle();
        });

    },

    processArticles: function () {

        let _lists = [
            {
                dest: 'functionParams',
                pref: 'article'
            },
            {
                dest: 'functionParamsLatest',
                pref: 'latest'
            }
        ];

        for (let i = 0; i < _lists.length; i++) {

            document.getElementById(_lists[i].dest).innerText = '';

            let data = '[';
            let _prefix = _lists[i].pref;

            let count = document.getElementById(_prefix + 'count');
            if (count !== fpcm.vars.jsvars.articlesDefault) {
                data += "\n";
                data += "   'count' => " + parseInt(count.value) + ",";
            }

            let category = document.getElementById(_prefix + 'category');
            if (category && category.value) {
                data += "\n";
                data += "   'category' => " + parseInt(category.value) + ",";
            }

            let template = document.getElementById(_prefix + 'template');
            if (template && template.value) {
                data += "\n";
                data += "   'template' => '" + template.value + "',";
            }

            if (data.length > 1) {
                data += "\n]";
                document.getElementById(_lists[i].dest).innerText = data;
            }
        }


        return true;
    },

    processTitle: function () {

        let _lists = [
            'titlePages',
            'titleHl'
        ];

        for (let i = 0; i < _lists.length; i++) {

            let prefix = _lists[i];

            document.getElementById('functionParams' + prefix + '1').innerText = '';
            document.getElementById('functionParams' + prefix + '2').innerText = '';

            document.getElementById('functionParams' + prefix + '1').innerText = document.getElementById(prefix + 'delimited').value;
        }


        return true;
    }
};