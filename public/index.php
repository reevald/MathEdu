<?php
function get_url($addon) {
  $production = True;
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
<html x-data="htmlData()">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
  <title>Ariga - Permainan Tebak Bangun Datar</title>
  <meta name="description" content="Aplikasi Belajar Bangun Datar melalui Game Tebak Gambar Berbasis Artificial Intelligence">
  <link rel="stylesheet" href="<?=get_url('css/app-output.css')?>">
  <!-- Dev css -->
  <!-- <link rel="stylesheet" href="<?=get_url('css/full-tailwind.css')?>"> -->

  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
  <script src="<?=get_url('js/init-alpine.js')?>"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');
    html * {
      font-family: 'Fredoka One', cursive !important;
    }
    
    body {
      overscroll-behavior: contain;
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

    /* Loader by https://loading.io/css/ */
    .lds-ring {
      display: inline-block;
      position: relative;
      width: 36px;
      height: 36px;
    }
    .lds-ring div {
      box-sizing: border-box;
      display: block;
      position: absolute;
      width: 24px;
      height: 24px;
      margin: 5px;
      border: 5px solid #fff;
      border-radius: 50%;
      animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
      border-color: #000 transparent transparent transparent;
    }
    .lds-ring div:nth-child(1) {
      animation-delay: -0.45s;
    }
    .lds-ring div:nth-child(2) {
      animation-delay: -0.3s;
    }
    .lds-ring div:nth-child(3) {
      animation-delay: -0.15s;
    }
    @keyframes lds-ring {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
  <script type="text/javascript">
    function get_url(addon){
      let production = true;
      let base_url;
      if(production){
        base_url = 'https://mathedu-ariga.herokuapp.com/';
      }else{
        base_url = 'http://localhost/MathEdu/public/';
      }
      return base_url + addon;
    }
  </script>
  <script src="<?=get_url('js/p5.min.js')?>"></script>
  <script src="<?=get_url('js/p5.dom.min.js')?>"></script>
  <script src="<?=get_url('js/setup-games.js?v=0.1')?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.6.0/dist/tf.min.js"></script>
</head>
<body class="flex justify-center">
  <!-- Loader -->
  <div id="boxLoader" class="flex flex-row items-center justify-center absolute top-0 h-full w-full z-30 bg-green-400">
    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    <div>Loading...</div>
  </div>
  <!-- Menu -->
  <div id="boxStart" class="flex flex-row items-center justify-center overflow-y-auto min-h-screen absolute top-0 w-full z-20 bg-indigo-500 py-10">
    <div class="flex flex-col items-center py-8 w-full z-42 max-w-sm bg-white rounded-lg shadow-md">
      <!-- Header menu -->
      <div class="flex flex-row justify-between w-full px-6">
        <div class="cursor-pointer" onclick="showSetting()">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36"><path fill="none" d="M0 0h24v24H0z"/><path d="M5.334 4.545a9.99 9.99 0 0 1 3.542-2.048A3.993 3.993 0 0 0 12 3.999a3.993 3.993 0 0 0 3.124-1.502 9.99 9.99 0 0 1 3.542 2.048 3.993 3.993 0 0 0 .262 3.454 3.993 3.993 0 0 0 2.863 1.955 10.043 10.043 0 0 1 0 4.09c-1.16.178-2.23.86-2.863 1.955a3.993 3.993 0 0 0-.262 3.455 9.99 9.99 0 0 1-3.542 2.047A3.993 3.993 0 0 0 12 20a3.993 3.993 0 0 0-3.124 1.502 9.99 9.99 0 0 1-3.542-2.047 3.993 3.993 0 0 0-.262-3.455 3.993 3.993 0 0 0-2.863-1.954 10.043 10.043 0 0 1 0-4.091 3.993 3.993 0 0 0 2.863-1.955 3.993 3.993 0 0 0 .262-3.454zM13.5 14.597a3 3 0 1 0-3-5.196 3 3 0 0 0 3 5.196z" fill="rgba(0,32,67,1)"/></svg>
        </div>
        <div>
          <span class="text-2xl" style="color: #002043;">Ariga</span>
        </div>
        <div class="cursor-pointer" onclick="showHelp()">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm2-1.645A3.502 3.502 0 0 0 12 6.5a3.501 3.501 0 0 0-3.433 2.813l1.962.393A1.5 1.5 0 1 1 12 11.5a1 1 0 0 0-1 1V14h2v-.645z" fill="rgba(0,32,67,1)"/></svg>
        </div>
      </div>
      <!-- Logo center -->
      <div class="w-1/2 my-10 ml-3">
        <img src="<?=get_url('img/logo-by-illustrations.co.svg')?>">
      </div>
      <!-- Button start -->
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
  </div>


  <!-- Setting panel -->
  <div id="settingPanel" class="flex flex-row items-center justify-center overflow-y-auto min-h-screen absolute top-0 w-full z-30 bg-indigo-500 py-10" style="display: none;">
    <div class="flex flex-col items-center py-8 w-full z-42 max-w-sm bg-white rounded-lg shadow-md">
      <!-- Header menu -->
      <div class="flex flex-row justify-between w-full px-6">
        <div class="cursor-pointer" onclick="showHelp()">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm2-1.645A3.502 3.502 0 0 0 12 6.5a3.501 3.501 0 0 0-3.433 2.813l1.962.393A1.5 1.5 0 1 1 12 11.5a1 1 0 0 0-1 1V14h2v-.645z" fill="rgba(0,32,67,1)"/></svg>
        </div>
        <div>
          <span class="text-2xl" style="color: #002043;">Pengaturan</span>
        </div>
        <div class="cursor-pointer" onclick="closeSetting()">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-11.414L9.172 7.757 7.757 9.172 10.586 12l-2.829 2.828 1.415 1.415L12 13.414l2.828 2.829 1.415-1.415L13.414 12l2.829-2.828-1.415-1.415L12 10.586z" fill="rgba(0,32,67,1)"/></svg>
        </div>
      </div>
      <!-- Main panel -->
      <div class="flex flex-col items-center">
        <img class="w-1/2 my-5" src="<?=get_url('img/undraw_Personal_settings_re_i6w4.svg')?>">
        <div class="flex flex-row justify-between w-full px-6 pt-3">
          <div style="color: #002043;">Layar Penuh</div>
          <div>
            <div id="offFullScreen" onclick="showFullScreen()" class="cursor-pointer">
              <div class="w-12 h-6 rounded-full flex items-center justify-items-start bg-gray-300">
                <div class="absolute w-4 h-4 ml-1 rounded-full bg-white"></div>
              </div>
            </div>
            <div id="onFullScreen" onclick="closeFullScreen()" class="cursor-pointer" style="display: none;">
              <div class="w-12 h-6 rounded-full flex items-center bg-indigo-500">
                <div class="w-4 h-4 ml-auto mr-1 rounded-full bg-white"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex flex-row justify-between w-full px-6 pt-5">
          <div style="color: #002043;">Kualitas Grafik 60 FPS</div>
          <div>
            <div id="off60FPS" onclick="show60FPS()" class="cursor-pointer">
              <div class="w-12 h-6 rounded-full flex items-center justify-items-start bg-gray-300">
                <div class="absolute w-4 h-4 ml-1 rounded-full bg-white"></div>
              </div>
            </div>
            <div id="on60FPS" onclick="close60FPS()" class="cursor-pointer" style="display: none;">
              <div class="w-12 h-6 rounded-full flex items-center bg-indigo-500">
                <div class="w-4 h-4 ml-auto mr-1 rounded-full bg-white"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Help panel -->
  <div id="helpPanel" class="flex flex-row items-center justify-center overflow-y-auto min-h-screen absolute top-0 w-full z-30 bg-indigo-500 py-10" style="display: none;">
    <div class="flex flex-col items-center py-8 w-full z-42 max-w-sm bg-white rounded-lg shadow-md">
      <!-- Header menu -->
      <div class="flex flex-row justify-between w-full px-6">
        <div class="cursor-pointer" onclick="showSetting()">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36"><path fill="none" d="M0 0h24v24H0z"/><path d="M5.334 4.545a9.99 9.99 0 0 1 3.542-2.048A3.993 3.993 0 0 0 12 3.999a3.993 3.993 0 0 0 3.124-1.502 9.99 9.99 0 0 1 3.542 2.048 3.993 3.993 0 0 0 .262 3.454 3.993 3.993 0 0 0 2.863 1.955 10.043 10.043 0 0 1 0 4.09c-1.16.178-2.23.86-2.863 1.955a3.993 3.993 0 0 0-.262 3.455 9.99 9.99 0 0 1-3.542 2.047A3.993 3.993 0 0 0 12 20a3.993 3.993 0 0 0-3.124 1.502 9.99 9.99 0 0 1-3.542-2.047 3.993 3.993 0 0 0-.262-3.455 3.993 3.993 0 0 0-2.863-1.954 10.043 10.043 0 0 1 0-4.091 3.993 3.993 0 0 0 2.863-1.955 3.993 3.993 0 0 0 .262-3.454zM13.5 14.597a3 3 0 1 0-3-5.196 3 3 0 0 0 3 5.196z" fill="rgba(0,32,67,1)"/></svg>
        </div>
        <div>
          <span class="text-2xl" style="color: #002043;">Petunjuk</span>
        </div>
        <div class="cursor-pointer" onclick="closeHelp()">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-11.414L9.172 7.757 7.757 9.172 10.586 12l-2.829 2.828 1.415 1.415L12 13.414l2.828 2.829 1.415-1.415L13.414 12l2.829-2.828-1.415-1.415L12 10.586z" fill="rgba(0,32,67,1)"/></svg>
        </div>
      </div>
      <!-- Main panel -->
      <div class="flex flex-col items-center">
        <img class="w-1/2 my-5" src="<?=get_url('img/undraw_Reading_re_29f8.svg')?>">
        <div style="text-align: center; color: #002043;" class="px-5 pt-3">
          Baca dan pahami pertanyaan yang muncul di bagian kotak atas layar.
        </div>
        <img class="w-1/2 mt-10 mb-5" src="<?=get_url('img/undraw_Specs_re_546x.svg')?>">
        <div style="text-align: center; color: #002043;" class="px-5 pt-3">
          Jawaban berupa bangun datar dan lukiskan ke dalam kotak di tengah layar.
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
      <div class="relative">
        <div>
          <div 
            @click="toggleMenu"
            aria-expanded="false" aria-haspopup="true"
            class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
            <div class="flex flex-row items-center">
              <div class="mr-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M5.334 4.545a9.99 9.99 0 0 1 3.542-2.048A3.993 3.993 0 0 0 12 3.999a3.993 3.993 0 0 0 3.124-1.502 9.99 9.99 0 0 1 3.542 2.048 3.993 3.993 0 0 0 .262 3.454 3.993 3.993 0 0 0 2.863 1.955 10.043 10.043 0 0 1 0 4.09c-1.16.178-2.23.86-2.863 1.955a3.993 3.993 0 0 0-.262 3.455 9.99 9.99 0 0 1-3.542 2.047A3.993 3.993 0 0 0 12 20a3.993 3.993 0 0 0-3.124 1.502 9.99 9.99 0 0 1-3.542-2.047 3.993 3.993 0 0 0-.262-3.455 3.993 3.993 0 0 0-2.863-1.954 10.043 10.043 0 0 1 0-4.091 3.993 3.993 0 0 0 2.863-1.955 3.993 3.993 0 0 0 .262-3.454zM13.5 14.597a3 3 0 1 0-3-5.196 3 3 0 0 0 3 5.196z" fill="#001b4d"/></svg>
              </div>
              <div>
                Menu
              </div>
            </div>
          </div>
        </div>
        <template x-if="is_menu_open">
          <div
            class="visible origin-top-right absolute z-30 bottom-10 left-2 mb-3 w-40 rounded-md shadow-2xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none border-4"
            role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
            tabindex="-1" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click.away="closeMenu" @keydown.escape="closeMenu">
            <!-- Active: "bg-gray-100", Not Active: "" -->
            <a href="#help" onclick="showHelp()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">Petunjuk</a>
            <a href="#setting" onclick="showSetting()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">Pengaturan</a>
            <a href="https://github.com/reevald/MathEdu" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">
              <div class="flex flex-row">
                <div class="mr-1">GitHub</div>
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.05 12.36l-5.656 5.658-1.414-1.415 5.657-5.656-4.95-4.95H18V17.31z" fill="rgba(55,65,81,1)"/></svg>
                </div>
              </div>
            </a>
          </div>
        </template>
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
      <div class="relative">
        <div>
          <div 
            @click="toggleSizePen"
            aria-expanded="false" aria-haspopup="true"
            class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm0 18c4.427 0 8-3.573 8-8s-3.573-8-8-8a7.99 7.99 0 0 0-8 8c0 4.427 3.573 8 8 8zm0-2c-3.32 0-6-2.68-6-6s2.68-6 6-6 6 2.68 6 6-2.68 6-6 6zm0-8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" fill="#001b4d"/></svg>
          </div>
        </div>
        <template x-if="is_size_pen_open">
          <div
            class="visible origin-top-right absolute z-30 bottom-10 left-2 mb-3 w-40 rounded-md shadow-2xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none border-4"
            role="menu" aria-orientation="vertical" aria-labelledby="size-pen-button"
            tabindex="-1" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click.away="closeSizePen" @keydown.escape="closeSizePen">
            <!-- Active: "bg-gray-100", Not Active: "" -->
            <a href="#small" onclick="setPenSize('small')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">Kecil</a>
            <a href="#medium" onclick="setPenSize('medium')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">Sedang</a>
            <a href="#large" onclick="setPenSize('large')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">Besar</a>
          </div>
        </template>
      </div>
      <!-- Color -->
      <div class="relative">
        <div>
          <div 
            @click="toggleColorPen"
            aria-expanded="false" aria-haspopup="true"
            class="button-footer select-none bg-blue-400 cursor-pointer px-2 py-1 visible" color="red">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2c5.522 0 10 3.978 10 8.889a5.558 5.558 0 0 1-5.556 5.555h-1.966c-.922 0-1.667.745-1.667 1.667 0 .422.167.811.422 1.1.267.3.434.689.434 1.122C13.667 21.256 12.9 22 12 22 6.478 22 2 17.522 2 12S6.478 2 12 2zM7.5 12a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm9 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM12 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" fill="#001b4d"/></svg>
          </div>
        </div>
        <template x-if="is_color_pen_open">
          <div
            class="visible origin-top-right absolute z-30 bottom-10 right-2 mb-3 w-40 rounded-md shadow-2xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none border-4"
            role="menu" aria-orientation="vertical" aria-labelledby="color-pen-button"
            tabindex="-1" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click.away="closeColorPen" @keydown.escape="closeColorPen">

            <a href="#red" onclick="setPenColor('red')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">
              <div class="flex flex-row justify-between items-center">
                <div class="mr-1">Merah</div>
                <div class="w-4 h-4" style="background-color: rgb(255, 0, 0);">
                </div>
              </div>
            </a>
            <a href="#orange" onclick="setPenColor('orange')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">
              <div class="flex flex-row justify-between items-center">
                <div class="mr-1">Oren</div>
                <div class="w-4 h-4" style="background-color: rgb(255, 160, 16);">
                </div>
              </div>
            </a>
            <a href="#green" onclick="setPenColor('green')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">
              <div class="flex flex-row justify-between items-center">
                <div class="mr-1">Hijau</div>
                <div class="w-4 h-4" style="background-color: rgb(0, 192, 0);">
                </div>
              </div>
            </a>
            <a href="#blue" onclick="setPenColor('blue')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">
              <div class="flex flex-row justify-between items-center">
                <div class="mr-1">Biru</div>
                <div class="w-4 h-4" style="background-color: rgb(0, 32, 255);">
                </div>
              </div>    
            </a>
            <a href="#purple" onclick="setPenColor('purple')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">
              <div class="flex flex-row justify-between items-center">
                <div class="mr-1">Ungu</div>
                <div class="w-4 h-4" style="background-color: rgb(160, 32, 255);">
                </div>
              </div>    
            </a>
            <a href="#black" onclick="setPenColor('black')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
              tabindex="-1">
              <div class="flex flex-row justify-between items-center">
                <div class="mr-1">Hitam</div>
                <div class="w-4 h-4" style="background-color: rgb(0, 0, 0);">
                </div>
              </div>
            </a>
          </div>
        </template>
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