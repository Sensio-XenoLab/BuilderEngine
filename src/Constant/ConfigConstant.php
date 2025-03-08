<?php

namespace VeeZions\BuilderEngine\Constant;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ConfigConstant
{
    public const CONFIG_FILE_NAME = 'builder_engine';
    public const CONFIG_MODE_DEFAULT = 'default';
    public const CONFIG_MODE_FORM = 'form';
    public const CONFIG_LOCALES = [
        'ad' => 'ca',
        'ae' => 'ar',
        'af' => 'fa',
        'ag' => 'en',
        'ai' => 'en',
        'al' => 'sq',
        'am' => 'hy',
        'an' => 'nl',
        'ao' => 'pt',
        'aq' => 'en',
        'ar' => 'es',
        'as' => 'en',
        'at' => 'de',
        'au' => 'en',
        'aw' => 'nl',
        'ax' => 'sv',
        'az' => 'az',
        'ba' => 'bs',
        'bb' => 'en',
        'bd' => 'bn',
        'be' => 'nl',
        'bf' => 'fr',
        'bg' => 'bg',
        'bh' => 'ar',
        'bi' => 'fr',
        'bj' => 'fr',
        'bl' => 'fr',
        'bm' => 'en',
        'bn' => 'ms',
        'bo' => 'es',
        'br' => 'pt',
        'bq' => 'nl',
        'bs' => 'en',
        'bt' => 'dz',
        'bv' => 'no',
        'bw' => 'en',
        'by' => 'be',
        'bz' => 'en',
        'ca' => 'en',
        'cc' => 'en',
        'cd' => 'fr',
        'cf' => 'fr',
        'cg' => 'fr',
        'ch' => 'de',
        'ci' => 'fr',
        'ck' => 'en',
        'cl' => 'es',
        'cm' => 'fr',
        'cn' => 'zh',
        'co' => 'es',
        'cr' => 'es',
        'cu' => 'es',
        'cv' => 'pt',
        'cw' => 'nl',
        'cx' => 'en',
        'cy' => 'el',
        'cz' => 'cs',
        'de' => 'de',
        'dj' => 'fr',
        'dk' => 'da',
        'dm' => 'en',
        'do' => 'es',
        'dz' => 'ar',
        'ec' => 'es',
        'ee' => 'et',
        'eg' => 'ar',
        'eh' => 'ar',
        'er' => 'ti',
        'es' => 'es',
        'et' => 'am',
        'fi' => 'fi',
        'fj' => 'en',
        'fk' => 'en',
        'fm' => 'en',
        'fo' => 'fo',
        'fr' => 'fr',
        'ga' => 'fr',
        'gb' => 'en',
        'gd' => 'en',
        'ge' => 'ka',
        'gf' => 'fr',
        'gg' => 'en',
        'gh' => 'en',
        'gi' => 'en',
        'gl' => 'kl',
        'gm' => 'en',
        'gn' => 'fr',
        'gp' => 'fr',
        'gq' => 'es',
        'gr' => 'el',
        'gs' => 'en',
        'gt' => 'es',
        'gu' => 'en',
        'gw' => 'pt',
        'gy' => 'en',
        'hk' => 'zh',
        'hm' => 'en',
        'hn' => 'es',
        'hr' => 'hr',
        'ht' => 'fr',
        'hu' => 'hu',
        'id' => 'id',
        'ie' => 'en',
        'il' => 'he',
        'im' => 'en',
        'in' => 'hi',
        'io' => 'en',
        'iq' => 'ar',
        'ir' => 'fa',
        'is' => 'is',
        'it' => 'it',
        'je' => 'en',
        'jm' => 'en',
        'jo' => 'ar',
        'jp' => 'ja',
        'ke' => 'sw',
        'kg' => 'ky',
        'kh' => 'km',
        'ki' => 'en',
        'km' => 'ar',
        'kn' => 'en',
        'kp' => 'ko',
        'kr' => 'ko',
        'kw' => 'ar',
        'ky' => 'en',
        'kz' => 'kk',
        'la' => 'lo',
        'lb' => 'ar',
        'lc' => 'en',
        'li' => 'de',
        'lk' => 'si',
        'lr' => 'en',
        'ls' => 'en',
        'lt' => 'lt',
        'lu' => 'lb',
        'lv' => 'lv',
        'ly' => 'ar',
        'ma' => 'ar',
        'mc' => 'fr',
        'md' => 'ru',
        'me' => 'srp',
        'mf' => 'fr',
        'mg' => 'mg',
        'mh' => 'en',
        'mk' => 'mk',
        'ml' => 'fr',
        'mm' => 'my',
        'mn' => 'mn',
        'mo' => 'zh',
        'mp' => 'ch',
        'mq' => 'fr',
        'mr' => 'ar',
        'ms' => 'en',
        'mt' => 'mt',
        'mu' => 'mfe',
        'mv' => 'dv',
        'mw' => 'en',
        'mx' => 'es',
        'my' => 'ms',
        'mz' => 'pt',
        'na' => 'en',
        'nc' => 'fr',
        'ne' => 'fr',
        'nf' => 'en',
        'ng' => 'en',
        'ni' => 'es',
        'nl' => 'nl',
        'no' => 'nb',
        'np' => 'ne',
        'nr' => 'na',
        'nu' => 'niu',
        'nz' => 'en',
        'om' => 'ar',
        'pa' => 'es',
        'pe' => 'es',
        'pf' => 'fr',
        'pg' => 'en',
        'ph' => 'en',
        'pk' => 'en',
        'pl' => 'pl',
        'pm' => 'fr',
        'pn' => 'en',
        'pr' => 'es',
        'ps' => 'ar',
        'pt' => 'pt',
        'pw' => 'en',
        'py' => 'es',
        'qa' => 'ar',
        're' => 'fr',
        'ro' => 'ro',
        'rs' => 'sr',
        'ru' => 'ru',
        'rw' => 'rw',
        'sa' => 'ar',
        'sb' => 'en',
        'sc' => 'fr',
        'sd' => 'ar',
        'se' => 'sv',
        'sg' => 'en',
        'sh' => 'en',
        'si' => 'sl',
        'sj' => 'no',
        'sk' => 'sk',
        'sl' => 'en',
        'sm' => 'it',
        'sn' => 'fr',
        'so' => 'so',
        'sr' => 'nl',
        'st' => 'pt',
        'ss' => 'en',
        'sv' => 'es',
        'sx' => 'nl',
        'sy' => 'ar',
        'sz' => 'en',
        'tc' => 'en',
        'td' => 'fr',
        'tf' => 'fr',
        'tg' => 'fr',
        'th' => 'th',
        'tj' => 'tg',
        'tk' => 'tkl',
        'tl' => 'pt',
        'tm' => 'tk',
        'tn' => 'ar',
        'to' => 'en',
        'tr' => 'tr',
        'tt' => 'en',
        'tv' => 'en',
        'tw' => 'zh',
        'tz' => 'sw',
        'ua' => 'uk',
        'ug' => 'en',
        'um' => 'en',
        'us' => 'en',
        'uy' => 'es',
        'uz' => 'uz',
        'va' => 'it',
        'vc' => 'en',
        've' => 'es',
        'vg' => 'en',
        'vi' => 'en',
        'vn' => 'vi',
        'vu' => 'bi',
        'wf' => 'fr',
        'ws' => 'sm',
        'ye' => 'ar',
        'yt' => 'fr',
        'za' => 'zu',
        'zm' => 'en',
        'zw' => 'en'
    ];
}
