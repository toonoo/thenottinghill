<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "progressinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$progress_add = NULL; // Initialize page object first

class cprogress_add extends cprogress {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1B3DE6F9-6AA9-4A0A-BD16-3CDE31B2DBC1}";

	// Table name
	var $TableName = 'progress';

	// Page object name
	var $PageObjName = 'progress_add';

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

		// Table object (progress)
		if (!isset($GLOBALS["progress"]) || get_class($GLOBALS["progress"]) == "cprogress") {
			$GLOBALS["progress"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["progress"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'progress', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
		global $EW_EXPORT, $progress;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($progress);
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
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("progresslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "progressview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->image->CurrentValue = NULL;
		$this->image->OldValue = $this->image->CurrentValue;
		$this->project_name->CurrentValue = NULL;
		$this->project_name->OldValue = $this->project_name->CurrentValue;
		$this->project_date->CurrentValue = NULL;
		$this->project_date->OldValue = $this->project_date->CurrentValue;
		$this->enable->CurrentValue = "1";
		$this->created_date->CurrentValue = NULL;
		$this->created_date->OldValue = $this->created_date->CurrentValue;
		$this->modified_date->CurrentValue = NULL;
		$this->modified_date->OldValue = $this->modified_date->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->image->FldIsDetailKey) {
			$this->image->setFormValue($objForm->GetValue("x_image"));
		}
		if (!$this->project_name->FldIsDetailKey) {
			$this->project_name->setFormValue($objForm->GetValue("x_project_name"));
		}
		if (!$this->project_date->FldIsDetailKey) {
			$this->project_date->setFormValue($objForm->GetValue("x_project_date"));
			$this->project_date->CurrentValue = ew_UnFormatDateTime($this->project_date->CurrentValue, 9);
		}
		if (!$this->enable->FldIsDetailKey) {
			$this->enable->setFormValue($objForm->GetValue("x_enable"));
		}
		if (!$this->created_date->FldIsDetailKey) {
			$this->created_date->setFormValue($objForm->GetValue("x_created_date"));
			$this->created_date->CurrentValue = ew_UnFormatDateTime($this->created_date->CurrentValue, 9);
		}
		if (!$this->modified_date->FldIsDetailKey) {
			$this->modified_date->setFormValue($objForm->GetValue("x_modified_date"));
			$this->modified_date->CurrentValue = ew_UnFormatDateTime($this->modified_date->CurrentValue, 9);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->image->CurrentValue = $this->image->FormValue;
		$this->project_name->CurrentValue = $this->project_name->FormValue;
		$this->project_date->CurrentValue = $this->project_date->FormValue;
		$this->project_date->CurrentValue = ew_UnFormatDateTime($this->project_date->CurrentValue, 9);
		$this->enable->CurrentValue = $this->enable->FormValue;
		$this->created_date->CurrentValue = $this->created_date->FormValue;
		$this->created_date->CurrentValue = ew_UnFormatDateTime($this->created_date->CurrentValue, 9);
		$this->modified_date->CurrentValue = $this->modified_date->FormValue;
		$this->modified_date->CurrentValue = ew_UnFormatDateTime($this->modified_date->CurrentValue, 9);
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
		$this->image->setDbValue($rs->fields('image'));
		$this->project_name->setDbValue($rs->fields('project_name'));
		$this->project_date->setDbValue($rs->fields('project_date'));
		$this->enable->setDbValue($rs->fields('enable'));
		$this->created_date->setDbValue($rs->fields('created_date'));
		$this->modified_date->setDbValue($rs->fields('modified_date'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->image->DbValue = $row['image'];
		$this->project_name->DbValue = $row['project_name'];
		$this->project_date->DbValue = $row['project_date'];
		$this->enable->DbValue = $row['enable'];
		$this->created_date->DbValue = $row['created_date'];
		$this->modified_date->DbValue = $row['modified_date'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		// image
		// project_name
		// project_date
		// enable
		// created_date
		// modified_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// image
			$this->image->ViewValue = $this->image->CurrentValue;
			$this->image->ViewCustomAttributes = "";

			// project_name
			$this->project_name->ViewValue = $this->project_name->CurrentValue;
			$this->project_name->ViewCustomAttributes = "";

			// project_date
			$this->project_date->ViewValue = $this->project_date->CurrentValue;
			$this->project_date->ViewValue = ew_FormatDateTime($this->project_date->ViewValue, 9);
			$this->project_date->ViewCustomAttributes = "";

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

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->HrefValue = "";
			$this->image->TooltipValue = "";

			// project_name
			$this->project_name->LinkCustomAttributes = "";
			$this->project_name->HrefValue = "";
			$this->project_name->TooltipValue = "";

			// project_date
			$this->project_date->LinkCustomAttributes = "";
			$this->project_date->HrefValue = "";
			$this->project_date->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->EditValue = ew_HtmlEncode($this->image->CurrentValue);
			$this->image->PlaceHolder = ew_RemoveHtml($this->image->FldCaption());

			// project_name
			$this->project_name->EditAttrs["class"] = "form-control";
			$this->project_name->EditCustomAttributes = "";
			$this->project_name->EditValue = ew_HtmlEncode($this->project_name->CurrentValue);
			$this->project_name->PlaceHolder = ew_RemoveHtml($this->project_name->FldCaption());

			// project_date
			$this->project_date->EditAttrs["class"] = "form-control";
			$this->project_date->EditCustomAttributes = "";
			$this->project_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->project_date->CurrentValue, 9));
			$this->project_date->PlaceHolder = ew_RemoveHtml($this->project_date->FldCaption());

			// enable
			$this->enable->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->enable->FldTagValue(1), $this->enable->FldTagCaption(1) <> "" ? $this->enable->FldTagCaption(1) : $this->enable->FldTagValue(1));
			$arwrk[] = array($this->enable->FldTagValue(2), $this->enable->FldTagCaption(2) <> "" ? $this->enable->FldTagCaption(2) : $this->enable->FldTagValue(2));
			$this->enable->EditValue = $arwrk;

			// created_date
			$this->created_date->EditAttrs["class"] = "form-control";
			$this->created_date->EditCustomAttributes = "";
			$this->created_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_date->CurrentValue, 9));
			$this->created_date->PlaceHolder = ew_RemoveHtml($this->created_date->FldCaption());

			// modified_date
			$this->modified_date->EditAttrs["class"] = "form-control";
			$this->modified_date->EditCustomAttributes = "";
			$this->modified_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->modified_date->CurrentValue, 9));
			$this->modified_date->PlaceHolder = ew_RemoveHtml($this->modified_date->FldCaption());

			// Edit refer script
			// image

			$this->image->HrefValue = "";

			// project_name
			$this->project_name->HrefValue = "";

			// project_date
			$this->project_date->HrefValue = "";

			// enable
			$this->enable->HrefValue = "";

			// created_date
			$this->created_date->HrefValue = "";

			// modified_date
			$this->modified_date->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckDate($this->project_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->project_date->FldErrMsg());
		}
		if (!ew_CheckDate($this->created_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_date->FldErrMsg());
		}
		if (!ew_CheckDate($this->modified_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->modified_date->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// image
		$this->image->SetDbValueDef($rsnew, $this->image->CurrentValue, NULL, FALSE);

		// project_name
		$this->project_name->SetDbValueDef($rsnew, $this->project_name->CurrentValue, NULL, FALSE);

		// project_date
		$this->project_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->project_date->CurrentValue, 9), NULL, FALSE);

		// enable
		$this->enable->SetDbValueDef($rsnew, ((strval($this->enable->CurrentValue) == "1") ? "1" : "0"), NULL, strval($this->enable->CurrentValue) == "");

		// created_date
		$this->created_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_date->CurrentValue, 9), NULL, FALSE);

		// modified_date
		$this->modified_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->modified_date->CurrentValue, 9), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "progresslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($progress_add)) $progress_add = new cprogress_add();

// Page init
$progress_add->Page_Init();

// Page main
$progress_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$progress_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var progress_add = new ew_Page("progress_add");
progress_add.PageID = "add"; // Page ID
var EW_PAGE_ID = progress_add.PageID; // For backward compatibility

// Form object
var fprogressadd = new ew_Form("fprogressadd");

// Validate form
fprogressadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_project_date");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($progress->project_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_created_date");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($progress->created_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modified_date");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($progress->modified_date->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fprogressadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprogressadd.ValidateRequired = true;
<?php } else { ?>
fprogressadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $progress_add->ShowPageHeader(); ?>
<?php
$progress_add->ShowMessage();
?>
<form name="fprogressadd" id="fprogressadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($progress_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $progress_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="progress">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($progress->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_progress_image" for="x_image" class="col-sm-2 control-label ewLabel"><?php echo $progress->image->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $progress->image->CellAttributes() ?>>
<span id="el_progress_image">
<input type="text" data-field="x_image" name="x_image" id="x_image" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($progress->image->PlaceHolder) ?>" value="<?php echo $progress->image->EditValue ?>"<?php echo $progress->image->EditAttributes() ?>>
</span>
<?php echo $progress->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($progress->project_name->Visible) { // project_name ?>
	<div id="r_project_name" class="form-group">
		<label id="elh_progress_project_name" for="x_project_name" class="col-sm-2 control-label ewLabel"><?php echo $progress->project_name->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $progress->project_name->CellAttributes() ?>>
<span id="el_progress_project_name">
<input type="text" data-field="x_project_name" name="x_project_name" id="x_project_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($progress->project_name->PlaceHolder) ?>" value="<?php echo $progress->project_name->EditValue ?>"<?php echo $progress->project_name->EditAttributes() ?>>
</span>
<?php echo $progress->project_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($progress->project_date->Visible) { // project_date ?>
	<div id="r_project_date" class="form-group">
		<label id="elh_progress_project_date" for="x_project_date" class="col-sm-2 control-label ewLabel"><?php echo $progress->project_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $progress->project_date->CellAttributes() ?>>
<span id="el_progress_project_date">
<input type="text" data-field="x_project_date" name="x_project_date" id="x_project_date" placeholder="<?php echo ew_HtmlEncode($progress->project_date->PlaceHolder) ?>" value="<?php echo $progress->project_date->EditValue ?>"<?php echo $progress->project_date->EditAttributes() ?>>
</span>
<?php echo $progress->project_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($progress->enable->Visible) { // enable ?>
	<div id="r_enable" class="form-group">
		<label id="elh_progress_enable" class="col-sm-2 control-label ewLabel"><?php echo $progress->enable->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $progress->enable->CellAttributes() ?>>
<span id="el_progress_enable">
<div id="tp_x_enable" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_enable" id="x_enable" value="{value}"<?php echo $progress->enable->EditAttributes() ?>></div>
<div id="dsl_x_enable" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $progress->enable->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($progress->enable->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_enable" name="x_enable" id="x_enable_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $progress->enable->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $progress->enable->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($progress->created_date->Visible) { // created_date ?>
	<div id="r_created_date" class="form-group">
		<label id="elh_progress_created_date" for="x_created_date" class="col-sm-2 control-label ewLabel"><?php echo $progress->created_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $progress->created_date->CellAttributes() ?>>
<span id="el_progress_created_date">
<input type="text" data-field="x_created_date" name="x_created_date" id="x_created_date" placeholder="<?php echo ew_HtmlEncode($progress->created_date->PlaceHolder) ?>" value="<?php echo $progress->created_date->EditValue ?>"<?php echo $progress->created_date->EditAttributes() ?>>
</span>
<?php echo $progress->created_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($progress->modified_date->Visible) { // modified_date ?>
	<div id="r_modified_date" class="form-group">
		<label id="elh_progress_modified_date" for="x_modified_date" class="col-sm-2 control-label ewLabel"><?php echo $progress->modified_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $progress->modified_date->CellAttributes() ?>>
<span id="el_progress_modified_date">
<input type="text" data-field="x_modified_date" name="x_modified_date" id="x_modified_date" placeholder="<?php echo ew_HtmlEncode($progress->modified_date->PlaceHolder) ?>" value="<?php echo $progress->modified_date->EditValue ?>"<?php echo $progress->modified_date->EditAttributes() ?>>
</span>
<?php echo $progress->modified_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fprogressadd.Init();
</script>
<?php
$progress_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$progress_add->Page_Terminate();
?>
