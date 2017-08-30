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
                <!-- PAGE HEADER -->
                <?php if(isset($data['image']) && strlen($data['image'])>0): ?>
                <div>
                    <div class="row no-margin wrap-slider padding-leftright-null">
                       <div class="col-md-6 padding-leftright-null">
                           <div class="bg-img home" style="background-image:url(<?=$data['image']?>)"></div>
                       </div>
                       <div class="col-md-6 padding-leftright-null">
                           <div id="home-slider" class="secondary-background">
                               <div class="text">
                                   <h1 class="white margin-bottom"><?=$title?><span>.</span></h1>
                                   <p class="heading left grey-light"><?=$desc?></p>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
                <?php else: ?>
                <div class="container">
                    <div class="row no-margin">
                        <div class="col-md-12 padding-leftright-null">
                            <div id="page-header">
                                <div class="text">
                                    <h1 class="margin-bottom-small"><?= $title ?><span class="color">.</span></h1>
                                    <p class="heading left max full grey-dark"><?= $desc ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- PAGE HEADER -->
                
                <!-- PAGE BODY -->
                <div id="home-wrap" class="content-section">
                    <div class="container">
                        <div class="row no-margin padding-lg">
                            <div class="col-md-12 padding-leftright-null">
                                <?=$data['body']?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- PAGE BODY -->
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