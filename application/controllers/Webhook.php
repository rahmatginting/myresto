<?php defined('BASEPATH') OR exit('No direct script access allowed');

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

class Webhook extends CI_Controller {

  private $bot;
  private $events;
  private $signature;
  private $user;
  private $resto;

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
        //Save Progress debug
        $this->resto = $this->tebakkode_m->getResto('1001');
        echo $this->resto;
        echo "</br>";

/*      
        // update restaurant and table code
        $this->tebakkode_m->setRestoTable('U4035bbada65f83a2ab7253095cd0e6e7', 'TEST');
*/      
      
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
      $message .= "Silakan kirim pesan \"MULAI\" untuk memulai kuis.";
      $textMessageBuilder = new TextMessageBuilder($message);
 
      // create sticker message
      $stickerMessageBuilder = new StickerMessageBuilder(1, 3);
 
      // merge all message
      $multiMessageBuilder = new MultiMessageBuilder();
      $multiMessageBuilder->add($textMessageBuilder);
      $multiMessageBuilder->add($stickerMessageBuilder);
 
       //Save Progress debug
      $this->tebakkode_m->saveProgress('token01=' . $event['replyToken']);

      // send reply message
      $this->bot->replyMessage($event['replyToken'], $multiMessageBuilder);
 
      // save user data
      $this->tebakkode_m->saveUser($profile);
    }
  }
  
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

        //Save Progress debug
        $this->tebakkode_m->saveProgress('token02=' . $event['replyToken']);

        // send question no.1
        $this->sendQuestion($event['replyToken'], 1);
      } else {
        $message = 'Silakan kirim pesan "MULAI" untuk memulai kuis.';
        $textMessageBuilder = new TextMessageBuilder($message);
        //Save Progress debug
        $this->tebakkode_m->saveProgress('token03=' . $event['replyToken']);

        $this->bot->replyMessage($event['replyToken'], $textMessageBuilder);
      }
 
    // if user already begin test
    } else {
      $this->checkAnswer($userMessage, $event['replyToken']);
    }
  }
    private function stickerMessage($event)
  {
    // create sticker message
    $stickerMessageBuilder = new StickerMessageBuilder(1, 106);
 
    // create text message
    $message = 'Silakan kirim pesan "MULAI" untuk memulai kuis.';
    $textMessageBuilder = new TextMessageBuilder($message);
 
    // merge all message
    $multiMessageBuilder = new MultiMessageBuilder();
    $multiMessageBuilder->add($stickerMessageBuilder);
    $multiMessageBuilder->add($textMessageBuilder);
 
         //Save Progress debug
        $this->tebakkode_m->saveProgress('token04=' . $event['replyToken']);

    // send message
    $this->bot->replyMessage($event['replyToken'], $multiMessageBuilder);
  }
  

  public function sendQuestion($replyToken, $questionNum=1)
  {
    // get question from database
    $question = $this->tebakkode_m->getQuestion($questionNum);
 
    /*
    // prepare answer options
    for($opsi = "a"; $opsi <= "d"; $opsi++) {
        if(!empty($question['option_'.$opsi]))
            $options[] = new MessageTemplateActionBuilder($question['option_'.$opsi], $question['option_'.$opsi]);
    }
    */

    if ($questionNum==1) {
      $options[] = new MessageTemplateActionBuilder('NOMOR MEJA', 'NOMOR MEJA');
      
    }else if ($questionNum==2) {
      //Save Progress debug
      $this->tebakkode_m->saveProgress('prog02');
      
      //Search Menu Category options
      $test='00';
      $categorys=$this->tebakkode_m->getCategory($this->resto);

      //Save Progress debug
      $this->tebakkode_m->saveProgress('restoID=' . $this->resto);

      //Save Progress debug
      $this->tebakkode_m->saveProgress('prog03');
      
      foreach($categorys as $category) {
      
          if(!empty($category['name'])) {
              $options[] = new MessageTemplateActionBuilder($category['name'], $category['name']);
              $test = '01';
          }
      }
      
      if ($test=='01') {
        //Save Progress debug
        $this->tebakkode_m->saveProgress('prog04');
      }
      
    }else {
      // prepare answer options
      for($opsi = "a"; $opsi <= "d"; $opsi++) {
          if(!empty($question['option_'.$opsi]))
              $options[] = new MessageTemplateActionBuilder($question['option_'.$opsi], $question['option_'.$opsi]);
      }
    }

    //Save Progress debug
    $this->tebakkode_m->saveProgress('prog05');
    
    // prepare button template
    $buttonTemplate = new ButtonTemplateBuilder($question['number']."/10", $question['text'], $question['image'], $options);

    //Save Progress debug
    $this->tebakkode_m->saveProgress('prog06');
    
    // build message
    $messageBuilder = new TemplateMessageBuilder("Gunakan mobile app untuk melihat soal", $buttonTemplate);
 
    //Save Progress debug
    $this->tebakkode_m->saveProgress('prog07');

        //Save Progress debug
        $this->tebakkode_m->saveProgress('token06=' . $replyToken);
    
    // send message
    $response = $this->bot->replyMessage($replyToken, $messageBuilder);

    //Save Progress debug
    $this->tebakkode_m->saveProgress('prog08');
    
  }
  
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
        $this->resto = $this->tebakkode_m->getResto($message);
        $this->tebakkode_m->saveProgress('resto='.$this->resto);
        $this->tebakkode_m->setResto($this->user['user_id'], $this->resto);

        //Save Progress debug
        $this->tebakkode_m->saveProgress('prog01');
        
        // send next question
        $this->sendQuestion($replyToken, $this->user['number'] + 1);
       
        //Save Progress debug
        $this->tebakkode_m->saveProgress('prog09');
        
      }else if ($this->user['number']==2) {

        //Save Progress debug
        $this->tebakkode_m->saveProgress($message);

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
