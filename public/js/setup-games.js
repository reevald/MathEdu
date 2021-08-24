// parameter canvas
var cnv, sub_cnv, length_box, x_box, y_box;
var img_logo_box, img_star_point, data_img;
var x1, x2, y1, y2;
var crop_status = false;

// model with tensorflow (json)
var model;

// parameter draw tools
var color_pen = 'black';
var click1 = false; 
var eraser = false;
var click2;
var touch_status = false;
var finish_status = false;
var currX, currY;
var pen_size = 10;
var pen_state = -1; // -1 = none, 0 = pencil, 1 = line
var curr_pen_state;
var start_pen = false;
var first_show = true;
var list_data_line_tool = [];

// parameter games
var data_qna, ans_user, ans_qna, quest_qna, num_level, num_qna, length_qna_level;
var show_quest_status, show_star_level_status, show_notice_result_status, result_qna_status;

async function preload(){
  let box_loader = document.getElementById('boxLoader');
  box_loader.style.display = 'flex';
  model = await tf.loadLayersModel(get_url('model/tfjs-quant-model/quant-model.json'));
  box_loader.style.display = 'none';
}

function setup(){
  frameRate(42);
  textFont('Fredoka One');
  cnv = createCanvas(window.innerWidth, window.innerHeight);
  cnv.position(0, 0);
  sub_cnv = createGraphics(window.innerWidth, window.innerHeight);
  data_qna = loadJSON('json/data-level.json');
  img_logo_box = loadImage(get_url('img/logo-box-200x200.svg'));
  img_star_point = loadImage("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='60' height='60'%3E%3Cpath fill='none' d='M0 0h24v24H0z'/%3E%3Cpath d='M12 .5l4.226 6.183 7.187 2.109-4.575 5.93.215 7.486L12 19.69l-7.053 2.518.215-7.486-4.575-5.93 7.187-2.109L12 .5zM10 12H8a4 4 0 0 0 7.995.2L16 12h-2a2 2 0 0 1-3.995.15L10 12z' fill='rgba(230,126,34,1)'/%3E%3C/svg%3E");
}

function draw(){
  // Fix (bug?) stroke weight line on touch (mobile)
  if(touch_status == true){
    strokeWeight(pen_size * 2);
  }else{
    strokeWeight(pen_size);
  }
  sub_cnv.strokeWeight(pen_size);

  stroke(color_pen);
  sub_cnv.stroke(color_pen);

  // crop with white background
  if (crop_status == true){
    background(255);
  }

  // watermark or logo center
  if (start_pen == false){
    background(255);
    drawBox();
    image(
      img_logo_box,
      (window.innerWidth - length_box / 2) / 2,
      (window.innerHeight - length_box / 2) / 2,
      length_box / 2,
      length_box / 2
    );
  }

  // clean up line and pen (only show watermark)
  if (eraser != true){
    // displays all previously created lines
    if(list_data_line_tool.length > 0){
      list_data_line_tool.forEach(
        coord => line(coord[0], coord[1], coord[2], coord[3])
      );
    }

    // show current line
    if(pen_state == 1){
      lineTool();
    }

    // show pen
    if(pen_state == 0){
      pencil();
    }

    image(sub_cnv, 0, 0);
  }else{
    sub_cnv.clear();
    list_data_line_tool = [];
    eraser = false;
  }

  if(crop_status == false && start_pen == false){
    if(show_star_level_status == true){
      showStarLevel(
        'Level ' + num_level,
        num_qna + '/' + length_qna_level
      );
    }
    if(show_quest_status == true){
      showQuest(quest_qna);
    }
    if(show_notice_result_status == true){
      showNoticeResult(result_qna_status, userAns);
    }
    if(finish_status == true){
      showFinish();
    }
  }
}

function showStarLevel(level, score){
  let 
    x_stdr = (window.innerWidth - length_box) / 2 + length_box - 85,
    y_stdr = (window.innerHeight - length_box) / 2 + 15,
    size_stdr = 24,
    x_stdr_n = x_stdr + 30,
    y_stdr_n = y_stdr + 20,
    size_stdr_n = 20,
    x_stdr_l = window.innerWidth - x_stdr_n - 35;

  // size and position adjustment
  if(length_box >= 450){
    x_stdr -= 50;
    y_stdr += 5;
    size_stdr += 15;
    x_stdr_n -= 30;
    y_stdr_n += 15;
    size_stdr_n += 10;
  }

  image(
    img_star_point,
    x_stdr,
    y_stdr,
    size_stdr,
    size_stdr
  )

  push();
  textSize(size_stdr_n);
  stroke('white');
  strokeWeight(3);
  fill(0);
  text(
    level,
    x_stdr_l,
    y_stdr_n
  )
  text(
    score,
    x_stdr_n,
    y_stdr_n
  )
  pop();
}

function lineTool(){
  x1 = currX;
  y1 = currY;
  x2 = mouseX;
  y2 = mouseY;

  // show dinamic line (current line)
  if(click1 == true){
    line(x1, y1, x2, y2);
  }
  
  // save line to show next frame
  if(click2 != undefined && click2 == true){
    list_data_line_tool.push([x1, y1, x2, y2]);
    click2 = false;
    click1 = false;
  }
}

function pencil(){
  if (mouseIsPressed && pen_state == 0) {
    if (touches.length > 0){
      sub_cnv.stroke(color_pen);
      sub_cnv.line(currX / 2, currY / 2, pmouseX / 2, pmouseY / 2);
    }else{
      sub_cnv.stroke(color_pen);
      sub_cnv.line(mouseX, mouseY, pmouseX, pmouseY);
    }
  }
}

function setEraser(value){
  eraser = value;
}

function drawBox(){
  // size adjustment based on screen size
  let
    w_screen = window.innerWidth,
    h_screen = window.innerHeight,
    max_length_box = 500,
    proportion_height = 4 / 6,
    new_length_box = h_screen * proportion_height;

  length_box = new_length_box <= max_length_box ? new_length_box : max_length_box;
  length_box = w_screen <= length_box ? w_screen - 10 : length_box;

  x_box = (w_screen - length_box) / 2;
  y_box = (h_screen - length_box) / 2;

  push();
  stroke(240);
  strokeWeight(7);
  noFill();
  drawingContext.setLineDash([10,15]);
  rect(x_box, y_box, length_box, length_box);
  pop();

  if(first_show){
    let header_panel = document.getElementById('headerPanel');
    let footer_panel = document.getElementById('footerPanel');
    let scaleNum = length_box / 500;
    header_panel.style.transform = 'scale('+scaleNum+')';
    footer_panel.style.transform = 'scale('+scaleNum+')';
    first_show = false;
  }
}

// =========== Auto call function ===========

function windowResized() {
  resizeCanvas(window.innerWidth, window.innerHeight);
  start_pen = false;
  draw();

  let scaleNum = length_box / 500;
  let header_panel = document.getElementById('headerPanel');
  let footer_panel = document.getElementById('footerPanel');
  header_panel.style.transform = 'scale('+scaleNum+')';
  footer_panel.style.transform = 'scale('+scaleNum+')';
}

function mouseDragged(){
  if(touches.length > 0 && pen_state == 0){
    currX = mouseX;
    currY = mouseY;
  }
}

function touchStarted(){
  if(touches.length > 0 && click1 == true){
    mouseX = pmouseX;
    mouseY = pmouseY;
  }else{
    currX = mouseX;
    currY = mouseY;
    pmouseX = mouseX;
    pmouseY = mouseY;
  }
  touch_status = true;
}

// shortcut
function keyTyped() {
  if (key == 'c') {
    start_pen = false;
    eraser = true;
    draw();
  }
  if (key == 'l') {
    click1 = false;
    pen_state = 1;
  }
  if (key == 'p') {
    pen_state = 0;
  }
}

function keyPressed() {
  if (keyCode == LEFT_ARROW && pen_size > 1) {
    pen_size -= 1;
  }
  if (keyCode == RIGHT_ARROW) {
    pen_size += 1;
  }
}

function mousePressed(event){
  if(event.target.tagName == "CANVAS"){
    if(click1 == false){
      currX = mouseX;
      currY = mouseY;
      click1 = true;
      click2 = false;
    }else{
      click1 = false;
      click2 = true;
    }
    if(curr_pen_state != undefined && pen_state == -1){
      pen_state = curr_pen_state;
    }
    draw();
  }else{
    if(pen_state != -1){
      curr_pen_state = pen_state;
    }
    pen_state = -1;
  }
}

// =========== Classification handler ===========

async function process(){
  // Reference : 
  // https://js.tensorflow.org/api/3.6.0/#browser.fromPixels
  // https://developer.mozilla.org/en-US/docs/Web/API/ImageData

  // clean up watermark
  crop_status = true;
  start_pen = true;
  draw();
  let crop_cnv = cnv.get(x_box, y_box, length_box, length_box);
  start_pen = false;
  crop_status = false;

  // get pixel data from crop
  crop_cnv.loadPixels();
  data_img = new ImageData(crop_cnv.pixels, length_box);
  detectCall(data_img);

  // uncomment to see crop result
  // let croper = createGraphics(length_box, length_box);
  // croper.image(crop_cnv, 0, 0);
  // save(croper,'croperCanvas', 'png');
  // croper.remove();
}

// label class
const label_result = {
  0: "circle",
  1: "kite",
  2: "parallelogram",
  3: "square",
  4: "trapezoid",
  5: "triangle"
};

async function detectCall(clmpArray) {
  let tensor = tf.browser.fromPixels(clmpArray)
    .resizeNearestNeighbor([224, 224])
    .div(tf.scalar(255))
    .expandDims();

  let predictions = await model.predict(tensor).data();
  let results = Array.from(predictions)
    .map(function (p, i) {
      return {
        probability: p,
        className: label_result[i]
      };
    }).sort(function (a, b) {
      return b.probability - a.probability;
    }).slice(0, 1);

  userAns = results[0].className;

  if(userAns == ans_qna){
    result_qna_status = true;
  }else{
    result_qna_status = false;
  }

  show_notice_result_status = true;
  repeatOrSetup(result_qna_status);
}

function setTool(code){
  pen_state = code;
  click1 = false;
  draw();
}

// =========== QnA handler ===========
function showQuest(quest){
  document.getElementById('questBox').textContent = quest;
}

function showFinish(){
  push();
  textSize(20);
  stroke('white');
  strokeWeight(3);
  fill(0);
  text(
    "Finish!",
    window.innerWidth / 2 - 40,
    window.innerHeight / 2
  );
  text(
    "Ulangi lagi?",
    window.innerWidth / 2 - 70,
    window.innerHeight / 2 + 20
  );
  pop();
}

function showNoticeResult(result, userAns){
  if(result == true){
    push();
    textSize(20);
    stroke('white');
    strokeWeight(3);
    fill(0);
    text(
      "Good Job!",
      window.innerWidth / 2 - 40,
      window.innerHeight / 2
    );
    text(
      "Jawabanmu : "+userAns,
      window.innerWidth / 2 - 70,
      window.innerHeight / 2 + 20
    );
    pop();
  }else{
    push();
    textSize(20);
    stroke('white');
    strokeWeight(3);
    fill(0);
    text(
      "Wrong Answer!",
      window.innerWidth / 2 - 70,
      window.innerHeight / 2
    );
    text(
      "Jawabanmu : "+userAns,
      window.innerWidth / 2 - 70,
      window.innerHeight / 2 + 20
    );
    pop();
  }
}

// Main QnA
function startQna(){
  if(num_level == undefined){
    num_level = 1;
  }
  if(num_qna == undefined){
    num_qna = 1;
  }
  document.getElementById('boxStart').style.display = "none";
  quest_qna = data_qna.level[num_level-1].qna[num_qna-1].quest;
  ans_qna = data_qna.level[num_level-1].qna[num_qna-1].ans;
  length_qna_level = data_qna.level[num_level-1].qna.length;
  show_quest_status = true;
  show_star_level_status = true;
  show_notice_result_status = false;
}

function repeatOrSetup(result){
  let restart_btn = document.getElementById('restartBtn');
  let done_btn = document.getElementById('doneBtn');
  let repeat_btn = document.getElementById('repeatBtn');
  let next_btn = document.getElementById('nextBtn');

  if(result == true){
    if(num_level > data_qna.level.length){
      finish_status = true;
    }
    restart_btn.style.display = "none";
    done_btn.style.display = "none";
    repeat_btn.style.display = "none";
    next_btn.style.display = "flex";
  }else{
    restart_btn.style.display = "none";
    repeat_btn.style.display = "flex";
    done_btn.style.display = "none";
    next_btn.style.display = "none";
  }
}

function repeatHandler(){
  let restart_btn = document.getElementById('restartBtn');
  let done_btn = document.getElementById('doneBtn');
  let repeat_btn = document.getElementById('repeatBtn');
  let next_btn = document.getElementById('nextBtn');

  show_notice_result_status = false;
  start_pen = false;
  eraser = true;
  draw();

  restart_btn.style.display = "none";
  done_btn.style.display = "flex";
  repeat_btn.style.display = "none";
  next_btn.style.display = "none";
}

function nextHandler(){
  let restart_btn = document.getElementById('restartBtn');
  let done_btn = document.getElementById('doneBtn');
  let repeat_btn = document.getElementById('repeatBtn');
  let next_btn = document.getElementById('nextBtn');

  if(num_qna >= length_qna_level){
    num_level += 1;
    num_qna = 1;
  }else{
    num_qna += 1;
  }
  if(num_level > data_qna.level.length){
    let restart_btn = document.getElementById('restartBtn');
    let done_btn = document.getElementById('doneBtn');
    let repeat_btn = document.getElementById('repeatBtn');
    let next_btn = document.getElementById('nextBtn');

    quest_qna = "Selamat Anda berhasil menyelesaikan semua tantangan!";
    
    finish_status = true;
    show_quest_status = true;
    show_star_level_status = false;
    show_notice_result_status = false;
    start_pen = false;
    eraser = true;
    draw();

    restart_btn.style.display = "flex";
    done_btn.style.display = "none";
    repeat_btn.style.display = "none";
    next_btn.style.display = "none";
  }else{
    quest_qna = data_qna.level[num_level-1].qna[num_qna-1].quest;
    ans_qna = data_qna.level[num_level-1].qna[num_qna-1].ans;
    length_qna_level = data_qna.level[num_level-1].qna.length;
    
    show_quest_status = true;
    show_star_level_status = true;
    show_notice_result_status = false;
    start_pen = false;
    eraser = true;
    draw();

    restart_btn.style.display = "none";
    done_btn.style.display = "flex";
    repeat_btn.style.display = "none";
    next_btn.style.display = "none";
  }
}


function restartHandler(){
  let restart_btn = document.getElementById('restartBtn');
  let done_btn = document.getElementById('doneBtn');
  let repeat_btn = document.getElementById('repeatBtn');
  let next_btn = document.getElementById('nextBtn');

  num_qna = 1;
  num_level = 1;
  
  quest_qna = data_qna.level[num_level-1].qna[num_qna-1].quest;
  ans_qna = data_qna.level[num_level-1].qna[num_qna-1].ans;
  length_qna_level = data_qna.level[num_level-1].qna.length;
  
  finish_status = false;
  show_quest_status = true;
  show_star_level_status = true;
  show_notice_result_status = false;
  start_pen = false;
  eraser = true;
  draw();
  
  restart_btn.style.display = "none";
  done_btn.style.display = "flex";
  repeat_btn.style.display = "none";
  next_btn.style.display = "none";
}