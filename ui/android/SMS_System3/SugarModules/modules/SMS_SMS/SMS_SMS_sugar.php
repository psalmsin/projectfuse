<?PHP
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004 - 2009 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/
/**
 * THIS CLASS IS GENERATED BY MODULE BUILDER
 * PLEASE DO NOT CHANGE THIS CLASS
 * PLACE ANY CUSTOMIZATIONS IN SMS_SMS
 */
//require_once('include/SugarObjects/templates/person/Person.php');
require_once('data/SugarBean.php');
require_once('modules/SMS_SMS/SMSFunctions.php');
class SMS_SMS_sugar extends SugarBean {
	var $new_schema = true;
	var $module_dir = 'SMS_SMS';
	var $object_name = 'SMS_SMS';
	var $table_name = 'sms_sms';
	var $importable = false;

	var $disable_row_level_security = true ; // to ensure that modules created and deployed under CE will continue to function under team security if the instance is upgraded to PRO

		var $id;
		var $name;
		var $date_entered;
		var $date_modified;
		var $modified_user_id;
		var $modified_by_name;
		var $created_by;
		var $created_by_name;
		var $description;
		var $deleted;
		var $created_by_link;
		var $modified_user_link;
		
		var $assigned_user_name;
		var $assigned_user_link;
		var $assigned_user_id;
		//var $date_start;
		var $salutation;
		var $first_name;
		var $last_name;
		var $full_name;
		var $title;
		var $department;
		var $do_not_call;
		var $phone_home;
		var $phone_mobile;
		var $phone_work;
		var $phone_other;
		var $phone_fax;
		var $email1;
		var $email2;
		var $invalid_email;
		var $email_opt_out;
		var $primary_address_street;
		var $primary_address_street_2;
		var $primary_address_street_3;
		var $primary_address_city;
		var $primary_address_state;
		var $primary_address_postalcode;
		var $primary_address_country;
		var $alt_address_street;
		var $alt_address_street_2;
		var $alt_address_street_3;
		var $alt_address_city;
		var $alt_address_state;
		var $alt_address_postalcode;
		var $alt_address_country;
		var $assistant;
		var $assistant_phone;
		var $email_addresses_primary;
		var $sender_user_id;
		var $account_id;
		var $contact_id;
		var $contact_name;
		var $parent_id;
		var $parent_type;
		var $status;
		var $to_user_id;		
		var $to_user_name;
		var $msgCnt;
		var $other_info;
		var $detailCampaignView ;
	
var $additional_column_fields = array('assigned_user_name1', 'assigned_user_id1', 'contact_id', 'user_id', 'contact_name', 'accept_status');
var $relationship_fields = array('account_id'=>'accounts','contact_id'=>'contacts');



	function SMS_SMS_sugar(){
		$this->msgCnt = "160";
		$this->detailCampaignView = "";		
		parent::SugarBean();
		$this->setupCustomFields('SMS_SMS');
		//echo "<pre>";print_r($this->field_defs);
		foreach($this->field_defs as $field) {			
			$this->field_name_map[$field['name']] = $field;
		}		
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL': return true;
		}
		return false;
	}
	
	function getToAddressInfo() {
		$paramData = array();
		//echo "<pre>";print_r($_REQUEST["addlParams"]);
		if(isset($_REQUEST["addlParams"])) {
			$addlParamData = $_REQUEST["addlParams"];
			$addlDArray = explode("~~~", $addlParamData);
			$addlParamInfo = "";
			$this->paramDataName = $addlParamData;			
			foreach ($addlDArray as $aK => $aV) {
				$addlDataArray = explode(":::", $aV);
				$paramData[] = $addlDataArray[2]; // Send To number 
				//$this->paramDataName[] = $addlDataArray[0].": :".$addlDataArray[3].": :".$addlDataArray[2]; // Send To id , the person type(contact/lead/users/target) and the 
			}
		}
		return $paramData;
	}
	
	function save($check_notify = FALSE) {
		global $current_user;		
		$this->status = 'Not Sent';
		$this->description = "";
//$sending = http_post(array("APIKey" => "richard", "APIPassword" => "ric123", "SenderID" => "27833777772", "MSISDN" => "919840893862", "Message" => "Test from InetDev"));
		$apiKey = $current_user->getPreference("SMSAPIKEY");
		$apiPassword = $current_user->getPreference("SMSAPIPASSWORD");			
		//$this->to_user_id = "919840893862";
		$composeFrom = (isset($_REQUEST["composeFrom"]) ? $_REQUEST["composeFrom"] : "");
		
		if($composeFrom == "campaigns") {
			$toAddressInfo = $this->getToAddressInfo();
			
			foreach($toAddressInfo as $tK => $tV) {
				$this->to_user_id = $tV;
				$this->status = 'Not Sent';
				$smsInfo[] = array (
					"APIKey" => $apiKey,
					"APIPassword" => $apiPassword,
					"SenderID" => $this->sender_user_id,
					"MSISDN" => $this->to_user_id, //"17328296272",//
					"Message" => $this->name,
				);
				//if($this->other_info != "") $this->other_info .= "~~~";
				//$this->other_info .= $this->paramDataName[$tK];				
			}
			$this->other_info = $this->paramDataName;
			$smsInfo["SenderID"] = $this->sender_user_id;
			$this->saveNsendCampaignsSMS($smsInfo);
		} else  {
			$this->other_info = '';
			$smsInfo = array (
				"APIKey" => $apiKey,
				"APIPassword" => $apiPassword,
				"SenderID" => $this->sender_user_id,
				"MSISDN" => $this->to_user_id,
				"Message" => $this->name,
			);
			$this->saveNsendSMS($smsInfo);
		}
		//exit;
		return $this->status;	

	}

	function saveNsendCampaignsSMS($smsInfo) {
		$returnData = SendBatchMessage($smsInfo);
		//echo "HHH".$returnData;
		$this->status = 'Not Sent';
		if($returnData == "0") {
			$this->status = 'Sent';	
		} else {
			$this->description = $returnData;
		}
		//echo "<BR>".$this->status."<BR>";
		$this->saveCampaignSMSInfo();

	}
	
	function saveNsendSMS($smsInfo) {
		
		$sentResultArray = sendSMS($smsInfo);

		list($sentResult, $description) = $sentResultArray;
		if($sentResult == 0 || $sentResult == "") $this->status = 'Sent';
		$this->description = $description;
		$this->saveSMSInfo();
	}
	
	function saveCampaignSMSInfo() {
		global $current_user;
		if($_REQUEST["record"] != "") {
			 $sqlQuery = "UPDATE sms_sms set 				
				name='".$this->name."', 
				date_entered='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 
				date_modified='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 
				modified_user_id='".$current_user->id."', 
				created_by='".$current_user->id."', 
				deleted='0', 
				status='".$this->status."', 
				description='".$this->description."'  WHERE id='".$_REQUEST["record"]."' ";
			$this->db->query($sqlQuery, true);			
		} else {	
			$this->id = create_guid();
			$this->to_user_id = '';
			 $sqlQuery = "INSERT into sms_sms set 
				id='".$this->id."', 
				name='".$this->name."', 
				date_entered='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 
				date_modified='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 
				modified_user_id='".$current_user->id."', 
				created_by='".$current_user->id."', 
				deleted='0', 
				status='".$this->status."', 
				description='".$this->description."', 
				sender_user_id='".$this->sender_user_id."', 
				other_info ='".$this->other_info."', 
				to_user_id='".$this->to_user_id."'";
			$this->db->query($sqlQuery, true);		
//			echo "<BR>".$sqlQuery;

			//Insert into relations table
			
			$subQry = "INSERT INTO sms_relations SET 
				id='".create_guid()."', 
				sms_id='".$this->id."', 
				relation_id='".$_REQUEST["parent_id"]."', 
				relation_type='".$_REQUEST["parent_type"]."', 			
				date_modified='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 			
				deleted='0'";
			$this->db->query($subQry, true);
		}
//			echo "<BR>".$subQry;					
//			
//			echo mysql_error();
		
	}
	
	function saveSMSInfo() {
		global $current_user;
		if($_REQUEST["record"] != "") {
			 $sqlQuery = "UPDATE sms_sms set 				
				name='".$this->name."', 
				date_entered='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 
				date_modified='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 
				modified_user_id='".$current_user->id."', 
				created_by='".$current_user->id."', 
				deleted='0', 
				status='".$this->status."', 
				description='".$this->description."', 
				sender_user_id='".$this->sender_user_id."', 
				other_info ='".$this->other_info."', 
				to_user_id='".$this->to_user_id."' WHERE id='".$_REQUEST["record"]."' ";
			$this->db->query($sqlQuery, true);			
		} else {
			$this->id = create_guid();
			 $sqlQuery = "INSERT into sms_sms set 
				id='".$this->id."', 
				name='".$this->name."', 
				date_entered='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 
				date_modified='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 
				modified_user_id='".$current_user->id."', 
				created_by='".$current_user->id."', 
				deleted='0', 
				status='".$this->status."', 
				description='".$this->description."', 
				sender_user_id='".$this->sender_user_id."', 
				other_info ='".$this->other_info."', 
				to_user_id='".$this->to_user_id."'";
			$this->db->query($sqlQuery, true);		
			//echo "<BR>".$sqlQuery;

			//Insert into relations table
			
			$subQry = "INSERT INTO sms_relations SET 
				id='".create_guid()."', 
				sms_id='".$this->id."', 
				relation_id='".$_REQUEST["parent_id"]."', 
				relation_type='".$_REQUEST["parent_type"]."', 			
				date_modified='".gmdate($GLOBALS['timedate']->get_db_date_time_format())."', 			
				deleted='0'";
			$this->db->query($subQry, true);
			//echo "<BR>".$subQry;					
		}
	}
	
	function fill_in_additional_detail_fields() {		
		parent::fill_in_additional_detail_fields();		
		$this->get_to_user_data($this->id);
		if($_REQUEST["module"] == "Contacts")
			$this->getContactName($_REQUEST["record"]);		
		
	}
		
	function get_summary_text() {		
		return "$this->name";
	}
	function create_new_list_query($order_by, $where,$filter=array(),$params=array(), $show_deleted = 0,$join_type='', $return_array = false,$parentbean, $singleSelect = false) {
//		echo $order_by."<BR>";
//		$order_by = " ORDER BY sms_sms.date_entered desc ";
//		echo $order_by."<BR><pre>";
//print_r($parentbean);
//		exit;
		return parent::create_new_list_query($order_by, $where,$filter,$params, $show_deleted,$join_type, $return_array,$parentbean, $singleSelect);
		
		$ret_array["select"] =  "SELECT  sms_sms.id , sms_sms.name , sms_sms.status  , '' contact_name , '' contact_id  , '' contact_name_owner  , '' contact_name_mod, sms_sms.date_entered  as date_start  , sms_sms.to_user_id as assigned_user_name , '' assigned_user_name_owner  , '' assigned_user_name_mod, '' assigned_user_id, '' aa  "; 
    $ret_array["from"] =  "FROM sms_sms 	 ";
    $ret_array["from_min"] =  "FROM sms_sms ";
    $ret_array["secondary_from"] =  "FROM sms_sms "; 
    //$ret_array["where"] =  "where ( sms_relations.deleted=0 AND sms_sms.deleted=0)";
    $ret_array["where"] =  " where  $where ";
    $ret_array["order_by"] =  " ORDER BY sms_sms.date_entered desc "; 
    $ret_array["secondary_select"] =  "";
    return $ret_array;
	}
	
	function getRelateInfo($expData) {
		$recordID = $expData[0];
		$relateType = $expData[3];
		$sqlQry = "";
		switch(strtolower($relateType)) {
			case "contacts":
				$sqlQry = "SELECT  concat(COALESCE(first_name,''), ' ', last_name) as relateName from contacts WHERE id ='".$recordID."'";
				break;
			case "leads":
				$sqlQry = "SELECT  concat(COALESCE(first_name,''), ' ', last_name) as relateName from leads WHERE id ='".$recordID."'";
				break;			
			case "prospects":
				$sqlQry = "SELECT  concat(COALESCE(first_name,''), ' ', last_name)  as relateName from prospects WHERE id ='".$recordID."'";
				break;
			case "users":
				$sqlQry = "SELECT  concat(COALESCE(first_name,''), ' ', last_name)  as relateName from users WHERE id ='".$recordID."'";
				break;
			case "accounts":
				$sqlQry = "SELECT  concat(COALESCE(name,''), '')  as relateName from accounts WHERE id ='".$recordID."'";
				break;
			
		}
		if($sqlQry != "" ) {		
			$res = $this->db->query($sqlQry);	
			if($res) {
				$row = $this->db->fetchByAssoc($res);
				if($row) return $row["relateName"] ."<BR> [".$expData[2]."]";
			}
		}			
		return '';

	}
	function get_to_user_data($smsID) {
		global $current_user, $timedate;			
		 $sqlQuery = "SELECT to_user_id, date_entered, sender_user_id, created_by,other_info from sms_sms WHERE id='$smsID'";
		$res = $this->db->query($sqlQuery);	
		if($res) {
			$row = $this->db->fetchByAssoc($res);
			$this->to_user_id = $row["to_user_id"];
			$this->sender_user_id = $row["sender_user_id"];//
			$this->created_by_name = get_assigned_user_name($row["created_by"]);
			$this->date_entered = $timedate->to_display_date_time($row["date_entered"],true,true,$current_user);
			$this->msgCnt = 160 - strlen($this->name);
			
			//displaying other info for campaigns
			if($row["other_info"] != "") {
				$this->other_infoData = $row["other_info"];
				$otherInfo = explode("~~~",$row["other_info"]);
				$displayRelateInfo = array();
				
				$addlParamInfo = "";
				if(is_array($otherInfo)) {
					$rCnting = 0;
					
					$addlParamInfo = "<table border=0 width=100% style='background-color:inherit;' cellpadding=2 ><tr>";
					foreach($otherInfo as $oK => $oV) {						
						$expData = explode(":::", $oV);						
						if(isset($expData[0]) && isset($expData[1])) {
							if($rCnting % 6 == 0 && $rCnting != 0) {
								$addlParamInfo .= "</tr><tr>"; $rCnting = 0;
							}
							$addlParamInfo .= "<td nowrap>".$this->getRelateInfo($expData)." </td>";
							$rCnting ++;

							//$displayRelateInfo[] = $this->getRelateInfo($expData);							
						}
					}
					$rCnting = 6 - $rCnting ;
					if($rCnting > 0)  {
						$addlParamInfo .= "<td colspan=$rCnting width=100%>&nbsp;</td>";
					}
					
				}
				$this->detailCampaignView = "campaigns";
				$this->to_user_id = $addlParamInfo."</tr></table>";//implode("<BR>", $displayRelateInfo);				
			}
			
		}
		return "";		
	}
	
	function getContactName($contactID) {
		global $current_user, $timedate;			
		$sqlQuery = "SELECT CONCAT(IFNULL(contacts.first_name,''),' ',IFNULL(contacts.last_name,'')) contact_name from contacts WHERE id='$contactID'";
		$res = $this->db->query($sqlQuery);	
		if($res) {
			$row = $this->db->fetchByAssoc($res);
			$this->contact_name = $row["contact_name"];
		}
		return "";		
	}	
	function get_list_view_data() {
		$displayFields = $this->get_list_view_array();	
		//echo "<pre>";print_r($this);	
		$this->fill_in_additional_detail_fields();
		$displayFields["TO_USER_ID"] = $this->to_user_id;
		$displayFields["DETAILCAMPAIGNVIEW"] = $this->detailCampaignView;		
		$displayFields["DATE_ENTERED"] = $this->date_entered;		
		$displayFields["CONTACT_NAME"] = $this->contact_name;		
		return $displayFields;
	}
}
?>