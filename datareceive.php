<?php
    require __DIR__ . '/vendor/autoload.php';

    use Google\Cloud\Language\LanguageClient;
    use Google\Cloud\Translate\TranslateClient;
    
    /**
     * Find the sentiment in text.
     * ```
     * analyze_sentiment('Do you know the way to San Jose?');
     * ```
     *
     * @param string $text The text to analyze.
     * @param string $projectId (optional) Your Google Cloud Project ID
     *
     */
    function analyze_sentiment($text)
    {
        // Create the Natural Language client
    $serviceAccountPath   ="C:\Users\zubyrman\Downloads\TranslationIIT-89beea1b29cb.json";
    $projectId = "translationiit-1557980969739";

    # Instantiates a client
    $language = new LanguageClient([
        'projectId' => $projectId,
        'keyFilePath' => $serviceAccountPath,

    ]);

    
        // Call the analyzeSentiment function
        $annotation = $language->analyzeSentiment($text);
    
        // Print document and sentence sentiment information
        $sentiment = $annotation->sentiment();
        echo "<br>";
        printf('  Score: %s' . PHP_EOL, $sentiment['score']);
        printf(PHP_EOL);
        foreach ($annotation->sentences() as $sentence) {
            printf('Sentence: %s' . PHP_EOL, $sentence['text']['content']);
            /*printf('Sentence Sentiment:' . PHP_EOL);
            printf('  Magnitude: %s' . PHP_EOL, $sentence['sentiment']['magnitude']);
            printf('  Score: %s' . PHP_EOL, $sentence['sentiment']['score']);*/
            printf(PHP_EOL);
        }
    }
    function translate_main($text)  {
        $serviceAccountPath   ="C:\Users\zubyrman\Downloads\TranslationIIT-89beea1b29cb.json";
        $projectId = "translationiit-1557980969739";
    
        # Instantiates a client
        $translate = new TranslateClient([
            'projectId' => $projectId,
            'keyFilePath' => $serviceAccountPath,
    
        ]);
        $result = $translate->detectLanguage($text);
        $translation = $translate->translate($text, [
            'target' => 'en'
        ]);
        printf($text);
        echo "<br>";
        return $translation['text'];
    }
    $id = $_POST['url'];
    $comments = $_POST['comments'];
    function getYouTubeIdFromURL($url)
    {
        $url_string = parse_url($url, PHP_URL_QUERY);
        parse_str($url_string, $args);
        return isset($args['v']) ? $args['v'] : false;
    }
    $id = getYouTubeIdFromURL($id);
    $url2="https://www.googleapis.com/youtube/v3/commentThreads?key=AIzaSyBvItSfiJPT7gxDf1tU6TqlZSTPc2q_MQ0&textFormat=plainText&part=snippet&videoId=".$id."&maxResults=".$comments;
    $dataa=file_get_contents($url2);
    $array = json_decode($dataa);
    //echo $array->items[0]->snippet->topLevelComment->snippet->textDisplay;
    for($i=0;$i<(int)$comments;$i++){
        $text = $array->items[$i]->snippet->topLevelComment->snippet->textDisplay;
        $text = translate_main($text);
        echo $text;
        analyze_sentiment($text);
        echo "<br>";
        echo "<br>";
    }
    //}
?>