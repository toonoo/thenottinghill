<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "galleryinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$gallery_list = NULL; // Initialize page object first

class cgallery_list extends cgallery {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{1B3DE6F9-6AA9-4A0A-BD16-3CDE31B2DBC1}";

	// Table name
	var $TableName = 'gallery';

	// Page object name
	var $PageObjName = 'gallery_list';

	// Grid form hidden field names
	var $FormName = 'fgallerylist';
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

		// Table object (gallery)
		if (!isset($GLOBALS["gallery"]) || get_class($GLOBALS["gallery"]) == "cgallery") {
			$GLOBALS["gallery"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gallery"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "galleryadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "gallerydelete.php";
		$this->MultiUpdateUrl = "galleryupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gallery', TRUE);

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
		global $EW_EXPORT, $gallery;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($gallery);
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
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

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
			$this->UpdateSort($this->image); // image
			$this->UpdateSort($this->description_th); // description_th
			$this->UpdateSort($this->description_en); // description_en
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->image->setSort("");
				$this->description_th->setSort("");
				$this->description_en->setSort("");
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitSelected(document.fgallerylist, '" . $this->MultiDeleteUrl . "');return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgallerylist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->image->Upload->DbValue = NULL;
		$this->image->OldValue = $this->image->Upload->DbValue;
		$this->description_th->CurrentValue = NULL;
		$this->description_th->OldValue = $this->description_th->CurrentValue;
		$this->description_en->CurrentValue = NULL;
		$this->description_en->OldValue = $this->description_en->CurrentValue;
		$this->enable->CurrentValue = "1";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->description_th->FldIsDetailKey) {
			$this->description_th->setFormValue($objForm->GetValue("x_description_th"));
		}
		if (!$this->description_en->FldIsDetailKey) {
			$this->description_en->setFormValue($objForm->GetValue("x_description_en"));
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
		$this->description_th->CurrentValue = $this->description_th->FormValue;
		$this->description_en->CurrentValue = $this->description_en->FormValue;
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
		$this->image->Upload->DbValue = $rs->fields('image');
		$this->image->CurrentValue = $this->image->Upload->DbValue;
		$this->description_th->setDbValue($rs->fields('description_th'));
		$this->description_en->setDbValue($rs->fields('description_en'));
		$this->enable->setDbValue($rs->fields('enable'));
		$this->created_date->setDbValue($rs->fields('created_date'));
		$this->modified_date->setDbValue($rs->fields('modified_date'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->image->Upload->DbValue = $row['image'];
		$this->description_th->DbValue = $row['description_th'];
		$this->description_en->DbValue = $row['description_en'];
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
		// image
		// description_th
		// description_en
		// enable

		$this->enable->CellCssStyle = "width: 1%;text-align: center;;";

		// created_date
		// modified_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// image
			$this->image->UploadPath = "../images/gallery/";
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

			// description_th
			$this->description_th->ViewValue = $this->description_th->CurrentValue;
			$this->description_th->ViewCustomAttributes = "";

			// description_en
			$this->description_en->ViewValue = $this->description_en->CurrentValue;
			$this->description_en->ViewCustomAttributes = "";

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
			$this->image->UploadPath = "../images/gallery/";
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
				$this->image->LinkAttrs["data-rel"] = "gallery_x" . $this->RowCnt . "_image";
				$this->image->LinkAttrs["class"] = "ewLightbox";
			}

			// description_th
			$this->description_th->LinkCustomAttributes = "";
			$this->description_th->HrefValue = "";
			$this->description_th->TooltipValue = "";

			// description_en
			$this->description_en->LinkCustomAttributes = "";
			$this->description_en->HrefValue = "";
			$this->description_en->TooltipValue = "";

			// enable
			$this->enable->LinkCustomAttributes = "";
			$this->enable->HrefValue = "";
			$this->enable->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "../images/gallery/";
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

			// description_th
			$this->description_th->EditAttrs["class"] = "form-control";
			$this->description_th->EditCustomAttributes = "";
			$this->description_th->EditValue = ew_HtmlEncode($this->description_th->CurrentValue);
			$this->description_th->PlaceHolder = ew_RemoveHtml($this->description_th->FldCaption());

			// description_en
			$this->description_en->EditAttrs["class"] = "form-control";
			$this->description_en->EditCustomAttributes = "";
			$this->description_en->EditValue = ew_HtmlEncode($this->description_en->CurrentValue);
			$this->description_en->PlaceHolder = ew_RemoveHtml($this->description_en->FldCaption());

			// enable
			$this->enable->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->enable->FldTagValue(1), $this->enable->FldTagCaption(1) <> "" ? $this->enable->FldTagCaption(1) : $this->enable->FldTagValue(1));
			$arwrk[] = array($this->enable->FldTagValue(2), $this->enable->FldTagCaption(2) <> "" ? $this->enable->FldTagCaption(2) : $this->enable->FldTagValue(2));
			$this->enable->EditValue = $arwrk;

			// Edit refer script
			// image

			$this->image->UploadPath = "../images/gallery/";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

			// description_th
			$this->description_th->HrefValue = "";

			// description_en
			$this->description_en->HrefValue = "";

			// enable
			$this->enable->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "../images/gallery/";
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

			// description_th
			$this->description_th->EditAttrs["class"] = "form-control";
			$this->description_th->EditCustomAttributes = "";
			$this->description_th->EditValue = ew_HtmlEncode($this->description_th->CurrentValue);
			$this->description_th->PlaceHolder = ew_RemoveHtml($this->description_th->FldCaption());

			// description_en
			$this->description_en->EditAttrs["class"] = "form-control";
			$this->description_en->EditCustomAttributes = "";
			$this->description_en->EditValue = ew_HtmlEncode($this->description_en->CurrentValue);
			$this->description_en->PlaceHolder = ew_RemoveHtml($this->description_en->FldCaption());

			// enable
			$this->enable->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->enable->FldTagValue(1), $this->enable->FldTagCaption(1) <> "" ? $this->enable->FldTagCaption(1) : $this->enable->FldTagValue(1));
			$arwrk[] = array($this->enable->FldTagValue(2), $this->enable->FldTagCaption(2) <> "" ? $this->enable->FldTagCaption(2) : $this->enable->FldTagValue(2));
			$this->enable->EditValue = $arwrk;

			// Edit refer script
			// image

			$this->image->UploadPath = "../images/gallery/";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

			// description_th
			$this->description_th->HrefValue = "";

			// description_en
			$this->description_en->HrefValue = "";

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
			$this->image->OldUploadPath = "../images/gallery/";
			$this->image->UploadPath = $this->image->OldUploadPath;
			$rsnew = array();

			// image
			if (!($this->image->ReadOnly) && !$this->image->Upload->KeepFile) {
				$this->image->Upload->DbValue = $rsold['image']; // Get original value
				if ($this->image->Upload->FileName == "") {
					$rsnew['image'] = NULL;
				} else {
					$rsnew['image'] = $this->image->Upload->FileName;
				}
			}

			// description_th
			$this->description_th->SetDbValueDef($rsnew, $this->description_th->CurrentValue, NULL, $this->description_th->ReadOnly);

			// description_en
			$this->description_en->SetDbValueDef($rsnew, $this->description_en->CurrentValue, NULL, $this->description_en->ReadOnly);

			// enable
			$tmpBool = $this->enable->CurrentValue;
			if ($tmpBool <> "1" && $tmpBool <> "0")
				$tmpBool = (!empty($tmpBool)) ? "1" : "0";
			$this->enable->SetDbValueDef($rsnew, $tmpBool, NULL, $this->enable->ReadOnly);
			if (!$this->image->Upload->KeepFile) {
				$this->image->UploadPath = "../images/gallery/";
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
			$this->image->OldUploadPath = "../images/gallery/";
			$this->image->UploadPath = $this->image->OldUploadPath;
		}
		$rsnew = array();

		// image
		if (!$this->image->Upload->KeepFile) {
			$this->image->Upload->DbValue = ""; // No need to delete old file
			if ($this->image->Upload->FileName == "") {
				$rsnew['image'] = NULL;
			} else {
				$rsnew['image'] = $this->image->Upload->FileName;
			}
		}

		// description_th
		$this->description_th->SetDbValueDef($rsnew, $this->description_th->CurrentValue, NULL, FALSE);

		// description_en
		$this->description_en->SetDbValueDef($rsnew, $this->description_en->CurrentValue, NULL, FALSE);

		// enable
		$tmpBool = $this->enable->CurrentValue;
		if ($tmpBool <> "1" && $tmpBool <> "0")
			$tmpBool = (!empty($tmpBool)) ? "1" : "0";
		$this->enable->SetDbValueDef($rsnew, $tmpBool, NULL, strval($this->enable->CurrentValue) == "");
		if (!$this->image->Upload->KeepFile) {
			$this->image->UploadPath = "../images/gallery/";
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
if (!isset($gallery_list)) $gallery_list = new cgallery_list();

// Page init
$gallery_list->Page_Init();

// Page main
$gallery_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gallery_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gallery_list = new ew_Page("gallery_list");
gallery_list.PageID = "list"; // Page ID
var EW_PAGE_ID = gallery_list.PageID; // For backward compatibility

// Form object
var fgallerylist = new ew_Form("fgallerylist");
fgallerylist.FormKeyCountName = '<?php echo $gallery_list->FormKeyCountName ?>';

// Validate form
fgallerylist.Validate = function() {
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
fgallerylist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgallerylist.ValidateRequired = true;
<?php } else { ?>
fgallerylist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($gallery_list->TotalRecs > 0 && $gallery_list->ExportOptions->Visible()) { ?>
<?php $gallery_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($gallery_list->TotalRecs <= 0)
			$gallery_list->TotalRecs = $gallery->SelectRecordCount();
	} else {
		if (!$gallery_list->Recordset && ($gallery_list->Recordset = $gallery_list->LoadRecordset()))
			$gallery_list->TotalRecs = $gallery_list->Recordset->RecordCount();
	}
	$gallery_list->StartRec = 1;
	if ($gallery_list->DisplayRecs <= 0 || ($gallery->Export <> "" && $gallery->ExportAll)) // Display all records
		$gallery_list->DisplayRecs = $gallery_list->TotalRecs;
	if (!($gallery->Export <> "" && $gallery->ExportAll))
		$gallery_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$gallery_list->Recordset = $gallery_list->LoadRecordset($gallery_list->StartRec-1, $gallery_list->DisplayRecs);

	// Set no record found message
	if ($gallery->CurrentAction == "" && $gallery_list->TotalRecs == 0) {
		if ($gallery_list->SearchWhere == "0=101")
			$gallery_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$gallery_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$gallery_list->RenderOtherOptions();
?>
<?php $gallery_list->ShowPageHeader(); ?>
<?php
$gallery_list->ShowMessage();
?>
<?php if ($gallery_list->TotalRecs > 0 || $gallery->CurrentAction <> "") { ?>
<div class="ewGrid">
<div class="ewGridUpperPanel">
<?php if ($gallery->CurrentAction <> "gridadd" && $gallery->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($gallery_list->Pager)) $gallery_list->Pager = new cNumericPager($gallery_list->StartRec, $gallery_list->DisplayRecs, $gallery_list->TotalRecs, $gallery_list->RecRange) ?>
<?php if ($gallery_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($gallery_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $gallery_list->PageUrl() ?>start=<?php echo $gallery_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($gallery_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $gallery_list->PageUrl() ?>start=<?php echo $gallery_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($gallery_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $gallery_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($gallery_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $gallery_list->PageUrl() ?>start=<?php echo $gallery_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($gallery_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $gallery_list->PageUrl() ?>start=<?php echo $gallery_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $gallery_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $gallery_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $gallery_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($gallery_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="gallery">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($gallery_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($gallery_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fgallerylist" id="fgallerylist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gallery_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gallery_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gallery">
<div id="gmp_gallery" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($gallery_list->TotalRecs > 0 || $gallery->CurrentAction == "add" || $gallery->CurrentAction == "copy") { ?>
<table id="tbl_gallerylist" class="table ewTable">
<?php echo $gallery->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$gallery->RowType = EW_ROWTYPE_HEADER;

// Render list options
$gallery_list->RenderListOptions();

// Render list options (header, left)
$gallery_list->ListOptions->Render("header", "left");
?>
<?php if ($gallery->image->Visible) { // image ?>
	<?php if ($gallery->SortUrl($gallery->image) == "") { ?>
		<th data-name="image"><div id="elh_gallery_image" class="gallery_image"><div class="ewTableHeaderCaption"><?php echo $gallery->image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gallery->SortUrl($gallery->image) ?>',1);"><div id="elh_gallery_image" class="gallery_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gallery->image->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gallery->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gallery->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gallery->description_th->Visible) { // description_th ?>
	<?php if ($gallery->SortUrl($gallery->description_th) == "") { ?>
		<th data-name="description_th"><div id="elh_gallery_description_th" class="gallery_description_th"><div class="ewTableHeaderCaption"><?php echo $gallery->description_th->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="description_th"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gallery->SortUrl($gallery->description_th) ?>',1);"><div id="elh_gallery_description_th" class="gallery_description_th">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gallery->description_th->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gallery->description_th->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gallery->description_th->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gallery->description_en->Visible) { // description_en ?>
	<?php if ($gallery->SortUrl($gallery->description_en) == "") { ?>
		<th data-name="description_en"><div id="elh_gallery_description_en" class="gallery_description_en"><div class="ewTableHeaderCaption"><?php echo $gallery->description_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="description_en"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gallery->SortUrl($gallery->description_en) ?>',1);"><div id="elh_gallery_description_en" class="gallery_description_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gallery->description_en->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gallery->description_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gallery->description_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($gallery->enable->Visible) { // enable ?>
	<?php if ($gallery->SortUrl($gallery->enable) == "") { ?>
		<th data-name="enable"><div id="elh_gallery_enable" class="gallery_enable"><div class="ewTableHeaderCaption" style="width: 1%;text-align: center;;"><?php echo $gallery->enable->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="enable"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gallery->SortUrl($gallery->enable) ?>',1);"><div id="elh_gallery_enable" class="gallery_enable">
			<div class="ewTableHeaderBtn" style="width: 1%;text-align: center;;"><span class="ewTableHeaderCaption"><?php echo $gallery->enable->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gallery->enable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gallery->enable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$gallery_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($gallery->CurrentAction == "add" || $gallery->CurrentAction == "copy") {
		$gallery_list->RowIndex = 0;
		$gallery_list->KeyCount = $gallery_list->RowIndex;
		if ($gallery->CurrentAction == "add")
			$gallery_list->LoadDefaultValues();
		if ($gallery->EventCancelled) // Insert failed
			$gallery_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$gallery->ResetAttrs();
		$gallery->RowAttrs = array_merge($gallery->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_gallery', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$gallery->RowType = EW_ROWTYPE_ADD;

		// Render row
		$gallery_list->RenderRow();

		// Render list options
		$gallery_list->RenderListOptions();
		$gallery_list->StartRowCnt = 0;
?>
	<tr<?php echo $gallery->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gallery_list->ListOptions->Render("body", "left", $gallery_list->RowCnt);
?>
	<?php if ($gallery->image->Visible) { // image ?>
		<td data-name="image">
<span id="el<?php echo $gallery_list->RowCnt ?>_gallery_image" class="form-group gallery_image">
<div id="fd_x<?php echo $gallery_list->RowIndex ?>_image">
<span title="<?php echo $gallery->image->FldTitle() ? $gallery->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($gallery->image->ReadOnly || $gallery->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $gallery_list->RowIndex ?>_image" id="x<?php echo $gallery_list->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $gallery_list->RowIndex ?>_image" id= "fn_x<?php echo $gallery_list->RowIndex ?>_image" value="<?php echo $gallery->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $gallery_list->RowIndex ?>_image" id= "fa_x<?php echo $gallery_list->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $gallery_list->RowIndex ?>_image" id= "fs_x<?php echo $gallery_list->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $gallery_list->RowIndex ?>_image" id= "fx_x<?php echo $gallery_list->RowIndex ?>_image" value="<?php echo $gallery->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $gallery_list->RowIndex ?>_image" id= "fm_x<?php echo $gallery_list->RowIndex ?>_image" value="<?php echo $gallery->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $gallery_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_image" name="o<?php echo $gallery_list->RowIndex ?>_image" id="o<?php echo $gallery_list->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($gallery->image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gallery->description_th->Visible) { // description_th ?>
		<td data-name="description_th">
<span id="el<?php echo $gallery_list->RowCnt ?>_gallery_description_th" class="form-group gallery_description_th">
<input type="text" data-field="x_description_th" name="x<?php echo $gallery_list->RowIndex ?>_description_th" id="x<?php echo $gallery_list->RowIndex ?>_description_th" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($gallery->description_th->PlaceHolder) ?>" value="<?php echo $gallery->description_th->EditValue ?>"<?php echo $gallery->description_th->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_description_th" name="o<?php echo $gallery_list->RowIndex ?>_description_th" id="o<?php echo $gallery_list->RowIndex ?>_description_th" value="<?php echo ew_HtmlEncode($gallery->description_th->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gallery->description_en->Visible) { // description_en ?>
		<td data-name="description_en">
<span id="el<?php echo $gallery_list->RowCnt ?>_gallery_description_en" class="form-group gallery_description_en">
<input type="text" data-field="x_description_en" name="x<?php echo $gallery_list->RowIndex ?>_description_en" id="x<?php echo $gallery_list->RowIndex ?>_description_en" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($gallery->description_en->PlaceHolder) ?>" value="<?php echo $gallery->description_en->EditValue ?>"<?php echo $gallery->description_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_description_en" name="o<?php echo $gallery_list->RowIndex ?>_description_en" id="o<?php echo $gallery_list->RowIndex ?>_description_en" value="<?php echo ew_HtmlEncode($gallery->description_en->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($gallery->enable->Visible) { // enable ?>
		<td data-name="enable">
<span id="el<?php echo $gallery_list->RowCnt ?>_gallery_enable" class="form-group gallery_enable">
<?php
$selwrk = (ew_ConvertToBool($gallery->enable->CurrentValue)) ? " checked=\"checked\"" : "";
?>
<input type="checkbox" data-field="x_enable" name="x<?php echo $gallery_list->RowIndex ?>_enable[]" id="x<?php echo $gallery_list->RowIndex ?>_enable[]" value="1"<?php echo $selwrk ?><?php echo $gallery->enable->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_enable" name="o<?php echo $gallery_list->RowIndex ?>_enable[]" id="o<?php echo $gallery_list->RowIndex ?>_enable[]" value="<?php echo ew_HtmlEncode($gallery->enable->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gallery_list->ListOptions->Render("body", "right", $gallery_list->RowCnt);
?>
<script type="text/javascript">
fgallerylist.UpdateOpts(<?php echo $gallery_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($gallery->ExportAll && $gallery->Export <> "") {
	$gallery_list->StopRec = $gallery_list->TotalRecs;
} else {

	// Set the last record to display
	if ($gallery_list->TotalRecs > $gallery_list->StartRec + $gallery_list->DisplayRecs - 1)
		$gallery_list->StopRec = $gallery_list->StartRec + $gallery_list->DisplayRecs - 1;
	else
		$gallery_list->StopRec = $gallery_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($gallery_list->FormKeyCountName) && ($gallery->CurrentAction == "gridadd" || $gallery->CurrentAction == "gridedit" || $gallery->CurrentAction == "F")) {
		$gallery_list->KeyCount = $objForm->GetValue($gallery_list->FormKeyCountName);
		$gallery_list->StopRec = $gallery_list->StartRec + $gallery_list->KeyCount - 1;
	}
}
$gallery_list->RecCnt = $gallery_list->StartRec - 1;
if ($gallery_list->Recordset && !$gallery_list->Recordset->EOF) {
	$gallery_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $gallery_list->StartRec > 1)
		$gallery_list->Recordset->Move($gallery_list->StartRec - 1);
} elseif (!$gallery->AllowAddDeleteRow && $gallery_list->StopRec == 0) {
	$gallery_list->StopRec = $gallery->GridAddRowCount;
}

// Initialize aggregate
$gallery->RowType = EW_ROWTYPE_AGGREGATEINIT;
$gallery->ResetAttrs();
$gallery_list->RenderRow();
$gallery_list->EditRowCnt = 0;
if ($gallery->CurrentAction == "edit")
	$gallery_list->RowIndex = 1;
while ($gallery_list->RecCnt < $gallery_list->StopRec) {
	$gallery_list->RecCnt++;
	if (intval($gallery_list->RecCnt) >= intval($gallery_list->StartRec)) {
		$gallery_list->RowCnt++;

		// Set up key count
		$gallery_list->KeyCount = $gallery_list->RowIndex;

		// Init row class and style
		$gallery->ResetAttrs();
		$gallery->CssClass = "";
		if ($gallery->CurrentAction == "gridadd") {
			$gallery_list->LoadDefaultValues(); // Load default values
		} else {
			$gallery_list->LoadRowValues($gallery_list->Recordset); // Load row values
		}
		$gallery->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($gallery->CurrentAction == "edit") {
			if ($gallery_list->CheckInlineEditKey() && $gallery_list->EditRowCnt == 0) { // Inline edit
				$gallery->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($gallery->CurrentAction == "edit" && $gallery->RowType == EW_ROWTYPE_EDIT && $gallery->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$gallery_list->RestoreFormValues(); // Restore form values
		}
		if ($gallery->RowType == EW_ROWTYPE_EDIT) // Edit row
			$gallery_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$gallery->RowAttrs = array_merge($gallery->RowAttrs, array('data-rowindex'=>$gallery_list->RowCnt, 'id'=>'r' . $gallery_list->RowCnt . '_gallery', 'data-rowtype'=>$gallery->RowType));

		// Render row
		$gallery_list->RenderRow();

		// Render list options
		$gallery_list->RenderListOptions();
?>
	<tr<?php echo $gallery->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gallery_list->ListOptions->Render("body", "left", $gallery_list->RowCnt);
?>
	<?php if ($gallery->image->Visible) { // image ?>
		<td data-name="image"<?php echo $gallery->image->CellAttributes() ?>>
<?php if ($gallery->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gallery_list->RowCnt ?>_gallery_image" class="form-group gallery_image">
<div id="fd_x<?php echo $gallery_list->RowIndex ?>_image">
<span title="<?php echo $gallery->image->FldTitle() ? $gallery->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($gallery->image->ReadOnly || $gallery->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $gallery_list->RowIndex ?>_image" id="x<?php echo $gallery_list->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $gallery_list->RowIndex ?>_image" id= "fn_x<?php echo $gallery_list->RowIndex ?>_image" value="<?php echo $gallery->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $gallery_list->RowIndex ?>_image"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $gallery_list->RowIndex ?>_image" id= "fa_x<?php echo $gallery_list->RowIndex ?>_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $gallery_list->RowIndex ?>_image" id= "fa_x<?php echo $gallery_list->RowIndex ?>_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $gallery_list->RowIndex ?>_image" id= "fs_x<?php echo $gallery_list->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $gallery_list->RowIndex ?>_image" id= "fx_x<?php echo $gallery_list->RowIndex ?>_image" value="<?php echo $gallery->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $gallery_list->RowIndex ?>_image" id= "fm_x<?php echo $gallery_list->RowIndex ?>_image" value="<?php echo $gallery->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $gallery_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($gallery->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span>
<?php echo ew_GetFileViewTag($gallery->image, $gallery->image->ListViewValue()) ?>
</span>
<?php } ?>
<a id="<?php echo $gallery_list->PageObjName . "_row_" . $gallery_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($gallery->RowType == EW_ROWTYPE_EDIT || $gallery->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id" name="x<?php echo $gallery_list->RowIndex ?>_id" id="x<?php echo $gallery_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($gallery->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($gallery->description_th->Visible) { // description_th ?>
		<td data-name="description_th"<?php echo $gallery->description_th->CellAttributes() ?>>
<?php if ($gallery->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gallery_list->RowCnt ?>_gallery_description_th" class="form-group gallery_description_th">
<input type="text" data-field="x_description_th" name="x<?php echo $gallery_list->RowIndex ?>_description_th" id="x<?php echo $gallery_list->RowIndex ?>_description_th" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($gallery->description_th->PlaceHolder) ?>" value="<?php echo $gallery->description_th->EditValue ?>"<?php echo $gallery->description_th->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($gallery->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gallery->description_th->ViewAttributes() ?>>
<?php echo $gallery->description_th->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gallery->description_en->Visible) { // description_en ?>
		<td data-name="description_en"<?php echo $gallery->description_en->CellAttributes() ?>>
<?php if ($gallery->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gallery_list->RowCnt ?>_gallery_description_en" class="form-group gallery_description_en">
<input type="text" data-field="x_description_en" name="x<?php echo $gallery_list->RowIndex ?>_description_en" id="x<?php echo $gallery_list->RowIndex ?>_description_en" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($gallery->description_en->PlaceHolder) ?>" value="<?php echo $gallery->description_en->EditValue ?>"<?php echo $gallery->description_en->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($gallery->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gallery->description_en->ViewAttributes() ?>>
<?php echo $gallery->description_en->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($gallery->enable->Visible) { // enable ?>
		<td data-name="enable"<?php echo $gallery->enable->CellAttributes() ?>>
<?php if ($gallery->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $gallery_list->RowCnt ?>_gallery_enable" class="form-group gallery_enable">
<?php
$selwrk = (ew_ConvertToBool($gallery->enable->CurrentValue)) ? " checked=\"checked\"" : "";
?>
<input type="checkbox" data-field="x_enable" name="x<?php echo $gallery_list->RowIndex ?>_enable[]" id="x<?php echo $gallery_list->RowIndex ?>_enable[]" value="1"<?php echo $selwrk ?><?php echo $gallery->enable->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($gallery->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $gallery->enable->ViewAttributes() ?>>
<?php if (ew_ConvertToBool($gallery->enable->CurrentValue)) { ?>
<input type="checkbox" value="<?php echo $gallery->enable->ListViewValue() ?>" checked="checked" disabled="disabled">
<?php } else { ?>
<input type="checkbox" value="<?php echo $gallery->enable->ListViewValue() ?>" disabled="disabled">
<?php } ?>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gallery_list->ListOptions->Render("body", "right", $gallery_list->RowCnt);
?>
	</tr>
<?php if ($gallery->RowType == EW_ROWTYPE_ADD || $gallery->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fgallerylist.UpdateOpts(<?php echo $gallery_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($gallery->CurrentAction <> "gridadd")
		$gallery_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($gallery->CurrentAction == "add" || $gallery->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $gallery_list->FormKeyCountName ?>" id="<?php echo $gallery_list->FormKeyCountName ?>" value="<?php echo $gallery_list->KeyCount ?>">
<?php } ?>
<?php if ($gallery->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $gallery_list->FormKeyCountName ?>" id="<?php echo $gallery_list->FormKeyCountName ?>" value="<?php echo $gallery_list->KeyCount ?>">
<?php } ?>
<?php if ($gallery->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($gallery_list->Recordset)
	$gallery_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($gallery->CurrentAction <> "gridadd" && $gallery->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($gallery_list->Pager)) $gallery_list->Pager = new cNumericPager($gallery_list->StartRec, $gallery_list->DisplayRecs, $gallery_list->TotalRecs, $gallery_list->RecRange) ?>
<?php if ($gallery_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($gallery_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $gallery_list->PageUrl() ?>start=<?php echo $gallery_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($gallery_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $gallery_list->PageUrl() ?>start=<?php echo $gallery_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($gallery_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $gallery_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($gallery_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $gallery_list->PageUrl() ?>start=<?php echo $gallery_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($gallery_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $gallery_list->PageUrl() ?>start=<?php echo $gallery_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $gallery_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $gallery_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $gallery_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($gallery_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="gallery">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="20"<?php if ($gallery_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($gallery_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($gallery_list->TotalRecs == 0 && $gallery->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($gallery_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fgallerylist.Init();
</script>
<?php
$gallery_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gallery_list->Page_Terminate();
?>
