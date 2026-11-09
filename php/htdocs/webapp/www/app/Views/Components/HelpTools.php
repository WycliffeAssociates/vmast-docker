<?php

function renderTq($language) {
    if ($language) {
        echo '<button class="btn btn-primary ttools" data-tool="tq">'.__("show_questions").'</button>';
    }
}

function renderTw($language) {
    if ($language) {
        echo '<button class="btn btn-primary ttools" data-tool="tw">'.__("show_keywords").'</button>';
    }
}

function renderTn($language) {
    if ($language) {
        echo '<button class="btn btn-primary ttools" data-tool="tn">'.__("show_notes").'</button>';
    }
}

function renderBc($language) {
    if ($language) {
        echo '<button class="btn btn-primary ttools" data-tool="bc">'.__("show_bible_commentaries").'</button>';
    }
}

function renderRubric($targetLanguage = null, $force = true) {
    if (($targetLanguage && !str_contains($targetLanguage, "sgn")) || $force) {
        echo '<button class="btn btn-primary ttools" data-tool="rubric">'.__("show_rubric").'</button>';
    }
}

function renderSailDict($targetLanguage = null, $force = true) {
    if (($targetLanguage && str_contains($targetLanguage, "sgn")) || $force) {
        echo '<button class="btn btn-warning ttools" data-tool="saildict">'.__("show_dictionary").'</button>';
    }
}

function renderSunBible() {
    echo '<button class="btn btn-primary ttools" data-tool="sunbible">'.__("go_sun_bible").'</button>';
}
