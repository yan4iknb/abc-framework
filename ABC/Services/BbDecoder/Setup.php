<?php

namespace ABC\ABC\Services\BbDecoder;

/** 
 * BB-декодер (класс установок)
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Setup 
{ 
    public static function getConfig($abc)
    {
        $config = $abc->getConfig();
     
        $settings = [   // Максимальная длина слова 
                      'max_len'      => 80, 
                         
                      // Распознование ссылок                             
                      'links'        => true, 
                         
                      // Распознование картинок                             
                      'images'       => true, 
                               
                      // Парные BB-теги 
                      'setup_bb' => [ 
                                       '[b]'                =>   '[/b]', 
                                       '[i]'                =>   '[/i]', 
                                       '[s]'                =>   '[/s]', 
                                       '[u]'                =>   '[/u]', 
                                       '[sub]'              =>   '[/sub]', 
                                       '[sup]'              =>   '[/sup]', 
                                       '[justify]'          =>   '[/justify]', 
                                       '[left]'             =>   '[/left]',                                        
                                       '[right]'            =>   '[/right]', 
                                       '[center]'           =>   '[/center]', 
                                       '[quote]'            =>   '[/quote]', 
                                       '[list=ol]'          =>   '[/list=ol]', 
                                       '[list=ul]'          =>   '[/list=ul]', 
                                       '[*]'                =>   '[/*]', 
                                       '[size=1]'           =>   '[/size=1]', 
                                       '[size=2]'           =>   '[/size=2]',  
                                       '[size=3]'           =>   '[/size=3]',  
                                       '[size=4]'           =>   '[/size=4]',  
                                       '[size=5]'           =>   '[/size=5]',  
                                       '[h=5]'              =>   '[/h=5]', 
                                       '[h=4]'              =>   '[/h=4]',  
                                       '[h=3]'              =>   '[/h=3]',  
                                       '[h=2]'              =>   '[/h=2]',  
                                       '[h=1]'              =>   '[/h=1]', 
                                       '[color=gray]'       =>   '[/color=gray]', 
                                       '[color=green]'      =>   '[/color=green]', 
                                       '[color=purple]'     =>   '[/color=purple]', 
                                       '[color=olive]'      =>   '[/color=olive]', 
                                       '[color=silver]'     =>   '[/color=silver]', 
                                       '[color=aqua]'       =>   '[/color=aqua]', 
                                       '[color=yellow]'     =>   '[/color=yellow]', 
                                       '[color=blue]'       =>   '[/color=blue]', 
                                       '[color=orange]'     =>   '[/color=orange]', 
                                       '[color=red]'        =>   '[/color=red]',                                        
                                    ], 
                         
                       // Парные HTML-теги (на них заменяются теги из предыдущего массива)                      
                       'setup_html'  => [ 
                                       '<b>'                                      =>   '</b>', 
                                       '<i>'                                      =>   '</i>', 
                                       '<s>'                                      =>   '</s>', 
                                       '<u>'                                      =>   '</u>', 
                                       '<sub>'                                    =>   '</sub>',  
                                       '<sup>'                                    =>   '</sup>',  
                                       '<p align="justify">'                      =>   '</p>',  
                                       '<p align="left">'                         =>   '</p>',  
                                       '<p align="right">'                        =>   '</p>',  
                                       '<p align="center">'                       =>   '</p>', 
                                       '<p class="quote"><b>цитата:</b><br />'    =>   '</p>', 
                                       '<ol>'                                     =>   '</ol>', 
                                       '<ul>'                                     =>   '</ul>',                                        
                                       '<li>'                                     =>   '</li>', 
                                       '<span style="font-size:11px">'            =>   '</span>', 
                                       '<span style="font-size:14px">'            =>   '</span>', 
                                       '<span style="font-size:18px">'            =>   '</span>', 
                                       '<span style="font-size:24px">'            =>   '</span>', 
                                       '<span style="font-size:32px">'            =>   '</span>', 
                                       '<h5>'                                     =>   '</h5>', 
                                       '<h4>'                                     =>   '</h4>',                                        
                                       '<h3>'                                     =>   '</h3>', 
                                       '<h2>'                                     =>   '</h2>', 
                                       '<h1>'                                     =>   '</h1>',                                        
                                       '<span style="color:gray">'                =>   '</span>', 
                                       '<span style="color:green">'               =>   '</span>', 
                                       '<span style="color:purple">'              =>   '</span>', 
                                       '<span style="color:olive">'               =>   '</span>', 
                                       '<span style="color:silver">'              =>   '</span>', 
                                       '<span style="color:aqua">'                =>   '</span>', 
                                       '<span style="color:yellow">'              =>   '</span>', 
                                       '<span style="color:blue">'                =>   '</span>', 
                                       '<span style="color:orange">'              =>   '</span>', 
                                       '<span style="color:red">'                 =>   '</span>', 
                   ],  
                // Не парные теги (смайлики и иже с ними)                        
                  'single_tags' => [ 
                                      '[:)]'   =>   '<img src="data:image/png;base64,R0lGODlhFwAXAPcAAP///////1ZACPTHAD8UEOuvAfrYD//mIAEBAf/hG9mSAKBqBG1OB7OABf3+/U0lIQIBAfK+APjQBrCMFvXIAL2aF8KEATIlBbqVD+utANWQAOarAbiulzMzM8utH6F/BMXFxS4uLs+rD86qD145Mls2M/f29vv7++iqAOfk3OenAKmFFvjOBeTg1yoqKuvq6mRCP/fMA/z8+/n5+UwkII97TamCBOe4AZyPbrN5AollG+uxAObACuCvAduaAdyvAvPBAOuwAJNrEKZwA39pNN+4CqaafcqfAtuzCcGRA/XGAq1zA31cErCPGdirApFlC/PCAJFxI41pHuaqAdaWAaicgPrVC9OwE6J8Fr62oc6lCPPDAu61ANyfAaRuA5d4K/C8APbLCfPDAP/iG//kHP3dE7h/A5BtGKt1A6J7ENWTAMiiAuqsANmXAPrdHLOpkJ90CvzbEfG7AO6zAMihAsaMAmRMEOSrAZhyHZ6ScqZuA/veG/rUDO23AMGQA/XICeS1BKJrA6h6BKB6EnlXDsGDAZpuBd60CmJLEMa+q9bRw9WbAZNiBOywAZaATL2WA/vVDMaTAohtMod2TZFpBtitCffVD8ejE8SVAu7u7uGnAezt7MOXCPHFAfG9ANCWAo9hBXVjNO3q5JqJX6V7BfPDB/bUEdCvHfvZD3hZFuTEGcypFPDFA+iuAKl1A4l1R+vo4f/jG6yZapBpBseXBvLJB/nSCPbXFP3cEfHHBfG7AcmUApRpBJ6IVeLe1a+kiaaWcHtZBqR+BdCiCPK/BX1aDeeuBc+dA6Z6BH5aBp2JXdzYzLKni7Kgd4RfBpx0BMyPAZVoBIVfBqyZa7Ooi9rVyfHv6p2Qb5qNbObj242AX/r6+q+vr05OTkhISKOjo7GxseTk5NeSAPnRB/LAAEZGRvnRCLa2tt3d3fzfHMjIyMTExB3I5B3I5B3I5B3I5BTX9hHb/AfR+QDA8gCq6AGEwiBSZ////////////////////zRidAKhyAjR+RTe/iH5BAEAAAAALAAAAAAXABcAAAjXAAEIHEiwoMGDCAEgSIhwoUCHDA8unAgxIkEEGC0aFMCxo0aBAhg0KDBggIIGDARYFLBggIGXLwcUWKAyoYACBhIQIKCTgIGSCmpubPCSQIAARpGWnCmUIIMBOgNgJIAR6c8CDIbmNDq1qk+ZNAve3HoUadmvBYKKdZngwM63Ow8kANoUgAC2bQ/o3Rszbd2xCfLu1dtXLUEBDVzyHCyXgEymBp8qDixX51IFWYeWNADXsUwFYQ8KUECypOkCaRvUFbsA9WPUoFdvZLBAAWnQKT/a7cgRYUAAOw==" />', 
                                      '[:(]'   =>   '<img src="data:image/png;base64,R0lGODlhFwAXAPcAAP///////1ZACP/mIPTHAD8UEPrYD+uvAQEBAdmSAP/hG6BqBG1OB7OABf3+/U0lIQIBAfK+APjQBrCMFvXIAL2aF8KEATIlBbqVD+utANWQAOarAbiulzMzM8utH6F/BMXFxS4uLs+rD86qD145Mls2M/f29vv7++iqAOfk3OenAKmFFvjOBeTg1yoqKuvq6mRCP/fMA/z8+/n5+UwkII97TamCBOe4AZyPbrN5AollG+uxAObACuCvAduaAdyvAvPBAOuwAJNrEKZwA39pNN+4CqaafcqfAtuzCcGRA/XGAq1zA31cErCPGdirApFlC/PCAJFxI41pHuaqAdaWAaicgPrVC9OwE6J8Fr62oc6lCPPDAu61ANyfAaRuA5d4K/C8APbLCfPDAP/iG//kHP3dE7h/A5BtGKt1A6J7ENWTAMiiAuqsANmXAPrdHLOpkJ90CvzbEfG7AO6zAMihAsaMAmRMEOSrAZhyHZ6ScqZuA/veG/rUDO23AMGQA/XICeS1BKJrA6h6BKB6EnlXDsGDAZpuBd60CmJLEMa+q9bRw9WbAZNiBOywAZaATL2WA/vVDMaTAohtMod2TZFpBtitCffVD8ejE8SVAu7u7uGnAezt7MOXCPHFAfG9ANCWAo9hBXVjNO3q5JqJX6V7BfPDB/bUEdCvHfvZD3hZFuTEGcypFPDFA+iuAKl1A4l1R+vo4f/jG6yZapBpBseXBvLJB/nSCPbXFP3cEfHHBfG7AcmUApRpBJ6IVeLe1a+kiaaWcHtZBqR+BdCiCPK/BX1aDeeuBc+dA6Z6BH5aBp2JXdzYzLKni7Kgd4RfBpx0BMyPAZVoBIVfBqyZa7Ooi9rVyfHv6p2Qb5qNbObj242AX/r6+q+vr05OTkhISKOjo7GxseTk5NeSAPnRB/LAAEZGRvnRCLa2tt3d3fzfHMjIyMTExB3I5B3I5B3I5B3I5BTX9hHb/AfR+QDA8gCq6AGEwiBSZ////////////////////zRidAKhyAjR+RTe/iH5BAEAAAAALAAAAAAXABcAAAjSAAEIHEiQIIKCCBEeFLgwoUOGAA4iaPhQ4cSKDgVo3IiRoAAGDQ4QIJCgAQMBHQUsIGCgZcuRC1A+FJDAwICbOG8eSCAToYAGNgsIHSC0gM6YCRkQUFAggNOmTwcQOMDAJ1CmASYWmBjA6FSkHg8YwKqVawEDU3kWFMBSAdGhRV+S7CmQ7dicOV/upAuA5l28N/Wq9digLWC9B8ASVGq4qAIFI3dWtSqSaUumU3cqXptA5MjPaRvwXbvggEjTphNszshgQcgEqk92HLiR4+zbAwMCADs=" />', 
                                      '[;)]'   =>   '<img src="data:image/png;base64,R0lGODlhFwAXAPcAAP///////1ZACP/mIEAUEPbKAPrWC//iHPrYD//dGPK4APTEAOyqAKVqA8qJAPLAAOmXAO6zAbV5AWxMBXJVBN6VAOGnANamCbKJBP3aEqR5CfjOA+uiAKBqBJVTCf///////////////////////////////////////////////////////////////////////////////////////////whAVghAVghAVghAVghAVghAVv///////////////////////////////////////////////////////////whAVgVMbANqpQCJygCX6QCX6QCJygNqpQVMbAhAVv///////////////////////////////////////////////whAVgRqoACn4QGz7gC48gC48gGz7gCq7ACi6wCV3glTlQhAVv///////////////////////////////////////whAVgCJygC48gDE9ADK9gDK9gDK9gDK9gDE9ADA8gCq7ACX6QF5tQhAVv///////////////////////////////whAVgF5tQDA8gDK9gvW+hzi/xAUQBAUQBAUQAvW+gDK9gDA8gCq7ACX6QNqpQhAVv///////////////////////////wRVcgDA8gDK9g/Y+hjd/xzi/xzi/yDm/xzi/xAUQAvW+gDK9gDE9ACq7ACX6QVMbP///////////////////////whAVgF5tQDK9g/Y+hzi/xzi/yDm/yDm/yDm/yDm/yDm/xAUQAvW+gDK9gC48gCi6wNqpQhAVv///////////////////whAVgCn4QvW+hjd/xzi/yDm/yDm/yDm/yDm/yDm/yDm/xAUQBjd/wvW+gDE9ACq7ACJyghAVv///////////////////whAVgDE9AvW+hzi/xAUQBAUQBAUQCDm/yDm/yDm/yDm/yDm/xjd/w/Y+gDK9gGz7gCV3ghAVv///////////////////whAViH5BAEAAAAALAAAAAAXABcAAAjmAAEIHEiwoMGDCBMqXMhQgMOHDAcKoIDhwoYFFyRQENBQwwYEIA0Y2BBBA8eEAi4YyJCgZcuRDxycNCgAg4EEBAgkGDAgAYINJBvMJEhhA84AAXjyfLmAwQSaNgkgTbpzAAGQQYcCEGABgdScVsEiSEBSZkEBC7wGAKt0AIIMJCtoRXszJ4G2PX9GkHvWws2qbXUaaGqWoAAJH1viJTAyAgehBik8SKwY58imEJ7SlDC5bk6YDCBAPijAQYQFQIEueBBagtazHSxEmB2BAYcKHl7TnNDAAQQIDhpM0I3wIcSIyJMvDAgAOw==" />', 
                                      '[%)]'   =>   '<img src="data:image/png;base64,R0lGODlhFwAXAPcAAP///////wAAAFZACP/iHPbKAP/mIPLAAOyqAKBqBPrWC+mXAPTEALKJBMqJAP3aEvrYD21RB9amCf/dGEAUEPK4AP///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////whAVghAVghAVghAVghAVghAVv///////////////////////////////////////////////////////////whAVgdRbQRqoACJygCX6QCX6QCJygRqoAdRbQhAVv///////////////////////////////////////////////whAVgRqoACq7ACq7AC48gDA8gCq7ACq7ACq7ACX6QRqoAhAVv///////////////////////////////////////whAVgCJygDA8gDE9ADK9gDK9gDK9hAUQBAUQADA8gCq7ACX6QSJsghAVv///////////////////////////////whAVgSJsgDA8gDK9gDK9g/Y+hLa/RLa/Q/Y+gvW+hAUQADE9ACq7ACX6QRqoAhAVv///////////////////////////wdRbQDA8gDK9gvW+hjd/xzi/xzi/yDm/xzi/xjd/xzi/xAUQADA8gCq7ACX6QdRbf///////////////////////whAVgSJsgDK9gvW+hzi/xzi/xzi/yDm/yDm/yDm/yDm/xzi/xLa/QDK9gDA8gCq7ARqoAhAVv///////////////////whAVgmm1gDK9hjd/xzi/xzi/yDm/yDm/yDm/yDm/yDm/xzi/w/Y+gvW+gDE9ACq7ACJyghAVv///////////////////whAVgDE9AvW+gAAAAAAAAAAAAAAAAAAABzi/wAAAAAAAAAAAAAAAAAAAADK9gCq7ACX6QhAVv///////////////////yH5BAEAAAAALAAAAAAXABcAAAjLAAEIHEiwoMGDCBMqXMgQwICHEBsKHBChgQQGDCQ0iDCA4YAEBRQ8gKCgQIEDCTomHCBB5ISXE0aedKDS4IAGCl4agDkBgkmUNQkKGEq0qFEBNo8qJRrUoYSjBo4eoFlwAAOoUhc0tarA6ASjBRBorSqhAEwDaNH2VMAAAVWCN0OeTRvzJIKUBiMckKsTJoUDYiMcvLm3AIQHIxVQaLsA72AHBxiYLEDhr9gGTasmQICgAmDOjTPbjJDAwYIFDhJwlOgQ4kPWsGMXDAgAOw==" />', 
                                 
                    ]
        ];
        
        $config = !empty($config['bb_code']) ? $config['bb_code'] : [];
        return array_merge($settings, $config);
    }
    
    public static function getTokens() 
    { 
        return [  
         
/**  
Массивы символов замены. Для корректной обработки теги нужно заменить на 
одиночные символы, иначе можно порвать тег. Количество символов должно соответствовать количеству тегов. 
Используются редко востребованные символы  
*/ 
        // Открывающие теги 
                'tmp_open'   => [
                                       'ᐁ', 'ᐂ', 'ᐃ', 'ᐄ', 'ᐅ', 'ᐆ', 'ᐇ', 'ᐉ', 'ᐊ', 'ᐋ',  
                                       'ᐌ', 'ᐍ', 'ᐎ', 'ᐏ', 'ᐐ', 'ᐑ', 'ᐒ', 'ᐓ', 'ᐔ', 'ᐕ',  
                                       'ᐫ', 'ᐬ', 'ᐭ', 'ᐮ', 'ᐯ', 'ᐰ', 'ᐱ', 'ᐲ', 'ᐳ', 'ᐴ',  
                                       'ᐵ', 'ᐷ', 'ᐸ', 'ᐹ', 'ᐺ', 'ᐻ', 'ᐼ', 'ᐽ', 'ᐾ', 'ᐿ',  
                                       'ᑌ', 'ᑍ', 'ᑎ', 'ᑏ', 'ᑐ', 'ᑑ', 'ᑒ', 'ᑔ', 'ᑕ', 'ᑖ', 
                ],                                
        // Закрывающие теги                   
                'tmp_close'  => [ 
                                         
                                       'ᑗ', 'ᑘ', 'ᑙ', 'ᑚ', 'ᑛ', 'ᑜ', 'ᑝ', 'ᑞ', 'ᑟ', 'ᑠ',   
                                       'ᑡ', 'ᑢ', 'ᑣ', 'ᑤ', 'ᑥ', 'ᑧ', 'ᑨ', 'ᑩ', 'ᑪ', 'ᑫ', 
                                       'ᑬ', 'ᑭ', 'ᑮ', 'ᑯ', 'ᑰ', 'ᑱ', 'ᑲ', 'ᑳ', 'ᑴ', 'ᑵ',  
                                       'ᑶ', 'ᑷ', 'ᑸ', 'ᑹ', 'ᑺ', 'ᑻ', 'ᑼ', 'ᑽ', 'ᑾ', 'ᑿ',  
                                       'ᒀ', 'ᒁ', 'ᒂ', 'ᒌ', 'ᒍ', 'ᒎ', 'ᒏ', 'ᒐ', 'ᒑ', 'ᒒ', 
                ],                             
        // Одиночные теги                                   
                'tmp_single' => [                   
                                       'ᒓ', 'ᒔ', 'ᒕ', 'ᒖ', 'ᒗ', 'ᒘ', 'ᒙ', 'ᒚ', 'ᒛ', 'ᒜ',  
                                       'ᒝ', 'ᒞ', 'ᒟ', 'ᒠ', 'ᒣ', 'ᒤ', 'ᒥ', 'ᒦ', 'ᒧ', 'ᒨ',  
                                       'ᒩ', 'ᒪ', 'ᒫ', 'ᒬ', 'ᒭ', 'ᒮ', 'ᒯ', 'ᒰ', 'ᒱ', 'ᒲ',  
                                       'ᒳ', 'ᒴ', 'ᒵ', 'ᒶ', 'ᒷ', 'ᒸ', 'ᒹ', 'ᒺ', 'ᓀ', 'ᓁ',  
                                       'ᓂ', 'ᓃ', 'ᓄ', 'ᓅ', 'ᓆ', 'ᓇ', 'ᓈ', 'ᓉ', 'ᓊ', 'ᓋ',  
                ], 
        ]; 
    }     
}
