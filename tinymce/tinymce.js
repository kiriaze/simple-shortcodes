(function() {

    tinymce.PluginManager.add('simple_button', function( editor, url ) {
        editor.addButton( 'simple_button', {
            text: '',
            icon: 'icon',
            type: 'menubutton',
            menu: [
                {
                    text: 'Button',
                    onclick: function() {
                        editor.insertContent("[button url='' target='' modal_id='' color='' size='' type='' icon='']Place your text here.[/button]");
                    }
                },
                {
                    text: 'Lightbox',
                    onclick: function() {
                        editor.insertContent("[lightbox link_text='Lightbox' type='' url='' effect='mfp-fade-in-up']Place your text here.[/lightbox]");
                    }
                },
                {
                    text: 'Callout',
                    onclick: function() {
                        editor.insertContent("[callout title='Example' icon='fa fa-search' size='' bg='' color='' stacked='' align='']Place your text here.[/callout]");
                    }
                },
                {
                    text: 'Accordions',
                    onclick: function() {
                        editor.insertContent("[accordion title='Title' state='closed']Place your text here.[/accordion][accordion title='Title' state='open']Place your text here.[/accordion]");
                    }
                },
                {
                    text: 'Toggles',
                    onclick: function() {
                        editor.insertContent("[toggle title='Title' state='closed']Place your text here.[/toggle][toggle title='Title' state='open']Place your text here.[/toggle]");
                    }
                },
                {
                    text: 'Alerts',
                    onclick: function() {
                        editor.insertContent("[alert class='info']Place your text here.[/alert]");
                    }
                },
                {
                    text: 'Pre',
                    onclick: function() {
                        editor.insertContent("[pre]Place your code here.[/pre]");
                    }
                },
                {
                    text: 'Grid',
                    onclick: function() {
                        editor.insertContent("[grid]<br />[column span='1']Place your content here.[/column]<br />[/grid]");
                    }
                },
                {
                    text: 'Tabs',
                    onclick: function() {
                        editor.insertContent("[tabs]<br/>[tab title='tab']Place your content here.[/tab]<br />[tab title='another tab']Place your content here.[/tab]</br>[/tabs]");
                    }
                },
                {
                    text: 'Video',
                    onclick: function() {
                        editor.insertContent("[video src=''][/video]");
                    }
                },
                {
                    text: 'Audio',
                    onclick: function() {
                        editor.insertContent("[audio src=''][/audio]");
                    }
                },
                {
                    text: 'Icons',
                    onclick: function() {
                        editor.insertContent("[icon class='fi-social-facebook' size='' color='' /]");
                    }
                },
                {
                    text: 'Map',
                    onclick: function() {
                        editor.insertContent("[map latitude='' longitude='' width='' height='' zoom='' infowindow_text='']");
                    }
                },
                {
                    text: 'Lists',
                    onclick: function() {
                        editor.insertContent("[list type='check']<li></li>[/list]");
                    }
                },
                {
                    text: 'Packages',
                    onclick: function() {
                        editor.insertContent("[packages][package title='Basic' featured='false' price='$20' time='month' signup='']Place your unordered list here.[/package][package title='Basic' featured='true' price='$20' time='month' signup=''Alt Text']Place your unordered list here.[/package][package title='Premium' featured='false' price='$100' time='month' signup='']Place your unordered list here.[/package][/packages]");
                    }
                },
                {
                    text: 'Section',
                    onclick: function() {
                        editor.insertContent("[section overlay='true|false' color='#000|#000000' ratio='' bg='path' position='center|left|bottom|top|right' cover='true|false' stellar='true|false' padding='no-paddding|no-padding-top|no-padding-bottom' margin='' class='dark|text-align-center|space separated' video='path'][/section]");
                    }
                },
                {
                    text: 'Stats',
                    onclick: function() {
                        editor.insertContent("[stats]<br/>[stat total='']Content[/stat]<br/>[/stats]");
                    }
                },
                {
                    text: 'Client List',
                    onclick: function() {
                        editor.insertContent("[clients type='' class=''][/clients]");
                    }
                },
                {
                    text: 'Post Carousel',
                    onclick: function() {
                        editor.insertContent("[posts type='']");
                    }
                },
                {
                    text: 'Hr',
                    onclick: function() {
                        editor.insertContent("[hr/]");
                    }
                },
                {
                    text: 'Br',
                    onclick: function() {
                        editor.insertContent("[br/]");
                    }
                },
                {
                    text: 'Bloginfo',
                    onclick: function() {
                        editor.insertContent("[bloginfo key='' /]");
                    }
                },
                {
                    text: 'Raw',
                    onclick: function() {
                        editor.insertContent("[raw][/raw]");
                    }
                },
                {
                    text: 'Item 1',
                    menu: [
                        {
                            text: 'Sub Item 1',
                            onclick: function() {
                                editor.insertContent('WPExplorer.com is awesome!');
                            }
                        }
                    ]
                },
                {
                    text: 'Item 2',
                    menu: [
                        {
                            text: 'Sub Item 1',
                            onclick: function() {
                                editor.insertContent('WPExplorer.com is awesome!');
                            }
                        },
                        {
                            text: 'Sub Item 2',
                            onclick: function() {
                                editor.insertContent('WPExplorer.com is awesome!');
                            }
                        }
                    ]
                },
                {
                    text: 'Pop-Up',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Insert Random Shortcode',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'textboxName',
                                    label: 'Text Box',
                                    value: '30'
                                },
                                {
                                    type: 'textbox',
                                    name: 'multilineName',
                                    label: 'Multiline Text Box',
                                    value: 'You can say a lot of stuff in here',
                                    multiline: true,
                                    minWidth: 300,
                                    minHeight: 100
                                },
                                {
                                    type: 'listbox',
                                    name: 'listboxName',
                                    label: 'List Box',
                                    'values': [
                                        {text: 'Option 1', value: '1'},
                                        {text: 'Option 2', value: '2'},
                                        {text: 'Option 3', value: '3'}
                                    ]
                                }
                            ],
                            onsubmit: function( e ) {
                                editor.insertContent( '[random_shortcode textbox="' + e.data.textboxName + '" multiline="' + e.data.multilineName + '" listbox="' + e.data.listboxName + '"]');
                            }
                        });
                    }
                }
            ]
        });
    });

})();