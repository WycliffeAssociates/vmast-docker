(function($) {
    $.fn.markers = function(options) {
        return this.each(function() {
            const defaults = {
                inputName: "",
                inputClass: "not-set",
                inputHidden: true,
                autoSave: true,
                movableButton: false,
                initialMarker: 1,
                lastMarker: 1,
                totalMarkers: 0
            };

            const settings = $.extend({}, defaults, options);

            const $this = $(this);
            const thisNode = $this[0];

            let addButton, markerCurrent, markerTotal, textarea, parent;
            let lastFocusedIndex = 0, lastFocusedNode = thisNode.firstChild;
            let lastMarker;
            let hasHieroglyphs = false;
            let addButtonHeight = 0;
            let addButtonTopOffset = 0;
            let editorBottomOffset = 0;

            initialize();
            setupListeners();

            // -------------- Private functions ----------------- //

            function initialize() {
                $this.attr("contenteditable", true);
                parent = $this.parent();

                handleDataProps();
                createMarkerButton();
                createTextarea();

                initializeControls();
                calculateAddButtonSpecs();

                markerCurrent.text("--");
                if (settings.totalMarkers > 0) {
                    markerTotal.text(settings.lastMarker);
                } else {
                    markerTotal.text("∞");
                }

                initializeMutationObserver();

                bindDraggable();
                updateMarkers();
                updateEditor(false);

                if (settings.movableButton) {
                    moveButton();
                }
            }

            function setupListeners() {
                $this.on("click", function() {
                    if (window.getSelection) {
                        const sel = window.getSelection();
                        lastFocusedIndex = sel.focusOffset;
                        lastFocusedNode = sel.focusNode;
                    }
                });
                $this.on("keyup", function() {
                    if (window.getSelection) {
                        const sel = window.getSelection();
                        lastFocusedIndex = sel.focusOffset;
                        lastFocusedNode = sel.focusNode;
                    }
                    updateMarkers();
                    updateEditor();
                });
                $this.keydown(function(e) {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        return false;
                    }
                });
                $this.on('drop', function(event) {
                    event.preventDefault();
                    dropMarker(event.originalEvent);
                    return false;
                });
                addButton.on("click", addMarker);

                if (settings.movableButton) {
                    $(window).scroll(function () {
                        moveButton();
                    });
                }
            }

            function handleDataProps() {
                if (typeof $this.data("totalmarkers") !== "undefined") {
                    settings.totalMarkers = parseInt($this.data("totalmarkers")) || 0;
                }

                if (typeof $this.data("initialmarker") !== "undefined") {
                    settings.initialMarker = parseInt($this.data("initialmarker")) || 1;
                }

                if (typeof $this.data("lastmarker") !== "undefined") {
                    settings.lastMarker = parseInt($this.data("lastmarker")) || 1;
                } else {
                    settings.lastMarker = settings.totalMarkers;
                }
            }

            function initializeControls() {
                addButton = $(".addMarkerButton", parent);
                markerCurrent = $(".markerCurrent", parent);
                markerTotal = $(".markerTotal", parent);
                textarea = $("textarea", parent);
            }

            function initializeMutationObserver() {
                const observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        $(mutation.removedNodes).each(function() {
                            if (this.nodeType === Node.ELEMENT_NODE && this.classList.contains("bubble")) {
                                updateMarkers();
                                updateEditor();
                            }
                        });
                    });
                });
                observer.observe(thisNode, { childList: true });
            }

            function addMarker() {
                const markers = $(".bubble", parent);
                const marker = (settings.initialMarker - 1) + markers.length + 1;

                if (settings.totalMarkers > 0 && markers.length >= settings.totalMarkers) return;

                const range = document.createRange();
                const text = lastFocusedNode.textContent.substring(0, lastFocusedIndex);
                const found = text.search(/\s[\S.]*$/) + 1;

                range.setStart(lastFocusedNode, found);

                const el = document.createElement("div");
                el.innerHTML = "<div class='bubble' draggable='true' contenteditable='false'>"+marker+"</div>";

                let frag = document.createDocumentFragment(), node, lastNode;
                while ( (node = el.firstChild) ) {
                    lastNode = frag.appendChild(node);
                }
                range.insertNode(frag);

                debug(marker);

                bindDraggable();
                updateMarkers();
                updateEditor();
            }

            function dropMarker(event) {
                const txt = $(event.target).text();
                // Check if text has Chinese/Japanese/Myanmar/Lao characters and SUN
                hasHieroglyphs = /[\u0e80-\u0eff\u3040-\u309f\u30a0-\u30ff\u4e00-\u9faf\u1000-\u109f\ue000-\uf8ff]/.test(txt);

                if(!event.target) {
                    bindDraggable();
                    return false;
                }

                lastMarker.addClass('dragged');

                const content = event.dataTransfer.getData('text');
                $this.focus();

                pasteHtmlAtCaret(event, content);
                bindDraggable();

                $('.dragged', parent).remove();
            }

            function bindDraggable() {
                const bubbles = $(".bubble", parent);

                bubbles.attr("contenteditable", false).attr("draggable", true);
                bubbles.off("dragstart").on("dragstart", function(e) {
                    if (!e.target.id)
                        e.target.id = (new Date()).getTime();

                    e.originalEvent.dataTransfer.setData("text", e.target.outerHTML);

                    lastMarker = $(e.target);
                });
            }

            function pasteHtmlAtCaret(e, html) {
                let range, pasteTo, textNode, text;
                if (document.caretRangeFromPoint) {
                    range = document.caretRangeFromPoint(e.clientX, e.clientY);
                    textNode = range.startContainer;
                    pasteTo = range.startOffset;
                    text = textNode.nodeValue.substring(0, range.startOffset);
                } else {
                    range = document.createRange();
                    pasteTo = e.rangeOffset;
                    textNode = e.rangeParent;
                    text = textNode.textContent.substring(0, pasteTo);
                }

                // If there words separated with spaces, insert markers in front of words
                if (!hasHieroglyphs) {
                    pasteTo = text.search(/\s[\S.]*$/) + 1;
                }
                range.setStart(textNode, pasteTo);

                const el = document.createElement("div");
                el.innerHTML = html;

                let frag = document.createDocumentFragment(), node, lastNode;
                while ( (node = el.firstChild) ) {
                    lastNode = frag.appendChild(node);
                }
                range.insertNode(frag);
            }

            function updateMarkers() {
                const markers = $(".bubble", parent);
                const numbers = [];
                for (let i=settings.initialMarker; i <= (markers.length + settings.initialMarker); i++) {
                    numbers.push(i);
                }
                markers.each(function(i, v) {
                    $(v).text(numbers[i]);
                });

                if (markers.length > 0) {
                    markerCurrent.text((settings.initialMarker - 1) + markers.length);
                } else {
                    markerCurrent.text("--");
                }
            }

            function updateEditor(updateTextarea = settings.autoSave) {
                const contents = $this.html();
                const converted = contents.replace(/<div.*?class="bubble".*?>(\d+)<\/div>/g, "|$1| ");
                let tmp = document.createElement("DIV");
                tmp.innerHTML = converted;
                const final = tmp.textContent || tmp.innerText || "";

                textarea.val(final);
                if (updateTextarea) {
                    textarea.trigger("change");
                }
            }

            function createMarkerButton() {
                const button = document.createElement("DIV");
                button.className = "addMarkerButton";

                const addMarker = document.createElement("SPAN");
                addMarker.className = "addMarker mdi mdi-map-marker-plus";

                const counter = document.createElement("DIV");
                const current = document.createElement("SPAN");
                current.className = "markerCurrent";
                const total = document.createElement("SPAN");
                total.className = "markerTotal";

                counter.append(current, "/", total);
                button.append(addMarker, counter);

                $this.parent().prepend(button);
            }

            function createTextarea() {
                const textarea = document.createElement("TEXTAREA");
                textarea.setAttribute("name", settings.inputName);
                textarea.classList.add(settings.inputClass);
                if (settings.inputHidden) {
                    textarea.classList.add("hidden");
                }

                $this.parent().append(textarea);
            }

            function moveButton() {
                if (window.scrollY >= addButtonTopOffset && window.scrollY <= (editorBottomOffset - addButtonHeight)) {
                    addButton.addClass("unlinked");
                    $this.css("margin-top", addButtonHeight);
                } else {
                    addButton.removeClass("unlinked");
                    $this.css("margin-top", "auto");
                }
            }

            function calculateAddButtonSpecs() {
                addButtonHeight = addButton.height();
                addButtonTopOffset = addButton.offset().top;
                editorBottomOffset = $this.offset().top + $this.height();
            }
        });
    }
}(jQuery));