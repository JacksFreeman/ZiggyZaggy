<html>
  <head>
    <title>2.3 Colisiones</title>
    <link rel="stylesheet" href="CanvasDesign.css" />
    <script
      type="text/javascript"
      src="js/libs/jquery/jquery-2.1.4.min.js"
    ></script>
    <script type="text/javascript" src="js/libs/three/three.js"></script>
    <script type="text/javascript" src="js/libs/three/MTLLoader.js"></script>
    <script type="text/javascript" src="js/libs/three/OBJLoader.js"></script>
    <script type="text/javascript" src="js/libs/mifacebook.js"></script>
    <script id="vertexShader" type="x-shader/x-vertex">
      varying vec2 vUv;
      void main()
      {
              vUv = uv;
              gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );
      }
    </script>
    <script id="fragmentShader" type="x-shader/x-vertex">
      uniform sampler2D baseTexture;
      uniform float baseSpeed;
      uniform sampler2D noiseTexture;
      uniform float noiseScale;
      uniform float alpha;
      uniform float time;

      varying vec2 vUv;
      void main()
      {
          vec2 uvTimeShift = vUv + vec2( -0.7, 1.5 ) * time * baseSpeed;
          vec4 noiseGeneratorTimeShift = texture2D( noiseTexture, uvTimeShift );
          vec2 uvNoiseTimeShift = vUv + noiseScale * vec2( noiseGeneratorTimeShift.r, noiseGeneratorTimeShift.b );
          vec4 baseColor = texture2D( baseTexture, uvNoiseTimeShift );

          baseColor.a = alpha;
          gl_FragColor = baseColor;
      }
    </script>
    <script type="text/javascript">
      var scene;
      var camera;
      var camera2;
      var renderer;
      var controls;
      var objetosConColision = [];
      var clock;
      var deltaTime;
      var keys = {};
      var ancla;
      var ancla2;
      var facebookPuntuaction, nombreUsuario;
      let starGeo, stars;

      $(document).ready(function () {
        $("#fbutton").click(function () {
          nombreUsuario = $("#fuser").val();
          var nameP1 = facebookPuntuaction;
          console.log(fuser);
          console.log(nameP1);
          var clickBtnValue = $(this).val();

          ajax_url = "<?php echo ('data.php'); ?>";

          (function ($) {
            $(document).ready(function () {
              var my_data = {
                action: "GuardarP1",
                name1: nombreUsuario,
                nameP1: nameP1,
              };

              $.post(ajax_url, my_data, function (response) {
                // This will make an AJAX request upon page load
                alert("Got this from the server: " + response);
              });
            });
          })(jQuery);

          // data = {};
          // jQuery.post(ajax_url, data, function (response) {
          //   alert("Got this from the server: " + response);
          // });
        });
      });

      // AGUA

      var noiseTexture = new THREE.ImageUtils.loadTexture("Images/cloud.png");
      noiseTexture.wrapS = noiseTexture.wrapT = THREE.RepeatWrapping;

      var lavaTexture = new THREE.ImageUtils.loadTexture("Images/water.jpg");
      lavaTexture.wrapS = lavaTexture.wrapT = THREE.RepeatWrapping;

      var customUniforms = {
        baseTexture: { type: "t", value: lavaTexture },
        baseSpeed: { type: "f", value: 0.05 },
        noiseTexture: { type: "t", value: noiseTexture },
        noiseScale: { type: "f", value: 0.5337 },
        alpha: { type: "f", value: 0.8 },
        time: { type: "f", value: 1.0 },
      };

      // -------------------------

      var isWorldReady = [false, false, false, false, false];
      $(document).ready(function () {
        rayCaster = new THREE.Raycaster();

        setupScene();

        // ------------------------------

        var customMaterial = new THREE.ShaderMaterial({
          uniforms: customUniforms,
          vertexShader: document.getElementById("vertexShader").textContent,
          fragmentShader: document.getElementById("fragmentShader").textContent,
        });

        customMaterial.opacity = 0.9;
        customMaterial.transparent = true;
        customMaterial.side = THREE.DoubleSide;

        var flatGeometry = new THREE.BoxGeometry(1000, 0, 500);
        var surface = new THREE.Mesh(flatGeometry, customMaterial);
        surface.position.set(0, -2, 0);
        // scene.add(surface);

        // ------------------------------

        ancla.misRayos = [
          new THREE.Vector3(0, -1, 0),
          new THREE.Vector3(0, 1, 0),
        ];

        loadOBJWithMTL("assets/", "ball2.obj", "ball2.mtl", (object) => {
          object.position.z = -10;
          object.position.x = 0;
          object.position.y = 5;

          object.name = "lancha";

          scene.add(object);
          camera.lookAt(object.position);

          object.add(camera);
          object;
          //object.add(ancla);
          isWorldReady[1] = true;
        });

        loadOBJWithMTL("assets/", "camino1.obj", "camino1.mtl", (object) => {
          //object.scale.set(2.5,2.5,2.5);
          object.position.z = -15;
          object.position.x = -8;
          object.position.y = 0;
          object.rotation.y = THREE.Math.degToRad(45);

          object.castShadow = true;
          object.receiveShadow = true;

          object.name = "camino";

          scene.add(object);

          objetosConColision.push(object);

          isWorldReady[0] = true;
        });

        loadOBJWithMTL("assets/", "camino2.obj", "camino2.mtl", (object) => {
          //object.scale.set(2.5,2.5,2.5);
          object.position.z = -1585;
          object.position.x = 18;
          object.position.y = 0;
          object.rotation.y = THREE.Math.degToRad(45);
          object.castShadow = true;
          object.receiveShadow = true;

          object.name = "camino2";

          scene.add(object);

          objetosConColision.push(object);
        });

        loadOBJWithMTL("assets/", "camino3.obj", "camino3.mtl", (object) => {
          //object.scale.set(2.5,2.5,2.5);
          object.position.z = -800;
          object.position.x = 18;
          object.position.y = 0;
          object.rotation.y = THREE.Math.degToRad(45);
          object.castShadow = true;
          object.receiveShadow = true;

          object.name = "camino2";

          scene.add(object);

          objetosConColision.push(object);
        });

        loadOBJWithMTL("assets/", "City2.obj", "City2.mtl", (object) => {
          object.scale.set(12, 12, 12);
          object.position.z = -270;
          object.position.x = -8;
          object.position.y = -120;

          object.castShadow = true;
          object.receiveShadow = true;

          object.name = "city1";

          scene.add(object);

          isWorldReady[2] = true;
        });

        loadOBJWithMTL("assets/", "island.obj", "island.mtl", (object) => {
          object.scale.set(20, 20, 20);
          object.position.z = -1250;
          object.position.x = -8;
          object.position.y = -220;
          object.rotation.y = THREE.Math.degToRad(-45);
          object.castShadow = true;
          object.receiveShadow = true;

          object.name = "city2";

          object.children[0].material.materials[1] = customMaterial;

          scene.add(object);

          isWorldReady[3] = true;
        });

        loadOBJWithMTL("assets/", "kameri.obj", "kameri.mtl", (object) => {
          object.scale.set(7, 7, 7);
          object.position.z = -2200;
          object.position.x = -8;
          object.position.y = -300;
          object.rotation.y = THREE.Math.degToRad(-45);
          object.castShadow = true;
          object.receiveShadow = true;

          object.name = "city2";

          scene.add(object);

          isWorldReady[4] = true;
        });

        render();

        document.addEventListener("keydown", onKeyDown);
        document.addEventListener("keyup", onKeyUp);
      });

      function loadOBJWithMTL(path, objFile, mtlFile, onLoadCallback) {
        var mtlLoader = new THREE.MTLLoader();
        mtlLoader.setPath(path);
        mtlLoader.load(mtlFile, (materials) => {
          var objLoader = new THREE.OBJLoader();
          objLoader.setMaterials(materials);
          objLoader.setPath(path);
          objLoader.load(objFile, (object) => {
            onLoadCallback(object);
          });
        });
      }

      function onKeyDown(event) {
        keys[String.fromCharCode(event.keyCode)] = true;
      }
      function onKeyUp(event) {
        keys[String.fromCharCode(event.keyCode)] = false;
      }

      var forward;
      var forwardz;
      var forwardy;
      var stop = false;
      forwardz = -10;
      forwardy = -20;
      var gameover = false;
      var points = 0;
      var final = false;
      var r = 0;
      var g = 0;
      var b = 0.65;

      function render() {
        requestAnimationFrame(render);

        starGeo.vertices.forEach((p) => {
          p.velocity += p.acceleration;
          p.y -= p.velocity;

          if (p.y < -200) {
            p.y = 200;
            p.velocity = 0;
          }
        });
        starGeo.verticesNeedUpdate = true;

        lancha = scene.getObjectByName("lancha", true);

        if (gameover == true && final == false) {
          var modal = document.getElementById("MyModal");
          var span = document.getElementsByClassName("close")[0];

          function openModal() {
            modal.style.display = "block";
          }

          openModal();

          span.onclick = function () {
            modal.style.display = "none";
          };

          /*window.onclick = function(event) {
      	if(event.target == modal){
      		modal.style.display = "none";
      	}
      }*/

          var go = document.getElementById("gameover");
          var titulo = document.createElement("h1");
          //var titletxt = document.createTextNode('GAME OVER');
          var puntuacion = document.createElement("h2");
          var titletxt2 = document.createTextNode("Puntuacion: " + points / 20);

          go.appendChild(titulo);
          // titulo.appendChild(titletxt);
          go.appendChild(puntuacion);
          puntuacion.appendChild(titletxt2);
          facebookPuntuaction = points / 20;

          final = true;

          // ventanita modal
        }

        if (keys["A"]) {
          forward = -10;
        } else if (keys["D"]) {
          forward = 10;
        }

        /*camera.translateX(forward * deltaTime);
      camera.translateZ(forwardz * deltaTime);*/

        if (points / 20 >= 472 && r >= 0.65 && g >= 0.65 && b >= 0.65) {
          renderer.setClearColor(new THREE.Color(r, g, b));
          r -= 0.01;
          g -= 0.01;
          b -= 0.01;
        } else if (points / 20 >= 472 && r >= 0 && g >= 0) {
          renderer.setClearColor(new THREE.Color(r, g, b));
          r -= 0.01;
          g -= 0.01;
        } else if (points / 20 >= 994 && b >= 0) {
          renderer.setClearColor(new THREE.Color(r, g, b));

          b -= 0.01;
        }

        if (
          isWorldReady[0] &&
          isWorldReady[1] &&
          isWorldReady[2] &&
          isWorldReady[3] &&
          gameover == false &&
          isWorldReady[4]
        ) {
          deltaTime = clock.getDelta();

          points++;

          for (var i = 0; i < ancla.misRayos.length; i++) {
            var rayo = ancla.misRayos[i];

            rayCaster.set(lancha.position, rayo);

            var colisiones = rayCaster.intersectObjects(
              objetosConColision,
              true
            );

            if (colisiones.length > 0 && colisiones[0].distance < 1) {
              lancha.translateX(forward * deltaTime);
              lancha.translateZ(forwardz * deltaTime);
              lancha.translateY(-forwardy * deltaTime);

              console.log("Estamos colisionando con algo!");
            } else {
              console.log("YA NO!");
              lancha.translateY(forwardy * deltaTime);
              //lancha.translateY(forwardy * deltaTime);
            }

            if (lancha.position.y <= -20) {
              gameover = true;
            }

            customUniforms.time.value += deltaTime;
          }
        }

        renderer.render(scene, camera);
      }

      function setupScene() {
        var visibleSize = {
          width: window.innerWidth,
          height: window.innerHeight,
        };
        clock = new THREE.Clock();
        scene = new THREE.Scene();
        camera = new THREE.PerspectiveCamera(
          75,
          visibleSize.width / visibleSize.height,
          0.1,
          600
        );
        camera.position.z = 30;
        camera.position.y = 30;

        var geo = new THREE.BoxGeometry(1.5, 1.5, 1.5);
        var material = new THREE.MeshLambertMaterial({
          color: new THREE.Color(1, 1, 1),
        });
        ancla = new THREE.Object3D();

        renderer = new THREE.WebGLRenderer({ precision: "mediump" });
        renderer.setClearColor(new THREE.Color(1, 1, 1));
        renderer.setPixelRatio(visibleSize.width / visibleSize.height);
        renderer.setSize(visibleSize.width, visibleSize.height);

        // --------------------------------------------------------------

        starGeo = new THREE.Geometry();
        for (let i = 0; i < 6000; i++) {
          let star = new THREE.Vector3(
            Math.random() * 600 - 300,
            Math.random() * 600 - 300,
            Math.random() * 600 - 300
          );
          star.velocity = 0;
          star.acceleration = 0.0002;
          starGeo.vertices.push(star);
        }

        let sprite = new THREE.TextureLoader().load("Images/star.png");
        let starMaterial = new THREE.PointsMaterial({
          color: 0xaaaaaa,
          size: 0.7,
          map: sprite,
        });

        stars = new THREE.Points(starGeo, starMaterial);
        scene.add(stars);

        // --------------------------------------------------------------
        var ambientLight = new THREE.AmbientLight(
          new THREE.Color(1, 1, 1),
          0.4
        );
        scene.add(ambientLight);

        var directionalLight = new THREE.DirectionalLight(
          new THREE.Color(1, 1, 1),
          0.9
        );
        directionalLight.position.set(0, 0, 1);

        directionalLight.castShadow = true;
        directionalLight.position.set(2, 1, 0);

        directionalLight.shadow.mapSize.width = 1024; // default
        directionalLight.shadow.mapSize.height = 1024; // default
        directionalLight.shadow.camera.near = 0.5; // default
        directionalLight.shadow.camera.far = 4000; // default
        scene.add(directionalLight);

        var grid = new THREE.GridHelper(50, 10, 0xffffff, 0xffffff);
        grid.position.y = -1;
        // scene.add(grid);

        render();
        $("#scene-section").append(renderer.domElement);
      }

      function shareFB() {
        var score = facebookPuntuaction;
        shareScore(score);
      }
    </script>
  </head>

  <body>
    <audio
      src="assets/fondo.mp3"
      type="audio/mpeg"
      id="musica"
      autoplay="autoplay"
    ></audio>
    <div id="scene-section"></div>

    <!-- ventana -->
    <div id="MyModal" class="modal">
      <!-- contenido -->
      <div class="modal-content">
        <span class="close">&times;</span>

        <div class="modal-body">
          <div class="modal-header">
            <div class="column-header">
              <img class="estrella" src="Images/icono.png" />
            </div>
            <div class="column-header">
              <img id="modal-header" src="Images/GameOver.png" />
            </div>
            <div class="column-header">
              <img class="estrella" src="Images/icono.png" />
            </div>
          </div>
          <br /><br /><br /><br />
          <div id="gameover"></div>
          <!-- form para poner tu nombresito -->

          <label for="fuser">Usuario:</label>
          <input type="text" name="fuser" id="fuser" />
          <br />
          <button id="fbutton" value="GuardarP1">Subir puntuacion</button>

          <div class="modal-footer">
            <div class="column">
              <a href="index.html">
                <button id="regresarBtn">Volver al menu</button>
              </a>
            </div>
            <div class="column">
              <a href="Solo.html">
                <button id="jugarBtn">Jugar de nuevo</button>
              </a>
            </div>
            <div class="column">
              <button onclick="shareFB();" id="facebookBtn">
                Compartir en Facebook
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
