<?php
namespace ImageSimilarityComparison;

/**
 * Class AHashComparisonClient
 * @package ImageSimilarityComparison
 */
class AHashComparisonClient {
    /**
     * @param $imageFileName
     * @param bool $cache
     * @return string
     */
    public function  makeAHash($imageFileName, $cache=TRUE) {
        // 結果保存ファイルを作成
        $hashFile = preg_replace('/\.(jpg|jpeg)$/', '.hash', $imageFileName);
        if ($cache) {
            // ハッシュファイルがすでに存在する場合は取得
            if (file_exists($hashFile)) {
                $v = file_get_contents($hashFile);
                return $v;
            }
        }

        // 16×16にリサイズ
        $sz = 16;
        $src = imagecreatefromjpeg($imageFileName);
        // イメージのサイズを取得
        $sx = imagesx($src);
        $sy = imagesy($src);
        $des = imagecreatetruecolor($sz, $sz);
        // リサイズを実施
        imagecopyresized($des, $src, 0, 0, 0, 0, $sz, $sz, $sx, $sy);
        // 元イメージを消す
        imagedestroy($src);

        // グレイスケールに変換
        imagefilter($des, IMG_FILTER_GRAYSCALE);

        // 平均値を算出し、配列に入れておく
        $pix = [];
        $sum = 0;
        for ($y = 0; $y < $sz; $y++) {
            for ($x = 0; $x < $sz; $x++) {
                $rgb = imagecolorat($des, $x, $y);
                $b = $rgb & 0xFF;
                $sum += $b;
                $pix[] = $b;
            }
        }
        $ave = floor($sum / ($sz * $sz));

        // 2値化を実施
        $hash = '';
        foreach ($pix as $i => $v) {
            $hash .= ($v > $ave) ? '1' : '0';
            if ($i % 16 == 15) {
                $hash .= "\n";
            }
        }

        //
        file_put_contents($hashFile, $hash);
        return $hash;
    }

    /**
     * 指定フォルダの下にあるJPEGファイルを取得
     *
     * @param $path
     * @return array
     */
    public function enumJpeg($path) {
        $files = [];
        $fs = scandir($path);
        foreach ($fs as $f) {
            //
            if (substr($f, 0, 1) == ".") {
                continue;
            }
            //
            $fullPath = $path . $f;
            if (is_dir($fullPath)) {
                $files = array_merge($files, $this->enumJpeg($fullPath));
                continue;
            }
            //
            if (!preg_match('/\.(jpg|jpeg)$/i', $f)) {
                continue;
            }
            //
            $files[] = $fullPath;
        }
        //
        return $files;
    }

    /**
     * ハッシュから画像類似度を算出
     *
     * @param $target
     * @param $files
     * @return array
     */
    public function checkHash($target, $files) {
        $list = [];
        foreach ($files as $file) {
            // ハッシュファイルを作成
            $hash = $this->makeAHash($file);
            $match = 0;
            for ($i = 0; $i < strlen($target); $i++) {
                if (substr($target, $i, 1) == substr($hash, $i, 1)) {
                    $match++;
                }
            }
            //
            $list[] = [
                "path" => $file,
                "value" => $match,
            ];
        }
        // ソート
        usort($list, function($a, $b) {
            return $b['value'] - $a['value'];
        });
        //
        return $list;
    }
}