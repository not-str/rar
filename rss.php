<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSS Feed Reader</title>
</head>
<body>
    <p>Choose a category</p>
    <form id="myForm" method="get">
        <select required name="rssurl">
            <option value="https://timesofindia.indiatimes.com/rssfeeds/1081479906.cms">Entertainment</option>
            <option value="https://timesofindia.indiatimes.com/rssfeeds/-2128672765.cms">Science</option>
            <option value="https://timesofindia.indiatimes.com/rssfeeds/2886704.cms">Life style</option>
        </select>
        <input type="submit" value="Load">
    </form>

    <?php 
    if(isset($_GET['rssurl'])){
        $rssurl = $_GET['rssurl'];
        echo '<h1>Search Result for RSS URL: ' . htmlspecialchars($rssurl) . '</h1>';
        
        $rssContent = @file_get_contents($rssurl);
        if ($rssContent === false) {
            echo "<p>Error loading RSS feed. Please try again later.</p>";
        } else {
            $rss = new SimpleXMLElement($rssContent);
            $feed = [];

            foreach ($rss->channel->item as $item) {
                $description = (string) $item->description;
                
                // Extract the first image if available
                preg_match('/<img[^>]+src="([^"]+)"/i', $description, $matches);
                $image = isset($matches[1]) ? $matches[1] : '';

                $feed[] = [
                    'title' => (string) $item->title,
                    'desc' => strip_tags($description), // Remove HTML but keep text
                    'link' => (string) $item->link,
                    'date' => (string) $item->pubDate,
                    'image' => $image
                ];
            }

            $limit = min(5, count($feed)); // Avoid out-of-bounds errors
            for($x = 0; $x < $limit; $x++){
                $title = htmlspecialchars($feed[$x]['title']);
                $link = htmlspecialchars($feed[$x]['link']);
                $description = htmlspecialchars($feed[$x]['desc']);
                $date = date('l, F d, Y', strtotime($feed[$x]['date']));
                $image = $feed[$x]['image'];

                echo "<p><strong><a href='$link' title='$title' target='_blank'>$title</a></strong></p>";
                if ($image) {
                    echo "<img src='$image' alt='Image' style='max-width:100%; height:auto;'><br>";
                }
                echo "<p>$description</p>";
                echo "<small><em>Posted on $date</em></small><hr>";
            }
        }
    }
    ?>
</body>
</html>
