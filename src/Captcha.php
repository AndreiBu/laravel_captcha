<?php

namespace AndreiBu\laravel_captcha;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;



class Captcha
{
    protected $md5='';
    protected $min=10000;
    protected $max=100000;
    protected $life_time=360;
    protected $height=56;
    protected $width=180;
    protected $garbage=25;
    protected $redraw=5;
    
    /**
     * Captcha.
     *
     * @param array $config
     */
    public function __construct($config=array())
    {
        $val=array('min','max','life_time','width','height','garbage','redraw');
        foreach ($val as $k=>$v){if(isset($config[$v])){$this->{$v}=$config[$v];}}
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
        $a=mt_rand($this->min,$this->max);
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
    
    public function img($key='')
    {
        $fat=$this->md5;
        if($key!=''){$fat=mb_substr($key,0,32,'UTF-8');}
        $results = DB::select('select * from captcha where  md5= ?', [$fat]);
        $s=$b='';
        if(isset($results[0]))
            {
                $s=$results[0]->a;
                $redraw=$results[0]->redraw;
                $redraw++;
                if($this->redraw>=6)
                {
                    $this->create_cod();
                    $results = DB::select('select * from captcha where  md5= ?', [$this->md5]);
                    $s=$results[0]->a;
                }
                else
                {
                DB::table('captcha')->where('md5', $fat)->update(['redraw' => $redraw]);
                }
            }
        $html = '';
        $dest1 = imagecreatetruecolor($this->width,$this->height);
        $color=imagecolorallocate($dest1,255,255,255);
        imagefilledrectangle($dest1,0,0,$this->width,$this->height,$color);
        
        
        $b1='';
        $color=imagecolorallocate($dest1,0,127,137);
        
        for ($i=0; $i<$this->garbage; $i++)
        {
            $rc=mt_rand(160,200);
            $gc=mt_rand(200,220);
            $bc=mt_rand(100,120);
            imagesetthickness($dest1, rand(1, 2));
            $color = imagecolorallocatealpha($dest1, $rc, $gc, $bc,rand(50, 100));
            imageline($dest1, rand(-50, 50), rand(-50, 50), rand(80, 280), rand(50, 100), $color);
        }
        
        for($i=0;$i<strlen($s);$i++)
        {
            $rc=mt_rand(160,200);
            $gc=mt_rand(170,220);
            $bc=mt_rand(100,150);
            $color=imagecolorallocate($dest1,$rc,$gc,$bc);
            $ugol=mt_rand(-26,20);
            $block=($this->width/(strlen($s)+1));
            $x=6+($i*$block)+mt_rand(-10,14);
            $y=($this->height/1.4)+mt_rand(-5,5);
            $size=mt_rand(18,50);
            $sum=substr($s,$i,1);
            imagettftext($dest1,$size, $ugol, $x,$y, $color, '/fonts/times.ttf', $sum);
        }

        for ($i=0; $i<$this->garbage; $i++)
        {
            $rc=mt_rand(10,220);
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

        $html.= '<img class="captcha" src="data:image/jpeg;base64,' . base64_encode($contents).'">';        
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
