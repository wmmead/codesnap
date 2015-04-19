        <div id="logo">
            <h1><span class="light">CODE</span><span class="semi-bold">snap</span></h1>
            <h2>&lt;personal code library&gt;</h2>
        </div>
        
        <?php
			if( isset( $_POST['mf']) && $_POST['mf']=='mf' )
			{
				include('includes/make_file_then_display.php');
			}
			else
			{
				include('includes/new_project_form.php');
			}
		?>