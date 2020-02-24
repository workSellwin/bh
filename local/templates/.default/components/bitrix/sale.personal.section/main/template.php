<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$APPLICATION->SetAdditionalCSS("/local/templates/.default/components/bitrix/sale.personal.section/main/style.min.css",true);

if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
    $APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

$theme = Bitrix\Main\Config\Option::get("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);

$availablePages = array();

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_ORDERS'],
        "name" => Loc::getMessage("SPS_ORDER_PAGE_NAME"),
        "icon" => '<svg version="1.1" id="Capa_1" width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 487.222 487.222" style="enable-background:new 0 0 487.222 487.222;" xml:space="preserve">
			<path d="M486.554,186.811c-1.6-4.9-5.8-8.4-10.9-9.2l-152-21.6l-68.4-137.5c-2.3-4.6-7-7.5-12.1-7.5l0,0c-5.1,0-9.8,2.9-12.1,7.6
				l-67.5,137.9l-152,22.6c-5.1,0.8-9.3,4.3-10.9,9.2s-0.2,10.3,3.5,13.8l110.3,106.9l-25.5,151.4c-0.9,5.1,1.2,10.2,5.4,13.2
				c2.3,1.7,5.1,2.6,7.9,2.6c2.2,0,4.3-0.5,6.3-1.6l135.7-71.9l136.1,71.1c2,1,4.1,1.5,6.2,1.5l0,0c7.4,0,13.5-6.1,13.5-13.5
				c0-1.1-0.1-2.1-0.4-3.1l-26.3-150.5l109.6-107.5C486.854,197.111,488.154,191.711,486.554,186.811z M349.554,293.911
				c-3.2,3.1-4.6,7.6-3.8,12l22.9,131.3l-118.2-61.7c-3.9-2.1-8.6-2-12.6,0l-117.8,62.4l22.1-131.5c0.7-4.4-0.7-8.8-3.9-11.9
				l-95.6-92.8l131.9-19.6c4.4-0.7,8.2-3.4,10.1-7.4l58.6-119.7l59.4,119.4c2,4,5.8,6.7,10.2,7.4l132,18.8L349.554,293.911z"/>
		</svg>'
    );
}

if ($arParams['SHOW_ACCOUNT_PAGE'] === 'Y')
{
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_ACCOUNT'],
        "name" => Loc::getMessage("SPS_ACCOUNT_PAGE_NAME"),
        "icon" => '<svg version="1.1" id="Capa_pa" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 493.592 493.592" style="enable-background:new 0 0 493.592 493.592;" xml:space="preserve">
<g>
	<path d="M64.113,95.726c15.715,0,28.5-12.785,28.5-28.5c0-15.715-12.785-28.5-28.5-28.5c-15.715,0-28.5,12.785-28.5,28.5
		C35.613,82.941,48.398,95.726,64.113,95.726z M64.113,59.726c4.136,0,7.5,3.364,7.5,7.5c0,4.136-3.364,7.5-7.5,7.5
		c-4.136,0-7.5-3.364-7.5-7.5C56.613,63.09,59.978,59.726,64.113,59.726z"/>
	<path d="M132.61,95.726c15.715,0,28.5-12.785,28.5-28.5c0-15.715-12.785-28.5-28.5-28.5c-15.715,0-28.5,12.785-28.5,28.5
		C104.11,82.941,116.896,95.726,132.61,95.726z M132.61,59.726c4.136,0,7.5,3.364,7.5,7.5c0,4.136-3.364,7.5-7.5,7.5
		c-4.136,0-7.5-3.364-7.5-7.5C125.11,63.09,128.475,59.726,132.61,59.726z"/>
	<path d="M463.399,4.732H30.192C13.544,4.732,0,18.276,0,34.923v334.26c0,16.648,13.544,30.192,30.192,30.192h71.383
		c10.076,16.031,23.621,29.922,39.92,40.426v23.356c0,14.172,11.53,25.702,25.703,25.702h28.126
		c14.172,0,25.703-11.53,25.703-25.702v-2.598h42.852v2.598c0,14.172,11.53,25.702,25.702,25.702h28.126
		c14.172,0,25.701-11.53,25.701-25.702v-16.461c21.292-10.721,39.384-27.254,52.075-47.321h67.917
		c16.648,0,30.192-13.544,30.192-30.192V34.923C493.592,18.276,480.047,4.732,463.399,4.732z M30.192,25.732h433.207
		c5.069,0,9.192,4.123,9.192,9.191v70.466H21V34.923C21,29.855,25.124,25.732,30.192,25.732z M328.703,430.413
		c-3.824,1.671-6.295,5.448-6.295,9.621v23.124c0,2.593-2.109,4.702-4.701,4.702h-28.126c-2.593,0-4.702-2.109-4.702-4.702V450.06
		c0-5.799-4.701-10.5-10.5-10.5h-62.165c-0.322,0-0.644-0.006-0.965-0.012l-0.576-0.01c-2.79-0.03-5.518,1.05-7.519,3.023
		c-2,1.973-3.127,4.665-3.127,7.476v13.12c0,2.593-2.11,4.702-4.703,4.702h-28.126c-2.593,0-4.703-2.109-4.703-4.702v-29.239
		c0-3.745-1.995-7.206-5.235-9.084c-33.812-19.598-54.816-56.016-54.816-95.043c0-44.368,29.414-85.143,71.504-101.333
		c-0.076,1.229-0.126,2.465-0.126,3.712c0,5.799,4.701,10.5,10.5,10.5h98.921c0.005-0.001,0.012-0.001,0.02,0
		c5.799,0,10.5-4.701,10.5-10.5c0-0.242-0.008-0.481-0.024-0.719c-0.045-3.811-0.461-7.531-1.189-11.141
		c2.322,0.167,4.657,0.401,6.993,0.715c4.002,0.544,7.97-1.273,10.185-4.657c13.964-21.343,29.529-25.426,38.525-25.728v44.332
		c0,3.232,1.489,6.284,4.036,8.274c15.405,12.034,27.343,27.926,34.524,45.957c1.591,3.994,5.456,6.615,9.755,6.615h16.915
		c2.593,0,4.703,2.111,4.703,4.706v58.529c0,2.594-2.109,4.703-4.703,4.703h-16.915c-4.299,0-8.165,2.621-9.755,6.615
		C376.201,397.027,355.02,418.91,328.703,430.413z M196.259,221.67c4.594-16.4,19.677-28.461,37.523-28.461
		c17.847,0,32.93,12.061,37.524,28.461H196.259z M283.744,199.048c-10.751-16.175-29.126-26.839-49.961-26.839
		c-22.986,0-42.981,13.008-53.041,32.042c-25.63,6.617-48.121,20.754-65.19,39.514c-0.014,0-0.028-0.002-0.042-0.002h-11.402
		c-2.593,0-4.703-2.109-4.703-4.702V227.66c0-2.594,2.11-4.704,4.703-4.704c5.799,0,10.5-4.701,10.5-10.5
		c0-5.799-4.701-10.5-10.5-10.5c-14.173,0-25.703,11.531-25.703,25.704v11.401c0,12.802,9.409,23.444,21.674,25.384
		c-11.854,19.519-18.633,42.013-18.633,65.347c0,16.876,3.303,33.338,9.428,48.584H30.192c-5.069,0-9.192-4.124-9.192-9.192V126.389
		h451.592v242.794c0,5.068-4.124,9.192-9.192,9.192h-32.992c5.375-4.715,8.779-11.626,8.779-19.32v-58.529
		c0-14.175-11.53-25.706-25.703-25.706h-10.01c-7.981-17.193-19.672-32.517-34.22-44.838v-48.718c0-4.603-2.998-8.669-7.395-10.03
		c-1.552-0.48-36.878-10.844-65.961,28.27C292.165,199.183,287.401,199.048,283.744,199.048z"/>
</g>
</svg>'
    );
}

if ($arParams['SHOW_PRIVATE_PAGE'] === 'Y')
{
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_PRIVATE'],
        "name" => Loc::getMessage("SPS_PERSONAL_PAGE_NAME"),
        "icon" => '<svg version="1.1" id="Capa_1" width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 482.9 482.9" style="enable-background:new 0 0 482.9 482.9;" xml:space="preserve">

			<path d="M239.7,260.2c0.5,0,1,0,1.6,0c0.2,0,0.4,0,0.6,0c0.3,0,0.7,0,1,0c29.3-0.5,53-10.8,70.5-30.5
				c38.5-43.4,32.1-117.8,31.4-124.9c-2.5-53.3-27.7-78.8-48.5-90.7C280.8,5.2,262.7,0.4,242.5,0h-0.7c-0.1,0-0.3,0-0.4,0h-0.6
				c-11.1,0-32.9,1.8-53.8,13.7c-21,11.9-46.6,37.4-49.1,91.1c-0.7,7.1-7.1,81.5,31.4,124.9C186.7,249.4,210.4,259.7,239.7,260.2z
				 M164.6,107.3c0-0.3,0.1-0.6,0.1-0.8c3.3-71.7,54.2-79.4,76-79.4h0.4c0.2,0,0.5,0,0.8,0c27,0.6,72.9,11.6,76,79.4
				c0,0.3,0,0.6,0.1,0.8c0.1,0.7,7.1,68.7-24.7,104.5c-12.6,14.2-29.4,21.2-51.5,21.4c-0.2,0-0.3,0-0.5,0l0,0c-0.2,0-0.3,0-0.5,0
				c-22-0.2-38.9-7.2-51.4-21.4C157.7,176.2,164.5,107.9,164.6,107.3z"/>
			<path d="M446.8,383.6c0-0.1,0-0.2,0-0.3c0-0.8-0.1-1.6-0.1-2.5c-0.6-19.8-1.9-66.1-45.3-80.9c-0.3-0.1-0.7-0.2-1-0.3
				c-45.1-11.5-82.6-37.5-83-37.8c-6.1-4.3-14.5-2.8-18.8,3.3c-4.3,6.1-2.8,14.5,3.3,18.8c1.7,1.2,41.5,28.9,91.3,41.7
				c23.3,8.3,25.9,33.2,26.6,56c0,0.9,0,1.7,0.1,2.5c0.1,9-0.5,22.9-2.1,30.9c-16.2,9.2-79.7,41-176.3,41
				c-96.2,0-160.1-31.9-176.4-41.1c-1.6-8-2.3-21.9-2.1-30.9c0-0.8,0.1-1.6,0.1-2.5c0.7-22.8,3.3-47.7,26.6-56
				c49.8-12.8,89.6-40.6,91.3-41.7c6.1-4.3,7.6-12.7,3.3-18.8c-4.3-6.1-12.7-7.6-18.8-3.3c-0.4,0.3-37.7,26.3-83,37.8
				c-0.4,0.1-0.7,0.2-1,0.3c-43.4,14.9-44.7,61.2-45.3,80.9c0,0.9,0,1.7-0.1,2.5c0,0.1,0,0.2,0,0.3c-0.1,5.2-0.2,31.9,5.1,45.3
				c1,2.6,2.8,4.8,5.2,6.3c3,2,74.9,47.8,195.2,47.8s192.2-45.9,195.2-47.8c2.3-1.5,4.2-3.7,5.2-6.3
				C447,415.5,446.9,388.8,446.8,383.6z"/>
			</svg>'
    );
}

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{

    $delimeter = ($arParams['SEF_MODE'] === 'Y') ? "?" : "&";
    $availablePages[] = array(
        "path" => "/personal/favorites/",
        "name" => "Избранное",
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 342.564 342.564">
	<path d="M318.624,50.159c-45.418-44.829-91.676-33.627-124.713,0l-22.675,22.675l-22.675-22.675 c-33.038-33.627-79.408-45.486-124.713,0c-33.264,33.4-30.952,90.655,2.086,124.26l145.28,147.819l145.28-147.819 C349.553,140.814,352.161,83.264,318.624,50.159z M295.927,163.534L171.213,288.248L46.5,163.534 c-29.614-30.135-29.614-71.903,0-102.038s61.087-18.798,90.701,11.338l34.013,34.013l34.013-34.013 c29.614-30.135,61.087-41.473,90.701-11.338C325.54,91.609,325.54,133.399,295.927,163.534z"/>
		</svg>
'
    );
}

if ($arParams['SHOW_PROFILE_PAGE'] === 'Y')
{
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_PROFILE'],
        "name" => Loc::getMessage("SPS_PROFILE_PAGE_NAME"),
        "icon" => '<svg version="1.1" id="Capa_1" width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 477 477" style="enable-background:new 0 0 477 477;" xml:space="preserve">
				<path d="M238.4,0C133,0,47.2,85.8,47.2,191.2c0,12,1.1,24.1,3.4,35.9c0.1,0.7,0.5,2.8,1.3,6.4c2.9,12.9,7.2,25.6,12.8,37.7
					c20.6,48.5,65.9,123,165.3,202.8c2.5,2,5.5,3,8.5,3s6-1,8.5-3c99.3-79.8,144.7-154.3,165.3-202.8c5.6-12.1,9.9-24.7,12.8-37.7
					c0.8-3.6,1.2-5.7,1.3-6.4c2.2-11.8,3.4-23.9,3.4-35.9C429.6,85.8,343.8,0,238.4,0z M399.6,222.4c0,0.2-0.1,0.4-0.1,0.6
					c-0.1,0.5-0.4,2-0.9,4.3c0,0.1,0,0.1,0,0.2c-2.5,11.2-6.2,22.1-11.1,32.6c-0.1,0.1-0.1,0.3-0.2,0.4
					c-18.7,44.3-59.7,111.9-148.9,185.6c-89.2-73.7-130.2-141.3-148.9-185.6c-0.1-0.1-0.1-0.3-0.2-0.4c-4.8-10.4-8.5-21.4-11.1-32.6
					c0-0.1,0-0.1,0-0.2c-0.6-2.3-0.8-3.8-0.9-4.3c0-0.2-0.1-0.4-0.1-0.7c-2-10.3-3-20.7-3-31.2c0-90.5,73.7-164.2,164.2-164.2
					s164.2,73.7,164.2,164.2C402.6,201.7,401.6,212.2,399.6,222.4z"/>
				<path d="M238.4,71.9c-66.9,0-121.4,54.5-121.4,121.4s54.5,121.4,121.4,121.4s121.4-54.5,121.4-121.4S305.3,71.9,238.4,71.9z
					 M238.4,287.7c-52.1,0-94.4-42.4-94.4-94.4s42.4-94.4,94.4-94.4s94.4,42.4,94.4,94.4S290.5,287.7,238.4,287.7z"/>
		</svg>'
    );
}

if ($arParams['SHOW_BASKET_PAGE'] === 'Y')
{
    $availablePages[] = array(
        "path" => $arParams['PATH_TO_BASKET'],
        "name" => Loc::getMessage("SPS_BASKET_PAGE_NAME"),
        "icon" => '<svg version="1.1" id="Capa_1" width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				 viewBox="0 0 489 489" style="enable-background:new 0 0 489 489;" xml:space="preserve">
			<g>
				<path d="M440.1,422.7l-28-315.3c-0.6-7-6.5-12.3-13.4-12.3h-57.6C340.3,42.5,297.3,0,244.5,0s-95.8,42.5-96.6,95.1H90.3
					c-7,0-12.8,5.3-13.4,12.3l-28,315.3c0,0.4-0.1,0.8-0.1,1.2c0,35.9,32.9,65.1,73.4,65.1h244.6c40.5,0,73.4-29.2,73.4-65.1
					C440.2,423.5,440.2,423.1,440.1,422.7z M244.5,27c37.9,0,68.8,30.4,69.6,68.1H174.9C175.7,57.4,206.6,27,244.5,27z M366.8,462
					H122.2c-25.4,0-46-16.8-46.4-37.5l26.8-302.3h45.2v41c0,7.5,6,13.5,13.5,13.5s13.5-6,13.5-13.5v-41h139.3v41
					c0,7.5,6,13.5,13.5,13.5s13.5-6,13.5-13.5v-41h45.2l26.9,302.3C412.8,445.2,392.1,462,366.8,462z"/>
			</svg>'
    );
}

if ($arParams['SHOW_SUBSCRIBE_PAGE'] === 'Y')
{
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_SUBSCRIBE'],
        "name" => Loc::getMessage("SPS_SUBSCRIBE_PAGE_NAME"),
        "icon" => '<svg version="1.1" id="Capa_1" width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 469.331 469.331" style="enable-background:new 0 0 469.331 469.331;" xml:space="preserve">
			<path d="M438.931,30.403c-40.4-40.5-106.1-40.5-146.5,0l-268.6,268.5c-2.1,2.1-3.4,4.8-3.8,7.7l-19.9,147.4
				c-0.6,4.2,0.9,8.4,3.8,11.3c2.5,2.5,6,4,9.5,4c0.6,0,1.2,0,1.8-0.1l88.8-12c7.4-1,12.6-7.8,11.6-15.2c-1-7.4-7.8-12.6-15.2-11.6
				l-71.2,9.6l13.9-102.8l108.2,108.2c2.5,2.5,6,4,9.5,4s7-1.4,9.5-4l268.6-268.5c19.6-19.6,30.4-45.6,30.4-73.3
				S458.531,49.903,438.931,30.403z M297.631,63.403l45.1,45.1l-245.1,245.1l-45.1-45.1L297.631,63.403z M160.931,416.803l-44.1-44.1
				l245.1-245.1l44.1,44.1L160.931,416.803z M424.831,152.403l-107.9-107.9c13.7-11.3,30.8-17.5,48.8-17.5c20.5,0,39.7,8,54.2,22.4
				s22.4,33.7,22.4,54.2C442.331,121.703,436.131,138.703,424.831,152.403z"/>
		</svg>'
    );
}

$availablePages[] = array(
    "path" => "/personal/subscribe/",
    "name" => Loc::getMessage("SPS_SUBSCRIBE_PAGE_NAME"),
    "icon" => '<svg version="1.1" id="Capa_1" width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		 viewBox="0 0 469.331 469.331" style="enable-background:new 0 0 469.331 469.331;" xml:space="preserve">
		<path d="M438.931,30.403c-40.4-40.5-106.1-40.5-146.5,0l-268.6,268.5c-2.1,2.1-3.4,4.8-3.8,7.7l-19.9,147.4
			c-0.6,4.2,0.9,8.4,3.8,11.3c2.5,2.5,6,4,9.5,4c0.6,0,1.2,0,1.8-0.1l88.8-12c7.4-1,12.6-7.8,11.6-15.2c-1-7.4-7.8-12.6-15.2-11.6
			l-71.2,9.6l13.9-102.8l108.2,108.2c2.5,2.5,6,4,9.5,4s7-1.4,9.5-4l268.6-268.5c19.6-19.6,30.4-45.6,30.4-73.3
			S458.531,49.903,438.931,30.403z M297.631,63.403l45.1,45.1l-245.1,245.1l-45.1-45.1L297.631,63.403z M160.931,416.803l-44.1-44.1
			l245.1-245.1l44.1,44.1L160.931,416.803z M424.831,152.403l-107.9-107.9c13.7-11.3,30.8-17.5,48.8-17.5c20.5,0,39.7,8,54.2,22.4
			s22.4,33.7,22.4,54.2C442.331,121.703,436.131,138.703,424.831,152.403z"/>
	</svg>'
);
/*<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 342.564 342.564">
	<path d="M318.624,50.159c-45.418-44.829-91.676-33.627-124.713,0l-22.675,22.675l-22.675-22.675 c-33.038-33.627-79.408-45.486-124.713,0c-33.264,33.4-30.952,90.655,2.086,124.26l145.28,147.819l145.28-147.819 C349.553,140.814,352.161,83.264,318.624,50.159z M295.927,163.534L171.213,288.248L46.5,163.534 c-29.614-30.135-29.614-71.903,0-102.038s61.087-18.798,90.701,11.338l34.013,34.013l34.013-34.013 c29.614-30.135,61.087-41.473,90.701-11.338C325.54,91.609,325.54,133.399,295.927,163.534z"/>
		</svg>

		''*/
if ($arParams['SHOW_CONTACT_PAGE'] === 'Y')
{
    $availablePages[] = array(
        "path" => $arParams['PATH_TO_CONTACT'],
        "name" => Loc::getMessage("SPS_CONTACT_PAGE_NAME"),
        "icon" => '<i class="fa fa-info-circle"></i>'
    );
}

$arGroups = \Bitrix\Main\UserTable::getUserGroupIds($GLOBALS['USER']->GetID());
$intersect = array_intersect($arGroups, \Kosmos\Export::ALLOW_GROUPS);
if(!empty($intersect))
{
    $availablePages[] = array(
        "path" => "/personal/export/",
        "name" => Loc::getMessage("SPS_EXPORT_PAGE_NAME"),
        "icon" => '<svg version="1.1" id="Capa_1" width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">
	<path d="M50.95,12.187l-0.771-0.771L40.084,1.321L39.313,0.55C38.964,0.201,38.48,0,37.985,0H8.963C7.777,0,6.5,0.916,6.5,2.926V39
		v16.537V56c0,0.837,0.842,1.653,1.838,1.91c0.05,0.013,0.098,0.032,0.15,0.042C8.644,57.983,8.803,58,8.963,58h40.074
		c0.16,0,0.319-0.017,0.475-0.048c0.052-0.01,0.1-0.029,0.15-0.042C50.658,57.653,51.5,56.837,51.5,56v-0.463V39V13.978
		C51.5,13.211,51.408,12.645,50.95,12.187z M47.935,12H39.5V3.565L47.935,12z M8.963,56c-0.071,0-0.135-0.026-0.198-0.049
		C8.609,55.877,8.5,55.721,8.5,55.537V41h41v14.537c0,0.184-0.109,0.339-0.265,0.414C49.172,55.974,49.108,56,49.037,56H8.963z
		 M8.5,39V2.926C8.5,2.709,8.533,2,8.963,2h28.595C37.525,2.126,37.5,2.256,37.5,2.391V14h11.609c0.135,0,0.264-0.025,0.39-0.058
		c0,0.015,0.001,0.021,0.001,0.036V39H8.5z"/>
	<path d="M18.034,45.954c0.241-0.306,0.515-0.521,0.82-0.649c0.305-0.128,0.626-0.191,0.964-0.191c0.301,0,0.59,0.06,0.868,0.178
		c0.278,0.118,0.531,0.31,0.759,0.574l1.135-1.012c-0.374-0.364-0.798-0.638-1.271-0.82c-0.474-0.183-0.984-0.273-1.531-0.273
		c-0.593,0-1.144,0.111-1.654,0.335c-0.511,0.224-0.955,0.549-1.333,0.978c-0.378,0.429-0.675,0.964-0.889,1.606
		c-0.214,0.643-0.321,1.388-0.321,2.235s0.107,1.595,0.321,2.242c0.214,0.647,0.51,1.185,0.889,1.613
		c0.378,0.429,0.82,0.752,1.326,0.971s1.06,0.328,1.661,0.328c0.547,0,1.057-0.091,1.531-0.273c0.474-0.183,0.897-0.456,1.271-0.82
		l-1.135-0.998c-0.237,0.265-0.499,0.456-0.786,0.574s-0.595,0.178-0.923,0.178s-0.641-0.07-0.937-0.212
		c-0.296-0.142-0.561-0.364-0.793-0.67s-0.415-0.699-0.547-1.183c-0.132-0.483-0.203-1.066-0.212-1.75
		c0.009-0.702,0.082-1.294,0.219-1.777S17.792,46.26,18.034,45.954z"/>
	<path d="M29.532,49.064c-0.314-0.228-0.654-0.422-1.019-0.581c-0.365-0.159-0.702-0.323-1.012-0.492
		c-0.31-0.169-0.57-0.364-0.779-0.588c-0.21-0.224-0.314-0.518-0.314-0.882c0-0.146,0.036-0.299,0.109-0.458
		c0.073-0.159,0.173-0.303,0.301-0.431c0.127-0.128,0.273-0.234,0.438-0.321s0.337-0.139,0.52-0.157
		c0.328-0.027,0.597-0.032,0.807-0.014c0.209,0.019,0.378,0.05,0.506,0.096c0.127,0.046,0.226,0.091,0.294,0.137
		s0.13,0.082,0.185,0.109c0.009-0.009,0.036-0.055,0.082-0.137c0.045-0.082,0.1-0.185,0.164-0.308
		c0.063-0.123,0.132-0.255,0.205-0.396c0.073-0.142,0.137-0.271,0.191-0.39c-0.265-0.173-0.611-0.299-1.039-0.376
		c-0.429-0.077-0.853-0.116-1.271-0.116c-0.41,0-0.8,0.063-1.169,0.191s-0.693,0.313-0.971,0.554
		c-0.278,0.241-0.499,0.535-0.663,0.882s-0.246,0.743-0.246,1.189c0,0.492,0.104,0.902,0.314,1.23
		c0.209,0.328,0.474,0.613,0.793,0.854c0.319,0.241,0.661,0.451,1.025,0.629c0.364,0.178,0.704,0.355,1.019,0.533
		s0.576,0.376,0.786,0.595c0.209,0.219,0.314,0.483,0.314,0.793c0,0.511-0.148,0.896-0.444,1.155c-0.296,0.26-0.723,0.39-1.278,0.39
		c-0.183,0-0.378-0.019-0.588-0.055c-0.21-0.036-0.419-0.084-0.629-0.144c-0.21-0.06-0.413-0.123-0.608-0.191
		c-0.196-0.068-0.358-0.139-0.485-0.212l-0.287,1.176c0.155,0.137,0.339,0.253,0.554,0.349c0.214,0.096,0.439,0.171,0.677,0.226
		c0.237,0.055,0.472,0.094,0.704,0.116s0.458,0.034,0.677,0.034c0.51,0,0.966-0.077,1.367-0.232
		c0.401-0.155,0.738-0.362,1.012-0.622s0.485-0.561,0.636-0.902s0.226-0.695,0.226-1.06c0-0.538-0.105-0.978-0.314-1.319
		C30.108,49.577,29.847,49.292,29.532,49.064z"/>
	<polygon points="36.115,52.619 33.777,43.924 31.904,43.924 35.035,54.055 37.168,54.055 40.449,43.924 38.59,43.924 	"/>
	<path d="M24.5,13h-12v4v2v2v2v2v2v2v2v4h10h2h21v-4v-2v-2v-2v-2v-2v-4h-21V13z M14.5,19h8v2h-8V19z M14.5,23h8v2h-8V23z M14.5,27h8
		v2h-8V27z M22.5,33h-8v-2h8V33z M43.5,33h-19v-2h19V33z M43.5,29h-19v-2h19V29z M43.5,25h-19v-2h19V25z M43.5,19v2h-19v-2H43.5z
		 M14.5,17v-2h8v2H14.5z"/>
</svg>'
    );
}

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{

    $delimeter = ($arParams['SEF_MODE'] === 'Y') ? "?" : "&";
    $availablePages[] = array(
        "path" => "/personal/list/",
        "name" => "Лист Ожидания",
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 342.564 342.564">
	<path d="M318.624,50.159c-45.418-44.829-91.676-33.627-124.713,0l-22.675,22.675l-22.675-22.675 c-33.038-33.627-79.408-45.486-124.713,0c-33.264,33.4-30.952,90.655,2.086,124.26l145.28,147.819l145.28-147.819 C349.553,140.814,352.161,83.264,318.624,50.159z M295.927,163.534L171.213,288.248L46.5,163.534 c-29.614-30.135-29.614-71.903,0-102.038s61.087-18.798,90.701,11.338l34.013,34.013l34.013-34.013 c29.614-30.135,61.087-41.473,90.701-11.338C325.54,91.609,325.54,133.399,295.927,163.534z"/>
		</svg>
'
    );
}
/*
$availablePages[] = array(
    "path" => "/personal/subscribe-product/",
    "name" => 'Подписки на товары',
    "icon" => '<svg version="1.1" id="Capa_1" width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		 viewBox="0 0 469.331 469.331" style="enable-background:new 0 0 469.331 469.331;" xml:space="preserve">
		<path d="M438.931,30.403c-40.4-40.5-106.1-40.5-146.5,0l-268.6,268.5c-2.1,2.1-3.4,4.8-3.8,7.7l-19.9,147.4
			c-0.6,4.2,0.9,8.4,3.8,11.3c2.5,2.5,6,4,9.5,4c0.6,0,1.2,0,1.8-0.1l88.8-12c7.4-1,12.6-7.8,11.6-15.2c-1-7.4-7.8-12.6-15.2-11.6
			l-71.2,9.6l13.9-102.8l108.2,108.2c2.5,2.5,6,4,9.5,4s7-1.4,9.5-4l268.6-268.5c19.6-19.6,30.4-45.6,30.4-73.3
			S458.531,49.903,438.931,30.403z M297.631,63.403l45.1,45.1l-245.1,245.1l-45.1-45.1L297.631,63.403z M160.931,416.803l-44.1-44.1
			l245.1-245.1l44.1,44.1L160.931,416.803z M424.831,152.403l-107.9-107.9c13.7-11.3,30.8-17.5,48.8-17.5c20.5,0,39.7,8,54.2,22.4
			s22.4,33.7,22.4,54.2C442.331,121.703,436.131,138.703,424.831,152.403z"/>
	</svg>'
);
*/
$result = \Bitrix\Main\UserTable::getList([
    'select' => ['UF_DISCOUNT_INFO'],
    'limit' => 1,
    'filter' => ['ID' => $GLOBALS['USER']->GetID()]
])->fetch();
if($result['UF_DISCOUNT_INFO'])
{
    $availablePages[] = array(
        "path" => "/personal/discount-info/",
        "name" => Loc::getMessage("SPS_DISCOUNT_INFO_PAGE_NAME"),
        "icon" => '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 480.064 480.064" style="enable-background:new 0 0 480.064 480.064;" xml:space="preserve" width="60" height="60">
<path d="M344.032,128.064h-256c-4.418,0-8,3.582-8,8s3.582,8,8,8h256c4.418,0,8-3.582,8-8S348.451,128.064,344.032,128.064z"/>
			<path d="M392.032,128.064h-16c-4.418,0-8,3.582-8,8s3.582,8,8,8h16c4.418,0,8-3.582,8-8S396.451,128.064,392.032,128.064z"/>
			<path d="M479.936,470.864l-56-368c-0.593-3.908-3.951-6.797-7.904-6.8h-72.408c-4.328-57.217-54.22-100.091-111.437-95.763
				c-51.198,3.873-91.89,44.564-95.763,95.763H64.032c-3.953,0.003-7.311,2.892-7.904,6.8l-56,368
				c-0.663,4.368,2.341,8.447,6.709,9.109c0.395,0.06,0.795,0.09,1.195,0.091h464c4.418-0.003,7.998-3.587,7.995-8.005
				C480.027,471.658,479.996,471.259,479.936,470.864z M240.032,16.064c45.47,0.06,83.421,34.722,87.592,80h-175.2
				C156.596,50.78,194.556,16.116,240.032,16.064z M17.344,464.064l53.6-352H409.16l53.6,352H17.344z"/>
			<path d="M304.06,193.289c-3.811-2.235-8.713-0.957-10.948,2.854l-120,207.84c-0.701,1.214-1.07,2.59-1.072,3.992
				c-0.005,4.418,3.573,8.004,7.992,8.008c2.861,0.003,5.506-1.522,6.936-4l120-207.84
				C309.131,200.339,307.835,195.503,304.06,193.289z"/>
			<path d="M199.976,272.064c2.815-0.003,5.617-0.374,8.336-1.104c14.021-3.775,23.762-16.491,23.757-31.011
				c-0.006-17.731-14.384-32.099-32.115-32.093c-17.731,0.006-32.099,14.384-32.093,32.115
				C167.868,257.701,182.246,272.07,199.976,272.064z M200.032,224.064c2.809,0,5.568,0.74,8,2.144c4.95,2.858,8,8.141,7.999,13.857
				c0,8.837-7.164,16-16.001,15.999c-8.837,0-16-7.164-15.999-16.001C184.032,231.227,191.196,224.063,200.032,224.064z"/>
			<path d="M279.955,335.856c-17.731,0.006-32.099,14.384-32.093,32.115c0.006,17.731,14.384,32.099,32.115,32.093
				c2.815-0.003,5.617-0.374,8.336-1.104c14.021-3.775,23.762-16.491,23.757-31.011C312.064,350.219,297.686,335.85,279.955,335.856
				z M280.031,384.064c-8.837,0-16-7.164-15.999-16.001c0-8.837,7.164-16,16.001-15.999c2.809,0,5.568,0.74,8,2.144
				c4.95,2.858,8,8.141,7.999,13.857C296.031,376.901,288.868,384.064,280.031,384.064z"/>
</svg>'
    );
}


$rsUserGmi = CUser::GetByID($GLOBALS['USER']->GetID());
$arUserGmi = $rsUserGmi->Fetch();
if($arUserGmi['UF_CODE_STORE']){
	$availablePages[] = array(
		"path" => "/personal/grafik-dostavki/",
		"name" => Loc::getMessage("SPS_DELIVERY_GRAFIC_PAGE_NAME"),
		"icon" => '<svg viewBox="0 -50 511.99831 511" xmlns="http://www.w3.org/2000/svg" style="enable-background:new 0 0 480.064 480.064;" xml:space="preserve" width="60" height="60"><path d="m50.933594 366.457031h-24.238282c-7.351562 0-14.027343-3-18.863281-7.835937-4.832031-4.832032-7.832031-11.507813-7.832031-18.859375v-246.71875c0-7.351563 3-14.027344 7.832031-18.859375 4.835938-4.835938 11.511719-7.835938 18.863281-7.835938h44.363282c5.808594-10.691406 13.125-20.445312 21.660156-28.976562 22.777344-22.78125 54.25-36.871094 89.007812-36.871094 34.757813 0 66.230469 14.089844 89.007813 36.871094 8.535156 8.53125 15.851563 18.285156 21.660156 28.976562h44.363281c7.351563 0 14.027344 3 18.859376 7.835938 4.835937 4.832031 7.835937 11.507812 7.835937 18.859375v68.324219h67.703125c11.570312 0 22.585938 4.691406 31.453125 12.101562 9.03125 7.550781 15.917969 18.085938 18.847656 29.375 9.605469 37.066406 19.214844 74.128906 28.824219 111.191406.828125 3.195313 1.347656 6.214844 1.570312 9.042969.839844 10.570313-1.949218 19.753906-7.476562 27.039063-5.433594 7.160156-13.378906 12.222656-22.988281 14.710937-.289063.074219-.582031.148437-.871094.21875-1.84375 11.632813-7.359375 22.039063-15.320313 30.003906-9.886718 9.882813-23.539062 15.996094-38.621093 15.996094-15.078125 0-28.734375-6.113281-38.617188-15.996094-7.640625-7.640625-13.023437-17.53125-15.078125-28.59375h-214.550781c-2.054687 11.0625-7.441406 20.953125-15.078125 28.59375-9.886719 9.882813-23.539062 15.996094-38.621094 15.996094-15.082031 0-28.734375-6.113281-38.617187-15.996094-7.640625-7.640625-13.027344-17.535156-15.078125-28.59375zm130.792968-134.226562c-5.523437 0-10 4.476562-10 10 0 5.523437 4.476563 10 10 10 5.523438 0 10-4.476563 10-10 0-5.523438-4.476562-10-10-10zm-29.15625 6.742187c-1.824218 5.214844-7.382812 8.011719-12.417968 6.253906-18.039063-6.3125-34.203125-16.609374-47.433594-29.84375-22.78125-22.777343-36.871094-54.246093-36.871094-89.007812 0-13.976562 2.28125-27.417969 6.484375-39.980469h-35.636719c-1.816406 0-3.476562.753907-4.6875 1.960938-1.207031 1.210937-1.960937 2.871093-1.960937 4.6875v246.71875c0 1.816406.753906 3.476562 1.960937 4.683593 1.210938 1.210938 2.871094 1.960938 4.6875 1.960938h24.238282c2.050781-11.0625 7.4375-20.953125 15.078125-28.59375 9.882812-9.882812 23.535156-16 38.617187-16 15.082032 0 28.734375 6.117188 38.617188 16 7.640625 7.640625 13.027344 17.53125 15.078125 28.59375h185.078125v-253.363281c0-1.816407-.75-3.476563-1.960938-4.6875-1.207031-1.207031-2.867187-1.960938-4.683594-1.960938h-35.640624c4.207031 12.5625 6.484374 26.007813 6.484374 39.980469 0 34.761719-14.089843 66.230469-36.867187 89.007812-12.910156 12.910157-28.613281 23.027344-46.121094 29.375-5.113281 1.847657-10.425781-1.316406-12.210937-6.246093-1.882813-5.207031.527344-10.855469 5.386718-12.609375 14.71875-5.335938 27.917969-13.839844 38.769532-24.691406 19.152344-19.152344 30.996094-45.609376 30.996094-74.832032 0-29.226562-11.84375-55.683594-30.996094-74.835937-19.152344-19.148438-45.609375-30.996094-74.832032-30.996094-29.226562 0-55.683593 11.847656-74.835937 30.996094-19.148437 19.152343-30.996094 45.609375-30.996094 74.835937 0 29.222656 11.847657 55.679688 30.996094 74.832032 11.140625 11.140624 24.6875 19.785156 39.882813 25.085937 5.035156 1.761719 7.621093 7.460937 5.796874 12.675781zm12.464844-187.820312v75.226562c0 5.535156 4.488282 10.023438 10.023438 10.023438h45.421875c5.535156 0 10.023437-4.488282 10.023437-10.023438 0-5.539062-4.488281-10.027344-10.023437-10.027344h-35.398438v-65.199218c0-5.535156-4.488281-10.023438-10.023437-10.023438-5.535156-.003906-10.023438 4.484375-10.023438 10.023438zm314.816406 293.195312c3.636719-1.460937 6.582032-3.59375 8.628907-6.289062 2.566406-3.382813 3.839843-7.925782 3.402343-13.414063-.148437-1.855469-.457031-3.726562-.941406-5.597656l-15.046875-58.03125h-112.441406v85.390625h9.425781c2.054688-11.0625 7.4375-20.957031 15.078125-28.59375 9.886719-9.886719 23.539063-16 38.621094-16 15.078125 0 28.734375 6.113281 38.617187 16 7.164063 7.160156 12.34375 16.304688 14.65625 26.535156zm-28.832031-12.363281c-6.253906-6.253906-14.894531-10.121094-24.441406-10.121094s-18.191406 3.867188-24.445313 10.121094c-6.253906 6.257813-10.121093 14.898437-10.121093 24.445313 0 9.546874 3.867187 18.191406 10.121093 24.445312 6.257813 6.253906 14.898438 10.125 24.445313 10.125s18.1875-3.871094 24.445313-10.125c6.253906-6.253906 10.121093-14.898438 10.121093-24.445312 0-9.546876-3.867187-18.1875-10.125-24.445313zm-321.945312 0c-6.253907-6.253906-14.898438-10.121094-24.445313-10.121094s-18.1875 3.867188-24.445312 10.121094c-6.253906 6.257813-10.121094 14.898437-10.121094 24.445313 0 9.546874 3.867188 18.191406 10.121094 24.445312 6.257812 6.253906 14.898437 10.125 24.445312 10.125s18.191406-3.871094 24.445313-10.125c6.253906-6.253906 10.125-14.898438 10.125-24.445312 0-9.546876-3.871094-18.1875-10.125-24.445313zm234.378906-91.015625h107.246094l-8.585938-33.113281c-1.875-7.226563-6.386719-14.054688-12.347656-19.039063-5.421875-4.53125-11.960937-7.398437-18.605469-7.398437h-67.707031zm0 0" fill-rule="evenodd"/></svg>'
	);
}

$customPagesList = CUtil::JsObjectToPhp($arParams['~CUSTOM_PAGES']);
if ($customPagesList)
{
    foreach ($customPagesList as $page)
    {
        $availablePages[] = array(
            "path" => $page[0],
            "name" => $page[1],
            "icon" => (strlen($page[2])) ? '<i class="fa '.htmlspecialcharsbx($page[2]).'"></i>' : ""
        );
    }
}

if (empty($availablePages))
{
    ShowError(Loc::getMessage("SPS_ERROR_NOT_CHOSEN_ELEMENT"));
}
else //$this->GetFolder()."/images/shopping-bag.svg"
{
    ?>
    <div class="row sale-personal-section-index">
        <div class="sale-personal-section-row-flex">
            <?
            foreach ($availablePages as $blockElement)
            {
                ?>
                <div class="sale-personal__col">
                    <div class="sale-personal-section-index-block">
                        <a class="sale-personal-section-index-block-link" href="<?=htmlspecialcharsbx($blockElement['path'])?>">
							<span class="sale-personal-section-index-block-ico">
								<?=$blockElement['icon']?>
							</span>
                            <h2 class="sale-personal-section-index-block-name">
                                <?=htmlspecialcharsbx($blockElement['name'])?>
                            </h2>
                        </a>
                    </div>
                </div>
                <?
            }
            ?>
        </div>
    </div>
    <br>
    <form action="" style="text-align: center;">
        <input type="hidden" value="yes" name="logout">
        <input type="submit" class="btn btn_black" value="Выйти из личного кабинета">
    </form>
    <?
}
?>