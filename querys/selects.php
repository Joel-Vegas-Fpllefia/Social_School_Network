<?php

function select($query, $mysqli)
{
    $query_testimonios = $mysqli->query($query);
    $testimonios = extract_data_query($query_testimonios);
    return $testimonios;
}


function extract_data_query($data)
{
    $final_data = $data->fetch_all(MYSQLI_ASSOC);
    return $final_data;
}
