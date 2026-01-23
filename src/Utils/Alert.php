<?php

namespace App\Utils;

class Alert
{
    public static function Swalfire(array $option = [])
    {
        $default = [
            'icon' => 'success',
            'title' => 'title',
            'text' => '',
            'showConfirmButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'OK',
            'showCancelButton' => false,
            'timer' => null,
            'timerProgressBar' => true,
            'redirect' => null,
            'footer' => null,
            'submitId' => null
        ];
        $config = array_merge($default, $option);
        $jscode = json_encode($config, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG);

        echo <<<JS
        <script>
        setTimeout(function() 
        {
            const config = {$jscode};
            Swal.fire(config).then((result)=>{
                if(result.isConfirmed)
                {
                    if(config.submitId){
                        document.getElementById(config.submitId)?.submit();
                    }
                    else if(config.redirect){
                        window.location.href = config.redirect;
                    }
                }
                else if(result.dismiss === Swal.DismissReason.timer)
                {
                    if (config.redirect && !config.submitId){
                        window.location.href = config.redirect;
                    }
                    else{
                        window.history.back();
                    }
                }
                else if(result.dismiss === Swal.DismissReason.cancel)
                {
                    if(config.cancelRedirect){
                        window.location.href = config.cancelRedirect;
                    }
                    else{
                        window.history.back();
                    }
                }
            });
        }, 100);
        </script>
        JS;
    }
    
    public static function alert($icon, $title = '', $text = '', $redirect = null, $extra = [])
    {
        $params = array_merge([
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
            'redirect' => $redirect,
        ],$extra);

        self::Swalfire($params);
    }

    public static function info($title, $text, $redirect = null, $extra = [])
    {
        if(!isset($extra['timer'])){
            $extra['timer'] = 2000;
        }
        self::alert("info", $title, $text, $redirect, $extra);
    }

    public static function success($title, $text, $redirect = null, $extra = [])
    {
        if(!isset($extra['timer'])){
            $extra['timer'] = 2000;
        }
        self::alert("success",$title,$text, $redirect, $extra);
    }

    public static function question($title, $text, $redirect = null, $extra = [])
    {
        if(!isset($extra['timer'])){
            $extra['timer'] = 5000;
        }
        self::alert("question", $title, $text, $redirect, $extra);
    }

    public static function error($title, $text, $redirect = null, $extra = [])
    {
        if(!isset($extra['timer'])){
            $extra['timer'] = 5000;
        }
        self::alert("error", $title, $text, $redirect, $extra);
    }

    public static function warning($title, $text, $redirect = null, $extra = [])
    {
        if(!isset($extra['timer'])){
            $extra['timer'] = 5000;
        }
        self::alert("warning", $title, $text, $redirect, $extra);
    }
}
