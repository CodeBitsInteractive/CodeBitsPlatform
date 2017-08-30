<!DOCTYPE html>
<html lang="<?=$this->lang->curr_lang?>">
    <head>
        <!-- BASE META -->
        <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$this->lang->line('application')?> » <?=$title?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?=$desc?>" />
	<meta name="author" content="CodeBits" />
        <meta name="generator" content="CodeBits Platform" />
        <meta name="robots" content="NOINDEX,NOFOLLOW" />
        <!-- BASE META -->
        
        <!-- ICONS -->
        <link rel="shortcut icon" href="/frontend/assets/img/favicon.ico" />
        <!-- ICONS -->
        
        <!-- THEME -->
	<?php include(FRONTEND.'/view/admin/parts/app_colors.php'); ?>
        <?php include(FRONTEND.'/view/admin/parts/base_styles.php'); ?>
        <link href="/frontend/view/admin/assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- THEME -->
        
        <!-- HTML5 SUPPORT -->
        <?php include(FRONTEND.'/view/admin/parts/html5.php'); ?>
        <!-- HTML5 SUPPORT -->
    </head>
    <body class="signup-page">
        <!-- PRELOADER -->
        <?php include(FRONTEND.'/view/admin/parts/preloader.php'); ?>
        <!-- PRELOADER -->
        
        <div class="mn-content valign-wrapper">
            <main class="mn-inner container">
                <div class="valign">
                      <div class="row">
                          <div class="col s12 m6 l4 offset-l4 offset-m3">
                              <div class="card white darken-1">
                                  <div class="card-content ">
                                      <span class="card-title"><?=$title?></span>
                                       <div class="row">
                                           <form class="col s12" action="/auth/registration/" method="POST">
                                               <!-- ERROR -->
                                               <?php if(isset($error) && !empty($error)):?>
                                               <div class="col s12">
                                                   <p class="red-text text-accent-4"><?=$error?></p>
                                               </div>
                                               <?php endif;?>
                                               <!-- ERROR -->
                                               <div class="input-field col s12">
                                                   <input id="fullname" name="fullname" type="text" class="validate" value="" />
                                                   <label for="fullname"><?=$this->lang->line("fullname")?></label>
                                               </div>
                                               <div class="input-field col s12">
                                                   <input id="login" name="login" type="text" class="validate" value="" />
                                                   <label for="login"><?=$this->lang->line("login")?></label>
                                               </div>
                                               <div class="input-field col s12">
                                                   <input id="email" name="email" type="email" class="validate" value="" />
                                                   <label for="email"><?=$this->lang->line("email")?></label>
                                               </div>
                                               <div class="input-field col s12">
                                                   <input id="password" name="password" type="password" class="validate" value="" />
                                                   <label for="password"><?=$this->lang->line("password")?></label>
                                               </div>
                                               <div class="input-field col s12">
                                                   <input id="repass" name="repass" type="password" class="validate" value="" />
                                                   <label for="repass"><?=$this->lang->line("repass")?></label>
                                               </div>
                                               <div class="col s12 right-align m-t-sm">
                                                   <a href="/auth/sign_in/" class="waves-effect waves-grey btn-flat"><?=$this->lang->line("signin")?></a>
                                                   <button type="submit" class="waves-effect waves-light btn purple"><?=$this->lang->line("signup")?></button>
                                               </div>
                                           </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
                    </div>
                </div>
            </main>
        </div>
        
        <!-- SCRIPTS -->
        <?php include(FRONTEND.'/view/admin/parts/base_scripts.php'); ?>
        <!-- SCRIPTS -->
    </body>
</html>