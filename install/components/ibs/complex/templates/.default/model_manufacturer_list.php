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
    "ibs:list",
    "",
    Array(
        "MODEL_TYPE" => "Ibs\\Test\\ModelTable",
        "PAGE_SIZE" => $arParams['PAGE_SIZE'],
        "SORT_FIELD" => "ID",
        "SORT_ORDER" => "ASC",
        "USER_PAGE_SIZE" => $arParams["USER_PAGE_SIZE"],
        "USER_SORT_FIELDS" => array("ID","NAME"),
        "URL_TEMPLATE" => $arResult['URL_TEMPLATE'],
        "RESULT" => $arResult
    )
);?>