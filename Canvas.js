var canvas = document.getElementById("myCanvas");
var context = canvas.getContext("2d");
var width = canvas.getAttribute("width");
var height = canvas.getAttribute("height");

//Instancia de imagenes
var bgImage = new Image();
var logoImage = new Image();
var playImage = new Image();
var instrucImage = new Image();
var settingsImage = new Image();
var creditsImage = new Image();
var iconImage = new Image();

//Carga de archivos
bgImage.src = "Images/Fondo3.png";
logoImage.src = "Images/LogoS.png";
playImage.src = "Images/JugarS.png";
instrucImage.src = "Images/InstruccionesS.png";
settingsImage.src = "Images/ConfiguracionS.png";
creditsImage.src = "Images/CreditosS.png";
iconImage.src = "Images/iconoS.png";

//Arreglos para el mouse
var buttonX = [300, 240, 235, 280];
var buttonY = [200, 250, 300, 350];
var buttonWidth = [105, 260, 260, 170];
var buttonHeight = [40, 40, 40, 40];

//Dibujado de Imagenes
bgImage.onload = function () {
  context.drawImage(bgImage, 0, 0);
};
logoImage.onload = function () {
  context.drawImage(logoImage, 170, 10);
};
playImage.onload = function () {
  context.drawImage(playImage, buttonX[0], buttonY[0]);
};
instrucImage.onload = function () {
  context.drawImage(instrucImage, buttonX[1], buttonY[1]);
};
settingsImage.onload = function () {
  context.drawImage(settingsImage, buttonX[2], buttonY[2]);
};
creditsImage.onload = function () {
  context.drawImage(creditsImage, buttonX[3], buttonY[3]);
};

//TIMER
var frames = 30;
var timerId = 0;

timerId = setInterval(update, 1000 / frames);

function update() {
  clear();
  move();
  draw();
}

function clear() {
  context.clearRect(0, 0, width, height);
}

//FONDO
var backgroundY = 0;
var speed = 1;

function move() {
  backgroundY -= speed;
  if (backgroundY == -1 * height) {
    backgroundY = 0;
  }
}

function draw() {
  context.drawImage(bgImage, 0, backgroundY);
  context.drawImage(logoImage, 170, 10);
  context.drawImage(playImage, buttonX[0], buttonY[0]);
  context.drawImage(instrucImage, buttonX[1], buttonY[1]);
  context.drawImage(settingsImage, buttonX[2], buttonY[2]);
  context.drawImage(creditsImage, buttonX[3], buttonY[3]);
  if (starVisible == true) {
    context.drawImage(
      iconImage,
      starX[0] - starSize / 2,
      starY[0],
      starSize,
      starHeight
    );
    context.drawImage(
      iconImage,
      starX[1] - starSize / 2,
      starY[1],
      starSize,
      starHeight
    );
  }
}

//MOUSE
var mouseX;
var mouseY;

canvas.addEventListener("mousemove", checkPos);

function checkPos(mouseEvent) {
  mouseX = mouseEvent.pageX - this.offsetLeft;
  mouseY = mouseEvent.pageY - this.offsetTop;

  if (mouseEvent.pageX || mouseEvent.pageY == 0) {
    mouseX = mouseEvent.pageX - this.offsetLeft;
    mouseY = mouseEvent.pageY - this.offsetTop;
  } else if (mouseEvent.offsetX || mouseEvent.offsetY == 0) {
    mouseX = mouseEvent.offsetX;
    mouseY = mouseEvent.offsetY;
  }

  for (i = 0; i < buttonX.length; i++) {
    if (mouseX > buttonX[i] && mouseX < buttonX[i] + buttonWidth[i]) {
      if (mouseY > buttonY[i] && mouseY < buttonY[i] + buttonHeight[i]) {
        starVisible = true;

        starX[0] = buttonX[i] - starWidth / 2 - 2;
        starY[0] = buttonY[i] + 2;
        starX[1] = buttonX[i] + buttonWidth[i] + starWidth / 2;
        starY[1] = buttonY[i] + 2;
      }
    } else {
      starVisible = false;
    }
  }

  if (starSize == starWidth) {
    starRotate = -1;
  }
  if (starSize == 0) {
    starRotate = 1;
  }
  starSize += starRotate;
}

// ESTRELLA
var starX = [0, 0];
var starY = [0, 0];
var starWidth = 46;
var starHeight = 46;

var starVisible = false;
var starSize = starWidth;
var starRotate = 0;
