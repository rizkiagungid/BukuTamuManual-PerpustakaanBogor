<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CollectionSearch $searchModel
 */

switch ($for) {
    case 'katalog':
        $title = Yii::t('app', 'Daftar Katalog');
        $datacheckbox = array(
            'OPAC1'=>yii::t('app','Tampil di OPAC'),
            'OPAC0'=>yii::t('app','Jangan tampil di OPAC'),
            'KARTU'=>yii::t('app','Cetak Kartu Katalog'),
            'KERANJANG1'=>yii::t('app','Masukan ke Keranjang Katalog'),
            'KARANTINA'=>yii::t('app','Karantina Data'),
            'EXPORT'=>yii::t('app','Export Data Katalog Terpilih'),
            'EXPORTALL'=>yii::t('app','Export Semua Katalog'));
        $template = '<div class="btn-group-vertical">{detail}</div>';
        $urlAdvanceSearch = '_searchAdvanced';
        break;
    case 'keranjang':
        $title = Yii::t('app', 'Keranjang Katalog');
        $datacheckbox = array(
            'OPAC1'=>yii::t('app','Tampil di OPAC'),
            'OPAC0'=>yii::t('app','Jangan tampil di OPAC'),
            'KARTU'=>yii::t('app','Cetak Kartu Katalog'),
            'KERANJANG0'=>yii::t('app','Hapus dari Keranjang Katalog'),
            'KARANTINA'=>yii::t('app','Karantina Data'));
        $template= '<div class="btn-group-vertical">{detail}</div>';
        $urlAdvanceSearch = '_searchAdvanced';
        break;
    case 'karantina':
        $title = Yii::t('app', 'Karantina Katalog');
        $datacheckbox = array('DELETE_PERMANENT'=>yii::t('app','Hapus Permanen'));
        $template='<div class="btn-group-vertical">{restore}</div>';
        $urlAdvanceSearch = '_searchAdvancedKarantina';
        break;
    
    default:
        # code...
        break;
}
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengkatalogan'), 'url' => Url::to(['/katalog'])];
$this->params['breadcrumbs'][] = $this->title;
echo  '&nbsp;' . Html::a(Yii::t('app', 'Tambah'), ['create?for=cat&rda=0'], ['class' => 'btn btn-primary btn-sm']);
echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), ['site/index'], ['class' => 'btn btn-success btn-sm']);
?>
<style type="text/css">
  .standard-error-summary
  {
    background-color: #faffe1;
    padding: 5px;
    border:dashed 1px #cccccc;
    margin-bottom: 10px;
    font-size: 12px;
    margin: 10px;
  }
</style>

<div class="collections-index">
 <?php  echo $this->render($urlAdvanceSearch, ['model' => $searchModel,'rules' => $rules,'for'=>'cat']); ?>


    <?php 
    Pjax::begin(['id' => 'myGridviewKatalog']); echo GridView::widget([
        'id'=>'myGridKatalog',
        'pjax'=>true,
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>Yii::t('app', 'Showing :'),
                        'labelOptions'=>[
                            'class'=>'col-sm-4 control-label',
                            'style'=>[
                                'width'=> '75px',
                                'margin'=> '0px',
                                'padding'=> '0px',
                            ]

                        ],
                        // gridview dengan if
                        'sizes'=>(Yii::$app->config->get('language') != 'en' ? Yii::$app->params['pageSize'] : Yii::$app->params['pageSize_ing']),
                        'options'=>[
                            'id'=>'aa',
                            'class'=>'form-control'
                        ]
                    ]
                 )

            ],

            //'{toggleData}',
            '{export}',
        ],
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return [
                        'value' => $searchModel->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP,
                // 'hidden'=> ($for=='karantina') ? true : false
                'hidden'=> false
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'IsRDA',
                'class' => '\kartik\grid\BooleanColumn',
                'trueIcon'=>'<span class="label label-success">RDA&nbsp;&nbsp;&nbsp;</span>',
                'falseIcon'=>'<span class="label label-primary">AACR</span>',
            ],
            [
                'attribute'=>'BIBID',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],
            /*[
                'attribute'=>'Title',
                //'value'=>'source.Name',
                'format' => 'raw',
            ],*/
            [
                         //'label'=>'Nama',
                         'format'=>'raw',
                         'attribute'=>'Title',
                         'value' => function($data) use ($for){
                             if($for=='karantina')
                             {
                                $url = Url::to(['viewkarantina','id'=>$data->ID,'edit'=>'t']);
                             }else{
                                $url = Url::to(['update','for' => 'cat','rda' => (int)$data->IsRDA,'id'=>$data->ID,'edit'=>'t']);
                             }
                             
                             return Html::a($data->Title, $url); 
                         }
            ],
            'Edition',
            [
            'attribute'=>'Publikasi',
            'label'=>yii::t('app','Publikasi'),
            ],
            [   
                'attribute'=>'PhysicalDescription',
                'value' => function($data){
                    $data = preg_replace('/(\$a)/', '',  preg_replace('/(?<=\d)(?=[a-z])/i', ' ', $data->PhysicalDescription));
                    return preg_replace('/(\$\w)/', ' ',  $data);
                    // return $data->PhysicalDescription;
                }
                ,
                'format' => 'raw',
            ],
            'Subject',
            'CallNumber',
            [
                'attribute'=>'KontenDigital',
                'label'=>yii::t('app','Konten Digital'),
                'value'=>function($model) use ($for) {
                    if($for=='karantina')
                    {
                        return 0;
                    }else{
                        return $model->getCatalogfiles()->count();
                    }
                    
                },
                'hidden'=> ($for=='karantina') ? true : false,
                'contentOptions'=>['style'=>'width: 150px;text-align:right;'],
            ],
            [
                'attribute'=>'Eksemplar',
                'value'=>function($model) use ($for) {
                    if($for=='karantina')
                    {
                        return 0;
                    }else{
                        return $model->getCollections()->count();
                    }
                },
                'hidden'=> ($for=='karantina') ? true : false,
                'contentOptions'=>['style'=>'width: 150px;text-align:right;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $template,
                'buttons' => [
				'detail' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-book"></span> '.Yii::t('app', 'Detail'), Yii::$app->urlManager->createUrl(['/katalog/detail','id' => $model->ID]), [
                                                    'title' => Yii::t('app', 'Detail'), 
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},
                'restore' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-retweet"></span> '.Yii::t('app', 'Restore'), Yii::$app->urlManager->createUrl(['/katalog/restore','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Restore'),
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-success btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to restore this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},
												

                ],
            ],
        ],
        'summary' => Yii::t('app', 'Showing')." <b>{begin}</b> - <b>{end}</b> ".Yii::t('app', 'of')." ".$summarydesc,
        //'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        //'hover'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            /*'before'=>'<div style=width:70% class=row><div class=col-sm-1>Aksi</div><div class=col-sm-3>'
                        .Select2::widget([
                            'id' => 'cbActioncheckbox',
                            'name' => 'cbActioncheckbox',
                            'data' => array(
                                    'OPAC1'=>'Tampil di OPAC',
                                    'OPAC0'=>'Jangan tampil di OPAC',
                                    'KERANJANG'=>'Masukan ke Keranjang Katalog',
                                    'KARANTINA'=>'Karantina Data'),
                            'size'=>'sm',
                        ])
                        .'</div><div class=col-sm-1>'
                        .Html::button('<i class="glyphicon glyphicon-check"></i> Proses', [
                                            'id'=>'btnCheckprocess',
                                            'class' => 'btn btn-primary btn-sm', 
                                            'title' => 'Proses', 
                                            //'data-toggle' => 'tooltip'
                                        ])
                        .'</div></div>',*/
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
<input type="hidden" id="hdnUrlProsesCetakKartu" value="<?=Yii::$app->urlManager->createUrl(["/katalog/cetak-kartu-proses"])?>">
<input type="hidden" id="hdnUrlProsesDownloadKatalog" value="<?=Yii::$app->urlManager->createUrl(["/katalog/download-katalog"])?>">
<input type="hidden" id="hdnUrlProsesDownloadKatalogAll" value="<?=Yii::$app->urlManager->createUrl(["/katalog/download-katalog-all"])?>">

<?php

    $this->registerJs(' 

    $(document).ready(function(){
    $(\'#btnDownload\').click(function(){
        var CekAction = $(\'#cbActioncheckbox\').val();
        var CekActionDetail = $(\'#cbActionDetail\').val();
        var ids = $(\'#myGridKatalog\').yiiGridView(\'getSelectedRows\');
        if(ids.length == 0){
            alertSwal(\'Harap pilih data katalog.\',\'error\',\'2000\');
            return;
        }

        if(CekAction === \'EXPORT\'){
            var arrayId = {ids} 
            var ids = jQuery.param(arrayId);
            var url =  $(\'#hdnUrlProsesDownloadKatalog\').val();
            window.location.href = url+\'?actionid=\'+CekActionDetail+\'&\'+ids;
        
        }
    });

    $(\'#btnDownloadall\').click(function(){
        var CekActionDetail = $(\'#cbActionDetail\').val();
        var ids = $(\'#myGridKatalog\').yiiGridView(\'getSelectedRows\');
        
        // var arrayId = {ids} 
        // var ids = jQuery.param(arrayId);
        var url =  $(\'#hdnUrlProsesDownloadKatalogAll\').val();
        window.location.href = url+\'?actionid=\'+CekActionDetail;
        
    });

    $(\'#btnCheckprocess\').click(function(){
        var CekAction = $(\'#cbActioncheckbox\').val();
        var CekActionDetail = $(\'#cbActionDetail\').val();
        var ids = $(\'#myGridKatalog\').yiiGridView(\'getSelectedRows\');
        if(ids.length == 0){
            alertSwal(\'Harap pilih data katalog.\',\'error\',\'2000\');
            return;
        }

        if(CekAction === \'KARTU\')
        {
            var arrayId = {ids} 
            var ids = jQuery.param(arrayId);
            var url =  $(\'#hdnUrlProsesCetakKartu\').val();
            window.location.href = url+\'?idcardformat=\'+CekActionDetail+\'&\'+ids;
        }
        if (CekAction === \'DELETE_PERMANENT\')
            {
                swal(
                {   
                  title: "Apakah anda yakin?",   
                  text: "akan menghapus data ini",   
                  type: "warning",   
                  showCancelButton: true,   
                  closeOnConfirm: false,   
                  confirmButtonColor: "#DD6B55",   
                  confirmButtonText: "Hapus!",
                  cancelButtonText: "Batal",
                }, 
                function(){   
                    $.ajax({
                        type: \'POST\',
                        url : "'.Yii::$app->urlManager->createUrl(["/katalog/checkbox-process"]).'",
                        data : {row_id: ids, action: CekAction, actionid : CekActionDetail},
                        success : function(response) {
                          $(\'#checkError\').html(response);
                          $.pjax.reload({container:"#myGridviewKatalog",async:false});  //Reload GridView
                          alertSwal(\'Data terpilih berhasil diproses.\',\'success\',\'2000\');
                        }
                    });
                });
            }else{
            isLoading=true;
            if (CekAction === \'KARANTINA\')
            {
                swal(
                {   
                  title: "Apakah anda yakin?",   
                  text: "akan memindahkan data ini ke karantina",   
                  showCancelButton: true,   
                  closeOnConfirm: false,   
                  showLoaderOnConfirm: true,
                  confirmButtonColor: "#DD6B55",   
                  confirmButtonText: "OK, Karantinakan!",
                  cancelButtonText: "Tidak",  
                }, 
                function(){   
                    $.ajax({
                        type: \'POST\',
                        url : "'.Yii::$app->urlManager->createUrl(["/katalog/checkbox-process"]).'",
                        data : {row_id: ids, action: CekAction, actionid : CekActionDetail},
                        success : function(response) {
                            if(response!=null)
                            {
                            $(\'#checkError\').html(response);alertSwal(\'Data terpilih berhasil diproses.\',\'warning\',\'2000\');
                            }
                        }
                    });
                });
            }else{
                $.ajax({
                    type: \'POST\',
                    url : "'.Yii::$app->urlManager->createUrl(["/katalog/checkbox-process"]).'",
                    data : {row_id: ids, action: CekAction, actionid : CekActionDetail},
                    success : function(response) {
                      $.pjax.reload({container:"#myGridviewKatalog",async:false});  //Reload GridView
                      alertSwal(\'Data terpilih berhasil diproses.\',\'success\',\'2000\');
                    }
                });
            }
        }
        

    });
    });', \yii\web\View::POS_READY);

?>
