<?php if (!defined('IN_PHPMEGATEMP')) exit; if ($this->_rootref['IS_HOMEPAGE']) {  } else { $this->_tpl_include('banner_bottom.html'); } ?><!-- footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="copyright">
                    <?php echo (isset($this->_rootref['THOP_COPYRIGHT'])) ? $this->_rootref['THOP_COPYRIGHT'] : ''; ?>
                    <br /> Made with <i class="fa fa-heart"></i> and alot of <i class="fa fa-coffee"></i> in Copenhagen
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end footer -->
</div>
<div class="go-up"><i class="pe-7s-angle-up"></i></div>
<?php if ($this->_rootref['DISPLAY_SHOWDEBUG']) {  ?>
<div class="text-center pt-10 pb-10"><?php echo (isset($this->_rootref['DEBUG_OUTPUT'])) ? $this->_rootref['DEBUG_OUTPUT'] : ''; ?></div>
<?php } ?>
<div id="data-ajax-loader"></div>
<?php $this->_tpl_include('overall_javascript.html'); ?>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/jquery.min.js"></script>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/modernizr.min.js"></script>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/jquery.cookie.js"></script>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/jquery.easing.min.js"></script>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/jQuery.ajaxQueue.min.js"></script>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/owl.carousel.min.js"></script>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/ResizeSensor.min.js"></script>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/theia.sticky.sidebar.min.js"></script>
<?php if ($this->_rootref['IS_USER']) {  ?><script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/users.js"></script><?php } else { ?><script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/signup.js"></script><?php } if ($this->_rootref['CLASS_HEADER'] == ('jpinning')) {  ?><script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/jPinning.min.js"></script><?php } ?>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/custom.js"></script>
<?php echo (isset($this->_rootref['ENQUEUE_SCRIPT'])) ? $this->_rootref['ENQUEUE_SCRIPT'] : ''; echo (isset($this->_rootref['THOP_FOOTERCODE'])) ? $this->_rootref['THOP_FOOTERCODE'] : ''; ?>
<?php if ($this->_rootref['THOP_COOKIE_STATUS']) {  ?>
<script src="<?php echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/js/cookie.min.js"></script>
<div class="cookie-box <?php echo (isset($this->_rootref['THOP_COOKIE_POSITION'])) ? $this->_rootref['THOP_COOKIE_POSITION'] : ''; ?>">
    <div class="cookie-box-inner">
        <img src="<?php if ($this->_rootref['THOP_COOKIE_IMAGE']) {  echo (isset($this->_rootref['THOP_COOKIE_IMAGE'])) ? $this->_rootref['THOP_COOKIE_IMAGE'] : ''; } else { echo (isset($this->_rootref['TEMPLATE_URL'])) ? $this->_rootref['TEMPLATE_URL'] : ''; ?>/assets/images/cookie.svg<?php } ?>" alt="cookie">
        <div class="cookie-content">
            <h3><?php echo (isset($this->_rootref['THOP_COOKIE_TITLE'])) ? $this->_rootref['THOP_COOKIE_TITLE'] : ''; ?></h3>
            <p><?php echo (isset($this->_rootref['THOP_COOKIE_DESC'])) ? $this->_rootref['THOP_COOKIE_DESC'] : ''; ?></p>
            <div class="buttons">
                <button class="button cookie-decline"><?php echo (isset($this->_rootref['THOP_COOKIE_DECLINE'])) ? $this->_rootref['THOP_COOKIE_DECLINE'] : ''; ?></button>
                <button class="button cookie-consent loading"><?php echo (isset($this->_rootref['THOP_COOKIE_CONSENT'])) ? $this->_rootref['THOP_COOKIE_CONSENT'] : ''; ?></button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
</body>
</html>