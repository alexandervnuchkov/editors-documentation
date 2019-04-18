<form action="" method="get">
    <div class="formDiv">
        <select name="projectVersion">
            <option <?php if($_GET['projectVersion'] == 'onlyoffice') echo 'selected'; ?> value="onlyoffice">ONLYOFFICE</option>
            <option <?php if($_GET['projectVersion'] == 'r7office') echo 'selected'; ?> value="r7office">Р7 Офис</option>
        </select>
        <select name="editorVersion">
            <option <?php if($_GET['editorVersion'] == 'documenteditor') echo 'selected'; ?> value="documenteditor">Document Editor</option>
            <option <?php if($_GET['editorVersion'] == 'presentationeditor') echo 'selected'; ?> value="presentationeditor">Presentation Editor</option>
            <option <?php if($_GET['editorVersion'] == 'spreadsheeteditor') echo 'selected'; ?> value="spreadsheeteditor">Spreadsheet Editor</option>
        </select>
        <select name="languageList">
            <option <?php if($_GET['languageList'] == 'en') echo 'selected'; ?> value="en">English</option>
            <option <?php if($_GET['languageList'] == 'ru') echo 'selected'; ?> value="ru">Русский</option>
            <option <?php if($_GET['languageList'] == 'de') echo 'selected'; ?> value="de">Deutsch</option>
            <option <?php if($_GET['languageList'] == 'fr') echo 'selected'; ?> value="fr">Français</option>
            <option <?php if($_GET['languageList'] == 'es') echo 'selected'; ?> value="es">Español</option>
        </select>
        <input name="submitEditor" type="submit" value="Start!">
    </div>
</form>