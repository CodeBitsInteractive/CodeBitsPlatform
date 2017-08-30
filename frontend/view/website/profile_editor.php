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
                                           <a href="/profile/edit/" class="btn-alt margin-null" style="width: 100%;"><?=$this->lang->line('change_profile')?></a>
                                           <a href="/profile/change_password/" class="btn margin-null" style="width: 100%;"><?=$this->lang->line('change_password')?></a>
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
                                <form role="form" id="contact-form" data-form="profile" action="/profile/saveProfile/" method="POST">
                                    <div class="row no-margin">
                                        <div class="col-sm-2 padding-leftright-null">
                                            <div class="project-images grid text">
                                                <div id="avatar" data-model="photo_picker" class="image" style="background-image:url(<?=(isset($profile['avatar']) && $profile['avatar']!="")?$profile['avatar']:'/frontend/website/assets/img/no-photo.jpg'?>); cursor: pointer;"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-sm-offset-2 padding-leftright-null">
                                            <input type="hidden" name="avatar" value="<?=$profile['avatar']?>" />
                                            <p style="font-weight: 800;"><?=$this->lang->line('change_avatar')?></p>
                                            <p><?=$this->lang->line('change_avatar_desc')?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row no-margin">
                                        <div class="col-sm-4 padding-leftright-null">
                                            <p style="font-weight: 800;"><?=$this->lang->line('fullname')?>:</p>
                                        </div>
                                        <div class="col-sm-8 padding-leftright-null">
                                            <div class="text small padding-topbottom-null">
                                                <input class="form-field" name="nickname" id="fullname" value="<?=$profile['nickname']?>" type="text" placeholder="<?= $this->lang->line('fullname') ?>" required="" maxlength="50">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-margin">
                                        <div class="col-sm-4 padding-leftright-null">
                                            <p style="font-weight: 800;"><?=$this->lang->line('login')?>:</p>
                                        </div>
                                        <div class="col-sm-8 padding-leftright-null">
                                            <div class="text small padding-topbottom-null">
                                                <input class="form-field" name="login" id="login" value="<?=$this->user->auth['login']?>" type="text" placeholder="<?= $this->lang->line('login') ?>" readonly="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-margin">
                                        <div class="col-sm-4 padding-leftright-null">
                                            <p style="font-weight: 800;">Email:</p>
                                        </div>
                                        <div class="col-sm-8 padding-leftright-null">
                                            <div class="text small padding-topbottom-null">
                                                <input class="form-field" name="email" id="email" value="<?=$profile['email']?>" type="email" placeholder="<?= $this->lang->line('email') ?>" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-margin">
                                        <div class="col-sm-4 padding-leftright-null">
                                            <p style="font-weight: 800;"><?= $this->lang->line('phone_number') ?>:</p>
                                        </div>
                                        <div class="col-sm-8 padding-leftright-null">
                                            <div class="text small padding-topbottom-null">
                                                <input class="form-field" name="profile_data[phone]" id="phone" value="<?=$profile['profile_data']['phone']?>" type="text" placeholder="<?= $this->lang->line('phone_number') ?>" maxlength="30">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-margin">
                                        <div class="col-sm-4 padding-leftright-null">
                                            <p style="font-weight: 800;"><?= $this->lang->line('birthday') ?>:</p>
                                        </div>
                                        <div class="col-sm-8 padding-leftright-null">
                                            <div class="text small padding-topbottom-null">
                                                <input class="form-field" name="profile_data[birthday]" id="phone" value="<?=$profile['profile_data']['birthday']?>" type="text" placeholder="<?= $this->lang->line('birthday') ?>" maxlength="30">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row no-margin">
                                        <div class="col-sm-12 padding-leftright-null">
                                            <a href="#" data-action="send-form" class="btn-alt shadow margin-null" style="width: 100%;"><?=$this->lang->line('save_profile')?></a>
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
                $('form[data-form="profile"]').submit();
            });
            
            // Photo Picker
            $('div[data-model="photo_picker"]').media_manager({
                enable_gallery: false,
                title: "<?=$this->lang->line("change_avatar")?>",
                upload_title: "<?=$this->lang->line("upload")?>",
                gallery_title: "Gallery",
                on_hidden: function(self){
                    var _elem = $('div[data-model="photo_picker"]');
                    var _url = _elem.media_manager('getURL');
                    if(_url!=''){
                        $('input[name="avatar"]').val(_url);
                        _elem.css('background-image', 'url("'+_url+'")');
                    }
                }
            });
        </script>
        <!-- SCRIPTS -->
    </body>
    <!-- BODY -->
</html>