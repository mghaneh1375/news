var selectedPlaceId = 0;
var selectedKindPlaceId = 0;
var dontShowfilters = [3, 4, 6, 12, 13];
var nearPlaces = [];
var markerLocation = {lat: 0, lng: 0};
var searchPlaceResult = null;
var searchPlaceAjax = null;
var canChooseFromMap = false;
var yourPosition = 0;
var mainMap;
var mobileListIsFull = false;
var mobileListScrollIsTop = false;
var movePositionMobileList = 0;
var startTouchY = 0;
var startTouchX = 0;

var lastLocationGetData = null;


var localShopFilterCategory = [0];
var totalLocalShopCategories = 0;
var scrollNumber = -170;

var mobileListSectionElement = $('#mobileListSection');
var startMobileListHeight = mobileListSectionElement.height();

var mobileListContentElement = $('.mobileListContent');

$('.topSecMobileList').on('touchstart', e => {
                            var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
                            startTouchY = touch.pageY;
                            startTouchX = touch.pageX;
                            startMobileListHeight = mobileListSectionElement.height();
                        }).on('touchend', e => {
                            var height = mobileListSectionElement.height();
                            var windowHeight = $(window).height();
                            var resultHeight;

                            if(height > windowHeight/2)
                                resultHeight = height > startMobileListHeight ? "full" : "middle";
                            else
                                resultHeight = height > startMobileListHeight ? "middle" : "min";
                            toggleMobileListNearPlace(resultHeight);
                        }).on('touchmove', e => {
                            var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
                            var maxHeight = $(window).height() - 150;
                            var height = startMobileListHeight + startTouchY - touch.pageY;

                            if(height > 75 && height < maxHeight)
                                mobileListSectionElement.height(height);
                            else if(height <= 75)
                                mobileListSectionElement.height(75);
                            else if(height >= maxHeight)
                                mobileListSectionElement.height(maxHeight);
                            else
                                mobileListSectionElement.height(75);
                        });

mobileListContentElement.on('touchstart', e => {
    var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
    movePositionMobileList = touch.pageY;
    mobileListScrollIsTop = mobileListContentElement.scrollTop() == 0;
}).on('touchend', e => {
    var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
    var diff = Math.abs(touch.pageY - movePositionMobileList);

    if(mobileListIsFull && mobileListScrollIsTop && movePositionMobileList < touch.pageY && diff > 75)
        toggleMobileListNearPlace("middle");
    else if(!mobileListIsFull && movePositionMobileList > touch.pageY && diff > 75)
        toggleMobileListNearPlace("full");
});

var turnOffSearchThisAreaButton = () => $('#searchThisAreaButton').addClass('hidden');

function searchThisArea(){
    var bounds = mainMap.getBounds();
    var center = mainMap.getCenter();
    var radius = mainMap.distance(bounds._northEast, center);
    radius = Math.floor(radius/100)/10;

    turnOffSearchThisAreaButton();
    getPlacesWithLocation(center, radius);
}

function toggleMobileListNearPlace(_kind){
    var windowHeight = $(window).height();
    var maxHeight = windowHeight-150;
    // var middleHeight = windowHeight/2-100;
    var middleHeight = 300;
    var minHeight = 60;
    var resultHeight;

    if(_kind == "full")
        resultHeight = maxHeight;
    else if(_kind == "middle")
        resultHeight = middleHeight;
    else
        resultHeight = minHeight;

    if(_kind == "full"){
        $('.sideSection').addClass('fullMobileList');
        mobileListSectionElement.addClass('fullMobileList');
        mobileListIsFull = true;
    }
    else {
        $('.sideSection').removeClass('fullMobileList');
        mobileListSectionElement.removeClass('fullMobileList');
        mobileListIsFull = false;
    }

    mobileListSectionElement.animate({ height: resultHeight}, 300);
}

function createFilterHtml(){
    var text = '';
    var mobile = '';
    for(var item in filterButtons){
            text += `<div class="filKind ${filterButtons[item].enName} filterButtonMap_${filterButtons[item].id} ${item == 1 ? '' : 'offFilter'}" onclick="toggleFilter(${item}, this)">
                            <div class="fullyCenterContent icon ${filterButtons[item].icon}"></div>
                            <div class="name">${filterButtons[item].name}</div>
                        </div>`;

        mobile += `<div id="mobileResultRow_${filterButtons[item].id}" class="typeRow hidden">
                                <div class="header ${filterButtons[item].icon}">${filterButtons[item].nameTitle}</div>
                                <div class="body"></div>
                           </div>`
    }
    $('.filtersSec').html(text);
    $('#mobileShowList').html(mobile);
}

createFilterHtml();

function toggleFilter(_item, _element){
    if(typeof filterButtons[_item].onClick === 'function')
        filterButtons[_item].onClick();
    else {
        var id = filterButtons[_item].id;
        $(_element).toggleClass('offFilter');
        if ($(_element).hasClass('offFilter'))
            dontShowfilters.push(id);
        else {
            var index = dontShowfilters.indexOf(id);
            if (index != -1)
                dontShowfilters.splice(index, 1);
        }
        togglePlaces();
    }
}

function initMapForMyLocation(_hasMyLocation = true){

    mainMap = L.map("map", {
        minZoom: 1,
        maxZoom: 20,
        crs: L.CRS.EPSG3857,
        center: [32.42056639964595, 54.00537109375],
        zoom: 6
    }).on('click', e => {
        if(canChooseFromMap) {
            $('.nearName').text('محل روی نقشه');
            setMarkerToMap(e.latlng.lat, e.latlng.lng);
        }
    });
    L.TileLayer.wmsHeader(
        "https://map.ir/shiveh",
        {
            layers: "Shiveh:Shiveh",
            format: "image/png",
            minZoom: 1,
            maxZoom: 20
        },
        [
            {
                header: "x-api-key",
                value: window.mappIrToken
            }
        ]
    ).addTo(mainMap);

    lastLocationGetData = mainMap.getCenter();
    mainMap.on('moveend', function(e) {
        var bounds = mainMap.getBounds();
        var center = mainMap.getCenter();

        if(lastLocationGetData != null){
            var changeCenter = mainMap.distance(lastLocationGetData, center);
            var radius = mainMap.distance(bounds._northEast, center);

            if(changeCenter > 2500 && radius < 5000)
                $('#searchThisAreaButton').removeClass('hidden');
            else
                turnOffSearchThisAreaButton();
        }

        var zoom = mainMap.getZoom();
        if(zoom < 13)
            $('.mapImgIcon').addClass('smallIcon');
        else
            $('.mapImgIcon').removeClass('smallIcon');

    });

    if(_hasMyLocation)
        getMyLocation();

    // mainMap = new Mapp({
    //     element: '#map',
    //     presets: {
    //         latlng: {
    //             lat: 32,
    //             lng: 52,
    //         },
    //         zoom: 6,
    //     },
    //     apiKey: window.mappIrToken
    // });
    // mainMap.addLayers();

    // var mapOptions = {
    //     center: new google.maps.LatLng(32.42056639964595, 54.00537109375),
    //     zoom: 7,
    //     styles: window.googleMapStyle,
    //     gestureHandling: 'greedy',
    // };
    // var mapElementSmall = document.getElementById('map');
    // mainMap = new google.maps.Map(mapElementSmall, mapOptions);
    //
    // getMyLocation();
    // google.maps.event.addListener(mainMap, 'click', event => {
    //     if(canChooseFromMap) {
    //         $('.nearName').text('محل روی نقشه');
    //         setMarkerToMap(event.latLng.lat(), event.latLng.lng());
    //     }
    // });
}

function setMarkerToMap(_lat, _lng, _id = 0, _name = '', _kindPlaceId = 0){
    _lat = parseFloat(_lat);
    _lng = parseFloat(_lng);

    if(yourPosition != 0)
        mainMap.removeLayer(yourPosition);

    if(_name != '')
        $('.nearName').text(_name);

    selectedPlaceId = _id;
    selectedKindPlaceId = _kindPlaceId;

    canChooseFromMap = false;
    markerLocation = {lat: _lat, lng: _lng};

    yourPosition = L.marker([markerLocation.lat, markerLocation.lng]).addTo(mainMap);
    mainMap.setView([markerLocation.lat, markerLocation.lng], 16);

    // google map
    // yourPosition = new google.maps.Marker({
    //     position:  new google.maps.LatLng(_lat, _lng),
    //     map: mainMap,
    // });
    // mainMap.setCenter({
    //     lat : _lat,
    //     lng : _lng
    // });
    // mainMap.setZoom(16);

    getPlacesWithLocation(markerLocation);
}

function getMyLocation(){
    if (navigator.geolocation) {
        $('.nearName').text('اطراف من');
        navigator.geolocation.getCurrentPosition((position) => setMarkerToMap(position.coords.latitude, position.coords.longitude));
    }
    else
        console.log("Geolocation is not supported by this browser.");
}

function showSearchResultSec(_kind){
    toggleMobileListNearPlace("min");

    if(_kind)
        $('#resultMapSearch').addClass('showResult');
    else
        setTimeout(() => $('#resultMapSearch').removeClass('showResult'), 100);
}

function chooseFromMap(){
    $('.nearName').text('محل را روی نقشه انتخاب کنید');
    toggleMobileListNearPlace("min");
    canChooseFromMap = true;
}

function searchPlace(_element){
    var searchButtonElement = $('.searchButton');
    var value = _element.value;
    if(value.trim().length > 1){
        searchButtonElement.find('.searchIcon').addClass('hidden');
        searchButtonElement.find('.lds-ring').removeClass('hidden');
        if(searchPlaceAjax != null)
            searchPlaceAjax.abort();

        searchPlaceAjax = $.ajax({
            type: 'GET',
            url: `${searchPlaceUrl}?value=${value}&kindPlaceId=${JSON.stringify([1, 3, 4, 6, 12, 13])}&forWhere=map`,
            success: response => {
                if(response.status == 'ok')
                    createSearchResult(response.result);
            },
            error: err => {
                console.error(err);
            }
        })
    }
    else{
        searchButtonElement.find('.searchIcon').removeClass('hidden');
        searchButtonElement.find('.lds-ring').addClass('hidden');
        $('#resultMapSearch').find('.resSec').empty();
    }
}

function createSearchResult(_result){
    var text = '';
    var searchButtonElement = $('.searchButton');

    searchPlaceResult = _result;

    _result.map(item => {
        text += `<div class="res" onclick="choosePlaceForMap(${item.id}, ${item.kindPlaceId})">
                    <div class="icon ${filterButtons[item.kindPlaceId].icon}"></div>
                    <div class="name">${item.name}</div>
                </div>`;
    });
    searchButtonElement.find('.searchIcon').removeClass('hidden');
    searchButtonElement.find('.lds-ring').addClass('hidden');

    $('#resultMapSearch').find('.resSec').html(text);
}

function choosePlaceForMap(_id, _kindPlaceId){
    $('#resultMapSearch').find('.resSec').empty();
    $('#searchPlaceInput').val('');
    searchPlaceResult.map(item => {
        if(item.id == _id && item.kindPlaceId == _kindPlaceId)
            setMarkerToMap(item.C, item.D, item.id, item.name, _kindPlaceId);
    })
}

function getPlacesWithLocation(_center = {lat: 0, lng: 0}, _radius = 0){
    $('.placeListLoading').removeClass('hidden');
    $('.bodySec').removeClass('fullMap');

    var specificData = `${selectedKindPlaceId}_${selectedPlaceId}`;
    $.ajax({
        type: 'get',
        url: `${getPlacesLocationUrl}?lat=${_center.lat}&lng=${_center.lng}&radius=${_radius}&specific=${specificData}`,
        success: response => {
            lastLocationGetData = _center;
            turnOffSearchThisAreaButton();
            if(response.status === "ok")
                createListElement(response.result, response.selectPlace);
        },
    })
}

function createListElement(_result, _selectPlaceOnMap){
    var elements = '';

    nearPlaces.map(place => {
        if(place.marker)
            mainMap.removeLayer(place.marker)
    });

    $('.typeRow .body').empty();
    $('.selectedPlace').empty();

    mobileListContentElement.scrollTop();
    $('.pcPlaceList').scrollTop();


    nearPlaces = _result;
    if(nearPlaces.length == 0){
        var emptyHtml = `<div class="notDataForMyLocationMap">
                                    <div class="imageSection">
                                        <img src="${noDataPicUrl}" alt="notData">
                                    </div>
                                    <div class="textSection">در اطراف محل شما ، مکانی یافت نشد.</div>
                                </div>`;
        $('.pcPlaceList').html(emptyHtml);
        $('#mobileShowList').html(emptyHtml);
    }
    else{
        if(_selectPlaceOnMap != null){
            var html = `<a href="${_selectPlaceOnMap.url}" class="placeCard listPlaceCard_${_selectPlaceOnMap.kindPlaceId}_${_selectPlaceOnMap.id}">
                            <div class="fullyCenterContent img">
                                <img src="${_selectPlaceOnMap.pic}" class="resizeImgClass" onload="fitThisImg(this)">
                            </div>
                            <div class="info">
                                <div class="name showOneLineText">${_selectPlaceOnMap.name}</div>
                                <div class="star">
                                    <div class="ui_bubble_rating bubble_${_selectPlaceOnMap.rate}0"></div>
                                    |
                                    ${_selectPlaceOnMap.review} نقد
                                </div>
                                <div class="address">${_selectPlaceOnMap.address}</div>
                            </div>
                            <a href="${_selectPlaceOnMap.url}" class="showPlacePage" >اطلاعات بیشتر</a>
                        </a>`;
            $('.selectedPlace').html(html);
        }


        nearPlaces.map(item => {
            text = `<div class="placeCard listPlaceCard_${item.kindPlaceId}_${item.id}" onclick="setMarkerToMap(${item.C}, ${item.D}, ${item.id}, '${item.name}', ${item.kindPlaceId})">
                            <div class="fullyCenterContent img">
                                <div class="withoutPic" style="background: ${filterButtons[item.kindPlaceId].color}">
                                ${item.kindPlaceId == 13 ?
                                    `<i class="${localShopCategoriesIcons[item.categoryId]}"></i>` :
                                    `<i class="${filterButtons[item.kindPlaceId].icon}"></i>`
                                }
                                </div>
                                <img src="${item.pic}" class="resizeImgClass hidden" onload="showPicInsteadOfIcon(this)">
                            </div>
                            <div class="info">
                                <div class="name showOneLineText">${item.name}</div>
                                ${item.onlyOnMap == 1 ?
                                    `` :
                                    `<div class="star">
                                        <div class="ui_bubble_rating bubble_${item.rate}0"></div>
                                        |
                                        ${item.review} نقد
                                    </div>`
                                }
                                <div class="address">${item.address}</div>
                            </div>
                            ${item.onlyOnMap == 1 ? '' : `<a href="${item.url}" class="showPlacePage" >اطلاعات بیشتر</a>`}
                        </div>`;
            elements += text;

            // let iconClass = item.kindPlaceId == 13 ? localShopCategoriesIcons[item.categoryId] : filterButtons[item.kindPlaceId].icon;
            item.markerInfo = L.marker([parseFloat(item.C), parseFloat(item.D)], {
                title: item.name,
                icon: L.icon({
                    iconUrl: item.minPic,
                    iconSize: [35, 35], // size of the icon
                    classToImg: `${filterButtons[item.kindPlaceId].classToImg}`
                })
            }).bindPopup(item.name).on('click', () => setMarkerToMap(item.C, item.D, item.id, item.name, item.kindPlaceId));
                // .on('add', mark => {
                    // if(item.kindPlaceId != 13)
                    //     mark.target._icon.classList.add(iconClass);
                    // mark.target._icon.addEventListener('error', function(){
                        // var elm = this;
                        // iconClass.split(' ').forEach(it => elm.classList.add(it));
                        // var $select = $(this);
                        // var $div = $(document.createElement('div'));
                        //
                        // var attributes = $select.prop("attributes");
                        //
                        // $.each(attributes, function() {
                        //     $div.attr(this.name, this.value);
                        // });
                        // $(this).replaceWith($div);

                        // this.src = emptyPic;
                    // })
                // });


            // item.marker = new google.maps.Marker({
            //     position: new google.maps.LatLng(item.C, item.D),
            //     map: mainMap,
            //     lat: item.C,
            //     lng: item.D,
            //     title: item.name,
            //     id: item.id,
            //     icon: {
            //         url: filterButtons[item.kindPlaceId].mapIcon,
            //         scaledSize: new google.maps.Size(30, 35), // scaled size
            //     },
            // });
            // item.marker.addListener('click', function () {
            //     setMarkerToMap(this.lat, this.lng, this.id, this.title)
            // });

            $(`#mobileResultRow_${item.kindPlaceId}`).find('.body').append(text);
        });
        $('.pcPlaceList').html(elements);
    }

    for(var kindPlaceId in filterButtons){
        var mobileResultRow_ = $(`#mobileResultRow_${kindPlaceId}`);
        if(mobileResultRow_.find('.body').html() == '')
            mobileResultRow_.addClass('hidden');
        else
            mobileResultRow_.removeClass('hidden');
    }

    $('.placeListLoading').addClass('hidden');
    toggleMobileListNearPlace("middle");
    togglePlaces();
}

function togglePlaces(){
    nearPlaces.map(item =>{
        if(dontShowfilters.indexOf(item.kindPlaceId) == -1){
            if(item.kindPlaceId === 13){
                if((localShopFilterCategory.length == 1 && localShopFilterCategory[0] == 0) || localShopFilterCategory.indexOf(`${item.categoryId}`) > -1) {
                    $(`.listPlaceCard_${item.kindPlaceId}_${item.id}`).removeClass('hidden');
                    if (item.markerInfo)
                        item.marker = item.markerInfo.addTo(mainMap);
                }
                else{
                    if (item.markerInfo)
                        mainMap.removeLayer(item.marker);
                    $(`.listPlaceCard_${item.kindPlaceId}_${item.id}`).addClass('hidden');
                }
            }
            else {
                if (item.markerInfo)
                    item.marker = item.markerInfo.addTo(mainMap);
                $(`.listPlaceCard_${item.kindPlaceId}_${item.id}`).removeClass('hidden');
            }
            $(`#mobileResultRow_${item.kindPlaceId}`).removeClass('hidden');
        }
        else{
            if(item.marker)
                mainMap.removeLayer(item.marker);
            $(`#mobileResultRow_${item.kindPlaceId}`).addClass('hidden');
            $(`.listPlaceCard_${item.kindPlaceId}_${item.id}`).addClass('hidden');
        }
    })
}



localShopCategories.map(item => item.sub.map(sub => totalLocalShopCategories++));

function showAllLocalShopCategories(_kind, _parent, _element = ''){
    if(_kind === 1)
        $('.categoryFilterInput_'+_parent).prop('checked', true);
    else if(_kind === 0)
        $('.categoryFilterInput_'+_parent).prop('checked', false);
    else if(_kind === -1){
        if($(_element).hasClass('showAll')) {
            $(_element).removeClass('showAll');
            $('.showIconCategories').attr('data-type', 'on').addClass('show');
            showAllLocalShopCategories(0, 0);
        }
        else {
            $(_element).addClass('showAll');
            $('.showIconCategories').attr('data-type', 'off').removeClass('show');
            showAllLocalShopCategories(1, 0);
        }
    }
}

function toggleLocalShopCategories(_id, _element){
    var type = $(_element).attr('data-type');
    if(type === 'on'){
        $(_element).attr('data-type', 'off').removeClass('show');
        showAllLocalShopCategories(1, _id);
    }
    else{
        $(_element).attr('data-type', 'on').addClass('show');
        showAllLocalShopCategories(0, _id);
    }
}

function doLocalShopCategoryFilter(){
    openLoading(false, () => {
        localShopFilterCategory = [];
        var elements = $('.categoryFilterInput_0');
        for(var i = 0; i < elements.length; i++){
            if($(elements[i]).prop('checked'))
                localShopFilterCategory.push($(elements[i]).attr('data-id'))
        }

        if(totalLocalShopCategories === localShopFilterCategory.length)
            localShopFilterCategory = [0];

        if(localShopFilterCategory.length == 0){
            $('.filterButtonMap_13').addClass('offFilter');
            dontShowfilters.push(13);
        }
        else{
            $('.filterButtonMap_13').removeClass('offFilter');
            var index = dontShowfilters.indexOf(13);
            if (index != -1)
                dontShowfilters.splice(index, 1);
        }

        closeLoading();
        closeMyModal('localShopCategoriesModal');
        togglePlaces();
    })
}

function goToMainCategorySection(_id){
    var element = $('#localShopCategoryFilterSection');
    element.animate({
        scrollTop: element.scrollTop() + $('#localShopMainCategorySection_'+_id).position().top + scrollNumber
    }, 1000).scrollTop();
}

function showPicInsteadOfIcon(_element){
    _element.classList.remove('hidden');
    _element.previousElementSibling.classList.add('hidden');
    fitThisImg(_element);
}

$(window).ready(() => {
    toggleMobileListNearPlace("middle");

    if(selectedPlaceFromBack.lat){
        initMapForMyLocation(false);
        setMarkerToMap(selectedPlaceFromBack.lat, selectedPlaceFromBack.lng, selectedPlaceFromBack.id, selectedPlaceFromBack.name, selectedPlaceFromBack.kindPlaceId);
    }
    else
        initMapForMyLocation(true);

});
