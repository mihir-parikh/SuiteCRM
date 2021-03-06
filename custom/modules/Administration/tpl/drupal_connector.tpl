<script src='cache/include/javascript/sugar_grp_yui_widgets.js'></script>
<script type="text/javascript">
	// Some default/necessary JS
	var ERR_NO_SINGLE_QUOTE = '{$APP.ERR_NO_SINGLE_QUOTE}';
	var cannotEq = "{$APP.ERR_DECIMAL_SEP_EQ_THOUSANDS_SEP}";

	{literal}
	/**
	 * Validate form inputs
	 *
	 * @return {boolean} Return true or false based on validation passed or failed
	 */
	function validateDCInputs(){
		// None of the fields should be empty
		if ($.trim($('#drupal_url').val()).length === 0 || $.trim($('#drupal_username').val()).length === 0 || 
				$.trim($('#drupal_password').val()).length === 0){
			YAHOO.SUGAR.MessageBox.show({msg: "Mandatory information is missing. Please enter all form fields"});
			return false;
		}
		else {
			return true;
		}
	}
	
	function verify_data(formName) {
		var f = document.getElementById(formName);
		for (i = 0; i < f.elements.length; i++) {
			if (f.elements[i].value == "'") {
				alert(ERR_NO_SINGLE_QUOTE + " " + f.elements[i].name);
				return false;
			}
		}

		// Adding custom validation in
		var validationResult = validateDCInputs();

		if(validationResult === false) {
			// Do not go ahead
			return false;
		}
		
		return true;
	}

	/**
	 * Javascript handler handling Test connection
	 */
	function testDrupalConnection(){
		// Get the form values
		var drupal_url = $("#drupal_url").val();
		var drupal_username = $("#drupal_username").val();
		var drupal_password = $("#drupal_password").val();

		var validationResult = validateDCInputs();

		if(validationResult === false) {
			return false;
		}		

		// Send an AJAX request to the controller action
		$.ajax({
			method: "POST", 
			url: "index.php?module=Administration&action=test_drupal_connection",
			data: {drupal_url: drupal_url, drupal_username: drupal_username, drupal_password: drupal_password},
			error: function(xhr){
				YAHOO.SUGAR.MessageBox.show({msg: "An error occured: " + xhr.status + " " + xhr.statusText});
			},
			success: function(data){
				if(data == 1){
					YAHOO.SUGAR.MessageBox.show({msg: "Yay! Connection successful."});
				}
				else if(data == -1){
					YAHOO.SUGAR.MessageBox.show({msg: "Mandatory information is missing. Please enter all form fields"});
				}
				else{
					YAHOO.SUGAR.MessageBox.show({msg: "Connection failed. Please check the entered information & try again."});
				}
			}
		});
	}
	{/literal}
</script>

<BR>

<form id="DrupalConnectorSettings" name="DrupalConnectorSettings" enctype='multipart/form-data' method="POST" 
action="index.php?module=Administration&action=drupal_connector&process=true">
	{* A block displaying error message (if any) *}
	<span class='error'>{$error.main}</span>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
		<tr>
			<td>
				<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary"
				type="submit" name="save" onclick="return verify_data('DrupalConnectorSettings');" value="{$APP.LBL_SAVE_BUTTON_LABEL}" /> 
				<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}" onclick="document.location.href='index.php?module=Administration&action=index'"
				class="button" type="button" name="cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" />
				{* On click call the Javascript function testDrupalConnection() *}
				<input title="Test connection" class="button" type="button" name="test-connection" 
				onclick="testDrupalConnection()" value="Test connection" />
			</td>
		</tr>
	</table>
	<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
		<tr>
			<th align="left" scope="row" colspan="2"><h4>Drupal Connector configurations</h4></th>
		</tr>
		<tr>
			<td nowrap width="10%" scope="row">URL:*</td>
			<td width="70%"><input type='text' id='drupal_url' name='drupal_url' size="60" value='{$DRUPAL_URL}'></td>
		</tr>
		<tr>
			<td nowrap width="10%" scope="row">Username:*</td>
			<td width="70%"><input type='text' id='drupal_username' name='drupal_username' size="60" value='{$DRUPAL_USERNAME}'></td>
		</tr>
		<tr>
			<td nowrap width="10%" scope="row">Password:*</td>
			<td width="70%"><input type='password' id='drupal_password' name='drupal_password' size="60" value='{$DRUPAL_PASSWORD}'></td>
		</tr>
	</table>
	<div style="padding-top: 2px;">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary"
		type="submit" name="save" onclick="return verify_data('DrupalConnectorSettings');" value="{$APP.LBL_SAVE_BUTTON_LABEL}" />
		<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}" onclick="document.location.href='index.php?module=Administration&action=index'"
		class="button" type="button" name="cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" />
		{* On Click call the Javascript function testDrupalConnection() *}
		<input title="Test connection" class="button" type="button" name="test-connection" 
		onclick="testDrupalConnection()" value="Test connection" />
	</div>
	{$JAVASCRIPT}
</form>