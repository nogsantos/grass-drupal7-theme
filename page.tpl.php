<div id="container" class="container">
    
    <?php include_once 'inc/skip-link.php'; ?>
    
    <?php include_once 'inc/header.php'; ?>

    <section id="main" role="main">
        <?php if($messages) : ?>
            <div class="row">
                <?php print $messages; ?>
            </div>
        <?php endif;?>
        <?php if ($page['highlighted']): ?>
            <div class="row" id="highlighted">
                <?php print render($page['highlighted']); ?>
            </div>
        <?php endif; ?>
        <?php print render($title_prefix); ?>
        <?php if ($title): ?>
            <h1 class="title" id="page-title"><?php print $title; ?></h1>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php if (!empty($tabs['#primary'])): ?>
            <div class="tabs-wrapper clearfix">
                <?php print render($tabs); ?>
            </div>
        <?php endif; ?>
        <?php print render($page['help']); ?>
        <?php if ($action_links): ?>
            <ul class="action-links"><?php print render($action_links); ?></ul>
        <?php endif; ?>
        <div class="row">
            <?php if ($page['sidebar_first']): ?>
                <div class="col-lg-4">
                    <?php print render($page['sidebar_first']); ?>
                </div>
            <?php endif; ?>
            <div class="col-lg-4">
                <?php print render($page['content']); ?>
            </div>
            <?php if ($page['sidebar_second']): ?>
                <div class="col-lg-4">
                    <?php print render($page['sidebar_second']); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include_once 'inc/footer.php'; ?>

</div>
