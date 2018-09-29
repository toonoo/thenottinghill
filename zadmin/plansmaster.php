<?php

// name_th
// name_en
// image
// enable

?>
<?php if ($plans->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $plans->TableCaption() ?></h4> -->
<div id="t_plans" class="<?php if (ew_IsResponsiveLayout()) echo "table-responsive "; ?>ewGrid">
<table id="tbl_plansmaster" class="table ewTable">
	<thead>
		<tr>
<?php if ($plans->name_th->Visible) { // name_th ?>
			<th class="ewTableHeader"><?php echo $plans->name_th->FldCaption() ?></th>
<?php } ?>
<?php if ($plans->name_en->Visible) { // name_en ?>
			<th class="ewTableHeader"><?php echo $plans->name_en->FldCaption() ?></th>
<?php } ?>
<?php if ($plans->image->Visible) { // image ?>
			<th class="ewTableHeader"><?php echo $plans->image->FldCaption() ?></th>
<?php } ?>
<?php if ($plans->enable->Visible) { // enable ?>
			<th class="ewTableHeader"><?php echo $plans->enable->FldCaption() ?></th>
<?php } ?>
		</tr>
	</thead>
	<tbody>
		<tr>
<?php if ($plans->name_th->Visible) { // name_th ?>
			<td<?php echo $plans->name_th->CellAttributes() ?>>
<span id="el_plans_name_th">
<span<?php echo $plans->name_th->ViewAttributes() ?>>
<?php echo $plans->name_th->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($plans->name_en->Visible) { // name_en ?>
			<td<?php echo $plans->name_en->CellAttributes() ?>>
<span id="el_plans_name_en">
<span<?php echo $plans->name_en->ViewAttributes() ?>>
<?php echo $plans->name_en->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($plans->image->Visible) { // image ?>
			<td<?php echo $plans->image->CellAttributes() ?>>
<span id="el_plans_image">
<span>
<?php echo ew_GetFileViewTag($plans->image, $plans->image->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($plans->enable->Visible) { // enable ?>
			<td<?php echo $plans->enable->CellAttributes() ?>>
<span id="el_plans_enable">
<span<?php echo $plans->enable->ViewAttributes() ?>>
<?php if (ew_ConvertToBool($plans->enable->CurrentValue)) { ?>
<input type="checkbox" value="<?php echo $plans->enable->ListViewValue() ?>" checked="checked" disabled="disabled">
<?php } else { ?>
<input type="checkbox" value="<?php echo $plans->enable->ListViewValue() ?>" disabled="disabled">
<?php } ?>
</span>
</span>
</td>
<?php } ?>
		</tr>
	</tbody>
</table>
</div>
<?php } ?>
