<?php if (!defined('IN_PHPMEGATEMP')) exit; ?><script type="text/javascript">
/* <![CDATA[ */
    var cookie_id = '<?php echo (isset($this->_rootref['THOP_COOKIE_ID'])) ? $this->_rootref['THOP_COOKIE_ID'] : ''; ?>';
    var ajaxRequests = [];
    var ajax_url = '<?php echo (isset($this->_rootref['SITE_URL'])) ? $this->_rootref['SITE_URL'] : ''; ?>/ajax.php';
    <?php if ($this->_rootref['CLASS_HEADER'] == ('jpinning')) {  ?>var is_jpinning = true;<?php } else { ?>var is_jpinning = false;<?php } ?>
/* ]]> */
</script>