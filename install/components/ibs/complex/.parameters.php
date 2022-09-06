<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arPageSize = [
    2 => 2,
    5 => 5,
    7 => 7,
    10 => 10
];

$arComponentParameters = array(
    'PARAMETERS' => array(
        'PAGE_SIZE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('PAGE_SIZE'),
            'TYPE' => 'STRING',
            'DEFAULT' => 5
        ),
        'USER_PAGE_SIZE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('USER_PAGE_SIZE'),
            'TYPE' => 'LIST',
            'VALUES' => $arPageSize,
            'DEFAULT' => 5,
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y'
        ),

        'SEF_MODE' => array( // для работы в режиме ЧПУ
            'manufacturer_list' => array(
                'NAME' => Loc::getMessage('SEF_MANUFACTURER_LIST'),
                'DEFAULT' => '',
            ),
            'model_manufacturer_list' => array(
                'NAME' => Loc::getMessage('SEF_MODEL_MANUFACTURER_LIST'),
                'DEFAULT' => '#BRAND#/',
            ),
            'model_laptop_list' => array(
                'NAME' => Loc::getMessage('SEF_MODEL_LAPTOP_LIST'),
                'DEFAULT' => '#BRAND#/#MODEL#/',
            ),
            'laptop_detail' => array(
                'NAME' => Loc::getMessage('SEF_LAPTOP_DETAIL'),
                'DEFAULT' => 'detail/#NOTEBOOK#/',
            ),
        ),

    ),
);