<?php

namespace AndreiBu\laravel_captcha;

use Symfony\Component\HttpFoundation\Request;


class Captcha
{

    /**
     * Captcha.
     *
     * @param string $secret
     * @param string $sitekey
     */
    public function __construct($config=array())
    {
    }

    /**
     * New captcha cod.
     *
     * @param array  $attributes
     * @param string $lang
     *
     * @return string
     */
    public function create()
    {

        
        $html = '';

        header("Content-type: image/jpeg");
        $w=180;
        $h=56;
        $dest1 = imagecreatetruecolor($w,$h);
        $color=imagecolorallocate($dest1,255,255,255);
        imagefilledrectangle($dest1,0,0,$w,$h,$color);
        
        $s=$b=mt_rand(10000,99999);
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
        
            ob_start(); // Let's start output buffering.
                imagejpeg($dest1); //This will normally output the image, but because of ob_start(), it won't.
                $contents = ob_get_contents(); //Instead, output above is saved to $contents
            ob_end_clean(); //End the output buffer.
        
        $html.= "data:image/jpeg;base64," . base64_encode($contents);        
        

            //imagejpeg($dest1);
            imagedestroy($dest1);
            
            
        return $html;
    }
    
    /**
     * Render HTML captcha.
     *
     * @param array  $attributes
     * @param string $lang
     *
     * @return string
     */
    public function display($attributes = [], $lang = null)
    {
        $html = '';
        $html .= '<h1>11</h1>';

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
    public function verifyResponse($response, $clientIp = null)
    {
        if (empty($response)) {
            return false;
        }

        $response = $this->sendRequestVerify([
            'secret' => $this->secret,
            'response' => $response,
            'remoteip' => $clientIp,
        ]);

        return isset($response['success']) && $response['success'] === true;
    }

    /**
     * Verify no-captcha response by Symfony Request.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function verifyRequest(Request $request)
    {
        //test
        return $this->verifyResponse(
            $request->get('g-recaptcha-response'),
            $request->getClientIp()
        );
    }

    /**
     * Get recaptcha js link.
     *
     * @param string $lang
     *
     * @return string
     */
    public function getJsLink($lang = null)
    {
        return $lang ? static::CLIENT_API.'?hl='.$lang : static::CLIENT_API;
    }

    /**
     * Send verify request.
     *
     * @param array $query
     *
     * @return array
     */
    protected function sendRequestVerify(array $query = [])
    {
        $response = $this->http->request('POST', static::VERIFY_URL, [
            'form_params' => $query,
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Build HTML attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function buildAttributes(array $attributes)
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            $html[] = $key.'="'.$value.'"';
        }

        return count($html) ? ' '.implode(' ', $html) : '';
    }
}
