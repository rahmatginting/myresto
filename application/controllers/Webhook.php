<?php defined('BASEPATH') OR exit('No direct script access allowed');

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

use \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder;

class Webhook extends CI_Controller {

  private $bot;
  private $events;
  private $signature;
  private $user;
  public $resto;
  public $categoryID;

  function __construct()
  {
    parent::__construct();
    $this->load->model('tebakkode_m');

    // create bot object
    $httpClient = new CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
    $this->bot  = new LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
    $this->resto="";
    
  }

  public function index()
  {
 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
       
/*      
      //get menu order
      $orders_list="Berikut ini adalah daftar seluruh pesanan Anda: ". "!\n";
      $menu_order = $this->tebakkode_m->getMenuOrder('49', '1');
      foreach($menu_order as $order) {
          if(!empty($order['name'])) {
              $orders_list .= "(" . $order['quantity'] . ")   " . $order['name'] . "    ==> " . $order['description'] . "!\n";
          }
      }
      echo $orders_list;



        //Save Progress debug
        $this->resto = $this->tebakkode_m->getResto('1001');
        $this->categoryID = '101';
        echo $this->resto;
        echo "</br>";

        // update restaurant and table code
        $this->tebakkode_m->setRestoTable('U4035bbada65f83a2ab7253095cd0e6e7', 'TEST');

      $menus=$this->tebakkode_m->getMenu($this->resto,$this->categoryID);
      if (is_array($menus) || is_object($menus))
      {      
        foreach($menus as $menu) {
        
            if(!empty($menu['name'])) 
                //$options[] = new MessageTemplateActionBuilder($menus['name'], $menus['name']);
                echo $menu['name'] . "</br>";
            
        }
      }
echo "</br>";
echo "</br>";
      
      $categorys=$this->tebakkode_m->getCategory($this->resto);
      foreach($categorys as $category) {
        
          if(!empty($category['name']))
              //$options[] = new MessageTemplateActionBuilder($category['name'], $category['name']);
            echo $category['name'] . "</br>";
      }
echo "</br>";
echo "</br>";
     
      // get question from database
      $question = $this->tebakkode_m->getQuestion(2);
      
      // prepare answer options
      for($opsi = "a"; $opsi <= "d"; $opsi++) {
          if(!empty($question['option_'.$opsi]))
              //$options[] = new MessageTemplateActionBuilder($question['option_'.$opsi], $question['option_'.$opsi]);
              echo $question['option_'.$opsi] . "</br>";
      }
      
*/      
           
      echo "Webhook Qitabot Batavia Labs";
      header('HTTP/1.1 400 Only POST method allowed');
      exit;
    }
 
    // get request
    $body = file_get_contents('php://input');
    $this->signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : "-";
    $this->events = json_decode($body, true);
 
    // save log every event requests
    $this->tebakkode_m->log_events($this->signature, $body);
 
    if(is_array($this->events['events'])){
      foreach ($this->events['events'] as $event){
 
        // skip group and room event
        if(! isset($event['source']['userId'])) continue;
 
        // get user data from database
        $this->user = $this->tebakkode_m->getUser($event['source']['userId']);
 
        // if user not registered
        if(!$this->user) $this->followCallback($event);
        else {
          // respond event
          if($event['type'] == 'message'){
            if(method_exists($this, $event['message']['type'].'Message')){
              $this->{$event['message']['type'].'Message'}($event);
            }
          }else if($event['type'] == 'postback'){

            $this->doPostback($event);

          } else {
            if(method_exists($this, $event['type'].'Callback')){
              $this->{$event['type'].'Callback'}($event);
            }
          }
        }
 
      } // end of foreach
    }
    
    // debuging data
    file_put_contents('php://stderr', 'Body: '.$body);
 
  } // end of index.php

 private function followCallback($event)
  {
    $res = $this->bot->getProfile($event['source']['userId']);
    if ($res->isSucceeded())
    {
      $profile = $res->getJSONDecodedBody();

      $img_url="https://myrestobot.herokuapp.com/img/qitabot.jpg";
      $options[] = new MessageTemplateActionBuilder('PESAN MAKANAN', 'MULAI');
      $options[] = new MessageTemplateActionBuilder('PANGGIL PRAMUSAJI', 'WAITER');
      $options[] = new MessageTemplateActionBuilder('MINTA TAGIHAN', 'BILLING');
      
      // prepare button template
      $buttonTemplate = new ButtonTemplateBuilder("Selamat datang " . $profile['displayName'], "Silahkan klik tombol pilihan dibawah", $img_url, $options);
     
      // build message
      $messageBuilder = new TemplateMessageBuilder("Selamat Datang", $buttonTemplate);

      // send message
      $this->bot->replyMessage($event['replyToken'], $messageBuilder);

      // save user data
      $this->tebakkode_m->saveUser($profile);
    }
  }

//====================================================================================
//====================================================================================
//====================================================================================

private function doPostback($event)
{
  $query = $event['postback']['data'];
  
  $this->checkAnswer($query, $event['replyToken']);
  
}


//====================================================================================
//====================================================================================
//====================================================================================
private function textMessage($event)
  {
    $userMessage = $event['message']['text'];
    if($this->user['number'] == 0)
    {
      if(strtolower($userMessage) == 'mulai')
      {
        $this->tebakkode_m->saveProgress('message = mulai');

        // reset score
        $this->tebakkode_m->setScore($this->user['user_id'], 0);
        // update number progress
        $this->tebakkode_m->setUserProgress($this->user['user_id'], 1);

        // send question no.1
        $this->sendQuestion($event['replyToken'], 1);

      } else if(strtolower($userMessage) == 'waiter') {
        $result=$this->tebakkode_m->checkTable($this->user['user_id']);
        $this->tebakkode_m->saveProgress("$result = " . $result);

        if ($this->tebakkode_m->checkTable($this->user['user_id'])==false ||
            is_null($this->tebakkode_m->checkTable($this->user['user_id'])) || 
            $this->tebakkode_m->checkTable($this->user['user_id'])=="0" ||
            $this->tebakkode_m->checkTable($this->user['user_id'])=="") {
              
          $this->tebakkode_m->saveProgress("tidak ada nomor meja");

          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 11);

          // send question no.1
          $this->sendQuestion($event['replyToken'], 11);

        } else {
              
          //Insert Waitress call 
          $this->tebakkode_m->saveCallWaitress($this->user['user_id']);

          $img_url="https://myrestobot.herokuapp.com/img/qitabot.jpg";
          $options[] = new MessageTemplateActionBuilder('PESAN MAKANAN', 'MULAI');
          $options[] = new MessageTemplateActionBuilder('PANGGIL PRAMUSAJI', 'WAITER');
          $options[] = new MessageTemplateActionBuilder('MINTA TAGIHAN', 'BILLING');
        
          // prepare button template
          $buttonTemplate = new ButtonTemplateBuilder("Terima kasih", "Petugas kami akan segera melayani Anda", $img_url, $options);
       
          // build message
          $messageBuilder = new TemplateMessageBuilder("Selamat Datang", $buttonTemplate);

          // send message
          $this->bot->replyMessage($event['replyToken'], $messageBuilder);
              
        }

      } else if(strtolower($userMessage) == 'billing') {
        $result=$this->tebakkode_m->checkTable($this->user['user_id']);
        $this->tebakkode_m->saveProgress("$result = " . $result);

        if ($this->tebakkode_m->checkTable($this->user['user_id'])==false ||
            is_null($this->tebakkode_m->checkTable($this->user['user_id'])) || 
            $this->tebakkode_m->checkTable($this->user['user_id'])=="0" ||
            $this->tebakkode_m->checkTable($this->user['user_id'])=="") {

          $this->tebakkode_m->saveProgress("tidak ada nomor meja");
          
          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 12);

          // send question no.1
          $this->sendQuestion($event['replyToken'], 12);

        } else {

          //Insert Bill call 
          $this->tebakkode_m->saveCallBilling($this->user['user_id']);

          $img_url="https://myrestobot.herokuapp.com/img/qitabot.jpg";
          $options[] = new MessageTemplateActionBuilder('PESAN MAKANAN', 'MULAI');
          $options[] = new MessageTemplateActionBuilder('PANGGIL PRAMUSAJI', 'WAITER');
          $options[] = new MessageTemplateActionBuilder('MINTA TAGIHAN', 'BILLING');
        
        // prepare button template
          $buttonTemplate = new ButtonTemplateBuilder("Terima kasih", "Petugas kami akan segera melayani Anda", $img_url, $options);
       
          // build message
          $messageBuilder = new TemplateMessageBuilder("Selamat Datang", $buttonTemplate);

          // send message
          $this->bot->replyMessage($event['replyToken'], $messageBuilder);
        }
       
      } else {
        $message = 'Silakan ketik pesan "MULAI" untuk melakukan pemesanan.';
        $textMessageBuilder = new TextMessageBuilder($message);

        $this->bot->replyMessage($event['replyToken'], $textMessageBuilder);
      }
 
    // if user already begin test
    } else {
      $this->checkAnswer($userMessage, $event['replyToken']);
    }
  }


  //====================================================================================
  //====================================================================================
  //====================================================================================
    private function stickerMessage($event)
  {
    // create sticker message
    $stickerMessageBuilder = new StickerMessageBuilder(1, 106);
 
    // create text message
    $message = 'Silakan ketik pesan "MULAI" untuk memulai pemesanan.';
    $textMessageBuilder = new TextMessageBuilder($message);
 
    // merge all message
    $multiMessageBuilder = new MultiMessageBuilder();
    $multiMessageBuilder->add($stickerMessageBuilder);
    $multiMessageBuilder->add($textMessageBuilder);
 
    // send message
    $this->bot->replyMessage($event['replyToken'], $multiMessageBuilder);
  }
  
  //====================================================================================
  //====================================================================================
  //====================================================================================
  public function sendQuestion($replyToken, $questionNum=1)
  {
    // get question from database
    $question = $this->tebakkode_m->getQuestion($questionNum);
 
    if ($questionNum==1) {
      $message = 'Silakan ketik nomor meja dimana Anda berada saat ini.';
      $messageBuilder = new TextMessageBuilder($message);

    }else if ($questionNum==2) {

      $resto=$this->tebakkode_m->getResto($this->user['user_id']);
      $categorys=$this->tebakkode_m->getCategory($resto);
      foreach($categorys as $category) {
          if(!empty($category['name'])) {
              $options[] = new MessageTemplateActionBuilder($category['name'], $category['name']);
          }
      }

      // prepare button template
      //$buttonTemplate = new ButtonTemplateBuilder($question['number']."/10", $question['text'], $question['image'], $options);
      $restoDesc=$this->tebakkode_m->getRestoDesc($resto);
           
      $imageURL="https://myrestobot.herokuapp.com/img/categories.jpg";
      $buttonTemplate = new ButtonTemplateBuilder($restoDesc['name'], $restoDesc['address'], $imageURL, $options);
      //$buttonTemplate = new ButtonTemplateBuilder("Kategori menu", "Pilih kategori menu yang ingin Anda pesan", $imageURL, $options);
                                                  
      // build message
      $messageBuilder = new TemplateMessageBuilder("Kategori Menu", $buttonTemplate);
      
    }else if ($questionNum==3) {
    
      $columns = array();
      $img_url = "https://res.cloudinary.com/db9zavtws/image/upload/v1486222467/4_n5ai4k.png";
      $menus=$this->tebakkode_m->getMenu($this->resto,$this->categoryID);
      if (is_array($menus) || is_object($menus))
      {
        foreach($menus as $menu) {
          if(!empty($menu['name'])){
            //$options[] = new MessageTemplateActionBuilder($menu['name'], $menu['name']);
            //$actions = array("Pesan","Kembali");
            //$actions = array(new PostbackTemplateActionBuilder("Pesan","code=".$menu['code']."&menu=".$menu['name']),
              //new UriTemplateActionBuilder("View","http://www.google.com"));
            $actions = array(
              new PostbackTemplateActionBuilder("PESAN","code=".$menu['code']."&menu=".$menu['name']),
              new PostbackTemplateActionBuilder("KEMBALI","KEMBALI"),
              new PostbackTemplateActionBuilder("SELESAI","SELESAI")
            );
            
            $column = new CarouselColumnTemplateBuilder($menu['name'], $menu['description'], $img_url , $actions);
            $columns[] = $column;
          }
        }
      }
      $carousel = new CarouselTemplateBuilder($columns);
      $messageBuilder = new TemplateMessageBuilder("Daftar Menu", $carousel);
    }else if ($questionNum==4) {
      //Tanya Jumlah porsi

      //get menu code
      $menu_code = $this->tebakkode_m->getMenuProg($this->user['user_id']);

      //get menu code
      $resto = $this->tebakkode_m->getResto($this->user['user_id']);

      //get menu name
      $menu_name = $this->tebakkode_m->getMenuName($resto, $menu_code);

      $message = "Masukkan \"JUMLAH PORSI\" " . $menu_name . " yang diinginkan.";
      $messageBuilder = new TextMessageBuilder($message);

    }else if ($questionNum==5) {
      //Tanya Jumlah porsi

      //get menu code
      $menu_code = $this->tebakkode_m->getMenuProg($this->user['user_id']);

      //get menu code
      $resto = $this->tebakkode_m->getResto($this->user['user_id']);

      //get menu name
      $menu_name = $this->tebakkode_m->getMenuName($resto, $menu_code);

      $message = "Masukkan \"KETERANGAN\" untuk pesanan " . $menu_name . '. Jika tidak ada keterangan ketik angka 0 [NOL]';
      $messageBuilder = new TextMessageBuilder($message);

      //============================================
      //Mulai dari sini ganti proses pemesanan
      //============================================
    }else if ($questionNum==6) {
      $actions = array (
        New PostbackTemplateActionBuilder("Pesan Lagi", "ans=Y"),
        New PostbackTemplateActionBuilder("Selesai", "ans=N")
      );
      $button = new ConfirmTemplateBuilder("Apakah Anda ingin menambah pesanan lagi?", $actions);
      $messageBuilder = new TemplateMessageBuilder("confim message", $button);

    }else if ($questionNum==7) {
      //get menu code
      $resto = $this->tebakkode_m->getResto($this->user['user_id']);

      $orderID = $this->tebakkode_m->getOrder($this->user['user_id']);

      //get menu order
      try {
        $orders_list="Berikut ini adalah daftar seluruh pesanan Anda: ". "\n";
        $menu_order = $this->tebakkode_m->getMenuOrder($orderID, $resto);
        foreach($menu_order as $order) {
            if(!empty($order['name'])) {
                $orders_list .= "(" . $order['quantity'] . ")   " . $order['name'] . "    ==> " . $order['description'] . "\n";
            }
        }
        $textMessageBuilder = new TextMessageBuilder($orders_list);

        //create confirmation
        $actions = array (
          New PostbackTemplateActionBuilder("Konfirm", "ans=Y"),
          New PostbackTemplateActionBuilder("Edit", "ans=N")
        );
        $button = new ConfirmTemplateBuilder("Pilih \"KONFIRM\" untuk konfirmasi daftar pesanan dan pilih \"EDIT\" untuk merubah pesanan ", $actions);
        $confirmMsgBuilder = new TemplateMessageBuilder("confirm order list", $button);

        // merge all message
        $messageBuilder = new MultiMessageBuilder();
        $messageBuilder->add($textMessageBuilder);
        $messageBuilder->add($confirmMsgBuilder);
      }
      
      catch(Exception $e) {
        $this->tebakkode_m->saveProgress('error = ' . $e->getMessage());
      }

    }else if ($questionNum==8) {
      //Progress Complete

      $img_url="https://myrestobot.herokuapp.com/img/thanks01.jpg";
      $options[] = new MessageTemplateActionBuilder('MULAI LAGI', 'MULAI');
      $options[] = new MessageTemplateActionBuilder('PANGGIL PRAMUSAJI', 'WAITER');
      $options[] = new MessageTemplateActionBuilder('MINTA TAGIHAN', 'BILLING');
      
      // prepare button template
      $buttonTemplate = new ButtonTemplateBuilder("Pemesanan berakhir", "Silahkan menunggu pesanan Anda", $img_url, $options);
     
      // build message
      $messageBuilder = new TemplateMessageBuilder("Terimakasih", $buttonTemplate);

    }else if ($questionNum==11 || $questionNum==12) {
    
      $message = 'Silakan ketik nomor meja dimana Anda berada saat ini.';
      $messageBuilder = new TextMessageBuilder($message);

    }

    // send message
    $response = $this->bot->replyMessage($replyToken, $messageBuilder);;

  }

  //====================================================================================
  //====================================================================================
  //====================================================================================  
  private function checkAnswer($message, $replyToken)
  {
    /*
    // if answer is true, increment score
    if($this->tebakkode_m->isAnswerEqual($this->user['number'], $message)){
      $this->user['score']++;
      $this->tebakkode_m->setScore($this->user['user_id'], $this->user['score']);
    }
    */
    
    if($this->user['number'] < 10)
    {
      // update number progress
      $this->tebakkode_m->setUserProgress($this->user['user_id'], $this->user['number'] + 1);

      if ($this->user['number']==0) {
        $img_url="https://myrestobot.herokuapp.com/img/qitabot.jpg";
        $options[] = new MessageTemplateActionBuilder('PESAN MAKANAN', 'MULAI');
        $options[] = new MessageTemplateActionBuilder('PANGGIL PRAMUSAJI', 'WAITER');
        $options[] = new MessageTemplateActionBuilder('MINTA TAGIHAN', 'BILLING');
      
        // prepare button template
        $buttonTemplate = new ButtonTemplateBuilder("Selamat datang", "Silahkan pilih tombol dibawah", $img_url, $options);
     
        // build message
        $messageBuilder = new TemplateMessageBuilder("Selamat Datang", $buttonTemplate);

        // send message
        $response = $this->bot->replyMessage($replyToken, $messageBuilder);

      } else if ($this->user['number']==1) {

        $this->resto = $this->tebakkode_m->checkResto($message);
        $this->tebakkode_m->saveProgress('$resto = ' . $this->resto);

        if ($this->resto=="" || $this->resto==false) 
        {
          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 1);
          
          $message = "Kami tidak menemukan \"NOMOR MEJA\" yang Anda masukkan. ". "\n" . "\n";
          $message .= "Silahkan ulangi ketik nomor meja dimana Anda berada saat ini.";
          $messageBuilder = new TextMessageBuilder($message);
      
          // send message
          $response = $this->bot->replyMessage($replyToken, $messageBuilder);

        }else {

          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], $this->user['number'] + 1);

          // update table code
          $this->tebakkode_m->setTable($this->user['user_id'], $message);

          // update restaurant code
          $this->tebakkode_m->setResto($this->user['user_id'], $this->resto);
         
          // send next question
          $this->sendQuestion($replyToken, $this->user['number'] + 1);
        }

        
      }else if ($this->user['number']==2) {
        if ($message!="SELESAI") {
          //get restaurant id
          $this->resto = $this->tebakkode_m->getResto($this->user['user_id']);
                 
          // get Category ID
          $this->categoryID = $this->tebakkode_m->getCategoryID($this->resto, $message);

          // update Category code
          $this->tebakkode_m->setCategory($this->user['user_id'], $this->categoryID);

          // send next question
          $this->sendQuestion($replyToken, $this->user['number'] + 1);

        } else {
          //get order ID
          $order = $this->tebakkode_m->getOrder($this->user['user_id']);

          if ($order!=0) {

            //publish complete order 
            $this->tebakkode_m->setOrderComplete($order);
          }


          //set user progress finish = 0
          $this->tebakkode_m->setUserProgress($this->user['user_id'],0);

          //set user clear progress 
          $this->tebakkode_m->setClearProgress($this->user['user_id']);

        }


      }else if ($this->user['number']==3) {
        
        parse_str($message, $parseMessage);
        if ($message=="KEMBALI") {

          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 2);

          // send next question
          $this->sendQuestion($replyToken, 2);

        }else if ($message=="SELESAI") {

          //Set confirmation order 
          $this->tebakkode_m->setUserProgress($this->user['user_id'],5);

          // send confirmation order list
          $this->sendQuestion($replyToken, 5);


        }else {
          //Proses order makanan

          //update progress menu code
          $this->tebakkode_m->setMenuProg($this->user['user_id'], $parseMessage["code"]);

          // send next question
          $this->sendQuestion($replyToken, $this->user['number'] + 1);

        }

      }else if ($this->user['number']==4) {
        
        //if (is_int($message)) { 
        if ( strval($message) != strval(intval($message)) ) {
          //$message variable is not an integer

          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 4);

          // send next question ==> Jumlah Porsi
          $this->sendQuestion($replyToken, 4);        

        } else { 
          $menu_code = $this->tebakkode_m->getMenuProg($this->user['user_id']);

          $orderID = $this->tebakkode_m->getOrder($this->user['user_id']);
          $user_name = $this->tebakkode_m->getUserName($this->user['user_id']);
          $resto = $this->tebakkode_m->getResto($this->user['user_id']);
          $table = $this->tebakkode_m->getTable($this->user['user_id']);
          if ($orderID == 0)
          {
            //save menu order header
            $lastID = $this->tebakkode_m->saveOrderHed($this->user['user_id'], $user_name, $resto, $table);

            //get last order id
            $orderID = $this->tebakkode_m->searchOrderID($this->user['user_id'], $resto, $table);

            //update order ID
            $this->tebakkode_m->setOrder($this->user['user_id'], $orderID);

          }
          
          //save menu order detail
          $this->tebakkode_m->saveOrderDet($orderID, $menu_code, $message);

          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 5);

          // send next question ==> Keterangan pesanan
          $this->sendQuestion($replyToken, 5);

        }

      }else if ($this->user['number']==5) {
        //Keterangan order

        //get menu code
        $menu_code = $this->tebakkode_m->getMenuProg($this->user['user_id']);

        //get last order id
        $resto = $this->tebakkode_m->getResto($this->user['user_id']);
        $table = $this->tebakkode_m->getTable($this->user['user_id']);
        $orderID = $this->tebakkode_m->searchOrderID($this->user['user_id'], $resto, $table);

        if ($message=='0') { 
        
          //update keterangan order detail
          $this->tebakkode_m->setKeterangan($orderID, $menu_code, "N/A");

        } else {

          //update keterangan order detail
          $this->tebakkode_m->setKeterangan($orderID, $menu_code, $message);
        }

        // update number progress
        $this->tebakkode_m->setUserProgress($this->user['user_id'], 6);

        // send next question ==> Keterangan pesanan
        $this->sendQuestion($replyToken, 6);


      }else if ($this->user['number']==6) {
        //Tambah pesanan atau tidak

        parse_str($message, $parseMessage);
        if ($parseMessage["ans"] == "Y") {

          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 2);

          // send next question
          $this->sendQuestion($replyToken, 2);
          
        }else if ($parseMessage["ans"] == "N") {
          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 7);

          // send next question
          $this->sendQuestion($replyToken, 7);

        }

      }else if ($this->user['number']==7) {
        parse_str($message, $parseMessage);
        if ($parseMessage["ans"] == "Y") {  
          //Proses complete order

          //get order ID
          $order = $this->tebakkode_m->getOrder($this->user['user_id']);

          if ($order!=0) {
            //publish complete order 
            $this->tebakkode_m->setOrderComplete($order);
          }

          //set user progress finish = 0
          $this->tebakkode_m->setUserProgress($this->user['user_id'],0);

          //set user clear progress 
          $this->tebakkode_m->setClearProgress($this->user['user_id']);

          // send next question
          $this->sendQuestion($replyToken, 8);

        }else if ($parseMessage["ans"] == "N") {
          //Rubah pesanan disini

          //Insert Waitress call 
          $this->tebakkode_m->saveCallWaitress($this->user['user_id']);

          //set user progress finish = 0
          $this->tebakkode_m->setUserProgress($this->user['user_id'],0);

          //set user clear progress 
          $this->tebakkode_m->setClearProgress($this->user['user_id']);

          $img_url="https://myrestobot.herokuapp.com/img/qitabot.jpg";
          $options[] = new MessageTemplateActionBuilder('PESAN MAKANAN', 'MULAI');
          $options[] = new MessageTemplateActionBuilder('PANGGIL PRAMUSAJI', 'WAITER');
          $options[] = new MessageTemplateActionBuilder('MINTA TAGIHAN', 'BILLING');
        
          // prepare button template
          $buttonTemplate = new ButtonTemplateBuilder("Terima kasih", "Petugas kami akan segera melayani Anda", $img_url, $options);
       
          // build message
          $messageBuilder = new TemplateMessageBuilder("Selamat Datang", $buttonTemplate);

          // send message
          $response = $this->bot->replyMessage($replyToken, $messageBuilder);

        }

      }
            
    } else if ($this->user['number']==11 || $this->user['number']==12) {
      //Check nomor meja exist
      $this->resto = $this->tebakkode_m->checkResto($message);
      $this->tebakkode_m->saveProgress('$resto = ' . $this->resto);

      if ($this->resto=="" || $this->resto==false) 
      {
        
        $message = "Kami tidak menemukan \"NOMOR MEJA\" yang Anda masukkan. ". "\n" . "\n";
        $message .= "Silahkan ulangi ketik nomor meja dimana Anda berada saat ini.";
        $messageBuilder = new TextMessageBuilder($message);
    
        // send message
        $response = $this->bot->replyMessage($replyToken, $messageBuilder);

      }else {

        // update table code
        $this->tebakkode_m->setTable($this->user['user_id'], $message);

        // update restaurant code
        $this->tebakkode_m->setResto($this->user['user_id'], $this->resto);
       
        if ($this->user['number']==11) {
          //Insert Waitress call 
          $this->tebakkode_m->saveCallWaitress($this->user['user_id']);
        }else if ($this->user['number']==12) {
          //Insert Billing call 
          $this->tebakkode_m->saveCallBilling($this->user['user_id']);
        }

        $img_url="https://myrestobot.herokuapp.com/img/qitabot.jpg";
        $options[] = new MessageTemplateActionBuilder('PESAN MAKANAN', 'MULAI');
        $options[] = new MessageTemplateActionBuilder('PANGGIL PRAMUSAJI', 'WAITER');
        $options[] = new MessageTemplateActionBuilder('MINTA TAGIHAN', 'BILLING');
      
        // prepare button template
        $buttonTemplate = new ButtonTemplateBuilder("Terima kasih", "Petugas kami akan segera melayani Anda", $img_url, $options);
     
        // build message
        $messageBuilder = new TemplateMessageBuilder("Selamat Datang", $buttonTemplate);

        // send message
        $this->bot->replyMessage($replyToken, $messageBuilder);

      }

    } else {
      // create user score message
      $message = 'Skormu '. $this->user['score'];
      $textMessageBuilder1 = new TextMessageBuilder($message);
 
      // create sticker message
      $stickerId = ($this->user['score'] < 8) ? 100 : 114;
      $stickerMessageBuilder = new StickerMessageBuilder(1, $stickerId);
 
      // create play again message
      $message = ($this->user['score'] < 8) ?
'Wkwkwk! Nyerah? Ketik "MULAI" untuk bermain lagi!':
'Great! Mantap bro! Ketik "MULAI" untuk bermain lagi!';
      $textMessageBuilder2 = new TextMessageBuilder($message);
 
      // merge all message
      $multiMessageBuilder = new MultiMessageBuilder();
      $multiMessageBuilder->add($textMessageBuilder1);
      $multiMessageBuilder->add($stickerMessageBuilder);
      $multiMessageBuilder->add($textMessageBuilder2);
 
      // send reply message
      $this->bot->replyMessage($replyToken, $multiMessageBuilder);
      $this->tebakkode_m->setUserProgress($this->user['user_id'], 0);
    }
  }
  
}
