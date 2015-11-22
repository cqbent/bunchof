<?php
if(isset($_POST['adsensexplosion']['lang']))
{
   if($_POST['adsensexplosion']['lang'] == 'it')
   {
      include_once('it.php');
   }
   else if($_POST['adsensexplosion']['lang'] == 'es')
   {
      include_once('es.php');
   }
   else
   {
      include_once('en.php');
   }
}
else
{
   include_once($this->opts['lang'] . '.php');
}
$lbsizes = array();
$lbsizes[] = array('desc' => '728 x 15', 'text' => '728x15');
$lbsizes[] = array('desc' => '468 x 15', 'text' => '468x15');
$lbsizes[] = array('desc' => '200 x 90', 'text' => '200x90');
$lbsizes[] = array('desc' => '180 x 90', 'text' => '180x90');
$lbsizes[] = array('desc' => '160 x 90', 'text' => '160x90');
$lbsizes[] = array('desc' => '120 x 90', 'text' => '120x90');
if(isset($_POST["adsensexplosion_update"])){$this->opts = $this->ripulisci_parametri($_POST['adsensexplosion'], $sizes, $lbsizes);$this->save_opts();}
$this->opts['is_only_tagged'] = $this->opts['only_tagged'] ? 'checked' : '';
$this->opts['is_omit_page'] = $this->opts['omit_page'] ? 'checked' : '';
$this->opts['is_policy_protect'] = $this->opts['policy_protect'] ? 'checked' : '';
$this->opts['is_omit_home'] = $this->opts['omit_home'] ? 'checked' : '';
$this->opts['is_omit_search'] = $this->opts['omit_search'] ? 'checked' : '';
$this->opts['is_omit_category'] = $this->opts['omit_category'] ? 'checked' : '';
$this->opts['is_omit_tag'] = $this->opts['omit_tag'] ? 'checked' : '';
$this->opts['is_omit_date'] = $this->opts['omit_date'] ? 'checked' : '';
$this->opts['is_omit_author'] = $this->opts['omit_author'] ? 'checked' : '';
$this->opts['is_overrule'] = $this->opts['overrule'] ? 'checked' : '';
$this->opts['is_omit_css'] = $this->opts['omit_css'] ? 'checked' : '';
$this->opts['is_mobile'] = $this->opts['mobile'] ? 'checked' : '';
$this->opts['is_async'] = $this->opts['async'] ? 'checked' : '';
$this->opts['is_smd'] = $this->opts['smd'] ? 'checked' : '';

for($i = 1; $i <= 8; $i++){
	$this->opts['is_responsive_rectangle'][$i] = $this->opts['responsive_rectangle'][$i] ? 'checked' : '';
	$this->opts['is_responsive_vertical'][$i] = $this->opts['responsive_vertical'][$i] ? 'checked' : '';
	$this->opts['is_responsive_horizontal'][$i] = $this->opts['responsive_horizontal'][$i] ? 'checked' : '';
}

?>

<script type="text/javascript" src="<?php echo(WP_PLUGIN_URL . '/' . $this->aeopt_menu);?>/jscolor/jscolor.js"></script>

<script type="text/javascript">
    function toggleInformativaPrivacy() {
      jQuery(".container").slideToggle("slow");
    }
    function toggleUsertype(val) {
      if (val=="") {jQuery(".pro").hide(10);jQuery(".link").hide(10);}
//      if (val=="link") {jQuery(".pro").hide(10);jQuery(".link").show(10);}
      else if (val=="pro") {jQuery(".pro").show(10);jQuery(".link").show(10);}
    }
    function invisibleMargin(val) {
      val = val || jQuery('#async').is(':checked');
      if (val) {jQuery(".lb_margin").hide(10)} else {jQuery(".lb_margin").show(10);}
    }
    function toggleNewOld(val) {
      if (val) {jQuery(".old").hide(10); jQuery(".new").show(10); } else {jQuery(".old").show(10); jQuery(".new").hide(10);}
      show_active("1");
      invisibleMargin(jQuery('#omit_css').is(':checked'));
    }
    function toggleResponsiveAd(val) {
//      if (val) {window.alert("si"); jQuery(".responsive_ad").show(10); } else {window.alert("no"); jQuery(".responsive_ad").hide(10);}
      if (val) {jQuery(".responsive_ad").show(10); } else {jQuery(".responsive_ad").hide(10);}
      //show_active("1");
      //invisibleMargin(jQuery('#omit_css').is(':checked'));
    }
    function toggleDynResponsive(val) {
      if (val) {jQuery(".dyn_resp").show(10); } else {jQuery(".dyn_resp").hide(10);}
    }
    function toggleAdtype(val) {
      var nuovo = jQuery('#async').is(':checked');
      if (val=="link") {jQuery(".normal_ad").hide(10);jQuery(".lb_ad").show(10);}
      else {jQuery(".normal_ad").show(10);jQuery(".lb_ad").hide(10);}
      if(nuovo) {
        jQuery(".numberLink").hide(10);
        jQuery(".corner").hide(10);
      }
    }
    function checkradio(feld){
      for (i=0; i < feld.length; i++){
        if(feld[i].checked == true){
          return feld[i].value;
        }
      }
    }
    </script>



<div class="wrap" data-ng-app="dynamic-grid">
  <div id="contenitore"
    style="background-color: #F5F5F5; padding: 10px; -moz-border-radius: 3px; -webkit-border-radius: 3px; -khtml-border-radius: 3px; border-radius: 3px;">
    
<?php
if(isset($_POST["adsensexplosion_update"])) {
?>	
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Options Updated!
	</div>
<?php 
} 
?>    
    
    <form name="mainform" method="post"
      action="<?php echo $_SERVER["REQUEST_URI"];?>" novalidate="novalidate">
      <h2><?php echo $impostazioniGenerali;?></h2>
      <table>
        <tr>
          <td><?php echo $campoLingua;?></td>
          <td><select onchange="document.getElementById('ae_update').click();" name="adsensexplosion[lang]" size="1">
              <option value="en"
<?php if($this->opts['lang'] == "en") echo(" selected");?>>English</option>
              <option value="es"
<?php if($this->opts['lang'] == "es") echo(" selected");?>>Espa&#241;ol</option>
              <option value="it"
<?php if($this->opts['lang'] == "it") echo(" selected");?>>Italiano</option>
          </select></td>
          <td><span class="ao_explain"><?php echo $campoLinguaDescr;?></span></td>
        </tr>
        <tr>
          <td>Adsense Publisher ID:</td>
          <td><input name="adsensexplosion[gen_id]" type="text"
            value="<?php echo $this->opts['gen_id'];?>"></td>

          <td><span class="ao_explain"><?php echo $campoAdSenseIdDescr;?></span></td>
        </tr>
        <tr>
          <td><?php echo $campoAsync;?></td>
          <td><input type="checkbox" value="1" id="async" name="adsensexplosion[async]" onchange="toggleNewOld(jQuery('#async').is(':checked'))"
<?php echo $this->opts['is_async'];?>>
          </td>
          <td><span class="ao_explain"><?php echo $campoAsyncDescr;?></span></td>
        </tr>
        <tr class="old">
          <td>Adsense Channel</td>
          <td><input id="gen_gen_channel" name="adsensexplosion[gen_channel]" type="text"
            value="<?php echo $this->opts['gen_channel'];?>"></td>
          <td><span class="ao_explain"><?php echo $campoAdSenseChannelDescr;?></span>
          </td>
        </tr>
        <tr class="new">
          <td>Adsense Data Ad Slot</td>
          <td><input id="gen_dataslot" name="adsensexplosion[gen_dataslot]" type="text"
            value="<?php echo $this->opts['gen_dataslot'];?>"></td>
          <td><span class="ao_explain"><?php echo $istruzioniDataAdSlot;?></span>
          </td>
        </tr>
        <tr>
          <td><?php echo $campoProblema3Annuncio;?></td>
          <td><input type="checkbox" value="1" name="adsensexplosion[overrule]"
<?php echo $this->opts['is_overrule'];?>>
          </td>
          <td><span class="ao_explain"><?php echo $campoProblema3AnnuncioDescr;?></span></td>
        </tr>
        <tr>
          <td><?php echo $campoEscludiCSS;?></td>
          <td><input type="checkbox" value="1" id="omit_css" name="adsensexplosion[omit_css]" onchange="invisibleMargin(jQuery('#omit_css').is(':checked'))"
<?php echo $this->opts['is_omit_css'];?>>
          </td>
          <td><span class="ao_explain"><?php echo $campoEscludiCSSDescr;?></span></td>
        </tr>
        <tr>
          <td><?php echo $campoDisattivaSuMobile;?></td>
          <td><input type="checkbox" value="1" name="adsensexplosion[mobile]"
<?php echo $this->opts['is_mobile'];?>>
          </td>
          <td><span class="ao_explain"><?php echo $campoDisattivaSuMobileDescr;?></span></td>
        </tr>
      </table>
      <br />
      
      







  <p><?php echo $cheTipoDiUtenteSei;?></p>

  <input type="radio" id="usertypebasic" name="adsensexplosion[usertype]"
    onchange="toggleUsertype('')" value=""
<?php if($this->opts['usertype'] == "") echo('checked');echo($utenteBase);?><br> <input
    type="radio" id="usertypepro" name="adsensexplosion[usertype]"
    onchange="toggleUsertype('pro')" value="pro"
<?php if($this->opts['usertype'] == "pro") echo('checked');echo($utenteEsperto);?><br> <br>
  <div
    style="background-color: #D6D1FF; padding: 10px; -moz-border-radius: 3px; -webkit-border-radius: 3px; -khtml-border-radius: 3px; border-radius: 3px;">
    <h2><?php echo $configuraTuoiAnnunci;?></h2>

    <p><?php echo $puoiImpostareFino8DiversiTipiDiAnnuncio;?></p>







    <script type="text/javascript">
   function raiseEvent (eventType, elementID)
   {
       var o = document.getElementById(elementID);
       if (document.createEvent) {
           var evt = document.createEvent("Events");
           evt.initEvent(eventType, true, true);
           o.dispatchEvent(evt);
       } else if (document.createEventObject) {
           var evt = document.createEventObject();
           o.fireEvent('on' + eventType, evt);
       }
       o = null;
   }
</script>





<?php for($i = 1; $i <= 8; $i++){ ?>

    <br>

    <button style="display: inline"
      onclick="show_active('<?php echo $i; ?>'); return false;">
      Ad Type
<?php echo $i; ?>
    </button>

    <input type="text" name="adsensexplosion[desc][<?php echo $i; ?>]"
      size="80" value="<?php echo($this->opts['desc'][$i]); ?>">


    <div id="ad<?php echo $i; ?>" class="adtype">

      <table width="100%" border="0" cellspacing="0" cellpadding="6">

        <tr valign="top">
          <td>
            <div>
          Type: <select name="adsensexplosion[type][<?php echo $i; ?>]"
            id="adtypeselect<?php echo $i; ?>"
            onchange="toggleAdtype(this.value);" size="1">

              <option value="text_image" class="old"
<?php if($this->opts['type'][$i] == "both") echo(" selected"); echo $TestoImmagini; ?></option>

              <option value="text" class="old"
<?php if($this->opts['type'][$i] == "text") echo(" selected"); echo $soloTesto; ?></option>

              <option value="image"
<?php if($this->opts['type'][$i] == "image") echo(" selected"); echo $soloImmagini; ?></option>

              <option value="link"
<?php if($this->opts['type'][$i] == "link") echo(" selected"); echo $blocchiDiLink; ?></option>

          </select> <br>
            </div>


            <div class="normal_ad old corner">
<?php echo $angoli; ?><select name="adsensexplosion[corner][<?php echo $i; ?>]" size="1">
                <option value="rc:0"
<?php if($this->opts['corner'][$i] == "rc:0") echo(" selected"); echo $quadrati; ?></option>
                <option value="rc:6"
<?php if($this->opts['corner'][$i] == "rc:6") echo(" selected"); echo $arrotondati; ?></option>
                <option value="rc:10"
<?php if($this->opts['corner'][$i] == "rc:10") echo(" selected"); echo $moltoArrotondati; ?></option>
              </select>
            </div>



            <div class="lb_ad numberLink">
<?php echo $numeroDiLinks; ?> <select
                name="adsensexplosion[links][<?php echo $i; ?>]" size="1">
                <option value="4"
<?php if($this->opts['links'][$i] == "4") echo(" selected"); ?>>4</option>
                <option value="5"
<?php if($this->opts['links'][$i] == "5") echo(" selected"); ?>>5</option>
              </select>
            </div>





            <div class="lb_margin old">
<?php echo $margineAttornoAnnuncio; ?><input
                name="adsensexplosion[padding][<?php echo $i; ?>]" type="text"
                size="3" value="<?php echo $this->opts['padding'][$i]; ?>"><span
                class="ao_explain">px</span>
            </div>



            <div class="pro">

              <table>
                <tr>
                  <td>Adsense Publisher ID:</td>
                  <td><input name="adsensexplosion[id][<?php echo $i; ?>]"
                    type="text" value="<?php echo $this->opts['id'][$i]; ?>">
                </tr>
                <tr>
                  <td></td>
                  <td><span class="ao_explain"><?php echo $soloSeDiversoDaImpostazioniGenerali; ?></span></td>
                </tr>



                <tr class="old">
                  <td>Adsense Channel</td>
                  <td><input name="adsensexplosion[channel][<?php echo $i; ?>]"
                    type="text" value="<?php echo $this->opts['channel'][$i]; ?>">
				  </td>
                </tr>
                <tr class="old">
                  <td></td>
                  <td><span class="ao_explain"><?php echo $soloSeDiversoDaImpostazioniGenerali; ?></span></td>
                </tr>



                <tr class="new">
                  <td>Data Ad Slot</td>
                  <td><input name="adsensexplosion[dataslot][<?php echo $i; ?>]"
                    type="text" value="<?php echo $this->opts['dataslot'][$i]; ?>">
				  </td>
                </tr>
                <tr class="new">
                  <td></td>
                  <td><span class="ao_explain"><?php echo $soloSeDiversoDaImpostazioniGenerali; ?></span></td>
                </tr>


              </table>
            </div>
            
            
			  
            <div class="dyn_resp">
                <br />
                <br />
                <br />
            	<div data-dynamic-grid="adsensexplosion[json][<?php echo $i;?>]" data-grid-value='<?php echo(stripslashes($this->opts["json"][$i])); ?>'></div>
<!--
<br />
<span class="ao_explain"><?php echo $usaQuestaFunzioneSuTemiResponsive; ?></span>
-->
            </div>
			  
			  
            
            
            
          </td>
          <td><span class="ao_explain"><?php echo($cliccaQuiPerVedereEsempiBanner); ?></span>
          <br> <?php echo('<div class="normal_ad">');
   foreach($sizes as $key => $size)
   {
      if($size['text']=='responsive') echo('<div class="new">');
      echo('<input type="radio" id="adsensexplosion_sz_' . $size['text'] . '_' . $i . '" name="adsensexplosion[sz][' . $i . ']" value="' . $size['text'] . '" ');
      if($this->opts['sz'][$i] == $size['text']) echo(' checked ');
//      echo(" onchange=\"toggleResponsiveAd(jQuery('#adsensexplosion_sz_responsive_$i').is(':checked'))\" ");
      echo(" onchange=\"toggleResponsiveAd(jQuery('#adsensexplosion_sz_responsive_$i').is(':checked')); toggleDynResponsive(jQuery('#adsensexplosion_sz_dynamic_$i').is(':checked')); \" ");
      echo('> ' . $size['desc'] . '<br>');
      if($size['text']=='responsive') echo('</div>');
   }
   //checkbox per responsive
?>   
   <br><div class="new"><div class="responsive_ad">
   <fieldset>
 <legend>Optional Data Format</legend>
<?php
echo('<input type="checkbox" value="1" name="adsensexplosion[responsive_rectangle][' . $i . ']" ' . $this->opts['is_responsive_rectangle'][$i] . '> Rectangle<br />');
echo('<input type="checkbox" value="1" name="adsensexplosion[responsive_vertical][' . $i . ']" ' . $this->opts['is_responsive_vertical'][$i] . '> Vertical<br />');
echo('<input type="checkbox" value="1" name="adsensexplosion[responsive_horizontal][' . $i . ']" ' . $this->opts['is_responsive_horizontal'][$i] . '> Horizontal<br />');
?>
</fieldset>
   </div></div>
<?php   
   //annunci - insieme di link
   echo('</div>');
   echo('<div class="lb_ad">');
   foreach($lbsizes as $key => $size)
   {
      echo('<input type="radio" name="adsensexplosion[lbsz][' . $i . ']" value="' . $size['text'] . '" ');
      if($this->opts['lbsz'][$i] == $size['text']) echo(' checked ');
      echo('> ' . $size['desc'] . '<br>');
   }
   echo('</div>');
   if($this->opts['col_border'][$i] == "") $this->opts['col_border'][$i] = "336699";
   if($this->opts['col_link'][$i] == "") $this->opts['col_link'][$i] = "0000FF";
   if($this->opts['col_bg'][$i] == "") $this->opts['col_bg'][$i] = "FFFFFF";
   if($this->opts['col_text'][$i] == "") $this->opts['col_text'][$i] = "000000";
   if($this->opts['col_url'][$i] == "") $this->opts['col_url'][$i] = "008000";
?>

          </td>
          <td class="old">Choose Colors:<br> Border: <input class="color"
            id="c_border<?php echo $i; ?>"
            name="adsensexplosion[col_border][<?php echo $i; ?>]" size="6"
            value="<?php echo $this->opts['col_border'][$i]; ?>"><br> Link: <input
            class="color" id="c_link<?php echo $i; ?>"
            name="adsensexplosion[col_link][<?php echo $i; ?>]" size="6"
            value="<?php echo $this->opts['col_link'][$i]; ?>"><br> Backgr.: <input
            class="color" id="c_bg<?php echo $i; ?>"
            name="adsensexplosion[col_bg][<?php echo $i; ?>]" size="6"
            value="<?php echo $this->opts['col_bg'][$i]; ?>"><br>

            <div class="normal_ad">
              Text:<input class="color" id="c_text<?php echo $i; ?>"
                name="adsensexplosion[col_text][<?php echo $i; ?>]" size="6"
                value="<?php echo $this->opts['col_text'][$i]; ?>"><br> URL: <input
                class="color" id="c_url<?php echo $i; ?>"
                name="adsensexplosion[col_url][<?php echo $i; ?>]" size="6"
                value="<?php echo $this->opts['col_url'][$i]; ?>">
            </div>



            <hr>change to Palette<select
            onchange="document.getElementById('c_border<?php echo $i; ?>').value=this.value.substring(0,6);document.getElementById('c_link<?php echo $i; ?>').value=this.value.substring(6,12);document.getElementById('c_bg<?php echo $i; ?>').value=this.value.substring(12,18);document.getElementById('c_text<?php echo $i; ?>').value=this.value.substring(18,24);document.getElementById('c_url<?php echo $i; ?>').value=this.value.substring(24,30); raiseEvent('blur', 'c_border<?php echo $i; ?>');raiseEvent('blur', 'c_link<?php echo $i; ?>');raiseEvent('blur', 'c_bg<?php echo $i; ?>');raiseEvent('blur', 'c_text<?php echo $i; ?>');raiseEvent('blur', 'c_url<?php echo $i; ?>');"
            name="palette[<?php echo $i; ?>]" size="1">

              <option value="FFFFFF0000FFFFFFFF000000008000">Maritim</option>

              <option value="3366990000FFFFFFFF000000008000">Ocean</option>

              <option value="0000000000FFF0F0F0000000008000">Shadow</option>

              <option value="6699CCFFFFFF003366AECCEBAECCEB">Blue</option>

              <option value="000000FFFFFF000000CCCCCC999999">Tint</option>

              <option value="CCCCCC000000CCCCCC333333666666">Graphite</option>

          </select>




            <table class="normal_ad">
              <tr>
                <td><button
                    onclick="document.getElementById('<?php echo $i; ?>.iframe').src = 'https://securepubads.g.doubleclick.net/pagead/ads?client=ca-google-asfe&adtest=on&format=160x70_as&color_border='+document.getElementsByName('adsensexplosion[col_border][<?php echo $i; ?>]')[0].value+'&color_bg='+document.getElementsByName('adsensexplosion[col_bg][<?php echo $i; ?>]')[0].value+'&color_link='+document.getElementsByName('adsensexplosion[col_link][<?php echo $i; ?>]')[0].value+'&color_text='+document.getElementsByName('adsensexplosion[col_text][<?php echo $i; ?>]')[0].value+'&color_url='+document.getElementsByName('adsensexplosion[col_url][<?php echo $i; ?>]')[0].value+'&hl=en&url=www.google.com'; return false;">Refresh
                    Preview</button></td>
                <td><iframe name="0.iframe" id="<?php echo $i; ?>.iframe"
                    height="70" frameborder="0" width="160" scrolling="no"
                    src="https://securepubads.g.doubleclick.net/pagead/ads?client=ca-google-asfe&adtest=on&format=160x70_as&color_border=<?php echo $this->opts['col_border'][$i]; ?>&color_bg=<?php echo $this->opts['col_bg'][$i]; ?>&color_link=<?php echo $this->opts['col_link'][$i]; ?>&color_text=<?php echo $this->opts['col_text'][$i]; ?>&color_url=<?php echo $this->opts['col_url'][$i]; ?>&hl=en&url=www.google.com"></iframe>
                </td>
              </tr>
            </table>


            <table class="lb_ad">
              <tr>
                <td><button
                    onclick="document.getElementById('lb<?php echo $i; ?>.iframe').src = 'http://googleads.g.doubleclick.net/pagead/ads?client=ca-google-asfe&format=200x90_0ads_al&output=html&h=90&w=200&lmt=1270531704&channel=123456789&adtest=on&ea=0&color_bg='+document.getElementsByName('adsensexplosion[col_bg][<?php echo $i; ?>]')[0].value+'&color_border='+document.getElementsByName('adsensexplosion[col_border][<?php echo $i; ?>]')[0].value+'&color_link='+document.getElementsByName('adsensexplosion[col_link][<?php echo $i; ?>]')[0].value; return false;">

                    Refresh Preview</button></td>
                <td><iframe name="lb0.iframe" id="lb<?php echo $i; ?>.iframe"
                    width="200" scrolling="no" height="90" frameborder="0"
                    allowtransparency="true" hspace="0" vspace="0"
                    marginheight="0" marginwidth="0"
                    src="http://googleads.g.doubleclick.net/pagead/ads?client=ca-google-asfe&format=200x90_0ads_al&output=html&h=90&w=200&lmt=1270531704&channel=123456789&adtest=on&ea=0&color_bg=<?php echo($this->opts[col_bg][$i]); ?>&color_border=<?php echo($this->opts[col_border][$i]); ?>&color_link=<?php echo($this->opts[col_link][$i]); ?>"></iframe>
                </td>
              </tr>
            </table>
          </td>
        </tr>




      </table>

    </div>



<?php
}
?>
</div>


















































  <div
    style="background-color: #F0E878; padding: 10px; margin-top: 20px; -moz-border-radius: 3px; -webkit-border-radius: 3px; -khtml-border-radius: 3px; border-radius: 3px;">

    <h2><?php echo $doveMostrareGliAnnunci;?></h2>
    <p><?php echo $doveMostrareGliAnnunciDescr;?>
    </p>
    <br> <br>

    <h3><?php echo $postSingoliPagStatiche;?></h3>
    <p><?php echo $postSingoliPagStaticheDescr;?></p>

    <table>
      <tr>
        <th>Ad Nr.</th>
        <th>Ad Type</th>
        <th>Position</th>
        <th>Aligment</th>
      </tr>

<?php

for($i = 1; $i <= 5; $i++)
{
?>

      <tr>
        <td><?php echo $i; ?></td>

        <td><select name="adsensexplosion[single][<?php echo $i; ?>]" size="1">

            <option value="0"
<?php if($this->opts['single'][$i] == "0") echo(" selected"); echo $nascondi; ?></option>
            <option value="1"
<?php if($this->opts['single'][$i] == "1") echo(" selected"); ?>>1</option>
            <option value="2"
<?php if($this->opts['single'][$i] == "2") echo(" selected"); ?>>2</option>
            <option value="3"
<?php if($this->opts['single'][$i] == "3") echo(" selected"); ?>>3</option>
            <option value="4"
<?php if($this->opts['single'][$i] == "4") echo(" selected"); ?>>4</option>
            <option value="5"
<?php if($this->opts['single'][$i] == "5") echo(" selected"); ?>>5</option>
            <option value="6"
<?php if($this->opts['single'][$i] == "6") echo(" selected"); ?>>6</option>
            <option value="7"
<?php if($this->opts['single'][$i] == "7") echo(" selected"); ?>>7</option>
            <option value="8"
<?php if($this->opts['single'][$i] == "8") echo(" selected"); ?>>8</option>

        </select>
        </td>
        <td><select name="adsensexplosion[single_pos][<?php echo $i; ?>]"
          size="1">

<?php ?>>replace &lt;--adsenseopt--&gt; Tag
            </option> ?>

            <option value="Top"
<?php if($this->opts['single_pos'][$i] == "Top") echo(" selected"); echo $alto; ?></option>
            <option value="Middle"
<?php if($this->opts['single_pos'][$i] == "Middle") echo(" selected"); echo $mezzo; ?></option>
            <option value="Bottom"
<?php if($this->opts['single_pos'][$i] == "Bottom") echo(" selected"); echo $basso; ?></option>
            <option value="Random"
<?php if($this->opts['single_pos'][$i] == "Random") echo(" selected"); echo $caso; ?></option>

        </select>
        </td>
        <td><select name="adsensexplosion[single_align][<?php echo $i; ?>]"
          size="1">

            <option value="center"
<?php if($this->opts['single_align'][$i] == "center") echo(" selected"); echo $centro; ?></option>
            <option value="right"
<?php if($this->opts['single_align'][$i] == "right") echo(" selected"); echo $destra; ?></option>
            <option value="left"
<?php if($this->opts['single_align'][$i] == "left") echo(" selected"); echo $sinistra; ?></option>
            <option value="random"
<?php if($this->opts['single_align'][$i] == "random") echo(" selected"); echo $caso; ?></option>

        </select>
        </td>
        <td><?php echo $mostraSoloSeArticoloPiuLungoDi; ?> <input
          name="adsensexplosion[single_long][<?php echo $i; ?>]"
          value="<?php echo $this->opts['single_long'][$i]; ?>" size="3">
<?php echo $caratteri; ?></td>
      </tr>
      <tr>

<?php }?>

    </table>

    <span class="ao_explain"><?php echo $caratteriDescr;?></span>

    <div class="pro">

      <p>
        <input name="adsensexplosion[only_tagged]" type="checkbox" value="1"
<?php echo $this->opts['is_only_tagged'] . $checkAdsenseopt;?>
      </p>



      <p>
        <input type="checkbox" value="1" name="adsensexplosion[omit_page]"
<?php echo $this->opts['is_omit_page'] . $checkOmitPag?>
      </p>


    </div>



    <br> <br>

    <h3><?php echo $postMultipli;?></h3>

    <p><?php echo $nellePagineMostranoMoltiArticoli;?></p>

    <table>
      <tr>
<?php echo $intestazioniColonnePostMultipli;?>
      </tr>



<?php

for($i = 1; $i <= 8; $i++)
{
?>

      <tr>

        <td><?php echo $i; ?></td>

        <td><select name="adsensexplosion[multi][<?php echo $i; ?>]" size="1">

            <option value="0"
<?php if($this->opts['multi'][$i] == "0") echo(" selected"); echo $nascondi; ?></option>

            <option value="1"
<?php if($this->opts['multi'][$i] == "1") echo(" selected"); ?>>1</option>

            <option value="2"
<?php if($this->opts['multi'][$i] == "2") echo(" selected"); ?>>2</option>

            <option value="3"
<?php if($this->opts['multi'][$i] == "3") echo(" selected"); ?>>3</option>

            <option value="4"
<?php if($this->opts['multi'][$i] == "4") echo(" selected"); ?>>4</option>

            <option value="5"
<?php if($this->opts['multi'][$i] == "5") echo(" selected"); ?>>5</option>

            <option value="6"
<?php if($this->opts['multi'][$i] == "6") echo(" selected"); ?>>6</option>

            <option value="7"
<?php if($this->opts['multi'][$i] == "7") echo(" selected"); ?>>7</option>

            <option value="8"
<?php if($this->opts['multi'][$i] == "8") echo(" selected"); ?>>8</option>

        </select>
        </td>



        <td><select name="adsensexplosion[multi_pos][<?php echo $i; ?>]"
          size="1">

            <option value="1"
<?php if($this->opts['multi_pos'][$i] == "1") echo(" selected"); ?>>1st
              Post</option>

            <option value="2"
<?php if($this->opts['multi_pos'][$i] == "2") echo(" selected"); ?>>2nd
              Post</option>

            <option value="3"
<?php if($this->opts['multi_pos'][$i] == "3") echo(" selected"); ?>>3rd
              Post</option>

            <option value="4"
<?php if($this->opts['multi_pos'][$i] == "4") echo(" selected"); ?>>4th
              Post</option>

            <option value="5"
<?php if($this->opts['multi_pos'][$i] == "5") echo(" selected"); ?>>5th
              Post</option>

            <option value="6"
<?php if($this->opts['multi_pos'][$i] == "6") echo(" selected"); ?>>6th
              Post</option>

            <option value="7"
<?php if($this->opts['multi_pos'][$i] == "7") echo(" selected"); ?>>7th
              Post</option>

            <option value="8"
<?php if($this->opts['multi_pos'][$i] == "8") echo(" selected"); ?>>8th
              Post</option>

            <option value="9"
<?php if($this->opts['multi_pos'][$i] == "9") echo(" selected"); ?>>9th
              Post</option>

            <option value="10"
<?php if($this->opts['multi_pos'][$i] == "10") echo(" selected"); ?>>10th
              Post</option>

        </select>
        </td>

        <td><select name="adsensexplosion[multi_align][<?php echo $i; ?>]"
          size="1">

            <option value="center"
<?php if($this->opts['multi_align'][$i] == "center") echo(" selected"); echo $centraleSopraTitolo; ?></option>

            <option value="left"
<?php if($this->opts['multi_align'][$i] == "left") echo(" selected"); echo $sinistraSopraTitolo; ?></option>

            <option value="right"
<?php if($this->opts['multi_align'][$i] == "right") echo(" selected"); echo $destraSopraTitolo; ?></option>

            <option value="cbt"
<?php if($this->opts['multi_align'][$i] == "cbt") echo(" selected"); echo $centraleSottoTitolo; ?></option>

            <option value="lbt"
<?php if($this->opts['multi_align'][$i] == "lbt") echo(" selected"); echo $sinistraSottoTitolo; ?></option>

            <option value="rbt"
<?php if($this->opts['multi_align'][$i] == "rbt") echo(" selected"); echo $destraSottoTitolo; ?></option>

        </select>
        </td>





      </tr>
      <tr>

<?php }?>

    </table>



    <div class="pro">



      <h4><?php echo $nonMostrareAdSuQuestePag;?></h4>

      <table>

        <tr>
          <td><input type="checkbox" value="1"
            name="adsensexplosion[omit_home]"
<?php echo $this->opts['is_omit_home'];?>/>
          </td>
          <td>Home page</td>
          <td class="ao_explain"></td>
        </tr>

        <tr>
          <td><input type="checkbox" value="1"
            name="adsensexplosion[omit_search]"
<?php echo $this->opts['is_omit_search'];?>/>
          </td>
          <td>Searchresult pages</td>
        </tr>

        <tr>
          <td><input type="checkbox" value="1"
            name="adsensexplosion[omit_category]"
<?php echo $this->opts['is_omit_category'];?>/>
          </td>
          <td>Category archives</td>
        </tr>

        <tr>
          <td><input type="checkbox" value="1" name="adsensexplosion[omit_tag]"
<?php echo $this->opts['is_omit_tag'];?>/>
          </td>
          <td>Tag archives</td>
        </tr>

        <tr>
          <td><input type="checkbox" value="1"
            name="adsensexplosion[omit_date]"
<?php echo $this->opts['is_omit_date'];?>/>
          </td>
          <td>Date archives</td>
        </tr>

        <tr>
          <td><input type="checkbox" value="1"
            name="adsensexplosion[omit_author]"
<?php echo $this->opts['is_omit_author'];?>/>
          </td>
          <td>Author archives</td>
        </tr>

      </table>

      <br>
      <h4><?php echo $supportaIlPlugin;?></h4>
      <input type="checkbox" value="1" name="adsensexplosion[smd]" <?php echo $this->opts['is_smd'];?> /> 
      <?php echo $dono;?><br />
    </div>
  </div>























  <div
    style="background-color: #B0E878; padding: 10px; margin-top: 20px; -moz-border-radius: 3px; -webkit-border-radius: 3px; -khtml-border-radius: 3px; border-radius: 3px;">

    <h2><?php echo $titoloPrivacy;?></h2>

    <h4>Ban</h4>
    <p><?php echo $spiegazioneBanPrivacy;?></p>
    <p><input type="checkbox" value="1" name="adsensexplosion[policy_protect]"
    <?php echo $this->opts['is_policy_protect'] . $checkPolicyProtect ?>
    </p>

    <br />
    <h4>Cookie</h4>
    <p><?php echo $spiegazioneUtilizzoPrivacy;?></p>

    <h3><?php echo $informativaSullaPrivacy . ' [<a href="#" onclick="toggleInformativaPrivacy(); return false;">' . $dettagli;?></a>]</h3>
    <?php include_once($this->opts['lang'] . '_privacy.php'); ?>
    <div class="container" style="display:none;">
    <p><?php echo adsense_privacy_policy_display();?></p>
    </div>
  </div>












  <div class="submit">
    <input type="submit" name="adsensexplosion_update" id="ae_update" value="Update &raquo;" />
  </div>

  </form>





  <div
    style="background-color: #FFC96B; padding: 10px; margin-top: 20px; -moz-border-radius: 3px; -webkit-border-radius: 3px; -khtml-border-radius: 3px; border-radius: 3px;">
    <p><?php echo $puoInserireAnnunciUsandoWidget;?></p>
  </div>



  <script type="text/javascript">
    function show_active($type) {
      toggleResponsiveAd(jQuery('#adsensexplosion_sz_responsive_'+$type).is(':checked'));      
      toggleDynResponsive(jQuery('#adsensexplosion_sz_dynamic_'+$type).is(':checked'));
      jQuery("div.adtype").hide(0);
      jQuery("div#ad"+$type).show(0);
      toggleAdtype(document.getElementById("adtypeselect"+$type).value);
      if(document.getElementById("usertypebasic").checked){toggleUsertype("");} else {toggleUsertype("pro");};
    }
//    show_active("1");
<?php
echo ('toggleUsertype("'.$this->opts['usertype'].'");');
?>
    toggleNewOld(jQuery('#async').is(':checked'));
  </script>





  </div>
</div>