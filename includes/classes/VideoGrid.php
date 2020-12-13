<?php 

class VideoGrid {
    private $con, $loggedInUser;
    private $largeMode = false;
    private $gridClass = "videoGrid";

    public function __construct($con, $loggedInUser) {
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;

    }

    public function create($videos, $title, $showFilter) {

        if($videos == null) {
            $gridItems = $this->generateItems();
        }
        else{
            $gridItems = $this->generateItemsFromVideos($videos);
        }

        $header = "";

        if($title != null) {
            $header = $this->createGridHeader($title, $showFilter);
        }
        return "$header
                <div class='$this->gridClass'>
                    $gridItems
                </div>";
    }
    public function generateItems() {
        $q = $this->con->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
        $q->execute();

        $htmlElements = "";
        while($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($this->con, $row, $this->loggedInUser);
            $item = new VideoGridItem($video, $this->largeMode);
            $htmlElements .= $item->create();
        }
        return $htmlElements;
    }
    public function generateItemsFromVideos($videos) {
        $htmlElements = "";

        foreach($videos as $vid) {
            $item = new VideoGridItem($vid, $this->largeMode);
            $htmlElements .= $item->create();
        }
        return $htmlElements;
        
    }
    public function createGridHeader($title, $showFilter) {
        $filter = "";

        if($showFilter) {
            // removes the previous value of orderBy parmeter from the URL
            $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // returns the current url
            $urlArray = parse_url($link);
            if(isset($urlArray["query"])) {
                $query = $urlArray["query"];
            }
            else {
                $query = null;
            }
            parse_str($query, $params);
            unset($params["orderBy"]);
            
            $newQuery = http_build_query($params);

            $newUrl = basename($_SERVER['PHP_SELF']) . "?" . $newQuery; 
            $filter = "<div class='right'>
                            <span>order by</span>
                            <a href='$newUrl&orderBy=uploadDate'>Upload date</a>
                            <a href='$newUrl&orderBy=views'>Most viewed</a>
                        </div>";
        }

        $header = "<div class='videoGridHeader'>
                        <div class='left'>
                            $title
                        </div>
                        $filter
                    </div>";
        return $header;
    }
    public function createLarge($videos, $title, $showFilter) {
        $this->gridClass .= " large";
        $this->largeMode = true;
        return $this->create($videos, $title, $showFilter);
    }
}


?>