		<form method="post" id="codeform" action="index.php">
        
            <label for="pagetitle">Project Title</label>
            <input type="text" name="projecttitle" id="pagetitle">
            
            <label for="tags">Tags (separate multiple tags with commas)</label>
            <input type="text" name="tags" id="tags">
            
            <fieldset class="codesnippet" id="snippet1">
                
                <h3>Code Snap 1</h3>
            
                <label for="intro1" id="introlabel1">Intro Text</label>
                <textarea id="intro1" name="intro1" cols="70" rows="5"></textarea>
                
                <label for="caption1" id="caplabel1">Caption Text</label>
                <input type="text" id="caption1" name="caption1">
                
                <label><input type="radio" name="lang1" id="default1" value="0" checked> Default</label>
                <label><input type="radio" name="lang1" id="css1" value="1"> CSS</label>
                
                <div class="codecontainer">
                    <label for="code1" id="codelabel1">Code Text</label>
                    <textarea id="code1" name="code1" cols="70" rows="20"></textarea>
                </div>
            
            </fieldset>
            
            
            <input type="hidden" name="mf" value="mf">
            <input type="button" name="addsnippet" id="addsnippet" value="Add A Code Snippet">
            <input type="submit" name="create" value="create file" id="create">
        
        </form>
        
        <section id="formfeedback"></section>