
									
									<?php
									use common\widgets\Alert;
									use yii\helpers\Html;

									 ?>
						   <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
				            <?php
				            echo \kartik\widgets\Growl::widget([
				                'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
				                'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
				                'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
				                'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
				                'showSeparator' => true,
				                'delay' => 1, //This delay is how long before the message shows
				                'pluginOptions' => [
				                    'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
				                    'placement' => [
				                        'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
				                        'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
				                    ]
				                ]
				            ]);
				            ?>
				        <?php endforeach; 

				        echo"<a onclick=\"favorite(".$catID.")\" href=\"javascript:void(0)\"  title=\"Tambah ke Favorite\"><span class=\"glyphicon glyphicon-star\"></span></a>";  
				        ?>
		