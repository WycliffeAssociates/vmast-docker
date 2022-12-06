(function($) {
    $.fn.autosize = function(options) {
        return this.each(function() {
            const defaults = {
                minHeight: "100px"
            };

            const settings = $.extend({}, defaults, options);

            const $this = $(this);
            const textarea = this;

            const resizeObserver = new ResizeObserver((entries) => {
                setHeight();
            });

            const mutationObserver = new MutationObserver(function (mutations) {
                setHeight();
            });

            initialize();
            setupListeners();

            // -------------- Private functions ----------------- //

            function initialize() {
                document.fonts.ready.then(function() {
                    resizeObserver.observe(textarea);
                    mutationObserver.observe(textarea, {
                        subtree: false,
                        childList: false,
                        attributes: true,
                        attributeFilter: ['class'],
                        attributeOldValue: true
                    });
                    setHeight();
                });
            }

            function setupListeners() {
                $this.on("input", function() {
                    setHeight();
                });
            }

            function setHeight() {
                setTimeout(function() {
                    textarea.style.minHeight = settings.minHeight;
                    textarea.style.height = "1px";
                    textarea.style.height = textarea.scrollHeight + 0 + "px";
                }, 100);
            }
        });
    }
}(jQuery));