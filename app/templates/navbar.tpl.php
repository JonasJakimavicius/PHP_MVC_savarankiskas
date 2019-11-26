<?php if (isset($data) && !empty($data)): ?>
    <nav <?php print html_attr($data['attr']); ?>>


        <?php if (isset ($data['logo'])): ?>
            <a <?php print html_attr($data['logo']['attr']); ?>></a>
        <?php endif; ?>

        <?php if (isset($data['toggler'])): ?>
            <button <?php print html_attr($data['toggler']['attr']); ?>>
                <?php if (isset($data['toggler-icon'])): ?>
                    <span <?php print html_attr($data['toggler-icon']['attr']); ?>></span>
                <?php endif; ?>
            </button>
        <?php endif; ?>

        <?php if (isset($data['nav-items-container'])): ?>
        <div <?php print html_attr($data['nav-items-container']['attr']) ?>>
            <?php endif; ?>

            <?php if (isset($data['nav-items'])): ?>
            <ul <?php print html_attr($data['nav-items']['attr']) ?>>
                <?php endif; ?>


                <?php foreach ($data['nav-item-array'] as $nav_item_id => $nav_item): ?>

                    <li <?php print html_attr($nav_item['attr']); ?>>
                        <a <?php print html_attr($nav_item['nav-link']['attr']); ?>><?php print $nav_item_id; ?></a>
                    </li>


                <?php endforeach; ?>

                <?php if (isset($data['nav-items'])): ?>
            </ul>
        <?php endif; ?>

            <?php if (isset($data['form'])): ?>
                <form <?php print html_attr($data['form']['attr']); ?>>
                    <input <?php print html_attr($data['form']['field']['attr']); ?>>
                    <button
                        <?php print  html_attr($data['form']['buttons']['search']['attr']);?>>
                        Search
                    </button>
                </form>
            <?php endif; ?>


            <?php if (isset($data['nav-items-container'])): ?>
        </div>
    <?php endif; ?>
    </nav>
<?php endif; ?>