<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

?>
<form action="<? echo $APPLICATION->GetCurPage() ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<? echo LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="ibs.test">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="1">
    <p>
        <input type="checkbox" name="installTables" id="installTables" value="Y" checked>
        <label for="installTables"><? echo Loc::getMessage("MODULE_CREATE_TABLE_CHECKBOX") ?>
        </label>
    </p>
    <input type="submit" name="inst" value="<? echo Loc::getMessage("MODULE_INSTALL") ?>">
</form>
