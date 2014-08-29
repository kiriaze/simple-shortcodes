/**
 * SimpleMedia.js
 * Version: 1.0
 * URL: getsimple.io
 * Description: Enable HTML5 <audio> and <video> data attributes for semantic modularity and fallbacks. Works brilliantly with mediaelements.js.
 * Requires: jQuery, awesomeness.
 * Author: Constantine Kiriaze [ kiriaze.com ]
 * Copyright: Copyright 2013 Constantine Kiriaze
 * License: GPL v2
 *
 * Usage:
 * <audio data-media-src="song.{mp4, ogg, mp3}" controls></audio>
 * <video data-media-src="trailer.{mp4, ogv, webm}" controls></video>
 * Call simpleMedia passing your selector: $('[data-media-src]').simpleMedia();
 */

// SimpleMedia closure wrapper
;(function($, document, window, undefined) {
    // Optional, but considered best practice by some
    "use strict";

    // Name the plugin so it's only in one place
    var simpleMedia = 'simpleMedia';

    // SimpleMedia constructor
    function SimpleMedia(element, options) {
        this.element = element;
        this.options = options;
        this.init();
    }

    SimpleMedia.prototype = {
        // Public functions accessible to users
        init: function() {
            // SimpleMedia initializer
            var mediaType = this.element.tagName.toLowerCase(),
                dataAttr = this.element.getAttribute('data-media-src'),
                mediaSource = dataAttr.match(/^([^]+)\{/)[1],
                fileExts = dataAttr.match(/\{([^]+)\}$/)[1].toString().replace(/\s/g, '').split(',');

            for (var i = 0; i < fileExts.length; i++) {
                var extension = fileExts[i],
                    source = document.createElement('source');
                source.src = mediaSource + extension;
                source.type = mediaType + '/' + extension;
                this.element.appendChild(source);
            }
        }
    };

    $.fn[simpleMedia] = function(options) {
        // Iterate through each DOM element and return it
        return this.each(function() {
            // prevent multiple instantiations
            if (!$.data(this, 'plugin_' + simpleMedia)) {
                $.data(this, 'plugin_' + simpleMedia,
                    new SimpleMedia(this, options));
            }
        });
    };

})(jQuery, document, window);