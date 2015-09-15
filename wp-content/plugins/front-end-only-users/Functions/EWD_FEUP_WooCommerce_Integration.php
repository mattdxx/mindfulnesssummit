<?php

$WooCommerce_Integration = get_option('EWD_FEUP_WooCommerce_Integration');
if ($WooCommerce_Integration == "Yes" and $EWD_FEUP_Full_Version == "Yes") {
	add_filter('woocommerce_checkout_fields', 'EWD_FEUP_WooCommerce_Field_Overide');
}

function EWD_FEUP_WooCommerce_Field_Overide($fields) {
	$User  = new FEUP_User;

	$First_Name_Field = get_option('EWD_FEUP_WooCommerce_First_Name_Field');
	$Last_Name_Field = get_option('EWD_FEUP_WooCommerce_Last_Name_Field');
	$Company_Field = get_option('EWD_FEUP_WooCommerce_Company_Field');
	$Address_Line_One_Field = get_option('EWD_FEUP_WooCommerce_Address_Line_One_Field');
	$Address_Line_Two_Field = get_option('EWD_FEUP_WooCommerce_Address_Line_Two_Field');
	$City_Field = get_option('EWD_FEUP_WooCommerce_City_Field');
	$Postcode_Field = get_option('EWD_FEUP_WooCommerce_Postcode_Field');
	$Country_Field = get_option('EWD_FEUP_WooCommerce_Country_Field');
	$State_Field = get_option('EWD_FEUP_WooCommerce_State_Field');
	$Email_Field = get_option('EWD_FEUP_WooCommerce_Email_Field');
	$Phone_Field = get_option('EWD_FEUP_WooCommerce_Phone_Field');

	$First_Name = $User->Get_Field_Value($First_Name_Field);
	$Last_Name = $User->Get_Field_Value($Last_Name_Field);
	$Company = $User->Get_Field_Value($Company_Field);
	$Address_Line_One = $User->Get_Field_Value($Address_Line_One_Field);
	$Address_Line_Two = $User->Get_Field_Value($Address_Line_Two_Field);
	$City = $User->Get_Field_Value($City_Field);
	$Postcode = $User->Get_Field_Value($Postcode_Field);
	$Country = $User->Get_Field_Value($Country_Field);
	$State = $User->Get_Field_Value($State_Field);
	$Email = $User->Get_Field_Value($Email_Field);
	$Phone = $User->Get_Field_Value($Phone_Field);

	if ($First_Name != "") {
		$fields['billing']['billing_first_name']['default'] = $First_Name;
		$fields['shipping']['shipping_first_name']['default'] = $First_Name;
	}

	if ($Last_Name != "") {
		$fields['billing']['billing_last_name']['default'] = $Last_Name;
		$fields['shipping']['shipping_last_name']['default'] = $Last_Name;
	}

	if ($Company != "") {
		$fields['billing']['billing_company']['default'] = $Company;
		$fields['shipping']['shipping_company']['default'] = $Company;
	}

	if ($Address_Line_One != "") {
		$fields['billing']['billing_address_1']['default'] = $Address_Line_One;
		$fields['shipping']['shipping_address_1']['default'] = $Address_Line_One;
	}

	if ($Address_Line_Two != "") {
		$fields['billing']['billing_address_2']['default'] = $Address_Line_Two;
		$fields['shipping']['shipping_address_2']['default'] = $Address_Line_Two;
	}

	if ($City != "") {
		$fields['billing']['billing_city']['default'] = $City;
		$fields['shipping']['shipping_city']['default'] = $City;
	}

	if ($Postcode != "") {
		$fields['billing']['billing_postcode']['default'] = $Postcode;
		$fields['shipping']['shipping_postcode']['default'] = $Postcode;
	}

	if ($Country != "") {
		$fields['billing']['billing_country']['default'] = $Country;
		$fields['shipping']['shipping_country']['default'] = $Country;
	}

	if ($State != "") {
		$fields['billing']['billing_state']['default'] = $State;
		$fields['shipping']['shipping_state']['default'] = $State;
	}

	if ($Email != "") {
		$fields['billing']['billing_email']['default'] = $Email;
		$fields['shipping']['shipping_email']['default'] = $Email;
	}

	if ($Phone != "") {
		$fields['billing']['billing_phone']['default'] = $Phone;
		$fields['shipping']['shipping_phone']['default'] = $Phone;
	}
    
    return $fields;
}

?>