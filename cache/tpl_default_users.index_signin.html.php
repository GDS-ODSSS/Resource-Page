<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); ?>

<section class="parallax-window" id="short">
    <div id="sub_header">
        <div class="container" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo ((isset($this->_rootref['LANG_ACCOUNT_LOGIN'])) ? $this->_rootref['LANG_ACCOUNT_LOGIN'] : ((get_languages('ACCOUNT_LOGIN')) ? get_languages('ACCOUNT_LOGIN') : '{ ACCOUNT_LOGIN }')); ?></h1>
                    <div class="bread-crums">
                        <a href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_HOME'])) ? $this->_rootref['LANG_HOME'] : ((get_languages('HOME')) ? get_languages('HOME') : '{ HOME }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current"><?php echo ((isset($this->_rootref['LANG_ACCOUNT_LOGIN'])) ? $this->_rootref['LANG_ACCOUNT_LOGIN'] : ((get_languages('ACCOUNT_LOGIN')) ? get_languages('ACCOUNT_LOGIN') : '{ ACCOUNT_LOGIN }')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('banner_top.html'); ?>
<section>
    <div class="container padding_80">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="mb-0 text-center"><?php echo ((isset($this->_rootref['LANG_ACCOUNT_LOGIN'])) ? $this->_rootref['LANG_ACCOUNT_LOGIN'] : ((get_languages('ACCOUNT_LOGIN')) ? get_languages('ACCOUNT_LOGIN') : '{ ACCOUNT_LOGIN }')); ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if ($this->_rootref['IS_SIGNOUT']) {  ?>
                        <div class="form-messeges warning">
                            <div class="messege-warp">
                                <span class="messege-text"><i class="fa fa-check"></i> <?php echo ((isset($this->_rootref['LANG_LOGGED_OUT'])) ? $this->_rootref['LANG_LOGGED_OUT'] : ((get_languages('LOGGED_OUT')) ? get_languages('LOGGED_OUT') : '{ LOGGED_OUT }')); ?></span>
                            </div>
                        </div>
                        <?php } else if ($this->_rootref['IS_FIELDLOGIN']) {  ?>
                        <div class="form-messeges error">
                            <div class="messege-warp">
                                <span class="messege-text"><i class="fa fa-exclamation-circle"></i> <?php echo ((isset($this->_rootref['LANG_PLEASE_COMPLETE_THE_FIELDS'])) ? $this->_rootref['LANG_PLEASE_COMPLETE_THE_FIELDS'] : ((get_languages('PLEASE_COMPLETE_THE_FIELDS')) ? get_languages('PLEASE_COMPLETE_THE_FIELDS') : '{ PLEASE_COMPLETE_THE_FIELDS }')); ?></span>
                            </div>
                        </div>
                        <?php } else if ($this->_rootref['IS_NOTLOGIN']) {  ?>
                        <div class="form-messeges error">
                            <div class="messege-warp">
                                <span class="messege-text"><i class="fa fa-error"></i><i class="fa fa-exclamation-circle"></i> <?php echo ((isset($this->_rootref['LANG_EMAIL_OR_PASSWORD_IS_INCORRECT'])) ? $this->_rootref['LANG_EMAIL_OR_PASSWORD_IS_INCORRECT'] : ((get_languages('EMAIL_OR_PASSWORD_IS_INCORRECT')) ? get_languages('EMAIL_OR_PASSWORD_IS_INCORRECT') : '{ EMAIL_OR_PASSWORD_IS_INCORRECT }')); ?></span>
                            </div>
                        </div>
                        <?php } ?>
                        <form name="loginform" id="loginform" role="form" action="<?php echo (isset($this->_rootref['PERMALINK_SIGNIN'])) ? $this->_rootref['PERMALINK_SIGNIN'] : ''; ?>" method="post">
                            <input type="hidden" name="action" value="signin" />
                            <input type="hidden" name="token" value="<?php echo (isset($this->_rootref['TOKEN'])) ? $this->_rootref['TOKEN'] : ''; ?>" />
                            <div class="form-group">
                                <label for="emailaddress"><?php echo ((isset($this->_rootref['LANG_EMAIL_ADDRESS'])) ? $this->_rootref['LANG_EMAIL_ADDRESS'] : ((get_languages('EMAIL_ADDRESS')) ? get_languages('EMAIL_ADDRESS') : '{ EMAIL_ADDRESS }')); ?></label>
                                <input class="form-control" type="email" name="email" id="emailaddress" required="" autocomplete="email" autofocus="" placeholder="<?php echo ((isset($this->_rootref['LANG_EMAIL_ADDRESS'])) ? $this->_rootref['LANG_EMAIL_ADDRESS'] : ((get_languages('EMAIL_ADDRESS')) ? get_languages('EMAIL_ADDRESS') : '{ EMAIL_ADDRESS }')); ?>">
                            </div>
                            <div class="form-group">
                                <a href="<?php echo (isset($this->_rootref['PERMALINK_FORGOT'])) ? $this->_rootref['PERMALINK_FORGOT'] : ''; ?>" class="forgot float-right"><?php echo ((isset($this->_rootref['LANG_FORGOT_PASSWORD'])) ? $this->_rootref['LANG_FORGOT_PASSWORD'] : ((get_languages('FORGOT_PASSWORD')) ? get_languages('FORGOT_PASSWORD') : '{ FORGOT_PASSWORD }')); ?></a>
                                <label for="password"><?php echo ((isset($this->_rootref['LANG_PASSWORD'])) ? $this->_rootref['LANG_PASSWORD'] : ((get_languages('PASSWORD')) ? get_languages('PASSWORD') : '{ PASSWORD }')); ?></label>
                                <input class="form-control" type="password" name="password" required="" autocomplete="current-password" id="password" placeholder="<?php echo ((isset($this->_rootref['LANG_PASSWORD'])) ? $this->_rootref['LANG_PASSWORD'] : ((get_languages('PASSWORD')) ? get_languages('PASSWORD') : '{ PASSWORD }')); ?>">
                            </div>
                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkbox-signin" name="autologin">
                                    <label class="custom-control-label" for="checkbox-signin">Remember Me</label>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="button btn-block" type="submit"> <?php echo ((isset($this->_rootref['LANG_LOGIN'])) ? $this->_rootref['LANG_LOGIN'] : ((get_languages('LOGIN')) ? get_languages('LOGIN') : '{ LOGIN }')); ?> </button>
                            </div>
                        </form>
                        <div class="text-center"><?php echo ((isset($this->_rootref['LANG_IF_YOU_DONT_HAVE_ACCOUNT'])) ? $this->_rootref['LANG_IF_YOU_DONT_HAVE_ACCOUNT'] : ((get_languages('IF_YOU_DONT_HAVE_ACCOUNT')) ? get_languages('IF_YOU_DONT_HAVE_ACCOUNT') : '{ IF_YOU_DONT_HAVE_ACCOUNT }')); ?> &nbsp;<a href="<?php echo (isset($this->_rootref['PERMALINK_SIGNUP'])) ? $this->_rootref['PERMALINK_SIGNUP'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_SIGNUP'])) ? $this->_rootref['LANG_SIGNUP'] : ((get_languages('SIGNUP')) ? get_languages('SIGNUP') : '{ SIGNUP }')); ?></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('overall_footer.html'); ?>