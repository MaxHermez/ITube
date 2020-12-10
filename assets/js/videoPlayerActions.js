function likeVideo(button, videoId) {
    $.post("ajax/likeVideo.php", {videoId: videoId})
    .done(function (data) {
        //JQUERY
        var likeB = $(button);
        var dislikeB = $(button).siblings(".dislikeButton");
            
        likeB.addClass("active");
        dislikeB.removeClass("active");

        var result = JSON.parse(data);
        updateLikes(likeB.find(".text"), result.likes);
        updateLikes(dislikeB.find(".text"), result.dislikes);

        if(result.likes < 0) {
            likeB.removeClass("active");
            likeB.find("img:first").attr("src", "assets/images/icons/thumb-up.png");
        }
        else {
            likeB.find("img:first").attr("src", "assets/images/icons/thumb-up-active.png")
        }
        dislikeB.find("img:first").attr("src", "assets/images/icons/thumb-down.png")
    });
}

function dislikeVideo(button, videoId) {
    $.post("ajax/dislikeVideo.php", {videoId: videoId})
    .done(function (data) {
        //JQUERY
        var dislikeB = $(button);
        var likeB = $(button).siblings(".likeButton");
            
        dislikeB.addClass("active");
        likeB.removeClass("active");

        var result = JSON.parse(data);
        updateLikes(likeB.find(".text"), result.likes);
        updateLikes(dislikeB.find(".text"), result.dislikes);

        if(result.dislikes < 0) {
            dislikeB.removeClass("active");
            dislikeB.find("img:first").attr("src", "assets/images/icons/thumb-down.png");
        }
        else {
            dislikeB.find("img:first").attr("src", "assets/images/icons/thumb-down-active.png")
        }
        likeB.find("img:first").attr("src", "assets/images/icons/thumb-up.png")
    });
}

function updateLikes(element, num) {
    var likesCount = element.text() || 0;
    element.text(parseInt(likesCount) + parseInt(num))

}