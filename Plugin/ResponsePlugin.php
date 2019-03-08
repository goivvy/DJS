<?php
/**
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to sales@goivvy.com so we can send you a copy immediately.
 *
 * @component  Goivvy_DJS
 * @copyright  Copyright (c) 2017 Goivvy.com. (https://www.goivvy.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Goivvy.com <sales@goivvy.com>
 */

namespace Goivvy\DJS\Plugin;

class ResponsePlugin {
  public function aroundsendContent(\Zend\Http\PhpEnvironment\Response $response, callable $proceed){
     $url = \Magento\Framework\App\ObjectManager::getInstance()
        ->get('Magento\Framework\UrlInterface');
     if(preg_match('#checkout#',$url->getCurrentUrl())) return $proceed();
     $content = $response->getContent();
     preg_match_all('#(<script.*?</script>)#is', $content, $matches);
     $js = '';
     foreach ($matches[0] as $value)
        $js .= $value;
     $content = preg_replace('#<script.*?</script>#is', '', $content); 
     $content = preg_replace('#</body>#',$js.'</body>',$content);
     $response->setContent($content);
     return $proceed();
  }
}
