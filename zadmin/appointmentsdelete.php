<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "appointmentsinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$appointments_delete = NULL; // Initialize page object first

class cappointments_delete extends cappointments {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{1B3DE6F9-6AA9-4A0A-BD16-3CDE31B2DBC1}";

	// Table name
	var $TableName = 'appointments';

	// Page object name
	var $PageObjName = 'appointments_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (appointments)
		if (!isset($GLOBALS["appointments"]) || get_class($GLOBALS["appointments"]) == "cappointments") {
			$GLOBALS["appointments"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["appointments"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'appointments', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $appointments;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($appointments);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("appointmentslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in appointments class, appointmentsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->name->setDbValue($rs->fields('name'));
		$this->lastname->setDbValue($rs->fields('lastname'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->date->setDbValue($rs->fields('date'));
		$this->time->setDbValue($rs->fields('time'));
		$this->detail->setDbValue($rs->fields('detail'));
		$this->enable->setDbValue($rs->fields('enable'));
		$this->created_date->setDbValue($rs->fields('created_date'));
		$this->modified_date->setDbValue($rs->fields('modified_date'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->name->DbValue = $row['name'];
		$this->lastname->DbValue = $row['lastname'];
		$this->_email->DbValue = $row['email'];
		$this->phone->DbValue = $row['phone'];
		$this->date->DbValue = $row['date'];
		$this->time->DbValue = $row['time'];
		$this->detail->DbValue = $row['detail'];
		$this->enable->DbValue = $row['enable'];
		$this->created_date->DbValue = $row['created_date'];
		$this->modified_date->DbValue = $row['modified_date'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// name
		// lastname
		// email
		// phone
		// date
		// time
		// detail
		// enable
		// created_date
		// modified_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// lastname
			$this->lastname->ViewValue = $this->lastname->CurrentValue;
			$this->lastname->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// phone
			$this->phone->ViewValue = $this->phone->CurrentValue;
			$this->phone->ViewCustomAttributes = "";

			// date
			$this->date->ViewValue = $this->date->CurrentValue;
			$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 9);
			$this->date->ViewCustomAttributes = "";

			// time
			$this->time->ViewValue = $this->time->CurrentValue;
			$this->time->ViewValue = ew_FormatDateTime($this->time->ViewValue, 9);
			$this->time->ViewCustomAttributes = "";

			// enable
			if (ew_ConvertToBool($this->enable->CurrentValue)) {
				$this->enable->ViewValue = $this->enable->FldTagCaption(1) <> "" ? $this->enable->FldTagCaption(1) : "1";
			} else {
				$this->enable->ViewValue = $this->enable->FldTagCaption(2) <> "" ? $this->enable->FldTagCaption(2) : "0";
			}
			$this->enable->ViewCustomAttributes = "";

			// created_date
			$this->created_date->ViewValue = $this->created_date->CurrentValue;
			$this->created_date->ViewValue = ew_FormatDateTime($this->created_date->ViewValue, 9);
			$this->created_date->ViewCustomAttributes = "";

			// modified_date
			$this->modified_date->ViewValue = $this->modified_date->CurrentValue;
			$this->modified_date->ViewValue = ew_FormatDateTime($this->modified_date->ViewValue, 9);
			$this->modified_date->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// lastname
			$this->lastname->LinkCustomAttributes = "";
			$this->lastname->HrefValue = "";
			$this->lastname->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// time
			$this->time->LinkCustomAttributes = "";
			$this->time->HrefValue = "";
			$this->time->TooltipValue = "";

			// enable
			$this->enable->LinkCustomAttributes = "";
			$this->enable->HrefValue = "";
			$this->enable->TooltipValue = "";

			// created_date
			$this->created_date->LinkCustomAttributes = "";
			$this->created_date->HrefValue = "";
			$this->created_date->TooltipValue = "";

			// modified_date
			$this->modified_date->LinkCustomAttributes = "";
			$this->modified_date->HrefValue = "";
			$this->modified_date->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "appointmentslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($appointments_delete)) $appointments_delete = new cappointments_delete();

// Page init
$appointments_delete->Page_Init();

// Page main
$appointments_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$appointments_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var appointments_delete = new ew_Page("appointments_delete");
appointments_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = appointments_delete.PageID; // For backward compatibility

// Form object
var fappointmentsdelete = new ew_Form("fappointmentsdelete");

// Form_CustomValidate event
fappointmentsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fappointmentsdelete.ValidateRequired = true;
<?php } else { ?>
fappointmentsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($appointments_delete->Recordset = $appointments_delete->LoadRecordset())
	$appointments_deleteTotalRecs = $appointments_delete->Recordset->RecordCount(); // Get record count
if ($appointments_deleteTotalRecs <= 0) { // No record found, exit
	if ($appointments_delete->Recordset)
		$appointments_delete->Recordset->Close();
	$appointments_delete->Page_Terminate("appointmentslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $appointments_delete->ShowPageHeader(); ?>
<?php
$appointments_delete->ShowMessage();
?>
<form name="fappointmentsdelete" id="fappointmentsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($appointments_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $appointments_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="appointments">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($appointments_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $appointments->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($appointments->id->Visible) { // id ?>
		<th><span id="elh_appointments_id" class="appointments_id"><?php echo $appointments->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->name->Visible) { // name ?>
		<th><span id="elh_appointments_name" class="appointments_name"><?php echo $appointments->name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->lastname->Visible) { // lastname ?>
		<th><span id="elh_appointments_lastname" class="appointments_lastname"><?php echo $appointments->lastname->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->_email->Visible) { // email ?>
		<th><span id="elh_appointments__email" class="appointments__email"><?php echo $appointments->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->phone->Visible) { // phone ?>
		<th><span id="elh_appointments_phone" class="appointments_phone"><?php echo $appointments->phone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->date->Visible) { // date ?>
		<th><span id="elh_appointments_date" class="appointments_date"><?php echo $appointments->date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->time->Visible) { // time ?>
		<th><span id="elh_appointments_time" class="appointments_time"><?php echo $appointments->time->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->enable->Visible) { // enable ?>
		<th><span id="elh_appointments_enable" class="appointments_enable"><?php echo $appointments->enable->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->created_date->Visible) { // created_date ?>
		<th><span id="elh_appointments_created_date" class="appointments_created_date"><?php echo $appointments->created_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($appointments->modified_date->Visible) { // modified_date ?>
		<th><span id="elh_appointments_modified_date" class="appointments_modified_date"><?php echo $appointments->modified_date->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$appointments_delete->RecCnt = 0;
$i = 0;
while (!$appointments_delete->Recordset->EOF) {
	$appointments_delete->RecCnt++;
	$appointments_delete->RowCnt++;

	// Set row properties
	$appointments->ResetAttrs();
	$appointments->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$appointments_delete->LoadRowValues($appointments_delete->Recordset);

	// Render row
	$appointments_delete->RenderRow();
?>
	<tr<?php echo $appointments->RowAttributes() ?>>
<?php if ($appointments->id->Visible) { // id ?>
		<td<?php echo $appointments->id->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_id" class="appointments_id">
<span<?php echo $appointments->id->ViewAttributes() ?>>
<?php echo $appointments->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->name->Visible) { // name ?>
		<td<?php echo $appointments->name->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_name" class="appointments_name">
<span<?php echo $appointments->name->ViewAttributes() ?>>
<?php echo $appointments->name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->lastname->Visible) { // lastname ?>
		<td<?php echo $appointments->lastname->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_lastname" class="appointments_lastname">
<span<?php echo $appointments->lastname->ViewAttributes() ?>>
<?php echo $appointments->lastname->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->_email->Visible) { // email ?>
		<td<?php echo $appointments->_email->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments__email" class="appointments__email">
<span<?php echo $appointments->_email->ViewAttributes() ?>>
<?php echo $appointments->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->phone->Visible) { // phone ?>
		<td<?php echo $appointments->phone->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_phone" class="appointments_phone">
<span<?php echo $appointments->phone->ViewAttributes() ?>>
<?php echo $appointments->phone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->date->Visible) { // date ?>
		<td<?php echo $appointments->date->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_date" class="appointments_date">
<span<?php echo $appointments->date->ViewAttributes() ?>>
<?php echo $appointments->date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->time->Visible) { // time ?>
		<td<?php echo $appointments->time->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_time" class="appointments_time">
<span<?php echo $appointments->time->ViewAttributes() ?>>
<?php echo $appointments->time->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->enable->Visible) { // enable ?>
		<td<?php echo $appointments->enable->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_enable" class="appointments_enable">
<span<?php echo $appointments->enable->ViewAttributes() ?>>
<?php echo $appointments->enable->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->created_date->Visible) { // created_date ?>
		<td<?php echo $appointments->created_date->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_created_date" class="appointments_created_date">
<span<?php echo $appointments->created_date->ViewAttributes() ?>>
<?php echo $appointments->created_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($appointments->modified_date->Visible) { // modified_date ?>
		<td<?php echo $appointments->modified_date->CellAttributes() ?>>
<span id="el<?php echo $appointments_delete->RowCnt ?>_appointments_modified_date" class="appointments_modified_date">
<span<?php echo $appointments->modified_date->ViewAttributes() ?>>
<?php echo $appointments->modified_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$appointments_delete->Recordset->MoveNext();
}
$appointments_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fappointmentsdelete.Init();
</script>
<?php
$appointments_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$appointments_delete->Page_Terminate();
?>
