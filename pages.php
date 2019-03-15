<?php
    error_reporting(E_ALL);
    //ini_set("display_errors", 1);
    
    $json = '';
    $totalPages = '0';
    
    include('shared/backToLink.php');
    
    if(isset($_GET['submitEditor'])) {
        
        $varEditors = $_GET['editorVersion'];
        $languageLocale = $_GET['languageList'];

        $editorList = ['Document Editor', 'Presentation Editor', 'Spreadsheet Editor'];
        $editorAvatarList = ['documenteditor', 'presentationeditor', 'spreadsheeteditor'];
        $languageList = ['English', 'Русский', 'Deutsch', 'Français', 'Español'];
        $languageAvatarList = ['en', 'ru', 'de', 'fr', 'es'];
        
        $lALLength = count($languageAvatarList);
        $eALLength = count($editorAvatarList);
        
        for ($i = 0; $i < $lALLength; $i++) {
            if($languageAvatarList[$i] == $languageLocale) {
                $languagePrint = $languageList[$i];
            }
        }
        for ($i = 0; $i < $eALLength; $i++) {
            if($editorAvatarList[$i] == $varEditors) {
                $editorsPrint = $editorList[$i];
            }
        }

        echo '<p>Editor: <b>' . $editorsPrint . '</b>, language: <b>' . $languagePrint . '</b></p>';
        echo '<p>___________________________________________</p>';

        echo '<div class="menu_left">';
    
        $url = 'OfficeWeb/apps/' . $varEditors. '/main/resources/help/' . $languageLocale. '/Contents.json';
        $jsonFile = file_get_contents($url);
        
        if(substr($jsonFile, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) $jsonFile = substr($jsonFile, 3);
        $contents = json_decode($jsonFile, true);
        foreach($contents as $item) {
            $header = $item['headername'];
            $link = $item['src'];
            $title = $item['name'];
           
            if($header != '') {
                echo '</ul><h2>' . $header . '</h2><ul>';
            }
            
            echo '<li><a href="OfficeWeb/apps/' . $varEditors. '/main/resources/help/' . $languageLocale. '/' . $link . '">' . $title . '</a></li>';
        }
        echo '</div>';
        echo '<p></p><p>===========================================</p><p><b>Contents.json</b> (file path: <u>\\\\vnuchkov10\sites\documenteditors\OfficeWeb\apps\\' . $varEditors. '\main\resources\help\\' . $languageLocale. '\Contents.json</u>):</p>';

    }
?>
<html>
<head>
    <title>Build table of contents from Contents.json</title>
    <?php
        include('shared/headLinks.php');
    ?>
</head>
<body id="pages">
    <h1>Build table of contents from Contents.json</h1>
    <?php
        include('shared/form.php');
        if($_GET['languageList'] == '') echo '<p>Select the editor and its language from the list and click the <b>Start!</b> button.</p><p>The list of all the pages included into the <b>Contents.json</b> file will be formed.</p>';
    ?>
    <p id="back-top" style="display: none">
        <a title="Scroll up" href="#top"></a>
    </p>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/arrowup.min.js"></script>
</body>
</html>