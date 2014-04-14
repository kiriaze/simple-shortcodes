(function() {
    tinymce.create('tinymce.plugins.SimpleShortcodes', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {

        },
 
        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        // createControl : function(n, cm) {
        createControl: function ( btn, e ) {
            
            if ( btn == "simple_button" ) {
                
                var s = this;
                
                // creates the button
                var btn = e.createSplitButton('simple_button', {
                    title: "Insert Bean Shortcode", // title of the button
                    image: SimpleShortcodes.plugin_folder +"/tinymce/images/icon5.png", // path to the button's image
                    icons: false
                });
                
                //Render a DropDown Menu
                btn.onRenderMenu.add(function (c, b) {  

                    s.addNow( b, "Button", "<br>[button url='' target='' modal_id='' color='' size='' type='' icon='']Place your text here.[/button]<br>" );
                    
                    s.addNow( b, "Lightbox", "[lightbox link_text='Lightbox' type='' url='' effect='mfp-fade-in-up']Place your text here.[/lightbox]" );
                    s.addNow( b, "Callout", "[callout title='Example' icon='fa fa-search' size='' bg='' color='' stacked='' align='']Place your text here.[/callout]" );

                    s.addNow( b, "Accordions", "[accordion title='Title' state='closed']Place your text here.[/accordion][accordion title='Title' state='open']Place your text here.[/accordion]" );
                    s.addNow( b, "Toggles", "[toggle title='Title' state='closed']Place your text here.[/toggle][toggle title='Title' state='open']Place your text here.[/toggle]" );
                    s.addNow( b, "Alerts", "[alert class='info']Place your text here.[/alert]" );
                    s.addNow( b, "Pre", "[pre]Place your code here.[/pre]" );

                    // Use both grid & columns, or just one? which one?
                    s.addNow( b, "Grid", "[grid]<br />[column span='1']Place your content here.[/column]<br />[/grid]" );
                    // s.addNow( b, "Columns", "[grid]<br />[column span='2']Place your content here.[/column]<br />[column span='2']Place your content here.[/column]<br />[/grid]" );

                    s.addNow( b, "Tabs", '[tabs]<br/>[tab title="tab"]Place your content here.[/tab]<br />[tab title="another tab"]Place your content here.[/tab]</br>[/tabs]' );
                    
                    s.addNow( b, "Video", "[video src=''][/video]" );
                    s.addNow( b, "Audio", "[audio src=''][/audio]" );
                    
                    s.addNow( b, "Icons", "[icon class='fi-social-facebook' size='' color='' /]" );
                    s.addNow( b, "Map", "[map latitude='' longitude='' width='' height='' zoom='' infowindow_text='']" );
                    s.addNow( b, "Lists", "[list]<li></li>[/list]" );

                    s.addNow( b, "Packages", '[packages]<br/>[package title="Basic" featured="false" price="$20" time="month" signup=""]Place your unordered list here.[/package]<br />[package title="Basic" featured="true" price="$20" time="month" signup="Alt Text"]Place your unordered list here.[/package]<br />[package title="Premium" featured="false" price="$100" time="month" signup=""]Place your unordered list here.[/package]<br />[/packages]' );

                    s.addNow( b, "Section", "[section short='' overlay='' color='' ratio='' bg='' stellar='' padding=''][/section]" );

                    s.addNow( b, "Stats", "[stats]<br/>[stat total='']Content[/stat]<br/>[/stats]" );

                    s.addNow( b, "Client List", "[clients type='' class=''][/clients]" );
                    s.addNow( b, "Post Carousel", "[posts type='']" );
                    
                    s.addNow( b, "Br", "[br/]" );
                    s.addNow( b, "Hr", "[hr/]" );
                    s.addNow( b, "Bloginfo", "[bloginfo key='' /]" );
                    s.addNow( b, "Raw", "[raw][/raw]" );
                       
                });
                
                return btn;
            }

            return null;
        },
 

        //Insert shortcode into content
        addNow: function ( ed, title, sc) {
            ed.add({
                title: title,
                onclick: function () {
                    tinyMCE.activeEditor.execCommand( "mceInsertContent", false, sc )
                }
            })
        },


        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname  : 'Simple Shortcodes Buttons',
                author    : 'Constantine Kiriaze',
                authorurl : 'http://kiriaze.com',
                infourl   : '',
                version   : '1'
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'SimpleShortcodes', tinymce.plugins.SimpleShortcodes );
})();