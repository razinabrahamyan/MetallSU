var filter = [];

const keypadZero = 48;
const numpadZero = 96;

for(var i = 0; i <= 9; i++){
    filter.push(i + keypadZero);
    filter.push(i + numpadZero);
}

filter.push(8);
filter.push(9);
filter.push(46);
filter.push(37);
filter.push(39);
filter.push(116);


function onKeyDown(e){
    if(filter.indexOf(e.keyCode) < 0 || (this.value.length >= 12 && e.keyCode !==8)){
        e.preventDefault();
        return false;
    }
}
function onChange(e){

    if(!validatePhone(this.value)){
        this.value = ''

        e.preventDefault();
        return false;
    }
}
function formatPhoneText(value){
    value = value.trim().replaceAll("-","");

    if(value.length > 3 && value.length <= 6)
        value = value.slice(0,3) + "-" + value.slice(3);
    else if(value.length > 6)
        value = value.slice(0,3) + "-" + value.slice(3,6) + "-" + value.slice(6);

    return value;
}
function validatePhone(p){
    var phoneRe = /^[(]{0,1}[0-9]{3}[)]{0,1}[-\s\.]{0,1}[0-9]{3}[-\s\.]{0,1}[0-9]{4}$/;
    var digits = p.replace(/\D/g, "");
    return phoneRe.test(digits);
}
function onKeyUp(e){
    var input = e.target;
    var formatted = formatPhoneText(input.value);
    var isError = (validatePhone(formatted) || formatted.length == 0);
    let color,borderWidth;
    if(isError && input.value){
        color ="#0ee40e";
        borderWidth =  "1px" ;
        $('.phone_check').addClass('success');
    }else{
        color = "#ff008d";
        borderWidth = "1px";
        $('.phone_check').removeClass('success');
    }
    input.style.borderColor = color;
    input.style.borderWidth = borderWidth;
    input.value = formatted;
    if(input.value.length > 12){
        input.value = input.value.slice(0,12)
        onKeyUp(e)
    }
}
function setupPhoneFields(className){
    var lstPhoneFields = document.getElementsByClassName(className);

    for(var i=0; i < lstPhoneFields.length; i++){
        var input = lstPhoneFields[i];
        if(input.type.toLowerCase() == "text"){
            input.placeholder = "Напишите телефон (XXX-XXX-XXXX)";
            input.addEventListener("keydown", onKeyDown);
            input.addEventListener("keyup", onKeyUp);
            input.addEventListener("change", onChange);
        }
    }
}
setupPhoneFields("mobile_number");
