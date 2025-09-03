<?php

function get_all_genres() {
    return db_select("SELECT * FROM genres ORDER BY name ASC");
}