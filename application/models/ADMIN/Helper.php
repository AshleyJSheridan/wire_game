<?php

class ADMIN_Helper
{
     static public function renderOptions($array, $firstoption=false, $selected=null, $range)
     {
         $options="";
         if($firstoption)
             $options = $options."<option value=''>$firstoption</option>";
          if(empty($range)){
	         foreach($array as $key=>$value)
	         {
	             $options = $options.sprintf("<option %s value='%s'>%s</option>", ($selected === $key) ? "selected='selected'" : "", $key, $value );
	         }
          } else{
          	$step=$range[0]+intval($range[2]);
			while (($step+intval($range[2])) <= $range[1]){
					$options = $options.sprintf("<option %s value='%s'>%s</option>", ($selected === $step) ? "selected='selected'" : "", $step, $step);
					(!isset($range[2]))?$step++:$step+=intval($range[2]);
					echo $step.'<hr />';
			}
          }

         return $options;
     }

     static public function renderMultiCheckbox($array, $name, $selected=null)
     {
         $checked = "";
         $checkboxes ="";
         if(is_string($selected)) $selected  = explode("|",$selected);

         /*var_dump($array);
         var_dump($selected);
         exit();*/

         //var_dump($selected);
         //exit();
         $i=0;
         foreach($array as $key=>$value)
         {
             $checked ="";
             if($selected && array_search($key, $selected)!==false)  $checked = "checked='checked'";
           //if(array_key_exists($key, $selected)!==false)  $checked = "checked='checked'";
            //$checked=(strpos($selected,(string)$key)!==false) ? "checked='checked'" : "";
            $i++;
         	$checkboxes = $checkboxes."<div class='check'> <input $checked type='checkbox' name='{$name}[]' value='{$key}' /><label for='{$name}[]'> $value </label></div>";
         }
         //exit($temp);
         return $checkboxes;
     }

    public static function GetUrlParam($i=3)
	{
	    $uri = explode("?", $_SERVER['REQUEST_URI']);
		$arrTemp = explode('/', $uri[0]);
     	return isset($arrTemp[$i]) ? $arrTemp[$i] : null;
	}

    static function date ($years)
    {

        $today = date("Y-m-d");
        return (date("Y")-$years)."-" . date("m") . date("-d");
    }

    static function tooltiper()
    {
        $alerts = new Alert();
        $alerts = $alerts->fetchAll("type='form'");

        $output = '<script  type="text/javascript">';

    foreach($alerts as $alert) {
        if($alert->field_value==null)
        {
    		$output .= "$('[name=".$alert->field."]').attr('tooltip','$alert->info');";
    		$output .= "$('[for=".$alert->field."]').attr('tooltip','$alert->info');";
        }
		else
		{
    		$output .= "$('#".$alert->field."_".$alert->field_value."').attr('tooltip','$alert->info');";
    		$output .= "$('[for=".$alert->field."_".$alert->field_value."]').attr('tooltip','$alert->info');";
		}

    }
    $output .= "
    	$('#confirm').attr('tooltip',$('#password').attr('tooltip'));
		$('#confirmemail').attr('tooltip',$('#email').attr('tooltip'));
		</script>";
    return $output;
    }


    static function alert_msg($id)
    {
        $msg = new Alert();
        $msg = $msg->find($id)->current();
        return $msg ? $msg->info : "";
    }

    public static function trigger($changebreadcrumbs = null, $firstitem=null, $trigger = null)
    {
        if(Helper::GetUrlParam(1)==null && !$trigger) return "";
        $array=array(
            array (
            	'title' => "My Account",
                'controller' => 'account',
                'href' => '/account'),
            array (
            	'title' => "Inbox",
                'controller' => 'account',
            	'action' => 'inbox',
                'href' => '/account/inbox'),
            array (
            	'title' => "Outbox",
                'controller' => 'account',
            	'action' => 'outbox',
                'href' => '/account/outbox'),
            array (
            	'title' => "History",
                'controller' => 'account',
            	'action' => 'history',
                'href' => '/account/history'),
            array (
            	'title' => "Reply",
                'controller' => 'account',
            	'action' => 'reply',
                'href' => '/account/reply'),
            array (
            	'title' => "My Adverts",
                'controller' => 'account',
            	'action' => 'myAds',
                'href' => '/account/myAds'),


            array (
                'title' => 'Users` admin',
            	'controller' => 'control',
            	'action' => 'userAdmin',
            	'href' => '/control/userAdmin',
            ),
            array (
                'title' => 'Manage Admin',
            	'controller' => 'control',
            	'action' => 'manageAdmin',
            	'href' => '/control/manageAdmin',
            ),
            array (
                'title' => 'Transactions',
            	'controller' => 'control',
            	'action' => 'transactions',
            	'href' => '/control/transactions',
            ),
            array (
                'title' => 'Discounts',
            	'controller' => 'control',
            	'action' => 'discounts',
            	'href' => '/control/discounts',
            ),
            array (
                'title' => 'Add Discount',
            	'controller' => 'control',
            	'action' => 'addDiscount',
            	'href' => '/control/addDiscount',
            ),
            array (
                'title' => 'Alerts',
            	'controller' => 'control',
            	'action' => 'alerts',
            	'href' => '/control/alerts',
            ),
            array (
                'title' => 'Add New Alert',
            	'controller' => 'control',
            	'action' => 'addNewAlert',
            	'href' => '/control/addNewAlert',
            ),
            array (
                'title' => 'Feedback',
            	'controller' => 'control',
            	'action' => 'feedback',
            	'href' => '/control/feedback',
            ),
            array (
                'title' => 'Add New Feedback',
            	'controller' => 'control',
            	'action' => 'addNewFeedback',
            	'href' => '/control/addNewFeedback',
            ),
            array (
                'title' => 'Site vars',
            	'controller' => 'control',
            	'action' => 'vars',
            	'href' => '/control/vars',
            ),
            array (
                'title' => 'Seo',
            	'controller' => 'control',
            	'action' => 'seo',
            	'href' => '/control/seo',
            ),
            array (
                'title' => 'Add SEO',
            	'controller' => 'control',
            	'action' => 'addseo',
            	'href' => '/control/addseo',
            ),


            array (
                'title' => 'Events',
            	'controller' => 'control',
            	'action' => 'events',
            	'href' => '/control/events',
            ),
            array (
                'title' => 'Add New Event',
            	'controller' => 'control',
            	'action' => 'addNewEvent',
            	'href' => '/control/addNewEvent',
            ),
            array (
                'title' => 'Services Prices',
            	'controller' => 'control',
            	'action' => 'services',
            	'href' => '/control/services',
            ),
            array (
                'title' => 'Add New Price',
            	'controller' => 'control',
            	'action' => 'addPrice',
            	'href' => '/control/addPrice',
            ),
            array (
                'title' => 'Property Classifieds',
            	'controller' => 'control',
            	'action' => 'propertyClassifieds',
            	'href' => '/control/propertyClassifieds',
            ),

            array (
                'title' => 'Tenant Classifieds',
            	'controller' => 'control',
            	'action' => 'tenantClassifieds',
            	'href' => '/control/tenantClassifieds',
            ),



            array (
                'title' => Zend_Auth::getInstance()->hasIdentity() ? 'Edit personal info' : "Registration",
                'controller' => 'account',
                'action' => 'registration',
            	'href' => '/account/registration'
            ),
            array (
                'title' => "Transactions",
                'controller' => 'account',
                'action' => 'transactions',
            	'href' => '/account/transactions'
            ),
            array (
                'title' => "Bids",
                'controller' => 'account',
                'action' => 'bids',
            	'href' => '/account/bids'
            ),
            array (
                'title' => "Place Housemate Ad",
                'controller' => 'placeAd',
                'action' => 'tenant',
            	'href' => ''
            ),
            array (
                'title' => "Place Ad",
                'controller' => 'placeAd',
            	'href' => ''
            ),
            array (
                'title' => "Place Letting Ad",
                'controller' => 'placeAd',
                'action' => 'landlord',
            	'href' => ''
            ),
            array (
                'title' => "Activate Your Ad",
                'controller' => 'placeAd',
                'action' => 'publish',
            	'href' => ''
            ),


        );

        $breadcrumbs = array();
        foreach($array as $item)
        {
            if(isset($item['action']) && $item['controller'] == Helper::GetUrlParam(1) && $item['action'] == Helper::GetUrlParam(2)) $breadcrumbs[1] = $item;
            else if($item['controller'] == Helper::GetUrlParam(1) && !isset($item['action'])) $breadcrumbs[0] = $item;
            //if($item['controller'] != Helper::GetUrlParam(1)) $firstBreadcrumb .= $item['controller']." != ".Helper::GetUrlParam(1)."   ";
             //$firstBreadcrumb = $item['title'];
        }
        /*if(isset($breadcrumbs["first"])) $firstBreadcrumb = $breadcrumbs["first"];
        if(isset($breadcrumbs["second"])) $secondBreadcrumb = $breadcrumbs["second"];
        if(isset($breadcrumbs["tree"])) $treeBreadcrumb = $breadcrumbs["tree"];*/

        if($changebreadcrumbs)
            foreach($changebreadcrumbs as $key=>$value)
                if(is_numeric($key))
                    $breadcrumbs[$key] = $value;

        array_unshift($breadcrumbs, array('title'=>'HOBO Lettings', 'href' => Helper::GetUrlParam(1) != 'control' ? '/' : "/conrol"));

        $result="<div class='trigger'>";
        $i=0;
        if(Helper::GetUrlParam(1) != 'control' && Helper::GetUrlParam(1) != 'page' &&  count($breadcrumbs)<=2 && !$trigger) return "";
        foreach($breadcrumbs as $breadcrumb)
        {
            $class="level$i";
            if($i==0) $class="first";
            if(count($breadcrumbs)-1 == $i)
                $result .= "<a class='last' href='#'>".$breadcrumb['title']. "</a> ";
            else
                $result .= "<a class='{$class}' href='{$breadcrumb['href']}'>".$breadcrumb['title']. "</a> "; //.($class=="last" ? "" : ">> ");
            $i++;
        }


        $result.="</div>";

        if($trigger)
        {
            $t="";
            array_shift($breadcrumbs);
            foreach($breadcrumbs as $breadcrumb)
                if(empty($t)) $t.= $breadcrumb['title'];
                else $t.= " >> ".$breadcrumb['title'];
            $t.= " >> HOBO Lettings";
            $view   = Zend_Layout::getMvcInstance()->getView();
            if(self::GetUrlParam(1)=="page" && isset($changebreadcrumbs[2]) && strpos($changebreadcrumbs[2]['title'],"Lettings ")!==-1)
            {
                $temp =str_replace("HOBO Lettings","", str_replace(">>",", ", $t)." letting agencies, lettings uk");
                $temp = str_replace($changebreadcrumbs[3]['title']." ," ,"",$temp);
                $view->headMeta()->prependName('keywords', $temp);
            }
            else $view->headMeta()->prependName('keywords', str_replace("HOBO Lettings","", str_replace(">>",", ", $t)." letting agencies, lettings uk"));
            //$view->headMeta()->setName('keywords',"letting agencies, lettings uk");
            return $t;
        }
        else return $result;
    }

    static function formmessage($message)
    {
        if ($message == new stdClass()) return null;
        $class = isset($message->class) ? $message->class : 'message';
        return "<p class='{$class}'>{$message->text}</p>";
    }

    //calculate years of age (input string: YYYY-MM-DD)
    static function age ($birthday)
    {
        if($birthday==null) return "N/A";
        list($year,$month,$day) = explode("-",$birthday);
        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
        if ($day_diff < 0 || $month_diff < 0)
          $year_diff--;
        return $year_diff;
    }

    static function img($url, $w, $h, $crop=true, $atr="", $fillcolor=false, $zoom=false)
    {

        $src= Graphics::createthumb('../..'.$url,$w,$h, $crop, $fillcolor);
        $flag=true;
        if(!$src)
        {
            $src = Graphics::createthumb('../../useruploads/images/nophoto.jpg',100,100, true);
            $flag=false;
        }
         if(isset($_GET['debug']))
        {
            var_dump($src);
            exit;
        }
        $src = str_replace('../..','',$src);
	    $str="<img {$atr} src='{$src}' />";
	    if($zoom && $flag) $str = "<a href='#' onclick=\"showPopUp('{$url}',500,500); return false\" />".$str."</a>";
	    return $str;
    }

    static function days ($date)
    {/*
        if($date==null) return "N/A";
        list($year,$month,$day) = explode("-",$date);
        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
        if ($day_diff < 0 || $month_diff < 0)
          $year_diff--;
        return $year_diff;*/
    }

    static function NewFileName($file_name)
    {

		$str = date('YmdHis');
		$ext = array_pop(explode(".", $file_name));
		$name = substr($file_name, 0, - strlen($ext)-1);
		$newname = $name."_".$str.".".$ext;
		//$newname = self::CyrToLatin($newname);
		return $newname;
    }

    static function CyrToLatin($str, $quote_fix = false)
    {
    	$mm=strlen($str);
    	$return_string = "";

    	for ($i=0;$i<=$mm;$i++)
    	{
    	$ss=mb_substr($str,$i,1,'utf-8');

        switch ($ss)

    	{

    	case "�":
    	        $return_string = $return_string."sch";
    	        break;

    	case "�":
    	        $return_string = $return_string."ch";
    	        break;

    	case "�":
    	        $return_string = $return_string."sh";
    	        break;

    	case "�":
    	        $return_string = $return_string."ja";
    	        break;

    	case "�":
    	        $return_string = $return_string."ju";
    	        break;

    	case "�":
    	        $return_string = $return_string."jo";
    	        break;

    	case "�":
    	        $return_string = $return_string."zh";
    	        break;

    	case "�":
    	        $return_string = $return_string."e";
    	        break;

    	case "�":
    	        $return_string = $return_string."Sch";
    	        break;

    	 case "�":
    	        $return_string = $return_string."Ch";
    	        break;

    	        case "�":
    	        $return_string = $return_string."Sh";
    	        break;

    	 case "�":
    	        $return_string = $return_string."Ja";
    	        break;

    	case "�":
    	        $return_string = $return_string."Ju";
    	        break;

    	case "�":
    	        $return_string = $return_string."Jo";
    	        break;

    	case "�":
    	        $return_string = $return_string."Zh";
    	        break;

    	case "�":
    	        $return_string = $return_string."E";
    	        break;

    	case "�":
    	        $return_string = $return_string."";
    	        break;

    	case "�":
    	        $return_string = $return_string."";
    	        break;

    	case "�":
    	        $return_string = $return_string."a";
    	        break;

    	 case "�":
    	        $return_string = $return_string."b";
    	        break;

    	case "�":
    	        $return_string = $return_string."c";
    	        break;

    	case "�":
    	        $return_string = $return_string."d";
    	        break;

    	case "�":
    	        $return_string = $return_string."e";
    	        break;

    	case "�":
    	        $return_string = $return_string."f";
    	        break;

    	case "�":
    	        $return_string = $return_string."g";
    	        break;
    	case "�":
    	        $return_string = $return_string."h";
    	        break;

    	case "�": case "�": case "�":
    	        $return_string = $return_string."i";
    	        break;

    	case "�":
    	        $return_string = $return_string."j";
    	        break;

    	case "�":

    	     {

    	        if ($str[$i+1]=="�" ) {
    	       $return_string = $return_string."x";
    	       $i=$i+1; break;}

    	        $return_string = $return_string."k";
    	        break;

    	       }

    	case "�":
    	        $return_string = $return_string."l";
    	        break;

    	case "�":
    	        $return_string = $return_string."m";
    	        break;
    	case "�":
    	        $return_string = $return_string."n";
    	        break;
    	case "�":
    	        $return_string = $return_string."o";
    	        break;
    	case "�":
    	        $return_string = $return_string."p";
    	        break;

    	 case "�":
    	        $return_string = $return_string."r";
    	        break;

    	case "�":
    	        $return_string = $return_string."s";
    	        break;

    	case "�":
    	        $return_string = $return_string."t";
    	        break;

    	case "�":
    	        $return_string = $return_string."u";
    	        break;

    	case "�":
    	        $return_string = $return_string."v";
    	        break;

    	case "�":
    	        $return_string = $return_string."y";
    	        break;

    	case "�":
    	        $return_string = $return_string."z";
    	        break;

    	case "�":
    	        $return_string = $return_string."'";
    	        break;

    	case "�":

    	        $return_string = $return_string."'";
    	        break;

    	case "�":
    	        $return_string = $return_string."A";
    	        break;

    	case "�":
    	        $return_string = $return_string."B";
    	        break;

    	case "�":
    	        $return_string = $return_string."C";
    	        break;

    	case "�":
    	        $return_string = $return_string."D";
    	        break;

    	 case "�":
    	        $return_string = $return_string."E";
    	        break;

    	case "�":
    	        $return_string = $return_string."F";
    	        break;

    	case "�":
    	        $return_string = $return_string."G";
    	        break;

    	case "�":
    	        $return_string = $return_string."H";
    	        break;

    	case "�":
    	        $return_string = $return_string."I";
    	        break;

    	case "�":
    	        $return_string = $return_string."J";
    	        break;

    	case "�":

    	     {

    	      if ($str[$i+1]=="�" ) {
    	       $return_string = $return_string."X";
    	       $i=$i+1; break;}

    	      if ($str[$i+1]=="�" ) {
    	       $return_string = $return_string."X";
    	       $i=$i+1; break;}

    	      $return_string = $return_string."K";
    	       break;

    	       }

    	case "�":
    	        $return_string = $return_string."L";
    	        break;

    	 case "�":
    	        $return_string = $return_string."M";
    	        break;

    	case "�":
    	        $return_string = $return_string."N";
    	        break;

    	case "�":
    	        $return_string = $return_string."O";
    	        break;

    	case "�":
    	        $return_string = $return_string."P";
    	        break;

    	case "�":
    	        $return_string = $return_string."R";
    	        break;

    	case "�":
    	        $return_string = $return_string."S";
    	        break;

    	case "�":
    	        $return_string = $return_string."T";
    	        break;

    	 case "�":
    	        $return_string = $return_string."U";
    	        break;

    	 case "�":
    	        $return_string = $return_string."V";
    	        break;

    	case "�":
    	        $return_string = $return_string."Y";
    	        break;

    	 case "�":
    	        $return_string = $return_string."Z";
    	        break;

    	 case " ":
    	        $return_string = $return_string."_";
    	        break;

    	case '"': case "'": case "�": case "�": case":": case"?": case",": case".": case"+": case"/": case"\\": case"|":
    			if($quote_fix){
    	       		 $return_string = $return_string."";
    			}
    	        break;

    	default:
    	        $return_string = $return_string.$ss;


    	}
    	}


    	if(empty($return_string)){
    		$return_string = $str;
    	}
    	return $return_string;
    }

    static function vaginator($pages, $current, $total)
    {
        $result =  '<div class="pager">';
        $prev = $current==1 ? "" :  '<a class="prev_btn" href="#" onclick="
                            					$(\'#page\')[0].value = \''. ($current-1) . '\'
        										document.search_form.submit();
                            					return false;">Prev</a> ';
        $result= $result.$prev;

        $pg = "";

        $j=$current>=4 ? $current-2 : 2;
        $i=1;
        for(; $i<=$pages && $i<=$current+2; $i++)
        {

                $style = $i == $current ? "active_nav" : "";
                $pg = $pg.'<a href="#" class="page_link '.$style.'" onclick="
                                    $(\'#page\')[0].value = \''.$i.'\';
                                    document.search_form.submit();
                                    return false;">
                                    '.$i.' </a>';
                if($i==1)
                {
                    if($j!=2) $pg = $pg." . . . ";
                    $i =$j-1;
                }
        }

        $result= $result.$pg;



        if($i<=$pages)
        {
            if($i<=$pages-1)
                $result= $result." . . . ";
            $result= $result.'<a href="#" class="page_link '.$style.'" onclick="
                                    $(\'#page\')[0].value = \''.$pages.'\';
                                    document.search_form.submit();
                                    return false;">
                                    '.$pages.' </a>';
        }

        $next = $current==$pages ? "" :  '<a class="next_btn" href="#" onclick="
                            					$(\'#page\')[0].value = \''. ($current+1) . '\'
        										document.search_form.submit();
                            					return false;">Next</a> ';

        $result= $result.$next;
        $result = $result."<span class='total'> <strong>$total</strong> Found</span></div>";
        return $result;
    }

    public static function uploadfile($path)
    {
        $path = "../..".$path;
        if(isset($_FILES) && !empty($_FILES))
        {
            foreach($_FILES as $key=>$file)
            {
                if(empty($file['name'])) continue;
                if(!is_dir($path)) mkdir($path);
                //$file['name'] = Helper::NewFileName($file['name']);
    		    move_uploaded_file($file['tmp_name'], $path.$file['name']);
    		    chmod($path.$file['name'], 0777);
    		    $_POST[$key]=  $file['name'];
            }
        }
    }

    public static function rowcounter($table,$select)
    {
        $db = Zend_Registry::get('db');
		$select  = $db->select()->from('property',array("count"=>"COUNT(*)"));
        $result = $db->fetchRow($select);
        return $result['count'];
    }

    static function vaginator3($pages, $current, $total, $form=null)
    {

       $submit = $form ? "document.$form.submit();" : "document.forms[0].submit()";
        $result =  '<div class="pager">';
        $prev = $current==1 ? "" :  '<a class="prev_btn" href="#" onclick="
                            					$(\'[name='.$form.'] #page\')[0].value = \''. ($current-1) . '\';
                            					'.$submit.'
                            					return false;">Prev</a> ';
        $result= $result.$prev;

        $pg = "";

        $j=$current>=4 ? $current-2 : 2;
        $i=1;
         for(; $i<=$pages && $i<=$current+2; $i++)
        {

                $style = $i == $current ? "active_nav" : "";
                $pg = $pg.'<a href="#" class="page_link '.$style.'" onclick="
                                    $(\'[name='.$form.'] #page\')[0].value = \''.$i.'\';
                                    '.$submit.'
                                    return false;">
                                    '.$i.' </a>';
                if($i==1)
                {
                    if($j!=2) $pg = $pg." . . . ";
                    $i =$j-1;
                }
        }

        $result= $result.$pg;



        if($i<=$pages)
        {
            if($i<=$pages-1)
                $result= $result." . . . ";
            $result= $result.'<a href="#" class="page_link '.$style.'" onclick="
                                    $(\'[name='.$form.'] #page\')[0].value = \''.$pages.'\';
                                    '.$submit.'
                                    return false;">
                                    '.$pages.' </a>';
        }

        $next = $current==$pages ? "" :  '<a class="next_btn" href="#" onclick="
                            					$(\'[name='.$form.'] #page\')[0].value = \''. ($current+1) . '\';
        										'.$submit.'
                            					return false;">Next</a> ';

        $result= $result.$next;
        $result = $result."<span class='total'> <strong>$total</strong> Found</span></div>";
        return $result;
    }

    static function deleteFiles($name,$path)
    {
        $n = explode(".",$name);
        $name = $n[0];
        $flist = glob($path.$name."_???x???.*");

        foreach($flist as $fname) unlink($fname);
    }

    public static function cityName($id)
    {
        $city = new City();
        $city = $city->fetchAll("city_id=".$id)->current();
        return $city->title;
    }
    public static function cityId($name)
    {
        $city = new City();
        $city = $city->fetchAll("title='".$name."'")->current();
        if(!$city) return null;
        return $city->city_id;
    }

    public static function countBids($id)
    {
        $db = Zend_Registry::get('db');
        $select  = Zend_Registry::get('db')->select()
                      ->from(array('message'), array('count' => ' COUNT(*) '))
                      ->join(array('property'),"property.property_id=message.property_id", array())
                      ->where("bid is not null")
                      ->where("property.property_id=".$id);

        $result = current($db->query($select)->fetchAll());
        return   $result['count'];
    }
    public static function countBidsTenant($id)
    {
        $db = Zend_Registry::get('db');
        $select  = Zend_Registry::get('db')->select()
                      ->from(array('message'), array('count' => ' COUNT(*) '))
                      ->join(array('tenant'),"tenant.tenant_id=message.tenant_id", array())
                      ->where("bid is not null")
                      ->where("tenant.tenant_id=".$id);

        $result = current($db->query($select)->fetchAll());
        return   $result['count'];
    }

    public static function limit($table, $limit, $where=null)
    {
        $current_page = isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page'] : 1);

    }

    public static function drystalka($table, $where, $limit=10, $group=null)
    {
        $current = isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page'] : 1);
        $dbtable = new dbtable();
        $dbtable->_name = $table;
        $total = $dbtable->countrows($where, $group);

        $pages = ceil($total/$limit);


        $result =  '<div class="pager">';
        $prev = $current==1 ? "" :  '<a class="prev_btn" href="#" onclick="
                            					this.href = document.location.href.split(\'?\')[0] + \'?page=\' + '.($current-1).'
                            					">Prev</a> ';
        $result= $result.$prev;

        $pg = "";

        $j=$current>=4 ? $current-2 : 2;
        $i=1;
        for(; $i<=$pages && $i<=$current+2; $i++)
        {

                $style = $i == $current ? "active_nav" : "";
                $pg = $pg.'<a href="#" class="page_link '.$style.'" onclick="
                                    this.href = document.location.href.split(\'?\')[0] + \'?page=\' + '.$i.'">
                                    '.$i.' </a>';
                if($i==1)
                {
                    if($j!=2) $pg = $pg." . . . ";
                    $i =$j-1;
                }
        }

        $result= $result.$pg;



        if($i<=$pages)
        {
            if($i<=$pages-1)
                $result= $result." . . . ";
            $result= $result.'<a href="#" class="page_link '.$style.'" onclick="
                                    this.href = document.location.href.split(\'?\')[0] + \'?page=\' + '.$i.'">
                                    '.$pages.' </a>';
        }

        $next = $current==$pages ? "" :  '<a class="next_btn" href="#" onclick="
                            					this.href = document.location.href.split(\'?\')[0] + \'?page=\' + '.$i.'">
                            					Next</a> ';

        $result= $result.$next;
        $result = $result."<span class='total'> <strong>$total</strong> Found</span></div>";
        return $result;
    }

    public static function drystalka2($total, $limit=10)
    {
        $current = isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page'] : 1);
        $pages = ceil($total/$limit);

        $result =  '<div class="pager">';
        $prev = $current==1 ? "" :  '<a class="prev_btn" href="#" onclick="
                            					this.href = document.location.href.split(\'?\')[0] + \'?page=\' + '.($current-1).'
                            					">Prev</a> ';
        $result= $result.$prev;

        $pg = "";

        $j=$current>=4 ? $current-2 : 2;
        $i=1;
        for(; $i<=$pages && $i<=$current+2; $i++)
        {

                $style = $i == $current ? "active_nav" : "";
                $pg = $pg.'<a href="#" class="page_link '.$style.'" onclick="
                                    this.href = document.location.href.split(\'?\')[0] + \'?page=\' + '.$i.'">
                                    '.$i.' </a>';
                if($i==1)
                {
                    if($j!=2) $pg = $pg." . . . ";
                    $i =$j-1;
                }
        }

        $result= $result.$pg;



        if($i<=$pages)
        {
            if($i<=$pages-1)
                $result= $result." . . . ";
            $result= $result.'<a href="#" class="page_link '.$style.'" onclick="
                                    this.href = document.location.href.split(\'?\')[0] + \'?page=\' + '.$i.'">
                                    '.$pages.' </a>';
        }

        $next = $current==$pages ? "" :  '<a class="next_btn" href="#" onclick="
                            					this.href = document.location.href.split(\'?\')[0] + \'?page=\' + '.$i.'">
                            					Next</a> ';

        $result= $result.$next;
        $result = $result."<span class='total'> <strong>$total</strong> Found</span></div>";
        return $result;
    }

    public static function days_difference($date, $date2=null)
    {
        if($date2) return floor((strtotime($date2) - strtotime($date))/86400);
        else return floor((time() - strtotime($date))/86400);
    }

    public static function total($table, $where)
    {
        $dbtable = new dbtable();
        $dbtable->_name = $table;
        return $dbtable->countrows($where);
    }

    public static function totalBedrooms($val,$tenant=false)
    {
        $db = Zend_Registry::get('db');
        if($tenant)
        {
            $select  = Zend_Registry::get('db')->select()
                      ->from(array('tenant'), array('count' => ' COUNT(*) '))
                      ->where("bedrooms=".$val);
        }
        else
        {
            $select  = Zend_Registry::get('db')->select()
                      ->from(array('property'), array('count' => ' COUNT(*) '))
                      ->join(array('address'),"property.address_id=address.address_id", array())
                      ->where("address.bedrooms=".$val);
        }
        $result = current($db->query($select)->fetchAll());
        return $result['count'];
    }

    //image must have the form as 'file://attached_image' in $bodyLeter
    static function sendLeter($to, $subject, $html, $filename='../../shell/images/logos/hobo_mail.jpg', $contacts = true)
    {
    	$mail = new Zend_Mail('UTF-8');


                if (is_readable($filename))
                {
                    $mail->setType(Zend_Mime::MULTIPART_RELATED);
                    $at = $mail->createAttachment(file_get_contents($filename));
                    $at->type = 'image/png';
                    $at->disposition = Zend_Mime::DISPOSITION_INLINE;
                    $at->encoding = Zend_Mime::ENCODING_BASE64;
                    $at->id = 'cid_' . md5_file($filename);
                    $html = str_replace('file://attached_image',  'cid:' . $at->id,  $html);

                }
                if($contacts) $html.=self::etemplate(array(),191);
                $mail->setBodyText('Link to change your password');
                $mail->setBodyHtml($html, 'UTF-8', Zend_Mime::ENCODING_8BIT);
                $mail->setFrom('info@hobolettings.com', 'HOBO Lettings');
                $mail->addTo($to, 'HOBO Lettings');
                $mail->setSubject($subject);
                $mail->send();

    }

    static function etemplate($array,$id)
    {
        $post = new Post();
        $post = $post->find($id)->current();

        if(!$post) return "";
        $result = $post->post_content;
        $result = str_replace("{site}", $_SERVER['HTTP_HOST'], $result);
        foreach($array as $key=>$value)
            $result = str_replace("{".$key."}", $value, $result);
        return $result;
    }

    static function sendTemplateLeter($to, $template_id, $array=array(), $contacts = true, $filename='../../shell/images/logos/hobo_mail.jpg')
    {
        $post = new Post();
        $post = $post->find($template_id)->current();

        if(!$post) return;
        $html = $post->post_content;
        $html = str_replace("{site}", $_SERVER['HTTP_HOST'], $html);
        foreach($array as $key=>$value)
            $html = str_replace("{".$key."}", $value, $html);

        $subject = $post->post_title;
    	$mail = new Zend_Mail('UTF-8');


                if (is_readable($filename))
                {
                    $mail->setType(Zend_Mime::MULTIPART_RELATED);
                    $at = $mail->createAttachment(file_get_contents($filename));
                    $at->type = 'image/png';
                    $at->disposition = Zend_Mime::DISPOSITION_INLINE;
                    $at->encoding = Zend_Mime::ENCODING_BASE64;
                    $at->id = 'cid_' . md5_file($filename);
                    $html = str_replace('file://attached_image',  'cid:' . $at->id,  $html);

                }
                if($contacts) $html.=self::etemplate(array(),191);
                $mail->setBodyText('Link to change your password');
                $mail->setBodyHtml($html, 'UTF-8', Zend_Mime::ENCODING_8BIT);
                $mail->setFrom('info@hobolettings.com', 'HOBO Lettings');
                $mail->addTo($to, 'HOBO Lettings');
                $mail->setSubject($subject);
                $mail->send();

    }


    static function login($id)
    {
        $users = new Users();
        $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(), 'users');
        $authAdapter->setIdentityColumn('user_id')->setCredentialColumn('user_id')->setIdentity($id)->setCredential($id);
        Zend_Auth::getInstance()->authenticate($authAdapter);
        Zend_Auth::getInstance()->getStorage()->write($authAdapter->getResultRowObject());
    }

    static function jAlert($message)
    {
        Zend_Layout::getMvcInstance()->jAlert=$message;
    }

    static function haveBockedAds($user_id)
    {
        $result = Zend_Registry::get('db')->query(
        "SELECT ((SELECT count( * ) FROM property WHERE user_id =$user_id AND blocked = 'admin' AND edit_date < block_date ) + ( SELECT count( * ) FROM tenant WHERE user_id = $user_id AND blocked IS NOT NULL AND edit_date < block_date ) ) AS count" )->fetchAll();

        return $result[0]['count'];
    }
    static function unansweredFeedbacks()
    {
        $result = Zend_Registry::get('db')->query(
        "SELECT COUNT(*) FROM feedback WHERE answer is null" )->fetchAll();
        return $result[0]['COUNT(*)'];
    }


}

class dbtable extends Zend_Db_Table
{
    public $_name = '';
    public function countrows($where=null, $group=null)
    {
        if(is_array($where)) $where = implode(" AND ", $where);
        $db = Zend_Registry::get('db');
        $select  = Zend_Registry::get('db')->select()
                      ->from(array($this->_name), array('count' => ' COUNT(*) '))
                      ->where($where);
        if($group) $select->group($group);
        $result = current($db->query($select)->fetchAll());
        return $result['count'];
    }
}