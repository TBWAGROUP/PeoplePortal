<?php if (isset($_GET['label']))
{
	if ( ($_GET['label'] == "TBWA") ||  ($_GET['label'] == "PRIDE") || ($_GET['label'] == "MAGMA") || ($_GET['label'] == "TWOMENANDAHORSEHEAD") || ($_GET['label'] == "SMARTS") || ($_GET['label'] == "DISRUPTIONWORKS") )// TBWA
	{
	?>
		<label>
			<input type="text" name="payroll" value="TBWA" size=18 />
		</label> 
	<?php
	}	
	else if ( ($_GET['label'] == "TEQUILA") ||  ($_GET['label'] == "SPICE") || ($_GET['label'] == "BEING")) // TEQUILA
	{
	?>
		<label>
			<input type="text" name="payroll" value="TEQUILA" size=18 />
		</label> 
	<?php
	}	
	else if ( ($_GET['label'] == "E-GRAPHICS PRINT") ||  ($_GET['label'] == "E-GRAPHICS DIGITAL") || ($_GET['label'] == "SAKE") ) // E-GRAPHICS
	{
	?>
		<label>
			<input type="text" name="payroll" value="E-GRAPHICS" size=18 />
		</label> 
	<?php
	}	
	else if ( ($_GET['label'] == "HEADLINE") ||  ($_GET['label'] == "DIGITAL CRAFTSMEN") ||  ($_GET['label'] == "TBWA ANTWERP")) // M & E
	{
	?>
		<label>
			<input type="text" name="payroll" value="MARKETING & ENTERTAINMENT" size=18 />
		</label> 

	<?php
	}
	else {
	?>
		<label>
			<input type="text" name="payroll" value="<?php echo $_GET['label']; ?>" size=18 />
		</label> 
	<?php }
}
else
{
?>
		<label>
			<input type="text" name="payroll" value="" size=18 />
		</label> 
<?php 
} ?>