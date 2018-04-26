<?php
	include('connect.php');
	$id=$_GET['id'];

    $sql="select b.name as resto_name, c.name as categ_name, a.code, a.name, a.description, a.picture ";
    $sql.="from restaurant_menu a inner join restaurants b on a.restaurant_id = b.id ";
    $sql.="inner join menu_category c on a.category_id = c.code WHERE a.id= :userid";
	$result = $db->prepare($sql);
	$result->bindParam(':userid', $id);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
	
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Edit Menu</h4>
	</div>
	<div class="modal-body">
		<form action="saveeditmenu.php" method="post" class = "form-group">
			<div id="ac">		
				<input type="hidden" class = "form-control" name="memi" value="<?php echo $id; ?>" />
				<span>Restaurant: </span><input type="text" name="rest" class = "form-control" value="<?php echo $row['resto_name']; ?>" disabled />
				<span>Kode Menu: </span><input type="text" name="code" class = "form-control" value="<?php echo $row['code']; ?>" disabled/>
				<span>Menu Kategori: </span><input type="text" name="categ" class = "form-control" value="<?php echo $row['categ_name']; ?>" disabled />
				<span>Nama Menu: </span><input type="text" name="name" class = "form-control" value="<?php echo $row['name']; ?>" />
				<span>Deskripsi: </span><input type="text" name="desc" class = "form-control" value="<?php echo $row['description']; ?>" />
				<span>Image URL: </span><input type="text" name="url" class = "form-control" value="<?php echo $row['picture']; ?>" />
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

	