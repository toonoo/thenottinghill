<?php

// Global variable for table object
$plans_house = NULL;

//
// Table class for plans_house
//
class cplans_house extends cTable {
	var $id;
	var $plans_id;
	var $name_th;
	var $name_en;
	var $image;
	var $enable;
	var $created_date;
	var $modified_date;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'plans_house';
		$this->TableName = 'plans_house';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('plans_house', 'plans_house', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// plans_id
		$this->plans_id = new cField('plans_house', 'plans_house', 'x_plans_id', 'plans_id', '`plans_id`', '`plans_id`', 3, -1, FALSE, '`plans_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->plans_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['plans_id'] = &$this->plans_id;

		// name_th
		$this->name_th = new cField('plans_house', 'plans_house', 'x_name_th', 'name_th', '`name_th`', '`name_th`', 200, -1, FALSE, '`name_th`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name_th'] = &$this->name_th;

		// name_en
		$this->name_en = new cField('plans_house', 'plans_house', 'x_name_en', 'name_en', '`name_en`', '`name_en`', 200, -1, FALSE, '`name_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name_en'] = &$this->name_en;

		// image
		$this->image = new cField('plans_house', 'plans_house', 'x_image', 'image', '`image`', '`image`', 200, -1, TRUE, '`image`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['image'] = &$this->image;

		// enable
		$this->enable = new cField('plans_house', 'plans_house', 'x_enable', 'enable', '`enable`', '`enable`', 202, -1, FALSE, '`enable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->enable->FldDataType = EW_DATATYPE_BOOLEAN;
		$this->fields['enable'] = &$this->enable;

		// created_date
		$this->created_date = new cField('plans_house', 'plans_house', 'x_created_date', 'created_date', '`created_date`', 'DATE_FORMAT(`created_date`, \'%Y/%m/%d %H:%i:%s\')', 135, 9, FALSE, '`created_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->created_date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['created_date'] = &$this->created_date;

		// modified_date
		$this->modified_date = new cField('plans_house', 'plans_house', 'x_modified_date', 'modified_date', '`modified_date`', 'DATE_FORMAT(`modified_date`, \'%Y/%m/%d %H:%i:%s\')', 135, 9, FALSE, '`modified_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->modified_date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['modified_date'] = &$this->modified_date;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "plans") {
			if ($this->plans_id->getSessionValue() <> "")
				$sMasterFilter .= "`id`=" . ew_QuotedValue($this->plans_id->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "plans") {
			if ($this->plans_id->getSessionValue() <> "")
				$sDetailFilter .= "`plans_id`=" . ew_QuotedValue($this->plans_id->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_plans() {
		return "`id`=@id@";
	}

	// Detail filter
	function SqlDetailFilter_plans() {
		return "`plans_id`=@plans_id@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`plans_house`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`id` DESC";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`plans_house`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "plans_houselist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "plans_houselist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("plans_houseview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("plans_houseview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "plans_houseadd.php?" . $this->UrlParm($parm);
		else
			return "plans_houseadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("plans_houseedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("plans_houseadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("plans_housedelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id"]; // id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->plans_id->setDbValue($rs->fields('plans_id'));
		$this->name_th->setDbValue($rs->fields('name_th'));
		$this->name_en->setDbValue($rs->fields('name_en'));
		$this->image->Upload->DbValue = $rs->fields('image');
		$this->enable->setDbValue($rs->fields('enable'));
		$this->created_date->setDbValue($rs->fields('created_date'));
		$this->modified_date->setDbValue($rs->fields('modified_date'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

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

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

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

		// enable
		$this->enable->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->enable->FldTagValue(1), $this->enable->FldTagCaption(1) <> "" ? $this->enable->FldTagCaption(1) : $this->enable->FldTagValue(1));
		$arwrk[] = array($this->enable->FldTagValue(2), $this->enable->FldTagCaption(2) <> "" ? $this->enable->FldTagCaption(2) : $this->enable->FldTagValue(2));
		$this->enable->EditValue = $arwrk;

		// created_date
		// modified_date
		// Call Row Rendered event

		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->plans_id->Exportable) $Doc->ExportCaption($this->plans_id);
					if ($this->name_th->Exportable) $Doc->ExportCaption($this->name_th);
					if ($this->name_en->Exportable) $Doc->ExportCaption($this->name_en);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->enable->Exportable) $Doc->ExportCaption($this->enable);
					if ($this->created_date->Exportable) $Doc->ExportCaption($this->created_date);
					if ($this->modified_date->Exportable) $Doc->ExportCaption($this->modified_date);
				} else {
					if ($this->plans_id->Exportable) $Doc->ExportCaption($this->plans_id);
					if ($this->name_th->Exportable) $Doc->ExportCaption($this->name_th);
					if ($this->name_en->Exportable) $Doc->ExportCaption($this->name_en);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->enable->Exportable) $Doc->ExportCaption($this->enable);
					if ($this->created_date->Exportable) $Doc->ExportCaption($this->created_date);
					if ($this->modified_date->Exportable) $Doc->ExportCaption($this->modified_date);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->plans_id->Exportable) $Doc->ExportField($this->plans_id);
						if ($this->name_th->Exportable) $Doc->ExportField($this->name_th);
						if ($this->name_en->Exportable) $Doc->ExportField($this->name_en);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->enable->Exportable) $Doc->ExportField($this->enable);
						if ($this->created_date->Exportable) $Doc->ExportField($this->created_date);
						if ($this->modified_date->Exportable) $Doc->ExportField($this->modified_date);
					} else {
						if ($this->plans_id->Exportable) $Doc->ExportField($this->plans_id);
						if ($this->name_th->Exportable) $Doc->ExportField($this->name_th);
						if ($this->name_en->Exportable) $Doc->ExportField($this->name_en);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->enable->Exportable) $Doc->ExportField($this->enable);
						if ($this->created_date->Exportable) $Doc->ExportField($this->created_date);
						if ($this->modified_date->Exportable) $Doc->ExportField($this->modified_date);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	 function Row_Rendering() {    
	 	Global ${$this->TableVar."_list"};    
		foreach($this->fields as $key => $value)
		{    if($value->FldType=="202")
				$this->fields[$key]->CellAttrs["alt"] = $value->FldName." ".$value->TblName." ".${$this->TableVar."_list"}->Recordset->fields["id"];
			if($value->FldViewTag=="IMAGE")
				$this->fields[$key]->CellAttrs["class"] = "light_box";
		}
	}

	function Row_Rendered() {    
	   	Global ${$this->TableVar."_list"};
		if(getType(${$this->TableVar."_list"})!="NULL")
		{    foreach(${$this->TableVar."_list"}->ListOptions->Items as $key => $value)
			{    ${$this->TableVar."_list"}->ListOptions->Items[$key]->CssStyle = "width:1%;white-space:nowrap;";
			}
		}
	}    

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>