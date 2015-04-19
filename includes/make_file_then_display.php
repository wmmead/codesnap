<?php $lastid = add_project($link); ?>
<?php

$details = get_project_details($link, $lastid);

?>

<div>
	<p>Project Name: <?php print $details['title']; ?></p>
    <p>Created by: <?php print $details['user_name']; ?></p>
    <p>Created on: <?php print fix_date($details['mod_date']); ?></p>
    <p>File Link: <?php print SNIPPET_DIR . $details['file_link']; ?></p>
    <p><a href="<?php print SNIPPET_DIR . $details['file_link']; ?>">download file</a></p>
</div>

<iframe src="<?php print SNIPPET_DIR . $details['file_link']; ?>" id="idIframe" style="width:100%"></iframe>
