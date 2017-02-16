<?php

namespace AndreiBu\laravel_captcha;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;



class Captcha
{
    public $md5='';
    /**
     * Captcha.
     *
     * @param array $config
     */
    public function __construct($config=array())
    {
    }

    /**
     * return md5 .
     *
     *
     * @return string
     */
    function md5()
    {
    return $this->md5;
    }

    /**
     * register new captcha cod.
     *
     * @param bool $return   true | false
     *
     * @return string 
     */
    
    
    function create_cod($return=false)
    {
        $a=mt_rand(10000,100000);
        $b=0;
        $c=1;
        $ip='';
        if(isset($_SERVER["REMOTE_ADDR"])){$ip = $_SERVER["REMOTE_ADDR"];}
        $c1='+';
        if($c==1){$c1='+';}
        else if($c==2){$c1='-';if($a<$b){$a1=$b;$a=$b;$b=$a1;}}
        else if($c==3){$c1='*';}
        $d1=date("Y-m-d G:i:s",time()-360);
        $d2=date("Y-m-d G:i:s",time());
        $str='mediest'.$a.$c1.$b.$ip.$d2;
        $md5_1=md5($str);
        DB::table('captcha')->where('dat', '<', $d1)->delete();
        DB::table('captcha')->insert(array('a' => $a,'md5'=>$md5_1, 'dat' => $d2) );
        $this->md5=$md5_1;
        if($return){return $md5_1;}
    }
    
    /**
     * Render base64 img captcha.
     *
     * @param string $fat
     *
     * @return string
     */
    
    public function img()
    {
        $fat=$this->md5;
        $results = DB::select('select * from captcha where  md5= ?', [$this->md5]);
        $s=$b='';
        if(isset($results[0])){$s=$results[0]->a;}
        $html = '';
        $w=180;
        $h=56;
        $dest1 = imagecreatetruecolor($w,$h);
        $color=imagecolorallocate($dest1,255,255,255);
        imagefilledrectangle($dest1,0,0,$w,$h,$color);
        
        
        $b1='';
        $color=imagecolorallocate($dest1,0,127,137);
        
        for ($i=0; $i<25; $i++)
        {
            $rc=mt_rand(160,180);
            $gc=mt_rand(200,220);
            $bc=mt_rand(100,120);
            imagesetthickness($dest1, rand(1, 2));
            $color = imagecolorallocatealpha($dest1, $rc, $gc, $bc,rand(50, 100));
            imageline($dest1, rand(-50, 50), rand(-50, 50), rand(80, 280), rand(50, 100), $color);
        }
        
        for($i=0;$i<strlen($s);$i++)
        {
            $rc=mt_rand(160,180);
            $gc=mt_rand(200,220);
            $bc=mt_rand(100,120);
            $color=imagecolorallocate($dest1,$rc,$gc,$bc);
            $ugol=mt_rand(-20,15);
            $x=46+($i*20)+mt_rand(2,12);
            $y=40+mt_rand(-3,3);
            $size=mt_rand(20,40);
            $sum=substr($s,$i,1);
            imagettftext($dest1,$size, $ugol, $x,$y, $color, '/fonts/times.ttf', $sum);
        }

        for ($i=0; $i<25; $i++)
        {
            $rc=mt_rand(10,180);
            $gc=mt_rand(20,200);
            $bc=mt_rand(100,120);
            imagesetthickness($dest1, rand(1, 2));
            $color = imagecolorallocatealpha($dest1, $rc, $gc, $bc,rand(50, 100));
            imageline($dest1, rand(-50, 200), rand(-101, 150), rand(80, 380), rand(51, 250), $color);
        }
        
            ob_start(); 
                imagejpeg($dest1); 
                $contents = ob_get_contents(); 
            ob_end_clean(); 

        $html.= '<img src="data:image/jpeg;base64,' . base64_encode($contents).'">';        
        imagedestroy($dest1);
            
        return $html;
    }
    

    /**
     * Verify captcha response.
     *
     * @param string $response
     * @param string $clientIp
     *
     * @return bool
     */
    public function verifyResponse($code, $md5)
    {
        $capcha_check=0;
        $results = DB::select('select * from captcha where  md5= ?', [$md5]);
        $s='';
        if(isset($results[0])){$s=$results[0]->a;}
        else
        {
            $capcha_check=false;
        }
        
        if(strlen($s)==strlen($code) and $code==$s)
        {
            $capcha_check=true;
        }
        
        
        //if(isset($response->captcha_cod)) return true;
        
        return $capcha_check;
    }

    /**
     * Verify captcha response by Symfony Request.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function verifyRequest(Request $request)
    {
        
/*
        return $this->verifyResponse(
            $request->get('g-recaptcha-response'),
            $request->getClientIp()
        );
*/        
    }



}
