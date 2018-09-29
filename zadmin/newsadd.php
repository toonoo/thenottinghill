<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "newsinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$news_add = NULL; // Initialize page object first

class cnews_add extends cnews {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1B3DE6F9-6AA9-4A0A-BD16-3CDE31B2DBC1}";

	// Table name
	var $TableName = 'news';

	// Page object name
	var $PageObjName = 'news_add';

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

		// Table object (news)
		if (!isset($GLOBALS["news"]) || get_class($GLOBALS["news"]) == "cnews") {
			$GLOBALS["news"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["news"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'news', TRUE);

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
		global $EW_EXPORT, $news;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($news);
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
					$this->Page_Terminate("newslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "newsview.php")
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
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->name_th->CurrentValue = NULL;
		$this->name_th->OldValue = $this->name_th->CurrentValue;
		$this->name_en->CurrentValue = NULL;
		$this->name_en->OldValue = $this->name_en->CurrentValue;
		$this->detail_th->CurrentValue = NULL;
		$this->detail_th->OldValue = $this->detail_th->CurrentValue;
		$this->detail_en->CurrentValue = NULL;
		$this->detail_en->OldValue = $this->detail_en->CurrentValue;
		$this->image->Upload->DbValue = NULL;
		$this->image->OldValue = $this->image->Upload->DbValue;
		$this->image->CurrentValue = NULL; // Clear file related field
		$this->created_date->CurrentValue = NULL;
		$this->created_date->OldValue = $this->created_date->CurrentValue;
		$this->modified_date->CurrentValue = NULL;
		$this->modified_date->OldValue = $this->modified_date->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->name_th->FldIsDetailKey) {
			$this->name_th->setFormValue($objForm->GetValue("x_name_th"));
		}
		if (!$this->name_en->FldIsDetailKey) {
			$this->name_en->setFormValue($objForm->GetValue("x_name_en"));
		}
		if (!$this->detail_th->FldIsDetailKey) {
			$this->detail_th->setFormValue($objForm->GetValue("x_detail_th"));
		}
		if (!$this->detail_en->FldIsDetailKey) {
			$this->detail_en->setFormValue($objForm->GetValue("x_detail_en"));
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
		$this->name_th->CurrentValue = $this->name_th->FormValue;
		$this->name_en->CurrentValue = $this->name_en->FormValue;
		$this->detail_th->CurrentValue = $this->detail_th->FormValue;
		$this->detail_en->CurrentValue = $this->detail_en->FormValue;
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
		$this->name_th->setDbValue($rs->fields('name_th'));
		$this->name_en->setDbValue($rs->fields('name_en'));
		$this->detail_th->setDbValue($rs->fields('detail_th'));
		$this->detail_en->setDbValue($rs->fields('detail_en'));
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
		$this->name_th->DbValue = $row['name_th'];
		$this->name_en->DbValue = $row['name_en'];
		$this->detail_th->DbValue = $row['detail_th'];
		$this->detail_en->DbValue = $row['detail_en'];
		$this->image->Upload->DbValue = $row['image'];
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
		// name_th
		// name_en
		// detail_th
		// detail_en
		// image
		// enable
		// created_date
		// modified_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// name_th
			$this->name_th->ViewValue = $this->name_th->CurrentValue;
			$this->name_th->ViewCustomAttributes = "";

			// name_en
			$this->name_en->ViewValue = $this->name_en->CurrentValue;
			$this->name_en->ViewCustomAttributes = "";

			// detail_th
			$this->detail_th->ViewValue = $this->detail_th->CurrentValue;
			$this->detail_th->ViewCustomAttributes = "";

			// detail_en
			$this->detail_en->ViewValue = $this->detail_en->CurrentValue;
			$this->detail_en->ViewCustomAttributes = "";

			// image
			$this->image->UploadPath = "../images/uploads/";
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

			// name_th
			$this->name_th->LinkCustomAttributes = "";
			$this->name_th->HrefValue = "";
			$this->name_th->TooltipValue = "";

			// name_en
			$this->name_en->LinkCustomAttributes = "";
			$this->name_en->HrefValue = "";
			$this->name_en->TooltipValue = "";

			// detail_th
			$this->detail_th->LinkCustomAttributes = "";
			$this->detail_th->HrefValue = "";
			$this->detail_th->TooltipValue = "";

			// detail_en
			$this->detail_en->LinkCustomAttributes = "";
			$this->detail_en->HrefValue = "";
			$this->detail_en->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../images/uploads/";
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
				$this->image->LinkAttrs["data-rel"] = "news_x_image";
				$this->image->LinkAttrs["class"] = "ewLightbox";
			}

			// created_date
			$this->created_date->LinkCustomAttributes = "";
			$this->created_date->HrefValue = "";
			$this->created_date->TooltipValue = "";

			// modified_date
			$this->modified_date->LinkCustomAttributes = "";
			$this->modified_date->HrefValue = "";
			$this->modified_date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// detail_th
			$this->detail_th->EditAttrs["class"] = "form-control";
			$this->detail_th->EditCustomAttributes = "";
			$this->detail_th->EditValue = ew_HtmlEncode($this->detail_th->CurrentValue);
			$this->detail_th->PlaceHolder = ew_RemoveHtml($this->detail_th->FldCaption());

			// detail_en
			$this->detail_en->EditAttrs["class"] = "form-control";
			$this->detail_en->EditCustomAttributes = "";
			$this->detail_en->EditValue = ew_HtmlEncode($this->detail_en->CurrentValue);
			$this->detail_en->PlaceHolder = ew_RemoveHtml($this->detail_en->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "../images/uploads/";
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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->image);

			// created_date
			// modified_date
			// Edit refer script
			// name_th

			$this->name_th->HrefValue = "";

			// name_en
			$this->name_en->HrefValue = "";

			// detail_th
			$this->detail_th->HrefValue = "";

			// detail_en
			$this->detail_en->HrefValue = "";

			// image
			$this->image->UploadPath = "../images/uploads/";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

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
			$this->image->OldUploadPath = "../images/uploads/";
			$this->image->UploadPath = $this->image->OldUploadPath;
		}
		$rsnew = array();

		// name_th
		$this->name_th->SetDbValueDef($rsnew, $this->name_th->CurrentValue, NULL, FALSE);

		// name_en
		$this->name_en->SetDbValueDef($rsnew, $this->name_en->CurrentValue, NULL, FALSE);

		// detail_th
		$this->detail_th->SetDbValueDef($rsnew, $this->detail_th->CurrentValue, NULL, FALSE);

		// detail_en
		$this->detail_en->SetDbValueDef($rsnew, $this->detail_en->CurrentValue, NULL, FALSE);

		// image
		if (!$this->image->Upload->KeepFile) {
			$this->image->Upload->DbValue = ""; // No need to delete old file
			if ($this->image->Upload->FileName == "") {
				$rsnew['image'] = NULL;
			} else {
				$rsnew['image'] = $this->image->Upload->FileName;
			}
		}

		// created_date
		$this->created_date->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['created_date'] = &$this->created_date->DbValue;

		// modified_date
		$this->modified_date->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['modified_date'] = &$this->modified_date->DbValue;
		if (!$this->image->Upload->KeepFile) {
			$this->image->UploadPath = "../images/uploads/";
			if (!ew_Empty($this->image->Upload->Value)) {
				$rsnew['image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->image->UploadPath), $rsnew['image']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "newslist.php", "", $this->TableVar, TRUE);
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
if (!isset($news_add)) $news_add = new cnews_add();

// Page init
$news_add->Page_Init();

// Page main
$news_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$news_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var news_add = new ew_Page("news_add");
news_add.PageID = "add"; // Page ID
var EW_PAGE_ID = news_add.PageID; // For backward compatibility

// Form object
var fnewsadd = new ew_Form("fnewsadd");

// Validate form
fnewsadd.Validate = function() {
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
fnewsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnewsadd.ValidateRequired = true;
<?php } else { ?>
fnewsadd.ValidateRequired = false; 
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
<?php $news_add->ShowPageHeader(); ?>
<?php
$news_add->ShowMessage();
?>
<form name="fnewsadd" id="fnewsadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($news_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $news_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="news">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($news->name_th->Visible) { // name_th ?>
	<div id="r_name_th" class="form-group">
		<label id="elh_news_name_th" for="x_name_th" class="col-sm-2 control-label ewLabel"><?php echo $news->name_th->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $news->name_th->CellAttributes() ?>>
<span id="el_news_name_th">
<input type="text" data-field="x_name_th" name="x_name_th" id="x_name_th" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($news->name_th->PlaceHolder) ?>" value="<?php echo $news->name_th->EditValue ?>"<?php echo $news->name_th->EditAttributes() ?>>
</span>
<?php echo $news->name_th->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($news->name_en->Visible) { // name_en ?>
	<div id="r_name_en" class="form-group">
		<label id="elh_news_name_en" for="x_name_en" class="col-sm-2 control-label ewLabel"><?php echo $news->name_en->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $news->name_en->CellAttributes() ?>>
<span id="el_news_name_en">
<input type="text" data-field="x_name_en" name="x_name_en" id="x_name_en" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($news->name_en->PlaceHolder) ?>" value="<?php echo $news->name_en->EditValue ?>"<?php echo $news->name_en->EditAttributes() ?>>
</span>
<?php echo $news->name_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($news->detail_th->Visible) { // detail_th ?>
	<div id="r_detail_th" class="form-group">
		<label id="elh_news_detail_th" class="col-sm-2 control-label ewLabel"><?php echo $news->detail_th->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $news->detail_th->CellAttributes() ?>>
<span id="el_news_detail_th">
<?php ew_AppendClass($news->detail_th->EditAttrs["class"], "editor"); ?>
<textarea data-field="x_detail_th" name="x_detail_th" id="x_detail_th" cols="35" rows="5" placeholder="<?php echo ew_HtmlEncode($news->detail_th->PlaceHolder) ?>"<?php echo $news->detail_th->EditAttributes() ?>><?php echo $news->detail_th->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fnewsadd", "x_detail_th", 35, 5, <?php echo ($news->detail_th->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $news->detail_th->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($news->detail_en->Visible) { // detail_en ?>
	<div id="r_detail_en" class="form-group">
		<label id="elh_news_detail_en" class="col-sm-2 control-label ewLabel"><?php echo $news->detail_en->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $news->detail_en->CellAttributes() ?>>
<span id="el_news_detail_en">
<?php ew_AppendClass($news->detail_en->EditAttrs["class"], "editor"); ?>
<textarea data-field="x_detail_en" name="x_detail_en" id="x_detail_en" cols="35" rows="5" placeholder="<?php echo ew_HtmlEncode($news->detail_en->PlaceHolder) ?>"<?php echo $news->detail_en->EditAttributes() ?>><?php echo $news->detail_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fnewsadd", "x_detail_en", 35, 5, <?php echo ($news->detail_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $news->detail_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($news->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_news_image" class="col-sm-2 control-label ewLabel"><?php echo $news->image->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $news->image->CellAttributes() ?>>
<span id="el_news_image">
<div id="fd_x_image">
<span title="<?php echo $news->image->FldTitle() ? $news->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($news->image->ReadOnly || $news->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x_image" id="x_image">
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $news->image->Upload->FileName ?>">
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="255">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $news->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $news->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $news->image->CustomMsg ?></div></div>
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
fnewsadd.Init();
</script>
<?php
$news_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$news_add->Page_Terminate();
?>
