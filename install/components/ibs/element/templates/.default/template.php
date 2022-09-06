<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

?>
<? if (!empty($arResult['ITEM'])): ?>
    <? $item = $arResult['ITEM']; ?>
    <div class="ibs-test-detail accordion accordion-flush opened" id="accordion-item">
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-item">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapse" aria-expanded="false"
                        aria-controls="flush-collapse">
                    <?= $item['NAME'] ? $item['NAME'] : 'ID ' . $item['ID'] ?>
                </button>
            </h2>
            <div id="flush-collapse" class="accordion-collapse collapse show"
                 aria-labelledby="flush-heading" data-bs-parent="#accordion-item">
                <div class="accordion-body">
                    <? foreach ($item as $propName => $propValue): ?>
                        <? if (!is_array($propValue)): ?>
                            <li class="list-group-item item-id"><?= Loc::getMessage('ACCORDION_' . $propName, ['#' . $propName . '#' => $propValue]) ?></li>
                        <? else: ?>
                            <?= Loc::getMessage('ACCORDION_' . $propName) ?>
                            <ul>
                                <? foreach ($propValue as $relationPropName => $relationProp): ?>
                                    <? if (!empty($relationProp)): ?>
                                        <? if (is_array($relationProp)): ?>
                                            <? foreach ($relationProp as $propRelationName => $propRelationValue): ?>
                                                <? if ($msg = Loc::getMessage('ACCORDION_' . $propRelationName, ['#' . $propRelationName . '#' => $propRelationValue])): ?>
                                                    <li><?= $msg ?></li>
                                                <? endif ?>
                                            <? endforeach; ?>
                                            <hr>
                                        <? else: ?>
                                            <? if ($msg = Loc::getMessage('ACCORDION_' . $relationPropName, ['#' . $relationPropName . '#' => $relationProp])): ?>
                                                <li><?= $msg ?></li>
                                            <? endif ?>
                                        <? endif ?>
                                    <? endif ?>
                                <? endforeach; ?>
                            </ul>
                        <? endif ?>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>
