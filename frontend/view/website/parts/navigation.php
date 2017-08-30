<header id="header" class="border">
    <div class="container">
        <nav class="navbar navbar-default">
            <!--  HEADER LOGO  -->
            <div id="logo">
                <a class="navbar-brand" href="/">
                    <img src="/frontend/view/website/assets/img/logo.png" class="normal" alt="<?=$this->lang->line('application') ?>">
                    <img src="/frontend/view/website/assets/img/logo@2x.png" class="retina" alt="<?=$this->lang->line('application') ?>">
                </a>
            </div>
            <!--  HEADER LOGO  -->
            <!--  MENU  -->
            <div id="sidemenu">
                <div class="menu-holder">
                    <ul>
                        <li><a href="/"><?=$this->lang->line('nav_home') ?></a></li>
                        <li><a href="/pages/view/about/"><?=$this->lang->line('nav_about') ?></a></li>
                        <li><a href="/pages/view/contacts/"><?=$this->lang->line('nav_contacts') ?></a></li>
                        <!--<li class="submenu">
                            <a href="javascript:void(0)">Pages</a>
                            <ul class="sub-menu">
                                <li><a href="about.html">About</a></li>
                                <li><a href="page-image-header.html">Page Image Header</a></li>
                                <li><a href="services.html">Services</a></li>
                                <li><a href="404.html">404</a></li>
                            </ul>
                        </li>-->
                        
                        <!-- LANG -->
                        <li class="submenu">
                            <a href="javascript:void(0)"><?=$this->lang->line('lang_name')?></a>
                            <ul class="sub-menu">
                                <li><a href="http://<?=DOMAIN.explode('?', URL, 2)[0]?>?lang=ru">Русский</a></li>
                                <li><a href="http://<?=DOMAIN.explode('?', URL, 2)[0]?>?lang=en">English</a></li>
                            </ul>
                        </li>
                        <!-- LANG -->
                    </ul>
                </div>
            </div>
            <!--  MENU  -->
            <!--  BUTTON FOR MENU  -->
            <div id="menu-responsive-sidemenu">
                <div class="menu-button">
                    <span class="bar bar-1"></span>
                    <span class="bar bar-2"></span>
                    <span class="bar bar-3"></span>
                </div>
            </div>
            <!--  BUTTON FOR MENU  -->
        </nav>
    </div>
</header>

<!-- FLOAT PANEL -->
<div class="float-pan">
    <?php if($this->user->auth['is_auth']): ?>
    <a href="/profile/"><div class="fp-button"><i class="icon ion-ios-person-outline"></i></div></a>
    <a href="/auth/logout/?redirect=<?=urlencode('http://'.DOMAIN.URL)?>"><div class="fp-button"><i class="icon ion-ios-close-empty"></i></div></a>
    <?php else: ?>
    <a href="/auth/sign_in/?redirect=<?=urlencode('http://'.DOMAIN.URL)?>"><div class="fp-button"><i class="icon ion-ios-locked-outline"></i></div></a>
    <?php endif; ?>
</div>
