<?php if (!defined('IN_PHPMEGATEMP')) exit; ?><div class="header-top nopadding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- socials -->
                <div class="social">
                    <ul>
                        <?php if ($this->_rootref['THOP_FACEBOOK']) {  ?><li><a href="<?php echo (isset($this->_rootref['THOP_FACEBOOK'])) ? $this->_rootref['THOP_FACEBOOK'] : ''; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li><?php } if ($this->_rootref['THOP_TWITTER']) {  ?><li><a href="<?php echo (isset($this->_rootref['THOP_TWITTER'])) ? $this->_rootref['THOP_TWITTER'] : ''; ?>" target="_blank"><i class="fab fa-twitter"></i></a></li><?php } if ($this->_rootref['THOP_INSTAGRAM']) {  ?><li><a href="<?php echo (isset($this->_rootref['THOP_INSTAGRAM'])) ? $this->_rootref['THOP_INSTAGRAM'] : ''; ?>" target="_blank"><i class="fab fa-instagram"></i></a></li><?php } if ($this->_rootref['THOP_VIMEO']) {  ?><li><a href="<?php echo (isset($this->_rootref['THOP_VIMEO'])) ? $this->_rootref['THOP_VIMEO'] : ''; ?>" target="_blank"><i class="fab fa-vimeo-v"></i></a></li><?php } if ($this->_rootref['THOP_YOUTUBE']) {  ?><li><a href="<?php echo (isset($this->_rootref['THOP_YOUTUBE'])) ? $this->_rootref['THOP_YOUTUBE'] : ''; ?>" target="_blank"><i class="fab fa-youtube"></i></a></li><?php } if ($this->_rootref['THOP_BEHANCE']) {  ?><li><a href="<?php echo (isset($this->_rootref['THOP_BEHANCE'])) ? $this->_rootref['THOP_BEHANCE'] : ''; ?>" target="_blank"><i class="fab fa-behance"></i></a></li><?php } if ($this->_rootref['THOP_LINKEDIN']) {  ?><li><a href="<?php echo (isset($this->_rootref['THOP_LINKEDIN'])) ? $this->_rootref['THOP_LINKEDIN'] : ''; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a></li><?php } if ($this->_rootref['THOP_PINTEREST']) {  ?><li><a href="<?php echo (isset($this->_rootref['THOP_PINTEREST'])) ? $this->_rootref['THOP_PINTEREST'] : ''; ?>" target="_blank"><i class="fab fa-pinterest"></i></a></li><?php } ?>
                    </ul>
                </div><!-- end socials --><!-- links -->
                <ul class="links">
                    <li><a href="tel:<?php echo (isset($this->_rootref['THOP_PHONE'])) ? $this->_rootref['THOP_PHONE'] : ''; ?>"><i class="fas fa-phone-alt"></i><span><?php echo (isset($this->_rootref['THOP_PHONE'])) ? $this->_rootref['THOP_PHONE'] : ''; ?></span></a></li>
                    <li><a href="mailto:<?php echo (isset($this->_rootref['THOP_EMAIL'])) ? $this->_rootref['THOP_EMAIL'] : ''; ?>"><i class="fas fa-envelope"></i><span><?php echo (isset($this->_rootref['THOP_EMAIL'])) ? $this->_rootref['THOP_EMAIL'] : ''; ?></span></a></li>
                </ul><!-- end links -->
            </div>
        </div>
    </div> 
</div>
<header id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-brand">
                    <a class="logo" href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>">
                        <?php if ($this->_rootref['IS_MOBILE'] && $this->_rootref['THOP_LOGOMOBILE']) {  ?>
                        <img src="<?php echo (isset($this->_rootref['THOP_LOGOMOBILE'])) ? $this->_rootref['THOP_LOGOMOBILE'] : ''; ?>" alt="<?php echo (isset($this->_rootref['SITE_NAME'])) ? $this->_rootref['SITE_NAME'] : ''; ?>">
                        <?php } else { ?>
                        <img src="<?php if ($this->_rootref['THOP_LOGO']) {  echo (isset($this->_rootref['THOP_LOGO'])) ? $this->_rootref['THOP_LOGO'] : ''; } else { echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/images/logo<?php echo (isset($this->_rootref['THOP_LOGOCOLOR'])) ? $this->_rootref['THOP_LOGOCOLOR'] : ''; ?>.png<?php } ?>" alt="<?php echo (isset($this->_rootref['SITE_NAME'])) ? $this->_rootref['SITE_NAME'] : ''; ?>">
                        <?php } ?>
                    </a>
                </div>
                <?php if ($this->_rootref['USERLINK_HEADER']) {  ?>
                <ul class="navuser">
                    <?php if ($this->_rootref['IS_USER']) {  ?>
                    <li>
                        <a href="#"><i class="pe-7s-user"></i> <span><?php echo ((isset($this->_rootref['LANG_HOWDY'])) ? $this->_rootref['LANG_HOWDY'] : ((get_languages('HOWDY')) ? get_languages('HOWDY') : '{ HOWDY }')); ?> <?php echo (isset($this->_rootref['USER_NAME'])) ? $this->_rootref['USER_NAME'] : ''; ?></span></a>
                        <ul class="menu-user">
                            <?php if ($this->_rootref['IS_USER_ADMIN']) {  ?>
                            <li><a href="<?php echo (isset($this->_rootref['PERMALINK_ADMIN_DASHBOARD'])) ? $this->_rootref['PERMALINK_ADMIN_DASHBOARD'] : ''; ?>"><i class="pe-7s-edit"></i> <?php echo ((isset($this->_rootref['LANG_DASHBOARD'])) ? $this->_rootref['LANG_DASHBOARD'] : ((get_languages('DASHBOARD')) ? get_languages('DASHBOARD') : '{ DASHBOARD }')); ?></a></li>
                            <?php } ?>
                            <li><a href="<?php echo (isset($this->_rootref['PERMALINK_USER_PROFILE'])) ? $this->_rootref['PERMALINK_USER_PROFILE'] : ''; ?>"><i class="pe-7s-id"></i>  <?php echo ((isset($this->_rootref['LANG_PROFILE'])) ? $this->_rootref['LANG_PROFILE'] : ((get_languages('PROFILE')) ? get_languages('PROFILE') : '{ PROFILE }')); ?></a></li>
                            <li><a href="<?php echo (isset($this->_rootref['PERMALINK_SIGNOUT'])) ? $this->_rootref['PERMALINK_SIGNOUT'] : ''; ?>" onclick="return confirm('<?php echo ((isset($this->_rootref['LANG_DO_YOU_LOGOUT'])) ? $this->_rootref['LANG_DO_YOU_LOGOUT'] : ((get_languages('DO_YOU_LOGOUT')) ? get_languages('DO_YOU_LOGOUT') : '{ DO_YOU_LOGOUT }')); ?>');"><i class="pe-7s-power"></i>  <?php echo ((isset($this->_rootref['LANG_SIGNOUT'])) ? $this->_rootref['LANG_SIGNOUT'] : ((get_languages('SIGNOUT')) ? get_languages('SIGNOUT') : '{ SIGNOUT }')); ?></a></li>
                        </ul>
                    </li>
                    <?php } else { ?>
                    <li><a href="<?php echo (isset($this->_rootref['PERMALINK_SIGNIN'])) ? $this->_rootref['PERMALINK_SIGNIN'] : ''; ?>" class="button-singin"><i class="pe-7s-unlock"></i><span><?php echo ((isset($this->_rootref['LANG_SIGNIN'])) ? $this->_rootref['LANG_SIGNIN'] : ((get_languages('SIGNIN')) ? get_languages('SIGNIN') : '{ SIGNIN }')); ?></span></a></li>
                   <!--<li><a href="<?php echo (isset($this->_rootref['PERMALINK_SIGNUP'])) ? $this->_rootref['PERMALINK_SIGNUP'] : ''; ?>" class="button-singup"><i class="pe-7s-lock"></i><span><?php echo ((isset($this->_rootref['LANG_SIGNUP'])) ? $this->_rootref['LANG_SIGNUP'] : ((get_languages('SIGNUP')) ? get_languages('SIGNUP') : '{ SIGNUP }')); ?></span></a></li>-->
                    <?php } ?>
                </ul>
                <?php } ?>
                <a class="toggle-switch open_close" href="javascript:void(0);"><span>Menu mobile</span></a>
                <nav class="main-menu menu-icons" id="main-menu">
                    <a href="javascript:void(0);" class="open_close" id="close_in"><i class="pe-7s-close"></i></a>
                    <div id="header_menu">
                        <?php if ($this->_rootref['IS_MOBILE'] && $this->_rootref['THOP_LOGOMOBILE']) {  ?>
                        <img src="<?php echo (isset($this->_rootref['THOP_LOGOMOBILE'])) ? $this->_rootref['THOP_LOGOMOBILE'] : ''; ?>" alt="<?php echo (isset($this->_rootref['SITE_NAME'])) ? $this->_rootref['SITE_NAME'] : ''; ?>">
                        <?php } else { ?>
                        <img src="<?php if ($this->_rootref['THOP_LOGO']) {  echo (isset($this->_rootref['THOP_LOGO'])) ? $this->_rootref['THOP_LOGO'] : ''; } else { echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/images/logo<?php echo (isset($this->_rootref['THOP_LOGOCOLOR'])) ? $this->_rootref['THOP_LOGOCOLOR'] : ''; ?>.png<?php } ?>" alt="<?php echo (isset($this->_rootref['SITE_NAME'])) ? $this->_rootref['SITE_NAME'] : ''; ?>">
                        <?php } ?>
                    </div>
                    <ul>
                        <?php $_loop_main_menu_count = (isset($this->_tpldata['loop_main_menu'])) ? sizeof($this->_tpldata['loop_main_menu']) : 0;if ($_loop_main_menu_count) {for ($_loop_main_menu_i = 0; $_loop_main_menu_i < $_loop_main_menu_count; ++$_loop_main_menu_i){$_loop_main_menu_val = &$this->_tpldata['loop_main_menu'][$_loop_main_menu_i]; ?>
                        <li <?php if ($_loop_main_menu_val['MENU_SUB_COUNT']) {  ?>class="submenu"<?php } ?>>
                            <a href="<?php if ($_loop_main_menu_val['MENU_SUB_COUNT']) {  ?>javascript:void(0);<?php } else { echo $_loop_main_menu_val['MENU_URL']; } ?>" <?php echo $_loop_main_menu_val['URL_TARGET']; ?> <?php if ($_loop_main_menu_val['MENU_SUB_COUNT']) {  ?>class="show-submenu"<?php } ?>>
                                <?php if ($_loop_main_menu_val['MENU_ICON']) {  ?><i class="<?php echo $_loop_main_menu_val['MENU_ICON']; ?>"></i><?php } if ($_loop_main_menu_val['MENU_IMAGE']) {  ?><i><img src="<?php echo $_loop_main_menu_val['MENU_IMAGE']; ?>" /></i><?php } ?> 
                                <?php echo $_loop_main_menu_val['MENU_TITLE']; ?>
                            </a>
                            <?php $_loop_main_menu_sub_count = (isset($_loop_main_menu_val['loop_main_menu_sub'])) ? sizeof($_loop_main_menu_val['loop_main_menu_sub']) : 0;if ($_loop_main_menu_sub_count) {for ($_loop_main_menu_sub_i = 0; $_loop_main_menu_sub_i < $_loop_main_menu_sub_count; ++$_loop_main_menu_sub_i){$_loop_main_menu_sub_val = &$_loop_main_menu_val['loop_main_menu_sub'][$_loop_main_menu_sub_i]; if ($_loop_main_menu_sub_val['SUB_NUM'] == ('1')) {  ?><ul class=""><?php } ?>
                            <li>
                                <a href="<?php echo $_loop_main_menu_sub_val['MENU_URL']; ?>" <?php echo $_loop_main_menu_sub_val['URL_TARGET']; ?>>
                                <?php if ($_loop_main_menu_sub_val['MENU_ICON']) {  ?><i class="<?php echo $_loop_main_menu_sub_val['MENU_ICON']; ?>"></i><?php } if ($_loop_main_menu_sub_val['MENU_IMAGE']) {  ?><i><img src="<?php echo $_loop_main_menu_sub_val['MENU_IMAGE']; ?>" /></i><?php } ?> 
                                <?php echo $_loop_main_menu_sub_val['MENU_TITLE']; ?>
                                </a>
                            </li>
                            <?php if ($_loop_main_menu_sub_val['SUB_NUM'] == $_loop_main_menu_val['MENU_SUB_COUNT']) {  ?></ul><?php } }} ?>
                        </li>
                        <?php }} ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>