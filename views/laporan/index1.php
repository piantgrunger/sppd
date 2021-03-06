<?php


use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Html;
use hscstudio\mimin\components\Mimin;

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    'no_spt',
    'nama_kota',
    [
        'attribute' => 'Tanggal',
        'format' => 'html',
        'value' => function ($model) {
            return Html::tag('div', Yii::$app->formatter->asDate($model->tgl_awal).' - '.Yii::$app->formatter->asDate($model->tgl_akhir), [
                'style' => 'max-width: 350px; word-wrap:break-word;',
            ]);
        },
    ],
    [
        'attribute' => 'Uraian',
         'format' => 'html',
        'contentOptions' => ['style' => 'width:300px; white-space: normal;'],
          'value' => function ($model) {
              return Html::tag('div', " $model->untuk <br> Pada  : <br>  Hari :$model->hariCetak <br> Tanggal : $model->tanggalCetak <br> Tempat :$model->tujuan ", [
                'style' => 'word-wrap:break-word;',
            ]);
          },
    ],
    'nama_kegiatan',
    'nama_alat_kelengkapan',
    [
        'attribute' => 'Nama Personil',
        'format' => 'raw',
        'value' => function ($data) {
            $anggota = [];
            foreach ($data->detailSuratPerintahTugas as $detail) {
                $anggota[] = $detail->nama_personil;
            }

            return implode(' - ', $anggota);
        },
    ],
    [
        'attribute' => 'Lama Hari',
        'value' => 'selisih',
    ],
    ['attribute' => 'Pengeluaran',
      'format' => 'raw',
      'value' => function ($data) {
          return 'Rp  '.Yii::$app->formatter->asDecimal($data->total_realisasi);
      },
    ],
];

/* @var $this yii\web\View */
/* @var $searchModel app\models\SuratPerintahTugasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'tableOptions' => ['class' => 'table  table-bordered table-hover'],
        'options' => ['style' => 'table-layout:fixed;'],

        'striped' => false,

        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,

        'panel' => [
            'before' => $this->render('_search', ['model' => $searchModel]),
            'type' => GridView::TYPE_INFO,
            'heading' => '<i class="glyphicon glyphicon-tasks"></i>  <strong> '.Yii::t('app', 'Laporan Surat Perintah Perjalanan Dinas').'</strong>',
        ],
        'toolbar' => [
            ['content' => ((Mimin::checkRoute($this->context->id.'/cetak-rekap'))) ? Html::a(Yii::t('app', 'Rekap'), ['cetak-rekap', 'tgl_awal' => $searchModel->tgl_aw, 'tgl_akhir' => $searchModel->tgl_ak], ['class' => 'btn btn-success', 'data-pjax' => 0]) : '',
        ],
        ['content' => ((Mimin::checkRoute($this->context->id.'/cetak-kartu'))) ? Html::a(Yii::t('app', 'Kartu'), ['cetak-kartu', 'tgl_awal' => $searchModel->tgl_aw, 'tgl_akhir' => $searchModel->tgl_ak, 'alat_kelengkapan' => $searchModel->id_alat_kelengkapan], ['class' => 'btn btn-success', 'data-pjax' => 0]) : '',
    ],
            '{export}',
            '{toggleData}',
        ],

        'resizableColumns' => true,
    ]);
    ?>
    <?php Pjax::end(); ?>