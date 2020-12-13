<?php

class Video {
    private $con, $data, $loggedInUser;
    
    public function __construct($con, $input, $loggedInUser) {
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;

        if(is_array($input)) {
            // if the sql data is already retrieved we don't need to make a query
            $this->data = $input;
        }
        else {
            $query = $this->con->prepare("SELECT * FROM videos WHERE id = :id");
            $query->bindParam(":id", $input);
            $query->execute();
            $this->data = $query->fetch(PDO::FETCH_ASSOC);
        }

    }
    public function getId() {
        return $this->data["id"];
    }
    public function getUploader() {
        return $this->data["uploadedBy"];
    }
    public function getTitle() {
        return $this->data["title"];
    }
    public function getDescription() {
        return $this->data["description"];
    }
    public function getPrivacy() {
        return $this->data["privacy"];
    }
    public function getFilePath() {
        return $this->data["filePath"];
    }
    public function getCategory() {
        return $this->data["category"];
    }
    public function getUploadDate() {
        $date = $this->data["uploadDate"];
        return date("M j, Y", strtotime($date));
    }
    public function getTimestamp() {
        $date = $this->data["uploadDate"];
        return date("M jS, Y", strtotime($date));
    }
    public function getViews() {
        return $this->data["views"];
    }
    public function getDuration() {
        return $this->data["duration"];
    }
    public function incrementViews() {
        $videoId = $this->getId();
        $q = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
        $q->bindParam(":id", $videoId);
        $q->execute();
        $this->data["views"] = $this->data["views"]+1;
    }
    public function getLikes() {
        $videoId = $this->getId();
        $q = $this->con->prepare("SELECT COUNT(*) as 'count' FROM likes WHERE videoId=:id");
        $q->bindParam(":id", $videoId);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data["count"];
    }
    public function getDislikes() {
        $videoId = $this->getId();
        $q = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId=:id");
        $q->bindParam(":id", $videoId);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data["count"];
    }
    public function like() {
        $id = $this->getId();
        $un = $this->loggedInUser->getUsername();
        if($this->wasLikedBy()) {
            // liked
            $q = $this->con->prepare("DELETE FROM likes WHERE username=:un AND videoId=:id");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();

            $result = array(
                "likes" => -1,
                "dislikes" => 0
            );
            return json_encode($result);

        }
        else {
            // not liked
            $q = $this->con->prepare("DELETE FROM dislikes WHERE username=:un AND videoId=:id");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            $count = $q->rowCount();
            $q = $this->con->prepare("INSERT INTO likes(username, videoId) VALUES(:un, :id)");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            
            $result = array(
                "likes" => +1,
                "dislikes" => 0-$count
            );
            return json_encode($result);
        }
    }

    public function dislike() {
        $id = $this->getId();
        $un = $this->loggedInUser->getUsername();
        if($this->wasDislikedBy()) {
            // liked
            $q = $this->con->prepare("DELETE FROM dislikes WHERE username=:un AND videoId=:id");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();

            $result = array(
                "likes" => 0,
                "dislikes" => -1
            );
            return json_encode($result);

        }
        else {
            // not liked
            $q = $this->con->prepare("DELETE FROM likes WHERE username=:un AND videoId=:id");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            $count = $q->rowCount();
            $q = $this->con->prepare("INSERT INTO dislikes(username, videoId) VALUES(:un, :id)");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            
            $result = array(
                "likes" => 0-$count,
                "dislikes" => 1
            );
            return json_encode($result);
        }
    }

    public function wasLikedBy() {
        $id = $this->getId();
        $un = $this->loggedInUser->getUsername();
        $q = $this->con->prepare("SELECT * FROM likes WHERE username=:un AND videoId=:id");
        $q->bindParam(":un", $un);
        $q->bindParam(":id", $id);
        $q->execute();

        if($q->rowCount() > 0) {
            return true;
        }
    }
    public function wasDislikedBy() {
        $id = $this->getId();
        $un = $this->loggedInUser->getUsername();
        $q = $this->con->prepare("SELECT * FROM dislikes WHERE username=:un AND videoId=:id");
        $q->bindParam(":un", $un);
        $q->bindParam(":id", $id);
        $q->execute();

        if($q->rowCount() > 0) {
            return true;
        }
    }
    public function getCommentsCount() {
        $id = $this->getId();
        $q = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId");
        $q->bindParam(":videoId", $id);
        $q->execute();
        return $q->rowCount();
    }
    public function getComments() {
        $id = $this->getId();
        $q = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId AND responseTo=0 ORDER BY datePosted DESC");
        $q->bindParam(":videoId", $id);
        $q->execute();
        $comments = array();
        while($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($this->con, $row, $this->loggedInUser, $id);
            array_push($comments, $comment);
        }
        return $comments;
    }
    public function getThumbnail() {
        $videoId = $this->getId();
        $q = $this->con->prepare("SELECT filePath FROM thumbnails WHERE videoId=:videoId AND selected=1");
        $q->bindParam(":videoId",$videoId);
        $q->execute();
        return $q->fetchColumn();
    }
}

?>