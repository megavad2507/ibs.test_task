<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    'NAME' => Loc::getMessage('MODULE_NAME'),
    'DESCRIPTION' => Loc::getMessage('MODULE_DESCRIPTION'),
    'CACHE_PATH' => 'Y',
    'SORT' => 20,
    'COMPLEX' => 'Y',
    'PATH' => array(
        'ID' => 'ibs_test_components',
        'NAME' => Loc::getMessage('MODULE_PATH_NAME'),
    )
);