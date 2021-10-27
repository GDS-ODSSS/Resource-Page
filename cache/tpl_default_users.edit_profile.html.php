<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); ?>
<section class="parallax-window" id="short">
    <div id="sub_header">
        <div class="container" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo ((isset($this->_rootref['LANG_PROFILE'])) ? $this->_rootref['LANG_PROFILE'] : ((get_languages('PROFILE')) ? get_languages('PROFILE') : '{ PROFILE }')); ?></h1>
                    <div class="bread-crums">
                        <a href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_HOME'])) ? $this->_rootref['LANG_HOME'] : ((get_languages('HOME')) ? get_languages('HOME') : '{ HOME }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current"><?php echo ((isset($this->_rootref['LANG_PROFILE'])) ? $this->_rootref['LANG_PROFILE'] : ((get_languages('PROFILE')) ? get_languages('PROFILE') : '{ PROFILE }')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('banner_top.html'); ?>
<section class="padding_60">
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="card profile-nav-tabs">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 mb-2 mb-sm-0">
                                <div class="avarar">
                                    <img src="<?php echo (isset($this->_rootref['USER_AVATER'])) ? $this->_rootref['USER_AVATER'] : ''; ?>" />
                                </div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="pe-7s-id"></i> <?php echo ((isset($this->_rootref['LANG_PROFILE'])) ? $this->_rootref['LANG_PROFILE'] : ((get_languages('PROFILE')) ? get_languages('PROFILE') : '{ PROFILE }')); ?></a></li>
                                    <li role="presentation" class=""><a href="#changepassword" aria-controls="changepassword" role="tab" data-toggle="tab"><i class="pe-7s-lock"></i> <?php echo ((isset($this->_rootref['LANG_CHANGE_PASSWORD'])) ? $this->_rootref['LANG_CHANGE_PASSWORD'] : ((get_languages('CHANGE_PASSWORD')) ? get_languages('CHANGE_PASSWORD') : '{ CHANGE_PASSWORD }')); ?></a></li>
                                    <li><a class="logout" href="<?php echo (isset($this->_rootref['PERMALINK_SIGNOUT'])) ? $this->_rootref['PERMALINK_SIGNOUT'] : ''; ?>" onclick="return confirm('<?php echo ((isset($this->_rootref['LANG_DO_YOU_LOGOUT'])) ? $this->_rootref['LANG_DO_YOU_LOGOUT'] : ((get_languages('DO_YOU_LOGOUT')) ? get_languages('DO_YOU_LOGOUT') : '{ DO_YOU_LOGOUT }')); ?>');"><i class="pe-7s-power"></i>  <?php echo ((isset($this->_rootref['LANG_SIGNOUT'])) ? $this->_rootref['LANG_SIGNOUT'] : ((get_languages('SIGNOUT')) ? get_languages('SIGNOUT') : '{ SIGNOUT }')); ?></a></li>
                                </ul>
                            </div> <!-- end col-->
                            <div class="col-sm-9">
                                <?php if ($this->_rootref['USER_STATUS']) {  ?>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="profile">
                                        <h3><?php echo ((isset($this->_rootref['LANG_PROFILE'])) ? $this->_rootref['LANG_PROFILE'] : ((get_languages('PROFILE')) ? get_languages('PROFILE') : '{ PROFILE }')); ?></h3>
                    	                <p><?php echo ((isset($this->_rootref['LANG_HERE_YOU_CAN_MAKE_CHANGES_TO_YOUR_PROFILE'])) ? $this->_rootref['LANG_HERE_YOU_CAN_MAKE_CHANGES_TO_YOUR_PROFILE'] : ((get_languages('HERE_YOU_CAN_MAKE_CHANGES_TO_YOUR_PROFILE')) ? get_languages('HERE_YOU_CAN_MAKE_CHANGES_TO_YOUR_PROFILE') : '{ HERE_YOU_CAN_MAKE_CHANGES_TO_YOUR_PROFILE }')); ?></p>
                                        <form id="form-update-profile" action="<?php echo (isset($this->_rootref['PERMALINK_PROFILE'])) ? $this->_rootref['PERMALINK_PROFILE'] : ''; ?>" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="action" value="update_profile"/>
                                            <input type="hidden" name="token" value="<?php echo (isset($this->_rootref['TOKEN'])) ? $this->_rootref['TOKEN'] : ''; ?>" />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="firstname"><?php echo ((isset($this->_rootref['LANG_FIRSTNAME'])) ? $this->_rootref['LANG_FIRSTNAME'] : ((get_languages('FIRSTNAME')) ? get_languages('FIRSTNAME') : '{ FIRSTNAME }')); ?></label>
                                                        <input class="form-control" name="firstname" type="text" id="firstname" placeholder="<?php echo ((isset($this->_rootref['LANG_FIRSTNAME'])) ? $this->_rootref['LANG_FIRSTNAME'] : ((get_languages('FIRSTNAME')) ? get_languages('FIRSTNAME') : '{ FIRSTNAME }')); ?>" value="<?php echo (isset($this->_rootref['USER_FNAME'])) ? $this->_rootref['USER_FNAME'] : ''; ?>" required="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fullname"><?php echo ((isset($this->_rootref['LANG_LASTNAME'])) ? $this->_rootref['LANG_LASTNAME'] : ((get_languages('LASTNAME')) ? get_languages('LASTNAME') : '{ LASTNAME }')); ?></label>
                                                        <input class="form-control" name="lastname" type="text" id="lastname" placeholder="<?php echo ((isset($this->_rootref['LANG_LASTNAME'])) ? $this->_rootref['LANG_LASTNAME'] : ((get_languages('LASTNAME')) ? get_languages('LASTNAME') : '{ LASTNAME }')); ?>" value="<?php echo (isset($this->_rootref['USER_LNAME'])) ? $this->_rootref['USER_LNAME'] : ''; ?>" required="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="username"><?php echo ((isset($this->_rootref['LANG_USERNAME'])) ? $this->_rootref['LANG_USERNAME'] : ((get_languages('USERNAME')) ? get_languages('USERNAME') : '{ USERNAME }')); ?></label>
                                                        <input class="form-control" name="username" type="text" id="username" placeholder="<?php echo ((isset($this->_rootref['LANG_USERNAME'])) ? $this->_rootref['LANG_USERNAME'] : ((get_languages('USERNAME')) ? get_languages('USERNAME') : '{ USERNAME }')); ?>" value="<?php echo (isset($this->_rootref['USER_NAME'])) ? $this->_rootref['USER_NAME'] : ''; ?>" required="" maxlength="25" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email"><?php echo ((isset($this->_rootref['LANG_EMAIL_ADDRESS'])) ? $this->_rootref['LANG_EMAIL_ADDRESS'] : ((get_languages('EMAIL_ADDRESS')) ? get_languages('EMAIL_ADDRESS') : '{ EMAIL_ADDRESS }')); ?></label>
                                                        <input class="form-control" name="email" type="email" id="email" placeholder="<?php echo ((isset($this->_rootref['LANG_EMAIL_ADDRESS'])) ? $this->_rootref['LANG_EMAIL_ADDRESS'] : ((get_languages('EMAIL_ADDRESS')) ? get_languages('EMAIL_ADDRESS') : '{ EMAIL_ADDRESS }')); ?>" value="<?php echo (isset($this->_rootref['USER_EMAIL'])) ? $this->_rootref['USER_EMAIL'] : ''; ?>" required="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <button type="button" id="button-update-profile-submit" class="button"><span></span> <?php echo ((isset($this->_rootref['LANG_UPDATE_PROFILE'])) ? $this->_rootref['LANG_UPDATE_PROFILE'] : ((get_languages('UPDATE_PROFILE')) ? get_languages('UPDATE_PROFILE') : '{ UPDATE_PROFILE }')); ?> </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="changepassword">
                                        <h3><?php echo ((isset($this->_rootref['LANG_CHANGE_PASSWORD'])) ? $this->_rootref['LANG_CHANGE_PASSWORD'] : ((get_languages('CHANGE_PASSWORD')) ? get_languages('CHANGE_PASSWORD') : '{ CHANGE_PASSWORD }')); ?></h3>
                    	                <p><?php echo ((isset($this->_rootref['LANG_INFO_CHANGE_PASSWORD'])) ? $this->_rootref['LANG_INFO_CHANGE_PASSWORD'] : ((get_languages('INFO_CHANGE_PASSWORD')) ? get_languages('INFO_CHANGE_PASSWORD') : '{ INFO_CHANGE_PASSWORD }')); ?></p>
                                        <form id="form-update-password" action="<?php echo (isset($this->_rootref['PERMALINK_PROFILE'])) ? $this->_rootref['PERMALINK_PROFILE'] : ''; ?>" method="post">
                                            <input type="hidden" name="action" value="update_password"/>
                                            <input type="hidden" name="token" value="<?php echo (isset($this->_rootref['TOKEN'])) ? $this->_rootref['TOKEN'] : ''; ?>" />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password"><?php echo ((isset($this->_rootref['LANG_PASSWORD'])) ? $this->_rootref['LANG_PASSWORD'] : ((get_languages('PASSWORD')) ? get_languages('PASSWORD') : '{ PASSWORD }')); ?></label>
                                                        <input class="form-control" type="password" placeholder="<?php echo ((isset($this->_rootref['LANG_PASSWORD'])) ? $this->_rootref['LANG_PASSWORD'] : ((get_languages('PASSWORD')) ? get_languages('PASSWORD') : '{ PASSWORD }')); ?>" name="password" id="password" required="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="confirmpassword"><?php echo ((isset($this->_rootref['LANG_CONFIRM_PASSWORD'])) ? $this->_rootref['LANG_CONFIRM_PASSWORD'] : ((get_languages('CONFIRM_PASSWORD')) ? get_languages('CONFIRM_PASSWORD') : '{ CONFIRM_PASSWORD }')); ?></label>
                                                        <input class="form-control" type="password" placeholder="<?php echo ((isset($this->_rootref['LANG_CONFIRM_PASSWORD'])) ? $this->_rootref['LANG_CONFIRM_PASSWORD'] : ((get_languages('CONFIRM_PASSWORD')) ? get_languages('CONFIRM_PASSWORD') : '{ CONFIRM_PASSWORD }')); ?>" name="confirmpassword" id="confirmpassword" required="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <button type="button" id="button-update-password-submit" class="button"><span></span> <?php echo ((isset($this->_rootref['LANG_UPDATE_PASSWORD'])) ? $this->_rootref['LANG_UPDATE_PASSWORD'] : ((get_languages('UPDATE_PASSWORD')) ? get_languages('UPDATE_PASSWORD') : '{ UPDATE_PASSWORD }')); ?> </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="activate-account-notes"><?php echo ((isset($this->_rootref['LANG_PLEASE_ACTIVATE_YOUR_ACCOUNT'])) ? $this->_rootref['LANG_PLEASE_ACTIVATE_YOUR_ACCOUNT'] : ((get_languages('PLEASE_ACTIVATE_YOUR_ACCOUNT')) ? get_languages('PLEASE_ACTIVATE_YOUR_ACCOUNT') : '{ PLEASE_ACTIVATE_YOUR_ACCOUNT }')); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('overall_footer.html'); ?>