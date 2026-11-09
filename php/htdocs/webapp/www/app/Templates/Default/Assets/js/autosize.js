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
                setHeightDelayed();
            });

            const mutationObserver = new MutationObserver(function (mutations) {
                setHeight();
                setHeightDelayed();
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
                    setHeightDelayed();
                });
            }

            function setupListeners() {
                $this.on("input", function() {
                    setHeight();
                });
                $this.on("autosize:update", function() {
                    setHeight();
                });
            }

            function setHeight() {
                textarea.style.minHeight = settings.minHeight;
                textarea.style.height = "1px";
                textarea.style.height = `${textarea.scrollHeight}px`;
            }

            /**
             * To fix an issue when fonts are not loaded and
             * scrollHeight is not calculated correctly
             * @param delay
             */
            function setHeightDelayed(delay = 500) {
                setTimeout(function() {
                    setHeight();
                }, delay);
            }
        });
    }
}(jQuery));