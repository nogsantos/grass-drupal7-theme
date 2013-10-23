<?php
/**
 * Arquivo: skip-link
 *
 * @author Fabricio Nogueira
 * @since Oct 23, 2013
 * 
 */
?>
<div class="row" id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
    <?php if ($main_menu): ?>
        <a href="#navigation" class="element-invisible element-focusable"><?php print t('Skip to navigation'); ?></a>
    <?php endif; ?>
</div>