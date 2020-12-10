<?php
Class VideoProcessor {
    private $con;
    private $sizeLimit = 800000000;
    private $allowedEXT = ["mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg"];
    private $ffmpegPath;
    private $ffprobePath;
    public function __construct($con) {
        $this->con = $con;
        $this->ffmpegPath=realpath("ffmpeg/ffmpeg.exe");
        $this->ffprobePath=realpath("./ffmpeg/ffprobe.exe");
    }

    public function upload($videoUploadData) {
        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->getVideoDataArray();
        $tempPath = $targetDir . uniqid() . basename($videoData["name"]);
        $tempPath = str_replace(" ", "_", $tempPath);
        $isValid = $this->processData($videoData, $tempPath);
        if(!$isValid) {
            return false;
        }
        if(move_uploaded_file($videoData["tmp_name"], $tempPath)) {
            $finalPath = $targetDir . uniqid() . ".mp4";

            if(!$this->insertVideoData($videoUploadData, $finalPath)) {
                echo "Insert failed!";
                return false;
            }
            if(!$this->convertVideoToMp4($tempPath, $finalPath)) {
                return false;
            }
            if(!$this->deleteFile($tempPath)) {
                return false;
            }
            if(!$this->generateThumbnails($finalPath)) {
                return false;
            }
            return true;
        }
        return false;
    }
    private function processData($videoData, $filePath) {
        $videoType = pathInfo($filePath, PATHINFO_EXTENSION);

        if(!$this->isValidSize($videoData)) {
            echo "File too large! Maximum size is $this->sizeLimit";
            return false;
        }
        else if(!$this->isValidType($videoType)){
            echo "Invalid file type!";
            return false;
        }
        else if ($this->hasError($videoData)) {
            echo "Error code: " . $videoData["error"];
            return false;
        }
        return true;
    }
    private function isValidSize($data) {
        return $data["size"] <= $this->sizeLimit;
    }
    private function isValidType($type) {
        $lowerType = strtolower($type);
        return in_array($lowerType, $this->allowedEXT);
    }
    private function hasError($data) {
        return $data["error"] != 0;
    }
    private function insertVideoData($uploadData, $filePath) {
        $q = $this->con->prepare("INSERT INTO videos(title, uploadedBy, description, privacy, category, filePath)
                                    VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath)");
        $title = $uploadData->getTitle();
        $uploadedBy =$uploadData->getUploader();
        $description = $uploadData->getDescription();
        $privacy = $uploadData->getPrivacy();
        $category = $uploadData->getCategory();
        $q->bindParam(":title", $title);
        $q->bindParam(":uploadedBy", $uploadedBy);
        $q->bindParam(":description", $description);
        $q->bindParam(":privacy", $privacy);
        $q->bindParam(":category", $category);
        $q->bindParam(":filePath", $filePath);
        return $q->execute();
    }
    public function convertVideoToMp4($tempPath, $finalPath) {
        $cmd = "$this->ffmpegPath -i $tempPath $finalPath";
        $outputLog = array();
        exec($cmd, $outputLog, $returnCode);
        if($returnCode!=0) {
            foreach($outputLog as $line) {
                echo $line . "<br>";
            }
            return false;
        }
        return true;
    }
    private function deleteFile($filePath) {
        if(!unlink($filePath)) {
            echo "Could not delete file";
            return false;
        }
        return true;
    }
    public function generateThumbnails($filePath) {
        $thumbnailSize = "210x118"; #taken from youtube
        $numThumbnails = 3;
        $pathToThumbnail = "uploads/videos/thumbnails";
        $duration = $this->getVideoDuration($filePath);
        $videoId = $this->con->lastInsertId();
        $this->updateDuration($duration, $videoId);

        for($num=1;$num<=$numThumbnails;$num++) {
            $imageName = uniqid().".jpg";
            $interval = ($duration*0.1)+($duration*0.8/$numThumbnails)*$num;
            $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";
            // calling ffmpeg to give up the frame at $interval
            $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath";
            $outputLog = array();
            exec($cmd, $outputLog, $returnCode);
            if($returnCode!=0) {
                foreach($outputLog as $line) {
                    echo $line . "<br>";
                }
            }
            $selected = $num == 1 ? 1 : 0 ;
            // queried DB to insert thumbnails
            $query = $this->con->prepare("INSERT INTO thumbnails(videoID, filePath, selected)
            VALUES(:videoID, :filePath, :selected)");
            $query->bindParam(":videoID", $videoId);
            $query->bindParam(":filePath", $fullThumbnailPath);
            $query->bindParam(":selected", $selected);
            $success = $query->execute();
            if(!$success) {
                echo "Failed to insert thumbnail";
                return false;
            }
        }
        return true;
    }
    private function getVideoDuration($filePath) {
        return (int) shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }
    private function updateDuration($duration, $videoID) {
        $hours = floor($duration / 3600);
        $mins = floor(($duration-($hours*3600)) / 60);
        $secs = floor($duration % 60);

        $hours = ($hours<1) ? "" : $hours.":";
        $mins = ($mins<10) ? "0" . $mins.":" : $mins . ":";
        $secs = ($secs<10) ? "0" . $secs : $secs;
        $duration = $hours.$mins.$secs;
        $q = $this->con->prepare("UPDATE videos SET duration=:duration WHERE id=:videoID");
        $q->bindParam(":duration", $duration);
        $q->bindParam(":videoID", $videoID);
        $q->execute();
    }
}
?>