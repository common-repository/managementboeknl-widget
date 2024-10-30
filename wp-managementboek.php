<?php
/*
Plugin Name: Managementboek.nl widget
Plugin URI: https://www.managementboek.nl/affiliate_informatie
Description: Voeg een widget toe met managementboeken
Author: Dennis Lutz
Version: 1.1.0
Author URI: https://www.managementboek.nl/
*/

  /**
   * echo widget
   *
   * @return string
   */
  function mgtwidget($options) {
    if( $options['num'] == "" ) $options['num'] = 10;
    $code = "
      <script src=\"https://i.mgtbk.nl/widget/affwidget.js\" type=\"text/javascript\"></script>
      <script type=\"text/javascript\">
        //  mogelijke vars
        //  sort = auteur|7d|14d|30d|60d|90d|lm|titel
        //  desc = 0|1
        //  taal = nl|en
        //  rubriek = 'a|b'
        //  trefwoord = 'a,b'
        //  num = X
        //  q = 'zoekoptie'
        //  timer = tijd tussen automatisch
        var options = {
            affiliate:{$options['affiliate']},
            sort:'{$options['sort']}',
            num:{$options['num']},
            taal:'{$options['taal']}',
            desc:{$options['desc']},
            rubriek:'{$options['rubriek']}',
            trefwoord: '{$options['trefwoord']}',
            q:'{$options['q']}',
            timer:{$options['timer']}
        };
        initMgtBoekWidget(options);
      </script>
    ";
    echo $code;
  }

  /**
   * echo the widget
   */
  function widget_mgtboek( $args )
  {
    extract($args);
    $options = get_option("widget_mgtboek");
    //when there are no options set, use these values
    if (!is_array( $options )) {
      $options = array(
        'titel' => 'Managementboek.nl Boeken',
        'affiliate'=>0,
        'sort'=>'',
        'num'=>10,
        'taal'=>'nl',
        'desc'=>0,
        'rubriek'=>'',
        'trefwoord'=>'',
        'q'=>'',
        'timer'=>5000
      );
    }
    echo $before_widget;
      echo $before_title . $options['titel'] . $after_title;
      echo mgtwidget( $options );
    echo $after_widget;
  }
  /**
   * add widget and widgetcontrol to wordpress-admin
   */
  function mgtboek_init() {
    register_sidebar_widget(__('Managementboek boeken'), 'widget_mgtboek');
    register_widget_control(   'Managementboek boeken', 'mgtboek_control', 200, 200 );
  }

  /**
   * echo the widgetControl in wordpress-admin
   */
  function mgtboek_control()
  {
    $options = get_option("widget_mgtboek");
    if (!is_array( $options  ))
    {
      //no options found for widget_mgtboek, use these defaults in the widgetform
      $options = array(
        'titel' => 'Managementboek.nl boeken',
        'affiliate'=>0,
        'sort'=>'',
        'num'=>10,
        'taal'=>'nl',
        'desc'=>0,
        'rubriek'=>'',
        'trefwoord'=>'',
        'q'=>'',
        'timer'=>5000
      );
    }
    //Set the variables if form is submitted
    if ($_POST['mgtboekTitel-Submit']) {
      $options['titel']     = htmlspecialchars($_POST['mgtboekTitel']);
      $options['affiliate'] = (integer)$_POST['mgtboekAffiliate'];
      $options['sort']      = $_POST['mgtboekSort'];
      $options['num']       = (integer)$_POST['mgtboekNum'];
      $options['taal']      = $_POST['mgtboekTaal'];
      $options['desc']      = $_POST['mgtboekDesc'];
      $options['rubriek']   = $_POST['mgtboekRubriek'];
      $options['trefwoord'] = $_POST['mgtboekTrefwoord'];
      $options['q']         = $_POST['mgtboekZoek'];
      $options['timer']     = $_POST['mgtboekTimer'];
      update_option("widget_mgtboek", $options);
    }
    //write the formfields
  ?>
  <div class="wrap">
    <input type="hidden" id="mgtboekTitel-Submit" name="mgtboekTitel-Submit" value="1" />
    <table>
    <tr><td><label for="mgtboekTitel">Titel</label></td><td><input type="text" id="mgtboekTitel" name="mgtboekTitel" value="<?=$options['titel']?>" /></td></tr>
    <tr><td><label for="mgtboekAffiliate">Affiliate</label></td><td><input type="text" id="mgtboekAffiliate" name="mgtboekAffiliate" value="<?=$options['affiliate']?>" /></td></tr>
    <!--
    <tr><td><label for="mgtboekSort">Sort</label></td><td><input type="text" id="mgtboekSort" name="mgtboekSort" value="<?=$options['sort']?>" /></td></tr>
    -->
    <tr><td><label for="mgtboekNum">Aantal</label></td><td><input type="text" id="mgtboekNum" name="mgtboekNum" value="<?=$options['num']?>" /></td></tr>
    <tr>
      <td><label for="mgtboekaal">Taal</label></td>
      <td>
        <select id="mgtboekTaal" name="mgtboekTaal">
         <option value="nl"<?php if($options['taal'] == "nl") echo " selected=\"selected\""; ?>>Nederlands</option>
         <option value="en"<?php if($options['taal'] == "en") echo " selected=\"selected\""; ?>>Engels</option>
       </select>
      </td>
    </tr>
    <tr>
      <td><label for="mgtboekDesc">Volgorde</label></td>
      <td>
        <select id="mgtboekDesc" name="mgtboekDesc">
         <option value="0"<?php if($options['desc'] == "0") echo " selected=\"selected\""; ?>>Oplopend</option>
         <option value="1"<?php if($options['desc'] == "1") echo " selected=\"selected\""; ?>>Aflopend</option>
       </select>
      </td>
    </tr>
    <!--
    <tr>
      <td><label for="mgtboekRubriek">Rubriek</label></td>
      <td>
        <select id="mgtboekRubriek" name="mgtboekRubriek">
         <option value=""<?php if($options['rubriek'] == "") echo " selected=\"selected\""; ?>>- alle rubrieken -</option>
       </select>
      </td>
    </tr>
    -->
    <tr><td><label for="mgtboekTrefwoord">Trefwoord</label></td><td><input type="text" id="mgtboekTrefwoord" name="mgtboekTrefwoord" value="<?=$options['trefwoord']?>" /></td></tr>
    <tr><td><label for="mgtboekZoek">Zoek</label></td><td><input type="text" id="mgtboekZoek" name="mgtboekZoek" value="<?=$options['q']?>" /></td></tr>
    <tr><td><label for="mgtboekTimer">Refresh(ms)</label></td><td><input type="text" id="mgtboekTimer" name="mgtboekTimer" value="<?=$options['timer']?>" /></td></tr>
  </table>
  </div>
  <?php
  };

  add_action("plugins_loaded", "mgtboek_init");


