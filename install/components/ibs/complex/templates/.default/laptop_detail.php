<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<?$APPLICATION->IncludeComponent(
    "ibs:element",
    "",
    Array(
        "MODEL_ELEMENT_ID" => "",
        "MODEL_ELEMENT_CODE" => $arResult['VARIABLES']['NOTEBOOK'],
        "MODEL_TYPE" => "Ibs\\Test\\LaptopTable",
    )
);?>