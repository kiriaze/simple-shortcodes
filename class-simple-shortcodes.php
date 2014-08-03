<?php
/**
* Simple Shortcodes.
*
* @package   Simple_Shortcodes_Class
* @author    Constantine Kiriaze, hello@kiriaze.com
* @license   GPL-2.0+
* @link      http://getsimple.io
* @copyright 2013 Constantine Kiriaze
*
*
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Simple_Shortcodes_Class' ) ) :

class Simple_Shortcodes_Class {

    function __construct() {

        //  Plugin Activation
        register_activation_hook( __FILE__, array( &$this, 'plugin_activation' ) );

        //  Translation
        load_plugin_textdomain( 'simple', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        //  Shortcodes
        add_action( 'init', array( &$this, 'shortcodes_init' ) );

        //  Load shortcode scripts
        add_action( 'wp_enqueue_scripts', array( &$this, 'load_scripts' ), 10 );

    }

    function shortcodes_init() {

        wp_localize_script( 'jquery', 'SimpleShortcodes', array('plugin_folder' => plugin_dir_url(__FILE__)) );

        function simple_register_buttons( $buttons ) {
            array_push( $buttons, "|", 'simple_button' );
            return $buttons;
        }

        function simple_add_buttons( $plugin_array ) {
            $plugin_array['SimpleShortcodes'] = plugins_url( 'tinymce/tinymce.js', __FILE__ );
            return $plugin_array;
        }

        add_filter( "mce_external_plugins", "simple_add_buttons" );
        add_filter( 'mce_buttons', 'simple_register_buttons' );

        // set global
        if ( !function_exists('HexToRGB') ):
            function HexToRGB($hex, $alpha = null) {
                $hex = preg_replace('/#/i', '', $hex);
                $color = array();
                if(strlen($hex) == 3) {
                    $color['r'] = hexdec(substr($hex, 0, 1) . $r);
                    $color['g'] = hexdec(substr($hex, 1, 1) . $g);
                    $color['b'] = hexdec(substr($hex, 2, 1) . $b);
                    $color['a'] = $alpha ? $alpha : '1';
                    }
                else if(strlen($hex) == 6) {
                    $color['r'] = hexdec(substr($hex, 0, 2));
                    $color['g'] = hexdec(substr($hex, 2, 2));
                    $color['b'] = hexdec(substr($hex, 4, 2));
                    $color['a'] = $alpha ? $alpha : '1';
                }
                return $color;
            }
        endif;


        add_filter('widget_text', 'shortcode_unautop', 10);
        add_filter('widget_text', 'do_shortcode', 10);


        ////////////////////////////////////
        // B U T T O N
        ////////////////////////////////////
        if ( !function_exists('simple_button_shortcode') ) {
            function simple_button_shortcode( $atts, $content = null ) {

                extract( shortcode_atts( array(
                    'url'       =>  '',
                    'target'    =>  '',
                    'modal_id'  =>  '',
                    'color'     =>  '',
                    'size'      =>  '',
                    'type'      =>  '',
                    'icon'      =>  '',
                    'class'     =>  ''
                ), $atts ) );

                $html = '';

                // $atts
                $color      = $color ? $color : '';
                $size       = $size ? $size : '';
                $type       = $type ? $type : ''; // types: square, warning, info, success, error. Defaults to round.
                $id         = rand(1, 10000);


                // target
                if ( $target == 'blank' || $target == '_blank' || $target == 'new' ) {
                    $target = ' target="_blank"';
                } else {
                    $target = '';
                }

                // icons
                $icon_type = '';
                $padding = '';
                if ($icon != "") {
                    if ( $content ) $padding = ' style="padding-right: 10px;" ';
                    $icon_type .= ( $icon ) ? '<i class="' . $icon . '" ' . $padding . ' ></i>' : '' ;

                    if ( $class == 'flip' ) {
                        $icon_type .= ( $icon ) ? '<i class="' . $icon . '" ' . $padding . ' ></i>' : '' ;
                    }
                }

                // button
                if ( !$content ) $class .= ' no-content';

                // modals
                $modal = '';
                if( $modal_id ) {
                    $modal = 'data-modal="'. $modal_id .'"';
                }

                // urls
                if( $url ) {
                    $url = $url;
                } else{
                    $url = 'javascript:;';
                }

                if ( $color ) {
                    $html .= "<style>.btn.color-$id{
                                background-color: $color;
                            }</style>";
                }

                $html .= '<a href="'. $url .'" class="btn '. $class . ' ' . $size . ' color-'.$id.' '.$type.'" '. $modal .' '.$target.'>' . $icon_type . do_shortcode( $content ) . '</a>';
                // [button url="http://" class="btn " color="black" size="small" type="square info" icon="fa fa-coffee" target="new" modal_id="1" ] Link [/button]

                return $html;
            }
            add_shortcode('button', 'simple_button_shortcode');
        }




        ////////////////////////////////////
        // I C O N S
        ////////////////////////////////////
        if ( !function_exists('simple_icon_shortcodes') ) {
            function simple_icon_shortcodes( $atts ){
                extract( shortcode_atts( array(
                    'class'     =>  '',
                    'size'      =>  '',
                    'color'     =>  '',
                    'tooltip'   =>  false,
                    'position'  =>  'top'
                ), $atts ) );

                if($size) $font_size = 'font-size: '. $size .';';
                if($color) $font_color = 'color: '. $color .';';

                $simple_icon = '';

                if ( $size || $color ) :
                    $simple_icon = '<style>
                        .'.$class.'.inline-sc:before {
                            '.$font_size.'
                            '.$font_color.'
                        }
                        </style>';
                endif;

                if( $tooltip )
                    $tooltip = ' data-toggle="tooltip" data-placement="'.$position.'" title="Coming Soon"';

                $simple_icon .= '<i class="' . $class . ' inline-sc"'. $tooltip .'></i>';

                return $simple_icon;

            }
            add_shortcode( 'icon', 'simple_icon_shortcodes' );
        }




        ////////////////////////////////////
        // C A L L O U T S
        ////////////////////////////////////
        if ( !function_exists('simple_callouts') ) {

            function simple_callouts($atts, $content=null ) {
                extract(shortcode_atts(array(
                    'title'     => '',
                    'icon'      => '',
                    'size'      => '',
                    'bg'        => '',
                    'color'     => '',
                    'stacked'   => '',
                    'align'     => '',
                    'type'      => ''
                ), $atts));

                if($icon != "") $icon = "<i class='$icon'></i>";
                if($stacked != "") $stacked = 'stacked';
                if($align != "") $align = "style='text-align: $align'";
                if($bg != "") $bg = "background: $bg;";
                $class = $bg != "" ? " has-bg " : '';
                if($color != "") $color = "color: $color;";
                if($size != "") $size = "font-size: $size;";

                $type = $type ? $type : '';

                // add blockquotes to the content
                $output  = '<div class="callout" '.$align.'>';
                if($icon != "") :
                    $output .= '<span class="callout-icon sbg '. $stacked . $type . $class .'" style="'. $bg . $color . $size .'">'.$icon.'</span>';
                endif;
                $output .= '<div class="callout-content">';
                $output .= '<h5 class="callout-content-title">'.$title."</h5>";
                $output .= wpautop( $content );
                $output .= '</div></div>';

                return $output;
            }
            add_shortcode('callout', 'simple_callouts');

        }




        ////////////////////////////////////
        // M A G N I F I C  - P O P U P S
        ////////////////////////////////////
        if ( !function_exists('simple_mfp') ) {
            function simple_mfp( $atts, $content = null ) {
                extract( shortcode_atts( array(
                    'link_text'     => null,
                    'type'          => null,
                    'url'           => null,
                    'effect'        => 'mfp-fade-in-up',
                ), $atts ) );

                $id = rand(1, 10000);

                if ($type != 'image') {
                    $output ='
                        <ul class="mfp-inline">
                            <li><a class="btn" href="#'.$id.'" data-effect="'.$effect.'">'.$link_text.'</a></li>
                        </ul>
                        <div id="'.$id.'" class="white-popup mfp-with-anim mfp-hide">'. do_shortcode( $content ) .'</div>';
                } else {
                    if ( !$link_text ) {
                        $output = '
                            <ul class="mfp-image">
                                <li><a href="'.$url.'" data-effect="'.$effect.'"><img src="'. $url .'" /></a></li>
                            </ul>
                        ';
                    } else {
                        $output = '
                            <ul class="mfp-image">
                                <li><a class="btn" href="'.$url.'" data-effect="'.$effect.'">'.$link_text.'</a></li>
                            </ul>
                        ';
                    }
                }

                return $output;
            }
            add_shortcode('lightbox', 'simple_mfp');
        }




        ////////////////////////////////////
        // T O G G L E S
        ////////////////////////////////////
        if ( !function_exists('simple_toggle') ) {
            function simple_toggle( $atts, $content = null ) {
                extract(shortcode_atts(array(
                    'title'      => 'Title goes here',
                    'state'      => 'closed'
                ), $atts));

                return "<div data-id='".$state."' class=\"simple-toggle\"><span class=\"simple-toggle-title\">". $title ."</span><div class=\"simple-toggle-inner\"><div class=\"target\">". do_shortcode( $content ) ."</div></div></div>";
            }
            add_shortcode('toggle', 'simple_toggle');
        }


        ////////////////////////////////////
        // A C C O R D I O N S
        ////////////////////////////////////
        if ( !function_exists('simple_accordion') ) {
            function simple_accordion( $atts, $content = null ){
                extract( shortcode_atts( array(
                    ''  =>  ''
                ), $atts ) );

                $html .= '<div class="accordion">';
                $html .= do_shortcode( $content);
                $html .= '</div>';

                return $html;

            }
            add_shortcode( 'accordion', 'simple_accordion' );
        }

        if ( !function_exists('simple_accordion_panel') ) {
            function simple_accordion_panel( $atts, $content = null ) {
                extract(shortcode_atts(array(
                    'title'      => 'Title goes here',
                    'state'      => 'closed'
                ), $atts));

                return "<div data-id='".$state."' class=\"simple-accordion\"><span class=\"simple-accordion-title\">". $title ."</span><div class=\"simple-accordion-inner\"><div class=\"target\">". do_shortcode( $content ) ."</div></div></div>";
            }
            add_shortcode('panel', 'simple_accordion_panel');
        }


        ////////////////////////////////////
        // A L E R T S
        ////////////////////////////////////
        if ( !function_exists('simple_alerts') ) {
            function simple_alerts( $atts, $content = null ) {
                extract( shortcode_atts( array(
                    'class'     =>  'info'
                ), $atts ) );

                $simple_alert = '<div class="alert alert-'. $class .'">' . do_shortcode( $content ) . '<a class="close" data-dismiss="alert" href="javascript:;">&times;</a></div>';

                return $simple_alert;
            }
            add_shortcode('alert', 'simple_alerts');
        }


        ////////////////////////////////////
        // P R E T T Y P R I N T
        ////////////////////////////////////
        if ( !function_exists('simple_pre') ) {
            function simple_pre ( $atts, $content = null ) {

                extract( shortcode_atts( array(
                    ''  =>  ''
                ), $atts ) );

                return '<pre class="prettyprint">' . do_shortcode( $content ) . '</pre>';
            }
            add_shortcode( 'pre', 'simple_pre' );
        }


        ////////////////////////////////////
        // G R I D
        ////////////////////////////////////
        if ( !function_exists('simple_grid') ) {
            function simple_grid( $atts, $content = null ) {

                extract( shortcode_atts( array(
                    ''  =>  ''
                ), $atts ) );

                $output = '<div data-layout="grid">' . do_shortcode( $content ) . '</div>';

                return apply_filters( 'simple_grid', $output );

            }
            add_shortcode('grid', 'simple_grid');
            //  [grid]
            //  [column span="1"]Some content[/column]
            //  [/grid]
        }


        ////////////////////////////////////
        // C O L U M N S
        ////////////////////////////////////
        if ( !function_exists('simple_columns') ) {
            function simple_columns( $atts, $content = null ) {

                extract( shortcode_atts( array(
                    'span'      => '1',
                    'center'    => false
                ), $atts ) );

                $center = $center ? ' class="center"' : false;

                $output = '<div data-columns="'. $span .'"'.$center.'>' . do_shortcode( $content ) . '</div>';

                return apply_filters( 'simple_columns', $output );

            }
            add_shortcode('column', 'simple_columns');
            //  [column span="1"]Some content[/column]
            //  [column span="2"]Some content[/column]
            //  [column span="2"]Some content[/column]
            //  [column span=".5"]Some content[/column]
            //  [column span=".5"]Some content[/column]
        }


        ////////////////////////////////////
        // T A B S
        ////////////////////////////////////
        if ( !function_exists('simple_tabs') ) {
            function simple_tabs( $atts, $content = null ) {

                extract( shortcode_atts( array(
                    '' =>  ''
                ), $atts ) );

                preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

                $tab_titles = array();
                if( isset($matches[1]) ){
                    $tab_titles = $matches[1];
                }

                $output = '';

                if( count($tab_titles) ){
                    $output .= '<div data-type="tabs">';
                    $count = 0;
                    foreach( $tab_titles as $tab ){
                        $output .= '<a href="#" data-tab="'. sanitize_title( $tab[0] ) .'" class="tab">' . $tab[0] . '</a>';
                        $count++;
                    }

                    $output .= do_shortcode( $content);
                    $output .= '</div>';
                } else {
                    $output .= do_shortcode( $content );
                }

                return $output;
            }
            add_shortcode( 'tabs', 'simple_tabs' );
        }

        if ( !function_exists('simple_tab') ) {

            function simple_tab( $atts, $content = null ) {

                extract( shortcode_atts( array(
                    'title' =>  'Tab'
                ), $atts ) );

                return '<div data-tab-content="'. sanitize_title( $title ) .'">'. apply_filters( 'the_content', do_shortcode( $content ) ) .'</div>';
            }
            add_shortcode( 'tab', 'simple_tab' );
        }


        ////////////////////////////////////
        // V I D E O - P L A Y E R
        ////////////////////////////////////
        if ( !function_exists('simple_video_shortcode') ) {
            function simple_video_shortcode( $atts ) {
                extract( shortcode_atts( array(
                    'src'   =>  '',
                    'mp4'   =>  ''  // default wp behavior
                ), $atts ) );

                $mp4 = ($mp4) ? $mp4 : $src;
                $src = $mp4;

                $src = substr($src, 0, -4);

                $video = '<figure class="aspect">
                            <video data-media-src="'.$src.'.{mp4, ogv, webm}" controls></video>
                        </figure>';

                return $video;
            }
            add_shortcode('video', 'simple_video_shortcode');
        }


        ////////////////////////////////////
        // A U D I O - P L A Y E R
        ////////////////////////////////////
        if ( !function_exists('simple_audio_shortcode') ) {
            function simple_audio_shortcode( $atts ) {
                extract( shortcode_atts( array(
                    'src'   =>  '',
                    'mp4'   =>  ''  // default wp behavior
                ), $atts ) );

                $mp4 = ($mp4) ? $mp4 : $src;
                $src = $mp4;

                $src = substr($src, 0, -4);

                $audio = '<audio data-media-src="'.$src.'.{mp4, ogg, mp3}" controls></audio>';

                return $audio;
            }
            add_shortcode('audio', 'simple_audio_shortcode');
        }



        ////////////////////////////////////
        // M A P
        ////////////////////////////////////
        if ( !function_exists('simple_shortcodes_map') ) {

            global $google_map_script_code;

            function simple_shortcodes_map($atts) {

                global $google_map_script_code;

                extract( shortcode_atts( array(
                    'latitude'          => '',
                    'longitude'         => '',
                    'width'             => '100%',
                    'height'            => '400px',
                    'zoom'              => '16',
                    'infowindow_text'   => ''
                ), $atts ) );

                $google_map_code = "";

                if( strpos($height, 'px') || strpos($height, '%') ) {

                } else {
                    $height = $height.'px';
                }
                if( strpos($width, 'px') || strpos($width, '%') ) {

                } else {
                    $width = $width.'px';
                }

                $infowindow_text = '<p>'.$infowindow_text.'</p>';

                $content_map_id = "content_map_".rand();

                $google_map_code .= '<div id="'.$content_map_id.'" class="simple-shortcode-map" style="width:'.$width.';height:'.$height.';"></div>';

                $google_map_script_code .= "
                <script src='https://maps.googleapis.com/maps/api/js?sensor=false'></script>
                <script type='text/javascript'>
                    (function($){
                        var myLatlng = new google.maps.LatLng(".$latitude.",".$longitude.");
                        var mapOptions = {
                            zoom: ".$zoom.",
                            center: myLatlng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        }

                        var map = new google.maps.Map(document.getElementById('".$content_map_id."'), mapOptions);

                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: map,
                            title: ''
                        });";

                        if($infowindow_text) {

                            $google_map_script_code .= "
                            var contentString = '".$infowindow_text."';

                            var infowindow = new google.maps.InfoWindow({
                                content: contentString
                            });

                            google.maps.event.addListener(marker, 'click', function() {
                              infowindow.open(map,marker);
                            });";
                        }

                    $google_map_script_code .= "
                    })(jQuery)
                </script>";

                return $google_map_code;

            }
            add_shortcode('map', 'simple_shortcodes_map');

            /* Add Google Map Code to Footer */
            function add_google_map_code() {
                global $google_map_script_code;
                if( isset($google_map_script_code) ) {
                    if($google_map_script_code) {
                        echo $google_map_script_code;
                    }
                }

            }
            add_action('wp_footer', 'add_google_map_code', 100);
        }


        ////////////////////////////////////
        // L I S T S
        ////////////////////////////////////
        if ( !function_exists('simple_lists') ) {
            function simple_lists( $atts, $content = null ){
                extract( shortcode_atts( array(
                    'type'     =>  ''
                ), $atts ) );

                $list = '';
                $list .= "<div class='shortcode-list $type'>";
                $list .= do_shortcode( $content );
                $list .= "</div>";

                return $list;

            }
            add_shortcode( 'list', 'simple_lists' );
        }


        ////////////////////////////////////
        // C L I E N T S
        ////////////////////////////////////
        if ( !function_exists('simple_client_list') ) {
            function simple_client_list( $atts, $content = null ){
                extract( shortcode_atts( array(
                    'type'      =>  '', // list, grid, slider
                    'class'     =>  '', // specific for styling/js
                ), $atts ) );

                $list = '';
                $list .= "<div class='client-list $type $class'>";
                $list .= do_shortcode( $content );
                $list .= "</div>";

                return $list;

            }
            add_shortcode( 'clients', 'simple_client_list' );
        }


        ////////////////////////////////////
        // S T A T S
        ////////////////////////////////////
        if ( !function_exists('simple_stats') ) {
            function simple_stats( $atts, $content = null ){
                extract( shortcode_atts( array(
                    ''     =>  '',
                ), $atts ) );

                $html = "";
                $html .= "<div class='stats'>";
                $html .= do_shortcode( $content );
                $html .= "</div>";

                return $html;

            }
            add_shortcode( 'stats', 'simple_stats' );
        }

        if ( !function_exists('simple_stat') ) {
            function simple_stat( $atts, $content = null ){
                extract( shortcode_atts( array(
                    'total'     =>  ''

                ), $atts ) );

                $html = "";
                $html .= "<div class='stat-block'>";
                $html .= "<span class='scrollstat' data-total='$total'>$total</span>";
                $html .= "<div class='stat-details'>".do_shortcode( $content )."</div>";
                $html .= "</div>";

                return $html;

            }
            add_shortcode( 'stat', 'simple_stat' );
        }


        ////////////////////////////////////
        // P A C K A G E S
        ////////////////////////////////////
        if ( !function_exists('simple_packages') ) {
            function simple_packages( $atts, $content = null ){
                extract( shortcode_atts( array(
                    ''  =>  ''
                ), $atts ) );

                $html = '';

                preg_match_all( '/package title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

                $packages = array();
                if( isset($matches[1]) ){
                    $packages = $matches[1];
                }

                if( count($packages) ){

                    $count = 0;
                    foreach( $packages as $package ){
                        $count++;
                    }
                    $width = 100/$count;
                    $html .= '<style>
                                .pricing-tables .pricing-tables-inner .package {
                                    width: '.$width.'%;
                                }
                            </style>';

                    $html .= '<div class="pricing-tables">';
                    $html .= '<div class="pricing-tables-inner">';
                    $html .= do_shortcode( $content);
                    $html .= '</div>';
                    $html .= '</div>';

                } else {
                    $html .= do_shortcode( $content );
                }

                return $html;

            }
            add_shortcode( 'packages', 'simple_packages' );
        }

        if ( !function_exists('simple_package') ) {

            function simple_package( $atts, $content = null ) {

                extract( shortcode_atts( array(
                    'title'     =>  '',
                    'featured'  =>  false,
                    'price'     =>  '',
                    'time'      =>  'month',
                    'signup'    =>  ''
                ), $atts ) );

                $featured = ($featured == 'true') ? 'type-featured' : '';
                $signup = $signup ? $signup : 'Sign Up';

                $html = '';
                $html .= "<div class='$featured package'>";
                $html .= "<div class='package-inner'>";
                $html .= "<div class='package-header'>
                            <div class='package-title sbg'>$title</div>
                            <div class='package-price'>$price<small>per $time</small></div>
                        </div>";
                $html .= "<div class='package-features'>";
                $html .= do_shortcode( $content );
                $html .= "</div>";
                $html .= "<div class='package-footer'>
                            <a class='btn' href='javascript:;'>
                                <span>$signup</span>
                            </a>
                        </div>";
                $html .= "</div>";
                $html .= "</div>";

                return $html;
            }
            add_shortcode( 'package', 'simple_package' );
        }


        ////////////////////////////////////
        // S E C T I O N S
        ////////////////////////////////////
        if ( !function_exists('simple_section') ) {

            function simple_section( $atts, $content = null ) {

                extract( shortcode_atts( array(
                    'overlay'   =>  false,
                    'color'     =>  '',
                    'ratio'     =>  '',
                    'bg'        =>  '',
                    'bg-position'=> '',
                    'cover'     =>  'true',
                    'stellar'   =>  false,
                    'padding'   =>  '',
                    'class'     =>  '' // container, content
                ), $atts ) );

                $overlay    = $overlay ? ' colored-overlay shadow ' : false;
                $context    = !$content ? ' no-content ' : '';
                $bg         = $bg ? $bg : '';
                $position   = $bg ? $position : '';
                $cover      = ($cover != 'true') ? 'background-size: auto;' : 'background-size: cover; background-repeat: no-repeat; background-position: center;';
                $stellar    = $stellar ? ' stellar ' : false;
                $ratio      = $ratio ? $ratio: '0.5';
                $ratio      = $stellar ? "data-stellar-background-ratio='$ratio'" : '';

                $padding = $padding ? $padding : '';
                if ($padding == "top") $padding = " padding-top ";
                if ($padding == "none") $padding = " padding-none ";

                $color      = $color ? $color : '';
                $id         = rand(1, 10000);

                $newColor = HexToRGB( $color, .7 ); // has to be hex
                $newColor = 'rgba(' . implode(",", $newColor) .');';

                $html = '';
                $html .= '<section id="colored-'.$id.'" class="full-width-section ' . $class . $stellar . $overlay . $context . $padding . '" '.$ratio.' style="background-image: url('. $bg .'); background-position:'.$position.'; ' . $newColor . $cover . '">';
                if ( $color ) {
                    $html .= "<style>.colored-overlay#colored-$id:before{
                                background-color: $newColor;
                            }</style>";
                }
                $html .= do_shortcode( $content );
                $html .= '</section>';

                return $html;
            }
            add_shortcode('section', 'simple_section');
        }

        ////////////////////////////////////
        // H R
        ////////////////////////////////////
        if ( !function_exists('simple_hr') ) {

            function simple_hr( $atts ) {

                extract(shortcode_atts(array(
                    '' => '',
                ), $atts));

                $html = '<br style="display: inline-block; width: 100%; content: \'\';" /><hr />'; // prepending ib fix inline issues

                return $html;
            }
            add_shortcode('hr', 'simple_hr');
        }


        ////////////////////////////////////
        // B R
        ////////////////////////////////////
        if ( !function_exists('simple_br') ) {

            function simple_br( $atts ) {

                extract(shortcode_atts(array(
                    '' => '',
                ), $atts));

                $html = '<br />';

                return $html;
            }
            add_shortcode('br', 'simple_br');
        }


        ////////////////////////////////////
        // S T A N D A R D
        ////////////////////////////////////
        if ( !function_exists('bloginfo_shortcode') ) {
            // bloginfo url
            function bloginfo_shortcode( $atts ) {
                extract(shortcode_atts(array(
                    'key' => '',
                ), $atts));
                return get_bloginfo($key);
            }
            add_shortcode('bloginfo', 'bloginfo_shortcode');
        }

        if ( !function_exists('raw_shortcode') ) {
            // raw
            function raw_shortcode( $atts, $content ) {
                extract(shortcode_atts(array(
                    '' => '',
                ), $atts));
                return do_shortcode( $content );
            }
            add_shortcode('raw', 'raw_shortcode');
        }




        ////////////////////////////////////
        // P O S T S
        ////////////////////////////////////
        if ( !function_exists('simple_post_type') ) {
            //  Simple Post Types
            function simple_post_type( $atts ) {
                extract(shortcode_atts(array(
                    'type'      => 'post', // events, testimonials, etc, and formats as well
                    'format'    => '', // gallery, audio, video, etc. Note: standard doesnt work, setup array if usecase
                    'filter'    => 'recent', // popular, featured, related & recent
                    'cat'       => '', // category
                    'count'     => -1,
                    'divider'   => 4,
                    'slider'    => 'true', // defaults to true: carousel
                    'class'     => '', // ul class
                    'meta'      => '', // author, tags, etc
                    'metalocation'  =>  'footer', // header, footer
                    'titletag'  => 'h4'
                ), $atts));

                global $post;

                $metaArr = explode(', ', $meta);
                $metaVal = array_fill_keys(array_keys(array_flip($metaArr)), 'true');

                $post_meta = apply_filters('simple_post_meta', array(
                    'author'        => isset($metaVal['author']),
                    'date'          => isset($metaVal['date']),
                    'tags'          => isset($metaVal['tags']),
                    'categories'    => isset($metaVal['categories']),
                    'wordcount'     => isset($metaVal['wordcount']),
                    'reading_time'  => isset($metaVal['readingtime']),
                    'views'         => isset($metaVal['views']),
                    'comments'      => isset($metaVal['comments']),
                    'icons'         => isset($metaVal['icons'])
                ));

                $count = ($count) ? $count : -1;
                $divider = ($divider) ? $divider : 4;

                $tag        = ($filter == 'featured') ? 'featured' : '';
                $order      = ($filter == 'recent') ? 'DESC' : '';
                $popular    = ($filter == 'popular') ? '' : ''; // hook into simple_likes
                $related    = ($filter == 'related') ? true : false;

                if ( $related ) {
                    $type = get_post_type();
                }

                $sliderClass = ($slider != 'false') ? ' carousel' : '';

                $tax_query = array('relation' => 'AND');

                if ( $format ) {
                    $format = 'post-format-' . $format;
                    $tax_query[] =  array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => array( $format )
                    );
                }

                $args = array(
                    'order'             => $order,
                    'post_type'         => $type,
                    'posts_per_page'    => $count,
                    'tag'               => $tag,
                    'category_name'     => $cat,
                    'tax_query'         => $tax_query
                );

                if ( $related ) {

                    $taxonomy = 'work_types';

                    $tags       = wp_get_post_tags($post->ID);
                    $categories = get_the_category($post->ID);
                    $terms      = wp_get_post_terms($post->ID, $taxonomy);

                    if ($tags) {
                        $tag_ids = array();
                        foreach( $tags as $individual_tag ) $tag_ids[] = $individual_tag->term_id;
                        $relatedArgs = array(
                            'tag__in'       => $tag_ids,
                            'post__not_in'  => array($post->ID),
                        );
                    }

                    if ($categories) {
                        $category_ids = array();
                        foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
                        $relatedArgs = array(
                            'category__in' => $category_ids,
                            'post__not_in' => array($post->ID),
                        );
                    }

                    if ($terms) {
                        $term_ids = array();
                        foreach( $terms as $individual_term ) $term_ids[] = $individual_term->term_id;
                        $relatedArgs = array(
                            'tax_query' => array(
                                array(
                                    'taxonomy'  => $taxonomy,
                                    'field'     => 'id',
                                    'terms'     => $term_ids,
                                    'operator'  => 'IN'
                                )
                            ),
                            'post__not_in' => array($post->ID)
                        );
                    }

                    $args = array_merge($args, $relatedArgs);
                }

                $posts = new WP_Query($args);

                if ( $posts->have_posts() ) :

                    $html = '';

                    // if sliders disabled, structure grid for posts
                    $grid = ($slider != 'false') ? '' : ' data-layout="grid"';
                    $columns = ($slider != 'false') ? '' : ' data-columns="'.$divider.'"';

                    $html .= '<ul'. $grid .' class="post-type-shortcode'. $sliderClass . ' ' . $class .'" data-items="'.$divider.'">';
                    $html .= "\n";

                    while( $posts->have_posts() ) : $posts->the_post();
                        $postClasses = get_post_class();

                        // sp($postClasses);
                        $classes = implode(' ',$postClasses);
                        $html .= '<li class="'.$classes.'" '.$columns.'>';
                        $html .= "\n";

                        if( has_post_thumbnail($post->ID) ) :
                            $html .= '<a href="'. get_permalink($post->ID) .'">';
                            $html .= '<figure>';
                            $html .= get_the_post_thumbnail($post->ID);
                            $html .= '</figure>';
                            $html .= '</a>';
                        endif;

                        $html .= '
                            <header>
                                <'.$titletag.' class="entry-title">
                                    <a href="'. get_permalink($post->ID) .'">'. sprintf( __('%s', 'simple'), get_the_title($post->ID) ) .'</a>
                                </'.$titletag.'>
                            </header>
                        ';

                        $html .= '<p>'.truncate_text( get_the_excerpt(), 50, simple_excerpt_more() ).'</p>';

                        $html .= '
                            <footer class="entry-meta">
                                '.$post_meta.'
                            </footer>
                        ';

                        $html .= '</li>';
                        $html .= "\n";

                    endwhile;

                    if( $posts->found_posts % $divider != 0 ) :

                        for ($i=0; $i < $divider - 2; $i++) {
                            $html .= '<article class="filler" data-columns="'.$divider.'"></article>';
                            $html .= "\n";
                        }

                    endif;

                    $html .= '</ul>';
                    $html .= '</div>';

                endif;

                wp_reset_postdata();

                return $html;

            }

            add_shortcode('posts', 'simple_post_type');

        }


    }

    //  Shortcode Scripts / Styles
    function load_scripts() {
        wp_enqueue_style( 'shortcodes-css', plugins_url( 'public/css/main.css', __FILE__ ) );
        wp_enqueue_script( 'shortcodes-js', plugins_url( 'public/js/min.js', __FILE__ ), array('jquery'), '', true );
    }

}

new Simple_Shortcodes_Class;

endif;