<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
?>
<div class="cases__slider">
<?foreach($arResult["ITEMS"] as $arItem):?>
<?
$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>
    <div id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="cases__wrap-pic">
            <div class="cases__wrap-pic_container">
            <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                <img
                    src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                    width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                    height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                    alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                    title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                />
            <?endif?>
            </div>
        </div>
        <div class="cases__wrap-text">
        <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
            <h3><?echo $arItem["NAME"]?></h3>
        <?endif;?>
        <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
            <div class="cases__wrap-text_scroll mCustomScrollbar">
            <?if($arItem["DETAIL_TEXT_TYPE"] == "text"):?>
                <p><?echo $arItem["PREVIEW_TEXT"];?></p>
            <?else:?>
                <?echo $arItem["PREVIEW_TEXT"];?>
            <?endif;?>
            </div>
        <?endif;?>
        </div>
    </div>
<?endforeach;?>
</div>
