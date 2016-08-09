<?php
// FANNを生成
$num_layers = 3;
$num_input = 2;
$num_neuros_hidden = 3;
$num_output  = 1;
$ann = fann_create_standard($num_layers, $num_input, $num_neuros_hidden, $num_output);
if (!$ann) { die("FANNの初期化に失敗"); }

// パラメータを設定
fann_set_activation_function_hidden($ann, FANN_SIGMOID_SYMMETRIC);
fann_set_activation_function_output($ann, FANN_SIGMOID_SYMMETRIC);

// 学習する
$desired_error = 0.00001;
$max_epochs = 500000;
$epochs_between_reports = 1000;
fann_train_on_file($ann, "./fann-xor.dat", $max_epochs, $epochs_between_reports, $desired_error);
fann_save($ann, "./fann-xor.net");

// 学習したデータをテスト
echo "学習結果をテスト:\n";
$xor_pattern = [[1, 1], [1, 0], [0, 1], [0, 0]];
foreach ($xor_pattern as $t) {
    $r = fann_run($ann, $t);
    $v = round($r[0]);
    printf("%d %d => %d (%f)\n", $t[0], $t[1], $v, $r[0]);
}
fann_destroy($ann);