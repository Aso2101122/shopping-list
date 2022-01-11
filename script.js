let category_select = document.getElementById("category-select");
let place_select = document.getElementById("place-select");
function categoryAdd(){
    if(isNaN(category_select.value)){
        window.location.href = category_select.value;
    }
}
function placeAdd(){
    if(isNaN(place_select.value)){
        window.location.href = place_select.value;
    }
}