<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!Loader::includeModule('ibs.test')) {
    ShowError(Loc::getMessage('MODULE_IBS_NOT_INSTALLED'));
    return;
}
$model = $arParams['MODEL_TYPE'];
if ($model != "NONE") {
    $message404 = Loc::getMessage('MODEL_ELEMENT_NOT_FOUND');

    $elemId = $arParams['MODEL_ELEMENT_ID'];
    $elemCode = $arParams['MODEL_ELEMENT_CODE'];
    if ($elemId || $elemCode) {
        $model = $arParams['MODEL_TYPE'];
        $entity = $model::getEntity();
        $relations = (new $model())->getRelations();

        $query = $entity->getDataClass()::query()
            ->addSelect('*')
            ->countTotal(true);
        if ($elemId) {
            $query->addFilter('ID', $elemId);
        }
        if ($elemCode) {
            $query->addFilter('CODE', $elemCode);
        }

        foreach ($relations as $relation) {
            $query->addSelect($relation->getName());
        }
        $queryExec = $query->exec();
        if ($queryExec->getCount() != 0) {
            $items = $queryExec->fetchCollection();
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
            }
            $arResult['ITEM'] = $itemValues;
            $APPLICATION->AddChainItem($arResult['ITEM']['NAME'], null);
            $this->IncludeComponentTemplate();
        } else {
            \Bitrix\Iblock\Component\Tools::process404(
                $message404,
                true
            );
            return;
        }
    } else {
        \Bitrix\Iblock\Component\Tools::process404(
            $message404,
            true,
        );
        return;
    }
} else {
    ShowError(Loc::getMessage('MODEL_NOT_FOUND'));
}

