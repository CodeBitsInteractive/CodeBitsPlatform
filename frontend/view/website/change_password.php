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
                                           <a href="/profile/edit/" class="btn margin-null" style="width: 100%;"><?=$this->lang->line('change_profile')?></a>
                                           <a href="/profile/change_password/" class="btn-alt margin-null" style="width: 100%; margin-top: 5px !important;"><?=$this->lang->line('change_password')?></a>
                                       </div>
                                   </div>
                               </div>
                           </div>
                        </div>
                       <div class="col-md-9">
                            <div style="padding: 50px 50px">
                                <h2><?=$title?></h2>
                                <p><b><?=$desc?></p>
                                <?php if(isset($error) && $error!==false && strlen($error)>0): ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong><?=$error?></strong>
                                </div>
                                <?php endif; ?>
                                <hr>
                                <form role="form" id="contact-form" data-form="passwords" action="/profile/savePassword/" method="POST">
                                    <div class="row no-margin">
                                        <div class="col-sm-4 padding-leftright-null">
                                            <p style="font-weight: 800;"><?=$this->lang->line('password')?>:</p>
                                        </div>
                                        <div class="col-sm-8 padding-leftright-null">
                                            <div class="text small padding-topbottom-null">
                                                <input class="form-field" name="password" id="password" value="" type="password" placeholder="<?= $this->lang->line('password') ?>" required="" maxlength="32">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-margin">
                                        <div class="col-sm-4 padding-leftright-null">
                                            <p style="font-weight: 800;"><?=$this->lang->line('repass')?>:</p>
                                        </div>
                                        <div class="col-sm-8 padding-leftright-null">
                                            <div class="text small padding-topbottom-null">
                                                <input class="form-field" name="repass" id="repass" value="" type="password" placeholder="<?= $this->lang->line('repass') ?>" maxlength="32">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-margin">
                                        <div class="col-sm-4 padding-leftright-null">
                                            <p style="font-weight: 800;"><?=$this->lang->line('new_password')?>:</p>
                                        </div>
                                        <div class="col-sm-8 padding-leftright-null">
                                            <div class="text small padding-topbottom-null">
                                                <input class="form-field" name="new_password" id="new_password" value="" type="password" placeholder="<?= $this->lang->line('new_password') ?>" maxlength="32">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row no-margin">
                                        <div class="col-sm-12 padding-leftright-null">
                                            <a href="#" data-action="send-form" class="btn-alt shadow margin-null" style="width: 100%;"><?=$this->lang->line('save_password')?></a>
                                        </div>
                                    </div>
                                </form>
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
        
        <script type="text/javascript">
            // Send form
            $('a[data-action="send-form"]').click(function(){
                $('form[data-form="passwords"]').submit();
            });
        </script>
        <!-- SCRIPTS -->
    </body>
    <!-- BODY -->
</html>