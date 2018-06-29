<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.discount.coupon.mail",
	"",
	Array(
		"COUPON_DESCRIPTION" => "{#EMAIL_TO#}",
		"COUPON_TYPE" => "Order",
		"DISCOUNT_UNIT" => "CurAll",
		"DISCOUNT_VALUE" => "5000",
		"DISCOUNT_XML_ID" => "{#SENDER_CHAIN_CODE#}"
	)
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>