<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "plans_houseinfo.php" ?>
<?php include_once "plansinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$plans_house_edit = NULL; // Initialize page object first

class cplans_house_edit extends cplans_house {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{1B3DE6F9-6AA9-4A0A-BD16-3CDE31B2DBC1}";

	// Table name
	var $TableName = 'plans_house';

	// Page object name
	var $PageObjName = 'plans_house_edit';

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

		// Table object (plans_house)
		if (!isset($GLOBALS["plans_house"]) || get_class($GLOBALS["plans_house"]) == "cplans_house") {
			$GLOBALS["plans_house"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["plans_house"];
		}

		// Table object (plans)
		if (!isset($GLOBALS['plans'])) $GLOBALS['plans'] = new cplans();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'plans_house', TRUE);

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
		global $EW_EXPORT, $plans_house;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($plans_house);
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("plans_houselist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("plans_houselist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->plans_id->FldIsDetailKey) {
			$this->plans_id->setFormValue($objForm->GetValue("x_plans_id"));
		}
		if (!$this->name_th->FldIsDetailKey) {
			$this->name_th->setFormValue($objForm->GetValue("x_name_th"));
		}
		if (!$this->name_en->FldIsDetailKey) {
			$this->name_en->setFormValue($objForm->GetValue("x_name_en"));
		}
		if (!$this->modified_date->FldIsDetailKey) {
			$this->modified_date->setFormValue($objForm->GetValue("x_modified_date"));
			$this->modified_date->CurrentValue = ew_UnFormatDateTime($this->modified_date->CurrentValue, 9);
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->plans_id->CurrentValue = $this->plans_id->FormValue;
		$this->name_th->CurrentValue = $this->name_th->FormValue;
		$this->name_en->CurrentValue = $this->name_en->FormValue;
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
		$this->plans_id->setDbValue($rs->fields('plans_id'));
		$this->name_th->setDbValue($rs->fields('name_th'));
		$this->name_en->setDbValue($rs->fields('name_en'));
		$this->image->Upload->DbValue = $rs->fields('image');
		$this->image->CurrentValue = $this->image->Upload->DbValue;
		$this->enable->setDbValue($rs->fields('enable'));
		$this->created_date->setDbValue($rs->fields('created_date'));
		$this->modified_date->setDbValue($rs->fields('modified_date'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->plans_id->DbValue = $row['plans_id'];
		$this->name_th->DbValue = $row['name_th'];
		$this->name_en->DbValue = $row['name_en'];
		$this->image->Upload->DbValue = $row['image'];
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
		// plans_id
		// name_th
		// name_en
		// image
		// enable
		// created_date
		// modified_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// plans_id
			$this->plans_id->ViewValue = $this->plans_id->CurrentValue;
			if (strval($this->plans_id->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->plans_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `name_th` AS `DispFld`, `name_en` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `plans`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->plans_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->plans_id->ViewValue = $rswrk->fields('DispFld');
					$this->plans_id->ViewValue .= ew_ValueSeparator(1,$this->plans_id) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->plans_id->ViewValue = $this->plans_id->CurrentValue;
				}
			} else {
				$this->plans_id->ViewValue = NULL;
			}
			$this->plans_id->ViewCustomAttributes = "";

			// name_th
			$this->name_th->ViewValue = $this->name_th->CurrentValue;
			$this->name_th->ViewCustomAttributes = "";

			// name_en
			$this->name_en->ViewValue = $this->name_en->CurrentValue;
			$this->name_en->ViewCustomAttributes = "";

			// image
			$this->image->UploadPath = "../images/plans/";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 150;
				$this->image->ImageHeight = 0;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->ViewValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->image->ViewValue = ew_UploadPathEx(TRUE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				}
			} else {
				$this->image->ViewValue = "";
			}
			$this->image->ViewCustomAttributes = "";

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

			// plans_id
			$this->plans_id->LinkCustomAttributes = "";
			$this->plans_id->HrefValue = "";
			$this->plans_id->TooltipValue = "";

			// name_th
			$this->name_th->LinkCustomAttributes = "";
			$this->name_th->HrefValue = "";
			$this->name_th->TooltipValue = "";

			// name_en
			$this->name_en->LinkCustomAttributes = "";
			$this->name_en->HrefValue = "";
			$this->name_en->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../images/plans/";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;
			$this->image->TooltipValue = "";
			if ($this->image->UseColorbox) {
				$this->image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->image->LinkAttrs["data-rel"] = "plans_house_x_image";
				$this->image->LinkAttrs["class"] = "ewLightbox";
			}

			// modified_date
			$this->modified_date->LinkCustomAttributes = "";
			$this->modified_date->HrefValue = "";
			$this->modified_date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// plans_id
			$this->plans_id->EditAttrs["class"] = "form-control";
			$this->plans_id->EditCustomAttributes = "";
			if ($this->plans_id->getSessionValue() <> "") {
				$this->plans_id->CurrentValue = $this->plans_id->getSessionValue();
			$this->plans_id->ViewValue = $this->plans_id->CurrentValue;
			if (strval($this->plans_id->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->plans_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `name_th` AS `DispFld`, `name_en` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `plans`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->plans_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->plans_id->ViewValue = $rswrk->fields('DispFld');
					$this->plans_id->ViewValue .= ew_ValueSeparator(1,$this->plans_id) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->plans_id->ViewValue = $this->plans_id->CurrentValue;
				}
			} else {
				$this->plans_id->ViewValue = NULL;
			}
			$this->plans_id->ViewCustomAttributes = "";
			} else {
			$this->plans_id->EditValue = ew_HtmlEncode($this->plans_id->CurrentValue);
			if (strval($this->plans_id->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->plans_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `name_th` AS `DispFld`, `name_en` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `plans`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->plans_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->plans_id->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->plans_id->EditValue .= ew_ValueSeparator(1,$this->plans_id) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$rswrk->Close();
				} else {
					$this->plans_id->EditValue = ew_HtmlEncode($this->plans_id->CurrentValue);
				}
			} else {
				$this->plans_id->EditValue = NULL;
			}
			$this->plans_id->PlaceHolder = ew_RemoveHtml($this->plans_id->FldCaption());
			}

			// name_th
			$this->name_th->EditAttrs["class"] = "form-control";
			$this->name_th->EditCustomAttributes = "";
			$this->name_th->EditValue = ew_HtmlEncode($this->name_th->CurrentValue);
			$this->name_th->PlaceHolder = ew_RemoveHtml($this->name_th->FldCaption());

			// name_en
			$this->name_en->EditAttrs["class"] = "form-control";
			$this->name_en->EditCustomAttributes = "";
			$this->name_en->EditValue = ew_HtmlEncode($this->name_en->CurrentValue);
			$this->name_en->PlaceHolder = ew_RemoveHtml($this->name_en->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "../images/plans/";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 150;
				$this->image->ImageHeight = 0;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->EditValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->image->EditValue = ew_UploadPathEx(TRUE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				}
			} else {
				$this->image->EditValue = "";
			}
			if (!ew_Empty($this->image->CurrentValue))
				$this->image->Upload->FileName = $this->image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->image);

			// modified_date
			// Edit refer script
			// plans_id

			$this->plans_id->HrefValue = "";

			// name_th
			$this->name_th->HrefValue = "";

			// name_en
			$this->name_en->HrefValue = "";

			// image
			$this->image->UploadPath = "../images/plans/";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

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
		if (!ew_CheckInteger($this->plans_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->plans_id->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->image->OldUploadPath = "../images/plans/";
			$this->image->UploadPath = $this->image->OldUploadPath;
			$rsnew = array();

			// plans_id
			$this->plans_id->SetDbValueDef($rsnew, $this->plans_id->CurrentValue, NULL, $this->plans_id->ReadOnly);

			// name_th
			$this->name_th->SetDbValueDef($rsnew, $this->name_th->CurrentValue, NULL, $this->name_th->ReadOnly);

			// name_en
			$this->name_en->SetDbValueDef($rsnew, $this->name_en->CurrentValue, NULL, $this->name_en->ReadOnly);

			// image
			if (!($this->image->ReadOnly) && !$this->image->Upload->KeepFile) {
				$this->image->Upload->DbValue = $rsold['image']; // Get original value
				if ($this->image->Upload->FileName == "") {
					$rsnew['image'] = NULL;
				} else {
					$rsnew['image'] = $this->image->Upload->FileName;
				}
			}

			// modified_date
			$this->modified_date->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['modified_date'] = &$this->modified_date->DbValue;
			if (!$this->image->Upload->KeepFile) {
				$this->image->UploadPath = "../images/plans/";
				if (!ew_Empty($this->image->Upload->Value)) {
					$rsnew['image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->image->UploadPath), $rsnew['image']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if (!$this->image->Upload->KeepFile) {
						if (!ew_Empty($this->image->Upload->Value)) {
							$this->image->Upload->SaveToFile($this->image->UploadPath, $rsnew['image'], TRUE);
						}
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
		return $EditRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "plans") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id"] <> "") {
					$GLOBALS["plans"]->id->setQueryStringValue($_GET["fk_id"]);
					$this->plans_id->setQueryStringValue($GLOBALS["plans"]->id->QueryStringValue);
					$this->plans_id->setSessionValue($this->plans_id->QueryStringValue);
					if (!is_numeric($GLOBALS["plans"]->id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "plans") {
				if ($this->plans_id->QueryStringValue == "") $this->plans_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "plans_houselist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($plans_house_edit)) $plans_house_edit = new cplans_house_edit();

// Page init
$plans_house_edit->Page_Init();

// Page main
$plans_house_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$plans_house_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var plans_house_edit = new ew_Page("plans_house_edit");
plans_house_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = plans_house_edit.PageID; // For backward compatibility

// Form object
var fplans_houseedit = new ew_Form("fplans_houseedit");

// Validate form
fplans_houseedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_plans_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($plans_house->plans_id->FldErrMsg()) ?>");

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
fplans_houseedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fplans_houseedit.ValidateRequired = true;
<?php } else { ?>
fplans_houseedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fplans_houseedit.Lists["x_plans_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name_th","x_name_en","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $plans_house_edit->ShowPageHeader(); ?>
<?php
$plans_house_edit->ShowMessage();
?>
<form name="fplans_houseedit" id="fplans_houseedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($plans_house_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $plans_house_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="plans_house">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($plans_house->plans_id->Visible) { // plans_id ?>
	<div id="r_plans_id" class="form-group">
		<label id="elh_plans_house_plans_id" class="col-sm-2 control-label ewLabel"><?php echo $plans_house->plans_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $plans_house->plans_id->CellAttributes() ?>>
<?php if ($plans_house->plans_id->getSessionValue() <> "") { ?>
<span id="el_plans_house_plans_id">
<span<?php echo $plans_house->plans_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $plans_house->plans_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_plans_id" name="x_plans_id" value="<?php echo ew_HtmlEncode($plans_house->plans_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_plans_house_plans_id">
<?php
	$wrkonchange = trim(" " . @$plans_house->plans_id->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$plans_house->plans_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_plans_id" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_plans_id" id="sv_x_plans_id" value="<?php echo $plans_house->plans_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($plans_house->plans_id->PlaceHolder) ?>"<?php echo $plans_house->plans_id->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_plans_id" name="x_plans_id" id="x_plans_id" value="<?php echo ew_HtmlEncode($plans_house->plans_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `name_th` AS `DispFld`, `name_en` AS `Disp2Fld` FROM `plans`";
$sWhereWrk = "`name_th` LIKE '{query_value}%' OR CONCAT(`name_th`,'" . ew_ValueSeparator(1, $Page->plans_id) . "',`name_en`) LIKE '{query_value}%'";

// Call Lookup selecting
$plans_house->Lookup_Selecting($plans_house->plans_id, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_plans_id" id="q_x_plans_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fplans_houseedit.CreateAutoSuggest("x_plans_id", false);
</script>
</span>
<?php } ?>
<?php echo $plans_house->plans_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($plans_house->name_th->Visible) { // name_th ?>
	<div id="r_name_th" class="form-group">
		<label id="elh_plans_house_name_th" for="x_name_th" class="col-sm-2 control-label ewLabel"><?php echo $plans_house->name_th->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $plans_house->name_th->CellAttributes() ?>>
<span id="el_plans_house_name_th">
<input type="text" data-field="x_name_th" name="x_name_th" id="x_name_th" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_th->PlaceHolder) ?>" value="<?php echo $plans_house->name_th->EditValue ?>"<?php echo $plans_house->name_th->EditAttributes() ?>>
</span>
<?php echo $plans_house->name_th->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($plans_house->name_en->Visible) { // name_en ?>
	<div id="r_name_en" class="form-group">
		<label id="elh_plans_house_name_en" for="x_name_en" class="col-sm-2 control-label ewLabel"><?php echo $plans_house->name_en->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $plans_house->name_en->CellAttributes() ?>>
<span id="el_plans_house_name_en">
<input type="text" data-field="x_name_en" name="x_name_en" id="x_name_en" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_en->PlaceHolder) ?>" value="<?php echo $plans_house->name_en->EditValue ?>"<?php echo $plans_house->name_en->EditAttributes() ?>>
</span>
<?php echo $plans_house->name_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($plans_house->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_plans_house_image" class="col-sm-2 control-label ewLabel"><?php echo $plans_house->image->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $plans_house->image->CellAttributes() ?>>
<span id="el_plans_house_image">
<div id="fd_x_image">
<span title="<?php echo $plans_house->image->FldTitle() ? $plans_house->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($plans_house->image->ReadOnly || $plans_house->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x_image" id="x_image">
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $plans_house->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_image"] == "0") { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="255">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $plans_house->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $plans_house->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $plans_house->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($plans_house->id->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fplans_houseedit.Init();
</script>
<?php
$plans_house_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$plans_house_edit->Page_Terminate();
?>
