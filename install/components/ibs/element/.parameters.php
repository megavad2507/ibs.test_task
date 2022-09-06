<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arCurrentValues */


use Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Ibs\Test\ManufacturerTable;
use \Ibs\Test\ModelTable;
use \Ibs\Test\LaptopTable;
use \Ibs\Test\OptionTable;

if (!Loader::includeModule('ibs.test')) {
    ShowError(Loc::getMessage('MODULE_IBS_NOT_INSTALLED'));
    return;
}
$arModels = [
    'NONE' => Loc::getMessage('MODEL_CHOOSE_MODEL'),
    ManufacturerTable::class => Loc::getMessage('MODEL_MANUFACTURES'),
    LaptopTable::class => Loc::getMessage('MODEL_LAPTOPS'),
    ModelTable::class => Loc::getMessage('MODEL_MODELS'),
    OptionTable::class => Loc::getMessage('MODEL_OPTIONS')
];


$arComponentParameters = array(
    'PARAMETERS' => array(
        //выбор модели
        'MODEL_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('MODEL_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arModels,
            'REFRESH' => 'Y',
        ),
        'MODEL_ELEMENT_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('MODEL_ELEMENT_ID'),
            'TYPE' => 'STRING',
            'DEFAULT' => '={$_REQUEST["MODEL_ELEMENT_ID"]}',
        ),
    ),
);