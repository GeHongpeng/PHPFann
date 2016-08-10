<?php
namespace ImageSimilarityComparison;

class HistogramComparisonClient {
    /**
     * 画像からカラーヒストグラムを作成する
     *
     * @param $imageFileName
     * @param bool $cache
     * @return array
     */
    public function makeHistogram($imageFileName, $cache=TRUE) {
        // 結果保存ファイルを作成
        $csvFile = preg_replace('/\.(jpg|jpeg)$/', '-his.csv', $imageFileName);
        if ($cache) {
            // ヒストグラムファイルがすでに存在する場合は取得
            if (file_exists($csvFile)) {
                $s = file_get_contents($csvFile);
                return explode(',', $s);
            }
        }

        // 対象イメージを読み込む
        $im = imagecreatefromjpeg($imageFileName);
        // イメージのサイズを取得
        $sx = imagesx($im);
        $sy = imagesy($im);

        // ピクセル数を数える
        $his = array_fill(0, 64, 0);
        for ($y = 0; $y < $sy; $y++) {
            for ($x = 0; $x < $sx; $x++) {
                $rgb = imagecolorat($im, $x, $y);
                $no = $this->rgb2no($rgb);
                $his[$no]++;
            }
        }

        // 8bitに正規化
        $pixels = $sx * $sy;
        for ($i = 0; $i < 64; $i++) {
            $his[$i] = floor(256 * $his[$i] / $pixels);
        }
        file_put_contents($csvFile, implode(',', $his));

        //
        return $his;
    }

    /**
     * RGBからヒストグラム番号へ変換を行う
     *
     * @param $rgb
     * @return int
     */
    public function rgb2no($rgb) {
        // RGBの値を取得
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        //
        $rn = floor($r / 64);
        $gn = floor($g / 64);
        $bn = floor($b / 64);
        //
        return 16 * $rn + 4 * $gn + $bn;
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
     * カラーヒストグラムから画像類似度を算出
     *
     * @param $target
     * @param $files
     * @return array
     */
    public function calcIntersection($target, $files) {
        $hisList = [];
        foreach ($files as $file) {
            // ヒストグラムファイルを作成
            $his = $this->makeHistogram($file);
            $value = 0;
            for ($i = 0; $i < count($target); $i++) {
                $value += min(intval($target[$i]), intval($his[$i]));
            }
            $hisList[] = [
                "path" => $file,
                "value" => $value,
            ];
        }
        // ソート
        usort($hisList, function($a, $b) {
            return $b['value'] - $a['value'];
        });
        //
        return $hisList;
    }
}