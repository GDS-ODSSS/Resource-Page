<?php if (!defined('IN_PHPMEGATEMP')) exit; ?><header class="header4" id="header">
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