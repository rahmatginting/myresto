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
    
  }

  public function index()
  {
 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
/*      
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
           
      echo "Hello Resto05!";
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
 
      // create welcome message
      $message  = "Salam kenal, " . $profile['displayName'] . "!\n";
      $message .= "Layanan chatbot ini ditujukan untuk pemesanan dan reservasi" . "!\n";
      $message .= "Silakan ketik \"MULAI\" untuk melakukan pemesanan.";
      $textMessageBuilder = new TextMessageBuilder($message);
 
      // create sticker message
      $stickerMessageBuilder = new StickerMessageBuilder(1, 3);
 
      // merge all message
      $multiMessageBuilder = new MultiMessageBuilder();
      $multiMessageBuilder->add($textMessageBuilder);
      $multiMessageBuilder->add($stickerMessageBuilder);
 

      // send reply message
      $this->bot->replyMessage($event['replyToken'], $multiMessageBuilder);
 
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
        // reset score
        $this->tebakkode_m->setScore($this->user['user_id'], 0);
        // update number progress
        $this->tebakkode_m->setUserProgress($this->user['user_id'], 1);

        // send question no.1
        $this->sendQuestion($event['replyToken'], 1);

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
      /*
      $options[] = new MessageTemplateActionBuilder('NOMOR MEJA', 'NOMOR MEJA');

      // prepare button template
      $buttonTemplate = new ButtonTemplateBuilder("Ketik nomor meja dimana Anda berada saat ini", $question['text'], $question['image'], $options);
     
      // build message
      $messageBuilder = new TemplateMessageBuilder("Nomor Meja", $buttonTemplate);
      */

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
      $this->tebakkode_m->saveProgress('masuk01');
      $this->tebakkode_m->saveProgress('juml='count($restoDesc));
      
      if (is_array($restoDesc) || is_object($restoDesc)) {
        $this->tebakkode_m->saveProgress('masuk02');
        if(!empty($restoDesc['name'])) {
          $this->tebakkode_m->saveProgress('masuk03');
          $alamat = $restoDesc['description'] . " " . $restoDesc['address'];
        }
      }
      
      $imageURL="https://myrestobot.herokuapp.com/img/categories.jpg";
      $buttonTemplate = new ButtonTemplateBuilder($restoDesc['name'], $alamat, $imageURL, $options);
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
      $messageBuilder = new TemplateMessageBuilder("Carousel Demo", $carousel);

    }else if ($questionNum==4) {
      //get menu code
      $menu_code = $this->tebakkode_m->getMenuProg($this->user['user_id']);

      //get menu code
      $resto = $this->tebakkode_m->getResto($this->user['user_id']);

      //get menu name
      $menu_name = $this->tebakkode_m->getMenuName($resto, $menu_code);

      $actions = array (
        New PostbackTemplateActionBuilder("Ya", "ans=Y"),
        New PostbackTemplateActionBuilder("Tidak", "ans=N")
      );
      $button = new ConfirmTemplateBuilder("Apakah Anda yakin pesan " . $menu_name ." ?", $actions);
      $messageBuilder = new TemplateMessageBuilder("confim message", $button);

    }else if ($questionNum==5) {
      //Progress Complete

      //set user progress finish = 0
      $this->tebakkode_m->setUserProgress($this->user['user_id'],0);

      $img_url="https://myrestobot.herokuapp.com/img/thanks01.jpg";
      $options[] = new MessageTemplateActionBuilder('MULAI LAGI', 'MULAI');
      //$options[] = new MessageTemplateActionBuilder('PANGGIL PRAMUSAJI', 'WAITER');
      
      // prepare button template
      $buttonTemplate = new ButtonTemplateBuilder("Pemesanan berakhir", "Silahkan menunggu pesanan Anda", $img_url, $options);
     
      // build message
      $messageBuilder = new TemplateMessageBuilder("Terimakasih", $buttonTemplate);

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

      //Proses Resto disini
      if ($this->user['number']==1) {
        // update table code
        $this->tebakkode_m->setTable($this->user['user_id'], $message);

        // update restaurant code
        $this->resto = $this->tebakkode_m->checkResto($message);
        $this->tebakkode_m->setResto($this->user['user_id'], $this->resto);
       
        // send next question
        $this->sendQuestion($replyToken, $this->user['number'] + 1);
        
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

        }


      }else if ($this->user['number']==3) {
        
        parse_str($message, $parseMessage);
        if ($message=="KEMBALI") {

          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 2);

          // send next question
          $this->sendQuestion($replyToken, 2);

        }else if ($message=="SELESAI") {
          //Proses complete order

          //Set cmplete order
          //get order ID
          $order = $this->tebakkode_m->getOrder($this->user['user_id']);

          if ($order!=0) {
            //publish complete order 
            $this->tebakkode_m->setOrderComplete($order);
          }

          //set user progress finish = 0
          $this->tebakkode_m->setUserProgress($this->user['user_id'],0);

          // send next question
          $this->sendQuestion($replyToken, 5);


        }else {
          //Proses order makanan

          //update progress menu code
          $this->tebakkode_m->setMenuProg($this->user['user_id'], $parseMessage["code"]);

          // send next question
          $this->sendQuestion($replyToken, $this->user['number'] + 1);

        }

      }else if ($this->user['number']==4) {

        $menu_code = $this->tebakkode_m->getMenuProg($this->user['user_id']);

        parse_str($message, $parseMessage);
        if ($parseMessage["ans"] == "Y") {

          $orderID = $this->tebakkode_m->getOrder($this->user['user_id']);
          $user_name = $this->tebakkode_m->getUserName($this->user['user_id']);
          $resto = $this->tebakkode_m->getResto($this->user['user_id']);
          $table = $this->tebakkode_m->getTable($this->user['user_id']);
          if ($orderID == 0)
          {
            //save menu order header
            $this->tebakkode_m->saveOrderHed($this->user['user_id'], $user_name, $resto, $table);
            
            //get last order id
            $orderID = $this->tebakkode_m->searchOrderID($this->user['user_id'], $resto, $table);

            //update order ID
            $this->tebakkode_m->setOrder($this->user['user_id'], $orderID);

          }
          
          //save menu order detail
          $this->tebakkode_m->saveOrderDet($orderID, $menu_code, 1);

          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 2);

          // send next question
          $this->sendQuestion($replyToken, 2);
          
        }else if ($parseMessage["ans"] == "N") {
          // update number progress
          $this->tebakkode_m->setUserProgress($this->user['user_id'], 2);

          // send next question
          $this->sendQuestion($replyToken, 2);

        }

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
