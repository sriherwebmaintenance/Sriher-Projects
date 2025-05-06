<?php 
function compareImages($image1Path, $image2Path) {
    // Check if paths are empty
    if (empty($image1Path) || empty($image2Path)) {
        return FALSE; // One or both image paths are empty
    }

    // Check if files exist
    if (!file_exists($image1Path) || !file_exists($image2Path)) {
        return FALSE; // One or both files do not exist
    }

    // Load images
    $image1 = @imagecreatefromjpeg($image1Path);
    $image2 = @imagecreatefromjpeg($image2Path);

    if ($image1 === FALSE || $image2 === FALSE) {
        return FALSE; // Error loading images
    }

    // Get image dimensions
    $width1 = imagesx($image1);
    $height1 = imagesy($image1);
    $width2 = imagesx($image2);
    $height2 = imagesy($image2);

    // Check if either image is empty (0 width or height)
    if ($width1 == 0 || $height1 == 0 || $width2 == 0 || $height2 == 0) {
        imagedestroy($image1);
        imagedestroy($image2);
        return FALSE; // One or both images are empty
    }

    // Check if images are of different sizes
    if ($width1 != $width2 || $height1 != $height2) {
        imagedestroy($image1);
        imagedestroy($image2);
        return FALSE; // Images are of different sizes
    }

    // Compare pixel by pixel
    $diffCount = 0;
    for ($y = 0; $y < $height1; $y++) {
        for ($x = 0; $x < $width1; $x++) {
            $color1 = imagecolorat($image1, $x, $y);
            $color2 = imagecolorat($image2, $x, $y);

            if ($color1 !== $color2) {
                $diffCount++;
            }
        }
    }

    imagedestroy($image1);
    imagedestroy($image2);

    return $diffCount == 0;
}


 ?>
