<?php session_start(); ?>    
<?php include 'inc/config.php'; $template['header_link'] = $_SESSION['sys_restoName']; ?> 
<?php
	//require_once('authentic.php');
	include 'inc/template_start.php'; 
	include 'inc/page_head.php'; 

	include('connect.php');
    /*
	$result = $db->prepare("SELECT COUNT(*) AS totRegist FROM tb_alumnim");
	$result->execute();											
	for($i=0; $row = $result->fetch(); $i++){
			$totRegist=$row['totRegist']; 
	}

	$result = $db->prepare("SELECT COUNT(*) AS totVote FROM tb_votingm");
	$result->execute();											
	for($i=0; $row = $result->fetch(); $i++){
			$totVote=$row['totVote']; 
	}
    */

    $totRegist=250;
    $totVote=10;

?>

<!-- Page content -->
<div id="page-content">
    <!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1><strong> <?php echo $_SESSION['sys_restoName'];?></strong></h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><?php echo $_SESSION['namaAlumni']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Table Styles Header -->

    <!-- Table Styles Block -->
    <div class="block">
        <!-- Table Styles Title -->
        <!-- END Table Styles Title -->
        <div class="col-lg-12">
            <!-- Kotak Pertama -->
            <div class="col-lg-3 col-md-8">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                            <i class="fa fa-comments fa-4x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <?php
                                    function formatMoney($number, $fractional=false) {
                                    if ($fractional) {
                                    $number = sprintf('%.2f', $number);
                                    }
                                    while (true) {
                                    $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
                                    if ($replaced != $number) {
                                    $number = $replaced;
                                    } else {
                                        break;
                                    }
                                    }
                                        return $number;
                                    }
                                ?>
                                <?php 
                                    $today = date('d-m-Y');
                                ?>
                                <div style = 'font:18px sans-serif;color:#ffffff'> Total Merchant  </div>
                                    <div style = 'font:18px sans-serif;color:#ffffff'>
	                                [<strong><span data-toggle="counter" data-to="<?php echo $totRegist; ?>"></span></strong>]
                                    </div>
                                    <?php echo  $today;  ?>         
                            </div>
                        </div>
                    </div>
                                <a href="total-merchant.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                </div>
            </div>
            <!-- Kotak Kedua -->
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-4x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style = 'font:18px sans-serif;color:#ffffff'> Total User </div>
                                    <div style = 'font:18px sans-serif;color:#ffffff'>
	                                [<strong><span data-toggle="counter" data-to="<?php echo $totRegist; ?>"></span></strong>]
                                    </div>
                                    <?php echo  $today;  ?>         
                           	</div>
                        </div>
                    </div>
                    <a href="total-user.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                    </a>
                </div>
            </div>
            <!-- Kotak Ketiga -->
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-money fa-4x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                               <div style = 'font:18px sans-serif;color:#ffffff'> Prosentase per Kota </div>
                                [0]  <?php echo "$today" ?>
                            </div>
                        </div>
                    </div>
                    <a href="total-merchant.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Kotak Keempat -->
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-recycle fa-4x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style = 'font:18px sans-serif;color:#ffffff'> Jumlah Kota </div>
                                    <div style = 'font:18px sans-serif;color:#ffffff'>
	                                [<strong><span data-toggle="counter" data-to="<?php echo $totVote; ?>"></span></strong>]
                                    </div>
                                    <?php echo  $today;  ?>                                         
                            </div>
                        </div>
                    </div>
                    <a href="total-user.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Charts Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1></h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li></li>
                        <li><a href=""></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Charts Header -->

    <!-- Classic and Stacked Chart -->
<!-- General Code -->

    <div class="row">
        <div class="col-sm-6">
            <div class="block full">
                <!-- Artikel Judul -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-warning" data-toggle="tooltip" title="Add to favorites"><i class="fa fa-star"></i></a>
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-success" data-toggle="tooltip" title="Add bookmark"><i class="fa fa-bookmark"></i></a>
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-danger" data-toggle="tooltip" title="Love it"><i class="fa fa-heart"></i></a>
                    </div>
                    <h2><i class="fa fa-clock-o"></i> Mengenai Chatbot </h2>
                </div>
                <!-- Konten Artikel -->
                <article class="article-story">
                    <!DOCTYPE html>
                    <html>
                    <head>
                    <title> oleh: Admin IAC </title>
                    </head>
                    <body>
 
                    </body>
                    </html>
                </article>
            </div>
        </div>
        <div class="col-sm-6">
            <!-- Stacked Chart Block -->
            <div class="block full">
                <!-- Artikel Judul -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-warning" data-toggle="tooltip" title="Add to favorites"><i class="fa fa-star"></i></a>
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-success" data-toggle="tooltip" title="Add bookmark"><i class="fa fa-bookmark"></i></a>
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-danger" data-toggle="tooltip" title="Love it"><i class="fa fa-heart"></i></a>
                    </div>
                    <h2><i class="gi gi-pen"></i> Tata Cara</h2>
                </div>
                <!-- Konten Artikel -->
                <article class="article-story">
                    <!DOCTYPE html>
                    <html>
                    <head>
                    <title> oleh: Admin IAC </title>
                    </head>
                    <body>
 
                    </body>
                    </html>
                </article>
                <!-- end of article -->
            </div>
        </div>
            <!-- END Stacked Chart Block -->
    </div>
    </div>
        <!-- Table Styles Content 
        <div class="table-responsive">
            Available Table Classes:
                'table'             - basic table
                'table-bordered'    - table with full borders
                'table-borderless'  - table with no borders
                'table-striped'     - striped table
                'table-condensed'   - table with smaller top and bottom cell padding
                'table-hover'       - rows highlighted on mouse hover
                'table-vcenter'     - middle align content vertically
            
        </div> -->
            <!--
            <table id="general-table" class="table table-striped table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 320px;">Progress</th>
                        <th style="width: 120px;" class="text-center"><i class="fa fa-flash"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Gabriel Morris</strong></td>
                        <td>gabriel.morris@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Ellis Thompson</strong></td>
                        <td>ellis.thompson@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Reece Bell</strong></td>
                        <td>reece.bell@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Scarlett Reid</strong></td>
                        <td>user4@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Alfie Harrison</strong></td>
                        <td>alfie.harrison@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-danger" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Finley Hunt</strong></td>
                        <td>finley.hunt@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" style="width: 55%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Oliver Watson</strong></td>
                        <td>oliver.watson@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Maddison Reid</strong></td>
                        <td>maddison.reid@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Katie Ward</strong></td>
                        <td>katie.ward@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><label class="csscheckbox csscheckbox-primary"><input type="checkbox"><span></span></label></td>
                        <td><strong>Aidan Powell</strong></td>
                        <td>aidan.powell@example.com</td>
                        <td>
                            <div class="progress progress-mini active remove-margin">
                                <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> -->
        <!-- END Table Styles Content -->
    <!-- END Table Styles Block -->
    
    <!-- END Datatables Block -->
<!-- END Page Content -->


<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<script src="js/pages/readyDashboard.js"></script>
<script>$(function(){ ReadyDashboard.init(); });</script>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/uiTables.js"></script>
<script>$(function(){ UiTables.init(); });</script>

<?php include 'inc/template_end.php'; ?>
