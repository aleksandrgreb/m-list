<?php

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<META NAME="Description" content="краткое описание сайта/страницы">
<META NAME="Keywords" content=" ключевые слова через запятую/смысловая нагрузка страницы ">
<TITLE>Добавить E-mail адрес в белый список почтового сервера</TITLE>

 <style type='text/css'> 

   h1 {
    font-family: Arial, 'Times New Roman', Times, serif; /* Гарнитура текста */ 
    color:white; font-size: 150%; /* Размер шрифта в процентах */ 
   } 

   p {
    font-family: Verdana, Arial, Helvetica, sans-serif; 
    font-size: 10pt; /* Размер шрифта в пунктах */ 
   }
   
 td{font-family: Verdana, Arial, Helvetica, sans-serif; color:black; font-size:10pt;}

 </style>   


</HEAD>
<BODY bgcolor="white" text="#000000" link="grey" vlink="white" alink="Fuchsia" font face="Arial,'Comic Sans MS',Courier" 
<basefont size="2">



<center>
<table>
<tr>
<td colspan="3" align="center""><h4>E-mail, домен или IP адрес:</h4></td>
<td>
</tr>
<form>

<tr>
<td colspan="3" align="center"><input type=text name=inputvalue size="50"></td>
</tr>

<tr>
<td colspan="3" align="center"><p><input type="checkbox" name='wl_sa' value='add' size="50"> - добавить в белый список spammassassin</p></td>
</tr>

<tr>
<td>
<input type='submit' name='whitelist' value='Добавить в белый список' style="background-color: #bde9ba;">&nbsp
</td>
<td>
<input type='submit' name='blacklist' value='Добавить в чёрный список' style="background-color: #ffd78c;">&nbsp
</td>
<td>
<input type='submit' name='delete' value='Удалить адрес из списков'  style="background-color: #ffa07a;">
</td>
</tr>
</form>
</table>
</center>
<br>



<?php

include 'vars.php';

 if(isset($_GET['whitelist']))
 {
  $inputvalue=$_GET['inputvalue'];
  $cmd="sudo /usr/local/bin/whitelist_add $inputvalue";
  $add_cmd="ssh -o \"StrictHostKeyChecking no\" $sudo_login@$mail_server \"$cmd\"";
  exec($add_cmd,$output,$retval);
//  var_dump($output,$retval);
//  echo "$output[0]<br>";
    foreach($output as $a)
    {
	echo "$a <br>";
    }

  if(isset($_GET['wl_sa']))
  {
    echo "Adding in spamassassin whitelist ...<br>\n"
;   $cmd1="sudo /usr/local/bin/wl_sa_add $inputvalue";
    $add_cmd1="ssh -o \"StrictHostKeyChecking no\" $sudo_login@$mail_server \"$cmd1\"";
    exec($add_cmd1,$output1,$retval);
//    echo "$output1[0]<br>";
    foreach($output1 as $a1)
    {
	echo "$a1 <br>";
    }
  }
 }

 if(isset($_GET['blacklist'])) 
 {
  $inputvalue=$_GET['inputvalue'];
  $cmd="sudo /usr/local/bin/blacklist_add $inputvalue";
  $add_cmd="ssh -o \"StrictHostKeyChecking no\" $sudo_login@$mail_server \"$cmd\"";
  exec($add_cmd,$output,$retval);
//  var_dump($output,$retval);
  echo "$output[0]<br>";
 }

 if(isset($_GET['delete'])) 
 {
  $inputvalue=$_GET['inputvalue'];
  $cmd="sudo /usr/local/bin/del_fromlist $inputvalue";
  $add_cmd="ssh -o \"StrictHostKeyChecking no\" $sudo_login@$mail_server \"$cmd\"";
  exec($add_cmd,$output,$retval);
//  var_dump($output,$retval);
  echo "$output[0]<br>";
 }
?>




<?php

 if(isset($_GET['show_sa']))
  {

   $cmd0="sudo /usr/local/bin/show_sa";
   $add_cmd0="ssh -o \"StrictHostKeyChecking no\" $sudo_login@$mail_server \"$cmd0\"";
   exec($add_cmd0,$output0,$retval);

    $sa_list=sel_sa($output0);

    echo "Белый список spamassassin:";

    echo "<table style=\"background-color: #bde9ba;\">";

    foreach($sa_list as $key => $v)
    {
      echo "<tr><td>$key</td></tr>\n";
    }

    echo "</table >";

  }


 if(isset($_GET['showlists']))
 {
  $inputvalue=$_GET['inputvalue'];

  $cmd0="sudo /usr/local/bin/show_sa";
  $add_cmd0="ssh -o \"StrictHostKeyChecking no\" $sudo_login@$mail_server \"$cmd0\"";
  exec($add_cmd0,$output0,$retval);
//  foreach($output0 as $a1)
//    {
//	echo "$a1 <br>";
//    }


    $sa_list=sel_sa($output0);

/*
    foreach($sa_list as $key => $v)
    {
      echo "k=$key<br>\n";
    }
*/

  $cmd="sudo /usr/local/bin/show_lists";
  $add_cmd="ssh -o \"StrictHostKeyChecking no\" $sudo_login@$mail_server \"$cmd\"";
  exec($add_cmd,$output,$retval);
  make_lists($output,$sa_list);
//  var_dump($output,$retval);
 }
 else
 {
  echo "<br><form><input type='submit' name='showlists' value='Показать списки'></form>";
 }


function sel_sa($output)
{
  $ar=[]; 
  foreach($output as $line)
  {
    if(preg_match("/^whitelist_from ([0-9a-zA-Z@_\-\.]+)$/",$line,$matches))
    {
//        $ar[]=trim($matches[1]);
	$ar[$matches[1]]='wl';
    }
  }
 
 return $ar;
}

function make_lists($output,$sa_list)
{
 
  foreach($output as $line)
  {
//    echo "line=$line<br>";
    if(preg_match("/^domain .+/",$line))
    {
      if(preg_match("/[[:blank:]]([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})[[:blank:]]([A-Z]+)$/",$line,$matches))
       {

	$value=trim($matches[2]);
	$ipaddress[$matches[1]]=$value;
        if($value=='OK')
	    {
		$white_ipaddress[$matches[1]]=$value;
		//echo "white ipaddress $matches[1] $matches[2]<br>";
	    }
	  else
	    {
		$black_ipaddress[$matches[1]]=$value;
		//echo "black ipaddress $matches[1] $matches[2]<br>";
	    }
       }

    }
    else
    {
      if(preg_match("/[[:blank:]]([a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-\.]+\.[a-zA-Z]{2,5})[[:blank:]]([A-Z]+)/",$line,$matches))
	{
         $value=trim($matches[2]);
	 $email[$matches[1]]=$value;
         if($value=='OK')
	    {
		$white_email[$matches[1]]=$value;
		//echo "white email $matches[1] $matches[2]<br>";
	    }
	  else
	    {
		$black_email[$matches[1]]=$value;
		//echo "black email $matches[1] $matches[2]<br>";
	    }
	}

      if(preg_match("/[[:blank:]]([a-zA-Z0-9_\-\.]+\.[a-zA-Z]{2,10})[[:blank:]]([A-Z]+)$/",$line,$matches))
       {
        $value=trim($matches[2]);
	$domain[$matches[1]]=$value;
        if($value=='OK')
	    {
		$white_domain[$matches[1]]=$value;
		//echo "white domain $matches[1] $matches[2]<br>";
	    }
	  else
	    {
		$black_domain[$matches[1]]=$value;
		//echo "black domain $matches[1] $matches[2]<br>";
	    }

       }      


    }
  }

 echo "<center>";



 echo "<table >";

 echo "<tr>";
 echo "<td colspan=1 align=\"center\">";
 echo "<h3>Белые списки</h3>";
 echo "</td>";

 echo "<td colspan=1 align=\"center\">";
 echo "<h3>Черные списки</h3>";
 echo "</td>";
 echo "</tr>";


 echo "<tr>";
 echo "<td style=\"vertical-align:top\">";
//__________________________________________________


 echo "<table style=\"background-color: #bde9ba;\">";
 echo "<tr>";
 echo "<th>E-mail адреса</th>";
 echo "<th>Домен</th>";
 echo "<th>IP адреса</th>";
 echo "</tr>";

 echo "<tr>";
 echo "<td style=\"vertical-align:top\">";
//------------------------------------------
 echo "<table>";
foreach($white_email as $email=>$value)
{
 if(isset($sa_list[$email]))
 {
  echo "<tr><td><b>$email</b></td>";
 }
else
 {
   echo "<tr><td>$email</td>";
 }
 echo "<td><form>";
 echo "<button type='submit' name='delete' title='Удалить'><img src='delete.png'></button>";
 echo "<input type=\"hidden\" name=\"inputvalue\" value=$email>";
 echo "</form></td>";
 echo "</tr>";
}
 echo "</table>";
//------------------------------------------
 echo "</td>";


echo "<td style=\"vertical-align:top\">";

//------------------------------------------
echo "<table>";
foreach($white_domain as $domain=>$value)
{
 if(isset($sa_list[$domain]))
 {
  echo "<tr><td><b>$domain</b></td>";
 }
else
 {
   echo "<tr><td>$domain</td>";
 }
 echo "<td><form>";
 echo "<button type='submit' name='delete' title='Удалить'><img src='delete.png'></button>";
 echo "<input type=\"hidden\" name=\"inputvalue\" value=$domain>";
 echo "</form></td>";
 echo "</tr>";
}
 echo "</table>";
//-----------------------------------------


 echo "</td>";



 echo "<td style=\"vertical-align:top\">";
//-----------------------------------------
echo "<table>";
foreach($white_ipaddress as $ipaddress=>$value)
{
 echo "<tr><td>$ipaddress</td>";
 echo "<td><form>";
 echo "<button type='submit' name='delete' title='Удалить'><img src='delete.png'></button>";
 echo "<input type=\"hidden\" name=\"inputvalue\" value=$ipaddress>";
 echo "</form></td></td>";
 echo "</tr>";
}
 echo "</table>";
//-----------------------------------------


 echo "</td>";




 echo "</tr>";

echo "</table>";
//__________________________________________________

echo "</td>";
//___________________________________________________
echo "<td  style=\"vertical-align:top\">";



 echo "<table style=\"background-color: #ffd78c;\">";
 echo "<tr>";
 echo "<th>E-mail адреса</th>";
 echo "<th>Домен</th>";
 echo "<th>IP адреса</th>";
 echo "</tr>";

 echo "<tr>";
 echo "<td style=\"vertical-align:top\">";
//------------------------------------------
 echo "<table>";
foreach($black_email as $email=>$value)
{
 echo "<tr><td>$email</td>";
 echo "<td><form>";
 echo "<button type='submit' name='delete' title='Удалить'><img src='delete.png'></button>";
 echo "<input type=\"hidden\" name=\"inputvalue\" value=$email>";
 echo "</form></td></td>";
 echo "</tr>";

}
 echo "</table>";
//------------------------------------------
 echo "</td>";

echo "<td style=\"vertical-align:top\">";

//------------------------------------------
echo "<table>";
foreach($black_domain as $domain=>$value)
{
 echo "<tr><td>$domain</td>";
 echo "<td><form>";
 echo "<button type='submit' name='delete' title='Удалить'><img src='delete.png'></button>";
 echo "<input type=\"hidden\" name=\"inputvalue\" value=$domain>";
 echo "</form></td></td>";
 echo "</tr>";
}
 echo "</table>";
//-----------------------------------------


 echo "</td>";



 echo "<td style=\"vertical-align:top\">";
//-----------------------------------------
echo "<table>";
foreach($black_ipaddress as $ipaddress=>$value)
{
 echo "<tr><td>$ipaddress</td>";
 echo "<td><form>";
 echo "<button type='submit' name='delete' title='Удалить'><img src='delete.png'></button>";
 echo "<input type=\"hidden\" name=\"inputvalue\" value=$ipaddress>";
 echo "</form></td></td>";
 echo "</tr>";
}
 echo "</table>";
//-----------------------------------------

 echo "</td>";

 echo "</tr>";

echo "</table>";



//________________________________________________
echo "</td>";

echo "</tr>";

if(count($sa_list)>0)
{
 echo "<tr>";
 echo "<td>";
 echo "<b>жирным шрифтом</b> обозначены адреса входящие в белый список spamassassin";
 echo "</td>";
 echo "</tr>";

 echo "<tr>";
 echo "<td>";
 echo "<form><input type='submit' name='show_sa' value='Показать былый список spamassassin'></form>";
 echo "</td>";
 echo "</tr>";


}

echo "</table>";


echo "</center>";

}


?>

</BODY>
</HTML>
