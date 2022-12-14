<?php if(!empty($this->session->userdata('user'))){ $users=$this->session->userdata('user'); }?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laboom - Food & Restaurant Bistro HTML Template</title>
    <link href="<?=base_url('assets_web/');?>plugin/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/bootstrap/datepicker.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/font-awesome/font-awesome.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/form-field/jquery.formstyler.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/revolution-plugin/extralayers.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/revolution-plugin/settings.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/owl-carousel/owl.theme.default.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/slick-slider/slick-theme.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/magnific/magnific-popup.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>plugin/animation/animate.min.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>css/theme.css" rel="stylesheet">
    <link href="<?=base_url('assets_web/');?>css/responsive.css" rel="stylesheet">
</head>

<body>
    
    <div id="pre-loader">
        <div class="loader-holder">
            <div class="frame">
                <img src="images/Preloader.gif" alt="Laboom" />
            </div>
        </div>
    </div>
    <div class="wrapper">
        <!-- Start Header -->
        <header>
            <div class="header-part header-reduce sticky">
                <div class="header-top">
                    <div class="container">
                        <div class="header-top-inner">
                            <div class="header-top-left">
                                <a href="#" class="top-cell"><img src="<?=base_url('assets_web/');?>images/fon.png" alt=""> <span>123-456-7890</span></a>
                                <a href="#" class="top-email"><span>support@laboom.com</span></a>
                            </div>
                            <div class="header-top-right">
                                <div class="social-top">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>
                                        <li><a href="#"><i class="fa fa-google" aria-hidden="true"></i></a></li>
                                    </ul>
                                </div>
                                <div class="language-menu">
                                    <a href="#" class="current-lang">English <i class="fa fa-caret-down" aria-hidden="true"></i></a>
                                    <ul>
                                        <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Turkish</a></li>
                                        <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Nederlands</a></li>
                                        <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Fran??ais</a></li>
                                        <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Deutsch</a></li>
                                        <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Italiano</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-bottom">
                    <div class="container">
                        <div class="header-info">
                            <div class="header-info-inner">
                                <div class="book-table header-collect book-md">
                                    <a href="#" data-toggle="modal" data-target="#booktable"><img src="images/icon-table.png" alt="">Book a Table</a>
                                </div>
                                <div class="shop-cart header-collect">
                                    <a href="#"><img src="<?=base_url('assets_web/');?>images/icon-basket.png" alt="">2 items - $ 20.89</a>
                                    <div class="cart-wrap">
                                        <div class="cart-blog">
                                            <div class="cart-item">
                                                <div class="cart-item-left">
                                                    <img src="images/img59.png" alt="">
                                                </div>
                                                <div class="cart-item-right">
                                                    <h6>Caramel Chesse Cake</h6>
                                                    <span>$ 14.00</span>
                                                </div>
                                                <span class="delete-icon"></span>
                                            </div>
                                            <div class="cart-item">
                                                <div class="cart-item-left">
                                                    <img src="images/img60.png" alt="">
                                                </div>
                                                <div class="cart-item-right">
                                                    <h6>Caramel Chesse Cake</h6>
                                                    <span>$ 14.00</span>
                                                </div>
                                                <span class="delete-icon"></span>
                                            </div>
                                            <div class="subtotal">
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <h6>Subtotal :</h6>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <span>$ 140.00</span>
                                                </div>
                                            </div>
                                            <div class="cart-btn">
                                                <a href="#" class="btn-black view">VIEW ALL</a>
                                                <a href="#" class="btn-main checkout">CHECK OUT</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="search-part">
                                    <a href="#"></a>
                                    <div class="search-box">
                                        <input type="text" name="txt" placeholder="Search">
                                        <input type="submit" name="submit" value=" ">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-icon">
                            <a href="#" class="hambarger">
                                <span class="bar-1"></span>
                                <span class="bar-2"></span>
                                <span class="bar-3"></span>
                            </a>
                        </div>
                        <div class="book-table header-collect book-sm">
                            <a href="#" data-toggle="modal" data-target="#booktable"><img src="<?=base_url('assets_web/');?>images/icon-table.png" alt="">Book a Table</a>
                        </div>
                        <div class="menu-main">
                            <ul>
                                <li class="has-child">
                                    <a href="index.html">Home</a>
                                    <ul class="drop-nav">
                                        <li><a href="index.html">Home Page 1</a></li>
                                        <li><a href="homepage1.html">Home Page 2</a></li>
                                        <li><a href="homepage2.html">Home Page 3</a></li>
                                        <li><a href="homepage3.html">Home Page 4</a></li>
                                        <li><a href="homepage4.html">Home Page 5</a></li>
                                        <li class="drop-has-child">
                                            <a href="#">Headers</a>
                                            <ul class="drop-nav">
                                                <li><a href="index.html">Header 1</a></li>
                                                <li><a href="homepage1.html">Header 2</a></li>
                                                <li><a href="homepage2.html">Header 3</a></li>
                                                <li><a href="homepage3.html">Header 4</a></li>
                                                <li><a href="homepage4.html">Header 5</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="mega-menu">
                                    <a href="#">Menu</a>
                                    <ul class="drop-nav">
                                        <li>
                                            <div class="drop-mega-part">
                                                <div class="row">
                                                <?php if(!empty($category)){ foreach($category as $row){ ?>
                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                        <span class="mega-title"><?=$row->category;?></span>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                <ul>
                                                                <?php if(!empty($subcategory)){ foreach($subcategory as $rows){ if($rows->cat_id==$row->id){ ?>
                                                                    <li><a href="#"><?=$rows->subcategory;?></a></li>
                                                                <?php } } } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } } ?>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="has-child">
                                    <a href="shop.html">Shop</a>
                                    <ul class="drop-nav">
                                        <li><a href="shop.html">Shop Page</a></li>
                                        <li><a href="shop_single.html">Shop Single</a></li>
                                        <li><a href="shop_cart.html">Shop Cart</a></li>
                                        <li><a href="shop_checkout.html">Shop Checkout</a></li>
                                        <li><a href="order_complete.html">Order Complete</a></li>
                                        <li><a href="track_order.html">Track Your Order</a></li>
                                        <li><a href="login_register.html">Login & Register</a></li>
                                    </ul>
                                </li>
                                <li class="has-child">
                                    <a href="#">Pages</a>
                                    <ul class="drop-nav">
                                        <li><a href="about.html">About Us</a></li>
                                        <li class="drop-has-child">
                                            <a href="service1.html">Services</a>
                                            <ul class="drop-nav">
                                                <li><a href="service1.html">Service 1</a></li>
                                                <li><a href="service2.html">Service 2</a></li>
                                            </ul>
                                        </li>
                                        <li class="drop-has-child">
                                            <a href="gallery1.html">Gallery</a>
                                            <ul class="drop-nav">
                                                <li><a href="gallery1.html">Gallery 2 Columns</a></li>
                                                <li><a href="gallery2.html">Gallery 3 Columns</a></li>
                                                <li><a href="gallery3.html">Gallery 4 Columns</a></li>
                                                <li><a href="gallery_masonry.html">Gallery Masonry</a></li>
                                            </ul>
                                        </li>
                                        <li class="drop-has-child">
                                            <a href="menu1.html">Menu</a>
                                            <ul class="drop-nav">
                                                <li><a href="menu1.html">Menu 1</a></li>
                                                <li><a href="menu2.html">Menu 2</a></li>
                                                <li><a href="menu3.html">Menu 3</a></li>
                                                <li><a href="menu4.html">Menu 4</a></li>
                                            </ul>
                                        </li>
                                        <li class="drop-has-child">
                                            <a href="team.html">Team</a>
                                            <ul class="drop-nav">
                                                <li><a href="team.html">Team List</a></li>
                                                <li><a href="team_single.html">Team Single</a></li>
                                            </ul>
                                        </li>
                                        <li class="drop-has-child">
                                            <a href="contact1.html">Contact</a>
                                            <ul class="drop-nav">
                                                <li><a href="contact1.html">Contact 1</a></li>
                                                <li><a href="contact2.html">Contact 2</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="terms_condition.html">Terms & Condition</a></li>
                                        <li><a href="faq.html">FAQ</a></li>
                                        <li><a href="404.html">404 Error</a></li>
                                    </ul>
                                </li>
                                <li class="has-child">
                                    <a href="blog_2col.html">Blog</a>
                                    <ul class="drop-nav">
                                        <li><a href="blog_list.html">Blog List</a></li>
                                        <li><a href="blog_2col.html">Blog 2 Columns</a></li>
                                        <li><a href="blog_full.html">Blog Full Width</a></li>
                                        <li><a href="blog_left_sidebar.html">Blog Left Sidebar</a></li>
                                        <li><a href="blog_right_sidebar.html">Blog Right Sidebar</a></li>
                                        <li><a href="blog_single.html">Blog Single</a></li>
                                    </ul>
                                </li>
                                <li class="has-child">
                                    <a href="contact1.html">Contact</a>
                                    <ul class="drop-nav">
                                        <li><a href="contact1.html">Contact 1</a></li>
                                        <li><a href="contact2.html">Contact 2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="logo">
                            <a href="index.html"><img src="<?=base_url('assets_web/');?>images/mlogo.png" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- End Header -->










