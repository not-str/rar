<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSS Feed Reader</title>
</head>

<body>
    <p>Choose a category</p>
    <form id="myForm" method="post">
        <select required name="rssurl">
            <option value="https://timesofindia.indiatimes.com/rssfeeds/1081479906.cms">Entertainment</option>
            <option value="https://timesofindia.indiatimes.com/rssfeeds/2128672765.cms">Science</option>
            <option value="https://timesofindia.indiatimes.com/rssfeeds/2886704.cms">Lifestyle</option>
        </select>
        <input type="submit" value="Load">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['rssurl'])) {
        $rssurl = $_POST['rssurl'];
        echo "<h1>Search Result for RSS URL: $rssurl</h1>";

        $rss = new DOMDocument();
        $rss->load($rssurl);

        $feed = [];
        foreach ($rss->getElementsByTagName('item') as $node) {
            $item = [
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'desc'  => $node->getElementsByTagName('description')->item(0)->nodeValue,
                'link'  => $node->getElementsByTagName('link')->item(0)->nodeValue,
                'date'  => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
            ];
            array_push($feed, $item);
        }

        $limit = min(5, count($feed));
        for ($x = 0; $x < $limit; $x++) {
            $title =$feed[$x]['title'];
            $link = $feed[$x]['link'];
            $description = htmlspecialchars_decode($feed[$x]['desc'], ENT_QUOTES);
            $date = date('l, F d, Y', strtotime($feed[$x]['date']));

            echo "<p><strong><a href=\"$link\" title=\"$title\">$title</a></strong></p>";
            echo "<p>$description</p>";
            echo "<small><em>Posted on $date</em></small><hr>";
        }
    }
    ?>
</body>

</html>
