<?php

// Create page object
if (!isset($plans_house_grid)) $plans_house_grid = new cplans_house_grid();

// Page init
$plans_house_grid->Page_Init();

// Page main
$plans_house_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$plans_house_grid->Page_Render();
?>
<?php if ($plans_house->Export == "") { ?>
<script type="text/javascript">

// Page object
var plans_house_grid = new ew_Page("plans_house_grid");
plans_house_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = plans_house_grid.PageID; // For backward compatibility

// Form object
var fplans_housegrid = new ew_Form("fplans_housegrid");
fplans_housegrid.FormKeyCountName = '<?php echo $plans_house_grid->FormKeyCountName ?>';

// Validate form
fplans_housegrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fplans_housegrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "name_th", false)) return false;
	if (ew_ValueChanged(fobj, infix, "name_en", false)) return false;
	if (ew_ValueChanged(fobj, infix, "image", false)) return false;
	if (ew_ValueChanged(fobj, infix, "enable[]", true)) return false;
	return true;
}

// Form_CustomValidate event
fplans_housegrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fplans_housegrid.ValidateRequired = true;
<?php } else { ?>
fplans_housegrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($plans_house->CurrentAction == "gridadd") {
	if ($plans_house->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$plans_house_grid->TotalRecs = $plans_house->SelectRecordCount();
			$plans_house_grid->Recordset = $plans_house_grid->LoadRecordset($plans_house_grid->StartRec-1, $plans_house_grid->DisplayRecs);
		} else {
			if ($plans_house_grid->Recordset = $plans_house_grid->LoadRecordset())
				$plans_house_grid->TotalRecs = $plans_house_grid->Recordset->RecordCount();
		}
		$plans_house_grid->StartRec = 1;
		$plans_house_grid->DisplayRecs = $plans_house_grid->TotalRecs;
	} else {
		$plans_house->CurrentFilter = "0=1";
		$plans_house_grid->StartRec = 1;
		$plans_house_grid->DisplayRecs = $plans_house->GridAddRowCount;
	}
	$plans_house_grid->TotalRecs = $plans_house_grid->DisplayRecs;
	$plans_house_grid->StopRec = $plans_house_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($plans_house_grid->TotalRecs <= 0)
			$plans_house_grid->TotalRecs = $plans_house->SelectRecordCount();
	} else {
		if (!$plans_house_grid->Recordset && ($plans_house_grid->Recordset = $plans_house_grid->LoadRecordset()))
			$plans_house_grid->TotalRecs = $plans_house_grid->Recordset->RecordCount();
	}
	$plans_house_grid->StartRec = 1;
	$plans_house_grid->DisplayRecs = $plans_house_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$plans_house_grid->Recordset = $plans_house_grid->LoadRecordset($plans_house_grid->StartRec-1, $plans_house_grid->DisplayRecs);

	// Set no record found message
	if ($plans_house->CurrentAction == "" && $plans_house_grid->TotalRecs == 0) {
		if ($plans_house_grid->SearchWhere == "0=101")
			$plans_house_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$plans_house_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$plans_house_grid->RenderOtherOptions();
?>
<?php $plans_house_grid->ShowPageHeader(); ?>
<?php
$plans_house_grid->ShowMessage();
?>
<?php if ($plans_house_grid->TotalRecs > 0 || $plans_house->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fplans_housegrid" class="ewForm form-inline">
<?php if ($plans_house_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($plans_house_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_plans_house" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_plans_housegrid" class="table ewTable">
<?php echo $plans_house->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$plans_house->RowType = EW_ROWTYPE_HEADER;

// Render list options
$plans_house_grid->RenderListOptions();

// Render list options (header, left)
$plans_house_grid->ListOptions->Render("header", "left");
?>
<?php if ($plans_house->name_th->Visible) { // name_th ?>
	<?php if ($plans_house->SortUrl($plans_house->name_th) == "") { ?>
		<th data-name="name_th"><div id="elh_plans_house_name_th" class="plans_house_name_th"><div class="ewTableHeaderCaption"><?php echo $plans_house->name_th->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name_th"><div><div id="elh_plans_house_name_th" class="plans_house_name_th">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $plans_house->name_th->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($plans_house->name_th->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($plans_house->name_th->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($plans_house->name_en->Visible) { // name_en ?>
	<?php if ($plans_house->SortUrl($plans_house->name_en) == "") { ?>
		<th data-name="name_en"><div id="elh_plans_house_name_en" class="plans_house_name_en"><div class="ewTableHeaderCaption"><?php echo $plans_house->name_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name_en"><div><div id="elh_plans_house_name_en" class="plans_house_name_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $plans_house->name_en->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($plans_house->name_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($plans_house->name_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($plans_house->image->Visible) { // image ?>
	<?php if ($plans_house->SortUrl($plans_house->image) == "") { ?>
		<th data-name="image"><div id="elh_plans_house_image" class="plans_house_image"><div class="ewTableHeaderCaption"><?php echo $plans_house->image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image"><div><div id="elh_plans_house_image" class="plans_house_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $plans_house->image->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($plans_house->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($plans_house->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($plans_house->enable->Visible) { // enable ?>
	<?php if ($plans_house->SortUrl($plans_house->enable) == "") { ?>
		<th data-name="enable"><div id="elh_plans_house_enable" class="plans_house_enable"><div class="ewTableHeaderCaption" style="width: 1%;text-align: center;;"><?php echo $plans_house->enable->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="enable"><div><div id="elh_plans_house_enable" class="plans_house_enable">
			<div class="ewTableHeaderBtn" style="width: 1%;text-align: center;;"><span class="ewTableHeaderCaption"><?php echo $plans_house->enable->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($plans_house->enable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($plans_house->enable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$plans_house_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$plans_house_grid->StartRec = 1;
$plans_house_grid->StopRec = $plans_house_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($plans_house_grid->FormKeyCountName) && ($plans_house->CurrentAction == "gridadd" || $plans_house->CurrentAction == "gridedit" || $plans_house->CurrentAction == "F")) {
		$plans_house_grid->KeyCount = $objForm->GetValue($plans_house_grid->FormKeyCountName);
		$plans_house_grid->StopRec = $plans_house_grid->StartRec + $plans_house_grid->KeyCount - 1;
	}
}
$plans_house_grid->RecCnt = $plans_house_grid->StartRec - 1;
if ($plans_house_grid->Recordset && !$plans_house_grid->Recordset->EOF) {
	$plans_house_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $plans_house_grid->StartRec > 1)
		$plans_house_grid->Recordset->Move($plans_house_grid->StartRec - 1);
} elseif (!$plans_house->AllowAddDeleteRow && $plans_house_grid->StopRec == 0) {
	$plans_house_grid->StopRec = $plans_house->GridAddRowCount;
}

// Initialize aggregate
$plans_house->RowType = EW_ROWTYPE_AGGREGATEINIT;
$plans_house->ResetAttrs();
$plans_house_grid->RenderRow();
if ($plans_house->CurrentAction == "gridadd")
	$plans_house_grid->RowIndex = 0;
if ($plans_house->CurrentAction == "gridedit")
	$plans_house_grid->RowIndex = 0;
while ($plans_house_grid->RecCnt < $plans_house_grid->StopRec) {
	$plans_house_grid->RecCnt++;
	if (intval($plans_house_grid->RecCnt) >= intval($plans_house_grid->StartRec)) {
		$plans_house_grid->RowCnt++;
		if ($plans_house->CurrentAction == "gridadd" || $plans_house->CurrentAction == "gridedit" || $plans_house->CurrentAction == "F") {
			$plans_house_grid->RowIndex++;
			$objForm->Index = $plans_house_grid->RowIndex;
			if ($objForm->HasValue($plans_house_grid->FormActionName))
				$plans_house_grid->RowAction = strval($objForm->GetValue($plans_house_grid->FormActionName));
			elseif ($plans_house->CurrentAction == "gridadd")
				$plans_house_grid->RowAction = "insert";
			else
				$plans_house_grid->RowAction = "";
		}

		// Set up key count
		$plans_house_grid->KeyCount = $plans_house_grid->RowIndex;

		// Init row class and style
		$plans_house->ResetAttrs();
		$plans_house->CssClass = "";
		if ($plans_house->CurrentAction == "gridadd") {
			if ($plans_house->CurrentMode == "copy") {
				$plans_house_grid->LoadRowValues($plans_house_grid->Recordset); // Load row values
				$plans_house_grid->SetRecordKey($plans_house_grid->RowOldKey, $plans_house_grid->Recordset); // Set old record key
			} else {
				$plans_house_grid->LoadDefaultValues(); // Load default values
				$plans_house_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$plans_house_grid->LoadRowValues($plans_house_grid->Recordset); // Load row values
		}
		$plans_house->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($plans_house->CurrentAction == "gridadd") // Grid add
			$plans_house->RowType = EW_ROWTYPE_ADD; // Render add
		if ($plans_house->CurrentAction == "gridadd" && $plans_house->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$plans_house_grid->RestoreCurrentRowFormValues($plans_house_grid->RowIndex); // Restore form values
		if ($plans_house->CurrentAction == "gridedit") { // Grid edit
			if ($plans_house->EventCancelled) {
				$plans_house_grid->RestoreCurrentRowFormValues($plans_house_grid->RowIndex); // Restore form values
			}
			if ($plans_house_grid->RowAction == "insert")
				$plans_house->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$plans_house->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($plans_house->CurrentAction == "gridedit" && ($plans_house->RowType == EW_ROWTYPE_EDIT || $plans_house->RowType == EW_ROWTYPE_ADD) && $plans_house->EventCancelled) // Update failed
			$plans_house_grid->RestoreCurrentRowFormValues($plans_house_grid->RowIndex); // Restore form values
		if ($plans_house->RowType == EW_ROWTYPE_EDIT) // Edit row
			$plans_house_grid->EditRowCnt++;
		if ($plans_house->CurrentAction == "F") // Confirm row
			$plans_house_grid->RestoreCurrentRowFormValues($plans_house_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$plans_house->RowAttrs = array_merge($plans_house->RowAttrs, array('data-rowindex'=>$plans_house_grid->RowCnt, 'id'=>'r' . $plans_house_grid->RowCnt . '_plans_house', 'data-rowtype'=>$plans_house->RowType));

		// Render row
		$plans_house_grid->RenderRow();

		// Render list options
		$plans_house_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($plans_house_grid->RowAction <> "delete" && $plans_house_grid->RowAction <> "insertdelete" && !($plans_house_grid->RowAction == "insert" && $plans_house->CurrentAction == "F" && $plans_house_grid->EmptyRow())) {
?>
	<tr<?php echo $plans_house->RowAttributes() ?>>
<?php

// Render list options (body, left)
$plans_house_grid->ListOptions->Render("body", "left", $plans_house_grid->RowCnt);
?>
	<?php if ($plans_house->name_th->Visible) { // name_th ?>
		<td data-name="name_th"<?php echo $plans_house->name_th->CellAttributes() ?>>
<?php if ($plans_house->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $plans_house_grid->RowCnt ?>_plans_house_name_th" class="form-group plans_house_name_th">
<input type="text" data-field="x_name_th" name="x<?php echo $plans_house_grid->RowIndex ?>_name_th" id="x<?php echo $plans_house_grid->RowIndex ?>_name_th" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_th->PlaceHolder) ?>" value="<?php echo $plans_house->name_th->EditValue ?>"<?php echo $plans_house->name_th->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name_th" name="o<?php echo $plans_house_grid->RowIndex ?>_name_th" id="o<?php echo $plans_house_grid->RowIndex ?>_name_th" value="<?php echo ew_HtmlEncode($plans_house->name_th->OldValue) ?>">
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $plans_house_grid->RowCnt ?>_plans_house_name_th" class="form-group plans_house_name_th">
<input type="text" data-field="x_name_th" name="x<?php echo $plans_house_grid->RowIndex ?>_name_th" id="x<?php echo $plans_house_grid->RowIndex ?>_name_th" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_th->PlaceHolder) ?>" value="<?php echo $plans_house->name_th->EditValue ?>"<?php echo $plans_house->name_th->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $plans_house->name_th->ViewAttributes() ?>>
<?php echo $plans_house->name_th->ListViewValue() ?></span>
<input type="hidden" data-field="x_name_th" name="x<?php echo $plans_house_grid->RowIndex ?>_name_th" id="x<?php echo $plans_house_grid->RowIndex ?>_name_th" value="<?php echo ew_HtmlEncode($plans_house->name_th->FormValue) ?>">
<input type="hidden" data-field="x_name_th" name="o<?php echo $plans_house_grid->RowIndex ?>_name_th" id="o<?php echo $plans_house_grid->RowIndex ?>_name_th" value="<?php echo ew_HtmlEncode($plans_house->name_th->OldValue) ?>">
<?php } ?>
<a id="<?php echo $plans_house_grid->PageObjName . "_row_" . $plans_house_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="x<?php echo $plans_house_grid->RowIndex ?>_id" id="x<?php echo $plans_house_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($plans_house->id->CurrentValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $plans_house_grid->RowIndex ?>_id" id="o<?php echo $plans_house_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($plans_house->id->OldValue) ?>">
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT || $plans_house->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id" name="x<?php echo $plans_house_grid->RowIndex ?>_id" id="x<?php echo $plans_house_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($plans_house->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($plans_house->name_en->Visible) { // name_en ?>
		<td data-name="name_en"<?php echo $plans_house->name_en->CellAttributes() ?>>
<?php if ($plans_house->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $plans_house_grid->RowCnt ?>_plans_house_name_en" class="form-group plans_house_name_en">
<input type="text" data-field="x_name_en" name="x<?php echo $plans_house_grid->RowIndex ?>_name_en" id="x<?php echo $plans_house_grid->RowIndex ?>_name_en" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_en->PlaceHolder) ?>" value="<?php echo $plans_house->name_en->EditValue ?>"<?php echo $plans_house->name_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name_en" name="o<?php echo $plans_house_grid->RowIndex ?>_name_en" id="o<?php echo $plans_house_grid->RowIndex ?>_name_en" value="<?php echo ew_HtmlEncode($plans_house->name_en->OldValue) ?>">
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $plans_house_grid->RowCnt ?>_plans_house_name_en" class="form-group plans_house_name_en">
<input type="text" data-field="x_name_en" name="x<?php echo $plans_house_grid->RowIndex ?>_name_en" id="x<?php echo $plans_house_grid->RowIndex ?>_name_en" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_en->PlaceHolder) ?>" value="<?php echo $plans_house->name_en->EditValue ?>"<?php echo $plans_house->name_en->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $plans_house->name_en->ViewAttributes() ?>>
<?php echo $plans_house->name_en->ListViewValue() ?></span>
<input type="hidden" data-field="x_name_en" name="x<?php echo $plans_house_grid->RowIndex ?>_name_en" id="x<?php echo $plans_house_grid->RowIndex ?>_name_en" value="<?php echo ew_HtmlEncode($plans_house->name_en->FormValue) ?>">
<input type="hidden" data-field="x_name_en" name="o<?php echo $plans_house_grid->RowIndex ?>_name_en" id="o<?php echo $plans_house_grid->RowIndex ?>_name_en" value="<?php echo ew_HtmlEncode($plans_house->name_en->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($plans_house->image->Visible) { // image ?>
		<td data-name="image"<?php echo $plans_house->image->CellAttributes() ?>>
<?php if ($plans_house_grid->RowAction == "insert") { // Add record ?>
<span id="el<?php echo $plans_house_grid->RowCnt ?>_plans_house_image" class="form-group plans_house_image">
<div id="fd_x<?php echo $plans_house_grid->RowIndex ?>_image">
<span title="<?php echo $plans_house->image->FldTitle() ? $plans_house->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($plans_house->image->ReadOnly || $plans_house->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $plans_house_grid->RowIndex ?>_image" id="x<?php echo $plans_house_grid->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fn_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fa_x<?php echo $plans_house_grid->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fs_x<?php echo $plans_house_grid->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fx_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fm_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $plans_house_grid->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_image" name="o<?php echo $plans_house_grid->RowIndex ?>_image" id="o<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($plans_house->image->OldValue) ?>">
<?php } elseif ($plans_house->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span>
<?php echo ew_GetFileViewTag($plans_house->image, $plans_house->image->ListViewValue()) ?>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $plans_house_grid->RowCnt ?>_plans_house_image" class="form-group plans_house_image">
<div id="fd_x<?php echo $plans_house_grid->RowIndex ?>_image">
<span title="<?php echo $plans_house->image->FldTitle() ? $plans_house->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($plans_house->image->ReadOnly || $plans_house->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $plans_house_grid->RowIndex ?>_image" id="x<?php echo $plans_house_grid->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fn_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $plans_house_grid->RowIndex ?>_image"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fa_x<?php echo $plans_house_grid->RowIndex ?>_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fa_x<?php echo $plans_house_grid->RowIndex ?>_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fs_x<?php echo $plans_house_grid->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fx_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fm_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $plans_house_grid->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($plans_house->enable->Visible) { // enable ?>
		<td data-name="enable"<?php echo $plans_house->enable->CellAttributes() ?>>
<?php if ($plans_house->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $plans_house_grid->RowCnt ?>_plans_house_enable" class="form-group plans_house_enable">
<?php
$selwrk = (ew_ConvertToBool($plans_house->enable->CurrentValue)) ? " checked=\"checked\"" : "";
?>
<input type="checkbox" data-field="x_enable" name="x<?php echo $plans_house_grid->RowIndex ?>_enable[]" id="x<?php echo $plans_house_grid->RowIndex ?>_enable[]" value="1"<?php echo $selwrk ?><?php echo $plans_house->enable->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_enable" name="o<?php echo $plans_house_grid->RowIndex ?>_enable[]" id="o<?php echo $plans_house_grid->RowIndex ?>_enable[]" value="<?php echo ew_HtmlEncode($plans_house->enable->OldValue) ?>">
<?php } ?>
<?php if ($plans_house->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $plans_house_grid->RowCnt ?>_plans_house_enable" class="form-group plans_house_enable">
<?php
$selwrk = (ew_ConvertToBool($plans_house->enable->CurrentValue)) ? " checked=\"checked\"" : "";
?>
<input type="checkbox" data-field="x_enable" name="x<?php echo $plans_house_grid->RowIndex ?>_enable[]" id="x<?php echo $plans_house_grid->RowIndex ?>_enable[]" value="1"<?php echo $selwrk ?><?php echo $plans_house->enable->EditAttributes() ?>>
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
<input type="hidden" data-field="x_enable" name="x<?php echo $plans_house_grid->RowIndex ?>_enable" id="x<?php echo $plans_house_grid->RowIndex ?>_enable" value="<?php echo ew_HtmlEncode($plans_house->enable->FormValue) ?>">
<input type="hidden" data-field="x_enable" name="o<?php echo $plans_house_grid->RowIndex ?>_enable[]" id="o<?php echo $plans_house_grid->RowIndex ?>_enable[]" value="<?php echo ew_HtmlEncode($plans_house->enable->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$plans_house_grid->ListOptions->Render("body", "right", $plans_house_grid->RowCnt);
?>
	</tr>
<?php if ($plans_house->RowType == EW_ROWTYPE_ADD || $plans_house->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fplans_housegrid.UpdateOpts(<?php echo $plans_house_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($plans_house->CurrentAction <> "gridadd" || $plans_house->CurrentMode == "copy")
		if (!$plans_house_grid->Recordset->EOF) $plans_house_grid->Recordset->MoveNext();
}
?>
<?php
	if ($plans_house->CurrentMode == "add" || $plans_house->CurrentMode == "copy" || $plans_house->CurrentMode == "edit") {
		$plans_house_grid->RowIndex = '$rowindex$';
		$plans_house_grid->LoadDefaultValues();

		// Set row properties
		$plans_house->ResetAttrs();
		$plans_house->RowAttrs = array_merge($plans_house->RowAttrs, array('data-rowindex'=>$plans_house_grid->RowIndex, 'id'=>'r0_plans_house', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($plans_house->RowAttrs["class"], "ewTemplate");
		$plans_house->RowType = EW_ROWTYPE_ADD;

		// Render row
		$plans_house_grid->RenderRow();

		// Render list options
		$plans_house_grid->RenderListOptions();
		$plans_house_grid->StartRowCnt = 0;
?>
	<tr<?php echo $plans_house->RowAttributes() ?>>
<?php

// Render list options (body, left)
$plans_house_grid->ListOptions->Render("body", "left", $plans_house_grid->RowIndex);
?>
	<?php if ($plans_house->name_th->Visible) { // name_th ?>
		<td data-name="name_th">
<?php if ($plans_house->CurrentAction <> "F") { ?>
<span id="el$rowindex$_plans_house_name_th" class="form-group plans_house_name_th">
<input type="text" data-field="x_name_th" name="x<?php echo $plans_house_grid->RowIndex ?>_name_th" id="x<?php echo $plans_house_grid->RowIndex ?>_name_th" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_th->PlaceHolder) ?>" value="<?php echo $plans_house->name_th->EditValue ?>"<?php echo $plans_house->name_th->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_plans_house_name_th" class="form-group plans_house_name_th">
<span<?php echo $plans_house->name_th->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $plans_house->name_th->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_name_th" name="x<?php echo $plans_house_grid->RowIndex ?>_name_th" id="x<?php echo $plans_house_grid->RowIndex ?>_name_th" value="<?php echo ew_HtmlEncode($plans_house->name_th->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_name_th" name="o<?php echo $plans_house_grid->RowIndex ?>_name_th" id="o<?php echo $plans_house_grid->RowIndex ?>_name_th" value="<?php echo ew_HtmlEncode($plans_house->name_th->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($plans_house->name_en->Visible) { // name_en ?>
		<td data-name="name_en">
<?php if ($plans_house->CurrentAction <> "F") { ?>
<span id="el$rowindex$_plans_house_name_en" class="form-group plans_house_name_en">
<input type="text" data-field="x_name_en" name="x<?php echo $plans_house_grid->RowIndex ?>_name_en" id="x<?php echo $plans_house_grid->RowIndex ?>_name_en" size="50" maxlength="255" placeholder="<?php echo ew_HtmlEncode($plans_house->name_en->PlaceHolder) ?>" value="<?php echo $plans_house->name_en->EditValue ?>"<?php echo $plans_house->name_en->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_plans_house_name_en" class="form-group plans_house_name_en">
<span<?php echo $plans_house->name_en->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $plans_house->name_en->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_name_en" name="x<?php echo $plans_house_grid->RowIndex ?>_name_en" id="x<?php echo $plans_house_grid->RowIndex ?>_name_en" value="<?php echo ew_HtmlEncode($plans_house->name_en->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_name_en" name="o<?php echo $plans_house_grid->RowIndex ?>_name_en" id="o<?php echo $plans_house_grid->RowIndex ?>_name_en" value="<?php echo ew_HtmlEncode($plans_house->name_en->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($plans_house->image->Visible) { // image ?>
		<td data-name="image">
<span id="el$rowindex$_plans_house_image" class="form-group plans_house_image">
<div id="fd_x<?php echo $plans_house_grid->RowIndex ?>_image">
<span title="<?php echo $plans_house->image->FldTitle() ? $plans_house->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($plans_house->image->ReadOnly || $plans_house->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $plans_house_grid->RowIndex ?>_image" id="x<?php echo $plans_house_grid->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fn_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fa_x<?php echo $plans_house_grid->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fs_x<?php echo $plans_house_grid->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fx_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $plans_house_grid->RowIndex ?>_image" id= "fm_x<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo $plans_house->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $plans_house_grid->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_image" name="o<?php echo $plans_house_grid->RowIndex ?>_image" id="o<?php echo $plans_house_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($plans_house->image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($plans_house->enable->Visible) { // enable ?>
		<td data-name="enable">
<?php if ($plans_house->CurrentAction <> "F") { ?>
<span id="el$rowindex$_plans_house_enable" class="form-group plans_house_enable">
<?php
$selwrk = (ew_ConvertToBool($plans_house->enable->CurrentValue)) ? " checked=\"checked\"" : "";
?>
<input type="checkbox" data-field="x_enable" name="x<?php echo $plans_house_grid->RowIndex ?>_enable[]" id="x<?php echo $plans_house_grid->RowIndex ?>_enable[]" value="1"<?php echo $selwrk ?><?php echo $plans_house->enable->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_plans_house_enable" class="form-group plans_house_enable">
<span<?php echo $plans_house->enable->ViewAttributes() ?>>
<?php if (ew_ConvertToBool($plans_house->enable->CurrentValue)) { ?>
<input type="checkbox" value="<?php echo $plans_house->enable->ViewValue ?>" checked="checked" disabled="disabled">
<?php } else { ?>
<input type="checkbox" value="<?php echo $plans_house->enable->ViewValue ?>" disabled="disabled">
<?php } ?>
</span>
</span>
<input type="hidden" data-field="x_enable" name="x<?php echo $plans_house_grid->RowIndex ?>_enable" id="x<?php echo $plans_house_grid->RowIndex ?>_enable" value="<?php echo ew_HtmlEncode($plans_house->enable->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_enable" name="o<?php echo $plans_house_grid->RowIndex ?>_enable[]" id="o<?php echo $plans_house_grid->RowIndex ?>_enable[]" value="<?php echo ew_HtmlEncode($plans_house->enable->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$plans_house_grid->ListOptions->Render("body", "right", $plans_house_grid->RowCnt);
?>
<script type="text/javascript">
fplans_housegrid.UpdateOpts(<?php echo $plans_house_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($plans_house->CurrentMode == "add" || $plans_house->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $plans_house_grid->FormKeyCountName ?>" id="<?php echo $plans_house_grid->FormKeyCountName ?>" value="<?php echo $plans_house_grid->KeyCount ?>">
<?php echo $plans_house_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($plans_house->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $plans_house_grid->FormKeyCountName ?>" id="<?php echo $plans_house_grid->FormKeyCountName ?>" value="<?php echo $plans_house_grid->KeyCount ?>">
<?php echo $plans_house_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($plans_house->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fplans_housegrid">
</div>
<?php

// Close recordset
if ($plans_house_grid->Recordset)
	$plans_house_grid->Recordset->Close();
?>
<?php if ($plans_house_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($plans_house_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($plans_house_grid->TotalRecs == 0 && $plans_house->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($plans_house_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($plans_house->Export == "") { ?>
<script type="text/javascript">
fplans_housegrid.Init();
</script>
<?php } ?>
<?php
$plans_house_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$plans_house_grid->Page_Terminate();
?>
