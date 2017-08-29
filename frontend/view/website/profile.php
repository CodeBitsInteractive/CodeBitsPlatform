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
                <!-- PROFILE HEADER -->
                <div class="container">
                   <div class="row no-margin">
                        <div class="col-md-3">
                           <div style="padding: 50px 50px">
                               <div class="row no-margin">
                                   <div class="project-images grid text">
                                       <div class="col-sm-12 padding-leftright-null">
                                           <a href="<?=(isset($profile['avatar']) && $profile['avatar']!="")?$profile['avatar']:''?>" class="lightbox">
                                               <div class="image" style="background-image:url(<?=(isset($profile['avatar']) && $profile['avatar']!="")?$profile['avatar']:'/frontend/website/assets/img/no-photo.jpg'?>)"></div>
                                           </a>
                                       </div>
                                   </div>
                               </div>
                           </div>
                        </div>
                       <div class="col-md-9">
                            <div style="padding: 50px 50px">
                                <h2><?= (isset($profile['nickname']) && $profile['nickname'] != "") ? $profile['nickname'] : $this->user->auth['login'] ?></h2>
                                <p><b><?=$this->lang->line('reg_date')?>:</b> <?=date("d.m.Y",$profile['last_login_day'])?></p>
                                <?php if(isset($complete) && $complete!==false): ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong><?=$this->lang->line('profile_saved_self')?></strong>
                                </div>
                                <?php endif; ?>
                                <?php if(isset($error) && $error!==false && strlen($error)>0): ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong><?=$error?></strong>
                                </div>
                                <?php endif; ?>
                                <hr>
                                <div class="row no-margin">
                                   <div class="col-sm-4 padding-leftright-null">
                                       <p style="font-weight: 800;"><?=$this->lang->line('login')?>:</p>
                                   </div>
                                   <div class="col-sm-8 padding-leftright-null">
                                       <p><?=$this->user->auth['login']?></p>
                                   </div>
                                </div>
                                <div class="row no-margin">
                                   <div class="col-sm-4 padding-leftright-null">
                                       <p style="font-weight: 800;">Email:</p>
                                   </div>
                                   <div class="col-sm-8 padding-leftright-null">
                                       <p><?=$profile['email']?></p>
                                   </div>
                                </div>
                                <div class="row no-margin">
                                   <div class="col-sm-4 padding-leftright-null">
                                       <p style="font-weight: 800;"><?=$this->lang->line('phone_number')?>:</p>
                                   </div>
                                   <div class="col-sm-8 padding-leftright-null">
                                       <p><?= (isset($profile['profile_data']['phone']) && $profile['profile_data']['phone'] != "") ? $profile['profile_data']['phone'] : $this->lang->line('unknown') ?></p>
                                   </div>
                                </div>
                                <div class="row no-margin">
                                   <div class="col-sm-4 padding-leftright-null">
                                       <p style="font-weight: 800;"><?= $this->lang->line('birthday')?>:</p>
                                   </div>
                                   <div class="col-sm-8 padding-leftright-null">
                                       <p><?=(isset($profile['profile_data']['birthday']))?$profile['profile_data']['birthday']:""?></p>
                                   </div>
                                </div>
                                <hr>
                                <div class="row no-margin">
                                   <div class="col-sm-4 padding-leftright-null">
                                       <p style="font-weight: 800;">IP:</p>
                                   </div>
                                   <div class="col-sm-8 padding-leftright-null">
                                       <p><?=$this->user->getIP()?></p>
                                   </div>
                                </div>
                                <hr>
                                <div class="row no-margin">
                                   <div class="col-sm-12 padding-leftright-null">
                                       <a href="/profile/edit/" class="btn-alt margin-null"><?=$this->lang->line('change_profile')?></a>
                                       <a href="/auth/logout/?redirect=http://<?=DOMAIN?>/" class="btn"><?=$this->lang->line('logout')?></a>
                                   </div>
                                </div>
                            </div>
                        </div>
                   </div>
                </div>
                <!-- PROFILE HEADER -->
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