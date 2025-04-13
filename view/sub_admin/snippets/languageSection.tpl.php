<?php
   /**
    * languageSection
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: languageSection.tpl.php, v1.00 7/4/2023 5:46 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   $i = 0;
   $html = '';
   
   foreach ($this->section as $key => $row) {
      $i++;
      $html .= '
            <tr>
               <td>
                  <span data-editable="true"
                        data-set=\'{"action": "phrase", "id": ' . $i . ',"key":"' . $key . '", "path":"' . $this->abbr . '.lang.json"\'>' . $row . '</span>
               </td>
               <td class="auto right-align"><span class="wojo mini secondary label">' . $key . '</span></td>
            </tr>';
      
   }
   echo $html;