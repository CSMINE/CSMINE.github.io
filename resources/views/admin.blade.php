<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="/frontend/admin/images/favicon.ico">

        <!-- App title -->
        <title>Панель управления</title>

        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="/frontend/admin/plugins/morris/morris.css">
		<link rel="stylesheet" href="/frontend/admin/plugins/toastr/toastr.min.css">
		
		<link href="/frontend/admin/plugins/custombox/css/custombox.min.css" rel="stylesheet" type="text/css" />
        <!-- DataTables -->
        <link href="/frontend/admin/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="/frontend/admin/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="/frontend/admin/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Switchery css -->
        <link href="/frontend/admin/plugins/switchery/switchery.min.css" rel="stylesheet" />

        <!-- App CSS -->
        <link href="/frontend/admin/css/style.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <!-- Modernizr js -->
        <script src="/frontend/admin/js/modernizr.min.js"></script>
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="/frontend/admin/js/jquery.min.js"></script>
        <script src="/frontend/admin/js/tether.min.js"></script><!-- Tether for Bootstrap -->
        <script src="/frontend/admin/js/bootstrap.min.js"></script>
        <script src="/frontend/admin/js/detect.js"></script>
        <script src="/frontend/admin/js/fastclick.js"></script>
        <script src="/frontend/admin/js/jquery.blockUI.js"></script>
        <script src="/frontend/admin/js/waves.js"></script>
        <script src="/frontend/admin/js/jquery.nicescroll.js"></script>
        <script src="/frontend/admin/js/jquery.scrollTo.min.js"></script>
        <script src="/frontend/admin/js/jquery.slimscroll.js"></script>
        <script src="/frontend/admin/plugins/switchery/switchery.min.js"></script>
        <script src="/frontend/admin/plugins/custombox/js/custombox.min.js"></script>
        <script src="/frontend/admin/plugins/custombox/js/legacy.min.js"></script>
        <script src="/frontend/admin/plugins/toastr/toastr.min.js"></script>
        <script src="/front/js/admin.js"></script>
    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="/admin" class="logo">
						<i class="zmdi zmdi-pocket"></i>
                        <span>CSGOPLAY.SU</span></a>
                </div>


                <nav class="navbar navbar-custom">
                    <ul class="nav navbar-nav">
                        <li class="nav-item">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="zmdi zmdi-menu"></i>
                            </button>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">

                        <li class="nav-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <img src="{{$u->avatar}}" alt="user" class="img-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow profile-dropdown " aria-labelledby="Preview">
                                <!-- item-->
                                <a href="/" class="dropdown-item notify-item">
                                    <i class="zmdi zmdi-home"></i> <span>На сайт</span>
                                </a>

                            </div>
                        </li>

                    </ul>

                </nav>

            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <ul>
                        	<li class="text-muted menu-title">Меню</li>

                            <li class="has_sub">
                                <a href="/admin" class="waves-effect"><i class="zmdi zmdi-view-dashboard"></i><span> Главная </span> </a>
                            </li> 
							
							<li class="has_sub">
                                <a href="/admin/users" class="waves-effect"><i class="zmdi zmdi-accounts"></i><span> Пользователи </span> </a>
                            </li>
                            <li class="has_sub">
                                <a href="/admin/game" class="waves-effect"><i class="zmdi zmdi-money-box"></i><span> Игры </span> </a>
                            </li>
                            
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>

                </div>

            </div>
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
						@yield('content')
					</div> <!-- container -->
                </div> <!-- content -->
            </div>
            <!-- End content-page -->
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
            <div class="side-bar right-bar">
                <div class="nicescroll">
                    <ul class="nav nav-tabs text-xs-center">
                        <li class="nav-item">
                            <a href="#home-2"  class="nav-link active" data-toggle="tab" aria-expanded="false">
                                Activity
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#messages-2" class="nav-link" data-toggle="tab" aria-expanded="true">
                                Settings
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home-2">
                            <div class="timeline-2">
                                <div class="time-item">
                                    <div class="item-info">
                                        <small class="text-muted">5 minutes ago</small>
                                        <p><strong><a href="#" class="text-info">John Doe</a></strong> Uploaded a photo <strong>"DSC000586.jpg"</strong></p>
                                    </div>
                                </div>

                                <div class="time-item">
                                    <div class="item-info">
                                        <small class="text-muted">30 minutes ago</small>
                                        <p><a href="" class="text-info">Lorem</a> commented your post.</p>
                                        <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                    </div>
                                </div>

                                <div class="time-item">
                                    <div class="item-info">
                                        <small class="text-muted">59 minutes ago</small>
                                        <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
                                        <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                    </div>
                                </div>

                                <div class="time-item">
                                    <div class="item-info">
                                        <small class="text-muted">1 hour ago</small>
                                        <p><strong><a href="#" class="text-info">John Doe</a></strong>Uploaded 2 new photos</p>
                                    </div>
                                </div>

                                <div class="time-item">
                                    <div class="item-info">
                                        <small class="text-muted">3 hours ago</small>
                                        <p><a href="" class="text-info">Lorem</a> commented your post.</p>
                                        <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                    </div>
                                </div>

                                <div class="time-item">
                                    <div class="item-info">
                                        <small class="text-muted">5 hours ago</small>
                                        <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
                                        <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="messages-2">

                            <div class="row m-t-20">
                                <div class="col-xs-8">
                                    <h5 class="m-0">Notifications</h5>
                                    <p class="text-muted m-b-0"><small>Do you need them?</small></p>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                                </div>
                            </div>

                            <div class="row m-t-20">
                                <div class="col-xs-8">
                                    <h5 class="m-0">API Access</h5>
                                    <p class="m-b-0 text-muted"><small>Enable/Disable access</small></p>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                                </div>
                            </div>

                            <div class="row m-t-20">
                                <div class="col-xs-8">
                                    <h5 class="m-0">Auto Updates</h5>
                                    <p class="m-b-0 text-muted"><small>Keep up to date</small></p>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                                </div>
                            </div>

                            <div class="row m-t-20">
                                <div class="col-xs-8">
                                    <h5 class="m-0">Online Status</h5>
                                    <p class="m-b-0 text-muted"><small>Show your status to all</small></p>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> <!-- end nicescroll -->
            </div>
            <!-- /Right-bar -->

            <footer class="footer text-right">
                2016 © CSGO.MK
            </footer>


        </div>
        <!-- END wrapper -->




        <!-- Counter Up  -->
        <script src="/frontend/admin/plugins/waypoints/lib/jquery.waypoints.js"></script>
        <script src="/frontend/admin/plugins/counterup/jquery.counterup.min.js"></script>

        <!-- Required datatable js -->
        <script src="/frontend/admin/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/frontend/admin/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="/frontend/admin/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="/frontend/admin/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="/frontend/admin/plugins/datatables/jszip.min.js"></script>
        <script src="/frontend/admin/plugins/datatables/pdfmake.min.js"></script>
        <script src="/frontend/admin/plugins/datatables/vfs_fonts.js"></script>
        <script src="/frontend/admin/plugins/datatables/buttons.html5.min.js"></script>
        <script src="/frontend/admin/plugins/datatables/buttons.print.min.js"></script>
        <script src="/frontend/admin/plugins/datatables/buttons.colVis.min.js"></script>
        <!-- Responsive examples -->
        <script src="/frontend/admin/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="/frontend/admin/plugins/datatables/responsive.bootstrap4.min.js"></script>

        <!-- App js -->
        <script src="/frontend/admin/js/jquery.core.js"></script>
        <script src="/frontend/admin/js/jquery.app.js"></script>

		<script type="text/javascript">
            $(document).ready(function() {
                $('#datatable').DataTable();

                //Buttons examples
                var table = $('#datatable-buttons').DataTable({
                    lengthChange: false,
                    buttons: ['copy', 'excel', 'pdf', 'colvis']
                });

                table.buttons().container()
					.appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
					$('#datatable').show();
					$('#loader').hide();
            } );

        </script>

    </body>
</html>