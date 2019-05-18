/**
 * FanPress CM Greedy Navigation Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_navigation = {

    _vars: {},

    init: function() {

        fpcm.ui_navigation.initGreedyEl();
        fpcm.ui_navigation.highlightModule();

        fpcm.ui_navigation._vars.btn = jQuery('#btnHiddenMenu');
        fpcm.ui_navigation._vars.greedy = jQuery('div.greedy ul.fpcm-ui-menu:first-child');
        fpcm.ui_navigation._vars.hlinks = jQuery('div.greedy ul.fpcm-ui-nav-hidden-links');

        fpcm.ui_navigation._vars.numOfItems = 0;
        fpcm.ui_navigation._vars.totalSpace = 0;
        fpcm.ui_navigation._vars.breakWidths = [];

        fpcm.ui_navigation._vars.checkcnt = 0;

        fpcm.ui_navigation._vars.greedy.children().width(function(i, w) {
            fpcm.ui_navigation._vars.totalSpace += w;
            fpcm.ui_navigation._vars.numOfItems += 1;
            fpcm.ui_navigation._vars.breakWidths.push(fpcm.ui_navigation._vars.totalSpace);
        });

        jQuery(window).resize(function() {
            fpcm.ui_navigation._checkGreedy();
        });

        fpcm.ui_navigation._vars.btn.click(function() {
            fpcm.ui_navigation._vars.hlinks.toggleClass('fpcm-ui-hidden');
        });

        fpcm.ui_navigation._checkGreedy();

    },
    
    _checkGreedy: function() {

        var availableSpace, numOfVisibleItems, requiredSpace;
        
        fpcm.ui_navigation.initGreedyEl();

        availableSpace = fpcm.ui_navigation._vars.nav.width();
        numOfVisibleItems = fpcm.ui_navigation._vars.greedy.children().length;
        requiredSpace = fpcm.ui_navigation._vars.breakWidths[numOfVisibleItems - 1];

        if (requiredSpace + 200 >= availableSpace) {
            fpcm.ui_navigation._vars.greedy.children().last().prependTo(fpcm.ui_navigation._vars.hlinks);
            numOfVisibleItems -= 1;
            fpcm.ui_navigation._checkGreedy();
        } else if (availableSpace > fpcm.ui_navigation._vars.breakWidths[numOfVisibleItems] + 200) {
            fpcm.ui_navigation._vars.hlinks.children().first().appendTo(fpcm.ui_navigation._vars.greedy);
            numOfVisibleItems += 1;
        }

        // Update the button accordingly
        fpcm.ui_navigation._vars.btn.attr("count", fpcm.ui_navigation._vars.numOfItems - numOfVisibleItems);
        if (numOfVisibleItems === fpcm.ui_navigation._vars.numOfItems) {
            fpcm.ui_navigation._vars.btn.addClass('fpcm-ui-hidden');
            return true;
        }
        
        fpcm.ui_navigation._vars.btn.removeClass('fpcm-ui-hidden');
        if (numOfVisibleItems > 1 && fpcm.ui_navigation._vars.btn.offset().left > 0) {
            fpcm.ui_navigation._vars.hlinks.css('left', fpcm.ui_navigation._vars.btn.offset().left); 
            fpcm.ui_navigation._vars.hlinks.css('top', fpcm.ui_navigation._vars.btn.innerHeight()); 
        }

        return true;
    },
    
    initGreedyEl: function() {

        if (fpcm.ui_navigation._vars.nav) {
            return true;
        }

        fpcm.ui_navigation._vars.nav = jQuery('div.greedy');
        return true;
    },
    
    highlightModule: function() {

        if (fpcm.vars.jsvars.navigationActive) {
            jQuery('#' + fpcm.vars.jsvars.navigationActive).addClass('fpcm-menu-item-active').parents('li.fpcm-menu-level1').addClass('fpcm-menu-item-active');
            return true;
        }

        jQuery('#fpcm-navigation li.fpcm-menu-level2.fpcm-menu-item-active').parents('li.fpcm-menu-level1').addClass('fpcm-menu-item-active');
        return true;

    }

};