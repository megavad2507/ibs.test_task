<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Ibs\Test\ManufacturerTable;
use Ibs\Test\ModelTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!Loader::includeModule('ibs.test')) {
    ShowError(Loc::getMessage('MODULE_IBS_NOT_INSTALLED'));
    return;
}
$model = $arParams['MODEL_TYPE'];
if ($model != "NONE") {
    $msg404 = Loc::getMessage('MODEL_ELEMENT_NOT_FOUND');
    $arResult = $arParams["RESULT"];
    $arResult['ITEMS'] = array();
    $request = Context::getCurrent()->getRequest();
    $sortField = $request->get('sortField');
    $sortOrder = $request->get('sortOrder');
    $pageSize = $request->get('pageSize') ?? $arParams['PAGE_SIZE'];
    $nav = new \Bitrix\Main\UI\PageNavigation("nav-more-items");
    $nav->allowAllRecords(true)
        ->setPageSize($pageSize)
        ->initFromUri();
    $title = $model::getTitle();

    $entity = $model::getEntity();
    $relations = (new $model())->getRelations();
    $query = $entity->getDataClass()::query()
        ->addSelect('*')
        ->setOffset($nav->getOffset())
        ->setLimit($nav->getLimit())
        ->countTotal(true);
    foreach ($relations as $relation) {
        $query->addSelect($relation->getName());
    }
    if (!empty($arResult['VARIABLES'])) {
        $variableNames = array_keys($arResult['VARIABLES']);
        $manufacturerQuery = ManufacturerTable::getList([
            'select' => array('ID', 'NAME', 'CODE'),
            'filter' => array('CODE' => $arResult['VARIABLES']['BRAND'])
        ]);
        $manufacturer = $manufacturerQuery->fetch();
        if (empty($manufacturer)) {
            \Bitrix\Iblock\Component\Tools::process404(
                $msg404,
                true,
                'Y',
                false,
            );
            return;
        } else {
            $url = $arParams['RESULT']['FOLDER'] . $manufacturer['CODE'] . '/';
            $APPLICATION->AddChainItem($manufacturer['NAME'], $url);
        }
        if (!in_array('MODEL', $variableNames)) {
            $query->addFilter('MANUFACTURER_ID', $manufacturer['ID']);
        }

        if (in_array('MODEL', $variableNames)) {
            $modelQuery = ModelTable::getList([
                'select' => array('ID', 'NAME', 'CODE'),
                'filter' => array(
                    'CODE' => $arResult['VARIABLES']['MODEL'],
                    'MANUFACTURER_ID' => $manufacturer['ID']
                )
            ]);
            if ($modelItem = $modelQuery->fetch()) {
                $query->addFilter('MODEL_ID', $modelItem['ID']);
                $url .= $modelItem['CODE'] . '/';
                $APPLICATION->AddChainItem($modelItem['NAME'], $url);
            } else {
                \Bitrix\Iblock\Component\Tools::process404(
                    $msg404,
                    true,
                    'Y',
                    false,
                );
                return;
            }

        }
    }
    unset($manufacturer, $manufacturerQuery, $modelItem, $modelQuery, $url);
    $fairLimit = true;
    if ($sortField && $sortOrder && $entity->hasField($sortField)) {
        $query->setOrder(array($sortField => $sortOrder));
        $fairLimit = false;
        $arResult['SORT_FIELD'] = $sortField;
        $arResult['SORT_ORDER'] = $sortOrder;
    }
    if ($pageSize) {
        $arResult['PAGE_SIZE'] = $pageSize;
    }
    $itemsQuery = Bitrix\Main\ORM\Query\QueryHelper::decompose($query, $fairLimit);
    $itemsCount = $query->exec()->getCount();

    if ($itemsCount > 0) {
        $items = $itemsQuery->getAll();
        foreach ($items as $item) {
            $itemValues = $item->collectValues();
            foreach ($relations as $relation) {
                $relationName = $relation->getName();
                $tmpRelations = $item[$relationName];
                unset($itemValues[$relationName]);
                if (is_a($tmpRelations, \Bitrix\Main\ORM\Objectify\EntityObject::class)) {
                    $itemValues[$relationName] = $tmpRelations->collectValues();
                } else {
                    foreach ($tmpRelations->getAll() as $tmpRelation) {
                        $itemValues[$relationName][] = $tmpRelation->collectValues();
                    }
                }
                foreach ($itemValues[$relationName] as &$relationProps) {
                    if (!empty($relationProps['PRICE'])) {
                        if (Loader::includeModule('currency'))
                            $relationProps['PRICE'] = CCurrencyLang::CurrencyFormat($relationProps['PRICE'], 'RUB');
                        else
                            $relationProps['PRICE'] = round($relationProps['PRICE'], 2) . ' ₽';
                    }
                }
            }
            if (!empty($itemValues['PRICE'])) {
                if (Loader::includeModule('currency'))
                    $itemValues['PRICE'] = CCurrencyLang::CurrencyFormat($itemValues['PRICE'], 'RUB');
                else
                    $itemValues['PRICE'] = round($itemValues['PRICE'], 2) . ' ₽';
            }
            if (!empty($arParams['URL_TEMPLATE'])) {
                $urlTemplate = $arParams['URL_TEMPLATE'];
                foreach ($arResult['VARIABLES'] as $variableTemplate => $variable) {
                    $urlTemplate = str_replace('#' . $variableTemplate . '#', $variable, $urlTemplate);
                }
                $pattern = '/#.*#/';
                preg_match($pattern, $urlTemplate, $matches);
                if (!empty($matches)) {
                    $urlTemplate = str_replace($matches[0], $itemValues['CODE'], $urlTemplate);
                    $itemValues['URL_LINK'] = $urlTemplate;
                }
            }
            $arResult['ITEMS'][] = $itemValues;
        }
        $nav->setRecordCount($itemsCount);
        $arResult['NAV_OBJECT'] = $nav;
        $arResult['USER_SORT_FIELDS'] = array_filter($arParams['USER_SORT_FIELDS'], "strlen");
        $arResult['USER_PAGE_SIZE'] = array_filter($arParams['USER_PAGE_SIZE'], "strlen");
        if (!in_array($arResult['PAGE_SIZE'], $arResult['USER_PAGE_SIZE'])) {
            $arResult['USER_PAGE_SIZE'][] = $arResult['PAGE_SIZE'];
        }
        sort($arResult['USER_PAGE_SIZE'], SORT_NUMERIC);
        $APPLICATION->SetTitle($title);
        $this->IncludeComponentTemplate();
    } else {
        \Bitrix\Iblock\Component\Tools::process404(
            Loc::getMessage('MODEL_NO_RELATION_ITEMS'),
            true,
            'Y',
            false,
        );
        return;
    }
} else {
    ShowError(Loc::getMessage('MODEL_NOT_FOUND'));
}

