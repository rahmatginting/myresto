<?php session_start(); ?>    
<?php include 'inc/config.php'; $template['header_link'] = $_SESSION['sys_restoName'];?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<!-- DataTables Responsive CSS -->
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="lib/jquery.js" type="text/javascript"></script>
<script src="src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('a[rel*=facebox]').facebox({
            loadingImage : 'src/loading.gif',
            closeImage   : 'src/closelabel.png'
        })
    })
</script>

<!-- Page content -->
<div id="page-content">
    <!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Menu Makanan</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <h2></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <div id="maintable"><div style="margin-top: 10px; margin-bottom: 10px;">
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <a href = "#add" data-toggle = "modal" class="btn btn-primary">Tambah Menu</a>
                <?php include 'addmenu.php'; ?>
                <thead>
                    <tr>
                        <th> Restorant </th>
                        <th> Kategori </th>
                        <th> Kode </th>
                        <th> Menu </th>
                        <th> Deskripsi </th>
                        <th> Image URL </th>
                        <th width="10%"> Aksi </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include('connect.php');

                        $sql="select a.id, b.name as resto_name, c.name as categ_name, a.code, a.name, a.description, a.picture ";
                        $sql.="from restaurant_menu a inner join restaurants b on a.restaurant_id = b.id ";
                        $sql.="inner join menu_category c on a.category_id = c.code ";
                        $sql.="order by a.id asc";

                        $result = $db->prepare($sql);
                        $result->execute();
                        for($i=0; $row = $result->fetch(); $i++){
                    ?>
                    <tr class="record">
                        <td><?php echo $row['resto_name']; ?></td>
                        <td><?php echo $row['categ_name']; ?></td>
                        <td><?php echo $row['code']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><img id="<?php echo 'imgRow' . $row['id']; ?>" src="<?php echo $row['picture']; ?> " width='150' height='100' style='display: inline-block;'> </td>
                        <td align="center">
                            <button type="button" id="<?php echo $row['id']; ?>" class="btn btn-info imgbutton" data-toggle="modal" data-userid="<?php echo $row['id']; ?>" data-target="#uploadModal">Gambar</button>

                            <a rel="facebox" class="btn btn-primary" href="editmenu.php?id=<?php echo $row['id']; ?>"> <i class="fa fa-pencil"></i> 
                            </a>  
                            <a href="#" id="<?php echo $row['id']; ?>" class="btn btn-danger delbutton" title="Click To Delete">
                                <i class = "fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                        ?>
                </tbody>            
            </table>

            <!-- Modal -->
            <div id="uploadModal" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">File upload form</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Form -->
                        <form method='post' action='' enctype="multipart/form-data">
                            <input id="menu_id" type="hidden" name="menu_id" value=""> 
                            Select file : <input type='file' name='file' id='file' class='form-control' ><br>
                            <input type='button' class='btn btn-info' value="Upload" id='upload'>
                        </form>

                        <!-- Preview-->
                        <div id='preview'></div>
                    </div>
                    
                </div>

              </div>
            </div>

            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<script src="js/jquery.js"></script>
<script type="text/javascript">
    $(function() {
    $(".delbutton").click(function(){

    //Save the link in a variable called element
    var element = $(this);

    //Find the id of the link that was clicked
    var del_id = element.attr("id");

    //Built a url to send
    var info = 'id=' + del_id;
        if(confirm("Apakah anda yakin akan menghapus? Data TIDAK bisa dikembalikan!"))
        {
            $.ajax({
            type: "GET",
            url: "deletemenu.php",
            data: info,
            success: function(){
        }
    });
    $(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast")
        .animate({ opacity: "hide" }, "slow");
    }
        return false;
    });
    });
</script>

<!-- jQuery -->
<script src="vendors/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="vendors/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="vendors/metisMenu/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

<!-- DataTables JavaScript -->
<script src="vendors/datatables/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables-responsive/dataTables.responsive.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });

        $('#uploadModal').on('show.bs.modal', function(e) {
            var userid = $(e.relatedTarget).data('userid');
            $(e.currentTarget).find('input[name="menu_id"]').val(userid);
        });

        $('#upload').click(function(){
            //var menu_id = $('#menu_id').attr("value").val();
            var menu_id = $('#menu_id').val();
            var param_id = 'id=' + menu_id;

            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file',files);
            fd.append('request',1);
            fd.append('id',menu_id);
            // AJAX request
            $.ajax({
                url: 'upload.php',
                type: 'post',
                data: fd,param_id,
                contentType: false,
                processData: false,
                success: function(response){
                    if(response != 0){
                        var res = response.split("|");
                        // Show image preview
                        $('#preview').append("<img src='"+res[1]+"' width='150' height='100' style='display: inline-block;'>");
                        $("#" + 'imgRow'+res[2]).attr('src',res[1]);
                    }else{
                        alert('file not uploaded');
                    }
                }
            });
        });

    });
</script>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/uiTables.js"></script>
<script>$(function(){ UiTables.init(); });</script>

<?php include 'inc/template_end.php'; ?>
