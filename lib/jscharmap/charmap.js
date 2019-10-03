/**
 * nkorg Js Chatmap, based on https://github.com/pallant/js-charMap
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.2.1
 */

nkorgJSCharMap = {

    _charList: {
        "CHARS": [
            { entity: "&Agrave;", hex: "&#00C0;", name: "LATIN CAPITAL LETTER A WITH GRAVE", char: "À" }
            ,{ entity: "&Aacute;", hex: "&#00C1;", name: "LATIN CAPITAL LETTER A WITH ACUTE", char: "Á" }
            ,{ entity: "&Acirc;", hex: "&#00C2;", name: "LATIN CAPITAL LETTER A WITH CIRCUMFLEX", char: "Â" }
            ,{ entity: "&Atilde;", hex: "&#00C3;", name: "LATIN CAPITAL LETTER A WITH TILDE", char: "Ã" }
            ,{ entity: "&Auml;", hex: "&#00C4;", name: "LATIN CAPITAL LETTER A WITH DIAERESIS", char: "Ä" }
            ,{ entity: "&Aring;", hex: "&#00C5;", name: "LATIN CAPITAL LETTER A WITH RING ABOVE", char: "Å" }
            ,{ entity: "&AElig;", hex: "&#00C6;", name: "LATIN CAPITAL LETTER AE", char: "Æ" }
            ,{ entity: "&Ccedil;", hex: "&#00C7;", name: "LATIN CAPITAL LETTER C WITH CEDILLA", char: "Ç" }
            ,{ entity: "&Egrave;", hex: "&#00C8;", name: "LATIN CAPITAL LETTER E WITH GRAVE", char: "È" }
            ,{ entity: "&Eacute;", hex: "&#00C9;", name: "LATIN CAPITAL LETTER E WITH ACUTE", char: "É" }
            ,{ entity: "&Ecirc;", hex: "&#00CA;", name: "LATIN CAPITAL LETTER E WITH CIRCUMFLEX", char: "Ê" }
            ,{ entity: "&Euml;", hex: "&#00CB;", name: "LATIN CAPITAL LETTER E WITH DIAERESIS", char: "Ë" }
            ,{ entity: "&Igrave;", hex: "&#00CC;", name: "LATIN CAPITAL LETTER I WITH GRAVE", char: "Ì" }
            ,{ entity: "&Iacute;", hex: "&#00CD;", name: "LATIN CAPITAL LETTER I WITH ACUTE", char: "Í" }
            ,{ entity: "&Icirc;", hex: "&#00CE;", name: "LATIN CAPITAL LETTER I WITH CIRCUMFLEX", char: "Î" }
            ,{ entity: "&Iuml;", hex: "&#00CF;", name: "LATIN CAPITAL LETTER I WITH DIAERESIS", char: "Ï" }
            ,{ entity: "&ETH;", hex: "&#00D0;", name: "LATIN CAPITAL LETTER ETH", char: "Ð" }
            ,{ entity: "&Ntilde;", hex: "&#00D1;", name: "LATIN CAPITAL LETTER N WITH TILDE", char: "Ñ" }
            ,{ entity: "&Ograve;", hex: "&#00D2;", name: "LATIN CAPITAL LETTER O WITH GRAVE", char: "Ò" }
            ,{ entity: "&Oacute;", hex: "&#00D3;", name: "LATIN CAPITAL LETTER O WITH ACUTE", char: "Ó" }
            ,{ entity: "&Ocirc;", hex: "&#00D4;", name: "LATIN CAPITAL LETTER O WITH CIRCUMFLEX", char: "Ô" }
            ,{ entity: "&Otilde;", hex: "&#00D5;", name: "LATIN CAPITAL LETTER O WITH TILDE", char: "Õ" }
            ,{ entity: "&Ouml;", hex: "&#00D6;", name: "LATIN CAPITAL LETTER O WITH DIAERESIS", char: "Ö" }
            ,{ entity: "&times;", hex: "&#00D7;", name: "MULTIPLICATION SIGN", char: "×" }
            ,{ entity: "&Oslash;", hex: "&#00D8;", name: "LATIN CAPITAL LETTER O WITH STROKE", char: "Ø" }
            ,{ entity: "&Ugrave;", hex: "&#00D9;", name: "LATIN CAPITAL LETTER U WITH GRAVE", char: "Ù" }
            ,{ entity: "&Uacute;", hex: "&#00DA;", name: "LATIN CAPITAL LETTER U WITH ACUTE", char: "Ú" }
            ,{ entity: "&Ucirc;", hex: "&#00DB;", name: "LATIN CAPITAL LETTER U WITH CIRCUMFLEX", char: "Û" }
            ,{ entity: "&Uuml;", hex: "&#00DC;", name: "LATIN CAPITAL LETTER U WITH DIAERESIS", char: "Ü" }
            ,{ entity: "&Yacute;", hex: "&#00DD;", name: "LATIN CAPITAL LETTER Y WITH ACUTE", char: "Ý" }
            ,{ entity: "&THORN;", hex: "&#00DE;", name: "LATIN CAPITAL LETTER THORN", char: "Þ" }
            ,{ entity: "&szlig;", hex: "&#00DF;", name: "LATIN SMALL LETTER SHARP S", char: "ß" }
            ,{ entity: "&agrave;", hex: "&#00E0;", name: "LATIN SMALL LETTER A WITH GRAVE", char: "à" }
            ,{ entity: "&aacute;", hex: "&#00E1;", name: "LATIN SMALL LETTER A WITH ACUTE", char: "á" }
            ,{ entity: "&acirc;", hex: "&#00E2;", name: "LATIN SMALL LETTER A WITH CIRCUMFLEX", char: "â" }
            ,{ entity: "&atilde;", hex: "&#00E3;", name: "LATIN SMALL LETTER A WITH TILDE", char: "ã" }
            ,{ entity: "&auml;", hex: "&#00E4;", name: "LATIN SMALL LETTER A WITH DIAERESIS", char: "ä" }
            ,{ entity: "&aring;", hex: "&#00E5;", name: "LATIN SMALL LETTER A WITH RING ABOVE", char: "å" }
            ,{ entity: "&aelig;", hex: "&#00E6;", name: "LATIN SMALL LETTER AE", char: "æ" }
            ,{ entity: "&ccedil;", hex: "&#00E7;", name: "LATIN SMALL LETTER C WITH CEDILLA", char: "ç" }
            ,{ entity: "&egrave;", hex: "&#00E8;", name: "LATIN SMALL LETTER E WITH GRAVE", char: "è" }
            ,{ entity: "&eacute;", hex: "&#00E9;", name: "LATIN SMALL LETTER E WITH ACUTE", char: "é" }
            ,{ entity: "&ecirc;", hex: "&#00EA;", name: "LATIN SMALL LETTER E WITH CIRCUMFLEX", char: "ê" }
            ,{ entity: "&euml;", hex: "&#00EB;", name: "LATIN SMALL LETTER E WITH DIAERESIS", char: "ë" }
            ,{ entity: "&igrave;", hex: "&#00EC;", name: "LATIN SMALL LETTER I WITH GRAVE", char: "ì" }
            ,{ entity: "&iacute;", hex: "&#00ED;", name: "LATIN SMALL LETTER I WITH ACUTE", char: "í" }
            ,{ entity: "&icirc;", hex: "&#00EE;", name: "LATIN SMALL LETTER I WITH CIRCUMFLEX", char: "î" }
            ,{ entity: "&iuml;", hex: "&#00EF;", name: "LATIN SMALL LETTER I WITH DIAERESIS", char: "ï" }
            ,{ entity: "&eth;", hex: "&#00F0;", name: "LATIN SMALL LETTER ETH", char: "ð" }
            ,{ entity: "&ntilde;", hex: "&#00F1;", name: "LATIN SMALL LETTER N WITH TILDE", char: "ñ" }
            ,{ entity: "&ograve;", hex: "&#00F2;", name: "LATIN SMALL LETTER O WITH GRAVE", char: "ò" }
            ,{ entity: "&oacute;", hex: "&#00F3;", name: "LATIN SMALL LETTER O WITH ACUTE", char: "ó" }
            ,{ entity: "&ocirc;", hex: "&#00F4;", name: "LATIN SMALL LETTER O WITH CIRCUMFLEX", char: "ô" }
            ,{ entity: "&otilde;", hex: "&#00F5;", name: "LATIN SMALL LETTER O WITH TILDE", char: "õ" }
            ,{ entity: "&ouml;", hex: "&#00F6;", name: "LATIN SMALL LETTER O WITH DIAERESIS", char: "ö" }
            ,{ entity: "&divide;", hex: "&#00F7;", name: "DIVISION SIGN", char: "÷" }
            ,{ entity: "&oslash;", hex: "&#00F8;", name: "LATIN SMALL LETTER O WITH STROKE", char: "ø" }
            ,{ entity: "&ugrave;", hex: "&#00F9;", name: "LATIN SMALL LETTER U WITH GRAVE", char: "ù" }
            ,{ entity: "&uacute;", hex: "&#00FA;", name: "LATIN SMALL LETTER U WITH ACUTE", char: "ú" }
            ,{ entity: "&ucirc;", hex: "&#00FB;", name: "LATIN SMALL LETTER U WITH CIRCUMFLEX", char: "û" }
            ,{ entity: "&uuml;", hex: "&#00FC;", name: "LATIN SMALL LETTER U WITH DIAERESIS", char: "ü" }
            ,{ entity: "&yacute;", hex: "&#00FD;", name: "LATIN SMALL LETTER Y WITH ACUTE", char: "ý" }
            ,{ entity: "&thorn;", hex: "&#00FE;", name: "LATIN SMALL LETTER THORN", char: "þ" }
            ,{ entity: "&yuml;", hex: "&#00FF;", name: "LATIN SMALL LETTER Y WITH DIAERESIS", char: "ÿ" }

            ,{ hex: "&#0100;", name: "LATIN CAPITAL LETTER A WITH MACRON", char: "Ā" }
            ,{ hex: "&#0101;", name: "LATIN SMALL LETTER A WITH MACRON", char: "ā" }
            ,{ hex: "&#0102;", name: "LATIN CAPITAL LETTER A WITH BREVE", char: "Ă" }
            ,{ hex: "&#0103;", name: "LATIN SMALL LETTER A WITH BREVE", char: "ă" }
            ,{ hex: "&#0104;", name: "LATIN CAPITAL LETTER A WITH OGONEK", char: "Ą" }
            ,{ hex: "&#0105;", name: "LATIN SMALL LETTER A WITH OGONEK", char: "ą" }
            ,{ hex: "&#0106;", name: "LATIN CAPITAL LETTER C WITH ACUTE", char: "Ć" }
            ,{ hex: "&#0107;", name: "LATIN SMALL LETTER C WITH ACUTE", char: "ć" }
            ,{ hex: "&#0108;", name: "LATIN CAPITAL LETTER C WITH CIRCUMFLEX", char: "Ĉ" }
            ,{ hex: "&#0109;", name: "LATIN SMALL LETTER C WITH CIRCUMFLEX", char: "ĉ" }
            ,{ hex: "&#010A;", name: "LATIN CAPITAL LETTER C WITH DOT ABOVE", char: "Ċ" }
            ,{ hex: "&#010B;", name: "LATIN SMALL LETTER C WITH DOT ABOVE", char: "ċ" }
            ,{ hex: "&#010C;", name: "LATIN CAPITAL LETTER C WITH CARON", char: "Č" }
            ,{ hex: "&#010D;", name: "LATIN SMALL LETTER C WITH CARON", char: "č" }
            ,{ hex: "&#010E;", name: "LATIN CAPITAL LETTER D WITH CARON", char: "Ď" }
            ,{ hex: "&#010F;", name: "LATIN SMALL LETTER D WITH CARON", char: "ď" }
            ,{ hex: "&#0110;", name: "LATIN CAPITAL LETTER D WITH STROKE", char: "Đ" }
            ,{ hex: "&#0111;", name: "LATIN SMALL LETTER D WITH STROKE", char: "đ" }
            ,{ hex: "&#0112;", name: "LATIN CAPITAL LETTER E WITH MACRON", char: "Ē" }
            ,{ hex: "&#0113;", name: "LATIN SMALL LETTER E WITH MACRON", char: "ē" }
            ,{ hex: "&#0114;", name: "LATIN CAPITAL LETTER E WITH BREVE", char: "Ĕ" }
            ,{ hex: "&#0115;", name: "LATIN SMALL LETTER E WITH BREVE", char: "ĕ" }
            ,{ hex: "&#0116;", name: "LATIN CAPITAL LETTER E WITH DOT ABOVE", char: "Ė" }
            ,{ hex: "&#0117;", name: "LATIN SMALL LETTER E WITH DOT ABOVE", char: "ė" }
            ,{ hex: "&#0118;", name: "LATIN CAPITAL LETTER E WITH OGONEK", char: "Ę" }
            ,{ hex: "&#0119;", name: "LATIN SMALL LETTER E WITH OGONEK", char: "ę" }
            ,{ hex: "&#011A;", name: "LATIN CAPITAL LETTER E WITH CARON", char: "Ě" }
            ,{ hex: "&#011B;", name: "LATIN SMALL LETTER E WITH CARON", char: "ě" }
            ,{ hex: "&#011C;", name: "LATIN CAPITAL LETTER G WITH CIRCUMFLEX", char: "Ĝ" }
            ,{ hex: "&#011D;", name: "LATIN SMALL LETTER G WITH CIRCUMFLEX", char: "ĝ" }
            ,{ hex: "&#011E;", name: "LATIN CAPITAL LETTER G WITH BREVE", char: "Ğ" }
            ,{ hex: "&#011F;", name: "LATIN SMALL LETTER G WITH BREVE", char: "ğ" }
            ,{ hex: "&#0120;", name: "LATIN CAPITAL LETTER G WITH DOT ABOVE", char: "Ġ" }
            ,{ hex: "&#0121;", name: "LATIN SMALL LETTER G WITH DOT ABOVE", char: "ġ" }
            ,{ hex: "&#0122;", name: "LATIN CAPITAL LETTER G WITH CEDILLA", char: "Ģ" }
            ,{ hex: "&#0123;", name: "LATIN SMALL LETTER G WITH CEDILLA", char: "ģ" }
            ,{ hex: "&#0124;", name: "LATIN CAPITAL LETTER H WITH CIRCUMFLEX", char: "Ĥ" }
            ,{ hex: "&#0125;", name: "LATIN SMALL LETTER H WITH CIRCUMFLEX", char: "ĥ" }
            ,{ hex: "&#0126;", name: "LATIN CAPITAL LETTER H WITH STROKE", char: "Ħ" }
            ,{ hex: "&#0127;", name: "LATIN SMALL LETTER H WITH STROKE", char: "ħ" }
            ,{ hex: "&#0128;", name: "LATIN CAPITAL LETTER I WITH TILDE", char: "Ĩ" }
            ,{ hex: "&#0129;", name: "LATIN SMALL LETTER I WITH TILDE", char: "ĩ" }
            ,{ hex: "&#012A;", name: "LATIN CAPITAL LETTER I WITH MACRON", char: "Ī" }
            ,{ hex: "&#012B;", name: "LATIN SMALL LETTER I WITH MACRON", char: "ī" }
            ,{ hex: "&#012C;", name: "LATIN CAPITAL LETTER I WITH BREVE", char: "Ĭ" }
            ,{ hex: "&#012D;", name: "LATIN SMALL LETTER I WITH BREVE", char: "ĭ" }
            ,{ hex: "&#012E;", name: "LATIN CAPITAL LETTER I WITH OGONEK", char: "Į" }
            ,{ hex: "&#012F;", name: "LATIN SMALL LETTER I WITH OGONEK", char: "į" }
            ,{ hex: "&#0130;", name: "LATIN CAPITAL LETTER I WITH DOT ABOVE", char: "İ" }
            ,{ hex: "&#0131;", name: "LATIN SMALL LETTER DOTLESS I", char: "ı" }
            ,{ hex: "&#0132;", name: "LATIN CAPITAL LIGATURE IJ", char: "Ĳ" }
            ,{ hex: "&#0133;", name: "LATIN SMALL LIGATURE IJ", char: "ĳ" }
            ,{ hex: "&#0134;", name: "LATIN CAPITAL LETTER J WITH CIRCUMFLEX", char: "Ĵ" }
            ,{ hex: "&#0135;", name: "LATIN SMALL LETTER J WITH CIRCUMFLEX", char: "ĵ" }
            ,{ hex: "&#0136;", name: "LATIN CAPITAL LETTER K WITH CEDILLA", char: "Ķ" }
            ,{ hex: "&#0137;", name: "LATIN SMALL LETTER K WITH CEDILLA", char: "ķ" }
            ,{ hex: "&#0138;", name: "LATIN SMALL LETTER KRA", char: "ĸ" }
            ,{ hex: "&#0139;", name: "LATIN CAPITAL LETTER L WITH ACUTE", char: "Ĺ" }
            ,{ hex: "&#013A;", name: "LATIN SMALL LETTER L WITH ACUTE", char: "ĺ" }
            ,{ hex: "&#013B;", name: "LATIN CAPITAL LETTER L WITH CEDILLA", char: "Ļ" }
            ,{ hex: "&#013C;", name: "LATIN SMALL LETTER L WITH CEDILLA", char: "ļ" }
            ,{ hex: "&#013D;", name: "LATIN CAPITAL LETTER L WITH CARON", char: "Ľ" }
            ,{ hex: "&#013E;", name: "LATIN SMALL LETTER L WITH CARON", char: "ľ" }
            ,{ hex: "&#013F;", name: "LATIN CAPITAL LETTER L WITH MIDDLE DOT", char: "Ŀ" }
            ,{ hex: "&#0140;", name: "LATIN SMALL LETTER L WITH MIDDLE DOT", char: "ŀ" }
            ,{ hex: "&#0141;", name: "LATIN CAPITAL LETTER L WITH STROKE", char: "Ł" }
            ,{ hex: "&#0142;", name: "LATIN SMALL LETTER L WITH STROKE", char: "ł" }
            ,{ hex: "&#0143;", name: "LATIN CAPITAL LETTER N WITH ACUTE", char: "Ń" }
            ,{ hex: "&#0144;", name: "LATIN SMALL LETTER N WITH ACUTE", char: "ń" }
            ,{ hex: "&#0145;", name: "LATIN CAPITAL LETTER N WITH CEDILLA", char: "Ņ" }
            ,{ hex: "&#0146;", name: "LATIN SMALL LETTER N WITH CEDILLA", char: "ņ" }
            ,{ hex: "&#0147;", name: "LATIN CAPITAL LETTER N WITH CARON", char: "Ň" }
            ,{ hex: "&#0148;", name: "LATIN SMALL LETTER N WITH CARON", char: "ň" }
            ,{ hex: "&#0149;", name: "LATIN SMALL LETTER N PRECEDED BY APOSTROPHE", char: "ŉ" }
            ,{ hex: "&#014A;", name: "LATIN CAPITAL LETTER ENG", char: "Ŋ" }
            ,{ hex: "&#014B;", name: "LATIN SMALL LETTER ENG", char: "ŋ" }
            ,{ hex: "&#014C;", name: "LATIN CAPITAL LETTER O WITH MACRON", char: "Ō" }
            ,{ hex: "&#014D;", name: "LATIN SMALL LETTER O WITH MACRON", char: "ō" }
            ,{ hex: "&#014E;", name: "LATIN CAPITAL LETTER O WITH BREVE", char: "Ŏ" }
            ,{ hex: "&#014F;", name: "LATIN SMALL LETTER O WITH BREVE", char: "ŏ" }
            ,{ hex: "&#0150;", name: "LATIN CAPITAL LETTER O WITH DOUBLE ACUTE", char: "Ő" }
            ,{ hex: "&#0151;", name: "LATIN SMALL LETTER O WITH DOUBLE ACUTE", char: "ő" }
            ,{ entity: "&OElig;", hex: "&#0152;", name: "LATIN CAPITAL LIGATURE OE", char: "Œ" }
            ,{ entity: "&oelig;", hex: "&#0153;", name: "LATIN SMALL LIGATURE OE", char: "œ" }
            ,{ hex: "&#0154;", name: "LATIN CAPITAL LETTER R WITH ACUTE", char: "Ŕ" }
            ,{ hex: "&#0155;", name: "LATIN SMALL LETTER R WITH ACUTE", char: "ŕ" }
            ,{ hex: "&#0156;", name: "LATIN CAPITAL LETTER R WITH CEDILLA", char: "Ŗ" }
            ,{ hex: "&#0157;", name: "LATIN SMALL LETTER R WITH CEDILLA", char: "ŗ" }
            ,{ hex: "&#0158;", name: "LATIN CAPITAL LETTER R WITH CARON", char: "Ř" }
            ,{ hex: "&#0159;", name: "LATIN SMALL LETTER R WITH CARON", char: "ř" }
            ,{ hex: "&#015A;", name: "LATIN CAPITAL LETTER S WITH ACUTE", char: "Ś" }
            ,{ hex: "&#015B;", name: "LATIN SMALL LETTER S WITH ACUTE", char: "ś" }
            ,{ hex: "&#015C;", name: "LATIN CAPITAL LETTER S WITH CIRCUMFLEX", char: "Ŝ" }
            ,{ hex: "&#015D;", name: "LATIN SMALL LETTER S WITH CIRCUMFLEX", char: "ŝ" }
            ,{ hex: "&#015E;", name: "LATIN CAPITAL LETTER S WITH CEDILLA", char: "Ş" }
            ,{ hex: "&#015F;", name: "LATIN SMALL LETTER S WITH CEDILLA", char: "ş" }
            ,{ entity: "&Scaron;", hex: "&#0160;", name: "LATIN CAPITAL LETTER S WITH CARON", char: "Š" }
            ,{ entity: "&scaron;", hex: "&#0161;", name: "LATIN SMALL LETTER S WITH CARON", char: "š" }
            ,{ hex: "&#0162;", name: "LATIN CAPITAL LETTER T WITH CEDILLA", char: "Ţ" }
            ,{ hex: "&#0163;", name: "LATIN SMALL LETTER T WITH CEDILLA", char: "ţ" }
            ,{ hex: "&#0164;", name: "LATIN CAPITAL LETTER T WITH CARON", char: "Ť" }
            ,{ hex: "&#0165;", name: "LATIN SMALL LETTER T WITH CARON", char: "ť" }
            ,{ hex: "&#0166;", name: "LATIN CAPITAL LETTER T WITH STROKE", char: "Ŧ" }
            ,{ hex: "&#0167;", name: "LATIN SMALL LETTER T WITH STROKE", char: "ŧ" }
            ,{ hex: "&#0168;", name: "LATIN CAPITAL LETTER U WITH TILDE", char: "Ũ" }
            ,{ hex: "&#0169;", name: "LATIN SMALL LETTER U WITH TILDE", char: "ũ" }
            ,{ hex: "&#016A;", name: "LATIN CAPITAL LETTER U WITH MACRON", char: "Ū" }
            ,{ hex: "&#016B;", name: "LATIN SMALL LETTER U WITH MACRON", char: "ū" }
            ,{ hex: "&#016C;", name: "LATIN CAPITAL LETTER U WITH BREVE", char: "Ŭ" }
            ,{ hex: "&#016D;", name: "LATIN SMALL LETTER U WITH BREVE", char: "ŭ" }
            ,{ hex: "&#016E;", name: "LATIN CAPITAL LETTER U WITH RING ABOVE", char: "Ů" }
            ,{ hex: "&#016F;", name: "LATIN SMALL LETTER U WITH RING ABOVE", char: "ů" }
            ,{ hex: "&#0170;", name: "LATIN CAPITAL LETTER U WITH DOUBLE ACUTE", char: "Ű" }
            ,{ hex: "&#0171;", name: "LATIN SMALL LETTER U WITH DOUBLE ACUTE", char: "ű" }
            ,{ hex: "&#0172;", name: "LATIN CAPITAL LETTER U WITH OGONEK", char: "Ų" }
            ,{ hex: "&#0173;", name: "LATIN SMALL LETTER U WITH OGONEK", char: "ų" }
            ,{ hex: "&#0174;", name: "LATIN CAPITAL LETTER W WITH CIRCUMFLEX", char: "Ŵ" }
            ,{ hex: "&#0175;", name: "LATIN SMALL LETTER W WITH CIRCUMFLEX", char: "ŵ" }
            ,{ hex: "&#0176;", name: "LATIN CAPITAL LETTER Y WITH CIRCUMFLEX", char: "Ŷ" }
            ,{ hex: "&#0177;", name: "LATIN SMALL LETTER Y WITH CIRCUMFLEX", char: "ŷ" }
            ,{ entity: "&Yuml;", hex: "&#0178;", name: "LATIN CAPITAL LETTER Y WITH DIAERESIS", char: "Ÿ" }
            ,{ hex: "&#0179;", name: "LATIN CAPITAL LETTER Z WITH ACUTE", char: "Ź" }
            ,{ hex: "&#017A;", name: "LATIN SMALL LETTER Z WITH ACUTE", char: "ź" }
            ,{ hex: "&#017B;", name: "LATIN CAPITAL LETTER Z WITH DOT ABOVE", char: "Ż" }
            ,{ hex: "&#017C;", name: "LATIN SMALL LETTER Z WITH DOT ABOVE", char: "ż" }
            ,{ hex: "&#017D;", name: "LATIN CAPITAL LETTER Z WITH CARON", char: "Ž" }
            ,{ hex: "&#017E;", name: "LATIN SMALL LETTER Z WITH CARON", char: "ž" }
            ,{ hex: "&#017F;", name: "LATIN SMALL LETTER LONG S", char: "ſ" }


            ,{ hex: "&#0180;", name: "LATIN SMALL LETTER B WITH STROKE", char: "ƀ" }
            ,{ hex: "&#0181;", name: "LATIN CAPITAL LETTER B WITH HOOK", char: "Ɓ" }
            ,{ hex: "&#0182;", name: "LATIN CAPITAL LETTER B WITH TOPBAR", char: "Ƃ" }
            ,{ hex: "&#0183;", name: "LATIN SMALL LETTER B WITH TOPBAR", char: "ƃ" }
            ,{ hex: "&#0184;", name: "LATIN CAPITAL LETTER TONE SIX", char: "Ƅ" }
            ,{ hex: "&#0185;", name: "LATIN SMALL LETTER TONE SIX", char: "ƅ" }
            ,{ hex: "&#0186;", name: "LATIN CAPITAL LETTER OPEN O", char: "Ɔ" }
            ,{ hex: "&#0187;", name: "LATIN CAPITAL LETTER C WITH HOOK", char: "Ƈ" }
            ,{ hex: "&#0188;", name: "LATIN SMALL LETTER C WITH HOOK", char: "ƈ" }
            ,{ hex: "&#0189;", name: "LATIN CAPITAL LETTER AFRICAN D", char: "Ɖ" }
            ,{ hex: "&#018A;", name: "LATIN CAPITAL LETTER D WITH HOOK", char: "Ɗ" }
            ,{ hex: "&#018B;", name: "LATIN CAPITAL LETTER D WITH TOPBAR", char: "Ƌ" }
            ,{ hex: "&#018C;", name: "LATIN SMALL LETTER D WITH TOPBAR", char: "ƌ" }
            ,{ hex: "&#018D;", name: "LATIN SMALL LETTER TURNED DELTA", char: "ƍ" }
            ,{ hex: "&#018E;", name: "LATIN CAPITAL LETTER REVERSED E", char: "Ǝ" }
            ,{ hex: "&#018F;", name: "LATIN CAPITAL LETTER SCHWA", char: "Ə" }
            ,{ hex: "&#0190;", name: "LATIN CAPITAL LETTER OPEN E", char: "Ɛ" }
            ,{ hex: "&#0191;", name: "LATIN CAPITAL LETTER F WITH HOOK", char: "Ƒ" }
            ,{ entity: "&fnof;", hex: "&#0192;", name: "LATIN SMALL LETTER F WITH HOOK", char: "ƒ" }
            ,{ hex: "&#0193;", name: "LATIN CAPITAL LETTER G WITH HOOK", char: "Ɠ" }
            ,{ hex: "&#0194;", name: "LATIN CAPITAL LETTER GAMMA", char: "Ɣ" }
            ,{ hex: "&#0195;", name: "LATIN SMALL LETTER HV", char: "ƕ" }
            ,{ hex: "&#0196;", name: "LATIN CAPITAL LETTER IOTA", char: "Ɩ" }
            ,{ hex: "&#0197;", name: "LATIN CAPITAL LETTER I WITH STROKE", char: "Ɨ" }
            ,{ hex: "&#0198;", name: "LATIN CAPITAL LETTER K WITH HOOK", char: "Ƙ" }
            ,{ hex: "&#0199;", name: "LATIN SMALL LETTER K WITH HOOK", char: "ƙ" }
            ,{ hex: "&#019A;", name: "LATIN SMALL LETTER L WITH BAR", char: "ƚ" }
            ,{ hex: "&#019B;", name: "LATIN SMALL LETTER LAMBDA WITH STROKE", char: "ƛ" }
            ,{ hex: "&#019C;", name: "LATIN CAPITAL LETTER TURNED M", char: "Ɯ" }
            ,{ hex: "&#019D;", name: "LATIN CAPITAL LETTER N WITH LEFT HOOK", char: "Ɲ" }
            ,{ hex: "&#019E;", name: "LATIN SMALL LETTER N WITH LONG RIGHT LEG", char: "ƞ" }
            ,{ hex: "&#019F;", name: "LATIN CAPITAL LETTER O WITH MIDDLE TILDE", char: "Ɵ" }
            ,{ hex: "&#01A0;", name: "LATIN CAPITAL LETTER O WITH HORN", char: "Ơ" }
            ,{ hex: "&#01A1;", name: "LATIN SMALL LETTER O WITH HORN", char: "ơ" }
            ,{ hex: "&#01A2;", name: "LATIN CAPITAL LETTER OI", char: "Ƣ" }
            ,{ hex: "&#01A3;", name: "LATIN SMALL LETTER OI", char: "ƣ" }
            ,{ hex: "&#01A4;", name: "LATIN CAPITAL LETTER P WITH HOOK", char: "Ƥ" }
            ,{ hex: "&#01A5;", name: "LATIN SMALL LETTER P WITH HOOK", char: "ƥ" }
            ,{ hex: "&#01A6;", name: "LATIN LETTER YR", char: "Ʀ" }
            ,{ hex: "&#01A7;", name: "LATIN CAPITAL LETTER TONE TWO", char: "Ƨ" }
            ,{ hex: "&#01A8;", name: "LATIN SMALL LETTER TONE TWO", char: "ƨ" }
            ,{ hex: "&#01A9;", name: "LATIN CAPITAL LETTER ESH", char: "Ʃ" }
            ,{ hex: "&#01AA;", name: "LATIN LETTER REVERSED ESH LOOP", char: "ƪ" }
            ,{ hex: "&#01AB;", name: "LATIN SMALL LETTER T WITH PALATAL HOOK", char: "ƫ" }
            ,{ hex: "&#01AC;", name: "LATIN CAPITAL LETTER T WITH HOOK", char: "Ƭ" }
            ,{ hex: "&#01AD;", name: "LATIN SMALL LETTER T WITH HOOK", char: "ƭ" }
            ,{ hex: "&#01AE;", name: "LATIN CAPITAL LETTER T WITH RETROFLEX HOOK", char: "Ʈ" }
            ,{ hex: "&#01AF;", name: "LATIN CAPITAL LETTER U WITH HORN", char: "Ư" }
            ,{ hex: "&#01B0;", name: "LATIN SMALL LETTER U WITH HORN", char: "ư" }
            ,{ hex: "&#01B1;", name: "LATIN CAPITAL LETTER UPSILON", char: "Ʊ" }
            ,{ hex: "&#01B2;", name: "LATIN CAPITAL LETTER V WITH HOOK", char: "Ʋ" }
            ,{ hex: "&#01B3;", name: "LATIN CAPITAL LETTER Y WITH HOOK", char: "Ƴ" }
            ,{ hex: "&#01B4;", name: "LATIN SMALL LETTER Y WITH HOOK", char: "ƴ" }
            ,{ hex: "&#01B5;", name: "LATIN CAPITAL LETTER Z WITH STROKE", char: "Ƶ" }
            ,{ hex: "&#01B6;", name: "LATIN SMALL LETTER Z WITH STROKE", char: "ƶ" }
            ,{ hex: "&#01B7;", name: "LATIN CAPITAL LETTER EZH", char: "Ʒ" }
            ,{ hex: "&#01B8;", name: "LATIN CAPITAL LETTER EZH REVERSED", char: "Ƹ" }
            ,{ hex: "&#01B9;", name: "LATIN SMALL LETTER EZH REVERSED", char: "ƹ" }
            ,{ hex: "&#01BA;", name: "LATIN SMALL LETTER EZH WITH TAIL", char: "ƺ" }
            ,{ hex: "&#01BB;", name: "LATIN LETTER TWO WITH STROKE", char: "ƻ" }
            ,{ hex: "&#01BC;", name: "LATIN CAPITAL LETTER TONE FIVE", char: "Ƽ" }
            ,{ hex: "&#01BD;", name: "LATIN SMALL LETTER TONE FIVE", char: "ƽ" }
            ,{ hex: "&#01BE;", name: "LATIN LETTER INVERTED GLOTTAL STOP WITH STROKE", char: "ƾ" }
            ,{ hex: "&#01BF;", name: "LATIN LETTER WYNN", char: "ƿ" }
            ,{ hex: "&#01C0;", name: "LATIN LETTER DENTAL CLICK", char: "ǀ" }
            ,{ hex: "&#01C1;", name: "LATIN LETTER LATERAL CLICK", char: "ǁ" }
            ,{ hex: "&#01C2;", name: "LATIN LETTER ALVEOLAR CLICK", char: "ǂ" }
            ,{ hex: "&#01C3;", name: "LATIN LETTER RETROFLEX CLICK", char: "ǃ" }
            ,{ hex: "&#01C4;", name: "LATIN CAPITAL LETTER DZ WITH CARON", char: "Ǆ" }
            ,{ hex: "&#01C5;", name: "LATIN CAPITAL LETTER D WITH SMALL LETTER Z WITH CARON", char: "ǅ" }
            ,{ hex: "&#01C6;", name: "LATIN SMALL LETTER DZ WITH CARON", char: "ǆ" }
            ,{ hex: "&#01C7;", name: "LATIN CAPITAL LETTER LJ", char: "Ǉ" }
            ,{ hex: "&#01C8;", name: "LATIN CAPITAL LETTER L WITH SMALL LETTER J", char: "ǈ" }
            ,{ hex: "&#01C9;", name: "LATIN SMALL LETTER LJ", char: "ǉ" }
            ,{ hex: "&#01CA;", name: "LATIN CAPITAL LETTER NJ", char: "Ǌ" }
            ,{ hex: "&#01CB;", name: "LATIN CAPITAL LETTER N WITH SMALL LETTER J", char: "ǋ" }
            ,{ hex: "&#01CC;", name: "LATIN SMALL LETTER NJ", char: "ǌ" }
            ,{ hex: "&#01CD;", name: "LATIN CAPITAL LETTER A WITH CARON", char: "Ǎ" }
            ,{ hex: "&#01CE;", name: "LATIN SMALL LETTER A WITH CARON", char: "ǎ" }
            ,{ hex: "&#01CF;", name: "LATIN CAPITAL LETTER I WITH CARON", char: "Ǐ" }
            ,{ hex: "&#01D0;", name: "LATIN SMALL LETTER I WITH CARON", char: "ǐ" }
            ,{ hex: "&#01D1;", name: "LATIN CAPITAL LETTER O WITH CARON", char: "Ǒ" }
            ,{ hex: "&#01D2;", name: "LATIN SMALL LETTER O WITH CARON", char: "ǒ" }
            ,{ hex: "&#01D3;", name: "LATIN CAPITAL LETTER U WITH CARON", char: "Ǔ" }
            ,{ hex: "&#01D4;", name: "LATIN SMALL LETTER U WITH CARON", char: "ǔ" }
            ,{ hex: "&#01D5;", name: "LATIN CAPITAL LETTER U WITH DIAERESIS AND MACRON", char: "Ǖ" }
            ,{ hex: "&#01D6;", name: "LATIN SMALL LETTER U WITH DIAERESIS AND MACRON", char: "ǖ" }
            ,{ hex: "&#01D7;", name: "LATIN CAPITAL LETTER U WITH DIAERESIS AND ACUTE", char: "Ǘ" }
            ,{ hex: "&#01D8;", name: "LATIN SMALL LETTER U WITH DIAERESIS AND ACUTE", char: "ǘ" }
            ,{ hex: "&#01D9;", name: "LATIN CAPITAL LETTER U WITH DIAERESIS AND CARON", char: "Ǚ" }
            ,{ hex: "&#01DA;", name: "LATIN SMALL LETTER U WITH DIAERESIS AND CARON", char: "ǚ" }
            ,{ hex: "&#01DB;", name: "LATIN CAPITAL LETTER U WITH DIAERESIS AND GRAVE", char: "Ǜ" }
            ,{ hex: "&#01DC;", name: "LATIN SMALL LETTER U WITH DIAERESIS AND GRAVE", char: "ǜ" }
            ,{ hex: "&#01DD;", name: "LATIN SMALL LETTER TURNED E", char: "ǝ" }
            ,{ hex: "&#01DE;", name: "LATIN CAPITAL LETTER A WITH DIAERESIS AND MACRON", char: "Ǟ" }
            ,{ hex: "&#01DF;", name: "LATIN SMALL LETTER A WITH DIAERESIS AND MACRON", char: "ǟ" }
            ,{ hex: "&#01E0;", name: "LATIN CAPITAL LETTER A WITH DOT ABOVE AND MACRON", char: "Ǡ" }
            ,{ hex: "&#01E1;", name: "LATIN SMALL LETTER A WITH DOT ABOVE AND MACRON", char: "ǡ" }
            ,{ hex: "&#01E2;", name: "LATIN CAPITAL LETTER AE WITH MACRON", char: "Ǣ" }
            ,{ hex: "&#01E3;", name: "LATIN SMALL LETTER AE WITH MACRON", char: "ǣ" }
            ,{ hex: "&#01E4;", name: "LATIN CAPITAL LETTER G WITH STROKE", char: "Ǥ" }
            ,{ hex: "&#01E5;", name: "LATIN SMALL LETTER G WITH STROKE", char: "ǥ" }
            ,{ hex: "&#01E6;", name: "LATIN CAPITAL LETTER G WITH CARON", char: "Ǧ" }
            ,{ hex: "&#01E7;", name: "LATIN SMALL LETTER G WITH CARON", char: "ǧ" }
            ,{ hex: "&#01E8;", name: "LATIN CAPITAL LETTER K WITH CARON", char: "Ǩ" }
            ,{ hex: "&#01E9;", name: "LATIN SMALL LETTER K WITH CARON", char: "ǩ" }
            ,{ hex: "&#01EA;", name: "LATIN CAPITAL LETTER O WITH OGONEK", char: "Ǫ" }
            ,{ hex: "&#01EB;", name: "LATIN SMALL LETTER O WITH OGONEK", char: "ǫ" }
            ,{ hex: "&#01EC;", name: "LATIN CAPITAL LETTER O WITH OGONEK AND MACRON", char: "Ǭ" }
            ,{ hex: "&#01ED;", name: "LATIN SMALL LETTER O WITH OGONEK AND MACRON", char: "ǭ" }
            ,{ hex: "&#01EE;", name: "LATIN CAPITAL LETTER EZH WITH CARON", char: "Ǯ" }
            ,{ hex: "&#01EF;", name: "LATIN SMALL LETTER EZH WITH CARON", char: "ǯ" }
            ,{ hex: "&#01F0;", name: "LATIN SMALL LETTER J WITH CARON", char: "ǰ" }
            ,{ hex: "&#01F1;", name: "LATIN CAPITAL LETTER DZ", char: "Ǳ" }
            ,{ hex: "&#01F2;", name: "LATIN CAPITAL LETTER D WITH SMALL LETTER Z", char: "ǲ" }
            ,{ hex: "&#01F3;", name: "LATIN SMALL LETTER DZ", char: "ǳ" }
            ,{ hex: "&#01F4;", name: "LATIN CAPITAL LETTER G WITH ACUTE", char: "Ǵ" }
            ,{ hex: "&#01F5;", name: "LATIN SMALL LETTER G WITH ACUTE", char: "ǵ" }
            ,{ hex: "&#01F6;", name: "LATIN CAPITAL LETTER HWAIR", char: "Ƕ" }
            ,{ hex: "&#01F7;", name: "LATIN CAPITAL LETTER WYNN", char: "Ƿ" }
            ,{ hex: "&#01F8;", name: "LATIN CAPITAL LETTER N WITH GRAVE", char: "Ǹ" }
            ,{ hex: "&#01F9;", name: "LATIN SMALL LETTER N WITH GRAVE", char: "ǹ" }
            ,{ hex: "&#01FA;", name: "LATIN CAPITAL LETTER A WITH RING ABOVE AND ACUTE (present in WGL4)", char: "Ǻ" }
            ,{ hex: "&#01FB;", name: "LATIN SMALL LETTER A WITH RING ABOVE AND ACUTE (present in WGL4)", char: "ǻ" }
            ,{ hex: "&#01FC;", name: "LATIN CAPITAL LETTER AE WITH ACUTE (present in WGL4)", char: "Ǽ" }
            ,{ hex: "&#01FD;", name: "LATIN SMALL LETTER AE WITH ACUTE (present in WGL4)", char: "ǽ" }
            ,{ hex: "&#01FE;", name: "LATIN CAPITAL LETTER O WITH STROKE AND ACUTE (present in WGL4)", char: "Ǿ" }
            ,{ hex: "&#01FF;", name: "LATIN SMALL LETTER O WITH STROKE AND ACUTE (present in WGL4)", char: "ǿ" }
            ,{ hex: "&#0200;", name: "LATIN CAPITAL LETTER A WITH DOUBLE GRAVE", char: "Ȁ" }
            ,{ hex: "&#0201;", name: "LATIN SMALL LETTER A WITH DOUBLE GRAVE", char: "ȁ" }
            ,{ hex: "&#0202;", name: "LATIN CAPITAL LETTER A WITH INVERTED BREVE", char: "Ȃ" }
            ,{ hex: "&#0203;", name: "LATIN SMALL LETTER A WITH INVERTED BREVE", char: "ȃ" }
            ,{ hex: "&#0204;", name: "LATIN CAPITAL LETTER E WITH DOUBLE GRAVE", char: "Ȅ" }
            ,{ hex: "&#0205;", name: "LATIN SMALL LETTER E WITH DOUBLE GRAVE", char: "ȅ" }
            ,{ hex: "&#0206;", name: "LATIN CAPITAL LETTER E WITH INVERTED BREVE", char: "Ȇ" }
            ,{ hex: "&#0207;", name: "LATIN SMALL LETTER E WITH INVERTED BREVE", char: "ȇ" }
            ,{ hex: "&#0208;", name: "LATIN CAPITAL LETTER I WITH DOUBLE GRAVE", char: "Ȉ" }
            ,{ hex: "&#0209;", name: "LATIN SMALL LETTER I WITH DOUBLE GRAVE", char: "ȉ" }
            ,{ hex: "&#020A;", name: "LATIN CAPITAL LETTER I WITH INVERTED BREVE", char: "Ȋ" }
            ,{ hex: "&#020B;", name: "LATIN SMALL LETTER I WITH INVERTED BREVE", char: "ȋ" }
            ,{ hex: "&#020C;", name: "LATIN CAPITAL LETTER O WITH DOUBLE GRAVE", char: "Ȍ" }
            ,{ hex: "&#020D;", name: "LATIN SMALL LETTER O WITH DOUBLE GRAVE", char: "ȍ" }
            ,{ hex: "&#020E;", name: "LATIN CAPITAL LETTER O WITH INVERTED BREVE", char: "Ȏ" }
            ,{ hex: "&#020F;", name: "LATIN SMALL LETTER O WITH INVERTED BREVE", char: "ȏ" }
            ,{ hex: "&#0210;", name: "LATIN CAPITAL LETTER R WITH DOUBLE GRAVE", char: "Ȑ" }
            ,{ hex: "&#0211;", name: "LATIN SMALL LETTER R WITH DOUBLE GRAVE", char: "ȑ" }
            ,{ hex: "&#0212;", name: "LATIN CAPITAL LETTER R WITH INVERTED BREVE", char: "Ȓ" }
            ,{ hex: "&#0213;", name: "LATIN SMALL LETTER R WITH INVERTED BREVE", char: "ȓ" }
            ,{ hex: "&#0214;", name: "LATIN CAPITAL LETTER U WITH DOUBLE GRAVE", char: "Ȕ" }
            ,{ hex: "&#0215;", name: "LATIN SMALL LETTER U WITH DOUBLE GRAVE", char: "ȕ" }
            ,{ hex: "&#0216;", name: "LATIN CAPITAL LETTER U WITH INVERTED BREVE", char: "Ȗ" }
            ,{ hex: "&#0217;", name: "LATIN SMALL LETTER U WITH INVERTED BREVE", char: "ȗ" }
            ,{ hex: "&#0218;", name: "LATIN CAPITAL LETTER S WITH COMMA BELOW", char: "Ș" }
            ,{ hex: "&#0219;", name: "LATIN SMALL LETTER S WITH COMMA BELOW", char: "ș" }
            ,{ hex: "&#021A;", name: "LATIN CAPITAL LETTER T WITH COMMA BELOW", char: "Ț" }
            ,{ hex: "&#021B;", name: "LATIN SMALL LETTER T WITH COMMA BELOW", char: "ț" }
            ,{ hex: "&#021C;", name: "LATIN CAPITAL LETTER YOGH", char: "Ȝ" }
            ,{ hex: "&#021D;", name: "LATIN SMALL LETTER YOGH", char: "ȝ" }
            ,{ hex: "&#021E;", name: "LATIN CAPITAL LETTER H WITH CARON", char: "Ȟ" }
            ,{ hex: "&#021F;", name: "LATIN SMALL LETTER H WITH CARON", char: "ȟ" }
            ,{ hex: "&#0220;", name: "LATIN CAPITAL LETTER N WITH LONG RIGHT LEG", char: "Ƞ" }
            ,{ hex: "&#0221;", name: "LATIN SMALL LETTER D WITH CURL", char: "ȡ" }
            ,{ hex: "&#0222;", name: "LATIN CAPITAL LETTER OU", char: "Ȣ" }
            ,{ hex: "&#0223;", name: "LATIN SMALL LETTER OU", char: "ȣ" }
            ,{ hex: "&#0224;", name: "LATIN CAPITAL LETTER Z WITH HOOK", char: "Ȥ" }
            ,{ hex: "&#0225;", name: "LATIN SMALL LETTER Z WITH HOOK", char: "ȥ" }
            ,{ hex: "&#0226;", name: "LATIN CAPITAL LETTER A WITH DOT ABOVE", char: "Ȧ" }
            ,{ hex: "&#0227;", name: "LATIN SMALL LETTER A WITH DOT ABOVE", char: "ȧ" }
            ,{ hex: "&#0228;", name: "LATIN CAPITAL LETTER E WITH CEDILLA", char: "Ȩ" }
            ,{ hex: "&#0229;", name: "LATIN SMALL LETTER E WITH CEDILLA", char: "ȩ" }
            ,{ hex: "&#022A;", name: "LATIN CAPITAL LETTER O WITH DIAERESIS AND MACRON", char: "Ȫ" }
            ,{ hex: "&#022B;", name: "LATIN SMALL LETTER O WITH DIAERESIS AND MACRON", char: "ȫ" }
            ,{ hex: "&#022C;", name: "LATIN CAPITAL LETTER O WITH TILDE AND MACRON", char: "Ȭ" }
            ,{ hex: "&#022D;", name: "LATIN SMALL LETTER O WITH TILDE AND MACRON", char: "ȭ" }
            ,{ hex: "&#022E;", name: "LATIN CAPITAL LETTER O WITH DOT ABOVE", char: "Ȯ" }
            ,{ hex: "&#022F;", name: "LATIN SMALL LETTER O WITH DOT ABOVE", char: "ȯ" }
            ,{ hex: "&#0230;", name: "LATIN CAPITAL LETTER O WITH DOT ABOVE AND MACRON", char: "Ȱ" }
            ,{ hex: "&#0231;", name: "LATIN SMALL LETTER O WITH DOT ABOVE AND MACRON", char: "ȱ" }
            ,{ hex: "&#0232;", name: "LATIN CAPITAL LETTER Y WITH MACRON", char: "Ȳ" }
            ,{ hex: "&#0233;", name: "LATIN SMALL LETTER Y WITH MACRON", char: "ȳ" }
            ,{ hex: "&#0234;", name: "LATIN SMALL LETTER L WITH CURL", char: "ȴ" }
            ,{ hex: "&#0235;", name: "LATIN SMALL LETTER N WITH CURL", char: "ȵ" }
            ,{ hex: "&#0236;", name: "LATIN SMALL LETTER T WITH CURL", char: "ȶ" }
            ,{ hex: "&#0237;", name: "LATIN SMALL LETTER DOTLESS J", char: "ȷ" }
            ,{ hex: "&#0238;", name: "LATIN SMALL LETTER DB DIGRAPH", char: "ȸ" }
            ,{ hex: "&#0239;", name: "LATIN SMALL LETTER QP DIGRAPH", char: "ȹ" }
            ,{ hex: "&#023A;", name: "LATIN CAPITAL LETTER A WITH STROKE", char: "Ⱥ" }
            ,{ hex: "&#023B;", name: "LATIN CAPITAL LETTER C WITH STROKE", char: "Ȼ" }
            ,{ hex: "&#023C;", name: "LATIN SMALL LETTER C WITH STROKE", char: "ȼ" }
            ,{ hex: "&#023D;", name: "LATIN CAPITAL LETTER L WITH BAR", char: "Ƚ" }
            ,{ hex: "&#023E;", name: "LATIN CAPITAL LETTER T WITH DIAGONAL STROKE", char: "Ⱦ" }
            ,{ hex: "&#023F;", name: "LATIN SMALL LETTER S WITH SWASH TAIL", char: "ȿ" }
            ,{ hex: "&#0240;", name: "LATIN SMALL LETTER Z WITH SWASH TAIL", char: "ɀ" }
            ,{ hex: "&#0241;", name: "LATIN CAPITAL LETTER GLOTTAL STOP", char: "Ɂ" }
            ,{ hex: "&#0242;", name: "LATIN SMALL LETTER GLOTTAL STOP", char: "ɂ" }
            ,{ hex: "&#0243;", name: "LATIN CAPITAL LETTER B WITH STROKE", char: "Ƀ" }
            ,{ hex: "&#0244;", name: "LATIN CAPITAL LETTER U BAR", char: "Ʉ" }
            ,{ hex: "&#0245;", name: "LATIN CAPITAL LETTER TURNED V", char: "Ʌ" }
            ,{ hex: "&#0246;", name: "LATIN CAPITAL LETTER E WITH STROKE", char: "Ɇ" }
            ,{ hex: "&#0247;", name: "LATIN SMALL LETTER E WITH STROKE", char: "ɇ" }
            ,{ hex: "&#0248;", name: "LATIN CAPITAL LETTER J WITH STROKE", char: "Ɉ" }
            ,{ hex: "&#0249;", name: "LATIN SMALL LETTER J WITH STROKE", char: "ɉ" }
            ,{ hex: "&#024A;", name: "LATIN CAPITAL LETTER SMALL Q WITH HOOK TAIL", char: "Ɋ" }
            ,{ hex: "&#024B;", name: "LATIN SMALL LETTER Q WITH HOOK TAIL", char: "ɋ" }
            ,{ hex: "&#024C;", name: "LATIN CAPITAL LETTER R WITH STROKE", char: "Ɍ" }
            ,{ hex: "&#024D;", name: "LATIN SMALL LETTER R WITH STROKE", char: "ɍ" }
            ,{ hex: "&#024E;", name: "LATIN CAPITAL LETTER Y WITH STROKE", char: "Ɏ" }
            ,{ hex: "&#024F;", name: "LATIN SMALL LETTER Y WITH STROKE", char: "ɏ" }


            ,{ hex: "&#2C60;", name: "LATIN CAPITAL LETTER L WITH DOUBLE BAR", char: "Ⱡ" }
            ,{ hex: "&#2C61;", name: "LATIN SMALL LETTER L WITH DOUBLE BAR", char: "ⱡ" }
            ,{ hex: "&#2C62;", name: "LATIN CAPITAL LETTER L WITH MIDDLE TILDE", char: "Ɫ" }
            ,{ hex: "&#2C63;", name: "LATIN CAPITAL LETTER P WITH STROKE", char: "Ᵽ" }
            ,{ hex: "&#2C64;", name: "LATIN CAPITAL LETTER R WITH TAIL", char: "Ɽ" }
            ,{ hex: "&#2C65;", name: "LATIN SMALL LETTER A WITH STROKE", char: "ⱥ" }
            ,{ hex: "&#2C66;", name: "LATIN SMALL LETTER T WITH DIAGONAL STROKE", char: "ⱦ" }
            ,{ hex: "&#2C67;", name: "LATIN CAPITAL LETTER H WITH DESCENDER", char: "Ⱨ" }
            ,{ hex: "&#2C68;", name: "LATIN SMALL LETTER H WITH DESCENDER", char: "ⱨ" }
            ,{ hex: "&#2C69;", name: "LATIN CAPITAL LETTER K WITH DESCENDER", char: "Ⱪ" }
            ,{ hex: "&#2C6A;", name: "LATIN SMALL LETTER K WITH DESCENDER", char: "ⱪ" }
            ,{ hex: "&#2C6B;", name: "LATIN CAPITAL LETTER Z WITH DESCENDER", char: "Ⱬ" }
            ,{ hex: "&#2C6C;", name: "LATIN SMALL LETTER Z WITH DESCENDER", char: "ⱬ" }
            ,{ hex: "&#2C6D;", name: "LATIN CAPITAL LETTER ALPHA", char: "Ɑ" }
            ,{ hex: "&#2C6E;", name: "LATIN CAPITAL LETTER M WITH HOOK", char: "Ɱ" }
            ,{ hex: "&#2C6F;", name: "LATIN CAPITAL LETTER TURNED A", char: "Ɐ" }
            ,{ hex: "&#2C70;", name: "LATIN CAPITAL LETTER TURNED ALPHA", char: "Ɒ" }
            ,{ hex: "&#2C71;", name: "LATIN SMALL LETTER V WITH RIGHT HOOK", char: "ⱱ" }
            ,{ hex: "&#2C72;", name: "LATIN CAPITAL LETTER W WITH HOOK", char: "Ⱳ" }
            ,{ hex: "&#2C73;", name: "LATIN SMALL LETTER W WITH HOOK", char: "ⱳ" }
            ,{ hex: "&#2C74;", name: "LATIN SMALL LETTER V WITH CURL", char: "ⱴ" }
            ,{ hex: "&#2C75;", name: "LATIN CAPITAL LETTER HALF H", char: "Ⱶ" }
            ,{ hex: "&#2C76;", name: "LATIN SMALL LETTER HALF H", char: "ⱶ" }
            ,{ hex: "&#2C77;", name: "LATIN SMALL LETTER TAILLESS PHI", char: "ⱷ" }
            ,{ hex: "&#2C78;", name: "LATIN SMALL LETTER E WITH NOTCH", char: "ⱸ" }
            ,{ hex: "&#2C79;", name: "LATIN SMALL LETTER TURNED R WITH TAIL", char: "ⱹ" }
            ,{ hex: "&#2C7A;", name: "LATIN SMALL LETTER O WITH LOW RING INSIDE", char: "ⱺ" }
            ,{ hex: "&#2C7B;", name: "LATIN LETTER SMALL CAPITAL TURNED E", char: "ⱻ" }
            ,{ hex: "&#2C7C;", name: "LATIN SUBSCRIPT SMALL LETTER J", char: "ⱼ" }
            ,{ hex: "&#2C7D;", name: "MODIFIER LETTER CAPITAL V", char: "ⱽ" }
            ,{ hex: "&#2C7E;", name: "LATIN CAPITAL LETTER S WITH SWASH TAIL", char: "Ȿ" }
            ,{ hex: "&#2C7F;", name: "LATIN CAPITAL LETTER Z WITH SWASH TAIL", char: "Ɀ" }


            ,{ hex: "&#A720;", name: "MODIFIER LETTER STRESS AND HIGH TONE", char: "꜠" }
            ,{ hex: "&#A721;", name: "MODIFIER LETTER STRESS AND LOW TONE", char: "꜡" }
            ,{ hex: "&#A722;", name: "LATIN CAPITAL LETTER EGYPTOLOGICAL ALEF", char: "Ꜣ" }
            ,{ hex: "&#A723;", name: "LATIN SMALL LETTER EGYPTOLOGICAL ALEF", char: "ꜣ" }
            ,{ hex: "&#A724;", name: "LATIN CAPITAL LETTER EGYPTOLOGICAL AIN", char: "Ꜥ" }
            ,{ hex: "&#A725;", name: "LATIN SMALL LETTER EGYPTOLOGICAL AIN", char: "ꜥ" }
            ,{ hex: "&#A726;", name: "LATIN CAPITAL LETTER HENG", char: "Ꜧ" }
            ,{ hex: "&#A727;", name: "LATIN SMALL LETTER HENG", char: "ꜧ" }
            ,{ hex: "&#A728;", name: "LATIN CAPITAL LETTER TZ", char: "Ꜩ" }
            ,{ hex: "&#A729;", name: "LATIN SMALL LETTER TZ", char: "ꜩ" }
            ,{ hex: "&#A72A;", name: "LATIN CAPITAL LETTER TRESILLO", char: "Ꜫ" }
            ,{ hex: "&#A72B;", name: "LATIN SMALL LETTER TRESILLO", char: "ꜫ" }
            ,{ hex: "&#A72C;", name: "LATIN CAPITAL LETTER CUATRILLO", char: "Ꜭ" }
            ,{ hex: "&#A72D;", name: "LATIN SMALL LETTER CUATRILLO", char: "ꜭ" }
            ,{ hex: "&#A72E;", name: "LATIN CAPITAL LETTER CUATRILLO WITH COMMA", char: "Ꜯ" }
            ,{ hex: "&#A72F;", name: "LATIN SMALL LETTER CUATRILLO WITH COMMA", char: "ꜯ" }
            ,{ hex: "&#A730;", name: "LATIN LETTER SMALL CAPITAL F", char: "ꜰ" }
            ,{ hex: "&#A731;", name: "LATIN LETTER SMALL CAPITAL S", char: "ꜱ" }
            ,{ hex: "&#A732;", name: "LATIN CAPITAL LETTER AA", char: "Ꜳ" }
            ,{ hex: "&#A733;", name: "LATIN SMALL LETTER AA", char: "ꜳ" }
            ,{ hex: "&#A734;", name: "LATIN CAPITAL LETTER AO", char: "Ꜵ" }
            ,{ hex: "&#A735;", name: "LATIN SMALL LETTER AO", char: "ꜵ" }
            ,{ hex: "&#A736;", name: "LATIN CAPITAL LETTER AU", char: "Ꜷ" }
            ,{ hex: "&#A737;", name: "LATIN SMALL LETTER AU", char: "ꜷ" }
            ,{ hex: "&#A738;", name: "LATIN CAPITAL LETTER AV", char: "Ꜹ" }
            ,{ hex: "&#A739;", name: "LATIN SMALL LETTER AV", char: "ꜹ" }
            ,{ hex: "&#A73A;", name: "LATIN CAPITAL LETTER AV WITH HORIZONTAL BAR", char: "Ꜻ" }
            ,{ hex: "&#A73B;", name: "LATIN SMALL LETTER AV WITH HORIZONTAL BAR", char: "ꜻ" }
            ,{ hex: "&#A73C;", name: "LATIN CAPITAL LETTER AY", char: "Ꜽ" }
            ,{ hex: "&#A73D;", name: "LATIN SMALL LETTER AY", char: "ꜽ" }
            ,{ hex: "&#A73E;", name: "LATIN CAPITAL LETTER REVERSED C WITH DOT", char: "Ꜿ" }
            ,{ hex: "&#A73F;", name: "LATIN SMALL LETTER REVERSED C WITH DOT", char: "ꜿ" }
            ,{ hex: "&#A740;", name: "LATIN CAPITAL LETTER K WITH STROKE", char: "Ꝁ" }
            ,{ hex: "&#A741;", name: "LATIN SMALL LETTER K WITH STROKE", char: "ꝁ" }
            ,{ hex: "&#A742;", name: "LATIN CAPITAL LETTER K WITH DIAGONAL STROKE", char: "Ꝃ" }
            ,{ hex: "&#A743;", name: "LATIN SMALL LETTER K WITH DIAGONAL STROKE", char: "ꝃ" }
            ,{ hex: "&#A744;", name: "LATIN CAPITAL LETTER K WITH STROKE AND DIAGONAL STROKE", char: "Ꝅ" }
            ,{ hex: "&#A745;", name: "LATIN SMALL LETTER K WITH STROKE AND DIAGONAL STROKE", char: "ꝅ" }
            ,{ hex: "&#A746;", name: "LATIN CAPITAL LETTER BROKEN L", char: "Ꝇ" }
            ,{ hex: "&#A747;", name: "LATIN SMALL LETTER BROKEN L", char: "ꝇ" }
            ,{ hex: "&#A748;", name: "LATIN CAPITAL LETTER L WITH HIGH STROKE", char: "Ꝉ" }
            ,{ hex: "&#A749;", name: "LATIN SMALL LETTER L WITH HIGH STROKE", char: "ꝉ" }
            ,{ hex: "&#A74A;", name: "LATIN CAPITAL LETTER O WITH LONG STROKE OVERLAY", char: "Ꝋ" }
            ,{ hex: "&#A74B;", name: "LATIN SMALL LETTER O WITH LONG STROKE OVERLAY", char: "ꝋ" }
            ,{ hex: "&#A74C;", name: "LATIN CAPITAL LETTER O WITH LOOP", char: "Ꝍ" }
            ,{ hex: "&#A74D;", name: "LATIN SMALL LETTER O WITH LOOP", char: "ꝍ" }
            ,{ hex: "&#A74E;", name: "LATIN CAPITAL LETTER OO", char: "Ꝏ" }
            ,{ hex: "&#A74F;", name: "LATIN SMALL LETTER OO", char: "ꝏ" }
            ,{ hex: "&#A750;", name: "LATIN CAPITAL LETTER P WITH STROKE THROUGH DESCENDER", char: "Ꝑ" }
            ,{ hex: "&#A751;", name: "LATIN SMALL LETTER P WITH STROKE THROUGH DESCENDER", char: "ꝑ" }
            ,{ hex: "&#A752;", name: "LATIN CAPITAL LETTER P WITH FLOURISH", char: "Ꝓ" }
            ,{ hex: "&#A753;", name: "LATIN SMALL LETTER P WITH FLOURISH", char: "ꝓ" }
            ,{ hex: "&#A754;", name: "LATIN CAPITAL LETTER P WITH SQUIRREL TAIL", char: "Ꝕ" }
            ,{ hex: "&#A755;", name: "LATIN SMALL LETTER P WITH SQUIRREL TAIL", char: "ꝕ" }
            ,{ hex: "&#A756;", name: "LATIN CAPITAL LETTER Q WITH STROKE THROUGH DESCENDER", char: "Ꝗ" }
            ,{ hex: "&#A757;", name: "LATIN SMALL LETTER Q WITH STROKE THROUGH DESCENDER", char: "ꝗ" }
            ,{ hex: "&#A758;", name: "LATIN CAPITAL LETTER Q WITH DIAGONAL STROKE", char: "Ꝙ" }
            ,{ hex: "&#A759;", name: "LATIN SMALL LETTER Q WITH DIAGONAL STROKE", char: "ꝙ" }
            ,{ hex: "&#A75A;", name: "LATIN CAPITAL LETTER R ROTUNDA", char: "Ꝛ" }
            ,{ hex: "&#A75B;", name: "LATIN SMALL LETTER R ROTUNDA", char: "ꝛ" }
            ,{ hex: "&#A75C;", name: "LATIN CAPITAL LETTER RUM ROTUNDA", char: "Ꝝ" }
            ,{ hex: "&#A75D;", name: "LATIN SMALL LETTER RUM ROTUNDA", char: "ꝝ" }
            ,{ hex: "&#A75E;", name: "LATIN CAPITAL LETTER V WITH DIAGONAL STROKE", char: "Ꝟ" }
            ,{ hex: "&#A75F;", name: "LATIN SMALL LETTER V WITH DIAGONAL STROKE", char: "ꝟ" }
            ,{ hex: "&#A760;", name: "LATIN CAPITAL LETTER VY", char: "Ꝡ" }
            ,{ hex: "&#A761;", name: "LATIN SMALL LETTER VY", char: "ꝡ" }
            ,{ hex: "&#A762;", name: "LATIN CAPITAL LETTER VISIGOTHIC Z", char: "Ꝣ" }
            ,{ hex: "&#A763;", name: "LATIN SMALL LETTER VISIGOTHIC Z", char: "ꝣ" }
            ,{ hex: "&#A764;", name: "LATIN CAPITAL LETTER THORN WITH STROKE", char: "Ꝥ" }
            ,{ hex: "&#A765;", name: "LATIN SMALL LETTER THORN WITH STROKE", char: "ꝥ" }
            ,{ hex: "&#A766;", name: "LATIN CAPITAL LETTER THORN WITH STROKE THROUGH DESCENDER", char: "Ꝧ" }
            ,{ hex: "&#A767;", name: "LATIN SMALL LETTER THORN WITH STROKE THROUGH DESCENDER", char: "ꝧ" }
            ,{ hex: "&#A768;", name: "LATIN CAPITAL LETTER VEND", char: "Ꝩ" }
            ,{ hex: "&#A769;", name: "LATIN SMALL LETTER VEND", char: "ꝩ" }
            ,{ hex: "&#A76A;", name: "LATIN CAPITAL LETTER ET", char: "Ꝫ" }
            ,{ hex: "&#A76B;", name: "LATIN SMALL LETTER ET", char: "ꝫ" }
            ,{ hex: "&#A76C;", name: "LATIN CAPITAL LETTER IS", char: "Ꝭ" }
            ,{ hex: "&#A76D;", name: "LATIN SMALL LETTER IS", char: "ꝭ" }
            ,{ hex: "&#A76E;", name: "LATIN CAPITAL LETTER CON", char: "Ꝯ" }
            ,{ hex: "&#A76F;", name: "LATIN SMALL LETTER CON", char: "ꝯ" }
            ,{ hex: "&#A770;", name: "MODIFIER LETTER US", char: "ꝰ" }
            ,{ hex: "&#A771;", name: "LATIN SMALL LETTER DUM", char: "ꝱ" }
            ,{ hex: "&#A772;", name: "LATIN SMALL LETTER LUM", char: "ꝲ" }
            ,{ hex: "&#A773;", name: "LATIN SMALL LETTER MUM", char: "ꝳ" }
            ,{ hex: "&#A774;", name: "LATIN SMALL LETTER NUM", char: "ꝴ" }
            ,{ hex: "&#A775;", name: "LATIN SMALL LETTER RUM", char: "ꝵ" }
            ,{ hex: "&#A776;", name: "LATIN LETTER SMALL CAPITAL RUM", char: "ꝶ" }
            ,{ hex: "&#A777;", name: "LATIN SMALL LETTER TUM", char: "ꝷ" }
            ,{ hex: "&#A778;", name: "LATIN SMALL LETTER UM", char: "ꝸ" }
            ,{ hex: "&#A779;", name: "LATIN CAPITAL LETTER INSULAR D", char: "Ꝺ" }
            ,{ hex: "&#A77A;", name: "LATIN SMALL LETTER INSULAR D", char: "ꝺ" }
            ,{ hex: "&#A77B;", name: "LATIN CAPITAL LETTER INSULAR F", char: "Ꝼ" }
            ,{ hex: "&#A77C;", name: "LATIN SMALL LETTER INSULAR F", char: "ꝼ" }
            ,{ hex: "&#A77D;", name: "LATIN CAPITAL LETTER INSULAR G", char: "Ᵹ" }
            ,{ hex: "&#A77E;", name: "LATIN CAPITAL LETTER TURNED INSULAR G", char: "Ꝿ" }
            ,{ hex: "&#A77F;", name: "LATIN SMALL LETTER TURNED INSULAR G", char: "ꝿ" }
            ,{ hex: "&#A780;", name: "LATIN CAPITAL LETTER TURNED L", char: "Ꞁ" }
            ,{ hex: "&#A781;", name: "LATIN SMALL LETTER TURNED L", char: "ꞁ" }
            ,{ hex: "&#A782;", name: "LATIN CAPITAL LETTER INSULAR R", char: "Ꞃ" }
            ,{ hex: "&#A783;", name: "LATIN SMALL LETTER INSULAR R", char: "ꞃ" }
            ,{ hex: "&#A784;", name: "LATIN CAPITAL LETTER INSULAR S", char: "Ꞅ" }
            ,{ hex: "&#A785;", name: "LATIN SMALL LETTER INSULAR S", char: "ꞅ" }
            ,{ hex: "&#A786;", name: "LATIN CAPITAL LETTER INSULAR T", char: "Ꞇ" }
            ,{ hex: "&#A787;", name: "LATIN SMALL LETTER INSULAR T", char: "ꞇ" }
            ,{ hex: "&#A788;", name: "MODIFIER LETTER LOW CIRCUMFLEX ACCENT", char: "ꞈ" }
            ,{ hex: "&#A789;", name: "MODIFIER LETTER COLON", char: "꞉" }
            ,{ hex: "&#A78A;", name: "MODIFIER LETTER SHORT EQUALS SIGN", char: "꞊" }
            ,{ hex: "&#A78B;", name: "LATIN CAPITAL LETTER SALTILLO", char: "Ꞌ" }
            ,{ hex: "&#A78C;", name: "LATIN SMALL LETTER SALTILLO", char: "ꞌ" }
            ,{ hex: "&#A78D;", name: "LATIN CAPITAL LETTER TURNED H", char: "Ɥ" }
            ,{ hex: "&#A78E;", name: "LATIN SMALL LETTER L WITH RETROFLEX HOOK AND BELT", char: "ꞎ" }
            ,{ hex: "&#A790;", name: "LATIN CAPITAL LETTER N WITH DESCENDER", char: "Ꞑ" }
            ,{ hex: "&#A791;", name: "LATIN SMALL LETTER N WITH DESCENDER", char: "ꞑ" }
            ,{ hex: "&#A7A0;", name: "LATIN CAPITAL LETTER G WITH OBLIQUE STROKE", char: "Ꞡ" }
            ,{ hex: "&#A7A1;", name: "LATIN SMALL LETTER G WITH OBLIQUE STROKE", char: "ꞡ" }
            ,{ hex: "&#A7A2;", name: "LATIN CAPITAL LETTER K WITH OBLIQUE STROKE", char: "Ꞣ" }
            ,{ hex: "&#A7A3;", name: "LATIN SMALL LETTER K WITH OBLIQUE STROKE", char: "ꞣ" }
            ,{ hex: "&#A7A4;", name: "LATIN CAPITAL LETTER N WITH OBLIQUE STROKE", char: "Ꞥ" }
            ,{ hex: "&#A7A5;", name: "LATIN SMALL LETTER N WITH OBLIQUE STROKE", char: "ꞥ" }
            ,{ hex: "&#A7A6;", name: "LATIN CAPITAL LETTER R WITH OBLIQUE STROKE", char: "Ꞧ" }
            ,{ hex: "&#A7A7;", name: "LATIN SMALL LETTER R WITH OBLIQUE STROKE", char: "ꞧ" }
            ,{ hex: "&#A7A8;", name: "LATIN CAPITAL LETTER S WITH OBLIQUE STROKE", char: "Ꞩ" }
            ,{ hex: "&#A7A9;", name: "LATIN SMALL LETTER S WITH OBLIQUE STROKE", char: "ꞩ" }
            ,{ hex: "&#A7FA;", name: "LATIN LETTER SMALL CAPITAL TURNED M", char: "ꟺ" }
            ,{ hex: "&#A7FB;", name: "LATIN EPIGRAPHIC LETTER REVERSED F", char: "ꟻ" }
            ,{ hex: "&#A7FC;", name: "LATIN EPIGRAPHIC LETTER REVERSED P", char: "ꟼ" }
            ,{ hex: "&#A7FD;", name: "LATIN EPIGRAPHIC LETTER INVERTED M", char: "ꟽ" }
            ,{ hex: "&#A7FE;", name: "LATIN EPIGRAPHIC LETTER I LONGA", char: "ꟾ" }
            ,{ hex: "&#A7FF;", name: "LATIN EPIGRAPHIC LETTER ARCHAIC M", char: "ꟿ" }


            ,{ hex: "&#1E00;", name: "LATIN CAPITAL LETTER A WITH RING BELOW", char: "Ḁ" }
            ,{ hex: "&#1E01;", name: "LATIN SMALL LETTER A WITH RING BELOW", char: "ḁ" }
            ,{ hex: "&#1E02;", name: "LATIN CAPITAL LETTER B WITH DOT ABOVE", char: "Ḃ" }
            ,{ hex: "&#1E03;", name: "LATIN SMALL LETTER B WITH DOT ABOVE", char: "ḃ" }
            ,{ hex: "&#1E04;", name: "LATIN CAPITAL LETTER B WITH DOT BELOW", char: "Ḅ" }
            ,{ hex: "&#1E05;", name: "LATIN SMALL LETTER B WITH DOT BELOW", char: "ḅ" }
            ,{ hex: "&#1E06;", name: "LATIN CAPITAL LETTER B WITH LINE BELOW", char: "Ḇ" }
            ,{ hex: "&#1E07;", name: "LATIN SMALL LETTER B WITH LINE BELOW", char: "ḇ" }
            ,{ hex: "&#1E08;", name: "LATIN CAPITAL LETTER C WITH CEDILLA AND ACUTE", char: "Ḉ" }
            ,{ hex: "&#1E09;", name: "LATIN SMALL LETTER C WITH CEDILLA AND ACUTE", char: "ḉ" }
            ,{ hex: "&#1E0A;", name: "LATIN CAPITAL LETTER D WITH DOT ABOVE", char: "Ḋ" }
            ,{ hex: "&#1E0B;", name: "LATIN SMALL LETTER D WITH DOT ABOVE", char: "ḋ" }
            ,{ hex: "&#1E0C;", name: "LATIN CAPITAL LETTER D WITH DOT BELOW", char: "Ḍ" }
            ,{ hex: "&#1E0D;", name: "LATIN SMALL LETTER D WITH DOT BELOW", char: "ḍ" }
            ,{ hex: "&#1E0E;", name: "LATIN CAPITAL LETTER D WITH LINE BELOW", char: "Ḏ" }
            ,{ hex: "&#1E0F;", name: "LATIN SMALL LETTER D WITH LINE BELOW", char: "ḏ" }
            ,{ hex: "&#1E10;", name: "LATIN CAPITAL LETTER D WITH CEDILLA", char: "Ḑ" }
            ,{ hex: "&#1E11;", name: "LATIN SMALL LETTER D WITH CEDILLA", char: "ḑ" }
            ,{ hex: "&#1E12;", name: "LATIN CAPITAL LETTER D WITH CIRCUMFLEX BELOW", char: "Ḓ" }
            ,{ hex: "&#1E13;", name: "LATIN SMALL LETTER D WITH CIRCUMFLEX BELOW", char: "ḓ" }
            ,{ hex: "&#1E14;", name: "LATIN CAPITAL LETTER E WITH MACRON AND GRAVE", char: "Ḕ" }
            ,{ hex: "&#1E15;", name: "LATIN SMALL LETTER E WITH MACRON AND GRAVE", char: "ḕ" }
            ,{ hex: "&#1E16;", name: "LATIN CAPITAL LETTER E WITH MACRON AND ACUTE", char: "Ḗ" }
            ,{ hex: "&#1E17;", name: "LATIN SMALL LETTER E WITH MACRON AND ACUTE", char: "ḗ" }
            ,{ hex: "&#1E18;", name: "LATIN CAPITAL LETTER E WITH CIRCUMFLEX BELOW", char: "Ḙ" }
            ,{ hex: "&#1E19;", name: "LATIN SMALL LETTER E WITH CIRCUMFLEX BELOW", char: "ḙ" }
            ,{ hex: "&#1E1A;", name: "LATIN CAPITAL LETTER E WITH TILDE BELOW", char: "Ḛ" }
            ,{ hex: "&#1E1B;", name: "LATIN SMALL LETTER E WITH TILDE BELOW", char: "ḛ" }
            ,{ hex: "&#1E1C;", name: "LATIN CAPITAL LETTER E WITH CEDILLA AND BREVE", char: "Ḝ" }
            ,{ hex: "&#1E1D;", name: "LATIN SMALL LETTER E WITH CEDILLA AND BREVE", char: "ḝ" }
            ,{ hex: "&#1E1E;", name: "LATIN CAPITAL LETTER F WITH DOT ABOVE", char: "Ḟ" }
            ,{ hex: "&#1E1F;", name: "LATIN SMALL LETTER F WITH DOT ABOVE", char: "ḟ" }
            ,{ hex: "&#1E20;", name: "LATIN CAPITAL LETTER G WITH MACRON", char: "Ḡ" }
            ,{ hex: "&#1E21;", name: "LATIN SMALL LETTER G WITH MACRON", char: "ḡ" }
            ,{ hex: "&#1E22;", name: "LATIN CAPITAL LETTER H WITH DOT ABOVE", char: "Ḣ" }
            ,{ hex: "&#1E23;", name: "LATIN SMALL LETTER H WITH DOT ABOVE", char: "ḣ" }
            ,{ hex: "&#1E24;", name: "LATIN CAPITAL LETTER H WITH DOT BELOW", char: "Ḥ" }
            ,{ hex: "&#1E25;", name: "LATIN SMALL LETTER H WITH DOT BELOW", char: "ḥ" }
            ,{ hex: "&#1E26;", name: "LATIN CAPITAL LETTER H WITH DIAERESIS", char: "Ḧ" }
            ,{ hex: "&#1E27;", name: "LATIN SMALL LETTER H WITH DIAERESIS", char: "ḧ" }
            ,{ hex: "&#1E28;", name: "LATIN CAPITAL LETTER H WITH CEDILLA", char: "Ḩ" }
            ,{ hex: "&#1E29;", name: "LATIN SMALL LETTER H WITH CEDILLA", char: "ḩ" }
            ,{ hex: "&#1E2A;", name: "LATIN CAPITAL LETTER H WITH BREVE BELOW", char: "Ḫ" }
            ,{ hex: "&#1E2B;", name: "LATIN SMALL LETTER H WITH BREVE BELOW", char: "ḫ" }
            ,{ hex: "&#1E2C;", name: "LATIN CAPITAL LETTER I WITH TILDE BELOW", char: "Ḭ" }
            ,{ hex: "&#1E2D;", name: "LATIN SMALL LETTER I WITH TILDE BELOW", char: "ḭ" }
            ,{ hex: "&#1E2E;", name: "LATIN CAPITAL LETTER I WITH DIAERESIS AND ACUTE", char: "Ḯ" }
            ,{ hex: "&#1E2F;", name: "LATIN SMALL LETTER I WITH DIAERESIS AND ACUTE", char: "ḯ" }
            ,{ hex: "&#1E30;", name: "LATIN CAPITAL LETTER K WITH ACUTE", char: "Ḱ" }
            ,{ hex: "&#1E31;", name: "LATIN SMALL LETTER K WITH ACUTE", char: "ḱ" }
            ,{ hex: "&#1E32;", name: "LATIN CAPITAL LETTER K WITH DOT BELOW", char: "Ḳ" }
            ,{ hex: "&#1E33;", name: "LATIN SMALL LETTER K WITH DOT BELOW", char: "ḳ" }
            ,{ hex: "&#1E34;", name: "LATIN CAPITAL LETTER K WITH LINE BELOW", char: "Ḵ" }
            ,{ hex: "&#1E35;", name: "LATIN SMALL LETTER K WITH LINE BELOW", char: "ḵ" }
            ,{ hex: "&#1E36;", name: "LATIN CAPITAL LETTER L WITH DOT BELOW", char: "Ḷ" }
            ,{ hex: "&#1E37;", name: "LATIN SMALL LETTER L WITH DOT BELOW", char: "ḷ" }
            ,{ hex: "&#1E38;", name: "LATIN CAPITAL LETTER L WITH DOT BELOW AND MACRON", char: "Ḹ" }
            ,{ hex: "&#1E39;", name: "LATIN SMALL LETTER L WITH DOT BELOW AND MACRON", char: "ḹ" }
            ,{ hex: "&#1E3A;", name: "LATIN CAPITAL LETTER L WITH LINE BELOW", char: "Ḻ" }
            ,{ hex: "&#1E3B;", name: "LATIN SMALL LETTER L WITH LINE BELOW", char: "ḻ" }
            ,{ hex: "&#1E3C;", name: "LATIN CAPITAL LETTER L WITH CIRCUMFLEX BELOW", char: "Ḽ" }
            ,{ hex: "&#1E3D;", name: "LATIN SMALL LETTER L WITH CIRCUMFLEX BELOW", char: "ḽ" }
            ,{ hex: "&#1E3E;", name: "LATIN CAPITAL LETTER M WITH ACUTE", char: "Ḿ" }
            ,{ hex: "&#1E3F;", name: "LATIN SMALL LETTER M WITH ACUTE", char: "ḿ" }
            ,{ hex: "&#1E40;", name: "LATIN CAPITAL LETTER M WITH DOT ABOVE", char: "Ṁ" }
            ,{ hex: "&#1E41;", name: "LATIN SMALL LETTER M WITH DOT ABOVE", char: "ṁ" }
            ,{ hex: "&#1E42;", name: "LATIN CAPITAL LETTER M WITH DOT BELOW", char: "Ṃ" }
            ,{ hex: "&#1E43;", name: "LATIN SMALL LETTER M WITH DOT BELOW", char: "ṃ" }
            ,{ hex: "&#1E44;", name: "LATIN CAPITAL LETTER N WITH DOT ABOVE", char: "Ṅ" }
            ,{ hex: "&#1E45;", name: "LATIN SMALL LETTER N WITH DOT ABOVE", char: "ṅ" }
            ,{ hex: "&#1E46;", name: "LATIN CAPITAL LETTER N WITH DOT BELOW", char: "Ṇ" }
            ,{ hex: "&#1E47;", name: "LATIN SMALL LETTER N WITH DOT BELOW", char: "ṇ" }
            ,{ hex: "&#1E48;", name: "LATIN CAPITAL LETTER N WITH LINE BELOW", char: "Ṉ" }
            ,{ hex: "&#1E49;", name: "LATIN SMALL LETTER N WITH LINE BELOW", char: "ṉ" }
            ,{ hex: "&#1E4A;", name: "LATIN CAPITAL LETTER N WITH CIRCUMFLEX BELOW", char: "Ṋ" }
            ,{ hex: "&#1E4B;", name: "LATIN SMALL LETTER N WITH CIRCUMFLEX BELOW", char: "ṋ" }
            ,{ hex: "&#1E4C;", name: "LATIN CAPITAL LETTER O WITH TILDE AND ACUTE", char: "Ṍ" }
            ,{ hex: "&#1E4D;", name: "LATIN SMALL LETTER O WITH TILDE AND ACUTE", char: "ṍ" }
            ,{ hex: "&#1E4E;", name: "LATIN CAPITAL LETTER O WITH TILDE AND DIAERESIS", char: "Ṏ" }
            ,{ hex: "&#1E4F;", name: "LATIN SMALL LETTER O WITH TILDE AND DIAERESIS", char: "ṏ" }
            ,{ hex: "&#1E50;", name: "LATIN CAPITAL LETTER O WITH MACRON AND GRAVE", char: "Ṑ" }
            ,{ hex: "&#1E51;", name: "LATIN SMALL LETTER O WITH MACRON AND GRAVE", char: "ṑ" }
            ,{ hex: "&#1E52;", name: "LATIN CAPITAL LETTER O WITH MACRON AND ACUTE", char: "Ṓ" }
            ,{ hex: "&#1E53;", name: "LATIN SMALL LETTER O WITH MACRON AND ACUTE", char: "ṓ" }
            ,{ hex: "&#1E54;", name: "LATIN CAPITAL LETTER P WITH ACUTE", char: "Ṕ" }
            ,{ hex: "&#1E55;", name: "LATIN SMALL LETTER P WITH ACUTE", char: "ṕ" }
            ,{ hex: "&#1E56;", name: "LATIN CAPITAL LETTER P WITH DOT ABOVE", char: "Ṗ" }
            ,{ hex: "&#1E57;", name: "LATIN SMALL LETTER P WITH DOT ABOVE", char: "ṗ" }
            ,{ hex: "&#1E58;", name: "LATIN CAPITAL LETTER R WITH DOT ABOVE", char: "Ṙ" }
            ,{ hex: "&#1E59;", name: "LATIN SMALL LETTER R WITH DOT ABOVE", char: "ṙ" }
            ,{ hex: "&#1E5A;", name: "LATIN CAPITAL LETTER R WITH DOT BELOW", char: "Ṛ" }
            ,{ hex: "&#1E5B;", name: "LATIN SMALL LETTER R WITH DOT BELOW", char: "ṛ" }
            ,{ hex: "&#1E5C;", name: "LATIN CAPITAL LETTER R WITH DOT BELOW AND MACRON", char: "Ṝ" }
            ,{ hex: "&#1E5D;", name: "LATIN SMALL LETTER R WITH DOT BELOW AND MACRON", char: "ṝ" }
            ,{ hex: "&#1E5E;", name: "LATIN CAPITAL LETTER R WITH LINE BELOW", char: "Ṟ" }
            ,{ hex: "&#1E5F;", name: "LATIN SMALL LETTER R WITH LINE BELOW", char: "ṟ" }
            ,{ hex: "&#1E60;", name: "LATIN CAPITAL LETTER S WITH DOT ABOVE", char: "Ṡ" }
            ,{ hex: "&#1E61;", name: "LATIN SMALL LETTER S WITH DOT ABOVE", char: "ṡ" }
            ,{ hex: "&#1E62;", name: "LATIN CAPITAL LETTER S WITH DOT BELOW", char: "Ṣ" }
            ,{ hex: "&#1E63;", name: "LATIN SMALL LETTER S WITH DOT BELOW", char: "ṣ" }
            ,{ hex: "&#1E64;", name: "LATIN CAPITAL LETTER S WITH ACUTE AND DOT ABOVE", char: "Ṥ" }
            ,{ hex: "&#1E65;", name: "LATIN SMALL LETTER S WITH ACUTE AND DOT ABOVE", char: "ṥ" }
            ,{ hex: "&#1E66;", name: "LATIN CAPITAL LETTER S WITH CARON AND DOT ABOVE", char: "Ṧ" }
            ,{ hex: "&#1E67;", name: "LATIN SMALL LETTER S WITH CARON AND DOT ABOVE", char: "ṧ" }
            ,{ hex: "&#1E68;", name: "LATIN CAPITAL LETTER S WITH DOT BELOW AND DOT ABOVE", char: "Ṩ" }
            ,{ hex: "&#1E69;", name: "LATIN SMALL LETTER S WITH DOT BELOW AND DOT ABOVE", char: "ṩ" }
            ,{ hex: "&#1E6A;", name: "LATIN CAPITAL LETTER T WITH DOT ABOVE", char: "Ṫ" }
            ,{ hex: "&#1E6B;", name: "LATIN SMALL LETTER T WITH DOT ABOVE", char: "ṫ" }
            ,{ hex: "&#1E6C;", name: "LATIN CAPITAL LETTER T WITH DOT BELOW", char: "Ṭ" }
            ,{ hex: "&#1E6D;", name: "LATIN SMALL LETTER T WITH DOT BELOW", char: "ṭ" }
            ,{ hex: "&#1E6E;", name: "LATIN CAPITAL LETTER T WITH LINE BELOW", char: "Ṯ" }
            ,{ hex: "&#1E6F;", name: "LATIN SMALL LETTER T WITH LINE BELOW", char: "ṯ" }
            ,{ hex: "&#1E70;", name: "LATIN CAPITAL LETTER T WITH CIRCUMFLEX BELOW", char: "Ṱ" }
            ,{ hex: "&#1E71;", name: "LATIN SMALL LETTER T WITH CIRCUMFLEX BELOW", char: "ṱ" }
            ,{ hex: "&#1E72;", name: "LATIN CAPITAL LETTER U WITH DIAERESIS BELOW", char: "Ṳ" }
            ,{ hex: "&#1E73;", name: "LATIN SMALL LETTER U WITH DIAERESIS BELOW", char: "ṳ" }
            ,{ hex: "&#1E74;", name: "LATIN CAPITAL LETTER U WITH TILDE BELOW", char: "Ṵ" }
            ,{ hex: "&#1E75;", name: "LATIN SMALL LETTER U WITH TILDE BELOW", char: "ṵ" }
            ,{ hex: "&#1E76;", name: "LATIN CAPITAL LETTER U WITH CIRCUMFLEX BELOW", char: "Ṷ" }
            ,{ hex: "&#1E77;", name: "LATIN SMALL LETTER U WITH CIRCUMFLEX BELOW", char: "ṷ" }
            ,{ hex: "&#1E78;", name: "LATIN CAPITAL LETTER U WITH TILDE AND ACUTE", char: "Ṹ" }
            ,{ hex: "&#1E79;", name: "LATIN SMALL LETTER U WITH TILDE AND ACUTE", char: "ṹ" }
            ,{ hex: "&#1E7A;", name: "LATIN CAPITAL LETTER U WITH MACRON AND DIAERESIS", char: "Ṻ" }
            ,{ hex: "&#1E7B;", name: "LATIN SMALL LETTER U WITH MACRON AND DIAERESIS", char: "ṻ" }
            ,{ hex: "&#1E7C;", name: "LATIN CAPITAL LETTER V WITH TILDE", char: "Ṽ" }
            ,{ hex: "&#1E7D;", name: "LATIN SMALL LETTER V WITH TILDE", char: "ṽ" }
            ,{ hex: "&#1E7E;", name: "LATIN CAPITAL LETTER V WITH DOT BELOW", char: "Ṿ" }
            ,{ hex: "&#1E7F;", name: "LATIN SMALL LETTER V WITH DOT BELOW", char: "ṿ" }
            ,{ hex: "&#1E80;", name: "LATIN CAPITAL LETTER W WITH GRAVE (present in WGL4)", char: "Ẁ" }
            ,{ hex: "&#1E81;", name: "LATIN SMALL LETTER W WITH GRAVE (present in WGL4)", char: "ẁ" }
            ,{ hex: "&#1E82;", name: "LATIN CAPITAL LETTER W WITH ACUTE (present in WGL4)", char: "Ẃ" }
            ,{ hex: "&#1E83;", name: "LATIN SMALL LETTER W WITH ACUTE (present in WGL4)", char: "ẃ" }
            ,{ hex: "&#1E84;", name: "LATIN CAPITAL LETTER W WITH DIAERESIS (present in WGL4)", char: "Ẅ" }
            ,{ hex: "&#1E85;", name: "LATIN SMALL LETTER W WITH DIAERESIS (present in WGL4)", char: "ẅ" }
            ,{ hex: "&#1E86;", name: "LATIN CAPITAL LETTER W WITH DOT ABOVE", char: "Ẇ" }
            ,{ hex: "&#1E87;", name: "LATIN SMALL LETTER W WITH DOT ABOVE", char: "ẇ" }
            ,{ hex: "&#1E88;", name: "LATIN CAPITAL LETTER W WITH DOT BELOW", char: "Ẉ" }
            ,{ hex: "&#1E89;", name: "LATIN SMALL LETTER W WITH DOT BELOW", char: "ẉ" }
            ,{ hex: "&#1E8A;", name: "LATIN CAPITAL LETTER X WITH DOT ABOVE", char: "Ẋ" }
            ,{ hex: "&#1E8B;", name: "LATIN SMALL LETTER X WITH DOT ABOVE", char: "ẋ" }
            ,{ hex: "&#1E8C;", name: "LATIN CAPITAL LETTER X WITH DIAERESIS", char: "Ẍ" }
            ,{ hex: "&#1E8D;", name: "LATIN SMALL LETTER X WITH DIAERESIS", char: "ẍ" }
            ,{ hex: "&#1E8E;", name: "LATIN CAPITAL LETTER Y WITH DOT ABOVE", char: "Ẏ" }
            ,{ hex: "&#1E8F;", name: "LATIN SMALL LETTER Y WITH DOT ABOVE", char: "ẏ" }
            ,{ hex: "&#1E90;", name: "LATIN CAPITAL LETTER Z WITH CIRCUMFLEX", char: "Ẑ" }
            ,{ hex: "&#1E91;", name: "LATIN SMALL LETTER Z WITH CIRCUMFLEX", char: "ẑ" }
            ,{ hex: "&#1E92;", name: "LATIN CAPITAL LETTER Z WITH DOT BELOW", char: "Ẓ" }
            ,{ hex: "&#1E93;", name: "LATIN SMALL LETTER Z WITH DOT BELOW", char: "ẓ" }
            ,{ hex: "&#1E94;", name: "LATIN CAPITAL LETTER Z WITH LINE BELOW", char: "Ẕ" }
            ,{ hex: "&#1E95;", name: "LATIN SMALL LETTER Z WITH LINE BELOW", char: "ẕ" }
            ,{ hex: "&#1E96;", name: "LATIN SMALL LETTER H WITH LINE BELOW", char: "ẖ" }
            ,{ hex: "&#1E97;", name: "LATIN SMALL LETTER T WITH DIAERESIS", char: "ẗ" }
            ,{ hex: "&#1E98;", name: "LATIN SMALL LETTER W WITH RING ABOVE", char: "ẘ" }
            ,{ hex: "&#1E99;", name: "LATIN SMALL LETTER Y WITH RING ABOVE", char: "ẙ" }
            ,{ hex: "&#1E9A;", name: "LATIN SMALL LETTER A WITH RIGHT HALF RING", char: "ẚ" }
            ,{ hex: "&#1E9B;", name: "LATIN SMALL LETTER LONG S WITH DOT ABOVE", char: "ẛ" }
            ,{ hex: "&#1E9C;", name: "LATIN SMALL LETTER LONG S WITH DIAGONAL STROKE", char: "ẜ" }
            ,{ hex: "&#1E9D;", name: "LATIN SMALL LETTER LONG S WITH HIGH STROKE", char: "ẝ" }
            ,{ hex: "&#1E9E;", name: "LATIN CAPITAL LETTER SHARP S", char: "ẞ" }
            ,{ hex: "&#1E9F;", name: "LATIN SMALL LETTER DELTA", char: "ẟ" }
            ,{ hex: "&#1EA0;", name: "LATIN CAPITAL LETTER A WITH DOT BELOW", char: "Ạ" }
            ,{ hex: "&#1EA1;", name: "LATIN SMALL LETTER A WITH DOT BELOW", char: "ạ" }
            ,{ hex: "&#1EA2;", name: "LATIN CAPITAL LETTER A WITH HOOK ABOVE", char: "Ả" }
            ,{ hex: "&#1EA3;", name: "LATIN SMALL LETTER A WITH HOOK ABOVE", char: "ả" }
            ,{ hex: "&#1EA4;", name: "LATIN CAPITAL LETTER A WITH CIRCUMFLEX AND ACUTE", char: "Ấ" }
            ,{ hex: "&#1EA5;", name: "LATIN SMALL LETTER A WITH CIRCUMFLEX AND ACUTE", char: "ấ" }
            ,{ hex: "&#1EA6;", name: "LATIN CAPITAL LETTER A WITH CIRCUMFLEX AND GRAVE", char: "Ầ" }
            ,{ hex: "&#1EA7;", name: "LATIN SMALL LETTER A WITH CIRCUMFLEX AND GRAVE", char: "ầ" }
            ,{ hex: "&#1EA8;", name: "LATIN CAPITAL LETTER A WITH CIRCUMFLEX AND HOOK ABOVE", char: "Ẩ" }
            ,{ hex: "&#1EA9;", name: "LATIN SMALL LETTER A WITH CIRCUMFLEX AND HOOK ABOVE", char: "ẩ" }
            ,{ hex: "&#1EAA;", name: "LATIN CAPITAL LETTER A WITH CIRCUMFLEX AND TILDE", char: "Ẫ" }
            ,{ hex: "&#1EAB;", name: "LATIN SMALL LETTER A WITH CIRCUMFLEX AND TILDE", char: "ẫ" }
            ,{ hex: "&#1EAC;", name: "LATIN CAPITAL LETTER A WITH CIRCUMFLEX AND DOT BELOW", char: "Ậ" }
            ,{ hex: "&#1EAD;", name: "LATIN SMALL LETTER A WITH CIRCUMFLEX AND DOT BELOW", char: "ậ" }
            ,{ hex: "&#1EAE;", name: "LATIN CAPITAL LETTER A WITH BREVE AND ACUTE", char: "Ắ" }
            ,{ hex: "&#1EAF;", name: "LATIN SMALL LETTER A WITH BREVE AND ACUTE", char: "ắ" }
            ,{ hex: "&#1EB0;", name: "LATIN CAPITAL LETTER A WITH BREVE AND GRAVE", char: "Ằ" }
            ,{ hex: "&#1EB1;", name: "LATIN SMALL LETTER A WITH BREVE AND GRAVE", char: "ằ" }
            ,{ hex: "&#1EB2;", name: "LATIN CAPITAL LETTER A WITH BREVE AND HOOK ABOVE", char: "Ẳ" }
            ,{ hex: "&#1EB3;", name: "LATIN SMALL LETTER A WITH BREVE AND HOOK ABOVE", char: "ẳ" }
            ,{ hex: "&#1EB4;", name: "LATIN CAPITAL LETTER A WITH BREVE AND TILDE", char: "Ẵ" }
            ,{ hex: "&#1EB5;", name: "LATIN SMALL LETTER A WITH BREVE AND TILDE", char: "ẵ" }
            ,{ hex: "&#1EB6;", name: "LATIN CAPITAL LETTER A WITH BREVE AND DOT BELOW", char: "Ặ" }
            ,{ hex: "&#1EB7;", name: "LATIN SMALL LETTER A WITH BREVE AND DOT BELOW", char: "ặ" }
            ,{ hex: "&#1EB8;", name: "LATIN CAPITAL LETTER E WITH DOT BELOW", char: "Ẹ" }
            ,{ hex: "&#1EB9;", name: "LATIN SMALL LETTER E WITH DOT BELOW", char: "ẹ" }
            ,{ hex: "&#1EBA;", name: "LATIN CAPITAL LETTER E WITH HOOK ABOVE", char: "Ẻ" }
            ,{ hex: "&#1EBB;", name: "LATIN SMALL LETTER E WITH HOOK ABOVE", char: "ẻ" }
            ,{ hex: "&#1EBC;", name: "LATIN CAPITAL LETTER E WITH TILDE", char: "Ẽ" }
            ,{ hex: "&#1EBD;", name: "LATIN SMALL LETTER E WITH TILDE", char: "ẽ" }
            ,{ hex: "&#1EBE;", name: "LATIN CAPITAL LETTER E WITH CIRCUMFLEX AND ACUTE", char: "Ế" }
            ,{ hex: "&#1EBF;", name: "LATIN SMALL LETTER E WITH CIRCUMFLEX AND ACUTE", char: "ế" }
            ,{ hex: "&#1EC0;", name: "LATIN CAPITAL LETTER E WITH CIRCUMFLEX AND GRAVE", char: "Ề" }
            ,{ hex: "&#1EC1;", name: "LATIN SMALL LETTER E WITH CIRCUMFLEX AND GRAVE", char: "ề" }
            ,{ hex: "&#1EC2;", name: "LATIN CAPITAL LETTER E WITH CIRCUMFLEX AND HOOK ABOVE", char: "Ể" }
            ,{ hex: "&#1EC3;", name: "LATIN SMALL LETTER E WITH CIRCUMFLEX AND HOOK ABOVE", char: "ể" }
            ,{ hex: "&#1EC4;", name: "LATIN CAPITAL LETTER E WITH CIRCUMFLEX AND TILDE", char: "Ễ" }
            ,{ hex: "&#1EC5;", name: "LATIN SMALL LETTER E WITH CIRCUMFLEX AND TILDE", char: "ễ" }
            ,{ hex: "&#1EC6;", name: "LATIN CAPITAL LETTER E WITH CIRCUMFLEX AND DOT BELOW", char: "Ệ" }
            ,{ hex: "&#1EC7;", name: "LATIN SMALL LETTER E WITH CIRCUMFLEX AND DOT BELOW", char: "ệ" }
            ,{ hex: "&#1EC8;", name: "LATIN CAPITAL LETTER I WITH HOOK ABOVE", char: "Ỉ" }
            ,{ hex: "&#1EC9;", name: "LATIN SMALL LETTER I WITH HOOK ABOVE", char: "ỉ" }
            ,{ hex: "&#1ECA;", name: "LATIN CAPITAL LETTER I WITH DOT BELOW", char: "Ị" }
            ,{ hex: "&#1ECB;", name: "LATIN SMALL LETTER I WITH DOT BELOW", char: "ị" }
            ,{ hex: "&#1ECC;", name: "LATIN CAPITAL LETTER O WITH DOT BELOW", char: "Ọ" }
            ,{ hex: "&#1ECD;", name: "LATIN SMALL LETTER O WITH DOT BELOW", char: "ọ" }
            ,{ hex: "&#1ECE;", name: "LATIN CAPITAL LETTER O WITH HOOK ABOVE", char: "Ỏ" }
            ,{ hex: "&#1ECF;", name: "LATIN SMALL LETTER O WITH HOOK ABOVE", char: "ỏ" }
            ,{ hex: "&#1ED0;", name: "LATIN CAPITAL LETTER O WITH CIRCUMFLEX AND ACUTE", char: "Ố" }
            ,{ hex: "&#1ED1;", name: "LATIN SMALL LETTER O WITH CIRCUMFLEX AND ACUTE", char: "ố" }
            ,{ hex: "&#1ED2;", name: "LATIN CAPITAL LETTER O WITH CIRCUMFLEX AND GRAVE", char: "Ồ" }
            ,{ hex: "&#1ED3;", name: "LATIN SMALL LETTER O WITH CIRCUMFLEX AND GRAVE", char: "ồ" }
            ,{ hex: "&#1ED4;", name: "LATIN CAPITAL LETTER O WITH CIRCUMFLEX AND HOOK ABOVE", char: "Ổ" }
            ,{ hex: "&#1ED5;", name: "LATIN SMALL LETTER O WITH CIRCUMFLEX AND HOOK ABOVE", char: "ổ" }
            ,{ hex: "&#1ED6;", name: "LATIN CAPITAL LETTER O WITH CIRCUMFLEX AND TILDE", char: "Ỗ" }
            ,{ hex: "&#1ED7;", name: "LATIN SMALL LETTER O WITH CIRCUMFLEX AND TILDE", char: "ỗ" }
            ,{ hex: "&#1ED8;", name: "LATIN CAPITAL LETTER O WITH CIRCUMFLEX AND DOT BELOW", char: "Ộ" }
            ,{ hex: "&#1ED9;", name: "LATIN SMALL LETTER O WITH CIRCUMFLEX AND DOT BELOW", char: "ộ" }
            ,{ hex: "&#1EDA;", name: "LATIN CAPITAL LETTER O WITH HORN AND ACUTE", char: "Ớ" }
            ,{ hex: "&#1EDB;", name: "LATIN SMALL LETTER O WITH HORN AND ACUTE", char: "ớ" }
            ,{ hex: "&#1EDC;", name: "LATIN CAPITAL LETTER O WITH HORN AND GRAVE", char: "Ờ" }
            ,{ hex: "&#1EDD;", name: "LATIN SMALL LETTER O WITH HORN AND GRAVE", char: "ờ" }
            ,{ hex: "&#1EDE;", name: "LATIN CAPITAL LETTER O WITH HORN AND HOOK ABOVE", char: "Ở" }
            ,{ hex: "&#1EDF;", name: "LATIN SMALL LETTER O WITH HORN AND HOOK ABOVE", char: "ở" }
            ,{ hex: "&#1EE0;", name: "LATIN CAPITAL LETTER O WITH HORN AND TILDE", char: "Ỡ" }
            ,{ hex: "&#1EE1;", name: "LATIN SMALL LETTER O WITH HORN AND TILDE", char: "ỡ" }
            ,{ hex: "&#1EE2;", name: "LATIN CAPITAL LETTER O WITH HORN AND DOT BELOW", char: "Ợ" }
            ,{ hex: "&#1EE3;", name: "LATIN SMALL LETTER O WITH HORN AND DOT BELOW", char: "ợ" }
            ,{ hex: "&#1EE4;", name: "LATIN CAPITAL LETTER U WITH DOT BELOW", char: "Ụ" }
            ,{ hex: "&#1EE5;", name: "LATIN SMALL LETTER U WITH DOT BELOW", char: "ụ" }
            ,{ hex: "&#1EE6;", name: "LATIN CAPITAL LETTER U WITH HOOK ABOVE", char: "Ủ" }
            ,{ hex: "&#1EE7;", name: "LATIN SMALL LETTER U WITH HOOK ABOVE", char: "ủ" }
            ,{ hex: "&#1EE8;", name: "LATIN CAPITAL LETTER U WITH HORN AND ACUTE", char: "Ứ" }
            ,{ hex: "&#1EE9;", name: "LATIN SMALL LETTER U WITH HORN AND ACUTE", char: "ứ" }
            ,{ hex: "&#1EEA;", name: "LATIN CAPITAL LETTER U WITH HORN AND GRAVE", char: "Ừ" }
            ,{ hex: "&#1EEB;", name: "LATIN SMALL LETTER U WITH HORN AND GRAVE", char: "ừ" }
            ,{ hex: "&#1EEC;", name: "LATIN CAPITAL LETTER U WITH HORN AND HOOK ABOVE", char: "Ử" }
            ,{ hex: "&#1EED;", name: "LATIN SMALL LETTER U WITH HORN AND HOOK ABOVE", char: "ử" }
            ,{ hex: "&#1EEE;", name: "LATIN CAPITAL LETTER U WITH HORN AND TILDE", char: "Ữ" }
            ,{ hex: "&#1EEF;", name: "LATIN SMALL LETTER U WITH HORN AND TILDE", char: "ữ" }
            ,{ hex: "&#1EF0;", name: "LATIN CAPITAL LETTER U WITH HORN AND DOT BELOW", char: "Ự" }
            ,{ hex: "&#1EF1;", name: "LATIN SMALL LETTER U WITH HORN AND DOT BELOW", char: "ự" }
            ,{ hex: "&#1EF2;", name: "LATIN CAPITAL LETTER Y WITH GRAVE (present in WGL4)", char: "Ỳ" }
            ,{ hex: "&#1EF3;", name: "LATIN SMALL LETTER Y WITH GRAVE (present in WGL4)", char: "ỳ" }
            ,{ hex: "&#1EF4;", name: "LATIN CAPITAL LETTER Y WITH DOT BELOW", char: "Ỵ" }
            ,{ hex: "&#1EF5;", name: "LATIN SMALL LETTER Y WITH DOT BELOW", char: "ỵ" }
            ,{ hex: "&#1EF6;", name: "LATIN CAPITAL LETTER Y WITH HOOK ABOVE", char: "Ỷ" }
            ,{ hex: "&#1EF7;", name: "LATIN SMALL LETTER Y WITH HOOK ABOVE", char: "ỷ" }
            ,{ hex: "&#1EF8;", name: "LATIN CAPITAL LETTER Y WITH TILDE", char: "Ỹ" }
            ,{ hex: "&#1EF9;", name: "LATIN SMALL LETTER Y WITH TILDE", char: "ỹ" }
            ,{ hex: "&#1EFA;", name: "LATIN CAPITAL LETTER MIDDLE-WELSH LL", char: "Ỻ" }
            ,{ hex: "&#1EFB;", name: "LATIN SMALL LETTER MIDDLE-WELSH LL", char: "ỻ" }
            ,{ hex: "&#1EFC;", name: "LATIN CAPITAL LETTER MIDDLE-WELSH V", char: "Ỽ" }
            ,{ hex: "&#1EFD;", name: "LATIN SMALL LETTER MIDDLE-WELSH V", char: "ỽ" }
            ,{ hex: "&#1EFE;", name: "LATIN CAPITAL LETTER Y WITH LOOP", char: "Ỿ" }
            ,{ hex: "&#1EFF;", name: "LATIN SMALL LETTER Y WITH LOOP", char: "ỿ" }
        ],

        "MATH": [
           { hex: "&#2242;", name: "MINUS TILDE", char: "≂" }
           ,{ hex: "&#2243;", name: "ASYMPTOTICALLY EQUAL TO", char: "≃" }
           ,{ hex: "&#2244;", name: "NOT ASYMPTOTICALLY EQUAL TO", char: "≄" }
           ,{ hex: "&#2245;" , entity: "&cong;", name: "APPROXIMATELY EQUAL TO", char: "≅" }
           ,{ hex: "&#2246;", name: "APPROXIMATELY BUT NOT ACTUALLY EQUAL TO", char: "≆" }
           ,{ hex: "&#2247;", name: "NEITHER APPROXIMATELY NOR ACTUALLY EQUAL TO", char: "≇" }
           ,{ hex: "&#2248;" , entity: "&asymp;", name: "ALMOST EQUAL TO", char: "≈" }
           ,{ hex: "&#2249;", name: "NOT ALMOST EQUAL TO", char: "≉" }
           ,{ hex: "&#224A;", name: "ALMOST EQUAL OR EQUAL TO", char: "≊" }
           ,{ hex: "&#2260;" , entity: "&ne;", name: "NOT EQUAL TO", char: "≠" }
           ,{ hex: "&#2261;" , entity: "&equiv;", name: "IDENTICAL TO", char: "≡" }
           ,{ hex: "&#2262;", name: "NOT IDENTICAL TO", char: "≢" }
           ,{ hex: "&#2264;" , entity: "&le;", name: "LESS-THAN OR EQUAL TO", char: "≤" }
           ,{ hex: "&#2265;" , entity: "&ge;", name: "GREATER-THAN OR EQUAL TO", char: "≥" }
           ,{ hex: "&#2266;", name: "LESS-THAN OVER EQUAL TO", char: "≦" }
           ,{ hex: "&#2267;", name: "GREATER-THAN OVER EQUAL TO", char: "≧" }
           ,{ hex: "&#2268;", name: "LESS-THAN BUT NOT EQUAL TO", char: "≨" }
           ,{ hex: "&#2269;", name: "GREATER-THAN BUT NOT EQUAL TO", char: "≩" }
           ,{ hex: "&#226A;", name: "MUCH LESS-THAN", char: "≪" }
           ,{ hex: "&#226B;", name: "MUCH GREATER-THAN", char: "≫" }
           ,{ hex: "&#226C;", name: "BETWEEN", char: "≬" }
           ,{ hex: "&#2282;" , entity: "&sub;", name: "SUBSET OF", char: "⊂" }
           ,{ hex: "&#2283;" , entity: "&sup;", name: "SUPERSET OF", char: "⊃" }
           ,{ hex: "&#2284;" , entity: "&nsub;", name: "NOT A SUBSET OF", char: "⊄" }
           ,{ hex: "&#2285;", name: "NOT A SUPERSET OF", char: "⊅" }
           ,{ hex: "&#2286;" , entity: "&sube;", name: "SUBSET OF OR EQUAL TO", char: "⊆" }
           ,{ hex: "&#2287;" , entity: "&supe;", name: "SUPERSET OF OR EQUAL TO", char: "⊇" }
           ,{ hex: "&#2288;", name: "NEITHER A SUBSET OF NOR EQUAL TO", char: "⊈" }
           ,{ hex: "&#2289;", name: "NEITHER A SUPERSET OF NOR EQUAL TO", char: "⊉" }
           ,{ hex: "&#228A;", name: "SUBSET OF WITH NOT EQUAL TO", char: "⊊" }
           ,{ hex: "&#228B;", name: "SUPERSET OF WITH NOT EQUAL TO", char: "⊋" }
           ,{ hex: "&#22A5;" , entity: "&perp;", name: "UP TACK", char: "⊥" }
           ,{ hex: "&#22BB;", name: "XOR", char: "⊻" }
           ,{ hex: "&#22BC;", name: "NAND", char: "⊼" }
           ,{ hex: "&#22BD;", name: "NOR", char: "⊽" }
           ,{ hex: "&#22BE;", name: "RIGHT ANGLE WITH ARC", char: "⊾" }
           ,{ hex: "&#22BF;", name: "RIGHT TRIANGLE", char: "⊿" }
           ,{ hex: "&#22C0;", name: "N-ARY LOGICAL AND", char: "⋀" }
           ,{ hex: "&#22C1;", name: "N-ARY LOGICAL OR", char: "⋁" }
           ,{ hex: "&#22C2;", name: "N-ARY INTERSECTION", char: "⋂" }
           ,{ hex: "&#22C3;", name: "N-ARY UNION", char: "⋃" }

           ,{ hex: "&#2153;", name: "VULGAR FRACTION ONE THIRD", char: "⅓" }
           ,{ hex: "&#2154;", name: "VULGAR FRACTION TWO THIRDS", char: "⅔" }
           ,{ hex: "&#2155;", name: "VULGAR FRACTION ONE FIFTH", char: "⅕" }
           ,{ hex: "&#2156;", name: "VULGAR FRACTION TWO FIFTHS", char: "⅖" }
           ,{ hex: "&#2157;", name: "VULGAR FRACTION THREE FIFTHS", char: "⅗" }
           ,{ hex: "&#2158;", name: "VULGAR FRACTION FOUR FIFTHS", char: "⅘" }
           ,{ hex: "&#2159;", name: "VULGAR FRACTION ONE SIXTH", char: "⅙" }
           ,{ hex: "&#215A;", name: "VULGAR FRACTION FIVE SIXTHS", char: "⅚" }
           ,{ hex: "&#215B;", name: "VULGAR FRACTION ONE EIGHTH (present in WGL4)", char: "⅛" }
           ,{ hex: "&#215C;", name: "VULGAR FRACTION THREE EIGHTHS (present in WGL4)", char: "⅜" }
           ,{ hex: "&#215D;", name: "VULGAR FRACTION FIVE EIGHTHS (present in WGL4)", char: "⅝" }
           ,{ hex: "&#215E;", name: "VULGAR FRACTION SEVEN EIGHTHS (present in WGL4)", char: "⅞" }
           ,{ hex: "&#215F;", name: "FRACTION NUMERATOR ONE", char: "⅟" }
           ,{ hex: "&#2160;", name: "ROMAN NUMERAL ONE", char: "Ⅰ" }
           ,{ hex: "&#2161;", name: "ROMAN NUMERAL TWO", char: "Ⅱ" }
           ,{ hex: "&#2162;", name: "ROMAN NUMERAL THREE", char: "Ⅲ" }
           ,{ hex: "&#2163;", name: "ROMAN NUMERAL FOUR", char: "Ⅳ" }
           ,{ hex: "&#2164;", name: "ROMAN NUMERAL FIVE", char: "Ⅴ" }
           ,{ hex: "&#2165;", name: "ROMAN NUMERAL SIX", char: "Ⅵ" }
           ,{ hex: "&#2166;", name: "ROMAN NUMERAL SEVEN", char: "Ⅶ" }
           ,{ hex: "&#2167;", name: "ROMAN NUMERAL EIGHT", char: "Ⅷ" }
           ,{ hex: "&#2168;", name: "ROMAN NUMERAL NINE", char: "Ⅸ" }
           ,{ hex: "&#2169;", name: "ROMAN NUMERAL TEN", char: "Ⅹ" }
           ,{ hex: "&#216A;", name: "ROMAN NUMERAL ELEVEN", char: "Ⅺ" }
           ,{ hex: "&#216B;", name: "ROMAN NUMERAL TWELVE", char: "Ⅻ" }
           ,{ hex: "&#216C;", name: "ROMAN NUMERAL FIFTY", char: "Ⅼ" }
           ,{ hex: "&#216D;", name: "ROMAN NUMERAL ONE HUNDRED", char: "Ⅽ" }
           ,{ hex: "&#216E;", name: "ROMAN NUMERAL FIVE HUNDRED", char: "Ⅾ" }
           ,{ hex: "&#216F;", name: "ROMAN NUMERAL ONE THOUSAND", char: "Ⅿ" }
           ,{ hex: "&#2170;", name: "SMALL ROMAN NUMERAL ONE", char: "ⅰ" }
           ,{ hex: "&#2171;", name: "SMALL ROMAN NUMERAL TWO", char: "ⅱ" }
           ,{ hex: "&#2172;", name: "SMALL ROMAN NUMERAL THREE", char: "ⅲ" }
           ,{ hex: "&#2173;", name: "SMALL ROMAN NUMERAL FOUR", char: "ⅳ" }
           ,{ hex: "&#2174;", name: "SMALL ROMAN NUMERAL FIVE", char: "ⅴ" }
           ,{ hex: "&#2175;", name: "SMALL ROMAN NUMERAL SIX", char: "ⅵ" }
           ,{ hex: "&#2176;", name: "SMALL ROMAN NUMERAL SEVEN", char: "ⅶ" }
           ,{ hex: "&#2177;", name: "SMALL ROMAN NUMERAL EIGHT", char: "ⅷ" }
           ,{ hex: "&#2178;", name: "SMALL ROMAN NUMERAL NINE", char: "ⅸ" }
           ,{ hex: "&#2179;", name: "SMALL ROMAN NUMERAL TEN", char: "ⅹ" }
           ,{ hex: "&#217A;", name: "SMALL ROMAN NUMERAL ELEVEN", char: "ⅺ" }
           ,{ hex: "&#217B;", name: "SMALL ROMAN NUMERAL TWELVE", char: "ⅻ" }
           ,{ hex: "&#217C;", name: "SMALL ROMAN NUMERAL FIFTY", char: "ⅼ" }
           ,{ hex: "&#217D;", name: "SMALL ROMAN NUMERAL ONE HUNDRED", char: "ⅽ" }
           ,{ hex: "&#217E;", name: "SMALL ROMAN NUMERAL FIVE HUNDRED", char: "ⅾ" }
           ,{ hex: "&#217F;", name: "SMALL ROMAN NUMERAL ONE THOUSAND", char: "ⅿ" }

           ,{ hex: "&#2A2F;", name: "VECTOR OR CROSS PRODUCT", char: "⨯" }
           ,{ hex: "&#2460;", name: "CIRCLED DIGIT ONE", char: "①" }
           ,{ hex: "&#2461;", name: "CIRCLED DIGIT TWO", char: "②" }
           ,{ hex: "&#2462;", name: "CIRCLED DIGIT THREE", char: "③" }
           ,{ hex: "&#2463;", name: "CIRCLED DIGIT FOUR", char: "④" }
           ,{ hex: "&#2464;", name: "CIRCLED DIGIT FIVE", char: "⑤" }
           ,{ hex: "&#2465;", name: "CIRCLED DIGIT SIX", char: "⑥" }
           ,{ hex: "&#2466;", name: "CIRCLED DIGIT SEVEN", char: "⑦" }
           ,{ hex: "&#2467;", name: "CIRCLED DIGIT EIGHT", char: "⑧" }
           ,{ hex: "&#2468;", name: "CIRCLED DIGIT NINE", char: "⑨" }
           ,{ hex: "&#2469;", name: "CIRCLED NUMBER TEN", char: "⑩" }
           ,{ hex: "&#246A;", name: "CIRCLED NUMBER ELEVEN", char: "⑪" }
           ,{ hex: "&#246B;", name: "CIRCLED NUMBER TWELVE", char: "⑫" }
           ,{ hex: "&#246C;", name: "CIRCLED NUMBER THIRTEEN", char: "⑬" }
           ,{ hex: "&#246D;", name: "CIRCLED NUMBER FOURTEEN", char: "⑭" }
           ,{ hex: "&#246E;", name: "CIRCLED NUMBER FIFTEEN", char: "⑮" }
           ,{ hex: "&#246F;", name: "CIRCLED NUMBER SIXTEEN", char: "⑯" }
           ,{ hex: "&#2470;", name: "CIRCLED NUMBER SEVENTEEN", char: "⑰" }
           ,{ hex: "&#2471;", name: "CIRCLED NUMBER EIGHTEEN", char: "⑱" }
           ,{ hex: "&#2472;", name: "CIRCLED NUMBER NINETEEN", char: "⑲" }
           ,{ hex: "&#2473;", name: "CIRCLED NUMBER TWENTY", char: "⑳" }
           ,{ hex: "&#24B6;", name: "CIRCLED LATIN CAPITAL LETTER A", char: "Ⓐ" }
           ,{ hex: "&#24B7;", name: "CIRCLED LATIN CAPITAL LETTER B", char: "Ⓑ" }
           ,{ hex: "&#24B8;", name: "CIRCLED LATIN CAPITAL LETTER C", char: "Ⓒ" }
           ,{ hex: "&#24B9;", name: "CIRCLED LATIN CAPITAL LETTER D", char: "Ⓓ" }
           ,{ hex: "&#24BA;", name: "CIRCLED LATIN CAPITAL LETTER E", char: "Ⓔ" }
           ,{ hex: "&#24BB;", name: "CIRCLED LATIN CAPITAL LETTER F", char: "Ⓕ" }
           ,{ hex: "&#24BC;", name: "CIRCLED LATIN CAPITAL LETTER G", char: "Ⓖ" }
           ,{ hex: "&#24BD;", name: "CIRCLED LATIN CAPITAL LETTER H", char: "Ⓗ" }
           ,{ hex: "&#24BE;", name: "CIRCLED LATIN CAPITAL LETTER I", char: "Ⓘ" }
           ,{ hex: "&#24BF;", name: "CIRCLED LATIN CAPITAL LETTER J", char: "Ⓙ" }
           ,{ hex: "&#24C0;", name: "CIRCLED LATIN CAPITAL LETTER K", char: "Ⓚ" }
           ,{ hex: "&#24C1;", name: "CIRCLED LATIN CAPITAL LETTER L", char: "Ⓛ" }
           ,{ hex: "&#24C2;", name: "CIRCLED LATIN CAPITAL LETTER M", char: "Ⓜ" }
           ,{ hex: "&#24C3;", name: "CIRCLED LATIN CAPITAL LETTER N", char: "Ⓝ" }
           ,{ hex: "&#24C4;", name: "CIRCLED LATIN CAPITAL LETTER O", char: "Ⓞ" }
           ,{ hex: "&#24C5;", name: "CIRCLED LATIN CAPITAL LETTER P", char: "Ⓟ" }
           ,{ hex: "&#24C6;", name: "CIRCLED LATIN CAPITAL LETTER Q", char: "Ⓠ" }
           ,{ hex: "&#24C7;", name: "CIRCLED LATIN CAPITAL LETTER R", char: "Ⓡ" }
           ,{ hex: "&#24C8;", name: "CIRCLED LATIN CAPITAL LETTER S", char: "Ⓢ" }
           ,{ hex: "&#24C9;", name: "CIRCLED LATIN CAPITAL LETTER T", char: "Ⓣ" }
           ,{ hex: "&#24CA;", name: "CIRCLED LATIN CAPITAL LETTER U", char: "Ⓤ" }
           ,{ hex: "&#24CB;", name: "CIRCLED LATIN CAPITAL LETTER V", char: "Ⓥ" }
           ,{ hex: "&#24CC;", name: "CIRCLED LATIN CAPITAL LETTER W", char: "Ⓦ" }
           ,{ hex: "&#24CD;", name: "CIRCLED LATIN CAPITAL LETTER X", char: "Ⓧ" }
           ,{ hex: "&#24CE;", name: "CIRCLED LATIN CAPITAL LETTER Y", char: "Ⓨ" }
           ,{ hex: "&#24CF;", name: "CIRCLED LATIN CAPITAL LETTER Z", char: "Ⓩ" }
           ,{ hex: "&#24D0;", name: "CIRCLED LATIN SMALL LETTER A", char: "ⓐ" }
           ,{ hex: "&#24D1;", name: "CIRCLED LATIN SMALL LETTER B", char: "ⓑ" }
           ,{ hex: "&#24D2;", name: "CIRCLED LATIN SMALL LETTER C", char: "ⓒ" }
           ,{ hex: "&#24D3;", name: "CIRCLED LATIN SMALL LETTER D", char: "ⓓ" }
           ,{ hex: "&#24D4;", name: "CIRCLED LATIN SMALL LETTER E", char: "ⓔ" }
           ,{ hex: "&#24D5;", name: "CIRCLED LATIN SMALL LETTER F", char: "ⓕ" }
           ,{ hex: "&#24D6;", name: "CIRCLED LATIN SMALL LETTER G", char: "ⓖ" }
           ,{ hex: "&#24D7;", name: "CIRCLED LATIN SMALL LETTER H", char: "ⓗ" }
           ,{ hex: "&#24D8;", name: "CIRCLED LATIN SMALL LETTER I", char: "ⓘ" }
           ,{ hex: "&#24D9;", name: "CIRCLED LATIN SMALL LETTER J", char: "ⓙ" }
           ,{ hex: "&#24DA;", name: "CIRCLED LATIN SMALL LETTER K", char: "ⓚ" }
           ,{ hex: "&#24DB;", name: "CIRCLED LATIN SMALL LETTER L", char: "ⓛ" }
           ,{ hex: "&#24DC;", name: "CIRCLED LATIN SMALL LETTER M", char: "ⓜ" }
           ,{ hex: "&#24DD;", name: "CIRCLED LATIN SMALL LETTER N", char: "ⓝ" }
           ,{ hex: "&#24DE;", name: "CIRCLED LATIN SMALL LETTER O", char: "ⓞ" }
           ,{ hex: "&#24DF;", name: "CIRCLED LATIN SMALL LETTER P", char: "ⓟ" }
           ,{ hex: "&#24E0;", name: "CIRCLED LATIN SMALL LETTER Q", char: "ⓠ" }
           ,{ hex: "&#24E1;", name: "CIRCLED LATIN SMALL LETTER R", char: "ⓡ" }
           ,{ hex: "&#24E2;", name: "CIRCLED LATIN SMALL LETTER S", char: "ⓢ" }
           ,{ hex: "&#24E3;", name: "CIRCLED LATIN SMALL LETTER T", char: "ⓣ" }
           ,{ hex: "&#24E4;", name: "CIRCLED LATIN SMALL LETTER U", char: "ⓤ" }
           ,{ hex: "&#24E5;", name: "CIRCLED LATIN SMALL LETTER V", char: "ⓥ" }
           ,{ hex: "&#24E6;", name: "CIRCLED LATIN SMALL LETTER W", char: "ⓦ" }
           ,{ hex: "&#24E7;", name: "CIRCLED LATIN SMALL LETTER X", char: "ⓧ" }
           ,{ hex: "&#24E8;", name: "CIRCLED LATIN SMALL LETTER Y", char: "ⓨ" }
           ,{ hex: "&#24E9;", name: "CIRCLED LATIN SMALL LETTER Z", char: "ⓩ" }
           ,{ hex: "&#24EA;", name: "CIRCLED DIGIT ZERO", char: "⓪" }
        ],

        "MISC": [
            {char: "♀" }
            ,{char: "♂" }
            ,{char: "☿" }
            ,{char: "♁" }
            ,{char: "⚢" }
            ,{char: "⚣" }
            ,{char: "⚤" }
            ,{char: "⚥" }
            ,{char: "⚦" }
            ,{char: "⚧" }
            ,{char: "⚨" }
            ,{char: "⚩" }

            ,{char: "☤" }
            ,{char: "⚕" }
            ,{char: "⚒" }
            ,{char: "⚓" }
            ,{char: "⚙" }
            ,{char: "⚜" }

            ,{char: "☐" }
            ,{char: "☑" }
            ,{char: "☒" }
            ,{char: "✓" }
            ,{char: "✔" }
            ,{char: "✕" }
            ,{char: "✖" }
            ,{char: "✗" }
            ,{char: "✘" }
            ,{char: "✚" }

            ,{char: "➘" }
            ,{char: "➙" }
            ,{char: "➚" }
            ,{char: "➝" }
            ,{char: "➜" }
            ,{char: "➟" }
            ,{char: "➡" }
            ,{char: "➢" }
            ,{char: "➤" }
            ,{char: "➩" }
            ,{char: "⟲" }
            ,{char: "⟳" }
            ,{char: "⟷" }
            ,{char: "⟵" }
            ,{char: "⟶" }

            ,{char: "™" }

            ,{ entity: "&copy;", hex: "&#00A9;", name: "COPYRIGHT SIGN", char: "©" }
            ,{ entity: "&reg;", hex: "&#00AE;", name: "REGISTERED SIGN", char: "®" }
            ,{char: "$" }
            ,{char: "€" }
            ,{ entity: "&cent;", hex: "&#00A2;", name: "CENT SIGN", char: "¢" }
            ,{ entity: "&pound;", hex: "&#00A3;", name: "POUND SIGN", char: "£" }
            ,{ entity: "&curren;", hex: "&#00A4;", name: "CURRENCY SIGN", char: "¤" }
            ,{ entity: "&yen;", hex: "&#00A5;", name: "YEN SIGN", char: "¥" }

            ,{ char: "❝" }
            ,{ char:"❞" }
            ,{ char:"∞" }
            ,{ char:"ø" }
            ,{ char:"≠" }
            ,{ char:"∫" }
            ,{ char:"≈" }
            ,{ char:"∴" }
            ,{ char:"∝" }
            ,{ char:"∂" }
            ,{ char:"Ω" }
            ,{ char:"Φ" }
            ,{ char:"Ψ" }
            ,{ char:"λ" }
            ,{ char:"ϴ" }
            ,{ char:"ω" }
            ,{ char:"ꝏ" }
            ,{ char:"ᵠ" }

            ,{ entity: "&uml;", hex: "&#00A8;", name: "DIAERESIS", char: "¨" }
            ,{ entity: "&not;", hex: "&#00AC;", name: "NOT SIGN", char: "¬" }
            ,{ entity: "&macr;", hex: "&#00AF;", name: "MACRON", char: "¯" }
            ,{ entity: "&acute;", hex: "&#00B4;", name: "ACUTE ACCENT", char: "´" }
            ,{ entity: "&middot;", hex: "&#00B7;", name: "MIDDLE DOT", char: "·" }
            ,{ entity: "&plusmn;", hex: "&#00B1;", name: "PLUS-MINUS SIGN", char: "±" }
            ,{ entity: "&frac14;", hex: "&#00BC;", name: "VULGAR FRACTION ONE QUARTER", char: "¼" }
            ,{ entity: "&frac12;", hex: "&#00BD;", name: "VULGAR FRACTION ONE HALF", char: "½" }
            ,{ entity: "&frac34;", hex: "&#00BE;", name: "VULGAR FRACTION THREE QUARTERS", char: "¾" }
            ,{ entity: "&iquest;", hex: "&#00BF;", name: "INVERTED QUESTION MARK", char: "¿" }
            ,{ entity: "&iexcl;", hex: "&#00A1;", name: "INVERTED EXCLAMATION MARK", char: "¡" }
            ,{ entity: "&brvbar;", hex: "&#00A6;", name: "BROKEN BAR", char: "¦" }

            ,{ entity: "&laquo;", hex: "&#00AB;", name: "LEFT-POINTING DOUBLE ANGLE QUOTATION MARK", char: "«" }
            ,{ entity: "&raquo;", hex: "&#00BB;", name: "RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK", char: "»" }

            ,{ hex: "&#25A0;", name: "BLACK SQUARE", char: "■" }
            ,{ hex: "&#25A1;", name: "WHITE SQUARE", char: "□" }
            ,{ hex: "&#25A2;", name: "WHITE SQUARE WITH ROUNDED CORNERS", char: "▢" }
            ,{ hex: "&#25AA;", name: "BLACK SMALL SQUARE", char: "▪" }
            ,{ hex: "&#25AB;", name: "WHITE SMALL SQUARE", char: "▫" }
            ,{ hex: "&#25AC;", name: "BLACK RECTANGLE", char: "▬" }
            ,{ hex: "&#25AD;", name: "WHITE RECTANGLE", char: "▭" }
            ,{ hex: "&#25B2;", name: "BLACK UP-POINTING TRIANGLE", char: "▲" }
            ,{ hex: "&#25B3;", name: "WHITE UP-POINTING TRIANGLE", char: "△" }
            ,{ hex: "&#25B4;", name: "BLACK UP-POINTING SMALL TRIANGLE", char: "▴" }
            ,{ hex: "&#25B5;", name: "WHITE UP-POINTING SMALL TRIANGLE", char: "▵" }
            ,{ hex: "&#25B6;", name: "BLACK RIGHT-POINTING TRIANGLE", char: "▶" }
            ,{ hex: "&#25B7;", name: "WHITE RIGHT-POINTING TRIANGLE", char: "▷" }
            ,{ hex: "&#25B8;", name: "BLACK RIGHT-POINTING SMALL TRIANGLE", char: "▸" }
            ,{ hex: "&#25B9;", name: "WHITE RIGHT-POINTING SMALL TRIANGLE", char: "▹" }
            ,{ hex: "&#25BA;", name: "BLACK RIGHT-POINTING POINTER", char: "►" }
            ,{ hex: "&#25BB;", name: "WHITE RIGHT-POINTING POINTER", char: "▻" }
            ,{ hex: "&#25BC;", name: "BLACK DOWN-POINTING TRIANGLE", char: "▼" }
            ,{ hex: "&#25BD;", name: "WHITE DOWN-POINTING TRIANGLE", char: "▽" }
            ,{ hex: "&#25BE;", name: "BLACK DOWN-POINTING SMALL TRIANGLE", char: "▾" }
            ,{ hex: "&#25BF;", name: "WHITE DOWN-POINTING SMALL TRIANGLE", char: "▿" }
            ,{ hex: "&#25C0;", name: "BLACK LEFT-POINTING TRIANGLE", char: "◀" }
            ,{ hex: "&#25C1;", name: "WHITE LEFT-POINTING TRIANGLE", char: "◁" }
            ,{ hex: "&#25C2;", name: "BLACK LEFT-POINTING SMALL TRIANGLE", char: "◂" }
            ,{ hex: "&#25C3;", name: "WHITE LEFT-POINTING SMALL TRIANGLE", char: "◃" }
            ,{ hex: "&#25C4;", name: "BLACK LEFT-POINTING POINTER", char: "◄" }
            ,{ hex: "&#25C5;", name: "WHITE LEFT-POINTING POINTER", char: "◅" }
            ,{ hex: "&#25C6;", name: "BLACK DIAMOND", char: "◆" }
            ,{ hex: "&#25C7;", name: "WHITE DIAMOND", char: "◇" }
            ,{ hex: "&#25C8;", name: "WHITE DIAMOND CONTAINING BLACK SMALL DIAMOND", char: "◈" }
            ,{ entity: "&loz;", hex: "&#25CA;", name: "LOZENGE", char: "◊" }
            ,{ hex: "&#25CB;", name: "WHITE CIRCLE", char: "○" }
            ,{ hex: "&#25CF;", name: "BLACK CIRCLE", char: "●" }
                        ,{ hex: "&#25E6;", name: "WHITE BULLET", char: "◦" }
            ,{ hex: "&#25EF;", name: "LARGE CIRCLE", char: "◯" }
            ,{ hex: "&#25FB;", name: "WHITE MEDIUM SQUARE", char: "◻" }
            ,{ hex: "&#25FC;", name: "BLACK MEDIUM SQUARE", char: "◼" }
            ,{ hex: "&#25FD;", name: "WHITE MEDIUM SMALL SQUARE", char: "◽" }
            ,{ hex: "&#25FE;", name: "BLACK MEDIUM SMALL SQUARE", char: "◾" }

            ,{ hex: "&#263C;", name: "WHITE SUN WITH RAYS", char: "☼" }
            ,{ hex: "&#263D;", name: "FIRST QUARTER MOON", char: "☽" }
            ,{ hex: "&#263E;", name: "LAST QUARTER MOON", char: "☾" }
            ,{ hex: "&#263F;", name: "MERCURY", char: "☿" }
            ,{ hex: "&#2640;", name: "FEMALE SIGN", char: "♀" }
            ,{ hex: "&#2641;", name: "EARTH", char: "♁" }
            ,{ hex: "&#2642;", name: "MALE SIGN", char: "♂" }
            ,{ hex: "&#2643;", name: "JUPITER", char: "♃" }
            ,{ hex: "&#2644;", name: "SATURN", char: "♄" }
            ,{ hex: "&#2645;", name: "URANUS", char: "♅" }
            ,{ hex: "&#2646;", name: "NEPTUNE", char: "♆" }
            ,{ hex: "&#2647;", name: "PLUTO", char: "♇" }
            ,{ hex: "&#2648;", name: "ARIES", char: "♈" }
            ,{ hex: "&#2649;", name: "TAURUS", char: "♉" }
            ,{ hex: "&#264A;", name: "GEMINI", char: "♊" }
            ,{ hex: "&#264B;", name: "CANCER", char: "♋" }
            ,{ hex: "&#264C;", name: "LEO", char: "♌" }
            ,{ hex: "&#264D;", name: "VIRGO", char: "♍" }
            ,{ hex: "&#264E;", name: "LIBRA", char: "♎" }
            ,{ hex: "&#264F;", name: "SCORPIUS", char: "♏" }
            ,{ hex: "&#2650;", name: "SAGITTARIUS", char: "♐" }
            ,{ hex: "&#2651;", name: "CAPRICORN", char: "♑" }
            ,{ hex: "&#2652;", name: "AQUARIUS", char: "♒" }
            ,{ hex: "&#2653;", name: "PISCES", char: "♓" }
            ,{ hex: "&#2669;", name: "QUARTER NOTE", char: "♩" }
            ,{ hex: "&#266A;", name: "EIGHTH NOTE", char: "♪" }
            ,{ hex: "&#266B;", name: "BEAMED EIGHTH NOTES", char: "♫" }
            ,{ hex: "&#266C;", name: "BEAMED SIXTEENTH NOTES", char: "♬" }
            ,{ hex: "&#266D;", name: "MUSIC FLAT SIGN", char: "♭" }
            ,{ hex: "&#266E;", name: "MUSIC NATURAL SIGN", char: "♮" }
            ,{ hex: "&#266F;", name: "MUSIC SHARP SIGN", char: "♯" }
            ,{ hex: "&#26A2;", name: "DOUBLED FEMALE SIGN", char: "⚢" }
            ,{ hex: "&#26A3;", name: "DOUBLED MALE SIGN", char: "⚣" }
            ,{ hex: "&#26A4;", name: "INTERLOCKED FEMALE AND MALE SIGN", char: "⚤" }
            ,{ hex: "&#26A5;", name: "MALE AND FEMALE SIGN", char: "⚥" }
            ,{ hex: "&#26A6;", name: "MALE WITH STROKE SIGN", char: "⚦" }
            ,{ hex: "&#26A7;", name: "MALE WITH STROKE AND MALE AND FEMALE SIGN", char: "⚧" }
            ,{ hex: "&#26A8;", name: "VERTICAL MALE WITH STROKE SIGN", char: "⚨" }
            ,{ hex: "&#26A9;", name: "HORIZONTAL MALE WITH STROKE SIGN", char: "⚩" }
            ,{ hex: "&#26AD;", name: "MARRIAGE SYMBOL", char: "⚭" }
            ,{ hex: "&#26AE;", name: "DIVORCE SYMBOL", char: "⚮" }


            ,{ hex: "&#276A;", name: "MEDIUM FLATTENED LEFT PARENTHESIS ORNAMENT", char: "❪" }
            ,{ hex: "&#276B;", name: "MEDIUM FLATTENED RIGHT PARENTHESIS ORNAMENT", char: "❫" }
            ,{ hex: "&#276C;", name: "MEDIUM LEFT-POINTING ANGLE BRACKET ORNAMENT", char: "❬" }
            ,{ hex: "&#276D;", name: "MEDIUM RIGHT-POINTING ANGLE BRACKET ORNAMENT", char: "❭" }
            ,{ hex: "&#276E;", name: "HEAVY LEFT-POINTING ANGLE QUOTATION MARK ORNAMENT", char: "❮" }
            ,{ hex: "&#276F;", name: "HEAVY RIGHT-POINTING ANGLE QUOTATION MARK ORNAMENT", char: "❯" }
            ,{ hex: "&#2770;", name: "HEAVY LEFT-POINTING ANGLE BRACKET ORNAMENT", char: "❰" }
            ,{ hex: "&#2771;", name: "HEAVY RIGHT-POINTING ANGLE BRACKET ORNAMENT", char: "❱" }
            ,{ hex: "&#2772;", name: "LIGHT LEFT TORTOISE SHELL BRACKET ORNAMENT", char: "❲" }
            ,{ hex: "&#2773;", name: "LIGHT RIGHT TORTOISE SHELL BRACKET ORNAMENT", char: "❳" }
            ,{ hex: "&#2774;", name: "MEDIUM LEFT CURLY BRACKET ORNAMENT", char: "❴" }
            ,{ hex: "&#2775;", name: "MEDIUM RIGHT CURLY BRACKET ORNAMENT", char: "❵" }
            ,{ hex: "&#2776;", name: "DINGBAT NEGATIVE CIRCLED DIGIT ONE", char: "❶" }
            ,{ hex: "&#2777;", name: "DINGBAT NEGATIVE CIRCLED DIGIT TWO", char: "❷" }
            ,{ hex: "&#2778;", name: "DINGBAT NEGATIVE CIRCLED DIGIT THREE", char: "❸" }
            ,{ hex: "&#2779;", name: "DINGBAT NEGATIVE CIRCLED DIGIT FOUR", char: "❹" }
            ,{ hex: "&#277A;", name: "DINGBAT NEGATIVE CIRCLED DIGIT FIVE", char: "❺" }
            ,{ hex: "&#277B;", name: "DINGBAT NEGATIVE CIRCLED DIGIT SIX", char: "❻" }
            ,{ hex: "&#277C;", name: "DINGBAT NEGATIVE CIRCLED DIGIT SEVEN", char: "❼" }
            ,{ hex: "&#277D;", name: "DINGBAT NEGATIVE CIRCLED DIGIT EIGHT", char: "❽" }
            ,{ hex: "&#277E;", name: "DINGBAT NEGATIVE CIRCLED DIGIT NINE", char: "❾" }
            ,{ hex: "&#277F;", name: "DINGBAT NEGATIVE CIRCLED NUMBER TEN", char: "❿" }
            ,{ hex: "&#2780;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT ONE", char: "➀" }
            ,{ hex: "&#2781;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT TWO", char: "➁" }
            ,{ hex: "&#2782;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT THREE", char: "➂" }
            ,{ hex: "&#2783;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT FOUR", char: "➃" }
            ,{ hex: "&#2784;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT FIVE", char: "➄" }
            ,{ hex: "&#2785;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT SIX", char: "➅" }
            ,{ hex: "&#2786;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT SEVEN", char: "➆" }
            ,{ hex: "&#2787;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT EIGHT", char: "➇" }
            ,{ hex: "&#2788;", name: "DINGBAT CIRCLED SANS-SERIF DIGIT NINE", char: "➈" }
            ,{ hex: "&#2789;", name: "DINGBAT CIRCLED SANS-SERIF NUMBER TEN", char: "➉" }
            ,{ hex: "&#278A;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT ONE", char: "➊" }
            ,{ hex: "&#278B;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT TWO", char: "➋" }
            ,{ hex: "&#278C;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT THREE", char: "➌" }
            ,{ hex: "&#278D;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT FOUR", char: "➍" }
            ,{ hex: "&#278E;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT FIVE", char: "➎" }
            ,{ hex: "&#278F;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT SIX", char: "➏" }
            ,{ hex: "&#2790;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT SEVEN", char: "➐" }
            ,{ hex: "&#2791;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT EIGHT", char: "➑" }
            ,{ hex: "&#2792;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF DIGIT NINE", char: "➒" }
            ,{ hex: "&#2793;", name: "DINGBAT NEGATIVE CIRCLED SANS-SERIF NUMBER TEN", char: "➓" }
        ],

        "ARROWS": [
            { entity: "&larr;", hex: "&#2190;", name: "LEFTWARDS ARROW", char: "←" }
            ,{ entity: "&uarr;", hex: "&#2191;", name: "UPWARDS ARROW", char: "↑" }
            ,{ entity: "&rarr;", hex: "&#2192;", name: "RIGHTWARDS ARROW", char: "→" }
            ,{ entity: "&darr;", hex: "&#2193;", name: "DOWNWARDS ARROW", char: "↓" }
            ,{ entity: "&harr;", hex: "&#2194;", name: "LEFT RIGHT ARROW", char: "↔" }
            ,{ hex: "&#2195;", name: "UP DOWN ARROW", char: "↕" }
            ,{ hex: "&#2196;", name: "NORTH WEST ARROW", char: "↖" }
            ,{ hex: "&#2197;", name: "NORTH EAST ARROW", char: "↗" }
            ,{ hex: "&#2198;", name: "SOUTH EAST ARROW", char: "↘" }
            ,{ hex: "&#2199;", name: "SOUTH WEST ARROW", char: "↙" }
            ,{ hex: "&#21A9;", name: "LEFTWARDS ARROW WITH HOOK", char: "↩" }
            ,{ hex: "&#21AA;", name: "RIGHTWARDS ARROW WITH HOOK", char: "↪" }
            ,{ hex: "&#21B6;", name: "ANTICLOCKWISE TOP SEMICIRCLE ARROW", char: "↶" }
            ,{ hex: "&#21B7;", name: "CLOCKWISE TOP SEMICIRCLE ARROW", char: "↷" }
            ,{ hex: "&#21BA;", name: "ANTICLOCKWISE OPEN CIRCLE ARROW", char: "↺" }
            ,{ hex: "&#21BB;", name: "CLOCKWISE OPEN CIRCLE ARROW", char: "↻" }
            ,{ hex: "&#21C4;", name: "RIGHTWARDS ARROW OVER LEFTWARDS ARROW", char: "⇄" }
            ,{ hex: "&#21C5;", name: "UPWARDS ARROW LEFTWARDS OF DOWNWARDS ARROW", char: "⇅" }
            ,{ hex: "&#21C6;", name: "LEFTWARDS ARROW OVER RIGHTWARDS ARROW", char: "⇆" }
            ,{ hex: "&#21C7;", name: "LEFTWARDS PAIRED ARROWS", char: "⇇" }
            ,{ hex: "&#21C8;", name: "UPWARDS PAIRED ARROWS", char: "⇈" }
            ,{ hex: "&#21C9;", name: "RIGHTWARDS PAIRED ARROWS", char: "⇉" }
            ,{ hex: "&#21CA;", name: "DOWNWARDS PAIRED ARROWS", char: "⇊" }
            ,{ entity: "&lArr;", hex: "&#21D0;", name: "LEFTWARDS DOUBLE ARROW", char: "⇐" }
            ,{ entity: "&uArr;", hex: "&#21D1;", name: "UPWARDS DOUBLE ARROW", char: "⇑" }
            ,{ entity: "&rArr;", hex: "&#21D2;", name: "RIGHTWARDS DOUBLE ARROW", char: "⇒" }
            ,{ entity: "&dArr;", hex: "&#21D3;", name: "DOWNWARDS DOUBLE ARROW", char: "⇓" }
            ,{ entity: "&hArr;", hex: "&#21D4;", name: "LEFT RIGHT DOUBLE ARROW", char: "⇔" }
            ,{ hex: "&#21E0;", name: "LEFTWARDS DASHED ARROW", char: "⇠" }
            ,{ hex: "&#21E1;", name: "UPWARDS DASHED ARROW", char: "⇡" }
            ,{ hex: "&#21E2;", name: "RIGHTWARDS DASHED ARROW", char: "⇢" }
            ,{ hex: "&#21E3;", name: "DOWNWARDS DASHED ARROW", char: "⇣" }
            ,{ hex: "&#21E4;", name: "LEFTWARDS ARROW TO BAR", char: "⇤" }
            ,{ hex: "&#21E5;", name: "RIGHTWARDS ARROW TO BAR", char: "⇥" }
            ,{ hex: "&#21E6;", name: "LEFTWARDS WHITE ARROW", char: "⇦" }
            ,{ hex: "&#21E7;", name: "UPWARDS WHITE ARROW", char: "⇧" }
            ,{ hex: "&#21E8;", name: "RIGHTWARDS WHITE ARROW", char: "⇨" }
            ,{ hex: "&#21E9;", name: "DOWNWARDS WHITE ARROW", char: "⇩" }
            ,{ hex: "&#21EA;", name: "UPWARDS WHITE ARROW FROM BAR", char: "⇪" }
            ,{ hex: "&#21F3;", name: "UP DOWN WHITE ARROW", char: "⇳" }
            ,{ hex: "&#21F4;", name: "RIGHT ARROW WITH SMALL CIRCLE", char: "⇴" }
            ,{ hex: "&#21F5;", name: "DOWNWARDS ARROW LEFTWARDS OF UPWARDS ARROW", char: "⇵" }
            ,{ hex: "&#21FD;", name: "LEFTWARDS OPEN-HEADED ARROW", char: "⇽" }
            ,{ hex: "&#21FE;", name: "RIGHTWARDS OPEN-HEADED ARROW", char: "⇾" }
            ,{ hex: "&#2B05;", name: "LEFTWARDS BLACK ARROW", char: "⬅" }
            ,{ hex: "&#2B06;", name: "UPWARDS BLACK ARROW", char: "⬆" }
            ,{ hex: "&#2B07;", name: "DOWNWARDS BLACK ARROW", char: "⬇" }
            ,{ hex: "&#2B08;", name: "NORTH EAST BLACK ARROW", char: "⬈" }
            ,{ hex: "&#2B09;", name: "NORTH WEST BLACK ARROW", char: "⬉" }
            ,{ hex: "&#2B0A;", name: "SOUTH EAST BLACK ARROW", char: "⬊" }
            ,{ hex: "&#2B0B;", name: "SOUTH WEST BLACK ARROW", char: "⬋" }
            ,{ hex: "&#2B0C;", name: "LEFT RIGHT BLACK ARROW", char: "⬌" }
            ,{ hex: "&#2B0D;", name: "UP DOWN BLACK ARROW", char: "⬍" }

            ,{ hex: "&#27F5;", name: "LONG LEFTWARDS ARROW", char: "⟵" }
            ,{ hex: "&#27F6;", name: "LONG RIGHTWARDS ARROW", char: "⟶" }
            ,{ hex: "&#27F7;", name: "LONG LEFT RIGHT ARROW", char: "⟷" }
            ,{ hex: "&#27F8;", name: "LONG LEFTWARDS DOUBLE ARROW", char: "⟸" }
            ,{ hex: "&#27F9;", name: "LONG RIGHTWARDS DOUBLE ARROW", char: "⟹" }
            ,{ hex: "&#27FA;", name: "LONG LEFT RIGHT DOUBLE ARROW", char: "⟺" }
            ,{ hex: "&#27FB;", name: "LONG LEFTWARDS ARROW FROM BAR", char: "⟻" }
            ,{ hex: "&#27FC;", name: "LONG RIGHTWARDS ARROW FROM BAR", char: "⟼" }
        ]
    },
    
    createList: function (elId) {
        
        var category = '';
        var html = [];
        var item = {};
        var item = {};
        var counter = 0;
        var code = '';
        
        for (var descr in nkorgJSCharMap._charList) {
            
            if (!nkorgJSCharMap._charList[descr]) {
                continue;
            }

            html.push('<h3>' + fpcm.ui.translate('EDITOR_INSERTSYMBOL_' + descr) + '</h3>');
            html.push('<div class="row no-gutters">');

            for (var i in nkorgJSCharMap._charList[descr]) {
                
                if (!nkorgJSCharMap._charList[descr][i]) {
                    continue;
                }

                item = nkorgJSCharMap._charList[descr][i];
                code = item.entity !== undefined
                     ? item.entity
                     : item.char;

                html.push('<div class="col-1 p-2 fpcm-ui-align-center"><a class="nkorg-charmap-el" href="#" title="' + (item.name ? item.name : '') + '" data-code="' + code + '">' + item.char + '</a></div>');
                counter++;
                
            }
                
            html.push('</div>');

        }

        var _targetEl = jQuery(elId);
        _targetEl.empty();
        _targetEl.html(html.join(''));
    },
    
    addClickEvent: function (fn) {

        var _clickEl = jQuery('.nkorg-charmap-el');
        _clickEl.unbind('click');
        _clickEl.click(fn);

        return _clickEl;
    }

};