<?php session_start(); ?>    
<?php include 'inc/config.php'; $template['header_link'] = 'Monitoring'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
    $today = date('d-m-Y');
    /*
        ORDER STATUS
        0 = Open
        1 = Progress
        2 = Close
        3 = Urgent
        4 = Invalid
    */
?>
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

<style type="text/css">
    .newline {
        white-space:pre-wrap;
    }
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/ion.sound.js"></script>


<input type="hidden" name="resto_id" id="resto_id" value="<?=$_SESSION['sys_restoID']?>" />

<!-- Page content -->
<div id="page-content">
    <!-- Tickets Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1><?php echo $_SESSION['sys_restoName'];?></h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><?php echo $today; ?></li>
                    </ul>
                </div>
            </div>            
        </div>
    </div>
    <!-- END Tickets Header -->

    <!-- Tickets Content -->
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <!-- Menu Block -->
            <div class="block full">
                <!-- Menu Title -->
                <div class="block-title clearfix">
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default" data-toggle="tooltip" title="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <h2>Manage Orders</h2>
                </div>
                <!-- END Menu Title -->

                <!-- Menu Content -->
                <ul class="nav nav-pills nav-stacked">
                    <li class="active">
                        <a href="page_app_email.php">
                            <span class="badge pull-right">350</span>
                            <i class="fa fa-fw fa-ticket icon-push"></i> <strong>All</strong>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <span class="badge pull-right">5</span>
                            <i class="fa fa-fw fa-exclamation-triangle icon-push"></i> <strong>Urgent</strong>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <span class="badge pull-right">10</span>
                            <i class="fa fa-fw fa-folder-open-o icon-push"></i> <strong>Open</strong>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <span class="badge pull-right">50</span>
                            <i class="fa fa-fw fa-folder-o icon-push"></i> <strong>Closed</strong>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <span class="badge pull-right">8</span>
                            <i class="fa fa-fw fa-ban icon-push"></i> <strong>Invalid</strong>
                        </a>
                    </li>
                </ul>
                <!-- END Menu Content -->
            </div>
            <!-- END Menu Block -->

            <!-- Quick Month Stats Block -->
            <div class="block">
                <!-- Quick Month Stats Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default" data-toggle="tooltip" title="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <h2>Statistik</h2>
                </div>
                <!-- END Quick Month Stats Title -->

                <!-- Quick Month Stats Content -->
                <table class="table table-striped table-borderless table-vcenter">
                    <tbody>
                        <tr>
                            <td style="width: 60%;">
                                <strong>Total Tickets</strong>
                            </td>
                            <td>1500</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Total Responses</strong>
                            </td>
                            <td>2590</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Forum Tickets</strong>
                            </td>
                            <td>320</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Email Tickets</strong>
                            </td>
                            <td>200</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Contact Form Tickets</strong>
                            </td>
                            <td>70</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Avg Response Time</strong>
                            </td>
                            <td>2 hrs</td>
                        </tr>
                    </tbody>
                </table>
                <!-- END Quick Month Stats Content -->
            </div>
            <!-- END Quick Month Stats Block -->
        </div>
        <div class="col-md-8 col-lg-9">
            <!-- Tickets Block -->
            <div class="block">
                <!-- Tickets Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default" data-toggle="tooltip" title="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        <li class="active">
                            <a href="#order-list">
                                <span id="spnOrder" class="label label-danger pull-right"></span>Daftar Pemesanan
                            </a>
                        </li>
                        <li>
                            <a href="#waiters">
                                <span id="spnWaitress" class="label label-danger pull-right"></span>Panggilan Pramusaji
                            </a>  
                        </li>
                        <li>
                            <a href="#tagihan">
                                <span id="spnTagihan" class="label label-danger pull-right"></span>Permintaan Tagihan
                            </a>  
                        </li>
                    </ul>
                </div>
                <!-- END Tickets Title -->

                <!-- Tabs Content -->
                <div class="tab-content">
                    <!-- Orders List -->
                    <div class="tab-pane active" id="order-list">
                        <div class="block-content-full">
                            <div class="table-responsive remove-margin-bottom">
                                <table id="tblOrders" class="table table-striped table-vcenter remove-margin-bottom">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th>Status</th>
                                            <th>Meja</th>
                                            <th>Pemesan</th>
                                            <th>Daftar Pesanan</th>
                                            <th class="text-center"><i class="fa fa-comments"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Disini coding order -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Rubah Status</h4>
                                  </div>
                                  <div class="modal-body">
                                    <form action="saveeditstatus.php" method="post" class = "form-group">
                                        <div id="ac">       
                                            <input type="hidden" class = "form-control"  name="orderID" id="orderID" value=""/>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Status Pemesanan</label>
                                                <div class="col-md-9">
                                                    <div class="radio">
                                                        <label for="radio1">
                                                            <input type="radio" id="radio1" name="status" value="0"> Open
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label for="radio2">
                                                            <input type="radio" id="radio2" name="status" value="1"> Progress
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label for="radio3">
                                                            <input type="radio" id="radio3" name="status" value="2"> Close
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label for="radio4">
                                                            <input type="radio" id="radio4" name="status" value="3"> Urgent
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label for="radio5">
                                                            <input type="radio" id="radio5" name="status" value="4"> Invalid
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>          
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="text-center">
                                <ul class="pagination">
                                    <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i></a></li>
                                    <li class="active"><a href="javascript:void(0)">1</a></li>
                                    <li><a href="javascript:void(0)">2</a></li>
                                    <li><a href="javascript:void(0)">3</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END Orders List -->

                    <!-- Waiters View -->
                    <div class="tab-pane" id="waiters">
                        <div class="block-content-full">
                            <div class="table-responsive remove-margin-bottom">
                                <table id="tblWaitress" class="table table-striped table-vcenter remove-margin-bottom">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th>Status</th>
                                            <th>Meja</th>
                                            <th>Pemesan</th>
                                            <th>Daftar Pesanan</th>
                                            <th class="text-center"><i class="fa fa-comments"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Disini coding order -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                                <ul class="pagination">
                                    <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i></a></li>
                                    <li class="active"><a href="javascript:void(0)">1</a></li>
                                    <li><a href="javascript:void(0)">2</a></li>
                                    <li><a href="javascript:void(0)">3</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END waiters View -->

                    <!-- Bayar View -->
                    <div class="tab-pane" id="tagihan">
                        <div class="block-content-full">
                            <div class="table-responsive remove-margin-bottom">
                                <table id="tblBayar" class="table table-striped table-vcenter remove-margin-bottom">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th>Status</th>
                                            <th>Meja</th>
                                            <th>Pemesan</th>
                                            <th>Daftar Pesanan</th>
                                            <th class="text-center"><i class="fa fa-comments"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Disini coding order -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                                <ul class="pagination">
                                    <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i></a></li>
                                    <li class="active"><a href="javascript:void(0)">1</a></li>
                                    <li><a href="javascript:void(0)">2</a></li>
                                    <li><a href="javascript:void(0)">3</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END Bayar View -->
                </div>
                <!-- END Tabs Content -->
            </div>
            <!-- END Tickets Block -->
        </div>
    </div>
    <!-- END Tickets Content -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>


<script src="js/pages/readyTickets.js"></script>
<script>$(function(){ ReadyTickets.init(); });</script>
<script type="text/javascript" src="js/monitor.js"></script>

<script type="text/javascript" >
    $(document).ready(function() {
        ion.sound({
            sounds: [
                {name: "bell_ring"},
            ],
            path: "sounds/",
            preload: true,
            volume: 1.0
        });

        var resto_id = document.getElementById('resto_id').value; 
        startAjaxMonitor(resto_id);
    });

    $(document).on("click", ".open-ModalEdit", function () {
         var myOrderId = $(this).data('id');
         $(".modal-body #orderID").val( myOrderId );
         // As pointed out in comments, 
         // it is superfluous to have to manually call the modal.
         $('#myModal').modal('show');
    });    
</script>

<?php include 'inc/template_end.php'; ?>
