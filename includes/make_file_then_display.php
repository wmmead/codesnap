<?php $lastid = add_project($link); ?>
<?php

$details = get_project_details($link, $lastid);

?>

<p><strong>Project Name:</strong> <?php print $details['title']; ?></p>
<p><strong>Created by:</strong> <?php print $details['user_name']; ?></p>
<p><strong>Created on:</strong> <?php print fix_date($details['mod_date']); ?></p>
<p><strong>File Link:</strong> <?php print SNIPPET_DIR . $details['file_link']; ?></p>
<p><a href="<?php print SNIPPET_DIR . $details['file_link']; ?>">download file</a></p>
<p><button type="submit" onclick="window.open('<?php print 'snippets/' . $details['file_link']; ?>')">Download!</button></p>

<h2>Generated File...</h2>
<iframe src="<?php print SNIPPET_DIR . $details['file_link']; ?>" id="idIframe" style="width:100%"></iframe>
