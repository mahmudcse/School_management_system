<?php
class Applicationconst {
    const TRANSACTION_TYPE_JOURNAL = 'JOURNAL';
    const TRANSACTION_TYPE_PURCHASE = 'PURCHASE';
    const TRANSACTION_TYPE_PURCHASE_RETURN = 'PURCHASE-RETURN';
    const TRANSACTION_TYPE_SALE = 'SALES';
    const TRANSACTION_TYPE_SALE_RETURN = 'SALE-RETURN';
    const TRANSACTION_TYPE_PAYMENT = 'PAYMENT';
    const TRANSACTION_TYPE_RECEIVE = 'RECEIVE';
    const TRANSACTION_TYPE_FEE = 'FEE';
    const TRANSACTION_TYPE_EXPENSE = 'EXPENSE';
    const TRANSACTION_TYPE_SALARY_PAYABLE = 'SALARY PAYABLE';
    
    const ACCOUNT_CAT1_ASSET = 'ASSET';
    const ACCOUNT_CAT1_LIABILITY = 'LIABILITY';
    const ACCOUNT_CAT1_EXPENSE = 'EXPENSE';
    const ACCOUNT_CAT1_REVENUE = 'REVENUE';
    
	const ACCOUNT_CAT2_FEE = 'FEE';
    const ACCOUNT_CAT2_OPERATING_EXPENSE = 'OPERATING EXPENSE';
    const ACCOUNT_CAT2_CURRENT_ASSET = 'CURRENT ASSET';
    const ACCOUNT_CAT2_CURRENT_LIABILITY = 'CURRENT LIABILITY';
    const ACCOUNT_CAT2_OTHER_LIABILITY = 'OTHER LIABILITY';
    const ACCOUNT_CAT2_FIXED_ASSET = 'FIXED ASSET';
    const ACCOUNT_CAT2_REVENUE_FROM_SALE = 'REVENUE FROM SALE';
    
    const ACCOUNT_CAT3_CASH = 'CASH';
    const ACCOUNT_CAT3_BANK = 'BANK';
    
    const ACCOUNT_HEAD_PURCHASE = 12;
    const ACCOUNT_HEAD_SALARY = 2;
    const ACCOUNT_HEAD_STUDENT_FEE = 14;
    const ACCOUNT_HEAD_SALE = 13;
    const ACCOUNT_HEAD_PAYABLE = 5;
    const ACCOUNT_HEAD_RECEIVABLE = 4;
    const ACCOUNT_HEAD_CASH_IN_HAND = 1;
    const ACCOUNT_HEAD_PURCHASE_COMMISSION = 8;
    const ACCOUNT_HEAD_SALE_DISCOUNT = 9;
    const ACCOUNT_HEAD_CASH_ADVANCE = 10;
    const ACCOUNT_HEAD_SUSPENSION = 7;
    
    const ITEM_TYPE_INVENTORY = 'INVENTORY';
	const ITEM_TYPE_FEE = 'FEE';
    const ITEM_TYPE_OTHERS = 'OTHERS';
    
    const ITEM_CASH = 1;
    const ITEM_TAKA = 2;
    const USER_COMPANY = 1;
    const USER_ANNONYMUS = 2;
    
    const TRANSACTION_DOC_CHALLAN = 'CHALLAN';
    const TRANSACTION_DOC_INVOICE = 'INVOICE';
    
    const EMAIL_FROM = 'info@netsoft.com.bd';
	
    public static function checkAndConv($inp){
    	if( gettype ( $inp ) == "double"){
    		return convertNumber($inp);
    	}else {
    		return $inp;
    	}
    }
    public static function convertNumber($inp){
    	
    	return number_format((float)$inp, 2, ".");
    	
    }
    
    public static function convertDate($inp){
    	$date = DateTime::createFromFormat('m/d/Y', $inp);
		return $date->format('Y-m-d');
    	 
    }
	
	 public static function convertWord($inp){
    	
    	return number_format((float)$inp, 2);
    	
    }
	public static function convert_number($number) {
		if (($number < 0) || ($number > 999999999)) {
			throw new Exception("Number is out of range");
		}
		$Gn = floor($number / 1000000);
		/* Millions (giga) */
		$number -= $Gn * 1000000;
		$kn = floor($number / 1000);
		/* Thousands (kilo) */
		$number -= $kn * 1000;
		$Hn = floor($number / 100);
		/* Hundreds (hecto) */
		$number -= $Hn * 100;
		$Dn = floor($number / 10);
		/* Tens (deca) */
		$n = $number % 10;
		/* Ones */
		$res = "";
		if ($Gn) {
			$res .= ApplicationConst::convert_number($Gn) .  "Million";
		}
		if ($kn) {
			$res .= (empty($res) ? "" : " ") .ApplicationConst::convert_number($kn) . "Thousand";
		}
		if ($Hn) {
			$res .= (empty($res) ? "" : " ") .ApplicationConst::convert_number($Hn) . " Hundred";
		}
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
		if ($Dn || $n) {
			if (!empty($res)) {
				$res .= " and ";
			}
			if ($Dn < 2) {
				$res .= $ones[$Dn * 10 + $n];
			} else {
				$res .= $tens[$Dn];
				if ($n) {
					$res .= "-" . $ones[$n];
				}
			}
		}
		if (empty($res)) {
			$res = "zero";
		}
		return $res;
	}
}