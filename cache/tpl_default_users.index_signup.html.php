<?php if (!defined('IN_PHPMEGATEMP')) exit; $this->_tpl_include('overall_header.html'); ?>
<section class="parallax-window" id="short">
    <div id="sub_header">
        <div class="container" id="sub_content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo ((isset($this->_rootref['LANG_SIGNUP'])) ? $this->_rootref['LANG_SIGNUP'] : ((get_languages('SIGNUP')) ? get_languages('SIGNUP') : '{ SIGNUP }')); ?></h1>
                    <div class="bread-crums">
                        <a href="<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_HOME'])) ? $this->_rootref['LANG_HOME'] : ((get_languages('HOME')) ? get_languages('HOME') : '{ HOME }')); ?></a>
                        <span class="bread-crums-span">&raquo;</span>
                        <span class="current"><?php echo ((isset($this->_rootref['LANG_SIGNUP'])) ? $this->_rootref['LANG_SIGNUP'] : ((get_languages('SIGNUP')) ? get_languages('SIGNUP') : '{ SIGNUP }')); ?></span>
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
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="mb-0 text-center"><?php echo ((isset($this->_rootref['LANG_SIGNUP'])) ? $this->_rootref['LANG_SIGNUP'] : ((get_languages('SIGNUP')) ? get_languages('SIGNUP') : '{ SIGNUP }')); ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if ($this->_rootref['IS_NOT_RES_USERS']) {  ?>
                        <form id="form-signup" action="<?php echo (isset($this->_rootref['PERMALINK_SIGNUP'])) ? $this->_rootref['PERMALINK_SIGNUP'] : ''; ?>" method="post">
                            <input type="hidden" name="action" value="create_account"/>
                            <input type="hidden" name="token" value="<?php echo (isset($this->_rootref['TOKEN'])) ? $this->_rootref['TOKEN'] : ''; ?>" />
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname"><?php echo ((isset($this->_rootref['LANG_FIRSTNAME'])) ? $this->_rootref['LANG_FIRSTNAME'] : ((get_languages('FIRSTNAME')) ? get_languages('FIRSTNAME') : '{ FIRSTNAME }')); ?></label>
                                        <input class="form-control" type="text" name="firstname" id="firstname" autofocus="" placeholder="<?php echo ((isset($this->_rootref['LANG_FIRSTNAME'])) ? $this->_rootref['LANG_FIRSTNAME'] : ((get_languages('FIRSTNAME')) ? get_languages('FIRSTNAME') : '{ FIRSTNAME }')); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname"><?php echo ((isset($this->_rootref['LANG_LASTNAME'])) ? $this->_rootref['LANG_LASTNAME'] : ((get_languages('LASTNAME')) ? get_languages('LASTNAME') : '{ LASTNAME }')); ?></label>
                                        <input class="form-control" type="text" name="lastname" id="lastname" placeholder="<?php echo ((isset($this->_rootref['LANG_LASTNAME'])) ? $this->_rootref['LANG_LASTNAME'] : ((get_languages('LASTNAME')) ? get_languages('LASTNAME') : '{ LASTNAME }')); ?>">
                                    </div>
                                </div>
                            </div>  
                            <div class="form-group">
                                <label for="username"><?php echo ((isset($this->_rootref['LANG_USERNAME'])) ? $this->_rootref['LANG_USERNAME'] : ((get_languages('USERNAME')) ? get_languages('USERNAME') : '{ USERNAME }')); ?></label>
                                <input class="form-control" type="text" name="username" id="username" placeholder="<?php echo ((isset($this->_rootref['LANG_USERNAME'])) ? $this->_rootref['LANG_USERNAME'] : ((get_languages('USERNAME')) ? get_languages('USERNAME') : '{ USERNAME }')); ?>" maxlength="25">
                            </div>
                            <div class="form-group">
                                <label for="email"><?php echo ((isset($this->_rootref['LANG_EMAIL_ADDRESS'])) ? $this->_rootref['LANG_EMAIL_ADDRESS'] : ((get_languages('EMAIL_ADDRESS')) ? get_languages('EMAIL_ADDRESS') : '{ EMAIL_ADDRESS }')); ?></label>
                                <input class="form-control" type="email" name="email" id="email" value="" autocomplete="email" placeholder="<?php echo ((isset($this->_rootref['LANG_EMAIL_ADDRESS'])) ? $this->_rootref['LANG_EMAIL_ADDRESS'] : ((get_languages('EMAIL_ADDRESS')) ? get_languages('EMAIL_ADDRESS') : '{ EMAIL_ADDRESS }')); ?>">
                            </div>
                            <div class="form-group">
                                <label for="username"><?php echo ((isset($this->_rootref['LANG_PASSWORD'])) ? $this->_rootref['LANG_PASSWORD'] : ((get_languages('PASSWORD')) ? get_languages('PASSWORD') : '{ PASSWORD }')); ?></label>
                                <input class="form-control" type="password" name="password" id="password" placeholder="<?php echo ((isset($this->_rootref['LANG_PASSWORD'])) ? $this->_rootref['LANG_PASSWORD'] : ((get_languages('PASSWORD')) ? get_languages('PASSWORD') : '{ PASSWORD }')); ?>">
                            </div>
                            <div class="form-group">
                                <label for="confirmpassword"><?php echo ((isset($this->_rootref['LANG_CONFIRM_PASSWORD'])) ? $this->_rootref['LANG_CONFIRM_PASSWORD'] : ((get_languages('CONFIRM_PASSWORD')) ? get_languages('CONFIRM_PASSWORD') : '{ CONFIRM_PASSWORD }')); ?></label>
                                <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" placeholder="<?php echo ((isset($this->_rootref['LANG_CONFIRM_PASSWORD'])) ? $this->_rootref['LANG_CONFIRM_PASSWORD'] : ((get_languages('CONFIRM_PASSWORD')) ? get_languages('CONFIRM_PASSWORD') : '{ CONFIRM_PASSWORD }')); ?>">
                            </div>
                            <div class="form-group text-center">
                                <button class="button btn-block" id="button-signup-submit" type="submit"> <?php echo ((isset($this->_rootref['LANG_CREATE_ACCOUNT'])) ? $this->_rootref['LANG_CREATE_ACCOUNT'] : ((get_languages('CREATE_ACCOUNT')) ? get_languages('CREATE_ACCOUNT') : '{ CREATE_ACCOUNT }')); ?> </button>
                            </div>
                        </form>
                        <?php } else { ?>
                        <div class="form-messeges warning">
                            <div class="messege-warp">
                                <span class="messege-text"><?php echo ((isset($this->_rootref['LANG_REGISTRATION_IS_CLOSED'])) ? $this->_rootref['LANG_REGISTRATION_IS_CLOSED'] : ((get_languages('REGISTRATION_IS_CLOSED')) ? get_languages('REGISTRATION_IS_CLOSED') : '{ REGISTRATION_IS_CLOSED }')); ?></span>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="text-center"><?php echo ((isset($this->_rootref['LANG_IF_YOU_ALREADY_HAVE_AN_ACCOUNT'])) ? $this->_rootref['LANG_IF_YOU_ALREADY_HAVE_AN_ACCOUNT'] : ((get_languages('IF_YOU_ALREADY_HAVE_AN_ACCOUNT')) ? get_languages('IF_YOU_ALREADY_HAVE_AN_ACCOUNT') : '{ IF_YOU_ALREADY_HAVE_AN_ACCOUNT }')); ?> &nbsp;<a href="<?php echo (isset($this->_rootref['PERMALINK_SIGNIN'])) ? $this->_rootref['PERMALINK_SIGNIN'] : ''; ?>"><?php echo ((isset($this->_rootref['LANG_SIGNIN'])) ? $this->_rootref['LANG_SIGNIN'] : ((get_languages('SIGNIN')) ? get_languages('SIGNIN') : '{ SIGNIN }')); ?></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->_tpl_include('overall_footer.html'); ?>