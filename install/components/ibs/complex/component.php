<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if ($arParams['SEF_MODE'] == 'Y') {

    $arVariables = array();

    $componentPage = CComponentEngine::ParseComponentPath(
        $arParams['SEF_FOLDER'],
        $arParams['SEF_URL_TEMPLATES'],
        $arVariables
    );
    if ($componentPage === false && parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == $arParams['SEF_FOLDER']) {
        $componentPage = 'manufacturer_list';
    }


    // Если определить файл шаблона не удалось, показываем  страницу 404 Not Found
    if (empty($componentPage)) {
        \Bitrix\Iblock\Component\Tools::process404(
            Loc::getMessage('MODEL_NOT_FOUND'),
            true,
        );
        return;
    }

    CComponentEngine::InitComponentVariables(
        $componentPage,
        null,
        array(),
        $arVariables
    );

    $arResult['VARIABLES'] = $arVariables;
    $arResult['FOLDER'] = $arParams['SEF_FOLDER'];
    switch ($componentPage) {
        case 'manufacturer_list':
            $urlTemplate = 'model_manufacturer_list';
            break;
        case 'model_manufacturer_list':
            $urlTemplate = 'model_laptop_list';
            break;
        case 'model_laptop_list':
            $urlTemplate = 'laptop_detail';
            break;
    }
    $arResult['URL_TEMPLATE'] = $arParams['SEF_FOLDER'] . $arParams['SEF_URL_TEMPLATES'][$urlTemplate];
    $this->IncludeComponentTemplate($componentPage);

} else {
    \Bitrix\Iblock\Component\Tools::process404(
        Loc::getMessage('NOT_SEF_MODE'),
        true,
    );
    return;
}
