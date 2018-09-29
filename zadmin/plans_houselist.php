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

$plans_house_list = NULL; // Initialize page object first

class cplans_house_list extends cplans_house {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{1B3DE6F9-6AA9-4A0A-BD16-3CDE31B2DBC1}";

	// Table name
	var $TableName = 'plans_house';

	// Page object name
	var $PageObjName = 'plans_house_list';

	// Grid form hidden field names
	var $FormName = 'fplans_houselist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "plans_houseadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "plans_housedelete.php";
		$this->MultiUpdateUrl = "plans_houseupdate.php";

		// Table object (plans)
		if (!isset($GLOBALS['plans'])) $GLOBALS['plans'] = new cplans();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'plans_house', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "plans") {
			global $plans;
			$rsmaster = $plans->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("planslist.php"); // Return to master page
			} else {
				$plans->LoadListRowValues($rsmaster);
				$plans->RowType = EW_ROWTYPE_MASTER; // Master row
				$plans->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = EW_SELECT_LIMIT;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("id", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		$bInlineEdit = TRUE;
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id", $this->id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("id")) <> strval($this->id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		$this->CurrentAction = "add";
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->name_th); // name_th
			$this->UpdateSort($this->name_en); // name_en
			$this->UpdateSort($this->image); // image
			$this->UpdateSort($this->enable); // enable
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->plans_id->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->name_th->setSort("");
				$this->name_en->setSort("");
				$this->image->setSort("");
				$this->enable->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn() && ($this->CurrentAction == "add");
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\">";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitSelected(document.fplans_houselist, '" . $this->MultiDeleteUrl . "');return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->IsLoggedIn());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fplans_houselist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->name_th->CurrentValue = NULL;
		$this->name_th->OldValue = $this->name_th->CurrentValue;
		$this->name_en->CurrentValue = NULL;
		$this->name_en->OldValue = $this->name_en->CurrentValue;
		$this->image->Upload->DbValue = NULL;
		$this->image->OldValue = $this->image->Upload->DbValue;
		$this->enable->CurrentValue = "1";
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
		if (!$this->enable->FldIsDetailKey) {
			$this->enable->setFormValue($objForm->GetValue("x_enable"));
		}
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->name_th->CurrentValue = $this->name_th->FormValue;
		$this->name_en->CurrentValue = $this->name_en->FormValue;
		$this->enable->CurrentValue = $this->enable->FormValue;
	}

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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// plans_id
		// name_th
		// name_en
		// image
		// enable

		$this->enable->CellCssStyle = "width: 1%;text-align: center;;";

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
				$this->image->LinkAttrs["data-rel"] = "plans_house_x" . $this->RowCnt . "_image";
				$this->image->LinkAttrs["class"] = "ewLightbox";
			}

			// enable
			$this->enable->LinkCustomAttributes = "";
			$this->enable->HrefValue = "";
			$this->enable->TooltipValue = "";
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
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->image, $this->RowIndex);

			// enable
			$this->enable->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->enable->FldTagValue(1), $this->enable->FldTagCaption(1) <> "" ? $this->enable->FldTagCaption(1) : $this->enable->FldTagValue(1));
			$arwrk[] = array($this->enable->FldTagValue(2), $this->enable->FldTagCaption(2) <> "" ? $this->enable->FldTagCaption(2) : $this->enable->FldTagValue(2));
			$this->enable->EditValue = $arwrk;

			// Edit refer script
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

			// enable
			$this->enable->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->image, $this->RowIndex);

			// enable
			$this->enable->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->enable->FldTagValue(1), $this->enable->FldTagCaption(1) <> "" ? $this->enable->FldTagCaption(1) : $this->enable->FldTagValue(1));
			$arwrk[] = array($this->enable->FldTagValue(2), $this->enable->FldTagCaption(2) <> "" ? $this->enable->FldTagCaption(2) : $this->enable->FldTagValue(2));
			$this->enable->EditValue = $arwrk;

			// Edit refer script
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

			// enable
			$this->enable->HrefValue = "";
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

			// enable
			$tmpBool = $this->enable->CurrentValue;
			if ($tmpBool <> "1" && $tmpBool <> "0")
				$tmpBool = (!empty($tmpBool)) ? "1" : "0";
			$this->enable->SetDbValueDef($rsnew, $tmpBool, NULL, $this->enable->ReadOnly);
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->image->OldUploadPath = "../images/plans/";
			$this->image->UploadPath = $this->image->OldUploadPath;
		}
		$rsnew = array();

		// name_th
		$this->name_th->SetDbValueDef($rsnew, $this->name_th->CurrentValue, NULL, FALSE);

		// name_en
		$this->name_en->SetDbValueDef($rsnew, $this->name_en->CurrentValue, NULL, FALSE);

		// image
		if (!$this->image->Upload->KeepFile) {
			$this->image->Upload->DbValue = ""; // No need to delete old file
			if ($this->image->Upload->FileName == "") {
				$rsnew['image'] = NULL;
			} else {
				$rsnew['image'] = $this->image->Upload->FileName;
			}
		}

		// enable
		$tmpBool = $this->enable->CurrentValue;
		if ($tmpBool <> "1" && $tmpBool <> "0")
			$tmpBool = (!empty($tmpBool)) ? "1" : "0";
		$this->enable->SetDbValueDef($rsnew, $tmpBool, NULL, strval($this->enable->CurrentValue) == "");

		// plans_id
		if ($this->plans_id->getSessionValue() <> "") {
			$rsnew['plans_id'] = $this->plans_id->getSessionValue();
		}
		if (!$this->image->Upload->KeepFile) {
			$this->image->UploadPath = "../images/plans/";
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($plans_house_list)) $plans_house_list = new cplans_house_list();

// Page init
$plans_house_list->Page_Init();

// Page main
$plans_house_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$plans_house_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var plans_house_list = new ew_Page("plans_house_list");
plans_house_list.PageID = "list"; // Page ID
var EW_PAGE_ID = plans_house_list.PageID; // For backward compatibility

// Form object
var fplans_houselist = new ew_Form("fplans_houselist");
fplans_houselist.FormKeyCountName = '<?php echo $plans_house_list->FormKeyCountName ?>';

// Validate form
fplans_houselist.Validate = function() {
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
	return true;
}

// Form_CustomValidate event
fplans_houselist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fplans_houselist.ValidateRequired = true;
<?php } else { ?>
fplans_houselist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($plans_house_list->TotalRecs > 0 && $plans_house_list->ExportOptions->Visible()) { ?>
<?php $plans_house_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php if (($plans_house->Export == "") || (EW_EXPORT_MASTER_RECORD && $plans_house->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "planslist.php";
if ($plans_house_list->DbMasterFilter <> "" && $plans_house->getCurrentMasterTable() == "plans") {
	if ($plans_house_list->MasterRecordExists) {
		if ($plans_house->getCurrentMasterTable() == $plans_house->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "plansmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($plans_house_list->TotalRecs <= 0)
			$plans_house_list->TotalRecs = $plans_house->SelectRecordCount();
	} else {
		if (!$plans_house_list->Recordset && ($plans_house_list->Recordset = $plans_house_list->LoadRecordset()))
			$plans_house_list->TotalRecs = $plans_house_list->Recordset->RecordCount();
	}
	$plans_house_list->StartRec = 1;
	if ($plans_house_list->DisplayRecs <= 0 || ($plans_house->Export <> "" && $plans_house->ExportAll)) // Display all records
		$plans_house_list->DisplayRecs = $plans_house_list->TotalRecs;
	if (!($plans_house->Export <> "" && $plans_house->ExportAll))
		$plans_house_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$plans_house_list->Recordset = $plans_house_list->LoadRecordset($plans_house_list->StartRec-1, $plans_house_list->DisplayRecs);

	// Set no record found message
	if ($plans_house->CurrentAction == "" && $plans_house_list->TotalRecs == 0) {
		if ($plans_house_list->SearchWhere == "0=101")
			$plans_house_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$plans_house_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$plans_house_list->RenderOtherOptions();
?>
<?php $plans_house_list->ShowPageHeader(); ?>
<?php
$plans_house_list->ShowMessage();
?>
<?php if ($plans_house_list->TotalRecs > 0 || $plans_house->CurrentAction <> "") { ?>
<div class="ewGrid">
<div class="ewGridUpperPanel">
<?php if ($plans_house->CurrentAction <> "gridadd" && $plans_house->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($plans_house_list->Pager)) $plans_house_list->Pager = new cNumericPager($plans_house_list->StartRec, $plans_house_list->DisplayRecs, $plans_house_list->TotalRecs, $plans_house_list->RecRange) ?>
<?php if ($plans_house_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($plans_house_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $plans_house_list->PageUrl() ?>start=<?php echo $plans_house_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($plans_house_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $plans_house_list->PageUrl() ?>start=<?php echo $plans_house_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($plans_house_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $plans_house_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($plans_house_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $plans_house_list->PageUrl() ?>start=<?php echo $plans_house_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($plans_house_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $plans_house_list->PageUrl() ?>start=<?php echo $plans_house_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $plans_house_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $plans_house_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $plans_house_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($plans_house_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="plans_house">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($plans_house_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($plans_house_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fplans_houselist" id="fplans_houselist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($plans_house_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $plans_house_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="plans_house">
<div id="gmp_plans_house" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($plans_house_list->TotalRecs > 0 || $plans_house->CurrentAction == "add" || $plans_house->CurrentAction == "copy") { ?>
<table id="tbl_plans_houselist" class="table ewTable">
<?php echo $plans_house->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$plans_house->RowType = EW_ROWTYPE_HEADER;

// Render list options
$plans_house_list->RenderListOptions();

// Render list options (header, left)
$plans_house_list->ListOptions->Render("header", "left");
?>
<?php if ($plans_house->name_th->Visible) { // name_th ?>
	<?php if ($plans_house->SortUrl($plans_house->name_th) == "") { ?>
		<th data-name="name_th"><div id="elh_plans_house_name_th" class="plans_house_name_th"><div class="ewTableHeaderCaption"><?php echo $plans_house->name_th->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name_th"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $plans_house->SortUrl($plans_house->name_th) ?>',1);"><div id="elh_plans_house_name_th" class="plans_house_name_th">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $plans_house->name_th->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($plans_house->name_th->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($plans_house->name_th->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($plans_house->name_en->Visible) { // name_en ?>
	<?php if ($plans_house->SortUrl($plans_house->name_en) == "") { ?>
		<th data-name="name_en"><div id="elh_plans_house_name_en" class="plans_house_name_en"><div class="ewTableHeaderCaption"><?php echo $plans_house->name_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name_en"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $plans_house->SortUrl($plans_house->name_en) ?>',1);"><div id="elh_plans_house_name_en" class="plans_house_name_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $plans_house->name_en->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($plans_house->name_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($plans_house->name_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($plans_house->image->Visible) { // image ?>
	<?php if ($plans_house->SortUrl($plans_house->image) == "") { ?>
		<th data-name="image"><div id="elh_plans_house_image" class="plans_house_image"><div class="ewTableHeaderCaption"><?php echo $plans_house->image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $plans_house->SortUrl($plans_house->image) ?>',1);"><div id="elh_plans_house_image" class="plans_house_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $plans_house->image->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($plans_house->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($plans_house->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($plans_house->enable->Visible) { // enable ?>
	<?php if ($plans_house->SortUrl($plans_house->enable) == "") { ?>
		<th data-name="enable"><div id="elh_plans_house_enable" class="plans_house_enable"><div class="ewTableHeaderCaption" style="width: 1%;text-align: center;;"><?php echo $plans_house->enable->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="enable"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $plans_house->SortUrl($plans_house->enable) ?>',1);"><div id="elh_plans_house_enable" class="plans_house_enable">
			<div class="ewTableHeaderBtn" style="width: 1%;text-align: center;;"><span class="ewTableHeaderCaption"><?php echo $plans_house->enable->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($plans_house->enable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($plans_house->enable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$plans_house_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($plans_house->CurrentAction == "add" || $plans_house->CurrentAction == "copy") {
		$plans_house_list->RowIndex = 0;
		$plans_house_list->KeyCount = $plans_house_list->RowIndex;
		if ($plans_house->CurrentAction == "add")
			$plans_house_list->LoadDefaultValues();
		if ($plans_house->EventCancelled) // Insert failed
			$plans_house_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$plans_house->ResetAttrs();
		$plans_house->RowAttrs = array_merge($plans_house->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_plans_house', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$plans_house->RowType = EW_ROWTYPE_ADD;

		// Render row
		$plans_house_list->RenderRow();

		// Render list options
		$plans_house_list->RenderListOptions();
		$plans_house_list->StartRowCnt = 0;
?>
	<tr<?php echo $plans_house->RowAttributes() ?>>
<?php

// Render list options (body, left)
$plans_house_list->ListOptions->Render("body", "left", $plans_house_list->RowCnt);
?>
	<?php if ($plans_house->name_th->Visible) { // name_th ?>
		<td data-name="name_th">
<span id="el<?php echo $plans_house_list->RowCnt ?>_plans_house_name_th" class="form-group plans_house_name_th">
<input type="text" data-field="x_name_th" name="x<?php echo $plans_house_list->RowIndex ?>_name_th" id="x<?php echo $plans_house_list->RowIndex ?>_name_th" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_th->PlaceHolder) ?>" value="<?php echo $plans_house->name_th->EditValue ?>"<?php echo $plans_house->name_th->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name_th" name="o<?php echo $plans_house_list->RowIndex ?>_name_th" id="o<?php echo $plans_house_list->RowIndex ?>_name_th" value="<?php echo ew_HtmlEncode($plans_house->name_th->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($plans_house->name_en->Visible) { // name_en ?>
		<td data-name="name_en">
<span id="el<?php echo $plans_house_list->RowCnt ?>_plans_house_name_en" class="form-group plans_house_name_en">
<input type="text" data-field="x_name_en" name="x<?php echo $plans_house_list->RowIndex ?>_name_en" id="x<?php echo $plans_house_list->RowIndex ?>_name_en" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_en->PlaceHolder) ?>" value="<?php echo $plans_house->name_en->EditValue ?>"<?php echo $plans_house->name_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name_en" name="o<?php echo $plans_house_list->RowIndex ?>_name_en" id="o<?php echo $plans_house_list->RowIndex ?>_name_en" value="<?php echo ew_HtmlEncode($plans_house->name_en->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($plans_house->image->Visible) { // image ?>
		<td data-name="image">
<span id="el<?php echo $plans_house_list->RowCnt ?>_plans_house_image" class="form-group plans_house_image">
<div id="fd_x<?php echo $plans_house_list->RowIndex ?>_image">
<span title="<?php echo $plans_house->image->FldTitle() ? $plans_house->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($plans_house->image->ReadOnly || $plans_house->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $plans_house_list->RowIndex ?>_image" id="x<?php echo $plans_house_list->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fn_x<?php echo $plans_house_list->RowIndex ?>_image" value="<?php echo $plans_house->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fa_x<?php echo $plans_house_list->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fs_x<?php echo $plans_house_list->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fx_x<?php echo $plans_house_list->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fm_x<?php echo $plans_house_list->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $plans_house_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_image" name="o<?php echo $plans_house_list->RowIndex ?>_image" id="o<?php echo $plans_house_list->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($plans_house->image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($plans_house->enable->Visible) { // enable ?>
		<td data-name="enable">
<span id="el<?php echo $plans_house_list->RowCnt ?>_plans_house_enable" class="form-group plans_house_enable">
<?php
$selwrk = (ew_ConvertToBool($plans_house->enable->CurrentValue)) ? " checked=\"checked\"" : "";
?>
<input type="checkbox" data-field="x_enable" name="x<?php echo $plans_house_list->RowIndex ?>_enable[]" id="x<?php echo $plans_house_list->RowIndex ?>_enable[]" value="1"<?php echo $selwrk ?><?php echo $plans_house->enable->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_enable" name="o<?php echo $plans_house_list->RowIndex ?>_enable[]" id="o<?php echo $plans_house_list->RowIndex ?>_enable[]" value="<?php echo ew_HtmlEncode($plans_house->enable->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$plans_house_list->ListOptions->Render("body", "right", $plans_house_list->RowCnt);
?>
<script type="text/javascript">
fplans_houselist.UpdateOpts(<?php echo $plans_house_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($plans_house->ExportAll && $plans_house->Export <> "") {
	$plans_house_list->StopRec = $plans_house_list->TotalRecs;
} else {

	// Set the last record to display
	if ($plans_house_list->TotalRecs > $plans_house_list->StartRec + $plans_house_list->DisplayRecs - 1)
		$plans_house_list->StopRec = $plans_house_list->StartRec + $plans_house_list->DisplayRecs - 1;
	else
		$plans_house_list->StopRec = $plans_house_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($plans_house_list->FormKeyCountName) && ($plans_house->CurrentAction == "gridadd" || $plans_house->CurrentAction == "gridedit" || $plans_house->CurrentAction == "F")) {
		$plans_house_list->KeyCount = $objForm->GetValue($plans_house_list->FormKeyCountName);
		$plans_house_list->StopRec = $plans_house_list->StartRec + $plans_house_list->KeyCount - 1;
	}
}
$plans_house_list->RecCnt = $plans_house_list->StartRec - 1;
if ($plans_house_list->Recordset && !$plans_house_list->Recordset->EOF) {
	$plans_house_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $plans_house_list->StartRec > 1)
		$plans_house_list->Recordset->Move($plans_house_list->StartRec - 1);
} elseif (!$plans_house->AllowAddDeleteRow && $plans_house_list->StopRec == 0) {
	$plans_house_list->StopRec = $plans_house->GridAddRowCount;
}

// Initialize aggregate
$plans_house->RowType = EW_ROWTYPE_AGGREGATEINIT;
$plans_house->ResetAttrs();
$plans_house_list->RenderRow();
$plans_house_list->EditRowCnt = 0;
if ($plans_house->CurrentAction == "edit")
	$plans_house_list->RowIndex = 1;
while ($plans_house_list->RecCnt < $plans_house_list->StopRec) {
	$plans_house_list->RecCnt++;
	if (intval($plans_house_list->RecCnt) >= intval($plans_house_list->StartRec)) {
		$plans_house_list->RowCnt++;

		// Set up key count
		$plans_house_list->KeyCount = $plans_house_list->RowIndex;

		// Init row class and style
		$plans_house->ResetAttrs();
		$plans_house->CssClass = "";
		if ($plans_house->CurrentAction == "gridadd") {
			$plans_house_list->LoadDefaultValues(); // Load default values
		} else {
			$plans_house_list->LoadRowValues($plans_house_list->Recordset); // Load row values
		}
		$plans_house->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($plans_house->CurrentAction == "edit") {
			if ($plans_house_list->CheckInlineEditKey() && $plans_house_list->EditRowCnt == 0) { // Inline edit
				$plans_house->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($plans_house->CurrentAction == "edit" && $plans_house->RowType == EW_ROWTYPE_EDIT && $plans_house->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$plans_house_list->RestoreFormValues(); // Restore form values
		}
		if ($plans_house->RowType == EW_ROWTYPE_EDIT) // Edit row
			$plans_house_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$plans_house->RowAttrs = array_merge($plans_house->RowAttrs, array('data-rowindex'=>$plans_house_list->RowCnt, 'id'=>'r' . $plans_house_list->RowCnt . '_plans_house', 'data-rowtype'=>$plans_house->RowType));

		// Render row
		$plans_house_list->RenderRow();

		// Render list options
		$plans_house_list->RenderListOptions();
?>
	<tr<?php echo $plans_house->RowAttributes() ?>>
<?php

// Render list options (body, left)
$plans_house_list->ListOptions->Render("body", "left", $plans_house_list->RowCnt);
?>
	<?php if ($plans_house->name_th->Visible) { // name_th ?>
		<td data-name="name_th"<?php echo $plans_house->name_th->CellAttributes() ?>>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $plans_house_list->RowCnt ?>_plans_house_name_th" class="form-group plans_house_name_th">
<input type="text" data-field="x_name_th" name="x<?php echo $plans_house_list->RowIndex ?>_name_th" id="x<?php echo $plans_house_list->RowIndex ?>_name_th" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_th->PlaceHolder) ?>" value="<?php echo $plans_house->name_th->EditValue ?>"<?php echo $plans_house->name_th->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $plans_house->name_th->ViewAttributes() ?>>
<?php echo $plans_house->name_th->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $plans_house_list->PageObjName . "_row_" . $plans_house_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT || $plans_house->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id" name="x<?php echo $plans_house_list->RowIndex ?>_id" id="x<?php echo $plans_house_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($plans_house->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($plans_house->name_en->Visible) { // name_en ?>
		<td data-name="name_en"<?php echo $plans_house->name_en->CellAttributes() ?>>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $plans_house_list->RowCnt ?>_plans_house_name_en" class="form-group plans_house_name_en">
<input type="text" data-field="x_name_en" name="x<?php echo $plans_house_list->RowIndex ?>_name_en" id="x<?php echo $plans_house_list->RowIndex ?>_name_en" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_en->PlaceHolder) ?>" value="<?php echo $plans_house->name_en->EditValue ?>"<?php echo $plans_house->name_en->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $plans_house->name_en->ViewAttributes() ?>>
<?php echo $plans_house->name_en->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($plans_house->image->Visible) { // image ?>
		<td data-name="image"<?php echo $plans_house->image->CellAttributes() ?>>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $plans_house_list->RowCnt ?>_plans_house_image" class="form-group plans_house_image">
<div id="fd_x<?php echo $plans_house_list->RowIndex ?>_image">
<span title="<?php echo $plans_house->image->FldTitle() ? $plans_house->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($plans_house->image->ReadOnly || $plans_house->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $plans_house_list->RowIndex ?>_image" id="x<?php echo $plans_house_list->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fn_x<?php echo $plans_house_list->RowIndex ?>_image" value="<?php echo $plans_house->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $plans_house_list->RowIndex ?>_image"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fa_x<?php echo $plans_house_list->RowIndex ?>_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fa_x<?php echo $plans_house_list->RowIndex ?>_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fs_x<?php echo $plans_house_list->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fx_x<?php echo $plans_house_list->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $plans_house_list->RowIndex ?>_image" id= "fm_x<?php echo $plans_house_list->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $plans_house_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span>
<?php echo ew_GetFileViewTag($plans_house->image, $plans_house->image->ListViewValue()) ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($plans_house->enable->Visible) { // enable ?>
		<td data-name="enable"<?php echo $plans_house->enable->CellAttributes() ?>>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $plans_house_list->RowCnt ?>_plans_house_enable" class="form-group plans_house_enable">
<?php
$selwrk = (ew_ConvertToBool($plans_house->enable->CurrentValue)) ? " checked=\"checked\"" : "";
?>
<input type="checkbox" data-field="x_enable" name="x<?php echo $plans_house_list->RowIndex ?>_enable[]" id="x<?php echo $plans_house_list->RowIndex ?>_enable[]" value="1"<?php echo $selwrk ?><?php echo $plans_house->enable->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $plans_house->enable->ViewAttributes() ?>>
<?php if (ew_ConvertToBool($plans_house->enable->CurrentValue)) { ?>
<input type="checkbox" value="<?php echo $plans_house->enable->ListViewValue() ?>" checked="checked" disabled="disabled">
<?php } else { ?>
<input type="checkbox" value="<?php echo $plans_house->enable->ListViewValue() ?>" disabled="disabled">
<?php } ?>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$plans_house_list->ListOptions->Render("body", "right", $plans_house_list->RowCnt);
?>
	</tr>
<?php if ($plans_house->RowType == EW_ROWTYPE_ADD || $plans_house->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fplans_houselist.UpdateOpts(<?php echo $plans_house_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($plans_house->CurrentAction <> "gridadd")
		$plans_house_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($plans_house->CurrentAction == "add" || $plans_house->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $plans_house_list->FormKeyCountName ?>" id="<?php echo $plans_house_list->FormKeyCountName ?>" value="<?php echo $plans_house_list->KeyCount ?>">
<?php } ?>
<?php if ($plans_house->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $plans_house_list->FormKeyCountName ?>" id="<?php echo $plans_house_list->FormKeyCountName ?>" value="<?php echo $plans_house_list->KeyCount ?>">
<?php } ?>
<?php if ($plans_house->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($plans_house_list->Recordset)
	$plans_house_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($plans_house->CurrentAction <> "gridadd" && $plans_house->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($plans_house_list->Pager)) $plans_house_list->Pager = new cNumericPager($plans_house_list->StartRec, $plans_house_list->DisplayRecs, $plans_house_list->TotalRecs, $plans_house_list->RecRange) ?>
<?php if ($plans_house_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($plans_house_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $plans_house_list->PageUrl() ?>start=<?php echo $plans_house_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($plans_house_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $plans_house_list->PageUrl() ?>start=<?php echo $plans_house_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($plans_house_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $plans_house_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($plans_house_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $plans_house_list->PageUrl() ?>start=<?php echo $plans_house_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($plans_house_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $plans_house_list->PageUrl() ?>start=<?php echo $plans_house_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $plans_house_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $plans_house_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $plans_house_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($plans_house_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="plans_house">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($plans_house_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($plans_house_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($plans_house_list->TotalRecs == 0 && $plans_house->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($plans_house_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fplans_houselist.Init();
</script>
<?php
$plans_house_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$plans_house_list->Page_Terminate();
?>
