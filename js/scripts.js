function getAge(){
    elem=document.getElementById("age");
    var d = new Date();
    var d2 = new Date("1988-11-03");
    age=Math.floor((d-d2)/1000/60/60/24/365.25);
    elem.innerHTML=age;
}

function setPaths(){
    console.log("setting parameters..");
    e=document.querySelector('body');
    e.style.setProperty('--tooltravel', '200px');
    e.style.setProperty('--tooltravel-override', '205px');
    e.style.setProperty('--loop1x', '30px');
    e.style.setProperty('--loop1y', '100px');
}
//CSS:  transform: rotate(30deg);
function decode_eMail(){
    elem=document.getElementById("eMail");
    str="&nbsp; svoboda_loud.com";
    var txt = str.replace("_","_michal@ic");
    elem.innerHTML=txt;
}