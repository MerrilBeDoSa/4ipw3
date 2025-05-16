<?php

/**
 * Récupère les données du sponsor depuis le JSON distant.
 *
 * @return array|null
 */
function get_sponsor_content()
{
    $url = "http://playground.burotix.be/adv/banner_for_isfce.json";
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    return $data['banner_4IPDW'] ?? null;
}
