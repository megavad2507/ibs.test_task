<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<? if (!empty($arResult['ITEMS'])): ?>
    <?if(!empty($arResult['USER_SORT_FIELDS'])):?>
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <?=Loc::getMessage('SORT_FIELD_CHOOSE')?>
            </button>
            <ul class="dropdown-menu dropdown-sort-field">
                <?foreach($arResult['USER_SORT_FIELDS'] as $i => $field):?>
                    <li class="dropdown-item
                    <?=isset($arResult['SORT_FIELD']) ? ($arResult['SORT_FIELD'] == $field ? 'active' : '') :
                        ($i == 0 ? 'active' : '')?>
                    " data-field="<?=$field?>"><?= Loc::getMessage('SORT_' . $field,['#'. $field . '#' => ''])?></li>
                <?endforeach?>
            </ul>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <?=Loc::getMessage('SORT_ORDER_CHOOSE')?>
            </button>
            <ul class="dropdown-menu dropdown-sort-order">
                <li class="dropdown-item <?=isset($arResult['SORT_ORDER']) ? ($arResult['SORT_ORDER'] == 'ASC' ? 'active' : '') :
                    'active'?>"  data-field="ASC"><?=Loc::getMessage('SORT_ASC')?></li>
                <li class="dropdown-item <?=isset($arResult['SORT_ORDER']) ? ($arResult['SORT_ORDER'] == 'DESC' ? 'active' : '') :
                    ''?>" href="#" data-field="DESC"><?=Loc::getMessage('SORT_DESC')?></li>
            </ul>
        </div>
        <div class="btn btn-primary" id="sort-apply-button"><?=Loc::getMessage('SORT_APPLY_BUTTON')?></div>
    <?endif?>
    <?if(!empty($arResult['USER_PAGE_SIZE'])):?>
    <div class="row">
        <?=Loc::getMessage('PAGE_SIZE_TEXT')?>
        <select class="form-select select-page-size">
            <?foreach($arResult['USER_PAGE_SIZE'] as $size):?>
                <option value="<?=$size?>" <?=$size == $arResult['PAGE_SIZE'] ? 'selected' : ''?>><?=$size?></option>
            <?endforeach;?>
        </select>
    </div>
    <?endif?>
    <div class="ibs-test-list accordion accordion-flush" id="accordion-items">
        <?foreach ($arResult['ITEMS'] as $i => $item):?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-item-<?=$i+1?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-<?=$i+1?>" aria-expanded="false" aria-controls="flush-collapse-<?=$i+1?>">
                        <?=$item['NAME'] ? $item['NAME'] : 'ID ' . $item['ID']?>
                    </button>
                </h2>
                <div id="flush-collapse-<?=$i+1?>" class="accordion-collapse collapse" aria-labelledby="flush-heading-<?=$i+1?>" data-bs-parent="#accordion-items">
                    <div class="accordion-body">
                        <?if(!empty($item['URL_LINK'])):?>
                            <a href="<?=$item['URL_LINK']?>">Ссылка</a>
                        <?endif?>
                        <?foreach($item as $propName => $propValue):?>
                            <?if(!is_array($propValue) && $propValue && $msg = Loc::getMessage('ACCORDION_' . $propName,
                                    ['#' . $propName . '#' => $propValue])):?>
                                <li class="list-group-item item"><?=$msg  ?></li>
                            <?elseif(is_array($propValue)):?>
                                <?=Loc::getMessage('ACCORDION_' . $propName)?>
                                <ul>
                                    <?foreach($propValue as $relationPropName => $relationProp):?>
                                        <?if(!empty($relationProp)):?>
                                            <?if(is_array($relationProp)):?>
                                                <?foreach($relationProp as $propRelationName => $propRelationValue):?>
                                                    <?if($msg = Loc::getMessage('ACCORDION_' . $propRelationName,
                                                        ['#' . $propRelationName .'#' => $propRelationValue])):?>
                                                        <li><?=$msg ?></li>
                                                    <?endif?>
                                                <?endforeach;?>
                                                <hr>
                                            <?else:?>
                                                <?if($msg = Loc::getMessage('ACCORDION_' . $relationPropName, ['#' . $relationPropName .'#' => $relationProp])):?>
                                                    <li><?=$msg ?></li>
                                                <?endif?>
                                            <?endif?>
                                        <?endif?>
                                    <?endforeach;?>
                                </ul>
                            <?endif?>
                        <?endforeach;?>
                    </div>
                </div>
            </div>
        <?endforeach?>
    </div>
<? endif; ?>


<?
$APPLICATION->IncludeComponent(
    "bitrix:main.pagenavigation",
    "",
    array(
        "NAV_OBJECT" => $arResult['NAV_OBJECT'],
        "SEF_MODE" => "N",
        "SHOW_COUNT" => "N",
    ),
    false
);
?>