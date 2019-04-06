<?php
    include('shared/simple_html_dom.php');
    error_reporting(E_ALL);
    //ini_set("display_errors", 1);
    
    function siteURL()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'].'/';
        return $protocol.$domainName;
    }
    define( 'SITE_URL', siteURL() );

    $json = '';
    $totalPages = '0';
    
    include('shared/backToLink.php');

    if(isset($_GET['submitEditor'])) {
        $varEditors = $_GET['editorVersion'];
        $languageLocale = $_GET['languageList'];

        $pages = array_map(null, glob('OfficeWeb/apps/' . $varEditors. '/main/resources/help/' . $languageLocale. '/*/*.htm'));
        
        if(count($pages) != 0) {
            
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
            echo '<p>Try if it works correctly: <a target="_blank" href="' . SITE_URL . 'OfficeWeb/apps/' . $varEditors. '/main/resources/help/' . $languageLocale. '/search/search.html?query=document">' . SITE_URL . 'OfficeWeb/apps/' . $varEditors. '/main/resources/help/' . $languageLocale. '/search/search.html?query=document</a></p>';
            echo '<p><a class="indexesjs_link" onclick="javascript: $(\'html, body\').animate({ \'scrollTop\': $(\'#indexesjs\').offset().top - 50}, 500)">Go to indexes.js file</a></p>';
            echo '<p>___________________________________________</p>';

            foreach($pages as $url) {
                $page_link_for_array = '';
                $title_for_array = '';
                $body_for_array = '';
                $html = '';
                
                $page_link = $url;
                
                $page_link = str_replace('OfficeWeb/apps/' . $varEditors. '/main/resources/help/' . $languageLocale. '/',"",$page_link);
                $page_link_for_array = '"id": "' . $page_link . '", ';
                
                $html = file_get_html($url);
                
                $titleTag = $html->find('title');
                foreach($titleTag as $titleValue) {
                    $title = $titleValue->plaintext;
                    $title = str_replace("\\","\\\\",$title);
                    $title = str_replace('"','\"',$title);
                    $title_for_array = '"title": "' . $title . '", ';
                }
                $bodyTag = $html->find('body');
                foreach($bodyTag as $bodyValue) {
                    $body = $bodyValue->plaintext;
                    $bodyTrimmed = trim($body);
                    $bodyTrimmed = str_replace("\t"," ",$bodyTrimmed);
                    $bodyTrimmed = str_replace("\n"," ",$bodyTrimmed);
                    $bodyTrimmed = str_replace("\r"," ",$bodyTrimmed);
                    $bodyTrimmed = preg_replace('/\s\s+/', ' ', $bodyTrimmed);
                    $bodyTrimmed = str_replace("\\","\\\\",$bodyTrimmed);
                    $bodyTrimmed = str_replace('"','\"',$bodyTrimmed);
                    
                    if (substr($bodyTrimmed, 0, strlen($title)) == $title) {
                        $bodyTrimmed = substr($bodyTrimmed, strlen($title));
                    } 
                    $bodyTrimmed = trim($bodyTrimmed);
                    
                    $body_for_array = '"body": "' . $bodyTrimmed . '"';
                }

                $json .=  PHP_EOL . '   {' . PHP_EOL . '        ' . $page_link_for_array . PHP_EOL . '        ' . $title_for_array . PHP_EOL . '        ' . $body_for_array . PHP_EOL . '    },';
                
                echo '<p>' . $page_link . ' &mdash; done!</p>';
                $totalPages += 1;
            }
            $file_start = 'var indexes = ' . PHP_EOL . '[';
            $file_end = PHP_EOL . ']';
            $json = rtrim($json,",");
            file_put_contents('OfficeWeb/apps/' . $varEditors. '/main/resources/help/' . $languageLocale. '/search/indexes.js', $file_start . $json . $file_end);
            echo '<p>___________________________________________</p><p>Total pages: <b>' . $totalPages . '</b></p>';
            echo '<p></p><p>===========================================</p><p><a id="indexesjs"></a><b>indexes.js</b> (file path: <u>\\\\vnuchkov10\sites\documenteditors\OfficeWeb\apps\\' . $varEditors. '\main\resources\help\\' . $languageLocale. '\search\indexes.js</u>):</p>';
            echo '<pre>' . file_get_contents('OfficeWeb/apps/' . $varEditors. '/main/resources/help/' . $languageLocale. '/search/indexes.js') . '</pre>';
        }
    }
?>
<html>
<head>
    <title>Build indexes.js for documentation search</title>
    <?php
        include('shared/headLinks.php');
    ?>
</head>
<body id="parse">
    <h1>Build indexes.js for documentation search</h1>
    <?php
        include('shared/form.php');
        if($_GET['languageList'] == '') echo '<p>Select the editor and its language from the list and click the <b>Start!</b> button.</p><p>The <b>indexes.js</b> file will be formed based on <b>all</b> the pages present in the selected editor/language folder.</p><p>The file will be placed to the editor folder and used for the documentation on-page search.</p>';
    ?>
    <p id="back-top" style="display: none">
        <a title="Scroll up" href="#top"></a>
    </p>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/arrowup.min.js"></script>
</body>
</html>