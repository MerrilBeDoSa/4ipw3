<?php





/**
 * build <body>
 * @param $user
 * @param $role
 */
function html_body()
{
	ob_start();
	?>
    <h2>
        HOME
    </h2>
    <p>
        Ceci est notre site
    </p>
    <?php
	return ob_get_clean();
}

