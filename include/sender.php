<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="bx-subscribe">
	<div class="bx-block-title">РАССЫЛКА</div>
	<?$APPLICATION->IncludeComponent(
	"admbitrix:sender.subscribe", 
	".default", 
	array(
		"SET_TITLE" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"USE_PERSONALIZATION" => "N",
		"CONFIRMATION" => "N",
		"HIDE_MAILINGS" => "N",
		"SHOW_HIDDEN" => "N",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "N",
		"USER_CONSENT_IS_LOADED" => "N",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600"
	),
	false
);?>
</div>