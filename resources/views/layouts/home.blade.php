@extends('layouts.app')

@section('content')
<!-- [ Pre-loader ] start -->
<!-- <div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div> -->
	<!-- [ Pre-loader ] End -->
	<!-- [ navigation menu ] start -->

	<!-- [ Header ] end -->
	
	

<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Dashboard</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- View Chart Start-->
            <div class="col-xl-4 col-md-6">
                <div class="card flat-card widget-primary-card bg-success-dark">
                    <div class="row-table">
                        <div class="col-sm-6 card-body bg-white">
                            <h6 class="text-dark m-b-5">Page Views</h6>
                            <h4 class="text-dark mb-0">3671</h4>
                        </div>
                        <div class="col-sm-6">
                            <div id="resource-barchart1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card flat-card widget-primary-card bg-primary-dark">
                    <div class="row-table">
                        <div class="col-sm-6 card-body bg-white">
                            <h6 class="text-dark m-b-5">Conversions</h6>
                            <h4 class="text-dark mb-0">1534</h4>
                        </div>
                        <div class="col-sm-6">
                            <div id="resource-barchart3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12">
                <div class="card flat-card widget-primary-card bg-danger-dark">
                    <div class="row-table">
                        <div class="col-sm-6 card-body bg-white">
                            <h6 class="text-dark m-b-5">Aquisitions</h6>
                            <h4 class="text-dark mb-0">3814</h4>
                        </div>
                        <div class="col-sm-6">
                            <div id="resource-barchart4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- View Chart End-->
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center text-center">
                            <div class="col-5">
                                <h6 class="text-muted">Real-Time Visits</h6>
                                <h3>53k</h3>
                            </div>
                            <div class="col-5">
                                <h6 class="text-muted">Returning Visitors</h6>
                                <h3>10k</h3>
                            </div>
                        </div>
                        <div id="seo-ecommerce-barchart"></div>
                        <hr class="mb-3 mt-0">
                        <div class="row justify-content-center text-center">
                            <div class="col-3 b-r-default">
                                <h5>85%</h5>
                                <p class="text-muted m-b-0">Satisfied</p>
                            </div>
                            <div class="col-3 b-r-default">
                                <h5>6%</h5>
                                <p class="text-muted m-b-0">Unsatisfied</p>
                            </div>
                            <div class="col-3">
                                <h5>9%</h5>
                                <p class="text-muted m-b-0">NA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card app-design">
                    <div class="card-body">
                        <button class="btn btn-success float-right">Pending</button>
                        <h6 class="f-w-400 text-muted">Landing Page Design</h6>
                        <p class="text-c-green f-w-400">Webdesign</p>
                        <hr class="my-4">
                        <p class="text-muted">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's.</p>
                        <div class="design-description d-inline-block m-r-40">
                            <h3 class="f-w-400">271</h3>
                            <p class="text-muted">Question</p>
                        </div>
                        <div class="design-description d-inline-block">
                            <h3 class="f-w-400">816</h3>
                            <p class="text-muted">Comments</p>
                        </div>
                        <div class="team-box p-b-0">
                            <p class="d-inline-block m-r-20 f-w-400">Team</p>
                            <div class="team-section d-inline-block">
                                <div class="team-section d-inline-block">
                                    <a href="#! "><img src="assets/images/user/avatar-3.jpg " data-toggle="tooltip" title="Lary Doe" alt=" " class="m-l-5 "></a>
                                    <a href="#! "><img src="assets/images/user/avatar-4.jpg " data-toggle="tooltip" title="Alia" alt=" " class="m-l-5 "></a>
                                    <a href="#! "><img src="assets/images/user/avatar-2.jpg " data-toggle="tooltip" title="Josephin Doe" alt=" "></a>
                                    <a href="#! "><img src="assets/images/user/avatar-3.jpg " data-toggle="tooltip" title="Suzen" alt=" " class="m-l-5 "></a>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="progress-box">
                            <p class="d-inline-block m-r-20 f-w-400">Progress</p>
                            <div class="progress d-inline-flex">
                                <div class="progress-bar bg-c-green" style="width:78% "><label>78%</label></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card table-card">
                    <div class="card-header">
                        <h5>New Products</h5>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-scroll" style="height:350px;position:relative;">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover m-b-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>HeadPhone</td>
                                            <td><img src="assets/images/widget/p1.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-warning">Pending</label></div>
                                            </td>
                                            <td>$10</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16  text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Iphone 6</td>
                                            <td><img src="assets/images/widget/p2.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-danger">Cancel</label></div>
                                            </td>
                                            <td>$20</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16  text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Jacket</td>
                                            <td><img src="assets/images/widget/p3.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-success">Success</label></div>
                                            </td>
                                            <td>$35</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Sofa</td>
                                            <td><img src="assets/images/widget/p4.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-danger">Cancel</label></div>
                                            </td>
                                            <td>$85</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Iphone 6</td>
                                            <td><img src="assets/images/widget/p2.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-success">Success</label></div>
                                            </td>
                                            <td>$20</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>HeadPhone</td>
                                            <td><img src="assets/images/widget/p1.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-warning">Pending</label></div>
                                            </td>
                                            <td>$50</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Iphone 6</td>
                                            <td><img src="assets/images/widget/p2.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-danger">Cancel</label></div>
                                            </td>
                                            <td>$30</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="user-card-body card">
                    <div class="card-body">
                        <div class="top-card text-center">
                            <img src="assets/images/user/avatar-2.jpg" class="img-fluid img-radius" alt="">
                        </div>
                        <div class="card-contain text-center p-t-20">
                            <h5 class="text-capitalize p-b-10">Gregory Johnes</h5>
                            <p class="text-muted">Califonia, USA</p>
                        </div>
                        <div class="card-data m-t-40">
                            <div class="row">
                                <div class="col-4 border-right text-center">
                                    <p class="text-muted">Followers</p>
                                    <h4 class="mb-0 text-primary">345</h4>
                                </div>
                                <div class="col-4 border-right text-center">
                                    <p class="text-muted">Following</p>
                                    <h4 class="mb-0 text-primary">40</h4>
                                </div>
                                <div class="col-4 text-center">
                                    <p class="text-muted">Answers</p>
                                    <h4 class="mb-0 text-primary">40</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-button p-t-50">
                            <div class="row">
                                <div class="col-6 text-right">
                                    <button class="btn btn-primary btn-round">Follow</button>
                                </div>
                                <div class="col-6 text-left">
                                    <button class="btn btn-success btn-round">Message</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
    <!-- Warning Section start -->
    <!-- Older IE warning message -->
    <!--[if lt IE 11]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade
               <br/>to any of the following web browsers to access this website.
            </p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="assets/images/browser/chrome.png" alt="Chrome">
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="assets/images/browser/firefox.png" alt="Firefox">
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="assets/images/browser/opera.png" alt="Opera">
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="assets/images/browser/safari.png" alt="Safari">
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="assets/images/browser/ie.png" alt="">
                            <div>IE (11 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
    <![endif]-->
    <!-- Warning Section Ends -->

    <!-- Required Js -->
    <!-- <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
	<script src="assets/js/menu-setting.min.js"></script> -->

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="text-center">
                        <h3 class="mt-3">Welcome To <span class="text-primary">Mash Able</span><sup>v3.0</sup></h3>
                    </div>
                    <div class="carousel-inner text-center">
                        <div class="carousel-item active" data-interval="50000">
                            <img src="assets/images/model/welcome.svg" class="wid-250 my-4" alt="images">
                            <div class="row justify-content-center">
                                <div class="col-lg-9">
                                    <p class="f-16"><strong>Mash Able v3.0</strong> will come with new Structure.</p>
                                    <p class="f-16"> it include <strong>Gulp / npm support, UI kit, Live customizer improved version, New improved layouts with RTL support, 8+ New Admin Panels</strong></p>
                                    <div class="row justify-content-center text-left">
                                        <div class="col-md-10">
                                            <h4 class="mb-3">Feature</h4>
                                            <p class="mb-2 f-16"><i class="feather icon-check-circle mr-2 text-primary"></i> Gulp / npm support</p>
                                            <p class="mb-2 f-16"><i class="feather icon-check-circle mr-2 text-primary"></i> UI kit</p>
                                            <p class="mb-2 f-16"><i class="feather icon-check-circle mr-2 text-primary"></i> Live customizer improved version</p>
                                            <p class="mb-2 f-16"><i class="feather icon-check-circle mr-2 text-primary"></i> New improved layouts with RTL support</p>
                                            <p class="mb-2 f-16"><i class="feather icon-check-circle mr-2 text-primary"></i> 8+ New Admin Panels</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item" data-interval="50000">
                            <img src="assets/images/model/able-admin.jpg" class="img-fluid mt-0" alt="images">
                        </div>
                    </div>

                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" style="transform:rotate(180deg);margin-bottom:-1px">
                    <path class="elementor-shape-fill" fill="#0073aa" opacity="0.33"
                        d="M473,67.3c-203.9,88.3-263.1-34-320.3,0C66,119.1,0,59.7,0,59.7V0h1000v59.7 c0,0-62.1,26.1-94.9,29.3c-32.8,3.3-62.8-12.3-75.8-22.1C806,49.6,745.3,8.7,694.9,4.7S492.4,59,473,67.3z">
                    </path>
                    <path class="elementor-shape-fill" fill="#0073aa" opacity="0.66"
                        d="M734,67.3c-45.5,0-77.2-23.2-129.1-39.1c-28.6-8.7-150.3-10.1-254,39.1 s-91.7-34.4-149.2,0C115.7,118.3,0,39.8,0,39.8V0h1000v36.5c0,0-28.2-18.5-92.1-18.5C810.2,18.1,775.7,67.3,734,67.3z"></path>
                    <path class="elementor-shape-fill" fill="#0073aa" d="M766.1,28.9c-200-57.5-266,65.5-395.1,19.5C242,1.8,242,5.4,184.8,20.6C128,35.8,132.3,44.9,89.9,52.5C28.6,63.7,0,0,0,0 h1000c0,0-9.9,40.9-83.6,48.1S829.6,47,766.1,28.9z"></path>
                </svg>
                <div class="modal-body text-center bg-primary py-4">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    </ol>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="ml-2">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="mr-2">Next</span>
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection