<?php if ($media): ?>
    <style>
        #media_waveform {
            margin: 20px 0;
            border: 1px solid #ccc;
            border-left-width: 0;
            border-right-width: 0;
        }

        #media_waveform_container {
            height: 200px;
        }

        #media_waveform.detached {
            position: fixed;
            top: 0;
            background-color: white;
            z-index: 10;
            border: 2px solid #4d4d4d;
            padding: 0;
        }

        .marker {
            width: 30px;
            height: 20px;
            background-color: #006ffd;
            color: white;
            display: flex;
            justify-content: center;
            border-radius: 10px;
            z-index: 10;
        }
    </style>

    <div id="media_waveform_container">
        <div id="media_waveform"></div>
    </div>

    <script src="<?php echo template_url("js/wavesurfer.min.js?v=1")?>"></script>
    <script src="<?php echo template_url("js/wavesurfer.markers.min.js?v=1")?>"></script>
    <script src="<?php echo template_url("js/wavesurfer.cursor.min.js?v=1")?>"></script>

    <script>
        const mediaUrl = "<?php echo template_url($media->getAudioUrl()); ?>";
        const mediaMarkers = [];

        <?php foreach ($media->getCueData()->getTracks() as $track): ?>
        mediaMarkers.push({
            label: "<?php echo $track->getNumber(); ?>",
            position: "<?php echo $track->getPosition(); ?>"
        });
        <?php endforeach; ?>

        $(document).ready(function() {
            const waveForm = $("#media_waveform");
            const container = $("#media_waveform_container");
            const containerTop = waveForm.offset().top;
            let containerWidth = container.width();

            const wavesurfer = WaveSurfer.create({
                container: waveForm[0],
                mediaControls: true,
                normalize: true,
                scrollParent: true,
                fillParent: false,
                backend: "MediaElement",
                plugins: [
                    WaveSurfer.markers.create({
                        markerWidth: 30,
                        markerHeight: 20
                    }),
                    WaveSurfer.cursor.create({
                        showTime: true,
                        opacity: 1,
                        customShowTimeStyle: {
                            "background-color": "#000",
                            color: "#fff",
                            padding: "2px",
                            "font-size": "10px"
                        }
                    })
                ]
            });

            wavesurfer.load(mediaUrl);
            wavesurfer.on("ready", function() {
                mediaMarkers.forEach(function(item) {
                    const div = document.createElement("div");
                    div.innerText = item.label;
                    div.classList.add("marker");
                    const marker = {
                        time: item.position,
                        markerElement: div
                    };
                    wavesurfer.markers.add(marker);
                });
            });
            wavesurfer.on("marker-click", function(e) {
                /*wavesurfer.play(e.time);*/
            });
            wavesurfer.on("loading", function(progress) {
                debug(progress);
            });

            /*$(document).keydown(function(e) {
                /!* SpaceBar pressed *!/
                if (e.keyCode === 32) {
                    if (wavesurfer.isPlaying()) {
                        wavesurfer.pause();
                    } else {
                        wavesurfer.play();
                    }
                    return false;
                }
            });*/

            /*$(window).scroll(function() {
                const scroll = $(this).scrollTop();
                if (scroll >= containerTop) {
                    waveForm.addClass("detached");
                    waveForm.width(containerWidth);
                } else {
                    waveForm.removeClass("detached");
                }
            });*/

            $(window).resize(function() {
                containerWidth = container.width();
                waveForm.width(containerWidth);
            });
        });
    </script>
<?php endif; ?>
