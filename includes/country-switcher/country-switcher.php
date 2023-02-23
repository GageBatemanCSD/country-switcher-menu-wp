<?php
///**
// * Custom navigation country switcher for Kyani
// */
//
//function add_country_selector_to_menu()
//{
//	$sites = get_sites(['public' => 1]);
//	$current_site_id = get_current_blog_id();
//	$new_nav_item = "";
//
//	$url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING);
//	if (strpos($url, 'events') !== false) {
//		$parts = parse_url($url);
//		$country_path = explode('/', $parts[path]);
//		$current_site_country_code = $country_path[3];
//	} else {
//		$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);
//	}
//
//	// since the main site is for the US but contains no country code assign it to $current_site_country_code
//	if ($current_site_country_code == "") {
//		$current_site_country_code = "us";
//	}
//
//
//	$new_nav_item .= '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">';
//	$new_nav_item .= '<img src="' . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $current_site_country_code . '.svg" width="24">' . '</a>';
//	$new_nav_item .= '<ul class="dropdown-menu dropdown-menu-right" id="countries" aria-labelledby="navbarDropdown">';
//
//	$new_nav_item .= buildDropdownItems($sites);
//	$new_nav_item .= '</ul>';
//	return $new_nav_item;
//
//}
//
//add_shortcode('countrySwitcher', 'add_country_selector_to_menu');
//function add_country_selector_to_mobile($items, $args)
//{
//	$sites = get_sites(['public' => 1]);
//	$new_nav_item = "";
//
//	if ($args->theme_location == "primary") {
//		$new_nav_item .= "<li class='mobile-only mega-menu-item-has-children mega-menu-item mega-menu-item-type-post_type mega-menu-item-object-page mega-align-bottom-left mega-menu-item-101010 mega-menu-flyout dropdown'><a class='mega-menu-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown'>";
//		$new_nav_item .= __("Country") . "<span class='mega-indicator' data-has-click-event='true'></span></a>";
//		$new_nav_item .= "<ul class='dropdown-menu' id='countries' aria-labelledby='navbarDropdown'>";
//
//		$new_nav_item .= buildDropDownItems($sites);
//		$new_nav_item .= "</ul>";
//		// if there are menu items in the $items array it will add the country switcher as the second menu item
//		if ($items) {
//			while (false !== ($items_pos = strpos($items, '<li', 2))) {
//				$items_array[] = substr($items, 0, $items_pos);
//				$items = substr($items, $items_pos);
//
//			}
//			$items_array[] = $items;
//			array_splice($items_array, 101, 0, $new_nav_item);
//
//			$items = implode('', $items_array);
//			return $items;
//		}
//	}
//	return $new_nav_item . $items;
//}
//
//add_filter('wp_nav_menu_items', 'add_country_selector_to_mobile', 10, 2);
//// function builds the dropdown menu with all the countries
//// function builds the dropdown menu with all the countries
//function buildDropdownItems($sites)
//{
//
//	$country_websites = json_decode(file_get_contents(plugins_url() . '/kyani-wp-SAP/assets/data/sites.json'));
//	$rep = "";
//	if (isset($_SERVER['HTTP_X_KYANI_REP'])) {
//		$rep = explode(';', $_SERVER['HTTP_X_KYANI_REP'])[0];
//	}
//	$dropdownItems = "";
//
//	foreach ($country_websites->regions as $region) {
//		$dropdownItems .= '<li class="dropdown-header">' . $region->name . '</li>';
//
//		foreach ($region->countries as $country) {
//			foreach ($sites as $subsite) {
//				$subsite->object_id = get_object_vars($subsite)['blog_id'];
//				$url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING);
//				$subsite->url = get_blog_details($subsite->object_id)->siteurl;
//				$subsite->displayname = get_blog_details($subsite->object_id)->blogname;
//				$subsite->path = str_replace("/", "", get_blog_details($subsite->object_id)->path);
//			}
//			if ($country->code == 'cn') {
//				$dropdownItems .= "<a class='dropdown-item' href='" . $country->url . "'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</a>";
//			} else {
//				if (strpos($url, 'events') !== false) {
//					$dropdownItems .= "<a class='dropdown-item' href='//" . ($rep != "" ? $rep . '.' : "") . $_SERVER['HTTP_HOST'] . "/events" . $country->events_name . "'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</a>";
//				} else {
//					$dropdownItems .= "<a class='dropdown-item' href='//" . ($rep != "" ? $rep . '.' : "") . $_SERVER['HTTP_HOST'] . $country->url . "'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</a>";
//				}
//			}
//		}
//		$dropdownItems .= '<li class="dropdown-divider"></li>';
//	}
//	return $dropdownItems;
//}
//
//?>

<?php
/**
 * Custom navigation country switcher for Kyani
 */


function add_country_selector_to_menu($items, $args)
{
	register_files();
	$sites = get_sites(['public' => 1]);
	$current_site_id = get_current_blog_id();
	$new_nav_item = "";

	$url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING);
	if (strpos($url, 'events') !== false) {
		if (strpos($url, 'event') !== false) {
			$current_site_country_code = 'event';
		} else {
			$parts = $_SERVER['REQUEST_URI'];
			$country_path = explode('/', $parts);
			$current_site_country_code = $country_path[3];
		}
	} else {
		$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);
	}

	// since the main site is for the US but contains no country code assign it to $current_site_country_code
	if ($current_site_country_code == "") {
		$current_site_country_code = "us";
	}


	$new_nav_item .= '<li class="nav-item" id="countryDisplayToggle" onclick="toggleCountryDisplay()"><a class="nav-link" href="#" id="navbarDropdown" role="button">';
	$new_nav_item .= '<img src="' . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $current_site_country_code . '.svg" width="24">' . '</a></li>';
	$new_nav_item .= '<div id="countries" style="display: none;">';

	$new_nav_item .= countrySwitcherBuilder($sites);
	$new_nav_item .= '</div>';
	return $new_nav_item;

}

add_shortcode('countrySwitcher', 'add_country_selector_to_menu');
add_filter('wp_nav_menu_items', 'add_country_selector_to_mobile', 10, 2);
function add_country_selector_to_mobile($items, $args)
{
	$sites = get_sites(['public' => 1]);
	$new_nav_item = "";

	if ($args->theme_location == "primary") {
		$new_nav_item .= '<li class="mobile-only mega-menu-item mega-menu-item-has-children mega-menu-item-type-post_type mega-menu-item-object-page mega-align-bottom-left mega-menu-flyout mega-menu-item-1010102" id="countryDisplayToggle" onclick="toggleCountryDisplayMobile()"><a class="mega-menu-link"id="navbarDropdown" role="button">';
		$new_nav_item .= __("Country") . '<span class="mega-indicator" data-has-click-event="true"></span></a></li>';
		$new_nav_item .= '<div id="countriesMobile" style="display: none;">';

		$new_nav_item .= countrySwitcherBuilderMobile($sites);
		$new_nav_item .= '</div>';
		if ($items) {
			while (false !== ($items_pos = strpos($items, '<li', 2))) {
				$items_array[] = substr($items, 0, $items_pos);
				$items = substr($items, $items_pos);

			}
			$items_array[] = $items;
			array_splice($items_array, 2001, 0, $new_nav_item);

			$items = implode('', $items_array);
			return $items;
		}
	}
	return $new_nav_item . $items;
}

// function builds the dropdown menu with all the countries
function countrySwitcherBuilder($sites)
{
	register_files();
	$country_websites = json_decode(file_get_contents(plugins_url() . '/kyani-wp-SAP/assets/data/sites.json'));
	$rep = "";
	if (isset($_SERVER['HTTP_X_KYANI_REP'])) {
		$rep = explode(';', $_SERVER['HTTP_X_KYANI_REP'])[0];
	}
	$dropdownItems = "<div class='countriesHeader '>";

	foreach ($country_websites->regions as $region) {
		$dropdownItems .= '<div id="' . $region->className . '-header">' . $region->name . '</div>';
	}
	$dropdownItems .= '</div>';
	foreach ($country_websites->regions as $region) {
		$dropdownItems .= '<div class="country-dropdown-list" id="' . $region->className . '-list">';
		foreach ($region->countries as $country) {
			foreach ($sites as $subsite) {
				$subsite->object_id = get_object_vars($subsite)['blog_id'];
				$url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING);
				$subsite->url = get_blog_details($subsite->object_id)->siteurl;
				$subsite->displayname = get_blog_details($subsite->object_id)->blogname;
				$subsite->path = str_replace("/", "", get_blog_details($subsite->object_id)->path);
			}
			$dropdownItems .= "<div class='country-dropdown-item-container'>";
			if ($country->code == 'cn') {
				$dropdownItems .= "<div class='country-dropdown-item''><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</div>";
			} else {
				$dropdownItems .= "<div class='country-dropdown-item'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</div>";
			}
			$dropdownItems .= "<br><div class='d-flex flex-row country-dropdown-language'>";
			foreach ($country->languages as $language) {
				if ($country->code == 'cn') {
					$dropdownItems .= "<a href='https://kyani.cn'>";
				} else {
					if (strpos($url, 'events') !== false) {
						$dropdownItems .= "<a href='//" . ($rep != "" ? $rep . '.' : "") . $_SERVER['HTTP_HOST'] . "/events" . $language->events_url . "'>";
					} else if (strpos($url, 'support') !== false) {
						$dropdownItems .= "<a href='//" . ($rep != "" ? $rep . '.' : "") . $_SERVER['HTTP_HOST'] . $country->url . $language->url . "support'>";
					} else {
						$dropdownItems .= "<a href='//" . ($rep != "" ? $rep . '.' : "") . $_SERVER['HTTP_HOST'] . $country->url . $language->url . "'>";
					}
				}
				$dropdownItems .= $language->name;
				$dropdownItems .= "</a>";
			}
			$dropdownItems .= "</div></div>";
		}
		$dropdownItems .= '</div>';
	}

	$dropdownItems .= '<script>let northAmericaHeader = document.getElementById("northamerica-header");
let centralAmericaHeader = document.getElementById("centralamerica-header");
let europeHeader = document.getElementById("europe-header");
let chinaHeader = document.getElementById("china-header");
let asiaHeader = document.getElementById("asia-header");
let northAmericaList = document.getElementById("northamerica-list");
let centralAmericaList = document.getElementById("centralamerica-list");
let europeList = document.getElementById("europe-list");
let chinaList = document.getElementById("china-list");
let asiaList = document.getElementById("asia-list");


northAmericaHeader.addEventListener("click", togglenorthamerica);
centralAmericaHeader.addEventListener("click", togglecentralamerica);
europeHeader.addEventListener("click", toggleeurope);
chinaHeader.addEventListener("click", togglechina);
asiaHeader.addEventListener("click", toggleasia);
function togglenorthamerica() {
  northAmericaHeader.style.borderBottom = "2px solid #212121";
  centralAmericaHeader.style.borderBottom = "1px solid #2121211a";
  europeHeader.style.borderBottom = "1px solid #2121211a";
  chinaHeader.style.borderBottom = "1px solid #2121211a";
  asiaHeader.style.borderBottom = "1px solid #2121211a";
  northAmericaHeader.style.fontWeight = "bold";
  centralAmericaHeader.style.fontWeight = "normal";
  europeHeader.style.fontWeight = "normal";
  chinaHeader.style.fontWeight = "normal";
  asiaHeader.style.fontWeight = "normal";
  northAmericaList.style.display = "flex";
  centralAmericaList.style.display = "none";
  europeList.style.display = "none";
  chinaList.style.display = "none";
  asiaList.style.display = "none";
}

function togglecentralamerica() {
  northAmericaHeader.style.borderBottom = "1px solid #2121211a";
  centralAmericaHeader.style.borderBottom = "2px solid #212121";
  europeHeader.style.borderBottom = "1px solid #2121211a";
  chinaHeader.style.borderBottom = "1px solid #2121211a";
  asiaHeader.style.borderBottom = "1px solid #2121211a";
  northAmericaHeader.style.fontWeight = "normal";
  centralAmericaHeader.style.fontWeight = "bold";
  europeHeader.style.fontWeight = "normal";
  chinaHeader.style.fontWeight = "normal";
  asiaHeader.style.fontWeight = "normal";
  northAmericaList.style.display = "none";
  centralAmericaList.style.display = "flex";
  europeList.style.display = "none";
  chinaList.style.display = "none";
  asiaList.style.display = "none";
}

function toggleeurope() {
  northAmericaHeader.style.borderBottom = "1px solid #2121211a";
  centralAmericaHeader.style.borderBottom = "1px solid #2121211a";
  europeHeader.style.borderBottom = "2px solid #212121";
  chinaHeader.style.borderBottom = "1px solid #2121211a";
  asiaHeader.style.borderBottom = "1px solid #2121211a";
  northAmericaHeader.style.fontWeight = "normal";
  centralAmericaHeader.style.fontWeight = "normal";
  europeHeader.style.fontWeight = "bold";
  chinaHeader.style.fontWeight = "normal";
  asiaHeader.style.fontWeight = "normal";
  northAmericaList.style.display = "none";
  centralAmericaList.style.display = "none";
  europeList.style.display = "flex";
  chinaList.style.display = "none";
  asiaList.style.display = "none";
}

function togglechina() {
  northAmericaHeader.style.borderBottom = "1px solid #2121211a";
  centralAmericaHeader.style.borderBottom = "1px solid #2121211a";
  europeHeader.style.borderBottom = "1px solid #2121211a";
  chinaHeader.style.borderBottom = "2px solid #212121";
  asiaHeader.style.borderBottom = "1px solid #2121211a";
  northAmericaHeader.style.fontWeight = "normal";
  centralAmericaHeader.style.fontWeight = "normal";
  europeHeader.style.fontWeight = "normal";
  chinaHeader.style.fontWeight = "bold";
  asiaHeader.style.fontWeight = "normal";
  northAmericaList.style.display = "none";
  centralAmericaList.style.display = "none";
  europeList.style.display = "none";
  chinaList.style.display = "flex";
  asiaList.style.display = "none";
}

function toggleasia() {
  northAmericaHeader.style.borderBottom = "1px solid #2121211a";
  centralAmericaHeader.style.borderBottom = "1px solid #2121211a";
  europeHeader.style.borderBottom = "1px solid #2121211a";
  chinaHeader.style.borderBottom = "1px solid #2121211a";
  asiaHeader.style.borderBottom = "2px solid #212121";
  northAmericaHeader.style.fontWeight = "normal";
  centralAmericaHeader.style.fontWeight = "normal";
  europeHeader.style.fontWeight = "normal";
  chinaHeader.style.fontWeight = "normal";
  asiaHeader.style.fontWeight = "bold";
  northAmericaList.style.display = "none";
  centralAmericaList.style.display = "none";
  europeList.style.display = "none";
  chinaList.style.display = "none";
  asiaList.style.display = "flex";
}
</script>';
	return $dropdownItems;
}

// function builds the dropdown menu with all the countries
function countrySwitcherBuilderMobile($sites)
{
	register_files();
	$country_websites = json_decode(file_get_contents(plugins_url() . '/kyani-wp-SAP/assets/data/sites.json'));
	$rep = "";
	if (isset($_SERVER['HTTP_X_KYANI_REP'])) {
		$rep = explode(';', $_SERVER['HTTP_X_KYANI_REP'])[0];
	}
	$dropdownItems = "<div class='countriesHeaderMobile'>";

	foreach ($country_websites->regions as $region) {
		$dropdownItems .= '<li class="mega-menu-item mega-menu-item-type-custom mega-menu-item-object-custom mega-menu-item-has-children mega-has-icon mega-icon-right mega-collapse-children country-region-container"><span  class="countriesHeadersM" id="' . $region->className . '-headerMobile">' . $region->name . '</span><div class="country-dropdown-list" id="' . $region->className . '-listMobile">';
		foreach ($region->countries as $country) {
			foreach ($sites as $subsite) {
				$subsite->object_id = get_object_vars($subsite)['blog_id'];
				$url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING);
				$subsite->url = get_blog_details($subsite->object_id)->siteurl;
				$subsite->displayname = get_blog_details($subsite->object_id)->blogname;
				$subsite->path = str_replace("/", "", get_blog_details($subsite->object_id)->path);
			}
			$dropdownItems .= '<div class="countries-mobile-container">';
			if ($country->code == 'cn') {
				$dropdownItems .= "<div class='country-dropdown-item' href='" . $country->url . "'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</div>";
			} else {
				if (strpos($url, 'events') !== false) {
					$dropdownItems .= "<div class='country-dropdown-item' href='//" . ($rep != "" ? $rep . '.' : "") . $_SERVER['HTTP_HOST'] . "/events" . $country->events_name . "'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</div>";
				} else {
					$dropdownItems .= "<div class='country-dropdown-item' href='//" . ($rep != "" ? $rep . '.' : "") . $_SERVER['HTTP_HOST'] . $country->url . "'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</div>";
				}
			}
			$dropdownItems .= "<div class='d-flex flex-row country-dropdown-language'>";
			foreach ($country->languages as $language) {
				$dropdownItems .= "<a href='//" . ($rep != "" ? $rep . '.' : "") . $_SERVER['HTTP_HOST'] . $country->url . $language->url . "'>";
				$dropdownItems .= $language->name;
				$dropdownItems .= "</a>";
			}
			$dropdownItems .= "</div></div>";
		}
		$dropdownItems .= '</li>';
	}
	$dropdownItems .= '</div><script>let northAmericaHeaderMobile = document.getElementById("northamerica-headerMobile");
let centralAmericaHeaderMobile = document.getElementById("centralamerica-headerMobile");
let europeHeaderMobile = document.getElementById("europe-headerMobile");
let chinaHeaderMobile = document.getElementById("china-headerMobile");
let asiaHeaderMobile = document.getElementById("asia-headerMobile");
let northAmericaListMobile = document.getElementById("northamerica-listMobile");
let centralAmericaListMobile = document.getElementById("centralamerica-listMobile");
let europeListMobile = document.getElementById("europe-listMobile");
let chinaListMobile = document.getElementById("china-listMobile");
let asiaListMobile = document.getElementById("asia-listMobile");
northAmericaHeaderMobile.addEventListener("click", togglenorthamericamobile);
centralAmericaHeaderMobile.addEventListener("click", togglecentralamericamobile);
europeHeaderMobile.addEventListener("click", toggleeuropemobile);
chinaHeaderMobile.addEventListener("click", togglechinamobile);
asiaHeaderMobile.addEventListener("click", toggleasiamobile);
function togglenorthamericamobile() {
  northAmericaHeaderMobile.style.fontWeight = "bold";
  centralAmericaHeaderMobile.style.fontWeight = "normal";
  europeHeaderMobile.style.fontWeight = "normal";
  chinaHeaderMobile.style.fontWeight = "normal";
  asiaHeaderMobile.style.fontWeight = "normal";
  northAmericaListMobile.style.display = "flex";
  centralAmericaListMobile.style.display = "none";
  europeListMobile.style.display = "none";
  chinaListMobile.style.display = "none";
  asiaListMobile.style.display = "none";
}

function togglecentralamericamobile() {
  northAmericaHeaderMobile.style.fontWeight = "normal";
  centralAmericaHeaderMobile.style.fontWeight = "bold";
  europeHeaderMobile.style.fontWeight = "normal";
  chinaHeaderMobile.style.fontWeight = "normal";
  asiaHeaderMobile.style.fontWeight = "normal";
  northAmericaListMobile.style.display = "none";
  centralAmericaListMobile.style.display = "flex";
  europeListMobile.style.display = "none";
  chinaListMobile.style.display = "none";
  asiaListMobile.style.display = "none";
}

function toggleeuropemobile() {
  northAmericaHeaderMobile.style.fontWeight = "normal";
  centralAmericaHeaderMobile.style.fontWeight = "normal";
  europeHeaderMobile.style.fontWeight = "bold";
  chinaHeaderMobile.style.fontWeight = "normal";
  asiaHeaderMobile.style.fontWeight = "normal";
  northAmericaListMobile.style.display = "none";
  centralAmericaListMobile.style.display = "none";
  europeListMobile.style.display = "flex";
  chinaListMobile.style.display = "none";
  asiaListMobile.style.display = "none";
}

function togglechinamobile() {
  northAmericaHeaderMobile.style.fontWeight = "normal";
  centralAmericaHeaderMobile.style.fontWeight = "normal";
  europeHeaderMobile.style.fontWeight = "normal";
  chinaHeaderMobile.style.fontWeight = "bold";
  asiaHeaderMobile.style.fontWeight = "normal";
  northAmericaListMobile.style.display = "none";
  centralAmericaListMobile.style.display = "none";
  europeListMobile.style.display = "none";
  chinaListMobile.style.display = "flex";
  asiaListMobile.style.display = "none";
}

function toggleasiamobile() {
  northAmericaHeaderMobile.style.fontWeight = "normal";
  centralAmericaHeaderMobile.style.fontWeight = "normal";
  europeHeaderMobile.style.fontWeight = "normal";
  chinaHeaderMobile.style.fontWeight = "normal";
  asiaHeaderMobile.style.fontWeight = "bold";
  northAmericaListMobile.style.display = "none";
  centralAmericaListMobile.style.display = "none";
  europeListMobile.style.display = "none";
  chinaListMobile.style.display = "none";
  asiaListMobile.style.display = "flex";
}

</script>';
	return $dropdownItems;
}

function register_files()
{
	wp_enqueue_style('product-carousel-style', plugins_url() . '/kyani-wp-SAP/includes/country-switcher/assets/css/style.css');
	wp_enqueue_script('product-carousel-scri', plugins_url() . '/kyani-wp-SAP/includes/country-switcher/assets/js/script.js');
}
