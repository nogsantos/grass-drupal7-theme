<?php
/**
 * Arquivo: header
 *
 * @author Fabricio Nogueira
 * @since Oct 23, 2013
 * 
 */
?>
<header id="header" role="banner" class="clearfix">
    <div class="row">
        <div class="col-md-4">
            <?php if (@$logo): ?>
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" id="logo">
                    <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
                </a>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <?php if (@$banner): ?>
                <?php print render($page['banner']); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <?php print @render($page['header']); ?>
    </div>
    <div class="row">
        <?php if ($main_menu || $secondary_menu || !empty($page['navigation'])): ?>
            <nav id="navigation" role="navigation" class="clearfix">
                <?php if (!empty($page['navigation'])): ?>
                    <?php print render($page['navigation']); ?>
                <?php endif; ?>
                <?php if (empty($page['navigation'])): ?>
                    <?php
                    print theme('links__system_main_menu', array(
                        'links' => $main_menu,
                        'attributes' => array(
                            'id' => 'main-menu',
                            'class' => array('links', 'clearfix'),
                        ),
                        'heading' => array(
                            'text' => t('Main menu'),
                            'level' => 'h2',
                            'class' => array('element-invisible'),
                        ),
                    ));
                    ?>
                    <?php
                    print theme('links__system_secondary_menu', array(
                        'links' => $secondary_menu,
                        'attributes' => array(
                            'id' => 'secondary-menu',
                            'class' => array('links', 'clearfix'),
                        ),
                        'heading' => array(
                            'text' => t('Secondary menu'),
                            'level' => 'h2',
                            'class' => array('element-invisible'),
                        ),
                    ));
                    ?>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if ($breadcrumb): print $breadcrumb; endif; ?>
    </div>
</header>
