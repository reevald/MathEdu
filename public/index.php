<?php
function get_url($addon) {
  $production = true;
  if($production){
    $base_url = 'https://mathedu-ariga.herokuapp.com/';
  }else{
    $base_url = 'http://localhost/MathEdu/public/';
  }
  $base_url .= $addon;
  return $base_url;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
  <title>Ariga - Permainan Tebak Bangun Datar</title>
  <meta name="description" content="Aplikasi Belajar Bangun Datar melalui Game Tebak Gambar Berbasis Artificial Intelligence">
  <link rel="stylesheet" href="<?=get_url('css/app-output.css')?>">
  <!-- Dev css -->
  <!-- <link rel="stylesheet" href="<?=get_url('css/full-tailwind.css')?>"> -->

  <!-- Core Libraries -->
  <!-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script> -->
  <!-- <script src="<?=get_url('js/init-alpine.js')?>"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.6.0/dist/tf.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');
    html * {
      font-family: 'Fredoka One', cursive !important;
    }

    .button-footer{
      border-radius: 15px;
      border-top: 2px transparent solid;
      border-bottom: 2px transparent solid;
      color: #001b4d;
      background-color: #6366F1;
      border-top-color: #A5B4FC;
      border-bottom-color: #3730A3;
      box-shadow: 0 0 0 5px #002043,0 0 0 6px #7c92b0;
    }
    .button-footer:hover{
      background-color: #3B82F6;
      border-top-color: #93C5FD;
      border-bottom-color: #1E40AF;
    }
  </style>

  <script src="<?=get_url('js/p5.min.js')?>"></script>
  <script src="<?=get_url('js/p5.dom.min.js')?>"></script>
  <script src="<?=get_url('js/setup-games.js')?>"></script>
</head>
<body class="flex justify-center">
  <!-- Loader -->
  <!-- <div id="boxLoader" class="flex flex-row items-center justify-center absolute top-0 h-full w-full z-20 bg-green-400">
    <div>Loading...</div>
  </div> -->

  <!-- Menu -->
  <div id="boxStart" class="flex flex-row items-center justify-center absolute top-0 h-full w-full z-20 bg-indigo-400">
    <div onclick="startQna()" class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1">
      <div class="flex flex-row items-center">
        <div class="mr-1">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zM10.622 8.415a.4.4 0 0 0-.622.332v6.506a.4.4 0 0 0 .622.332l4.879-3.252a.4.4 0 0 0 0-.666l-4.88-3.252z" fill="#001b4d"/></svg>
        </div>
        <div>
          <span>
            Mulai
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Header Desktop -->
  <div id="headerPanel" class="flex absolute top-0 h-1/6 2xl:max-h-1/6 z-10 py-0 sm:py-3 invisible">
    <div class="flex flex-row">
      <div class="flex justify-center button-footer select-none bg-blue-400 px-2 py-1 visible" style="width: 500px;">
        <div class="flex flex-col items-center justify-center">
          <div class="justify-center overflow-y-auto">
            <span id="questBox">
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Desktop -->
  <div id="footerPanel" class="flex items-center absolute bottom-0 h-1/6 z-10 invisible">
    <div class="flex flex-row justify-between" style="width: 500px;">
      <!-- Menu -->
      <div class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
        <div class="flex flex-row items-center">
          <div class="mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M5.334 4.545a9.99 9.99 0 0 1 3.542-2.048A3.993 3.993 0 0 0 12 3.999a3.993 3.993 0 0 0 3.124-1.502 9.99 9.99 0 0 1 3.542 2.048 3.993 3.993 0 0 0 .262 3.454 3.993 3.993 0 0 0 2.863 1.955 10.043 10.043 0 0 1 0 4.09c-1.16.178-2.23.86-2.863 1.955a3.993 3.993 0 0 0-.262 3.455 9.99 9.99 0 0 1-3.542 2.047A3.993 3.993 0 0 0 12 20a3.993 3.993 0 0 0-3.124 1.502 9.99 9.99 0 0 1-3.542-2.047 3.993 3.993 0 0 0-.262-3.455 3.993 3.993 0 0 0-2.863-1.954 10.043 10.043 0 0 1 0-4.091 3.993 3.993 0 0 0 2.863-1.955 3.993 3.993 0 0 0 .262-3.454zM13.5 14.597a3 3 0 1 0-3-5.196 3 3 0 0 0 3 5.196z" fill="#001b4d"/></svg>
          </div>
          <div>
            Menu
          </div>
        </div>
      </div>
      <!-- Pen -->
      <div onclick="setTool(0)" class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12.9 6.858l4.242 4.243L7.242 21H3v-4.243l9.9-9.9zm1.414-1.414l2.121-2.122a1 1 0 0 1 1.414 0l2.829 2.829a1 1 0 0 1 0 1.414l-2.122 2.121-4.242-4.242z" fill="#001b4d"/></svg>
      </div>
      <!-- Line -->
      <div onclick="setTool(1)" class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M9.243 19H21v2H3v-4.243l9.9-9.9 4.242 4.244L9.242 19zm5.07-13.556l2.122-2.122a1 1 0 0 1 1.414 0l2.829 2.829a1 1 0 0 1 0 1.414l-2.122 2.121-4.242-4.242z" fill="#001b4d"/></svg>
      </div>
      <!-- Eraser -->
      <div onclick="setEraser(true)" class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 19h7v2h-9l-3.998.002-6.487-6.487a1 1 0 0 1 0-1.414L12.12 2.494a1 1 0 0 1 1.415 0l7.778 7.778a1 1 0 0 1 0 1.414L14 19zm1.657-4.485l3.535-3.536-6.364-6.364-3.535 3.536 6.364 6.364z" fill="#001b4d"/></svg>
      </div>
      <!-- Adjustment -->
      <div class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm0 18c4.427 0 8-3.573 8-8s-3.573-8-8-8a7.99 7.99 0 0 0-8 8c0 4.427 3.573 8 8 8zm0-2c-3.32 0-6-2.68-6-6s2.68-6 6-6 6 2.68 6 6-2.68 6-6 6zm0-8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" fill="#001b4d"/></svg>
      </div>
      <!-- Color -->
      <div class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2c5.522 0 10 3.978 10 8.889a5.558 5.558 0 0 1-5.556 5.555h-1.966c-.922 0-1.667.745-1.667 1.667 0 .422.167.811.422 1.1.267.3.434.689.434 1.122C13.667 21.256 12.9 22 12 22 6.478 22 2 17.522 2 12S6.478 2 12 2zM7.5 12a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm9 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM12 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" fill="#001b4d"/></svg>
      </div>

      <!-- ============= Action ============= -->
      <div id="doneBtn" onclick="process()" class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
        <div class="flex flex-row items-center">
          <div class="mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" fill="#001b4d"/></svg>
          </div>
          <div>
            <span>
              Selesai
            </span>
          </div>
        </div>
      </div>

      <div id="nextBtn" onclick="nextHandler()" class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible" style="display: none;">
        <div class="flex flex-row items-center">
          <div class="mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3.897 17.86l3.91-3.91 2.829 2.828 4.571-4.57L17 14V9h-5l1.793 1.793-3.157 3.157-2.828-2.829-4.946 4.946A9.965 9.965 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.987 9.987 0 0 1-8.103-4.14z" fill="#001b4d"/></svg>
          </div>
          <div>
            <span>
              Lanjut
            </span>
          </div>
        </div>
      </div>

      <div id="repeatBtn" onclick="repeatHandler()" class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible" style="display: none;">
        <div class="flex flex-row items-center">
          <div class="mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm4.82-4.924A7 7 0 0 0 9.032 5.658l.975 1.755A5 5 0 0 1 17 12h-3l2.82 5.076zm-1.852 1.266l-.975-1.755A5 5 0 0 1 7 12h3L7.18 6.924a7 7 0 0 0 7.788 11.418z" fill="#001b4d"/></svg>
          </div>
          <div>
            <span>
              Ulangi
            </span>
          </div>
        </div>
      </div>

      <div id="restartBtn" onclick="restartHandler()" class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible" style="display: none;">
        <div class="flex flex-row items-center">
          <div class="mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm4.82-4.924a7 7 0 1 0-1.852 1.266l-.975-1.755A5 5 0 1 1 17 12h-3l2.82 5.076z" fill="#001b4d"/></svg>
          </div>
          <div>
            <span>
              Mulai
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>