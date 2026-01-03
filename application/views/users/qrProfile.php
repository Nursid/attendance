
<?php

$idd=$this->uri->segment(3);
$loginId = $this->web->qrProfile($idd);
$dd=$this->web->getQrProfile($loginId['login_id']);
$type=$this->web->TypeGet($dd['user_group']);
//print_r($dd);
?>

<!DOCTYPE html>

<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!-- Mirrored from demo.flexy-codes.com/FlexyVcard/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 13 Oct 2020 05:19:41 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>

        <title>MID | User Profile</title>

        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

        <meta name="description" content="MID | User Profile"/>

        <!-- CSS | bootstrap -->
        <!-- Credits: http://getbootstrap.com/ -->
        <link  rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/bootstrap.min.css" />

        <!-- CSS | font-awesome -->
        <!-- Credits: http://fortawesome.github.io/Font-Awesome/icons/ -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/font-awesome.min.css" />
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
        <!-- CSS | animate -->
        <!-- Credits: http://daneden.github.io/animate.css/ -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/animate.min.css" />

        <!-- CSS | Normalize -->
        <!-- Credits: http://manos.malihu.gr/jquery-custom-content-scroller -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/jquery.mCustomScrollbar.css" />

        <!-- CSS | Colors -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/DarkBlue.css" id="colors-style" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/switcher.css" />

        <!-- CSS | Style -->
        <!-- Credits: http://themeforest.net/user/FlexyCodes -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/main.css" />

        <!-- CSS | prettyPhoto -->
        <!-- Credits: http://www.no-margin-for-errors.com/ -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/prettyPhoto.css"/>

		<!-- CSS | Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
        <!-- Favicon -->
       <link rel="icon" type="image/png" href="<?php echo base_url('adminassets/dist/img/logo.png')?>"/>

        <!--[if IE 7]>
                <link rel="stylesheet" type="text/css" href="css/icons/font-awesome-ie7.min.css"/>
        <![endif]-->

        <style>
            @media only screen and (max-width : 991px){
                .resp-vtabs .resp-tabs-container {
                    margin-left: 13px;
                }
            }

			@media only screen and (min-width : 800px) and (max-width : 991px){
                .resp-vtabs .resp-tabs-container {
                    margin-left: 13px;
					width:89%;
                }
            }

        </style>

    </head>

    <body>


        <!-- Laoding page -->
        <div id="preloader"><div id="spinner"></div></div>

        <!-- .slideshow -->
        <ul class="cb-slideshow" id="cb_slideshow" style="display:none">
            <li><span>Image 01</span><div></div></li>

        </ul>
        <!-- /.slideshow -->

        <!-- .wrapper -->
        <div class="wrapper">

            <!--- .Content -->
            <section class="tab-content">
                <div class="container">

                    <div class="row">

                        <div class="col-md-12">
                            <center><img class="head-image up" src="<?php echo base_url('adminassets/dist/img/fulllogo.png')?>"alt="" style="width:20%; height:20%; margin-bottom:10px; "  /></center>

                            <div class="row">

                                <div class="col-md-3 widget-profil">
                                    <div class="row">





<?php

if($dd['image']==null){


	?>

	 <div class="col-lg-12 col-md-12 col-sm-3 col-xs-12 ">



         <div class="image-holder one" id="pic_prof_1" style="display:block">

                <img class="head-image up circle" src="<?php echo base_url()?>upload/admin-default-no.jpg" title="No Image" width="150" height="150" alt="" />


        </div>

        <!-- style for simple image profile -->
   		<div class="circle-img" id="pic_prof_2" style="display:none"></div>


    </div>
    <!-- End Profile Image -->

    <div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">


        <!-- Profile info -->
        <div id="profile_info">
            <h1 id="name" class="transition-02"><?php echo $dd['name'];?></h1>
            <!--<h4 class="line"><?php //echo $type['name'];?></h4>-->

        </div>
        <!-- End Profile info -->


        <!-- Profile Description -->
        <div id="profile_desc">
            <p>
            	<?php// echo $dd['about_us']?>
            </p>

        </div>
        <!-- End Profile Description -->


        <!-- Name -->
         <div id="profile_social">
            <h6>My Social Profiles</h6>
            <a href="<?php echo $dd['facebookid']?>"><i class="fa fa-facebook"></i></a>
            <a href=""><i class="fa fa-twitter"></i></a>
            <a href=""><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa fa-youtube"></i></a>
            <div class="clear"></div>
        </div>
        <!-- End Name -->



    </div>

	<?php



}
else{
    //print_r($dd);
	?>




 <div class="col-lg-12 col-md-12 col-sm-3 col-xs-12 ">



         <div class="image-holder one" id="pic_prof_1" style="display:block">

                <img class="head-image up circle" src="<?php echo base_url()?><?php echo $dd['image']?>" width="150" height="150" alt="" />


        </div>

        <!-- style for simple image profile -->
   		<div class="circle-img" id="pic_prof_2" style="display:none"></div>


    </div>
    <!-- End Profile Image -->

    <div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">


        <!-- Profile info -->
        <div id="profile_info">
            <h1 id="name" class="transition-02"><?php echo $dd['name'];?></h1>
           <!-- <h4 class="line"><?php //echo $type['name'];?></h4>-->



        </div>
        <!-- End Profile info -->


        <!-- Profile Description -->
        <div id="profile_desc">
            <p>
            	<?php// echo $dd['about_us']?>
            </p>

        </div>
        <!-- End Profile Description -->


        <!-- Name -->
         <div id="profile_social">
            <h6>My Social Profiles</h6>

           <a href="<?php echo $dd['facebookid']?>"><i class="fa fa-facebook"></i></a>
            <a href="<?php echo $dd['twitterid']?>"><i class="fa fa-twitter"></i></a>
            <a href="<?php echo $dd['instagramid']?>"><i class="fa fa-instagram"></i></a>
            <a href="<?php echo $dd['youtube']?>"><i class="fa fa fa-youtube"></i></a>
            <a href="<?php echo $dd['googleprofile']?>"><i class="fa fa-google-plus"></i></a>
            <div class="clear"></div>
        </div>
        <!-- End Name -->



    </div>

	<?php

}

?>

    <!-- Profile Image -->












</div>
 </div>

<div class="col-md-9 flexy_content" style="padding-left: 0;padding-right: 0;">


	<!-- verticalTab menu -->
	<div id="verticalTab">


		<ul class="resp-tabs-list">
			<li class="tabs-profile hi-icon-wrap hi-icon-effect-5 hi-icon-effect-5a profile" data-tab-name="profile">
				<span class="tite-list">profile</span>
				<i class="fa fa-user icon_menu icon_menu_active"></i>
			</li>

		</ul>

		<div class="resp-tabs-container">

			<!-- profile -->
			<div id="profile" class="content_2">


           <?php
           if($dd['user_group']==1){

               ?>
                <h1 class="h-bloc"><b><?php echo $type['name'];?> Profile</b> - <?php echo $dd['BussinessType']?> </h1>
               <?php
           }else{
               ?>
                <h1 class="h-bloc"><b>Work Profile</b> - <?php echo $dd['BussinessType']?> </h1>
               <?php
           }

           ?>


<div class="row top-p">

    <div class="col-md-12 profile-l">

        <!--About me-->
        <div class="title_content">
            <div class="text_content"><?php echo $dd['name'];?></div>
            <div class="clear"></div>
        </div>

		   <ul class="about">

            <li>
                <i class="glyphicon glyphicon-user"></i>
                <label>Name </label>
                <span class="value"> <?php echo $dd['name'];?></span>
                <div class="clear"></div>
            </li>


            <li>
                <i class="glyphicon glyphicon-map-marker"></i>
                <label>Adress </label>
                <span class="value"> <?php echo $dd['address'];?></span>
                <div class="clear"></div>
            </li>

            <li>
                <i class="glyphicon glyphicon-envelope"></i>
                <label>Email </label>
                 <?php if($dd['email']==''){
                    ?>
                    <span class="value"> No Available</span>
                    <?php
                }else{
                    ?>
                 <span class="value"><a href="mailto:<?php echo $dd['email'];?>"> <?php echo $dd['email'];?></a></span><?php
                }?>
                <div class="clear"></div>
            </li>

            <li>
                <i class="glyphicon glyphicon-phone"></i>
                <label>Phone </label>
                <?php if($dd['mobile']==''){
                    ?>
                    <span class="value"> No Available</span>
                    <?php
                }else{
                    ?>
                <span class="value"><a href="tel:<?php echo $dd['mobile'];?>"> <?php echo $dd['mobile'];?></a></span>
                 <?php
                }?>
                <div class="clear"></div>
            </li>

            <li>
                <i class="glyphicon glyphicon-globe"></i>
                <label>Website </label>
                <?php if($dd['website']==''){
                    ?>
                    <span class="value"> No Available</span>
                    <?php
                }else{
                    ?>
                    <span class="value"><a href="<?php echo $dd['website'];?>" target="_blank"> <?php echo $dd['website'];?></a></span>
                    <?php
                }?>

                <div class="clear"></div>
            </li>

        </ul>

    </div>


</div>

    <div class="row">
	 <?php if($dd['user_group']==1){
            ?>

		<div class="col-md-12">
		    <a href="<?php echo base_url('User/checkIn').'/'.$dd['m_id']?>"><button type="button" class="btn btn-warning">Check In</button></a>
			<a  href="<?php echo $dd['paymentlink']?>" class="btn btn-primary">Pay now</a>
			<a  href="<?php echo $dd['googleprofile']?>"  class="btn btn-success">Business Link</a>

		</div>
            <?php


        }else{
            ?>
            <div class="row">
            </div>
            <?php
        }

               ?>
    </div>

    <div class="clear"></div>


    <div class="row" id="services">
		<div class="col-md-12">
            <div class="title_content">
                <div class="text_content">About Us</div>
                <div class="clear"></div>
            </div>


            <div class="col-md-12 ">
                <div class="service">
                    <div class="service-detail">
                        <p>
						<?php echo $dd['about_us']?>
						</p>
                    </div>
                </div>
            </div>

         </div>
    </div><!-- End Services -->





    <div class="clear"></div>
  </div>




                            </div><!-- End row -->

                        </div><!-- End col-md-12 -->

                    </div><!-- End row -->

                </div><!-- End container -->

            </section>
            <!-- End Content -->

        </div>

        <!-- Credits: http://jquery.com -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/jquery/jquery.min.js"></script>

        <!-- Js | bootstrap -->
        <!-- Credits: http://getbootstrap.com/ -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>

        <!-- Js | jquery.cycle -->
        <!-- Credits: https://github.com/malsup/cycle2 -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.cycle2.min.js"></script>

        <!-- jquery | rotate and portfolio -->
        <!-- Credits: http://jquery.com -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.mixitup.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/HeadImage.js"></script>

        <!-- Js | easyResponsiveTabs -->
        <!-- Credits: http://webtrendset.com/demo/easy-responsive-tabs/Index.html -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/easyResponsiveTabs.min.js"></script>

        <!-- Js | jquery.cookie -->
        <!-- Credits: https://github.com/carhartl/jquery-cookie -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.cookie.js"></script>

        <!-- Js | switcher -->
        <!-- Credits: http://themeforest.net/user/FlexyCodes -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/switcher.js"></script>

        <!-- Js | mCustomScrollbar -->
        <!-- Credits: http://manos.malihu.gr/jquery-custom-content-scroller -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>

        <!-- jquery | prettyPhoto -->
        <!-- Credits: http://www.no-margin-for-errors.com/ -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.prettyPhoto.js"></script>

        <!-- Js | gmaps -->
        <!-- Credits: http://maps.google.com/maps/api/js?sensor=true-->
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/gmaps.min.js"></script>

 		<!-- Js | Js -->
        <!-- Credits: http://themeforest.net/user/FlexyCodes -->
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/main.js"></script>

        <!-- code js for image rotate -->
        <script type="text/javascript">

            var mouseX;
            var mouseY;
            var imageOne;

            /* Calling the initialization function */
            $(init);

            /* The images need to re-initialize on load and on resize, or else the areas
             * where each image is displayed will be wrong. */
            $(window).load(init);
            $(window).resize(init);

            /* Setting the mousemove event caller */
            $(window).mousemove(getMousePosition);

            /* This function is called on document ready, on load and on resize
             * and initiallizes all the images */
            function init() {

                /* Instanciate the mouse position variables */
                mouseX = 0;
                mouseY = 0;

                /* Instanciate a HeadImage class for every image */
                imageOne = new HeadImage("one");

            }


            function getMousePosition(event) {

                /* Setting the mouse position variables */
                mouseX = event.pageX;
                mouseY = event.pageY;

                /*Calling the setImageDirection function of the HeadImage class
                 * to display the correct image*/
                imageOne.setImageDirection();

            }

        </script>

<script>$(document).ready(function () {
$('.nav-link').click(function(e) {
$('.nav-link').removeClass('active');
$(this).addClass("active");

});
});

$(function () {
    var url = window.location;
    // for single sidebar menu
    $('ul.nav-sidebar a').filter(function () {
        return this.href == url;
    }).addClass('active');

    // for sidebar menu and treeview
    $('ul.nav-treeview a').filter(function () {
        return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview")
        .css({'display': 'block'})
        .addClass('menu-open').prev('a')
        .addClass('active');
});
</script>

    </body>

</html>
