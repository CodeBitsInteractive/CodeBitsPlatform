<!DOCTYPE html>
<html lang="<?=$this->lang->curr_lang?>">
    <!-- HEAD -->
    <?php include(FRONTEND.'/view/website/parts/header.php');?>
    <!-- HEAD -->
    <!-- BODY -->
    <body>
        <!-- PRELOADER -->
        <?php include(FRONTEND.'/view/website/parts/preloader.php'); ?>
        <!-- PRELOADER -->
        
        <!-- MAIN WRAPPER -->
        <div id="main-wrap">
            <!-- HEADER & MENU -->
            <?php include(FRONTEND.'/view/website/parts/navigation.php'); ?>
            <!-- HEADER & MENU -->
            
            <!-- CONTENT -->
            <div id="page-content">
                <!--  HomePage header  -->
                <div class="container">
                   <div class="row no-margin">
                       <div class="col-md-12 padding-leftright-null">
                           <div id="home-header">
                               <div class="text">
                                   <h1 class="margin-bottom-small"><span class="grey-dark"><?=$this->lang->line('hello_1')?></span><br><?=$this->lang->line('hello_2')?><span class="color">.</span></h1>
                                   <p class="heading left max full grey-dark"><?=$this->lang->line('hello_sub')?></p>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
                <div class="container">
                   <div class="row no-margin wrap-slider">
                       <div class="col-md-6 padding-leftright-null">
                           <div id="flexslider" class="home">
                               <ul class="slides">
                                   <li style="background-image:url(/frontend/view/website/assets/img/slider-creative.jpg)"></li>
                                   <li style="background-image:url(/frontend/view/website/assets/img/slider-creative-2.jpg)"></li>
                               </ul>
                           </div>
                       </div>
                       <div class="col-md-6 padding-leftright-null">
                           <div id="home-slider" class="secondary-background">
                               <div class="text">
                                   <h1 class="white margin-bottom"><?=$this->lang->line('be_creative')?></h1>
                                   <p class="heading left grey-light"><?=$this->lang->line('creative_desc')?></p>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
                <!--  END HomePage header  -->
                <div id="home-wrap" class="content-section">
                    <!-- Services Section -->
                    <div class="container">
                        <div class="row no-margin padding-lg">
                            <!-- Single Services -->
                            <div class="col-md-4 padding-leftright-null">
                                <div class="text padding-topbottom-null padding-md-bottom">
                                    <i class="icon ion-ios-speedometer-outline color service margin-bottom-extrasmall"></i>
                                    <h4 class="big margin-bottom-extrasmall"><?=$this->lang->line('feat1_head')?></h4>
                                    <p class="margin-bottom-null"><?=$this->lang->line('feat1_desc')?></p>
                                </div>
                            </div>
                            <!-- END Single Services -->
                            <div class="col-md-4 padding-leftright-null">
                                <div class="text padding-topbottom-null padding-md-bottom">
                                    <i class="icon ion-ios-world-outline color service margin-bottom-extrasmall"></i>
                                    <h4 class="big margin-bottom-extrasmall"><?=$this->lang->line('feat2_head')?></h4>
                                    <p class="margin-bottom-null"><?=$this->lang->line('feat2_desc')?></p>
                                </div>
                            </div>
                            <div class="col-md-4 padding-leftright-null">
                                <div class="text padding-topbottom-null">
                                    <i class="icon ion-ios-flame-outline color service margin-bottom-extrasmall"></i>
                                    <h4 class="big margin-bottom-extrasmall"><?=$this->lang->line('feat3_head')?></h4>
                                    <p class="margin-bottom-null"><?=$this->lang->line('feat3_desc')?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Services Section -->
                </div>
            </div>
            <!-- CONTENT -->
        </div>
        <!-- MAIN WRAPPER -->
        
        <!-- FOOTER -->
        <?php include(FRONTEND.'/view/website/parts/footer.php'); ?>
        <!-- FOOTER -->
        
        <!-- SCRIPTS -->
        <?php include(FRONTEND.'/view/website/parts/base_scripts.php'); ?>
        <script src="/frontend/view/website/assets/js/custom.js"></script>
        <!-- SCRIPTS -->
    </body>
    <!-- BODY -->
</html>