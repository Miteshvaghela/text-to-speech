<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Text to audio</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
      
      <!-- Bootstrap -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
      
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

      <style>
        span#charCount {
            font-size: 12px;
            float: right;
            font-weight:bold;
        }
        span.good{color:green;}
        span.danger{color: red;}
        section.instruction div.container{
            background: #f9f9f9;
            width: 70%;
            border-radius: 10px;
            padding: 10px;
        }
      </style>

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="container mt-5">
            
            <!-- https://translate.google.co.in/?hl=en&tab=TT&sl=en&tl=gu&text=Mitesh%20Vaghela&op=translate -->
            <h2 class="text-center">Convert text to audio (English, Chinese, Japanese).</h2>

            <form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="form-group">
                    <label class="form-label" for="lang">Language of your choice</label>    
                    <select class="form-control" name="lang" id="lang">
                        <option value="">Select</option>
                        <option value="en" <?php echo (isset($_POST['lang']) && ($_POST['lang'] === 'en'))?'selected':''; ?>>English</option>
                        <option value="zh" <?php echo (isset($_POST['lang']) && ($_POST['lang'] === 'zh'))?'selected':''; ?>>Chinese (traditional)</option>
                        <option value="ja" <?php echo (isset($_POST['lang']) && ($_POST['lang'] === 'ja'))?'selected':''; ?>>Japanese</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="text">Write anything you want</label>    
                    <textarea class="form-control" name="text" id="text" cols="30" rows="3"><?php echo (isset($_POST['text']))?$_POST['text']:''; ?></textarea><span id="charCount" class="">Limit : 262</span>
                </div>

                <input type="submit" class="mt-5 form-control btn btn-primary" value="Convert to audio" name="convert" id="convert">
            </form> 
            
            
        </div> 
        <?php  
            if(isset($_POST['convert']) && strlen(trim($_POST['text'])) > 0){
                $lang = trim(rawurlencode($_POST['lang']));
                $text = trim($_POST['text']);
                $text = htmlspecialchars($text);
                $text = rawurlencode($text); 

                if($lang == 'zh'){
                    $translate_url = 'https://translate.google.com.vn/translate_tts?ie=UTF-8&client=tw-ob&q='.$text.'&tl=zh';
                }else if($lang == 'ja'){
                    $translate_url = 'https://translate.google.com.vn/translate_tts?ie=UTF-8&client=tw-ob&q='.$text.'&tl=ja';
                }else{ // default
                    $translate_url = 'https://translate.google.com.vn/translate_tts?ie=UTF-8&client=tw-ob&q='.$text.'&tl=en';
                }
                $html = file_get_contents($translate_url);
                //echo 'Text length : '. strlen($text);  // string length limit 262
                if($html){
                    $player = '<audio controls autoplay><source src="data:audio/mpeg;base64,'.base64_encode($html).'" type="audio/mpeg"></audio>';
                    echo '<div class="text-center mx-auto mt-5">'.$player.'</div>';
                }
                
            }
        
        ?> 
        <section class="instruction m-5">
            <div class="container">
                <h3>Instructions</h3>
                <ul class="mt-4">
                    <li>Multiple language text to speech converter.</li>
                    <li>English/Chinese/Japanese</li>
                    <li>With characters limit</li>
                    <li>PHP/Javascript/CSS</li>
                    <li><a href="https://github.com/Miteshvaghela/text-to-speech">Mitesh vaghela (GitHub)</a></li>
                </ul>
            </div>
        </section>
        <script type="text/javascript">
            const characterLimit = 250;
            const text = document.getElementById('text');
            const charCount = document.getElementById('charCount');
            const submit = document.getElementById('convert');

            if(text.value.length === 0){
                charCount.innerHTML = 'Limit : '+characterLimit;
            }else{
                charCount.innerHTML = 'Limit : '+ parseInt(characterLimit - text.value.length);
            }
            text.addEventListener('input', function(e){
                let str = e.target.value; 
                let strLen = str.trim().length;

                // character limit 262 
                let currentChars = parseInt(characterLimit - strLen);
                if(currentChars < 0){
                    charCount.innerHTML = 'Limit : 0';
                    charCount.classList.add('danger'); 
                    charCount.classList.remove('good');
                    submit.setAttribute('disabled',''); 
                }else if(currentChars > 0 && currentChars <= characterLimit){
                    charCount.innerHTML = 'Limit : '+currentChars; 
                    charCount.classList.remove('danger');
                    charCount.classList.add('good');
                    submit.removeAttribute('disabled');
                }
                
            });
        </script>
    </body>
</html>

 
<!--  
<?php
// if(isset($_POST['text_field'])){
//    $data = trim($_POST['text_field']);
//    $data = htmlspecialchars($_POST['text_field']);
//    $data = rawurlencode($_POST['text_field']);
//    $html = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&g='.$data.'&lt=en-IN');
//    $player = "<audio controls='conrols' autoplay><source src='data:audio/mpeg;base64,".base64_encode($html)."'></audio>"; 
//    echo $player; 
// }
?>
<form action="/" method="post">
   <input type="text" name="text_field" />
   <input type="submit" value="Convert to audio" />
</form>

-->

