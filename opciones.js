var canvas = document.getElementById("myCanvas");
var context = canvas.getContext("2d");
var width = canvas.getAttribute('width');
var height = canvas.getAttribute('height');

var bgImage = new Image();
/* var iconImage = new Image(); */
var settingsImage = new Image();

bgImage.src = "Images/Fondo3.png";
/* iconImage.src = "Images/iconoS.png"; */
settingsImage.src = "Images/ConfiguracionS.png";

//Dibujado de imágenes 
bgImage.onload = function(){
	context.drawImage(bgImage, 0 ,0);
};
settingsImage.onload = function(){
    context.drawImage(settingsImage, 200 , 10);
};
/* settingsImage.onload = function(){
    context.drawImage(iconImage, 190 , 10);
};
settingsImage.onload = function(){
    context.drawImage(iconImage, 300 , 10);
}; */

//TIMER
var frames = 30;
var timerId = 0;
 
timerId = setInterval(update, 1000/frames);

function update() {
    clear();
    move();
    draw();
}

function clear(){
    context.clearRect(0, 0, width, height);
}

//FONDO
var backgroundY = 0;
var speed = 1;

function move(){
    backgroundY -= speed;
    if(backgroundY == -1 * height){
        backgroundY = 0;
    }
}

function draw(){
	context.drawImage(bgImage, 0, backgroundY);
	context.drawImage(settingsImage, 200 , 10);
	/* context.drawImage(iconImage, 150 , 10);
	context.drawImage(iconImage, 460 , 10); */
}

//rotación
/*if(starSize == starWidthr){
    starRotate = -1;
}
if(starSize == 0){
    starRotate = 1;
}
starSize += starRotate;

//ESTRELLA
var starX = [0,0];
var starY = [0,0];
var starWidth = 46;
var starHeight = 46;
 
var starSize = starWidth;
var starRotate = 0;*/
