function openLoading(_process = false, _callBack = "") {
    var fullPageLoaderElement = $("#fullPageLoader");
    fullPageLoaderElement.removeClass("hidden");

    fullPageLoaderElement.find(".percentBar").text(`0%`);
    fullPageLoaderElement.find(".bar").css("width", `0%`);

    if (_process && typeof _process !== "function")
        fullPageLoaderElement.find(".processBar").removeClass("hidden");

    try {
        setTimeout(function () {
            if (typeof _callBack === "function") _callBack();
            else if (typeof _process === "function") _process();
        }, 200);
    } catch (e) {
        closeLoading();
    }
}

function closeLoading() {
    $("#fullPageLoader").addClass("hidden");
}

function updatePercentLoadingBar(_percent) {
    var fullPageLoaderElement = $("#fullPageLoader");

    fullPageLoaderElement.find(".percentBar").text(`${_percent}%`);
    fullPageLoaderElement.find(".bar").css("width", `${_percent}%`);
}

function storePlaceToBookMark(_placeId, _kindPlaceId, _callBack = "") {
    if (!checkLogin()) return;

    openLoading();
    $.ajax({
        type: "POST",
        url: window.setPlaceToBookMarkUrl,
        data: {
            placeId: _placeId,
            kindPlaceId: _kindPlaceId,
        },
        complete: closeLoading,
        success: (response) => {
            if (typeof _callBack === "function") _callBack("ok", response);
        },
        error: (err) => {
            if (typeof _callBack === "function") _callBack("error", err);
        },
    });
}

function getLoginPages(_callBack) {
    openLoading(false, () => {
        $.ajax({
            type: "GET",
            url: window.getLoginPageUrl,
            success: (response) => {
                closeLoading();
                $("body").append(response);
                window.getPages.push("login");
                if (typeof _callBack === "function") _callBack();
            },
            error: (err) => {
                closeLoading();
                console.error(err);
            },
        });
    });
}

function checkLogin(redirect = window.InUrl, _callBackAfterLogin = "") {
    if (!hasLogin) {
        if (window.getPages.indexOf("login") == -1)
            getLoginPages(() => showLoginPrompt(redirect, _callBackAfterLogin));
        else showLoginPrompt(redirect, _callBackAfterLogin);
        return false;
    } else return true;
}

function setSeenAlert(_id = 0, _element = "") {
    let kind = _id == 0 ? "seen" : "click";

    if (kind == "seen" && seenToZero) return;

    $.ajax({
        type: "post",
        url: window.alertSeenUrl,
        data: {
            _token: csrfTokenGlobal,
            id: _id,
            kind: kind,
        },
        success: function (response) {
            if (response.status == "ok") {
                if (kind == "seen") {
                    $(".newAlertNumber").addClass("hidden");
                    seenToZero = true;
                } else $(_element).css("background", "white");
            }
        },
    });
}

function deleteBookMarkState(_id, _element) {
    $.ajax({
        type: "post",
        url: window.deleteBookMarkUrl,
        data: {
            _token: csrfTokenGlobal,
            id: _id,
        },
        success: (response) => {
            if (response.status == "ok") $(_element).parent().remove();
            else
                showSuccessNotifi(
                    "مشکلی در حذف نشان کرده پیش امده",
                    "left",
                    "red"
                );
        },
        error: (err) =>
            showSuccessNotifi("مشکلی در حذف نشان کرده پیش امده", "left", "red"),
    });
}

function getAlertItems() {
    $.ajax({
        type: "get",
        url: window.getAlertUrl,
        success: function (response) {
            var newElement = "";
            var newMsg = 0;

            if (response.result.length > 0) {
                response.result.map((item) => {
                    if (item.click != 0) item.color = "white";

                    if (item.seen == 0) newMsg++;

                    newElement +=
                        '<div class="alertMsgHeaderContent" style="background: ' +
                        item.color +
                        '" onclick="setSeenAlert(' +
                        item.id +
                        ', this)">\n' +
                        '<div class="alertMsgHeaderContentImgDiv">\n' +
                        '<img src="' +
                        item.pic +
                        '"  alt="Dorna News" class="resizeImgClass" onload="fitThisImg(this)" style="width: 100%">\n' +
                        "</div>\n" +
                        '<div class="alertMsgHeaderContentTextDiv">\n' +
                        '<div class="alertMsgHeaderContentText">' +
                        item.msg +
                        "</div>\n" +
                        '<div class="alertMsgHeaderContentTime">' +
                        item.time +
                        "</div>\n" +
                        "</div>\n" +
                        "</div>";
                });
                $(".alertMsgResultDiv").html(newElement);

                if (newMsg != 0)
                    $(".newAlertNumber").removeClass("hidden").html(newMsg);
            } else {
                newElement +=
                    '<div><div class="modules-engagement-notification-dropdown"><div class="notifdd_empty">هیچ پیامی موجود نیست </div></div></div>';
                $("#headerMsgPlaceHolder").html(newElement);
            }
        },
    });
}

function goToLanguage(_lang) {
    if (_lang != 0) location.href = window.languageUrl + _lang;
}

function convertNumberToEn(str) {
    let persianNumbers = [
        /۰/g,
        /۱/g,
        /۲/g,
        /۳/g,
        /۴/g,
        /۵/g,
        /۶/g,
        /۷/g,
        /۸/g,
        /۹/g,
    ];
    let arabicNumbers = [
        /٠/g,
        /١/g,
        /٢/g,
        /٣/g,
        /٤/g,
        /٥/g,
        /٦/g,
        /٧/g,
        /٨/g,
        /٩/g,
    ];
    if (typeof str === "string") {
        for (var i = 0; i < 10; i++)
            str = str
                .replace(persianNumbers[i], i)
                .replace(arabicNumbers[i], i);
    }
    return str;
}

function cleanImgMetaData(_input, _callBack) {
    openLoading(false, function () {
        options = { canvas: true };
        loadImage.parseMetaData(_input.files[0], function (data) {
            if (data.exif) options.orientation = data.exif.get("Orientation");

            loadImage(
                _input.files[0],
                function (canvas) {
                    closeLoading();
                    var imgDataURL = canvas.toDataURL();
                    if (typeof _callBack === "function") {
                        blob = dataURItoBlob(imgDataURL);
                        _callBack(imgDataURL, blob);
                    }
                },
                options
            );
        });
    });
}
function dataURItoBlob(dataURI) {
    var byteString = atob(dataURI.split(",")[1]);
    var mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++)
        ia[i] = byteString.charCodeAt(i);

    var blob = new Blob([ab], { type: mimeString });
    return blob;
}
function resizeImgTo(_dataUrl, _size, _callBack) {
    var resize_width = _size.width;
    var resize_height = _size.height;
    var img = new Image();
    img.onload = function (el) {
        var mainWidth = el.target.width;
        var mainHeight = el.target.height;

        var scaleFactor;
        var elem = document.createElement("canvas");

        if (resize_height != null && resize_width != null) {
            elem.height = Math.min(mainHeight, resize_height);
            elem.width = Math.min(mainWidth, resize_width);
        } else if (resize_height == null) {
            if (mainWidth < resize_width) {
                elem.width = mainWidth;
                elem.height = mainHeight;
            } else {
                scaleFactor = resize_width / mainWidth;
                elem.width = resize_width;
                elem.height = mainHeight * scaleFactor;
            }
        } else if (resize_width == null) {
            if (mainHeight < resize_height) {
                elem.width = mainWidth;
                elem.height = mainHeight;
            } else {
                scaleFactor = resize_height / mainHeight;
                elem.height = resize_height;
                elem.width = mainWidth * scaleFactor;
            }
        }

        var ctx = elem.getContext("2d");
        ctx.drawImage(el.target, 0, 0, elem.width, elem.height);
        var srcEncoded = ctx.canvas.toDataURL(el.target, "image/jpeg", 0);
        var newFile = dataURItoBlob(srcEncoded);
        _callBack(srcEncoded, newFile);
    };

    img.src = _dataUrl;
}

function openMyModal(_id) {
    $("#" + _id).addClass("showModal");
}

function closeMyModal(_id) {
    $("#" + _id).removeClass("showModal");
}

function closeMyModalClass(_class) {
    $("." + _class).removeClass("showModal");
}

function showLastPages() {
    let lastPages = localStorage.getItem("lastPages");
    lastPages = JSON.parse(lastPages);

    $("#recentlyRowMainSearch").html("");

    if (lastPages != null) {
        var showRecentlyText = "";
        for (i = 0; i < lastPages.length; i++) {
            var text = recentlyMainSearchSample;
            var fk = Object.keys(lastPages[i]);

            var name = lastPages[i]["name"];
            t = "##name##";
            re = new RegExp(t, "g");

            if (lastPages[i]["kind"] == "city")
                name += " در " + lastPages[i]["state"];
            else if (lastPages[i]["kind"] == "place")
                name += " در " + lastPages[i]["city"];
            else if (lastPages[i]["kind"] == "article")
                name = "مقاله " + lastPages[i]["name"];
            text = text.replace(re, name);

            for (var x of fk) {
                var t = "##" + x + "##";
                var re = new RegExp(t, "g");

                if (x == "city" && lastPages[i]["state"] != "")
                    text = text.replace(re, lastPages[i][x] + " در ");
                else text = text.replace(re, lastPages[i][x]);
            }

            showRecentlyText += text;
        }
        $(".recentlyRowMainSearch").html(showRecentlyText);
    }
}

function numberWithCommas(_x) {
    if (_x != undefined && _x != null) {
        _x = _x.toString().replace(new RegExp(",", "g"), "");
        var parts = _x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    } else return "";
}

function sendSeenPageLog() {
    $.ajax({
        type: "post",
        url: window.storeSeenLogUrl,
        data: {
            _token: csrfTokenGlobal,
            relatedId: window.seenRelatedId,
            seenPageLogId: window.seenPageLogId,
            scrollData: window.userScrollPageLog,
            isMobile: window.isMobile,
            windowsSize: {
                width: $(window).width(),
                height: $(window).height(),
            },
            url: document.location.pathname,
        },
        success: (response) => {
            if (response.status == "ok") {
                sessionStorage.setItem("lastPageLogId", response.seenPageLogId);
                window.seenPageLogId = response.seenPageLogId;
            }
            // setTimeout(sendSeenPageLog, 5000);
        },
        // error: err => setTimeout(sendSeenPageLog, 5000)
    });
}

function createPhotoModal(_title, _pics, _choosenIndex = 0) {
    // _pics = [
    //     {
    //         'id' : , must be kind_idNum like => review_124 or photographer_1134
    //         'sidePic' : ,
    //         'mainPic' : ,
    //         'userPic' : ,
    //         'userName' : ,
    //         'showInfo' : show like or not (true , false) ,
    //         'where' : ,   (optional)
    //         'whereUrl' : ,   (optional)
    //         'like' : ,   (optional)
    //         'dislike' : ,    (optional)
    //         'alt' : ,    (optional)
    //         'uploadTime' : , (optional)
    //         'video': if video, (optional)
    //         'description' : ,    (optional)
    //         'userLike' : if user like this img?,     (optional)
    //     }
    // ]

    sidePics = _pics;
    $("#photoAlbumTitle").text(_title);
    $("#sidePhotoModal").empty();

    for (var i = 0; i < sidePics.length; i++) {
        var text = srcSidePic;
        var fk = Object.keys(sidePics[i]);
        for (var x of fk)
            text = text.replace(new RegExp(`##${x}##`, "g"), sidePics[i][x]);

        text = text.replace(
            new RegExp("##picIndex##", "g"),
            `chooseAlbumMainPhoto(${i})`
        );
        text = text.replace(new RegExp("##index##", "g"), i);
        text = text.replace(
            new RegExp("##isVideoClass##", "g"),
            sidePics[i]["video"] != undefined ? "playIconOnPicSection" : ""
        );

        $("#sidePhotoModal").append(text);
    }
    $("#photoAlbumModal").modal({ backdrop: "static", keyboard: false });

    chooseAlbumMainPhoto(_choosenIndex);
}

function chooseAlbumMainPhoto(_index) {
    choosenIndex = _index;
    $(".chooseSidePhotoAlbum").removeClass("chooseSidePhotoAlbum");
    $("#photoAlbumDescription").text("");
    $("#photoAlbumNamePic").text("");

    if (sidePics[_index]["picName"] != undefined)
        $("#photoAlbumNamePic").text(" - " + sidePics[_index]["picName"]);

    $(".photoAlbumUploadTime").text(sidePics[_index]["uploadTime"]);
    $(".photoAlbumUserName").text(sidePics[_index]["userName"]);
    $(".userProfileLinks").attr(
        "href",
        `${userProfileUrl_albume}/${sidePics[_index]["userName"]}`
    );
    $(".photoAlbumWhere")
        .text(sidePics[_index]["where"] ? sidePics[_index]["where"] : "")
        .attr("href", sidePics[_index]["whereUrl"]);
    $(".photoAlbumUserPic").attr("src", sidePics[_index]["userPic"]);

    $("#sideAlbumPic" + _index).addClass("chooseSidePhotoAlbum");

    if (sidePics[_index]["video"] != undefined) {
        $("#mainPhotoAlbum").css("display", "none");
        $("#mainVideoPhotoAlbum")
            .css("display", "block")
            .attr("src", sidePics[_index]["video"]);
    } else {
        $("#mainPhotoAlbum")
            .css("display", "block")
            .attr("src", sidePics[_index]["mainPic"])
            .attr("alt", sidePics[_index]["alt"]);
        $("#mainVideoPhotoAlbum").css("display", "none").attr("src", "");
    }

    if (sidePics[_index]["showInfo"]) {
        $("#photoAlbumLikeSection").css("display", "block");
        $(".photoAlbumDisLikeCount").text(sidePics[_index]["dislike"]);
        $(".photoAlbumLikeCount").text(sidePics[_index]["like"]);

        $(".photoAlbumTopLike")
            .removeClass("fullLikePhotoAlbum")
            .attr("picId", sidePics[_index]["id"]);
        $(".photoAlbumTopDisLike")
            .removeClass("fullDisLikePhotoAlbum")
            .attr("picId", sidePics[_index]["id"]);

        if (sidePics[_index]["userLike"] == 1) likePhotoAlbum(1);
        else if (sidePics[_index]["userLike"] == -1) likePhotoAlbum(-1);
    } else $("#photoAlbumLikeSection").css("display", "none");

    $("#photoAlbumDescription").text(sidePics[_index]["description"]);
    if (userInPhoto !== false && userInPhoto == sidePics[_index].userName)
        $("#deletePicIconsPhotoAlbum")
            .css("display", "flex")
            .attr("dataValue", sidePics[_index].id)
            .attr("dataIndex", _index);
    else
        $("#deletePicIconsPhotoAlbum")
            .css("display", "none")
            .attr("dataValue", 0)
            .attr("dataIndex", false);
}

function likePhotoAlbum(_like) {
    $(".photoAlbumTopLike").removeClass("fullLikePhotoAlbum");
    $(".photoAlbumTopDisLike").removeClass("fullDisLikePhotoAlbum");
    sidePics[choosenIndex]["userLike"] = _like;

    if (_like == 1) $(".photoAlbumTopLike").addClass("fullLikePhotoAlbum");
    else if (_like == -1)
        $(".photoAlbumTopDisLike").addClass("fullDisLikePhotoAlbum");
}

function setLikeNumberInPhotoAlbum(_count, _kind) {
    if (_kind == "like") {
        $(".photoAlbumLikeCount").text(_count);
        sidePics[choosenIndex]["like"] = _count;
    } else if (_kind == "dislike") {
        $(".photoAlbumDisLikeCount").text(_count);
        sidePics[choosenIndex]["dislike"] = _count;
    }
}

function closePhotoAlbumModal() {
    $("#mainVideoPhotoAlbum").attr("src", "");
    $("#photoAlbumModal").modal("hide");
}

var deletedPhotoInAlbum = false;
function openDeletePhotoModal() {
    var element = $("#deletePicIconsPhotoAlbum");
    deletedPhotoInAlbum = element.attr("dataValue");
    var deletedPhotoIndex = element.attr("dataIndex");

    if (typeof sidePics[deletedPhotoIndex].deleteFunction === "function")
        sidePics[deletedPhotoIndex].deleteFunction(deletedPhotoInAlbum);
    else
        openWarning(
            "آیا از حذف عکس خود اطمینان دارید؟ در صورت حذف محتوای مورد نظر قابل بازیابی نمی باشد.",
            doPhotoDeleteInAlbum
        ); // in general.alert.blade.php
}

function doPhotoDeleteInAlbum() {
    openLoading();
    $.ajax({
        type: "POST",
        url: deletePicInAlbumUrl,
        data: {
            _token: csrfTokenGlobal,
            id: deletedPhotoInAlbum,
        },
        success: function (response) {
            if (response.status == "ok") location.reload();
            else {
                closeLoading();
                showSuccessNotifi(
                    "در حذف عکس مشکلی پیش آمده لطفا دوباره تلاش نمایید.",
                    "left",
                    "red"
                ); // in general.alert.blade.php
            }
        },
        error: () => {
            closeLoading();
            showSuccessNotifi(
                "در حذف عکس مشکلی پیش آمده لطفا دوباره تلاش نمایید.",
                "left",
                "red"
            ); // in general.alert.blade.php
        },
    });
}

function likeAlbumPic(_element, _like) {
    if (!checkLogin()) return;

    var id = $(".photoAlbumTopLike").attr("picId");

    $.ajax({
        type: "POST",
        url: likePhotographerUrl,
        data: {
            _token: csrfTokenGlobal,
            id: id,
            like: _like,
        },
        success: function (response) {
            if (response.status == "ok") {
                setLikeNumberInPhotoAlbum(response.like, "like");
                setLikeNumberInPhotoAlbum(response.disLike, "dislike");
                likePhotoAlbum(_like);
            }
        },
    });
}

// $(window).on('scroll', () => {
//     var time = seenLogStartTime;
//     seenLogStartTime = new Date().getTime();
//     if(new Date().getTime() - time > 1000){
//         window.userScrollPageLog.push({
//             scroll: (lastSeenLogScroll/($(document).height() - $(window).height())) * 100,
//             time: new Date().getTime() - time
//         })
//     }
//     else if(window.userScrollPageLog[window.userScrollPageLog.length-1] != 'scrolling')
//         window.userScrollPageLog.push('scrolling');
//
//     if(userWindowInScrolling != null)
//         clearTimeout(userWindowInScrolling);
//
//     setTimeout(() => {
//         seenLogStartTime = new Date().getTime();
//         lastSeenLogScroll = window.pageYOffset
//     }, 1000);
// });

// addToTripModal
function saveToTripPopUp(placeId, kindPlaceId) {
    if (checkLogin) {
        openLoading();
        selectedPlaceId = placeId;
        selectedKindPlaceId = kindPlaceId;

        $.ajax({
            type: "POST",
            url: placeTripUrl_addToTripModal,
            data: {
                placeId: placeId,
                kindPlaceId: kindPlaceId,
            },
            success: function (response) {
                closeLoading();
                selectedTrips = [];
                response = JSON.parse(response);
                var newElement = "<center class='row'>";
                for (i = 0; i < response.length; i++) {
                    newElement +=
                        "<div class='addPlaceBoxes cursor-pointer' onclick='addToSelectedTrips(\"" +
                        response[i].id +
                        "\")'>";
                    if (response[i].select == "1") {
                        newElement +=
                            "<div id='trip_" +
                            response[i].id +
                            "' onclick='' class='tripResponse addedTrip selectedTrip'>";
                        selectedTrips[selectedTrips.length] = response[i].id;
                    } else
                        newElement +=
                            "<div id='trip_" +
                            response[i].id +
                            "' onclick='' class='tripResponse addedTrip'>";
                    if (response[i].placeCount > 0) {
                        tmp = 'url("' + response[i].pic1 + '")';
                        newElement +=
                            "<div class='tripImage' style='background: " +
                            tmp +
                            " repeat 0 0; background-size: 100% 100%'></div>";
                    } else newElement += "<div class='tripImageEmpty'></div>";
                    if (response[i].placeCount > 1) {
                        tmp = 'url("' + response[i].pic2 + '")';
                        newElement +=
                            "<div class='tripImage' style='background: " +
                            tmp +
                            " repeat 0 0; background-size: 100% 100%'></div>";
                    } else newElement += "<div class='tripImageEmpty'></div>";
                    if (response[i].placeCount > 1) {
                        tmp = 'url("' + response[i].pic3 + '")';
                        newElement +=
                            "<div class='tripImage' style='background: " +
                            tmp +
                            " repeat 0 0; background-size: 100% 100%'></div>";
                    } else newElement += "<div class='tripImageEmpty'></div>";
                    if (response[i].placeCount > 1) {
                        tmp = 'url("' + response[i].pic4 + '")';
                        newElement +=
                            "<div class='tripImage' style='background: " +
                            tmp +
                            " repeat 0 0; background-size: 100% 100%'></div>";
                    } else newElement += "<div class='tripImageEmpty'></div>";
                    newElement +=
                        "</div><div class='create-trip-text font-size-12em'>" +
                        response[i].name +
                        "</div>";
                    newElement += "</div>";
                }
                newElement += "<div class='addPlaceBoxes'>";
                newElement +=
                    "<a onclick='createNewTrip()' class='single-tile is-create-trip'>";
                newElement +=
                    "<div class='tile-content text-align-center font-size-20Imp'>";
                newElement += "<span class='plus2'></span>";
                newElement += "<div class='create-trip-text'>ایجاد سفر</div>";
                newElement += "</div></a></div>";
                newElement += "</div>";
                $("#tripsForPlace").empty().append(newElement);
                openMyModal("addPlaceToTripPrompt");
            },
        });
    }
}

function addToSelectedTrips(id) {
    allow = true;
    for (i = 0; i < selectedTrips.length; i++) {
        if (selectedTrips[i] == id) {
            allow = false;
            $("#trip_" + id).css("border", "2px solid #a0a0a0");
            selectedTrips.splice(i, 1);
            break;
        }
    }
    if (allow) {
        $("#trip_" + id).css("border", "2px solid var(--koochita-light-green)");
        selectedTrips[selectedTrips.length] = id;
    }
}

function refreshThisAddTrip() {
    closeNewTrip();
    openMyModal("addPlaceToTripPrompt");
    saveToTripPopUp(selectedPlaceId, selectedKindPlaceId);
}

function assignPlaceToTrip() {
    if (selectedPlaceId != -1) {
        var checkedValuesTrips = selectedTrips;
        if (checkedValuesTrips == null || checkedValuesTrips.length == 0)
            checkedValuesTrips = "empty";
        $.ajax({
            type: "POST",
            url: assignPlaceToTripUrl_addToTripModal,
            data: {
                checkedValuesTrips,
                placeId: selectedPlaceId,
                kindPlaceId: selectedKindPlaceId,
            },
            success: function (response) {
                if (response == "ok") {
                    refreshThisAddTrip();
                    showSuccessNotifi(
                        "تغییرات شما با موفقیت اعمال شد.",
                        "left",
                        "var(--koochita-blue)"
                    );
                } else {
                    var err =
                        "<p>به جز سفر های زیر که اجازه ی افزودن مکان به آنها را نداشتید بقیه به درستی اضافه شدند</p>";
                    JSON.parse(response).map(
                        (error) => (err += `<p>${error}</p>`)
                    );
                    $("#errorAssignPlace").append(err);
                }
            },
        });
    }
}

function closeNewTrip() {
    $("#selectNewTripName").css("display", "flex");
    $("#selectNewTripDate").css("display", "none");
    closeMyModal("newTripModal");
    $("#tripName").val("");
}

function backToNewTripName() {
    $("#selectNewTripName").css("display", "flex");
    $("#selectNewTripDate").css("display", "none");
}

function createNewTrip(_callBack = "") {
    if (!checkLogin()) return;

    callBackCreateTrip = null;
    $("#my-trips-not").hide();

    checkEmptyTripInputs();
    openMyModal("newTripModal");
    if (typeof _callBack === "function") callBackCreateTrip = _callBack;
}

function checkEmptyTripInputs() {
    if ($("#tripName").val() == "")
        $("#saves-create-trip-button").addClass("disabled");
    else $("#saves-create-trip-button").removeClass("disabled");
}

function nextStep() {
    if ($("#tripName").val() == "") return;

    tripName = $("#tripName").val();

    $("#selectNewTripName").css("display", "none");
    $("#selectNewTripDate").css("display", "flex");

    var datePickerOptions = {
        numberOfMonths: 1,
        showButtonPanel: true,
        dateFormat: "yy/mm/dd",
    };
    $("#date_input_start").datepicker(datePickerOptions);
    $("#date_input_end").datepicker(datePickerOptions);
}

function saveTrip() {
    var dateInputStart = $("#date_input_start").val();
    var dateInputEnd = $("#date_input_end").val();
    $("#error").hide();
    if (
        dateInputStart > dateInputEnd &&
        dateInputEnd != "" &&
        dateInputEnd != ""
    ) {
        $("#error")
            .show()
            .empty()
            .append("تاریخ پایان از تاریخ شروع باید بزرگ تر باشد");
        return;
    }

    $.ajax({
        type: "POST",
        url: addTripUrl_addToTripModal,
        data: {
            tripName,
            dateInputStart,
            dateInputEnd,
        },
        success: function (response) {
            $("#error").hide();
            if (response == "ok") {
                if (
                    callBackCreateTrip != null &&
                    typeof callBackCreateTrip === "function"
                ) {
                    closeNewTrip();
                    callBackCreateTrip();
                    callBackCreateTrip = null;
                } else refreshThisAddTrip();
                showSuccessNotifi(
                    "لیست سفر شما با موفقیت ایجاد شد",
                    "left",
                    "var(--koochita-blue)"
                );
            } else
                $("#error")
                    .show()
                    .empty()
                    .append("تاریخ پایان از تاریخ شروع باید بزرگ تر باشد");
        },
    });
}

// followerPopUp

openFromInPageFollower = (_kind) =>
    openFollowerModal(_kind, getUserFollowerInPage);

function openFollowerModal(_kind, _forWho = 0) {
    lastFollowerModalOpenPage = _kind;
    if (_forWho != 0) getUserFollowerInPage = _forWho;

    if (followerUserId == _forWho)
        $("#ifYouCanSeeFollowing").removeClass("hidden");
    else $("#ifYouCanSeeFollowing").addClass("hidden");

    $("#followerModalBody").children().addClass("hidden");
    $(`#${_kind}`).removeClass("hidden");

    $(`.${_kind}Tab`).parent().find(".selected").removeClass("selected");
    $(`.${_kind}Tab`).addClass("selected");
    $("#" + _kind).html(followerPlaceHolder + followerPlaceHolder);

    var sendKind = "";
    if (_kind == "resultFollowing") sendKind = "following";
    else sendKind = "follower";

    openMyModal("followerModal");
    $.ajax({
        type: "POST",
        url: profileGetFollowerUrl_followerPopUp,
        data: {
            _token: csrfTokenGlobal,
            id: _forWho,
            kind: sendKind,
        },
        success: function (response) {
            response = JSON.parse(response);
            if (response.status == "ok") {
                if (_kind == "resultFollowers")
                    $(".followerNumber").text(response.result.length);
                createFollower(_kind, response.result);
            }
        },
        // error: err => console.log(err)
    });
}

function createFollower(_Id, _follower) {
    var text = "";
    if (_follower.length == 0) {
        text = `<div class="emptyPeople">
                    <img alt="noData" src="${noDataPic_followerPopUp}" >
                    <span class="text">هیچ کاربری ثبت نشده است</span>
                </div>`;
    } else {
        _follower.map((item) => {
            var followed = "";
            if (item.followed == 1) followed = "followed";

            text += `<div class="peopleRow">
                            <a href="${item.url}" class="pic">
                                <img alt="Dorna News" src="${item.pic}" class="resizeImgClass" style="width: 100%" onload="fitThisImg(this)">
                            </a>
                            <a href="${item.url}" class="name lessShowText">${item.username}</a>`;

            if (item.notMe == 1) {
                text += `<div style="display: flex; margin-right: auto;">
                            <a href="${profileMsgPageUrl_followerPopUp}?user=${item.username}" class="sendMsgButton">ارسال پیام</a>
                            <div class="button ${followed}"  onclick="followUser(this, ${item.userId})"></div>
                        </div>`;
            }

            text += "</div>";
        });
    }
    $("#" + _Id).html(text);
}

function openFollowerSearch(_value) {
    $("#followerModalBody").children().addClass("hidden");
    $("#searchResultFollower").removeClass("hidden");
    $("#followerModalHeaderTabs").addClass("hidden");

    $("#followerModalBody").addClass("openSearch");
    $("#followerModalSearchButton").children().addClass("hidden");
    $("#followerModalSearchButton").find(".iconClose").removeClass("hidden");
}

function searchForFollowerUser(_value) {
    if (ajaxForSearchFollowerUserModal != false) {
        ajaxForSearchFollowerUserModal.abort();
        ajaxForSearchFollowerUserModal = false;
    }

    $("#searchResultFollower").html("");
    if (_value.trim().length > 1) {
        $("#searchResultFollower").html(
            followerPlaceHolder + followerPlaceHolder
        );
        searchForUserCommon(_value)
            .then((response) =>
                createFollower("searchResultFollower", response.userName)
            )
            .catch((err) => console.error(err));
    } else $("#searchResultFollower").html("");
}

function closeFollowerSearch(_value) {
    if (_value == 0) $("#followerModalSearchInput").val("");

    if (_value == 0 || _value.length == 0) {
        $("#followerModalBody").removeClass("openSearch");
        $("#followerModalSearchButton").children().addClass("hidden");
        $("#followerModalSearchButton")
            .find(".searchIcon")
            .removeClass("hidden");

        $("#" + lastFollowerModalOpenPage).removeClass("hidden");
        $("#followerModalHeaderTabs").removeClass("hidden");
        $("#searchResultFollower").addClass("hidden");
    }
}

function followUser(_elem, _id) {
    if (!checkLogin()) return;

    $.ajax({
        type: "POST",
        url: profileSetFollowerUrl_followerPopUp,
        data: {
            _token: csrfTokenGlobal,
            id: _id,
        },
        success: function (response) {
            response = JSON.parse(response);
            if (response.status == "store") {
                $(_elem).addClass("followed");
                showSuccessNotifi(
                    "شما به لیست دوستان افزوده شدید",
                    "left",
                    "var(--koochita-blue)"
                );
                $(".followerNumber").text(response.followerNumber);
                $(".followingNumber").text(response.followingNumber);
            } else if (response.status == "delete") {
                $(_elem).removeClass("followed");
                showSuccessNotifi(
                    "شما از لیست دوستان خارج شدید",
                    "left",
                    "red"
                );
                $(".followerNumber").text(response.followerNumber);
                $(".followingNumber").text(response.followingNumber);
            }
        },
    });
}

// reportModal
function getReportsQuestions(_kindPlaceId) {
    if (getReportsQuestionsKindPlaceId != _kindPlaceId) {
        getReportsQuestionsKindPlaceId = _kindPlaceId;
        $.ajax({
            type: "post",
            url: getReportsDir,
            data: {
                kindPlaceId: _kindPlaceId,
            },
            success: function (response) {
                response = JSON.parse(response);

                let text = "";
                response.result.forEach((item) => {
                    text +=
                        '<div class="row reportsRow">\n' +
                        '<div class="filterItem lhrFilter filter selected radionReportInput">\n' +
                        '<input id="report_' +
                        item.id +
                        '" name="reportValue" type="radio" value="' +
                        item.id +
                        '" onchange="openReportTxt(this.value)"/>\n' +
                        '<label class="reportLabel" for="report_' +
                        item.id +
                        '">\n' +
                        "<span></span>\n" +
                        "<div>" +
                        item.description +
                        "</div>\n" +
                        "</label>\n" +
                        "</div>\n" +
                        "</div>";
                });

                text +=
                    '<div class="row reportsRow">\n' +
                    '<div class="filterItem lhrFilter filter selected radionReportInput">\n' +
                    '<input id="report_more" name="reportValue" type="radio" value="more" onchange="openReportTxt(this.value)"/>\n' +
                    '<label class="reportLabel" for="report_more">\n' +
                    "<span></span>\n" +
                    "<div>سایر موارد</div>\n" +
                    "</label>\n" +
                    "</div>\n" +
                    "</div>";

                $("#reportContainer").html(text);
            },
        });
    } else {
        $("input:radio[name=reportValue]").each(function () {
            $(this).prop("checked", false);
        });
        $("#reportTextDiv").css("display", "none");
    }
}

function sendReport() {
    if (reportValue != 0 && reportLogId != 0 && checkLogin) {
        let reportTxt = $("#reportText").val();

        $.ajax({
            type: "POST",
            url: storeReportUrl,
            data: {
                logId: reportLogId,
                reports: reportValue,
                customMsg: reportTxt,
            },
            success: function (response) {
                if (response == "ok") {
                    closeReportPrompt();
                    showSuccessNotifi(
                        "گزارش شما با موفقیت ثبت شد.",
                        "left",
                        "var(--koochita-blue)"
                    );
                } else if (response == "nok2")
                    showSuccessNotifi(
                        "شما برای مطلب خود نمی توانید گزارش دهید.",
                        "left",
                        "red"
                    );
                else
                    showSuccessNotifi(
                        "در ثبت گزارش مشکلی پیش آمده لطفا دوباره تلاش کنید.",
                        "left",
                        "red"
                    );
            },
            catch(e) {
                console.log(e);
                showSuccessNotifi(
                    "در ثبت گزارش مشکلی پیش آمده لطفا دوباره تلاش کنید.",
                    "left",
                    "red"
                );
            },
        });
    } else if (reportValue == 0)
        showSuccessNotifi("لطفا نوع گزارش خود را مشخص کنید.", "left", "red");
}

function closeReportPrompt() {
    $("#reportPane").css("display", "none");
}

function showReportPrompt(_logId, _kindPlaceId) {
    if (checkLogin) {
        reportValue = 0;
        reportLogId = _logId;
        getReportsQuestions(_kindPlaceId);
        $("#reportPane").css("display", "flex");
    }
}

function openReportTxt(_value) {
    reportValue = _value;
    if (reportValue == "more") $("#reportTextDiv").css("display", "block");
    else $("#reportTextDiv").css("display", "none");
}

//START tvCard section
function createTvCard(_video) {
    var tvHtml = `<div class="tvContentVideo ">
                    <a href="${_video.url}" class="tvVideoPic" target="_blank">
                        <div class="tvImgHover">
                            <img src="${playTvImg}" style="width: 50px">
                        </div>
                        <div class="tvOverPic tvSeenSection">
                            <span class="koochitaTvSeen">${_video.seen}</span>
                            <img src="${eyeTvImg}" style="height: 15px; margin-right: 5px">
                        </div>
                        <div class="tvOverPic tvLikeSection">
                            <div class="tvLike">
                                <span class="koochitaTvDisLikeCount">${_video.like}</span>
                                <i class="DisLikeIcon"></i>
                            </div>
                            <div class="tvLike" style="margin-right: 10px">
                                <span class="koochitaTvLikeCount">${_video.disLike}</span>
                                <i class="LikeIcon"></i>
                            </div>
                        </div>
                        <img src="${_video.pic}" class="koochitaTvImg resizeImgClass" style="width: 100%" onload="fitThisImg(this)">
                    </a>
                    <a href="${_video.url}" class="tvVideoName showLessText" target="_blank">${_video.title}</a>
                    <div class="tvUserContentDiv">
                        <div class="tvUserPic">
                            <img src="${_video.userPic}" class="koochitaTvUserImg resizeImgClass" style="width: 100%" onload="fitThisImg(this)">
                        </div>
                        <div class="tvUserInfo">
                            <div class="tvUserName">${_video.username}</div>
                            <div class="tvUserTime">${_video.time}</div>
                        </div>
                    </div>
                </div>`;

    return tvHtml;
}

function createTvCardPlaceHolder() {
    var card = `<div class="tvContentVideo PH">
                    <div class="tvVideoPic placeHolderAnime"></div>
                    <div class="tvVideoName showLessText placeHolderAnime"></div>
                    <div class="tvUserContentDiv">
                        <div class="tvUserPic placeHolderAnime"></div>
                        <div class="tvUserInfo">
                            <div class="tvUserName placeHolderAnime"></div>
                            <div class="tvUserName placeHolderAnime"></div>
                        </div>
                    </div>
                </div>`;

    return card;
}

//END tvCard section

$(window).on("click", (e) => {
    if (
        $(".modalBlackBack.closeWithClick.showModal:not(.notCloseOnClick)")
            .length > 0
    ) {
        if ($(e.target).is(".modalBlackBack, .showModal, .closeWithClick")) {
            closeMyModal($(e.target).attr("id"));
            opnedMobileFooterId = null; // for placeFooter.js
        }
    }
    if (
        $(".modalBlackBack.fullCenter.showModal:not(.notCloseOnClick)").length >
        0
    ) {
        if ($(e.target).is(".modalBlackBack, .showModal, .fullCenter"))
            closeMyModal($(e.target).attr("id"));
    }

    $(".closeWithOneClick").addClass("hidden");
    $(".moreOptionFullReview").removeClass("bg-color-darkgrey");

    if (openHeadersTab) hideAllTopNavs();
});

$(window).on("resize", (e) => {
    resizeFitImg("resizeImgClass");
});
window.mobileAndTabletCheck = function () {
    let check = false;
    (function (a) {
        if (
            /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(
                a
            ) ||
            /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
                a.substr(0, 4)
            )
        )
            check = true;
    })(navigator.userAgent || navigator.vendor || window.opera);
    return check;
};
