<header class="mn-header navbar-fixed">
    <nav class="purple darken-1">
        <div class="nav-wrapper row">
            <section class="material-design-hamburger navigation-toggle">
                <a href="javascript:void(0)" data-activates="slide-out" class="button-collapse show-on-large material-design-hamburger__icon">
                    <span class="material-design-hamburger__layer"></span>
                </a>
            </section>
            <div class="header-title col s3 m3">      
                <span class="chapter-title"><font class="hide-on-small-and-down">CodeBits</font> Platform</span>
            </div>

            <ul class="right col s9 m3 nav-right-menu">
                <li class="hide-on-small-and-down">
                    <a href="javascript:void(0)" data-activates="dropdown1" class="dropdown-button dropdown-right show-on-large">
                        <i class="material-icons">notifications_none</i>
                        <?php if($this->notify['new']>0): ?>
                        <span class="badge"><?=$this->notify['new']?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>

            <ul id="dropdown1" class="dropdown-content notifications-dropdown">
                <li class="notificatoins-dropdown-container">
                    <ul>
                        <li class="notification-drop-title"><?= $this->lang->line("actions_latest") ?></li>
                        <?php if(count($this->notify['list'])>0): ?>
                            <?php foreach($this->notify['list'] as $notification): ?>
                            <li class="<?=($notification['readed']==0)?"new-notification":""?>">
                                <a href="/admin/notifications/">
                                    <div class="notification">
                                        <div class="notification-icon circle purple"><i class="material-icons"><?=$notification['icon']?></i></div>
                                        <div class="notification-text"><p><?=  strip_tags($notification['text'])?></p><span><?=date("d.m.Y H:i", $notification['time'])?></span></div>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach; ?>
                            <li class="notification-drop-title linked"><a href="/admin/notifications/"><?= $this->lang->line("show_all_notifications") ?></a></li>
                        <?php else: ?>
                        <li class="notification-drop-title" style="font-weight: 300;"><?= $this->lang->line("notifications_empty") ?></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<aside id="slide-out" class="side-nav white fixed">
    <div class="side-nav-wrapper">
        <div class="sidebar-profile">
            <div class="sidebar-profile-image">
                <div class="user_photo small" style="background-image:url('<?=(isset($this->user->profile['avatar']) && $this->user->profile['avatar']!="")?$this->user->profile['avatar']:"/frontend/assets/img/user.png"?>');"></div>
            </div>
            <div class="sidebar-profile-info">
                <a href="javascript:void(0);" class="account-settings-link">
                    <p><?= (isset($user['nickname']) && $user['nickname'] != "") ? $user['nickname'] : $this->user->auth['login'] ?></p>
                    <span><?= $user['email'] ?><i class="material-icons right">arrow_drop_down</i></span>
                </a>
            </div>
        </div>
        <div class="sidebar-account-settings">
            <ul>
                <li class="no-padding">
                    <a class="waves-effect waves-grey" href="/profile/edit/" target="blank"><i class="material-icons">settings</i><?= $this->lang->line("change_profile") ?></a>
                </li>
                <li class="divider"></li>
                <li class="no-padding">
                    <a class="waves-effect waves-grey" href="/auth/logout/"><i class="material-icons">exit_to_app</i><?= $this->lang->line("logout") ?></a>
                </li>
            </ul>
        </div>
        <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
            <li class="no-padding"><a class="waves-effect waves-grey" href="/admin/"><i class="material-icons">settings_input_svideo</i><?= $this->lang->line("nav_dashboard") ?></a></li>
            <li class="no-padding"><a class="waves-effect waves-grey" href="/admin/analytics/"><i class="material-icons">trending_up</i><?= $this->lang->line("nav_stats") ?></a></li>
            <li class="no-padding"><a class="waves-effect waves-grey" href="/admin/users/"><i class="material-icons">perm_identity</i><?= $this->lang->line("nav_users") ?></a></li>
            <li class="no-padding">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">apps</i><?= $this->lang->line("nav_apps") ?><i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <?php include(FRONTEND . '/view/admin/parts/nav_modules.php'); ?>
                    </ul>
                </div>
            </li>
            <li class="no-padding">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">code</i><?= $this->lang->line("nav_core_editor") ?><i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li><a href="/admin/editor/?type=bootstrap"><?= $this->lang->line("nav_core_editor_line_1") ?></a></li>
                        <li><a href="/admin/editor/?type=controllers"><?= $this->lang->line("nav_core_editor_line_2") ?></a></li>
                        <li><a href="/admin/editor/?type=models"><?= $this->lang->line("nav_core_editor_line_3") ?></a></li>
                        <li><a href="/admin/editor/?type=modules"><?= $this->lang->line("nav_core_editor_line_4") ?></a></li>
                        <li><a href="/admin/editor/?type=core"><?= $this->lang->line("nav_core_editor_line_5") ?></a></li>
                    </ul>
                </div>
            </li>
            <li class="no-padding"><a class="waves-effect waves-grey" href="/admin/media/"><i class="material-icons">photo_size_select_actual</i><?= $this->lang->line("nav_media") ?></a></li>
            <li class="no-padding"><a class="waves-effect waves-grey" href="/admin/localization/"><i class="material-icons">translate</i><?= $this->lang->line("nav_locale") ?></a></li>
            <li class="no-padding"><a class="waves-effect waves-grey" href="/admin/settings/"><i class="material-icons">settings</i><?= $this->lang->line("nav_settings") ?></a></li>
        </ul>
        <div class="footer">
            <p class="copyright">2017 © CodeBits Platform</p>
            <a href="//platform.codebits.xyz/" target="blank">Official Website</a>
        </div>
    </div>
</aside>