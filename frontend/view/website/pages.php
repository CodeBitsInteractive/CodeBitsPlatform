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
                <div class="container">
                   <div class="row no-margin">
                       <div class="col-md-12 padding-leftright-null">
                           <div id="page-header">
                               <div class="text">
                                   <h1 class="margin-bottom-small"><?=$title?><span class="color">.</span></h1>
                                   <p class="heading left max full grey-dark"><?=$desc?></p>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
                <!-- PAGE HEADER -->
                
                <!-- PAGE SEARCH -->
                <div class="container">
                    <div class="row no-margin">
                        <div class="col-md-12 padding-leftright-null">
                            <form id="contact-form" class="" role="form" action="/pages/" method="GET" style="padding: 0 50px;">
                                <div class="row no-margin">
                                    <div class="col-md-12 padding-leftright-null">
                                        <div class="text small padding-topbottom-null">
                                            <input class="form-field" name="s" id="search" value="<?=(isset($search) && $search!==false && strlen($search)>0)?$search:''?>" type="text" placeholder="<?=$this->lang->line('find_pages')?>" maxlength="50">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PAGE SEARCH -->
                
                <!-- PAGE LIST -->
                <?php if(isset($list) && $list!==false && count($list)>0): ?>
                <div id="home-wrap" class="content-section">
                    <div class="container">
                        <div class="row no-margin wrap-text">
                            <section id="news" class="page">
                                <div class="news-items equal three-columns">
                                    <?php foreach($list as $key=>$page): ?>
                                    <div class="single-news one-item">
                                        <article>
                                            <img src="<?=(isset($page['image']) && strlen($page['image'])>0)?$page['image']:''?>" alt="<?=$page['title']?>">
                                                <div class="content">
                                                    <span class="meta"><?=date("d.m.Y H:i", $page['time'])?></span>
                                                    <h3><?=$page['title']?></h3>
                                                    <p><?=$page['desc']?></p>
                                                    <a href="/pages/view/<?=$page['slug']?>/" class="btn-pro"><?=$this->lang->line('t_more')?></a>
                                                </div>
                                        </article>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                
                <!-- PAGINATION -->
                <?php if(($page<$total && $total>1) || ($page!=1 && $total>1)): ?>
                <div class="container">
                    <div class="row no-margin">
                        <div class="col-md-12 padding-leftright-null">
                            <div style="padding: 50px 50px;">
                                <?php if($page!=1 && $total>1): ?>
                                <a href="/pages/?nav=<?=($page-1)?><?=(isset($search) && $search!==false)?"&s=".$search:""?>" class="btn-alt"><?=$this->lang->line('t_prev')?></a>
                                <?php endif?>
                                <?php if($page<$total && $total>1): ?>
                                <a href="/pages/?nav=<?=($page+1)?><?=(isset($search) && $search!==false)?"&s=".$search:""?>" class="btn-alt"><?=$this->lang->line('t_more')?></a>
                                <?php endif?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- PAGINATION -->
                <?php else: ?>
                <div class="container">
                   <div class="row no-margin">
                       <div class="col-md-12 padding-leftright-null">
                           <div style="padding: 50px 50px;">
                               <div class="text">
                                   <p class="heading left max full grey-dark"><?=$this->lang->line('no_results')?></p>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
                <?php endif; ?>
                <!-- PAGE LIST -->
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