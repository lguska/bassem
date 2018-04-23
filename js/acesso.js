function light(sw) {
    var pic;
    if (sw == 0) {
        pic = "pic_bulboff.gif"
    } else {
        pic = "pic_bulbon.gif"
    }
    document.getElementById('myImage').src = pic;
}

function mudarData(){
    var d = new Date("yyyy-MM-dd");
    console.log(d);
    document.getElementById("datentrada").value=d;
    alert(d);
	//document.getElementById("datentrada").value=dt;
}

function pegarData(){
	dt=document.getElementById("datentrada").value;
}
