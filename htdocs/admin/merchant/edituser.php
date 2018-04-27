<?php
	include('connect.php');
	$id=$_GET['id'];
	$result = $db->prepare("SELECT * FROM tb_useradm WHERE user_idxx= :userid");
	$result->bindParam(':userid', $id);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
	
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Edit User</h4>
	</div>
	<div class="modal-body">
		<form action="saveedituser.php" method="post" class = "form-group">
			<div id="ac">		
				<input type="hidden" class = "form-control" name="memi" value="<?php echo $id; ?>" />
				<span>Nama: </span><input type="text" name="name" class = "form-control" value="<?php echo $row['user_name']; ?>" />
				<span>Email: </span><input type="text" name="email" class = "form-control" value="<?php echo $row['user_emil']; ?>" />
				<span>Password: </span><input type="password" name="pasw" class = "form-control" value="<?php echo $row['address']; ?>" />
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>			
		</form>
	</div>

<?php
}
?>

	