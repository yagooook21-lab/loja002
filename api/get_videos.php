<?php
header('Content-Type: application/json');
require_once("db.php");

$videos = array();

$sql = "SELECT codigo, nome, valor, valor_original, desconto, img, img1, img2, img3, img4, img5, img6 FROM produto WHERE status != 'inativo' OR status IS NULL OR status = ''";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $imgs = array(
            $row['img'],
            $row['img1'],
            $row['img2'],
            $row['img3'],
            $row['img4'],
            $row['img5'],
            $row['img6']
        );
        
        $has_video = false;
        $video_url = "";
        $video_type = "";
        
        foreach ($imgs as $img) {
            if (empty($img)) continue;
            
            if (strpos($img, 'youtube.com/embed') !== false) {
                $video_url = $img;
                $video_type = "youtube";
                $has_video = true;
                break;
            } else if (strpos($img, 'm3u8_video|') === 0) {
                $parts = explode('|', $img);
                if (count($parts) >= 2) {
                    $video_url = $parts[1];
                    $video_type = "m3u8";
                    $has_video = true;
                }
                break;
            }
        }
        
        if ($has_video) {
            $videos[] = array(
                "codigo" => $row['codigo'],
                "nome" => $row['nome'],
                "valor" => floatval(str_replace(',', '.', str_replace('.', '', $row['valor']))),
                "valor_original" => floatval(str_replace(',', '.', str_replace('.', '', !empty($row['valor_original']) ? $row['valor_original'] : $row['valor']))),
                "desconto" => $row['desconto'],
                "video_url" => $video_url,
                "video_type" => $video_type,
                "capa" => $row['img'] // Use the first image as cover/poster if needed
            );
        }
    }
}

echo json_encode($videos);
?>
