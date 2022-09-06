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
$model = $arCurrentValues['MODEL_TYPE'];
$arSortFields = [];
if (!is_null($model) && $model != 'NONE') {
    foreach ($model::getEntity()->getScalarFields() as $field) {
        $arSortFields[$field->getName()] = $field->getTitle();
    }

}
$arSorts = array(
    "ASC" => Loc::getMessage('MODEL_SORT_ORDER_ASC'),
    "DESC" => Loc::getMessage("MODEL_SORT_ORDER_DESC")
);

$arPageSize = [
    2 => 2,
    5 => 5,
    7 => 7,
    10 => 10
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
        //выбор поля сортировки
        'SORT_FIELD' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('MODEL_SORT_FIELD'),
            'TYPE' => 'LIST',
            'VALUES' => $arSortFields,
            'REFRESH' => 'Y',
        ),
        //выбор порядка сортировки
        'SORT_ORDER' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('MODEL_SORT_ORDER'),
            'TYPE' => 'LIST',
            'VALUES' => $arSorts,
            'DEFAULT' => 'ASC',
        ),
        'USER_SORT_FIELDS' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('MODEL_USER_SORT_FIELDS'),
            'TYPE' => 'LIST',
            'VALUES' => $arSortFields,
            'MULTIPLE' => 'Y'
        ),
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
        )
    ),
);
