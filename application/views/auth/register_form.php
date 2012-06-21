<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<?php echo form_open($this->uri->uri_string(), array('class'=>'estandarForm', 'id'=>'registerForm')); ?>
<script type="text/javascript">
    $(function(){
        $('.estandarForm').submit(function(){
            if ($('#selectRol').val() == ''){
                alert("Debe seleccionar un rol");
                return false;
            }
            return true;
        });
    });
</script>
<table>
	<?php if ($use_username) { ?>
	<tr>
		<td><?php echo form_label('Usuario', $username['id']); ?></td>
		<td><?php echo form_input($username); ?></td>
		<td style="color: red;"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td><?php echo form_label('Dirección Email', $email['id']); ?></td>
		<td><?php echo form_input($email); ?></td>
		<td style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
	</tr>
        <tr>
		<td><?php echo form_label('Nombre Completo'); ?></td>
                <td><input type="text" name="nombre"></td>
                </td></td>
	</tr>
        <tr>
		<td><?php echo form_label('Rol'); ?></td>
                <td>
                    <select id="selectRol" name="rol">
                        <option value=""></option>
                        <?php foreach (Trabajador::$roles as $nombre => $nivel) {?>
                            <option value="<?php echo $nombre; ?>"><?php echo $nombre; ?></option>
                        <?php } ?>
                    </select>
                </td>
                </td></td>
	</tr>
        
	<tr>
		<td><?php echo form_label('Contraseña', $password['id']); ?></td>
		<td><?php echo form_password($password); ?></td>
		<td style="color: red;"><?php echo form_error($password['name']); ?></td>
	</tr>
	<tr>
		<td><?php echo form_label('Confirmar contraseña', $confirm_password['id']); ?></td>
		<td><?php echo form_password($confirm_password); ?></td>
		<td style="color: red;"><?php echo form_error($confirm_password['name']); ?></td>
	</tr>
        <tr>
		<td></td>
		<td><?php echo form_submit('register', 'Registrar'); ?></td>
	</tr>
</table>